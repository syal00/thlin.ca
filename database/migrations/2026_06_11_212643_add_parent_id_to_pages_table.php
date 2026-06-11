<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            if (! Schema::hasColumn('pages', 'parent_id')) {
                $table->foreignId('parent_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('pages')
                    ->nullOnDelete();
            }
        });

        $landingPages = [
            ['slug' => 'products-services', 'title' => 'Products & Services', 'section' => 'products'],
            ['slug' => 'about', 'title' => 'About', 'section' => 'about'],
            ['slug' => 'partners', 'title' => 'Partners', 'section' => 'partners'],
        ];

        foreach ($landingPages as $landing) {
            if (DB::table('pages')->where('slug', $landing['slug'])->exists()) {
                continue;
            }

            DB::table('pages')->insert([
                'slug' => $landing['slug'],
                'title' => $landing['title'],
                'section' => $landing['section'],
                'template' => 'standard',
                'page_type' => 'built_in',
                'status' => 'published',
                'is_published' => true,
                'sort_order' => 0,
                'published_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            if (Schema::hasColumn('pages', 'parent_id')) {
                $table->dropConstrainedForeignId('parent_id');
            }
        });
    }
};
