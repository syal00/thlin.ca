<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
