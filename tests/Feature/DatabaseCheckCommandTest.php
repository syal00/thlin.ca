<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use RuntimeException;
use Tests\TestCase;

class DatabaseCheckCommandTest extends TestCase
{
    private ?string $databasePath = null;

    protected function setUp(): void
    {
        parent::setUp();

        $databasePath = tempnam(sys_get_temp_dir(), 'thlin-db-check-');

        if ($databasePath === false) {
            throw new RuntimeException('Unable to create a temporary SQLite database file.');
        }

        $this->databasePath = $databasePath;
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', $this->databasePath);
        config()->set('database.connections.sqlite.foreign_key_constraints', true);
        DB::purge('sqlite');

        $this->artisan('migrate', ['--force' => true])->assertExitCode(0);
    }

    protected function tearDown(): void
    {
        DB::purge('sqlite');

        if ($this->databasePath !== null && is_file($this->databasePath)) {
            unlink($this->databasePath);
        }

        parent::tearDown();
    }

    public function test_it_passes_for_a_healthy_database_without_changing_the_database_file(): void
    {
        $beforeChecksum = hash_file('sha256', $this->databasePath);

        $this->assertNotFalse($beforeChecksum);

        $this->artisan('thlin:db-check')
            ->expectsOutput('SQLite database check passed.')
            ->expectsOutput('Key table counts: users=0, pages=3, news_posts=0, careers=0, board_members=0, portfolio_items=0, media_files=0, contact_messages=0, site_settings=0')
            ->assertExitCode(0);

        $this->assertSame($beforeChecksum, hash_file('sha256', $this->databasePath));
    }

    public function test_it_reports_an_integrity_check_failure(): void
    {
        DB::statement('CREATE TABLE integrity_check_fixture (value INTEGER CHECK (value > 0))');
        DB::statement('PRAGMA ignore_check_constraints = ON');
        DB::table('integrity_check_fixture')->insert(['value' => 0]);
        DB::statement('PRAGMA ignore_check_constraints = OFF');

        $this->assertDatabaseCheckFailsWith('PRAGMA integrity_check failed:');
    }

    public function test_it_reports_foreign_key_violations(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF');
        DB::table('media_files')->insert([
            'title' => 'Orphaned file',
            'original_name' => 'orphaned.pdf',
            'file_name' => 'orphaned.pdf',
            'file_path' => 'media/orphaned.pdf',
            'mime_type' => 'application/pdf',
            'uploaded_by' => 999999,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::statement('PRAGMA foreign_keys = ON');

        $this->assertDatabaseCheckFailsWith('PRAGMA foreign_key_check found 1 violation(s).');
    }

    public function test_it_reports_missing_required_tables(): void
    {
        DB::statement('DROP TABLE site_settings');

        $this->artisan('thlin:db-check')
            ->expectsOutput('Required SQLite tables are missing: site_settings')
            ->assertExitCode(1);
    }

    public function test_it_reports_pending_migrations(): void
    {
        DB::table('migrations')
            ->where('migration', '2026_06_22_000001_add_cloudinary_and_fulltext_search')
            ->delete();

        $this->assertDatabaseCheckFailsWith('Pending migrations: 2026_06_22_000001_add_cloudinary_and_fulltext_search');
    }

    public function test_it_reports_invalid_page_parent_relationships(): void
    {
        $pageId = DB::table('pages')->insertGetId([
            'title' => 'Self-referencing page',
            'slug' => 'self-referencing-page',
            'section' => 'custom',
            'template' => 'standard',
            'is_published' => true,
            'page_type' => 'custom',
            'status' => 'published',
            'show_in_navigation' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('pages')->where('id', $pageId)->update(['parent_id' => $pageId]);

        $this->assertDatabaseCheckFailsWith('Invalid page parent relationships: 1');
    }

    public function test_it_reports_orphaned_user_references(): void
    {
        DB::table('sessions')->insert([
            'id' => 'orphaned-session',
            'user_id' => 999999,
            'ip_address' => null,
            'user_agent' => null,
            'payload' => 'test payload',
            'last_activity' => now()->timestamp,
        ]);

        $this->assertDatabaseCheckFailsWith('Orphaned sessions.user_id references: 1');
    }

    public function test_it_reports_duplicate_administrator_emails(): void
    {
        foreach (['Admin@example.test', 'admin@example.test'] as $email) {
            DB::table('users')->insert([
                'name' => 'Administrator',
                'email' => $email,
                'password' => 'not-used-in-this-test',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->assertDatabaseCheckFailsWith('Duplicate administrator email groups: 1');
    }

    private function assertDatabaseCheckFailsWith(string $diagnostic): void
    {
        $this->artisan('thlin:db-check')
            ->expectsOutput('SQLite database check failed:')
            ->expectsOutputToContain($diagnostic)
            ->assertExitCode(1);
    }
}
