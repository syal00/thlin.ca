<?php

namespace Tests\Feature;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RuntimeException;
use Tests\TestCase;

class SQLiteRestoreCommandTest extends TestCase
{
    private ?string $databasePath = null;

    private ?string $backupPath = null;

    private ?string $manifestPath = null;

    private ?int $baselineParentPageId = null;

    private ?int $baselineChildPageId = null;

    protected function setUp(): void
    {
        parent::setUp();

        $databasePath = tempnam(sys_get_temp_dir(), 'thlin-sqlite-restore-');

        if ($databasePath === false) {
            throw new RuntimeException('Unable to create a temporary SQLite database file.');
        }

        $this->databasePath = $databasePath;
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', $this->databasePath);
        config()->set('database.connections.sqlite.foreign_key_constraints', true);
        DB::purge('sqlite');

        $this->artisan('migrate', ['--force' => true])->assertExitCode(0);
        $this->createRestoreExerciseBaseline();

        $timestamp = CarbonImmutable::create(2099, 1, 2, 0, 0, 0, 'UTC')->setMicrosecond(123456);
        CarbonImmutable::setTestNow($timestamp);

        $backupBaseName = 'sqlite-backup-'.$timestamp->format('Ymd_His_u');
        $backupDirectory = storage_path('app/backups/sqlite');
        $this->backupPath = $backupDirectory.'/'.$backupBaseName.'.sqlite';
        $this->manifestPath = $backupDirectory.'/'.$backupBaseName.'.json';

        $this->artisan('thlin:db-backup')->assertExitCode(0);
    }

    protected function tearDown(): void
    {
        CarbonImmutable::setTestNow();
        DB::purge('sqlite');

        if ($this->backupPath !== null) {
            $backupBaseName = pathinfo($this->backupPath, PATHINFO_FILENAME);

            foreach (glob(dirname($this->backupPath).'/'.$backupBaseName.'*') ?: [] as $backupArtifact) {
                File::delete($backupArtifact);
            }
        }

        if ($this->databasePath !== null && is_file($this->databasePath)) {
            unlink($this->databasePath);
        }

        parent::tearDown();
    }

    public function test_it_runs_a_read_only_preflight_for_a_valid_backup(): void
    {
        $this->assertNotNull($this->databasePath);
        $beforeChecksum = hash_file('sha256', $this->databasePath);

        $this->artisan('thlin:db-restore', [
            'backup' => $this->backupPath,
            '--dry-run' => true,
        ])
            ->expectsOutput('Restore preflight passed.')
            ->expectsOutput('Migration status: current')
            ->expectsOutput('No database changes were made.')
            ->expectsOutput('Re-run with --force only after confirming the preflight result.')
            ->assertExitCode(0);

        $this->assertSame($beforeChecksum, hash_file('sha256', $this->databasePath));
    }

    public function test_it_never_overwrites_the_database_without_force(): void
    {
        $this->assertNotNull($this->databasePath);
        $beforeChecksum = hash_file('sha256', $this->databasePath);

        $this->artisan('thlin:db-restore', ['backup' => $this->backupPath])
            ->expectsOutput('Restore preflight passed.')
            ->expectsOutput('No database changes were made.')
            ->assertExitCode(0);

        $this->assertSame($beforeChecksum, hash_file('sha256', $this->databasePath));
    }

