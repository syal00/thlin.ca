<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = false;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'admin.name' => 'Site Administrator',
            'admin.email' => 'admin@thlin.local',
            'admin.password' => 'Security123!',
            'admin.max_users' => 2,
        ]);

        $this->seed(AdminUserSeeder::class);
        User::query()->update(['must_change_password' => false]);
    }

    private function primaryAdmin(): User
    {
        return User::where('is_primary', true)->firstOrFail();
    }

    public function test_public_registration_is_not_available(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_guests_cannot_manage_admin_users(): void
    {
        $this->get(route('admin.users.index'))->assertRedirect(route('admin.login'));
    }

    public function test_non_primary_admin_cannot_manage_admin_users(): void
    {
        $secondaryAdmin = User::factory()->create([
            'email' => 'secondary.admin@example.com',
            'must_change_password' => false,
            'is_primary' => false,
        ]);

        $this->actingAs($secondaryAdmin)
            ->get(route('admin.users.index'))
            ->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('error');

        $this->actingAs($secondaryAdmin)
            ->post(route('admin.users.store'), [
                'name' => 'Third Administrator',
                'email' => 'third.admin@example.com',
            ])
            ->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('error');
    }

    public function test_primary_admin_can_create_a_second_admin_with_default_password(): void
    {
        $admin = $this->primaryAdmin();

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Second Administrator',
                'email' => 'second.admin@example.com',
            ])
            ->assertRedirect(route('admin.users.index'));

        $created = User::where('email', 'second.admin@example.com')->firstOrFail();
        $this->assertTrue($created->must_change_password);
        $this->assertFalse($created->is_primary);
        $this->assertTrue(Hash::check('Security123!', $created->password));
    }

    public function test_new_admin_must_change_password_on_first_login(): void
    {
        $admin = $this->primaryAdmin();

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Second Administrator',
                'email' => 'second.admin@example.com',
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));

        $this->post(route('admin.login'), [
            'email' => 'second.admin@example.com',
            'password' => 'Security123!',
        ])->assertRedirect(route('admin.password.change'));

        $this->put(route('admin.password.update'), [
            'current_password' => 'Security123!',
            'password' => 'UpdatedPassword1!',
            'password_confirmation' => 'UpdatedPassword1!',
        ])->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_user_limit_is_enforced(): void
    {
        $admin = $this->primaryAdmin();

        User::factory()->create([
            'email' => 'existing.admin@example.com',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Third Administrator',
                'email' => 'third.admin@example.com',
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseMissing('users', [
            'email' => 'third.admin@example.com',
        ]);
    }

    public function test_seeder_does_not_recreate_a_deleted_test_admin(): void
    {
        $admin = $this->primaryAdmin();
        $adminEmail = $admin->email;

        User::factory()->create([
            'email' => 'customer.admin@example.com',
        ]);

        $admin->delete();

        $this->seed(AdminUserSeeder::class);

        $this->assertDatabaseMissing('users', [
            'email' => $adminEmail,
        ]);
    }

    public function test_primary_admin_must_change_password_on_first_login(): void
    {
        config([
            'admin.email' => 'primary.admin@example.test',
            'admin.password' => 'InitialPassword1!',
            'admin.name' => 'Primary Administrator',
        ]);

        User::query()->delete();

        $this->post(route('admin.login'), [
            'email' => 'primary.admin@example.test',
            'password' => 'InitialPassword1!',
        ])->assertRedirect(route('admin.password.change'));

        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.password.change'));

        $this->put(route('admin.password.update'), [
            'current_password' => 'InitialPassword1!',
            'password' => 'UpdatedPrimaryPassword1!',
            'password_confirmation' => 'UpdatedPrimaryPassword1!',
        ])->assertRedirect(route('admin.dashboard'));

        $primaryAdmin = User::where('email', 'primary.admin@example.test')->firstOrFail();
        $this->assertFalse($primaryAdmin->must_change_password);
        $this->assertTrue($primaryAdmin->is_primary);
        $this->get(route('admin.dashboard'))->assertOk();
    }

    public function test_primary_admin_cannot_delete_their_own_account(): void
    {
        $admin = $this->primaryAdmin();

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin))
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_primary_admin_cannot_be_deleted(): void
    {
        $primaryAdmin = $this->primaryAdmin();

        $otherAdmin = User::factory()->create([
            'email' => 'other.admin@example.com',
        ]);

        $this->actingAs($otherAdmin)
            ->delete(route('admin.users.destroy', $primaryAdmin))
            ->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $primaryAdmin->id]);
    }

    public function test_primary_admin_can_delete_secondary_admin(): void
    {
        $primaryAdmin = $this->primaryAdmin();
        $secondaryAdmin = User::factory()->create([
            'email' => 'secondary.admin@example.com',
        ]);

        $this->actingAs($primaryAdmin)
            ->delete(route('admin.users.destroy', $secondaryAdmin))
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseMissing('users', ['id' => $secondaryAdmin->id]);
    }

    public function test_primary_admin_can_reset_secondary_admin_to_default_password(): void
    {
        $primaryAdmin = $this->primaryAdmin();
        $secondaryAdmin = User::factory()->create([
            'email' => 'secondary.admin@example.com',
            'password' => 'PersonalPassword1!',
            'must_change_password' => false,
        ]);

        $this->actingAs($primaryAdmin)
            ->put(route('admin.users.update', $secondaryAdmin), [
                'name' => 'Secondary Administrator',
                'email' => 'secondary.admin@example.com',
                'reset_to_default_password' => '1',
            ])
            ->assertRedirect(route('admin.users.index'));

        $secondaryAdmin->refresh();
        $this->assertTrue($secondaryAdmin->must_change_password);
        $this->assertTrue(Hash::check('Security123!', $secondaryAdmin->password));
    }

    public function test_customer_admin_deletion_clears_sessions_remember_token_and_access(): void
    {
        $primaryAdmin = $this->primaryAdmin();
        $secondaryAdmin = User::factory()->create([
            'email' => 'customer.admin@example.com',
            'is_primary' => false,
        ]);

        DB::table('sessions')->insert([
            'id' => 'deleted-admin-session',
            'user_id' => $secondaryAdmin->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'payload' => 'test-payload',
            'last_activity' => now()->timestamp,
        ]);

        $this->actingAs($primaryAdmin)
            ->delete(route('admin.users.destroy', $secondaryAdmin))
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseMissing('users', ['id' => $secondaryAdmin->id]);
        $this->assertDatabaseMissing('sessions', ['id' => 'deleted-admin-session']);

        $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));

        $this->from(route('admin.login'))
            ->post(route('admin.login'), [
                'email' => 'customer.admin@example.com',
                'password' => 'Security123!',
            ])
            ->assertRedirect(route('admin.login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
