<?php

namespace Database\Seeders;

use App\Support\AdminAccountSync;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        AdminAccountSync::syncFromConfig();
    }
}
