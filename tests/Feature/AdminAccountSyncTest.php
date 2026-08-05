<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAccountSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_creates_configured_administrator_when_database_has_no_users(): void
    {
        config([
            'admin.email' => 'vercel-admin@example.test',
            'admin.password' => 'VercelPassword1!',
            'admin.name' => 'Vercel Administrator',
        ]);

        $this->assertDatabaseCount('users', 0);

        $response = $this->post(route('admin.login'), [
            'email' => 'vercel-admin@example.test',
            'password' => 'VercelPassword1!',
        ]);

        $response->assertRedirect(route('admin.password.change'));

        $this->assertDatabaseHas('users', [
            'email' => 'vercel-admin@example.test',
            'name' => 'Vercel Administrator',
            'must_change_password' => true,
            'is_primary' => true,
        ]);
    }

    public function test_login_does_not_create_user_for_unconfigured_email_on_empty_database(): void
    {
        config([
            'admin.email' => 'vercel-admin@example.test',
            'admin.password' => 'VercelPassword1!',
        ]);

        $this->post(route('admin.login'), [
            'email' => 'other@example.test',
            'password' => 'VercelPassword1!',
        ])->assertSessionHasErrors('email');

        $this->assertDatabaseCount('users', 0);
    }

    public function test_login_updates_configured_admin_password_from_env_before_first_password_change(): void
    {
        config([
            'admin.email' => 'vercel-admin@example.test',
            'admin.password' => 'UpdatedPassword1!',
            'admin.name' => 'Vercel Administrator',
        ]);

        User::factory()->create([
            'email' => 'vercel-admin@example.test',
            'password' => Hash::make('OldPassword1!'),
            'must_change_password' => true,
            'is_primary' => true,
        ]);

        $response = $this->post(route('admin.login'), [
            'email' => 'vercel-admin@example.test',
            'password' => 'UpdatedPassword1!',
        ]);

        $response->assertRedirect(route('admin.password.change'));
    }

    public function test_login_does_not_reset_password_after_primary_admin_has_changed_it(): void
    {
        config([
            'admin.email' => 'vercel-admin@example.test',
            'admin.password' => 'UpdatedPassword1!',
        ]);

        User::factory()->create([
            'email' => 'vercel-admin@example.test',
            'password' => Hash::make('PersonalPassword1!'),
            'must_change_password' => false,
            'is_primary' => true,
        ]);

        $this->post(route('admin.login'), [
            'email' => 'vercel-admin@example.test',
            'password' => 'UpdatedPassword1!',
        ])->assertSessionHasErrors('email');

        $this->post(route('admin.login'), [
            'email' => 'vercel-admin@example.test',
            'password' => 'PersonalPassword1!',
        ])->assertRedirect(route('admin.dashboard'));
    }
}
