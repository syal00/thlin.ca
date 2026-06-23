<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Schema;
use RuntimeException;
use Throwable;

class ImportSqliteToPostgres extends Command
{
    private const DEFAULT_TABLES = [
        'pages',
        'news_posts',
        'careers',
        'board_members',
        'portfolio_items',
        'site_settings',
    ];

    private const BOOLEAN_COLUMNS = [
        'pages' => ['is_published', 'show_in_navigation'],
        'news_posts' => ['is_published'],
        'careers' => ['is_active'],
        'portfolio_items' => ['featured'],
    ];

    protected $signature = 'thlin:import-sqlite
        {source=database/database.sqlite : SQLite database path, relative to the project root by default}
        {--destination= : Destination database connection name; defaults to DB_CONNECTION}
        {--dry-run : Validate the source and report row counts without writing to the destination}
        {--fresh : Delete previously imported rows from the selected destination tables before importing}
        {--force : Allow --fresh without an interactive confirmation}
        {--include-media : Import media metadata only; referenced local files are not copied}
        {--include-users : Import user accounts and password hashes}
        {--include-contact-messages : Import contact form messages}';

    protected $description = 'Import selected THLIN data from SQLite into a migrated PostgreSQL database.';

    public function __construct(private readonly DatabaseManager $database)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        try {
            $sourcePath = $this->resolveSourcePath();
            $destination = $this->option('destination') ?: config('database.default');
            $destinationConnection = $this->database->connection($destination);

            $this->ensurePostgresDestination($destinationConnection, $destination);

            $sourceName = 'sqlite_import_source';
            $this->configureSourceConnection($sourceName, $sourcePath);
            $sourceConnection = $this->database->connection($sourceName);
            $tables = $this->selectedTables();

            $tables = $this->availableTables($sourceName, $destination, $tables);

            if ($this->option('dry-run')) {
                $this->reportDryRun($sourceConnection, $tables);

                return self::SUCCESS;
            }

            if ($this->option('fresh') && ! $this->option('force') && ! $this->confirm(
                'Delete current rows from the selected destination tables before importing?',
            )) {
                $this->warn('Import cancelled.');

                return self::FAILURE;
            }

            $counts = [];

            $destinationConnection->transaction(function () use ($sourceConnection, $destinationConnection, $destination, $tables, &$counts): void {
                if ($this->option('fresh')) {
                    $this->clearDestinationTables($destinationConnection, $tables);
                }

                $pageParents = [];

                foreach ($tables as $table) {
                    $columns = $this->sharedColumns($table, 'sqlite_import_source', $destination);
                    $counts[$table] = $this->importTable(
                        $sourceConnection,
                        $destinationConnection,
                        $table,
                        $columns,
                        $pageParents,
                    );
                }

                $this->restorePageParents($destinationConnection, $pageParents);
                $this->synchronisePostgresSequences($destinationConnection, $tables);
            });

            foreach ($counts as $table => $count) {
                $this->line("Imported {$count} row(s) into {$table}.");
            }

            if ($this->option('include-media')) {
                $this->warn('Media metadata was imported, but files must be moved to Vercel Blob separately.');
            }

            $this->info('SQLite import completed successfully.');

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        } finally {
            $this->database->purge('sqlite_import_source');
        }
    }

    private function resolveSourcePath(): string
    {
        $source = (string) $this->argument('source');
        $path = $this->isAbsolutePath($source) ? $source : base_path($source);
        $resolvedPath = realpath($path);

        if ($resolvedPath === false || ! is_file($resolvedPath) || ! is_readable($resolvedPath)) {
            throw new RuntimeException("SQLite source database is not readable: {$path}");
        }

        return $resolvedPath;
    }

    private function isAbsolutePath(string $path): bool
    {
        return str_starts_with($path, DIRECTORY_SEPARATOR)
            || str_starts_with($path, '\\')
            || preg_match('/^[A-Za-z]:[\\\\\/]/', $path) === 1;
    }

    private function ensurePostgresDestination(ConnectionInterface $connection, string $name): void
    {
        if (app()->environment('testing')) {
            return;
        }

        if ($connection->getDriverName() !== 'pgsql') {
            throw new RuntimeException("Destination connection [{$name}] must use PostgreSQL. Set DB_CONNECTION=pgsql first.");
        }
    }

    private function configureSourceConnection(string $name, string $path): void
    {
        config([
            "database.connections.{$name}" => [
                'driver' => 'sqlite',
                'database' => $path,
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
        ]);

        $this->database->purge($name);
    }

    /**
     * @return list<string>
     */
    private function selectedTables(): array
    {
        $tables = self::DEFAULT_TABLES;

        if ($this->option('include-users')) {
            array_unshift($tables, 'users');
        }

        if ($this->option('include-media')) {
            $tables[] = 'media_files';
        }

        if ($this->option('include-contact-messages')) {
            $tables[] = 'contact_messages';
        }

        return $tables;
    }

    /**
     * @param  list<string>  $tables
     */
    private function availableTables(string $source, string $destination, array $tables): array
    {
        $availableTables = [];

        foreach ($tables as $table) {
            if (! Schema::connection($destination)->hasTable($table)) {
                throw new RuntimeException("The destination does not contain [{$table}]. Run php artisan migrate --force first.");
            }

            if (! Schema::connection($source)->hasTable($table)) {
                if ($table === 'pages' || $this->wasExplicitlyRequested($table)) {
                    throw new RuntimeException("The SQLite source does not contain the required [{$table}] table.");
                }

                $this->warn("Skipping [{$table}] because the SQLite source predates that table.");

                continue;
            }

            $availableTables[] = $table;
        }

        return $availableTables;
    }

    private function wasExplicitlyRequested(string $table): bool
    {
        return match ($table) {
            'users' => (bool) $this->option('include-users'),
            'media_files' => (bool) $this->option('include-media'),
            'contact_messages' => (bool) $this->option('include-contact-messages'),
            default => false,
        };
    }

    /**
     * @param  list<string>  $tables
     */
    private function reportDryRun(ConnectionInterface $source, array $tables): void
    {
        $this->info('Dry run: no destination data will be changed.');

        foreach ($tables as $table) {
            $this->line("{$table}: ".$source->table($table)->count().' source row(s)');
        }
    }

    /**
     * @param  list<string>  $tables
     */
    private function clearDestinationTables(ConnectionInterface $destination, array $tables): void
    {
        foreach (array_reverse($tables) as $table) {
            $destination->table($table)->delete();
        }
    }

    /**
     * @return list<string>
     */
    private function sharedColumns(string $table, string $source, string $destination): array
    {
        $sourceColumns = Schema::connection($source)->getColumnListing($table);
        $destinationColumns = Schema::connection($destination)->getColumnListing($table);
        $columns = array_values(array_intersect($sourceColumns, $destinationColumns));

        if (! in_array('id', $columns, true)) {
            throw new RuntimeException("The [{$table}] table must expose an id column in both databases.");
        }

        return $columns;
    }

    /**
     * @param  list<string>  $columns
     * @param  array<int, int|null>  $pageParents
     */
    private function importTable(
        ConnectionInterface $source,
        ConnectionInterface $destination,
        string $table,
        array $columns,
        array &$pageParents,
    ): int {
        $count = 0;
        $updateColumns = array_values(array_diff($columns, ['id']));

        $source->table($table)->orderBy('id')->chunkById(200, function ($rows) use ($destination, $table, $columns, $updateColumns, &$pageParents, &$count): void {
            $records = [];

            foreach ($rows as $row) {
                $records[] = $this->normaliseRecord($table, (array) $row, $columns, $pageParents);
            }

            if ($records !== []) {
                $destination->table($table)->upsert($records, ['id'], $updateColumns);
                $count += count($records);
            }
        });

        return $count;
    }

    /**
     * @param  array<string, mixed>  $record
     * @param  list<string>  $columns
     * @param  array<int, int|null>  $pageParents
     * @return array<string, mixed>
     */
    private function normaliseRecord(string $table, array $record, array $columns, array &$pageParents): array
    {
        $record = array_intersect_key($record, array_flip($columns));

        foreach (self::BOOLEAN_COLUMNS[$table] ?? [] as $column) {
            if (array_key_exists($column, $record) && $record[$column] !== null) {
                $record[$column] = (bool) $record[$column];
            }
        }

        if ($table === 'pages' && array_key_exists('parent_id', $record)) {
            $pageParents[(int) $record['id']] = $record['parent_id'] === null ? null : (int) $record['parent_id'];
            $record['parent_id'] = null;
        }

        if ($table === 'media_files' && ! $this->option('include-users') && array_key_exists('uploaded_by', $record)) {
            $record['uploaded_by'] = null;
        }

        return $record;
    }

    /**
     * @param  array<int, int|null>  $pageParents
     */
    private function restorePageParents(ConnectionInterface $destination, array $pageParents): void
    {
        foreach ($pageParents as $pageId => $parentId) {
            if ($parentId !== null) {
                $destination->table('pages')->where('id', $pageId)->update(['parent_id' => $parentId]);
            }
        }
    }

    /**
     * @param  list<string>  $tables
     */
    private function synchronisePostgresSequences(ConnectionInterface $destination, array $tables): void
    {
        if ($destination->getDriverName() !== 'pgsql') {
            return;
        }

        foreach ($tables as $table) {
            $sequence = $destination->selectOne(
                'select pg_get_serial_sequence(?, ?) as sequence',
                [$table, 'id'],
            )->sequence;

            if ($sequence === null) {
                continue;
            }

            $maximumId = $destination->table($table)->max('id');
            $destination->select('select setval(?, ?, ?)', [
                $sequence,
                $maximumId ?? 1,
                $maximumId !== null,
            ]);
        }
    }
}
