<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE pages ALTER COLUMN meta_description TYPE TEXT');
        } else {
            DB::statement('ALTER TABLE pages MODIFY meta_description TEXT NULL');
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE pages ALTER COLUMN meta_description TYPE VARCHAR(255)');
        } else {
            DB::statement('ALTER TABLE pages MODIFY meta_description VARCHAR(255) NULL');
        }
    }
};
