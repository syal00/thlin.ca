<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use JsonException;

class RestoreSqliteDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thlin:db-restore
                            {backup : Absolute path to the SQLite backup file}
                            {--dry-run : Validate the backup without modifying the configured database}
                            {--force : Confirm that a future restore may replace the configured database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run guarded preflight checks for a SQLite database restore.';

    /**
     * Validate a backup and its manifest without changing any database file.
     */
    public function handle(): int
    {
        if ($this->option('dry-run') && $this->option('force')) {
            $this->error('The --dry-run and --force options cannot be used together.');

            return self::FAILURE;
        }

        $backupPath = $this->argument('backup');

        if (! is_string($backupPath) || $backupPath === '' || ! is_file($backupPath) || ! is_readable($backupPath)) {
            $this->error('The SQLite backup file is missing or unreadable.');

            return self::FAILURE;
        }

        if (! $this->hasSqliteHeader($backupPath)) {
            $this->error('The backup file is not a SQLite database.');

            return self::FAILURE;
        }

        $manifestPath = dirname($backupPath).'/'.pathinfo($backupPath, PATHINFO_FILENAME).'.json';

        if (! is_file($manifestPath) || ! is_readable($manifestPath)) {
            $this->error('The backup manifest is missing or unreadable.');

            return self::FAILURE;
        }

        try {
            $manifest = $this->readManifest($manifestPath);
        } catch (JsonException|\RuntimeException $exception) {
            $this->error('The backup manifest is invalid.');

            return self::FAILURE;
        }

        $actualHash = hash_file('sha256', $backupPath);

        if ($actualHash === false || ! hash_equals($manifest['backup']['sha256'], $actualHash)) {
            $this->error('The backup SHA-256 checksum does not match its manifest.');

            return self::FAILURE;
        }

        $actualSize = filesize($backupPath);

        if ($actualSize === false || $manifest['backup']['size_bytes'] !== $actualSize) {
            $this->error('The backup size does not match its manifest.');

            return self::FAILURE;
        }

        $this->info('Restore preflight passed.');
        $this->line('Migration status: '.$manifest['migrations']['status']);

        if ($this->option('force')) {
            try {
                $backupExitCode = Artisan::call('thlin:db-backup');
            } catch (\Throwable $exception) {
                $backupExitCode = self::FAILURE;
            }

            if ($backupExitCode !== self::SUCCESS) {
                $this->error('Unable to create a pre-restore backup; the restore was not attempted.');

                return self::FAILURE;
            }

            $this->info('Pre-restore backup created.');
            $validationError = $this->validateTemporaryCopy($backupPath, $manifest);

            if ($validationError !== null) {
                $this->error($validationError);

                return self::FAILURE;
            }

            $this->info('Temporary restore validation passed.');
            $restoreError = $this->restoreConfiguredDatabase($backupPath);

            if ($restoreError !== null) {
                $this->error($restoreError);

                return self::FAILURE;
            }

            $this->info('SQLite database restored.');

            return self::SUCCESS;
        }

        $this->line('No database changes were made.');
        $this->line('Re-run with --force only after confirming the preflight result.');

        return self::SUCCESS;
    }

    /**
     * Confirm the database header before later restore steps inspect its contents.
     */
    private function hasSqliteHeader(string $backupPath): bool
    {
        $handle = fopen($backupPath, 'rb');

        if ($handle === false) {
            return false;
        }

        try {
            $header = fread($handle, 16);
        } finally {
            fclose($handle);
        }

        return $header === "SQLite format 3\000";
    }

    /**
     * Copy a candidate backup to a controlled temporary file and validate it there.
     */
    private function validateTemporaryCopy(string $backupPath, array $manifest): ?string
    {
        $temporaryPath = tempnam(sys_get_temp_dir(), 'thlin-sqlite-restore-');

        if ($temporaryPath === false) {
            return 'Unable to create a temporary copy for restore validation.';
        }

        try {
            if (! File::copy($backupPath, $temporaryPath)) {
                return 'Unable to prepare a temporary copy for restore validation.';
            }

            $pdo = new \PDO('sqlite:'.$temporaryPath, null, null, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]);
            $integrityStatement = $pdo->query('PRAGMA integrity_check');
            $integrityResults = $integrityStatement === false
                ? []
                : $integrityStatement->fetchAll(\PDO::FETCH_COLUMN);

            if ($integrityResults !== ['ok']) {
                return 'Temporary SQLite integrity check failed.';
            }

            $foreignKeyStatement = $pdo->query('PRAGMA foreign_key_check');
            $foreignKeyViolations = $foreignKeyStatement === false
                ? [['query_failed' => true]]
                : $foreignKeyStatement->fetchAll(\PDO::FETCH_ASSOC);

            if ($foreignKeyViolations !== []) {
                return 'Temporary SQLite foreign key check failed.';
            }

            if (! $this->migrationStateMatchesManifest($pdo, $manifest['migrations'])) {
                return 'Temporary SQLite migration state does not match its manifest.';
            }
        } catch (\Throwable $exception) {
            return 'Temporary SQLite validation failed.';
        } finally {
            File::delete($temporaryPath);
        }

        return null;
    }

    /**
     * Confirm that the candidate database carries the migration state recorded at backup time.
     */
    private function migrationStateMatchesManifest(\PDO $pdo, array $expectedState): bool
    {
        $migrationTableExists = (bool) $pdo
            ->query("SELECT EXISTS(SELECT 1 FROM sqlite_master WHERE type = 'table' AND name = 'migrations')")
            ?->fetchColumn();
        $ranMigrations = $migrationTableExists
            ? $pdo->query('SELECT migration FROM migrations')?->fetchAll(\PDO::FETCH_COLUMN) ?? []
            : [];
        $availableMigrations = array_map(
            fn (string $path): string => basename($path, '.php'),
            glob(database_path('migrations').'/*.php') ?: [],
        );

        sort($ranMigrations);
        sort($availableMigrations);
        $pendingMigrations = array_values(array_diff($availableMigrations, $ranMigrations));
        $unknownMigrations = array_values(array_diff($ranMigrations, $availableMigrations));
        $actualStatus = ! $migrationTableExists
            ? 'unavailable'
            : ($pendingMigrations === [] && $unknownMigrations === [] ? 'current' : 'out_of_sync');
        $expectedPending = $expectedState['pending'];
        $expectedUnknown = $expectedState['unknown'];

        sort($expectedPending);
        sort($expectedUnknown);

        return $actualStatus === $expectedState['status']
            && count($ranMigrations) === $expectedState['applied_count']
            && count($availableMigrations) === $expectedState['available_count']
            && $pendingMigrations === $expectedPending
            && $unknownMigrations === $expectedUnknown;
    }

    /**
     * Replace the configured SQLite file only after every guard has passed.
     */
    private function restoreConfiguredDatabase(string $backupPath): ?string
    {
        $connectionName = config('database.default');
        $connection = is_string($connectionName)
            ? config("database.connections.{$connectionName}")
            : null;
        $databasePath = is_array($connection) ? ($connection['database'] ?? null) : null;

        if (! is_string($connectionName)
            || ! is_array($connection)
            || ($connection['driver'] ?? null) !== 'sqlite'
            || ! is_string($databasePath)
            || $databasePath === ''
            || $databasePath === ':memory:'
            || ! is_file($databasePath)) {
            return 'The configured SQLite database cannot be safely restored.';
        }

        $stagedRestorePath = tempnam(dirname($databasePath), basename($databasePath).'.restore-');

        if ($stagedRestorePath === false) {
            return 'Unable to prepare the staged SQLite restore file.';
        }

        try {
            if (! File::copy($backupPath, $stagedRestorePath)) {
                return 'Unable to stage the SQLite backup for restoration.';
            }

            DB::purge($connectionName);

            if (! rename($stagedRestorePath, $databasePath)) {
                return 'Unable to replace the configured SQLite database. The restore was not completed.';
            }
        } catch (\Throwable $exception) {
            return 'Unable to replace the configured SQLite database. The restore was not completed.';
        } finally {
            if (is_file($stagedRestorePath)) {
                File::delete($stagedRestorePath);
            }
        }

        return null;
    }

    /**
     * Read only the manifest data required to establish restore provenance.
     *
     * @return array{backup: array{sha256: string, size_bytes: int}, migrations: array{status: string, applied_count: int, available_count: int, pending: array<int, string>, unknown: array<int, string>}}
     */
    private function readManifest(string $manifestPath): array
    {
        $manifest = json_decode(File::get($manifestPath), true, 512, JSON_THROW_ON_ERROR);

        if (! is_array($manifest)
            || ! isset($manifest['backup'])
            || ! is_array($manifest['backup'])
            || ! isset($manifest['backup']['sha256'], $manifest['backup']['size_bytes'])
            || ! is_string($manifest['backup']['sha256'])
            || ! is_int($manifest['backup']['size_bytes'])
            || ! isset($manifest['migrations'])
            || ! is_array($manifest['migrations'])
            || ! isset(
                $manifest['migrations']['status'],
                $manifest['migrations']['applied_count'],
                $manifest['migrations']['available_count'],
                $manifest['migrations']['pending'],
                $manifest['migrations']['unknown'],
            )
            || ! is_string($manifest['migrations']['status'])
            || ! is_int($manifest['migrations']['applied_count'])
            || ! is_int($manifest['migrations']['available_count'])
            || ! is_array($manifest['migrations']['pending'])
            || ! is_array($manifest['migrations']['unknown'])
            || array_filter(
                [...$manifest['migrations']['pending'], ...$manifest['migrations']['unknown']],
                fn (mixed $migration): bool => ! is_string($migration),
            ) !== []) {
            throw new \RuntimeException('Manifest is missing required restore metadata.');
        }

        return $manifest;
    }
}
