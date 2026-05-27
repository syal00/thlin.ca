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

        User::updateOrCreate(
            ['email' => $admin['email']],
            [
                'name' => $admin['name'],
                'password' => Hash::make($admin['password']),
                'email_verified_at' => now(),
            ]
        );
    }
}
