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
    private function maxAdminUsers(): int
    {
        return max(1, (int) config('admin.max_users', 10));
    }

    private function defaultPassword(): string
    {
        return (string) config('admin.password', 'Security123!');
    }

    public function index(): View
    {
        $users = User::orderByDesc('is_primary')->orderBy('name')->orderBy('email')->get();

        return view('admin.users.index', [
            'users' => $users,
            'maxUsers' => $this->maxAdminUsers(),
            'defaultPassword' => $this->defaultPassword(),
            'accountStats' => [
                'total' => $users->count(),
                'mainAdmins' => $users->where('is_primary', true)->count(),
                'secondaryAdmins' => $users->where('is_primary', false)->count(),
                'pendingPasswordChange' => $users->where('must_change_password', true)->count(),
                'remainingSlots' => max(0, $this->maxAdminUsers() - $users->count()),
            ],
        ]);
    }

    public function create(): View|RedirectResponse
    {
        if (User::count() >= $this->maxAdminUsers()) {
            return redirect()
                ->route('admin.users.index')
                ->with('status', 'The admin user limit has been reached.');
        }

        return view('admin.users.form', [
            'user' => new User,
            'maxUsers' => $this->maxAdminUsers(),
            'defaultPassword' => $this->defaultPassword(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (User::count() >= $this->maxAdminUsers()) {
            return redirect()
                ->route('admin.users.index')
                ->with('status', 'The admin user limit has been reached.');
        }

        $data = $this->validated($request);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $this->defaultPassword(),
            'must_change_password' => true,
            'is_primary' => false,
            'email_verified_at' => now(),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Admin user created with the default password. They must choose a new password at first sign-in.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.form', [
            'user' => $user,
            'maxUsers' => $this->maxAdminUsers(),
            'defaultPassword' => $this->defaultPassword(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        if ($user->is_primary) {
            $data = $this->validated($request, $user, primaryOnly: true);
            unset($data['password'], $data['reset_to_default_password']);

            $user->update($data);

            return redirect()
                ->route('admin.users.index')
                ->with('status', 'CMS manager profile updated.');
        }

        $data = $this->validated($request, $user);

        if ($request->boolean('reset_to_default_password')) {
            $data['password'] = $this->defaultPassword();
            $data['must_change_password'] = true;
        } elseif (! isset($data['password'])) {
            unset($data['password']);
        } else {
            $data['must_change_password'] = false;
        }

        unset($data['reset_to_default_password']);

        $user->update($data);

        return redirect()->route('admin.users.index')->with('status', 'Admin user updated.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is_primary) {
            return redirect()
                ->route('admin.users.index')
                ->with('status', 'The CMS manager account cannot be deleted.');
        }

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

    private function validated(Request $request, ?User $user = null, bool $primaryOnly = false): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user),
            ],
        ];

        if (! $primaryOnly && $user !== null) {
            $passwordRules = ['nullable', 'string', Password::min(12)->mixedCase()->numbers()];
            $rules['password'] = $passwordRules;
            $rules['reset_to_default_password'] = ['sometimes', 'boolean'];
        }

        return $request->validate($rules);
    }
}
