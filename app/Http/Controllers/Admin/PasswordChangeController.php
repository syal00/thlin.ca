<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class PasswordChangeController extends Controller
{
    public function show(): View|RedirectResponse
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('admin.login');
        }

        return view('admin.password-change', [
            'required' => $user->must_change_password,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('admin.login');
        }

        $required = $user->must_change_password;

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed', Password::min(12)->mixedCase()->numbers()],
        ]);

        if (! Hash::check($data['current_password'], $user->password)) {
            return back()
                ->withErrors(['current_password' => 'The current password is incorrect.'])
                ->withInput($request->except('current_password', 'password', 'password_confirmation'));
        }

        $user->forceFill([
            'password' => $data['password'],
            'must_change_password' => false,
        ])->save();

        if ($required) {
            return redirect()
                ->route('admin.dashboard')
                ->with('status', 'Your password has been updated. You can now manage the CMS.');
        }

        return redirect()
            ->route('admin.password.change')
            ->with('status', 'Your password has been updated.');
    }
}
