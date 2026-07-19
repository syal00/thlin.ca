# Phase 0 Environment Checklist

Phase 0 establishes the storage boundaries before production CMS writes are enabled.

## Local Development

- Keep `.env` on `DB_CONNECTION=sqlite`.
- Keep the SQLite file at `database/database.sqlite`.
- Keep `FILESYSTEM_DISK=local`; uploads may use Laravel's local public disk while developing.
- Run `php artisan migrate --seed` and `php artisan test` locally.

## Vercel Production And Preview

1. Provision a managed PostgreSQL database and obtain its SSL-enabled connection URL.
2. Create or connect a public Vercel Blob store for site images, PDFs, and documents.
3. In Vercel Project Settings, add these variables to both Production and Preview:

   ```env
   DB_CONNECTION=pgsql
   DATABASE_URL=postgresql://...
   DB_SSLMODE=require
   BLOB_READ_WRITE_TOKEN=vercel_blob_rw_...
   ```

4. Run `php artisan migrate --force` against the PostgreSQL database from a trusted deployment or CI environment.
5. Deploy and confirm the application reads CMS records from PostgreSQL.

If the Vercel project owner cannot add developers without upgrading the Vercel plan, keep the no-upgrade workflow: the administrator performs these Vercel steps and developers continue through GitHub. Use `docs/admin-managed-vercel-setup.md` as the handoff checklist.

## Phase 0 Exit Conditions

- Local development remains on SQLite.
- Production and Preview are connected to PostgreSQL.
- The Blob token is available to Vercel functions but is not committed to Git.
- No production CMS upload action is enabled until Phase 1 changes the current local-disk upload controllers to write to Vercel Blob.
