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

        if (User::where('email', $admin['email'])->exists() || User::query()->exists()) {
            return;
        }

        $attributes = [
            'name' => $admin['name'],
            'email' => $admin['email'],
            'password' => Hash::make($admin['password']),
            'email_verified_at' => now(),
        ];

        User::create($attributes);
    }
}
