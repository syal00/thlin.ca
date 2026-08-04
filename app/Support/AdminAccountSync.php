<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminAccountSync
{
    /**
     * Ensure the configured administrator exists with credentials from config/admin.php.
     *
     * @param  string|null  $attemptedEmail  When set (login flow), only sync for the configured admin email.
     */
    public static function syncFromConfig(?string $attemptedEmail = null): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        $admin = config('admin');

        if ($attemptedEmail !== null && strcasecmp($attemptedEmail, $admin['email']) !== 0) {
            return;
        }

        if ($attemptedEmail === null) {
            $user = User::where('email', $admin['email'])->first()
                ?? User::where('email', 'admin@thlin.local')->first()
                ?? User::orderBy('id')->first();
        } else {
            $user = User::where('email', $admin['email'])->first()
                ?? User::where('email', 'admin@thlin.local')->first();

            if (! $user && User::exists()) {
                return;
            }
        }

        $attributes = [
            'name' => $admin['name'],
            'email' => $admin['email'],
            'password' => Hash::make($admin['password']),
            'email_verified_at' => now(),
        ];

        if ($user) {
            $user->update($attributes);
        } else {
            User::create($attributes);
        }
    }
}
