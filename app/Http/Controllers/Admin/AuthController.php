<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AdminTwoFactor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::query()->where('email', $credentials['email'])->first();

        if (! $user || ! Auth::validate($credentials)) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'These credentials do not match our records.']);
        }

        $request->session()->put('login.id', $user->id);
        $request->session()->put('login.remember', $request->boolean('remember'));

        if ($user->hasTwoFactorEnabled()) {
            return redirect()
                ->route('admin.login.verify')
                ->with('status', 'Enter the 6-digit code from your authenticator app.');
        }

        $secret = $user->two_factor_secret;

        if (! is_string($secret) || $secret === '') {
            $secret = AdminTwoFactor::generateSecret();
            $user->forceFill(['two_factor_secret' => $secret])->save();
        }

        $request->session()->put('login.2fa_secret', $secret);

        return redirect()
            ->route('admin.login.setup-2fa')
            ->with('status', 'Set up two-factor authentication to finish signing in.');
    }

    public function showSetupTwoFactor(Request $request): View|RedirectResponse
    {
        $user = $this->pendingLoginUser($request);

        if (! $user) {
            return redirect()
                ->route('admin.login')
                ->withErrors(['email' => 'Your sign-in session expired. Please start again.']);
        }

        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('admin.login.verify');
        }

        $secret = $user->two_factor_secret;

        if (! is_string($secret) || $secret === '') {
            return redirect()
                ->route('admin.login')
                ->withErrors(['email' => 'Your sign-in session expired. Please start again.']);
        }

        $request->session()->put('login.2fa_secret', $secret);

        return view('admin.setup-2fa', [
            'qrCodeSvg' => AdminTwoFactor::qrCodeSvg($user, $secret),
            'secret' => $secret,
        ]);
    }

    public function confirmSetupTwoFactor(Request $request): RedirectResponse
    {
        $code = AdminTwoFactor::normalizeCode($request->input('code'));

        $request->merge(['code' => $code]);
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = $this->pendingLoginUser($request);
        $secret = $user?->two_factor_secret ?? $request->session()->get('login.2fa_secret');

        if (! $user || ! is_string($secret) || $secret === '') {
            return redirect()
                ->route('admin.login')
                ->withErrors(['email' => 'Your sign-in session expired. Please start again.']);
        }

        if (! AdminTwoFactor::confirmSetup($user, $secret, $code)) {
            return redirect()
                ->route('admin.login.setup-2fa')
                ->withErrors(['code' => 'That code is invalid or expired. Wait for a fresh code in your app, then try again.']);
        }

        return $this->completeLogin($request, $user);
    }

    public function showVerifyTwoFactor(Request $request): View|RedirectResponse
    {
        $user = $this->pendingLoginUser($request);

        if (! $user) {
            return redirect()
                ->route('admin.login')
                ->withErrors(['email' => 'Your sign-in session expired. Please start again.']);
        }

        if (! $user->hasTwoFactorEnabled()) {
            return redirect()->route('admin.login.setup-2fa');
        }

        return view('admin.verify-2fa');
    }

    public function verifyTwoFactor(Request $request): RedirectResponse
    {
        $code = AdminTwoFactor::normalizeCode($request->input('code'));

        $request->merge(['code' => $code]);
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = $this->pendingLoginUser($request);

        if (! $user || ! $user->hasTwoFactorEnabled()) {
            return redirect()
                ->route('admin.login')
                ->withErrors(['email' => 'Your sign-in session expired. Please start again.']);
        }

        $user->refresh();

        if (! AdminTwoFactor::verify((string) $user->two_factor_secret, $code)) {
            return redirect()
                ->route('admin.login.verify')
                ->withErrors(['code' => 'That code is invalid or expired. Wait for a fresh code in your app, then try again.']);
        }

        return $this->completeLogin($request, $user);
    }

    public function cancelVerify(Request $request): RedirectResponse
    {
        $request->session()->forget(['login.id', 'login.remember', 'login.2fa_secret']);

        return redirect()->route('admin.login');
    }

    public function resetTwoFactorSetup(Request $request): RedirectResponse
    {
        $user = $this->pendingLoginUser($request);

        if (! $user) {
            return redirect()
                ->route('admin.login')
                ->withErrors(['email' => 'Your sign-in session expired. Please start again.']);
        }

        $secret = AdminTwoFactor::generateSecret();

        $user->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_confirmed_at' => null,
        ])->save();

        $request->session()->put('login.2fa_secret', $secret);

        return redirect()
            ->route('admin.login.setup-2fa')
            ->with('status', 'Scan the new QR code in your authenticator app. Remove any old THLIN CMS entry first.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    private function completeLogin(Request $request, User $user): RedirectResponse
    {
        $remember = (bool) $request->session()->pull('login.remember', false);

        $request->session()->forget(['login.id', 'login.2fa_secret']);
        Auth::login($user, $remember);
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    private function pendingLoginUser(Request $request): ?User
    {
        $userId = $request->session()->get('login.id');

        if (! $userId) {
            return null;
        }

        return User::query()->find($userId);
    }
}