    public function test_it_restores_the_backup_baseline_after_data_is_modified(): void
    {
        $this->assertNotNull($this->databasePath);
        $this->assertNotNull($this->backupPath);
        $this->assertNotNull($this->baselineParentPageId);
        $this->assertNotNull($this->baselineChildPageId);
        $beforeChecksum = hash_file('sha256', $this->databasePath);
        $baselineUserCount = DB::table('users')->count();
        $baselinePageCount = DB::table('pages')->count();
        $baselineChildParentId = DB::table('pages')->where('id', $this->baselineChildPageId)->value('parent_id');
        $preRestoreBackupPath = dirname($this->backupPath).'/'.pathinfo($this->backupPath, PATHINFO_FILENAME).'-1.sqlite';
        $preRestoreManifestPath = dirname($preRestoreBackupPath).'/'.pathinfo($preRestoreBackupPath, PATHINFO_FILENAME).'.json';

        DB::table('users')->insert([
            'name' => 'Restore Exercise Change',
            'email' => 'restore-exercise-change@example.test',
            'password' => 'restore-exercise-password',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('pages')->insert([
            'slug' => 'restore-exercise-change',
            'title' => 'Restore Exercise Change',
            'section' => 'general',
            'template' => 'standard',
            'page_type' => 'custom',
            'status' => 'published',
            'is_published' => true,
            'sort_order' => 0,
            'published_at' => now(),
            'parent_id' => $this->baselineParentPageId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->assertSame($baselineUserCount + 1, DB::table('users')->count());
        $this->assertSame($baselinePageCount + 1, DB::table('pages')->count());

        $this->artisan('thlin:db-restore', [
            'backup' => $this->backupPath,
            '--force' => true,
        ])
            ->expectsOutput('Restore preflight passed.')
            ->expectsOutput('Pre-restore backup created.')
            ->expectsOutput('Temporary restore validation passed.')
            ->expectsOutput('SQLite database restored.')
            ->assertExitCode(0);

        $this->assertNotSame($beforeChecksum, hash_file('sha256', $this->databasePath));
        $this->assertFileExists($preRestoreBackupPath);
        $this->assertFileExists($preRestoreManifestPath);
        $this->assertSame($baselineUserCount, DB::table('users')->count());
        $this->assertSame($baselinePageCount, DB::table('pages')->count());
        $this->assertSame($baselineChildParentId, DB::table('pages')->where('id', $this->baselineChildPageId)->value('parent_id'));
        $this->assertFalse(DB::table('users')->where('email', 'restore-exercise-change@example.test')->exists());
    }

    public function test_it_stops_when_the_pre_restore_backup_cannot_be_created(): void
    {
        $this->assertNotNull($this->databasePath);
        $this->assertNotNull($this->backupPath);
        $beforeChecksum = hash_file('sha256', $this->databasePath);
        $preRestoreBackupPath = dirname($this->backupPath).'/'.pathinfo($this->backupPath, PATHINFO_FILENAME).'-1.sqlite';
        config()->set('database.connections.sqlite.database', $this->databasePath.'-missing');
        DB::purge('sqlite');

        $this->artisan('thlin:db-restore', [
            'backup' => $this->backupPath,
            '--force' => true,
        ])
            ->expectsOutput('Restore preflight passed.')
            ->expectsOutput('Unable to create a pre-restore backup; the restore was not attempted.')
            ->assertExitCode(1);

        $this->assertSame($beforeChecksum, hash_file('sha256', $this->databasePath));
        $this->assertFileDoesNotExist($preRestoreBackupPath);
    }

    public function test_it_rejects_a_backup_that_fails_temporary_integrity_validation(): void
    {
        $this->assertNotNull($this->databasePath);
        $this->assertNotNull($this->backupPath);
        $beforeChecksum = hash_file('sha256', $this->databasePath);
        File::put($this->backupPath, str_pad("SQLite format 3\000", 4096, "\000"));
        $this->writeManifest();

        $this->artisan('thlin:db-restore', [
            'backup' => $this->backupPath,
            '--force' => true,
        ])
            ->expectsOutput('Restore preflight passed.')
            ->expectsOutput('Pre-restore backup created.')
            ->expectsOutput('Temporary SQLite validation failed.')
            ->assertExitCode(1);

        $this->assertSame($beforeChecksum, hash_file('sha256', $this->databasePath));
    }

    public function test_it_rejects_a_backup_with_foreign_key_violations_in_the_temporary_copy(): void
    {
        $this->assertNotNull($this->databasePath);
        $this->assertNotNull($this->backupPath);
        $beforeChecksum = hash_file('sha256', $this->databasePath);
        $this->createForeignKeyViolationBackup();

        $this->artisan('thlin:db-restore', [
            'backup' => $this->backupPath,
            '--force' => true,
        ])
            ->expectsOutput('Restore preflight passed.')
            ->expectsOutput('Pre-restore backup created.')
            ->expectsOutput('Temporary SQLite foreign key check failed.')
            ->assertExitCode(1);

        $this->assertSame($beforeChecksum, hash_file('sha256', $this->databasePath));
    }

    public function test_it_rejects_combining_dry_run_and_force(): void
    {
        $this->artisan('thlin:db-restore', [
            'backup' => $this->backupPath,
            '--dry-run' => true,
            '--force' => true,
        ])
            ->expectsOutput('The --dry-run and --force options cannot be used together.')
            ->assertExitCode(1);
    }

    public function test_it_rejects_a_backup_without_its_manifest(): void
    {
        $this->assertNotNull($this->manifestPath);
        File::delete($this->manifestPath);

        $this->artisan('thlin:db-restore', [
            'backup' => $this->backupPath,
            '--dry-run' => true,
        ])
            ->expectsOutput('The backup manifest is missing or unreadable.')
            ->assertExitCode(1);
    }

    public function test_it_rejects_a_non_sqlite_backup(): void
    {
        $this->assertNotNull($this->databasePath);
        $this->assertNotNull($this->backupPath);
        $this->assertNotNull($this->manifestPath);
        $beforeChecksum = hash_file('sha256', $this->databasePath);
        File::put($this->backupPath, 'not a SQLite database');
        $this->writeManifest();

        $this->artisan('thlin:db-restore', [
            'backup' => $this->backupPath,
            '--force' => true,
        ])
            ->expectsOutput('The backup file is not a SQLite database.')
            ->assertExitCode(1);

        $this->assertSame($beforeChecksum, hash_file('sha256', $this->databasePath));
    }

    public function test_it_rejects_a_backup_with_a_mismatched_checksum(): void
    {
        $this->assertNotNull($this->databasePath);
        $this->assertNotNull($this->backupPath);
        $this->assertNotNull($this->manifestPath);
        $beforeChecksum = hash_file('sha256', $this->databasePath);
        File::append($this->backupPath, 'tampered');

        $this->artisan('thlin:db-restore', [
            'backup' => $this->backupPath,
            '--force' => true,
        ])
            ->expectsOutput('The backup SHA-256 checksum does not match its manifest.')
            ->assertExitCode(1);

        $this->assertSame($beforeChecksum, hash_file('sha256', $this->databasePath));
    }

    public function test_it_rejects_an_unrelated_sqlite_database_even_when_its_manifest_hash_matches(): void
    {
        $this->assertNotNull($this->databasePath);
        $beforeChecksum = hash_file('sha256', $this->databasePath);
        $this->createUnrelatedSqliteBackup();

        $this->artisan('thlin:db-restore', [
            'backup' => $this->backupPath,
            '--force' => true,
        ])
            ->expectsOutput('Restore preflight passed.')
            ->expectsOutput('Pre-restore backup created.')
            ->expectsOutput('Temporary SQLite migration state does not match its manifest.')
            ->assertExitCode(1);

        $this->assertSame($beforeChecksum, hash_file('sha256', $this->databasePath));
    }

    private function writeManifest(): void
    {
        $this->assertNotNull($this->backupPath);
        $this->assertNotNull($this->manifestPath);
        $manifest = json_decode(File::get($this->manifestPath), true, 512, JSON_THROW_ON_ERROR);
        $manifest['backup']['size_bytes'] = filesize($this->backupPath);
        $manifest['backup']['sha256'] = hash_file('sha256', $this->backupPath);

        File::put($this->manifestPath, json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR).PHP_EOL);
    }

    private function createForeignKeyViolationBackup(): void
    {
        $this->assertNotNull($this->backupPath);
        File::delete($this->backupPath);
        $pdo = new \PDO('sqlite:'.$this->backupPath, null, null, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]);
        $pdo->exec('PRAGMA foreign_keys = OFF');
        $pdo->exec('CREATE TABLE parents (id INTEGER PRIMARY KEY)');
        $pdo->exec('CREATE TABLE children (id INTEGER PRIMARY KEY, parent_id INTEGER NOT NULL, FOREIGN KEY (parent_id) REFERENCES parents(id))');
        $pdo->exec('INSERT INTO children (id, parent_id) VALUES (1, 99)');
        unset($pdo);
        $this->writeManifest();
    }

    private function createUnrelatedSqliteBackup(): void
    {
        $this->assertNotNull($this->backupPath);
        File::delete($this->backupPath);
        $pdo = new \PDO('sqlite:'.$this->backupPath, null, null, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ]);
        $pdo->exec('CREATE TABLE unrelated_records (id INTEGER PRIMARY KEY, label TEXT NOT NULL)');
        $pdo->exec("INSERT INTO unrelated_records (label) VALUES ('not a project backup')");
        unset($pdo);
        $this->writeManifest();
    }

    private function createRestoreExerciseBaseline(): void
    {
        $timestamp = now();
        $userId = DB::table('users')->insertGetId([
            'name' => 'Restore Exercise Baseline',
            'email' => 'restore-exercise-baseline@example.test',
            'password' => 'restore-exercise-baseline-password',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);
        $this->baselineParentPageId = DB::table('pages')->insertGetId([
            'slug' => 'restore-exercise-parent',
            'title' => 'Restore Exercise Parent',
            'section' => 'general',
            'template' => 'standard',
            'page_type' => 'custom',
            'status' => 'published',
            'is_published' => true,
            'sort_order' => 0,
            'published_at' => $timestamp,
            'created_by' => $userId,
            'updated_by' => $userId,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);
        $this->baselineChildPageId = DB::table('pages')->insertGetId([
            'slug' => 'restore-exercise-child',
            'title' => 'Restore Exercise Child',
            'section' => 'general',
            'template' => 'standard',
            'page_type' => 'custom',
            'status' => 'published',
            'is_published' => true,
            'sort_order' => 1,
            'published_at' => $timestamp,
            'parent_id' => $this->baselineParentPageId,
            'created_by' => $userId,
            'updated_by' => $userId,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);
    }
}
