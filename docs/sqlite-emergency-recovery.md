# SQLite Emergency Recovery and Rollback

This procedure applies to a local or controlled single-instance environment whose default database connection is file-backed SQLite. A restore replaces the database file configured for that connection; run it only when a confirmed operational rollback is necessary.

Platforms such as Vercel, which have no durable shared filesystem or may run multiple instances, are not suitable for this recovery process. CMS writes on those platforms must not be treated as durable. Any production requirement for durable CMS data must move to SQL Server after client approval; do not run these commands on a remote function instance, PostgreSQL, Neon, or SQL Server.

## Prepare for Recovery

1. Record the incident time, symptoms, and path to the backup that you intend to restore.
2. Pause web requests, queue workers, and scheduled tasks that can write to the database. Run `php artisan down` first if necessary.
3. Confirm that the candidate backup was created by `thlin:db-backup` and that the matching `.json` manifest is in the same directory.
4. Do not manually copy, rename, or overwrite `database.sqlite`; use the commands below to preserve the built-in preflight, backup, and validation safeguards.

Backups are stored by default in `storage/app/backups/sqlite/`, which is Git-ignored.

## Run the Preflight First

Replace the path below with the absolute path to the candidate `.sqlite` backup:

```bash
php artisan thlin:db-restore /absolute/path/to/sqlite-backup-YYYYMMDD_HHMMSS_microseconds.sqlite --dry-run
```

The preflight validates the following without modifying the configured database:

- The backup file and matching manifest exist and are readable.
- The file has the SQLite format, and its SHA-256 checksum and size match the manifest.
- The manifest includes the recorded migration state.
- Without `--force`, the command performs preflight only and does not replace the database.

Continue only after `Restore preflight passed.` is displayed and the reported migration state is expected.

## Perform the Restore

```bash
php artisan thlin:db-restore /absolute/path/to/sqlite-backup-YYYYMMDD_HHMMSS_microseconds.sqlite --force
```

`--force` is the explicit confirmation that the configured database may be replaced. The command performs these steps in order:

1. Runs the preflight again.
2. Creates a fresh safeguard backup and manifest of the current database with `thlin:db-backup`.
3. Copies the candidate backup to a controlled temporary file, runs `PRAGMA integrity_check` and `PRAGMA foreign_key_check`, and compares its migration state with the manifest.
4. Replaces the configured SQLite database file only after every check passes.

On success, the command displays `SQLite database restored.`. Restart paused services afterwards; if maintenance mode was enabled, run:

```bash
php artisan up
```

## Verify After Recovery

1. Check key pages and CMS login to confirm that expected content can be read.
2. Confirm that key table counts, parent/child page relationships, and administrator access match the backup baseline.
3. Check migration status for unexpected pending or unknown migrations:

```bash
php artisan migrate:status
```

4. Run the read-only SQLite health check:

```bash
php artisan thlin:db-check
```

5. Keep the candidate backup path, the automatically created pre-restore backup path, execution time, and verification results in the maintenance record.

## Failure and Rollback

The restore stops without replacing the configured database if any of the following occurs: a missing or invalid manifest, SHA-256 or size mismatch, non-SQLite file, database-integrity error, foreign-key violation, migration-state mismatch, or pre-restore backup failure.

If a completed restore does not produce the expected result:

1. Keep writes paused; do not manually modify or overwrite the database file.
2. Locate the newest pre-restore backup and manifest automatically created by the restore command in `storage/app/backups/sqlite/`.
3. Run `--dry-run` against that pre-restore backup first. After a successful preflight, restore it with `--force`.
4. Repeat the post-recovery verification steps and record the rollback reason and outcome.

If the preflight or restore command fails, retain the command output and candidate backup. Do not bypass the safeguards with a forced file copy; investigate the manifest, disk permissions, SQLite integrity, and configured database path first.
