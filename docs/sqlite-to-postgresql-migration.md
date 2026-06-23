# SQLite To PostgreSQL Migration Runbook

## Scope

This runbook moves the current local SQLite content into an already-created PostgreSQL database for the `thlin-preview` environment. It does not move the production database, Vercel environment secrets, or uploaded files.

The import command preserves record IDs so page parent relationships and public URLs remain stable. It is safe to repeat: by default it upserts source rows; `--fresh --force` replaces the selected destination content with the source snapshot.

## Migration Audit

All current migrations can run on PostgreSQL without a schema-blocking change.

| Area | Result | Notes |
| --- | --- | --- |
| Laravel schema-builder migrations | Compatible | `id`, text, timestamps, indexes, foreign keys, and boolean fields are supported by PostgreSQL. |
| `pages.meta_description` conversion | Compatible | The migration has an explicit PostgreSQL `ALTER COLUMN ... TYPE TEXT` branch. |
| `after()` column placement modifiers | Compatible with no positional effect | PostgreSQL ignores column ordering; this does not change application behavior. |
| data updates inside migrations | Compatible | The `DB::table()` updates and landing-page inserts work on PostgreSQL. |
| unsigned integer columns | Follow-up required | PostgreSQL does not enforce unsigned values. Existing application values are non-negative, but a future migration should add `CHECK` constraints for fields such as `sort_order`, queue counters, and file sizes. |

`migrate:fresh` should be tested against the new PostgreSQL database before importing. Do not use it against a database containing data that must be retained.

## What The Import Includes

By default, the command imports public CMS content only:

- `pages`
- `news_posts`
- `careers`
- `board_members`
- `portfolio_items`
- `site_settings`

It deliberately excludes `users`, `contact_messages`, sessions, jobs, cache tables, password-reset tokens, and failed jobs.

The command supports older SQLite snapshots. A default public-content table introduced after the snapshot, such as `site_settings`, is reported and skipped. The `pages` table is always required. Explicitly requested tables must exist in the source.

Optional flags are available for exceptional cases:

- `--include-users`: copies accounts and password hashes. Use only for a controlled Preview environment.
- `--include-contact-messages`: copies potentially sensitive public submissions. Do not use for Preview.
- `--include-media`: copies only `media_files` metadata. It does not copy PDFs or images, and local file paths will not work on Vercel until the files are moved to Blob storage.

## Prepare PostgreSQL

1. Create a dedicated PostgreSQL database for `thlin-preview`.
2. Configure a local, uncommitted environment with the Preview database URL:

   ```dotenv
   DB_CONNECTION=pgsql
   DATABASE_URL=postgresql://USER:PASSWORD@HOST:5432/DATABASE?sslmode=require
   DB_SSLMODE=require
   ```

3. From the project root, run the schema migrations against the empty database:

   ```bash
   php artisan migrate --force
   ```

## Dry Run And Import

Confirm the source database and row counts first:

```bash
php artisan thlin:import-sqlite database/database.sqlite --dry-run
```

For an empty Preview database, import the public content snapshot:

```bash
php artisan thlin:import-sqlite database/database.sqlite --fresh --force
```

The command executes one destination transaction. If a foreign-key or data-type error occurs, PostgreSQL rolls back the import.

## Verify Before Vercel Deployment

Run these checks against PostgreSQL:

```bash
php artisan tinker --execute="dump([\App\Models\Page::count(), \App\Models\NewsPost::count(), \App\Models\Career::count()]);"
php artisan test --filter=ImportSqliteToPostgresTest
```

Then add the same database URL to the `thlin-preview` Vercel project as `DATABASE_URL`, with `DB_CONNECTION=pgsql` and `DB_SSLMODE=require`, and redeploy.

## Later Production Cutover

When original-project access is available, take a database backup, repeat the import from an approved production export, validate the Preview URL, and only then schedule the production switch. Do not transfer the local SQLite file to Vercel or commit it to Git.
