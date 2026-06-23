<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ImportSqliteToPostgresTest extends TestCase
{
    use RefreshDatabase;

    private string $sourcePath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sourcePath = tempnam(sys_get_temp_dir(), 'thlin-import-');

        config(['database.connections.sqlite_test_source' => [
            'driver' => 'sqlite',
            'database' => $this->sourcePath,
            'prefix' => '',
        ]]);

        DB::purge('sqlite_test_source');
        Schema::connection('sqlite_test_source')->create('pages', function ($table): void {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('slug');
            $table->string('title');
            $table->string('section');
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->string('template');
            $table->unsignedSmallInteger('sort_order');
            $table->boolean('is_published');
            $table->timestamps();
        });

        foreach (['news_posts', 'careers', 'board_members', 'portfolio_items', 'site_settings'] as $tableName) {
            Schema::connection('sqlite_test_source')->create($tableName, function ($table): void {
                $table->id();
            });
        }

        DB::connection('sqlite_test_source')->table('pages')->insert([
            [
                'id' => 101,
                'parent_id' => null,
                'slug' => 'parent-page',
                'title' => 'Parent Page',
                'section' => 'general',
                'excerpt' => null,
                'body' => 'Parent body',
                'template' => 'standard',
                'sort_order' => 0,
                'is_published' => 1,
                'created_at' => '2026-06-01 12:00:00',
                'updated_at' => '2026-06-01 12:00:00',
            ],
            [
                'id' => 102,
                'parent_id' => 101,
                'slug' => 'child-page',
                'title' => 'Child Page',
                'section' => 'general',
                'excerpt' => null,
                'body' => 'Child body',
                'template' => 'standard',
                'sort_order' => 1,
                'is_published' => 0,
                'created_at' => '2026-06-01 12:00:00',
                'updated_at' => '2026-06-01 12:00:00',
            ],
        ]);
    }

    protected function tearDown(): void
    {
        DB::purge('sqlite_test_source');

        if (isset($this->sourcePath) && is_file($this->sourcePath)) {
            unlink($this->sourcePath);
        }

        parent::tearDown();
    }

    public function test_it_replaces_selected_content_and_restores_page_parents(): void
    {
        $this->artisan('thlin:import-sqlite', [
            'source' => $this->sourcePath,
            '--fresh' => true,
            '--force' => true,
        ])->assertSuccessful();

        $this->assertDatabaseCount('pages', 2);
        $this->assertDatabaseHas('pages', [
            'id' => 101,
            'slug' => 'parent-page',
            'is_published' => 1,
        ]);
        $this->assertDatabaseHas('pages', [
            'id' => 102,
            'parent_id' => 101,
            'slug' => 'child-page',
            'is_published' => 0,
        ]);
    }

    public function test_it_skips_default_tables_that_are_missing_from_an_older_source_snapshot(): void
    {
        Schema::connection('sqlite_test_source')->drop('site_settings');

        $this->artisan('thlin:import-sqlite', [
            'source' => $this->sourcePath,
            '--dry-run' => true,
        ])
            ->expectsOutputToContain('Skipping [site_settings] because the SQLite source predates that table.')
            ->assertSuccessful();
    }
}
