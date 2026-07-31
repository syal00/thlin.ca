<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\AdminTwoFactor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class AdminTwoFactorTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_valid_password_redirects_to_setup_when_two_factor_not_configured(): void
    {
        $admin = User::firstOrFail();
        $admin->forceFill([
            'two_factor_secret' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        $response = $this->post(route('admin.login'), [
            'email' => $admin->email,
            'password' => config('admin.password'),
        ]);

        $response
            ->assertRedirect(route('admin.login.setup-2fa'))
            ->assertSessionHas('login.id', $admin->id)
            ->assertSessionHas('login.2fa_secret');

        $this->assertGuest();
    }

    public function test_setup_confirmation_completes_sign_in(): void
    {
        $admin = User::firstOrFail();
        $secret = AdminTwoFactor::generateSecret();
        $code = (new Google2FA)->getCurrentOtp($secret);

        $response = $this->withSession([
            'login.id' => $admin->id,
            'login.remember' => false,
            'login.2fa_secret' => $secret,
        ])->post(route('admin.login.setup-2fa.submit'), [
            'code' => $code,
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin->fresh());
        $this->assertTrue($admin->fresh()->hasTwoFactorEnabled());
    }

    public function test_valid_two_factor_code_completes_sign_in(): void
    {
        $admin = User::firstOrFail();
        $secret = AdminTwoFactor::generateSecret();
        $code = (new Google2FA)->getCurrentOtp($secret);

        $admin->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => now(),
        ])->save();

        $response = $this->withSession([
            'login.id' => $admin->id,
            'login.remember' => false,
        ])->post(route('admin.login.verify.submit'), [
            'code' => $code,
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_invalid_two_factor_code_is_rejected(): void
    {
        $admin = User::firstOrFail();
        $secret = AdminTwoFactor::generateSecret();

        $admin->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => now(),
        ])->save();

        $response = $this->withSession([
            'login.id' => $admin->id,
        ])->post(route('admin.login.verify.submit'), [
            'code' => '000000',
        ]);

        $response
            ->assertRedirect(route('admin.login.verify'))
            ->assertSessionHasErrors('code');

        $this->assertGuest();
    }

    public function test_two_factor_code_with_spaces_is_accepted(): void
    {
        $admin = User::firstOrFail();
        $secret = AdminTwoFactor::generateSecret();
        $code = (new Google2FA)->getCurrentOtp($secret);

        $admin->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => now(),
        ])->save();

        $response = $this->withSession([
            'login.id' => $admin->id,
            'login.remember' => false,
        ])->post(route('admin.login.verify.submit'), [
            'code' => substr($code, 0, 3).' '.substr($code, 3),
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }
}
