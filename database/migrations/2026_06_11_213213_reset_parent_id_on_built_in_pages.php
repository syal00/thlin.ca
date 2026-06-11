<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('pages')
            ->where('page_type', 'built_in')
            ->update(['parent_id' => null]);
    }

    public function down(): void
    {
        //
    }
};
