<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Schema;

class AdminAccountSync
{
    /**
     * Ensure the configured primary administrator exists with credentials from config/admin.php.
     *
     * @param  string|null  $attemptedEmail  When set (login flow), only sync for the configured admin email.
     */
    public static function syncFromConfig(?string $attemptedEmail = null): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        $admin = config('admin');

        if ($attemptedEmail !== null) {
            if (strcasecmp($attemptedEmail, $admin['email']) !== 0) {
                return;
            }

            self::syncPrimaryAdminOnLogin($admin);

            return;
        }

        self::syncPrimaryAdminFromSeeder($admin);
    }

    /**
     * @param  array{name: string, email: string, password: string}  $admin
     */
    private static function syncPrimaryAdminOnLogin(array $admin): void
    {
        $user = User::where('email', $admin['email'])->first();

        if (! $user) {
            if (User::exists()) {
                return;
            }

            User::create(self::newPrimaryAdminAttributes($admin));

            return;
        }

        if ($user->must_change_password) {
            $user->update([
                'password' => $admin['password'],
                'must_change_password' => true,
            ]);
        }
    }

    /**
     * @param  array{name: string, email: string, password: string}  $admin
     */
    private static function syncPrimaryAdminFromSeeder(array $admin): void
    {
        $user = User::where('email', $admin['email'])->first()
            ?? User::where('is_primary', true)->first()
            ?? User::where('email', 'admin@thlin.local')->first();

        if (! $user) {
            if (User::exists()) {
                return;
            }

            User::create(self::newPrimaryAdminAttributes($admin));

            return;
        }

        $attributes = [
            'name' => $admin['name'],
            'email' => $admin['email'],
            'email_verified_at' => now(),
            'is_primary' => true,
        ];

        if ($user->must_change_password) {
            $attributes['password'] = $admin['password'];
            $attributes['must_change_password'] = true;
        }

        User::whereKeyNot($user->id)->where('is_primary', true)->update(['is_primary' => false]);

        $user->update($attributes);
    }

    /**
     * @param  array{name: string, email: string, password: string}  $admin
     * @return array<string, mixed>
     */
    private static function newPrimaryAdminAttributes(array $admin): array
    {
        return [
            'name' => $admin['name'],
            'email' => $admin['email'],
            'password' => $admin['password'],
            'email_verified_at' => now(),
            'must_change_password' => true,
            'is_primary' => true,
        ];
    }
}
