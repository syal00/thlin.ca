<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = config('admin');

        $user = User::where('email', $admin['email'])->first()
            ?? User::where('email', 'admin@thlin.local')->first()
            ?? User::orderBy('id')->first();

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
