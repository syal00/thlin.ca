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

    protected bool $seed = true;

    public function test_public_registration_is_not_available(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_guests_cannot_manage_admin_users(): void
    {
        $this->get(route('admin.users.index'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_create_a_second_admin_user(): void
    {
        $admin = User::firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Second Administrator',
                'email' => 'second.admin@example.com',
                'password' => 'StrongPassword1',
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'Second Administrator',
            'email' => 'second.admin@example.com',
        ]);
    }

    public function test_admin_user_limit_is_enforced(): void
    {
        $admin = User::firstOrFail();

        User::factory()->create([
            'email' => 'existing.admin@example.com',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Third Administrator',
                'email' => 'third.admin@example.com',
                'password' => 'StrongPassword1',
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseMissing('users', [
            'email' => 'third.admin@example.com',
        ]);
    }

    public function test_seeder_does_not_recreate_a_deleted_test_admin(): void
    {
        $admin = User::firstOrFail();
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

    public function test_customer_admin_handoff_flow_supports_login_password_change_and_two_user_limit(): void
    {
        $testAdmin = User::firstOrFail();
        $customerEmail = 'customer.admin@example.com';
        $initialPassword = 'CustomerPassword1!';
        $updatedPassword = 'UpdatedCustomerPassword1!';

        $this->actingAs($testAdmin)
            ->post(route('admin.users.store'), [
                'name' => 'Customer Administrator',
                'email' => $customerEmail,
                'password' => $initialPassword,
            ])
            ->assertRedirect(route('admin.users.index'));

        $customerAdmin = User::where('email', $customerEmail)->firstOrFail();

        $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));

        $this->post(route('admin.login'), [
            'email' => $customerEmail,
            'password' => $initialPassword,
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($customerAdmin);
        $this->get(route('admin.dashboard'))->assertOk();

        $this->put(route('admin.users.update', $customerAdmin), [
            'name' => 'Customer Administrator Updated',
            'email' => $customerEmail,
            'password' => $updatedPassword,
        ])->assertRedirect(route('admin.users.index'));

        $customerAdmin->refresh();
        $this->assertSame('Customer Administrator Updated', $customerAdmin->name);
        $this->assertTrue(Hash::check($updatedPassword, $customerAdmin->password));
        $this->assertFalse(Hash::check($initialPassword, $customerAdmin->password));

        $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));

        $this->from(route('admin.login'))
            ->post(route('admin.login'), [
                'email' => $customerEmail,
                'password' => $initialPassword,
            ])
            ->assertRedirect(route('admin.login'))
            ->assertSessionHasErrors('email');

        $this->post(route('admin.login'), [
            'email' => $customerEmail,
            'password' => $updatedPassword,
        ])->assertRedirect(route('admin.dashboard'));

        $this->actingAs($customerAdmin)
            ->post(route('admin.users.store'), [
                'name' => 'Third Administrator',
                'email' => 'third.admin@example.com',
                'password' => 'ThirdAdminPassword1!',
            ])
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseMissing('users', [
            'email' => 'third.admin@example.com',
        ]);
        $this->assertSame(2, User::count());
    }

    public function test_admin_cannot_delete_their_own_account(): void
    {
        $admin = User::firstOrFail();

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin))
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_last_admin_cannot_be_deleted(): void
    {
        $lastAdmin = User::firstOrFail();
        $otherAuthenticatedUser = User::factory()->make(['id' => $lastAdmin->id + 1]);

        $this->actingAs($otherAuthenticatedUser)
            ->delete(route('admin.users.destroy', $lastAdmin))
            ->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['id' => $lastAdmin->id]);
    }

    public function test_customer_admin_deletion_clears_sessions_remember_token_and_access(): void
    {
        $testAdmin = User::firstOrFail();
        $testAdmin->forceFill(['remember_token' => 'previous-remember-token'])->save();
        $customerAdmin = User::factory()->create([
            'email' => 'customer.admin@example.com',
        ]);

        DB::table('sessions')->insert([
            'id' => 'deleted-admin-session',
            'user_id' => $testAdmin->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'payload' => 'test-payload',
            'last_activity' => now()->timestamp,
        ]);

        $rememberTokenAtDeletion = 'not-cleared';
        User::deleting(static function (User $user) use (&$rememberTokenAtDeletion, $testAdmin): void {
            if ($user->is($testAdmin)) {
                $rememberTokenAtDeletion = $user->remember_token;
            }
        });

        $this->actingAs($customerAdmin)
            ->delete(route('admin.users.destroy', $testAdmin))
            ->assertRedirect(route('admin.users.index'));

        $this->assertNull($rememberTokenAtDeletion);
        $this->assertDatabaseMissing('users', ['id' => $testAdmin->id]);
        $this->assertDatabaseMissing('sessions', ['id' => 'deleted-admin-session']);

        $this->post(route('admin.logout'))->assertRedirect(route('admin.login'));

        $this->from(route('admin.login'))
            ->post(route('admin.login'), [
                'email' => config('admin.email'),
                'password' => config('admin.password'),
            ])
            ->assertRedirect(route('admin.login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
