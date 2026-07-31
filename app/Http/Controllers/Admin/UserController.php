<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    private const MAX_ADMIN_USERS = 2;

    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::orderBy('name')->orderBy('email')->get(),
            'maxUsers' => self::MAX_ADMIN_USERS,
        ]);
    }

    public function create(): View|RedirectResponse
    {
        if (User::count() >= self::MAX_ADMIN_USERS) {
            return redirect()
                ->route('admin.users.index')
                ->with('status', 'The admin user limit has been reached.');
        }

        return view('admin.users.form', [
            'user' => new User,
            'maxUsers' => self::MAX_ADMIN_USERS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (User::count() >= self::MAX_ADMIN_USERS) {
            return redirect()
                ->route('admin.users.index')
                ->with('status', 'The admin user limit has been reached.');
        }

        User::create($this->validated($request));

        return redirect()->route('admin.users.index')->with('status', 'Admin user created.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.form', [
            'user' => $user,
            'maxUsers' => self::MAX_ADMIN_USERS,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validated($request, $user);

        if (! isset($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('status', 'Admin user updated.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()?->is($user)) {
            return redirect()
                ->route('admin.users.index')
                ->with('status', 'You cannot delete your own admin account while signed in.');
        }

        if (User::count() <= 1) {
            return redirect()
                ->route('admin.users.index')
                ->with('status', 'At least one admin user is required.');
        }

        DB::table('sessions')->where('user_id', $user->id)->delete();
        $user->forceFill(['remember_token' => null])->save();
        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'Admin user deleted.');
    }

    private function validated(Request $request, ?User $user = null): array
    {
        $passwordRules = ['string', Password::min(12)->mixedCase()->numbers()];

        if ($user === null) {
            array_unshift($passwordRules, 'required');
        } else {
            array_unshift($passwordRules, 'nullable');
        }

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user),
            ],
            'password' => $passwordRules,
        ]);
    }
}
