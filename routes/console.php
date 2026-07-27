<?php

use App\Services\ThlinContentImporter;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('thlin:import-content', function () {
    $importer = app(ThlinContentImporter::class);

    $this->info('Importing content from https://thlin.ca …');
    $importer->import(function (string $message): void {
        $this->line($message);
    });
    $this->info('Done.');
})->purpose('Import pages, news, careers, board, and portfolio from thlin.ca');

Artisan::command('thlin:db-check', function () {
    $failures = [];
    $connectionName = config('database.default');
    $connectionConfig = config("database.connections.{$connectionName}");

    if (($connectionConfig['driver'] ?? null) !== 'sqlite') {
        $this->error("Configured database connection [{$connectionName}] is not SQLite.");

        return 1;
    }

    $databaseFile = $connectionConfig['database'] ?? null;

    if (! is_string($databaseFile) || $databaseFile === '' || $databaseFile === ':memory:') {
        $this->error('The SQLite connection must use a file-backed database.');

        return 1;
    }

    if (! is_file($databaseFile)) {
        $this->error("SQLite database file is missing: {$databaseFile}");

        return 1;
    }

    if (! is_readable($databaseFile)) {
        $failures[] = "SQLite database file is not readable: {$databaseFile}";
    }

    if (! is_writable($databaseFile)) {
        $failures[] = "SQLite database file is not writable: {$databaseFile}";
    }

    if ($failures !== []) {
        foreach ($failures as $failure) {
            $this->error($failure);
        }

        return 1;
    }

    try {
        $connection = DB::connection($connectionName);
        $connection->getPdo();
    } catch (\Throwable $exception) {
        $this->error("Unable to connect to the SQLite database: {$exception->getMessage()}");

        return 1;
    }

    $requiredTables = [
        'migrations',
        'users',
        'password_reset_tokens',
        'sessions',
        'cache',
        'cache_locks',
        'jobs',
        'job_batches',
        'failed_jobs',
        'pages',
        'news_posts',
        'careers',
        'board_members',
        'portfolio_items',
        'media_files',
        'contact_messages',
        'site_settings',
    ];

    $existingTables = array_map(
        fn (object $row): string => $row->name,
        $connection->select("SELECT name FROM sqlite_master WHERE type = 'table'")
    );
    $missingTables = array_values(array_diff($requiredTables, $existingTables));

    if ($missingTables !== []) {
        $this->error('Required SQLite tables are missing: '.implode(', ', $missingTables));

        return 1;
    }

    $migrationFiles = glob(database_path('migrations').'/*.php') ?: [];
    $availableMigrations = array_map(
        fn (string $path): string => basename($path, '.php'),
        $migrationFiles
    );
    sort($availableMigrations);

    $ranMigrations = $connection->table('migrations')->pluck('migration')->all();
    $pendingMigrations = array_values(array_diff($availableMigrations, $ranMigrations));
    $unknownMigrations = array_values(array_diff($ranMigrations, $availableMigrations));

    if ($pendingMigrations !== []) {
        $failures[] = 'Pending migrations: '.implode(', ', $pendingMigrations);
    }

    if ($unknownMigrations !== []) {
        $failures[] = 'Migration records without files: '.implode(', ', $unknownMigrations);
    }

    $integrityRows = $connection->select('PRAGMA integrity_check');
    $integrityResults = array_map(function (object $row): string {
        $values = array_values((array) $row);

        return (string) ($values[0] ?? '');
    }, $integrityRows);

    if ($integrityResults !== ['ok']) {
        $failures[] = 'PRAGMA integrity_check failed: '.implode('; ', $integrityResults);
    }

    $foreignKeyViolations = $connection->select('PRAGMA foreign_key_check');

    if ($foreignKeyViolations !== []) {
        $failures[] = 'PRAGMA foreign_key_check found '.count($foreignKeyViolations).' violation(s).';
    }

    $keyTables = [
        'users',
        'pages',
        'news_posts',
        'careers',
        'board_members',
        'portfolio_items',
        'media_files',
        'contact_messages',
        'site_settings',
    ];
    $tableCounts = [];

    foreach ($keyTables as $table) {
        $tableCounts[$table] = $connection->table($table)->count();
    }

    foreach ([
        ['pages', 'parent_id', 'pages'],
        ['pages', 'created_by', 'users'],
        ['pages', 'updated_by', 'users'],
        ['media_files', 'uploaded_by', 'users'],
        ['sessions', 'user_id', 'users'],
    ] as [$childTable, $column, $parentTable]) {
        $orphanCount = $connection->table("{$childTable} as child")
            ->leftJoin("{$parentTable} as parent", "child.{$column}", '=', 'parent.id')
            ->whereNotNull("child.{$column}")
            ->whereNull('parent.id')
            ->count();

        if ($orphanCount > 0) {
            $failures[] = "Orphaned {$childTable}.{$column} references: {$orphanCount}";
        }
    }

    $duplicateEmails = $connection->table('users')
        ->selectRaw('LOWER(TRIM(email)) AS normalized_email, COUNT(*) AS duplicate_count')
        ->groupByRaw('LOWER(TRIM(email))')
        ->havingRaw('COUNT(*) > 1')
        ->get()
        ->count();

    if ($duplicateEmails > 0) {
        $failures[] = "Duplicate administrator email groups: {$duplicateEmails}";
    }

    $pages = $connection->table('pages')
        ->select('id', 'parent_id', 'page_type', 'status', 'slug')
        ->get();
    $pagesById = [];

    foreach ($pages as $page) {
        $pagesById[(int) $page->id] = $page;
    }

    $allowedParentSlugs = [
        'products-services',
        'partners',
        'about',
        'contact',
        'careers',
        'board',
        'news',
        'portfolio',
    ];
    $invalidParentCount = 0;
    $circularParentCount = 0;

    foreach ($pages as $page) {
        if ($page->parent_id === null) {
            continue;
        }

        $parent = $pagesById[(int) $page->parent_id] ?? null;

        if (
            $parent === null
            || (int) $page->id === (int) $page->parent_id
            || $page->page_type !== 'custom'
            || $parent->status !== 'published'
            || $parent->parent_id !== null
            || ($parent->page_type !== 'custom' && ! in_array($parent->slug, $allowedParentSlugs, true))
        ) {
            $invalidParentCount++;
        }

        $visitedPageIds = [];
        $currentPage = $page;

        while ($currentPage->parent_id !== null) {
            $currentPageId = (int) $currentPage->id;

            if (isset($visitedPageIds[$currentPageId])) {
                $circularParentCount++;
                break;
            }

            $visitedPageIds[$currentPageId] = true;
            $currentPage = $pagesById[(int) $currentPage->parent_id] ?? null;

            if ($currentPage === null) {
                break;
            }
        }
    }

    if ($invalidParentCount > 0) {
        $failures[] = "Invalid page parent relationships: {$invalidParentCount}";
    }

    if ($circularParentCount > 0) {
        $failures[] = "Circular page parent relationships: {$circularParentCount}";
    }

    if ($failures !== []) {
        $this->error('SQLite database check failed:');

        foreach ($failures as $failure) {
            $this->line("- {$failure}");
        }

        return 1;
    }

    $countSummary = implode(', ', array_map(
        fn (string $table, int $count): string => "{$table}={$count}",
        array_keys($tableCounts),
        $tableCounts
    ));

    $this->info('SQLite database check passed.');
    $this->line('Key table counts: '.$countSummary);

    return 0;
})->purpose('Run read-only SQLite integrity and schema checks');
