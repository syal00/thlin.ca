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
            if (! Schema::hasColumn('pages', 'hero_title')) {
                $table->string('hero_title')->nullable()->after('title');
            }

            if (! Schema::hasColumn('pages', 'hero_subtitle')) {
                $table->string('hero_subtitle', 500)->nullable()->after('hero_title');
            }

            if (! Schema::hasColumn('pages', 'page_type')) {
                $table->string('page_type')->default('built_in')->after('meta_description');
            }

            if (! Schema::hasColumn('pages', 'status')) {
                $table->string('status')->default('published')->after('page_type');
            }

            if (! Schema::hasColumn('pages', 'show_in_navigation')) {
                $table->boolean('show_in_navigation')->default(false)->after('status');
            }

            if (! Schema::hasColumn('pages', 'navigation_label')) {
                $table->string('navigation_label')->nullable()->after('show_in_navigation');
            }

            if (! Schema::hasColumn('pages', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('sort_order');
            }
        });

        foreach (DB::table('pages')->get(['id', 'is_published']) as $page) {
            DB::table('pages')->where('id', $page->id)->update([
                'page_type' => 'built_in',
                'status' => $page->is_published ? 'published' : 'draft',
                'published_at' => $page->is_published ? now() : null,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $columns = [
                'hero_title',
                'hero_subtitle',
                'page_type',
                'status',
                'show_in_navigation',
                'navigation_label',
                'published_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('pages', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
