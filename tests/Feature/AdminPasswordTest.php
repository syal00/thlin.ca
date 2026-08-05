<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use App\Notifications\AdminResetPasswordNotification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AdminPasswordTest extends TestCase
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
            'admin.max_users' => 10,
        ]);

        $this->seed(AdminUserSeeder::class);
        User::query()->update(['must_change_password' => false]);
    }

    private function primaryAdmin(): User
    {
        return User::where('is_primary', true)->firstOrFail();
    }

    public function test_login_page_shows_forgot_password_link(): void
    {
        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('Forgot password?')
            ->assertSee(route('admin.password.request'), false);
    }

    public function test_forgot_password_shows_reset_link_when_mail_driver_is_log(): void
    {
        config(['mail.default' => 'log']);

        $admin = $this->primaryAdmin();

        $this->post(route('admin.password.email'), [
            'email' => $admin->email,
        ])
            ->assertSessionHas('status')
            ->assertSessionHas('dev_reset_url');

        $this->assertStringContainsString(
            'admin/password/reset',
            session('dev_reset_url')
        );
    }

    public function test_forgot_password_sends_reset_link(): void
    {
        Notification::fake();

        $admin = $this->primaryAdmin();

        $this->post(route('admin.password.email'), [
            'email' => $admin->email,
        ])->assertSessionHas('status');

        Notification::assertSentTo($admin, AdminResetPasswordNotification::class);
    }

    public function test_admin_can_reset_password_with_valid_token(): void
    {
        $admin = $this->primaryAdmin();
        $token = Password::broker()->createToken($admin);

        $this->post(route('admin.password.store'), [
            'token' => $token,
            'email' => $admin->email,
            'password' => 'ResetPassword123!',
            'password_confirmation' => 'ResetPassword123!',
        ])->assertRedirect(route('admin.login'))
            ->assertSessionHas('status');

        $admin->refresh();
        $this->assertTrue(Hash::check('ResetPassword123!', $admin->password));
        $this->assertFalse($admin->must_change_password);
    }

    public function test_logged_in_admin_can_change_password_voluntarily(): void
    {
        $admin = $this->primaryAdmin();

        $this->actingAs($admin)
            ->get(route('admin.password.change'))
            ->assertOk()
            ->assertSee('Change password');

        $this->actingAs($admin)
            ->put(route('admin.password.update'), [
                'current_password' => 'Security123!',
                'password' => 'VoluntaryChange1!',
                'password_confirmation' => 'VoluntaryChange1!',
            ])->assertRedirect(route('admin.password.change'))
            ->assertSessionHas('status');

        $admin->refresh();
        $this->assertTrue(Hash::check('VoluntaryChange1!', $admin->password));
    }

    public function test_voluntary_password_change_requires_current_password(): void
    {
        $admin = $this->primaryAdmin();

        $this->actingAs($admin)
            ->put(route('admin.password.update'), [
                'current_password' => 'WrongPassword1!',
                'password' => 'VoluntaryChange1!',
                'password_confirmation' => 'VoluntaryChange1!',
            ])->assertSessionHasErrors('current_password');
    }
}
