<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class BackupSqliteDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thlin:db-backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a safe backup of the configured SQLite database.';

    /**
     * Prepare the controlled backup destination.
     */
    public function handle(): int
    {
        $connectionName = config('database.default');
        $connection = is_string($connectionName)
            ? config("database.connections.{$connectionName}")
            : null;

        if (! is_array($connection) || ($connection['driver'] ?? null) !== 'sqlite') {
            $this->error('Configured database connection is not SQLite.');

            return self::FAILURE;
        }

        $databasePath = $connection['database'] ?? null;

        if (! is_string($databasePath) || $databasePath === '' || $databasePath === ':memory:') {
            $this->error('The SQLite connection must use a readable file-backed database.');

            return self::FAILURE;
        }

        if (! is_file($databasePath) || ! is_readable($databasePath)) {
            $this->error('The configured SQLite database file is missing or unreadable.');

            return self::FAILURE;
        }

        $backupDirectory = storage_path('app/backups/sqlite');

        try {
            File::ensureDirectoryExists($backupDirectory);
        } catch (\Throwable $exception) {
            $this->error('Unable to prepare the SQLite backup directory.');

            return self::FAILURE;
        }

        $backupPath = $this->uniqueBackupPath($backupDirectory);

        try {
            $pdo = DB::connection($connectionName)->getPdo();
            $quotedBackupPath = $pdo->quote($backupPath);

            if (! is_string($quotedBackupPath)) {
                throw new \RuntimeException('Unable to quote the SQLite backup path.');
            }

            $pdo->exec("VACUUM INTO {$quotedBackupPath}");
        } catch (\Throwable $exception) {
            $this->error('Unable to create the SQLite backup.');

            return self::FAILURE;
        }

        if (! is_file($backupPath) || ! is_readable($backupPath)) {
            $this->error('SQLite backup was not created successfully.');

            return self::FAILURE;
        }

        try {
            $manifestPath = $this->writeManifest($backupPath, $databasePath, $connectionName);
        } catch (\Throwable $exception) {
            File::delete($backupPath);
            $this->error('Unable to create the SQLite backup manifest.');

            return self::FAILURE;
        }

        $this->info('SQLite backup created.');
        $this->line('Backup destination: '.$backupPath);
        $this->line('Manifest: '.$manifestPath);

        return self::SUCCESS;
    }

    /**
     * Build a timestamped backup path without replacing an existing backup.
     */
    private function uniqueBackupPath(string $backupDirectory): string
    {
        $timestamp = now()->format('Ymd_His_u');
        $suffix = 0;

        do {
            $suffixLabel = $suffix === 0 ? '' : '-'.$suffix;
            $backupPath = $backupDirectory."/sqlite-backup-{$timestamp}{$suffixLabel}.sqlite";
            $suffix++;
        } while (File::exists($backupPath));

        return $backupPath;
    }

    /**
     * Write the metadata required to verify a SQLite backup later.
     */
    private function writeManifest(string $backupPath, string $databasePath, string $connectionName): string
    {
        $backupSize = filesize($backupPath);
        $backupHash = hash_file('sha256', $backupPath);

        if ($backupSize === false || $backupHash === false) {
            throw new \RuntimeException('Unable to calculate backup metadata.');
        }

        $connection = DB::connection($connectionName);
        $schema = Schema::connection($connectionName);
        $migrationFiles = glob(database_path('migrations').'/*.php') ?: [];
        $availableMigrations = array_map(
            fn (string $path): string => basename($path, '.php'),
            $migrationFiles,
        );
        sort($availableMigrations);

        $hasMigrationsTable = $schema->hasTable('migrations');
        $ranMigrations = $hasMigrationsTable
            ? $connection->table('migrations')->pluck('migration')->all()
            : [];
        $pendingMigrations = array_values(array_diff($availableMigrations, $ranMigrations));
        $unknownMigrations = array_values(array_diff($ranMigrations, $availableMigrations));
        $keyTableCounts = [];

        foreach ([
            'users',
            'pages',
            'news_posts',
            'careers',
            'board_members',
            'portfolio_items',
            'media_files',
            'contact_messages',
            'site_settings',
        ] as $table) {
            $keyTableCounts[$table] = $schema->hasTable($table)
                ? $connection->table($table)->count()
                : null;
        }

        $manifestPath = dirname($backupPath).'/'.pathinfo($backupPath, PATHINFO_FILENAME).'.json';
        $manifest = [
            'source' => $databasePath,
            'created_at' => now()->toAtomString(),
            'backup' => [
                'path' => $backupPath,
                'size_bytes' => $backupSize,
                'sha256' => $backupHash,
            ],
            'migrations' => [
                'status' => ! $hasMigrationsTable
                    ? 'unavailable'
                    : ($pendingMigrations === [] && $unknownMigrations === [] ? 'current' : 'out_of_sync'),
                'applied_count' => count($ranMigrations),
                'available_count' => count($availableMigrations),
                'pending' => $pendingMigrations,
                'unknown' => $unknownMigrations,
            ],
            'key_table_counts' => $keyTableCounts,
        ];

        $encodedManifest = json_encode(
            $manifest,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR,
        );

        if (File::put($manifestPath, $encodedManifest.PHP_EOL) === false) {
            throw new \RuntimeException('Unable to write the backup manifest.');
        }

        return $manifestPath;
    }
}
