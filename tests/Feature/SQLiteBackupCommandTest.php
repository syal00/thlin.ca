<?php

namespace Tests\Feature;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RuntimeException;
use Tests\TestCase;

class SQLiteBackupCommandTest extends TestCase
{
    private ?string $databasePath = null;

    private ?string $backupPath = null;

    private ?string $manifestPath = null;

    protected function setUp(): void
    {
        parent::setUp();

        $databasePath = tempnam(sys_get_temp_dir(), 'thlin-sqlite-backup-');

        if ($databasePath === false) {
            throw new RuntimeException('Unable to create a temporary SQLite database file.');
        }

        $this->databasePath = $databasePath;
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', $this->databasePath);
        config()->set('database.connections.sqlite.foreign_key_constraints', true);
        DB::purge('sqlite');

        $this->artisan('migrate', ['--force' => true])->assertExitCode(0);

        $timestamp = CarbonImmutable::create(2099, 1, 1, 0, 0, 0, 'UTC')->setMicrosecond(123456);
        CarbonImmutable::setTestNow($timestamp);

        $backupBaseName = 'sqlite-backup-'.$timestamp->format('Ymd_His_u');
        $backupDirectory = storage_path('app/backups/sqlite');
        $this->backupPath = $backupDirectory.'/'.$backupBaseName.'.sqlite';
        $this->manifestPath = $backupDirectory.'/'.$backupBaseName.'.json';
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

    public function test_it_does_not_expose_password_hashes_or_database_credentials(): void
    {
        $passwordHash = 'password-hash-fixture-do-not-disclose';
        $databaseCredential = 'database-credential-fixture-do-not-disclose';

        config()->set('database.connections.sqlite.password', $databaseCredential);

        DB::table('users')->insert([
            'name' => 'Sensitive Administrator',
            'email' => 'admin@example.test',
            'password' => $passwordHash,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->artisan('thlin:db-backup')
            ->doesntExpectOutputToContain($passwordHash)
            ->doesntExpectOutputToContain($databaseCredential)
            ->assertExitCode(0);

        $this->assertNotNull($this->manifestPath);
        $manifest = File::get($this->manifestPath);

        $this->assertStringNotContainsString($passwordHash, $manifest);
        $this->assertStringNotContainsString($databaseCredential, $manifest);
    }

    public function test_it_creates_a_backup_and_manifest(): void
    {
        $this->artisan('thlin:db-backup')
            ->expectsOutput('SQLite backup created.')
            ->assertExitCode(0);

        $this->assertNotNull($this->backupPath);
        $this->assertNotNull($this->manifestPath);
        $this->assertFileExists($this->backupPath);
        $this->assertFileExists($this->manifestPath);
        $this->assertJson(File::get($this->manifestPath));
    }

    public function test_it_creates_unique_backups_when_run_twice(): void
    {
        $this->artisan('thlin:db-backup')->assertExitCode(0);
        $this->artisan('thlin:db-backup')->assertExitCode(0);

        $this->assertNotNull($this->backupPath);
        $this->assertNotNull($this->manifestPath);

        $secondBackupPath = dirname($this->backupPath).'/'.pathinfo($this->backupPath, PATHINFO_FILENAME).'-1.sqlite';
        $secondManifestPath = dirname($this->manifestPath).'/'.pathinfo($this->manifestPath, PATHINFO_FILENAME).'-1.json';

        $this->assertFileExists($this->backupPath);
        $this->assertFileExists($secondBackupPath);
        $this->assertFileExists($this->manifestPath);
        $this->assertFileExists($secondManifestPath);
        $this->assertNotSame($this->backupPath, $secondBackupPath);
    }

    public function test_it_rejects_a_non_sqlite_driver_without_creating_a_backup(): void
    {
        config()->set('database.default', 'mysql');

        $this->artisan('thlin:db-backup')
            ->expectsOutput('Configured database connection is not SQLite.')
            ->assertExitCode(1);

        $this->assertNotNull($this->backupPath);
        $this->assertFileDoesNotExist($this->backupPath);
    }

    public function test_it_rejects_a_missing_sqlite_database_without_creating_a_backup(): void
    {
        $this->assertNotNull($this->databasePath);
        config()->set('database.connections.sqlite.database', $this->databasePath.'-missing');

        $this->artisan('thlin:db-backup')
            ->expectsOutput('The configured SQLite database file is missing or unreadable.')
            ->assertExitCode(1);

        $this->assertNotNull($this->backupPath);
        $this->assertFileDoesNotExist($this->backupPath);
    }
}
