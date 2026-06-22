# Vercel Data Architecture Plan

## Current Deployment

- Production/preview URL: `https://thlin-ca.vercel.app/`
- Runtime: Laravel on Vercel through `vercel-php`
- Current local database: SQLite at `database/database.sqlite`
- Current local upload disk: Laravel `public` disk backed by `storage/app/public`

The deployed URL responds from Vercel, so future user editing and uploads must be planned for a serverless runtime.

## Phase 0 Decision

For Vercel deployment, application data must be split into two persistent services:

1. PostgreSQL for structured data:
   - pages
   - page revisions
   - users and roles
   - media metadata
   - media usage references
   - audit logs

2. Vercel Blob for uploaded file bodies:
   - images
   - PDFs
   - documents
   - future large media assets

SQLite remains the local-development and automated-test database. PostgreSQL is required for production and preview user-editable content on Vercel.

## Vercel Constraints

Vercel Functions should be treated as stateless:

- Do not use `database/database.sqlite` as a writable production database.
- Do not use `storage/app/public` as persistent upload storage.
- Do not rely on files created at runtime surviving redeploys or function invocations.
- Use `/tmp` only for temporary processing, such as image resizing before upload to Vercel Blob.

## Environment Configuration

Local development continues to use the committed `.env.example` defaults:

```env
APP_ENV=local
DB_CONNECTION=sqlite
FILESYSTEM_DISK=local
```

Production and preview must use the values documented in `.env.production.example`. Set the real credentials in Vercel project settings; never commit them to this repository.

## Target Production Environment Variables

Production/preview should eventually provide:

```env
DB_CONNECTION=pgsql
DATABASE_URL=postgres://...

BLOB_READ_WRITE_TOKEN=vercel_blob_rw_...
```

Vercel Blob is the selected storage target for production and preview uploads. The upload implementation should write files to Vercel Blob and persist the returned Blob URL/path metadata in PostgreSQL.

Because this is a Laravel/PHP project, the implementation should verify the best supported Blob upload path before coding. If the official Vercel Blob SDK is only available for JavaScript in the target runtime, use the Vercel Blob HTTP API from Laravel instead of Laravel's default filesystem disk abstraction.

Local development may keep using Laravel's `public` disk for convenience, or it may use Vercel Blob when `BLOB_READ_WRITE_TOKEN` is configured.

## Current Code Paths To Change Later

These code paths currently write uploads to Laravel's local `public` disk and must be changed before production user uploads are enabled:

- `app/Http/Controllers/Admin/MediaController.php`
- `app/Http/Controllers/Admin/EditorUploadController.php`
- `app/Http/Controllers/Admin/InlineEditController.php`
- `app/Models/MediaFile.php`

The current `vercel.json` already avoids database-backed cache, session, and queue storage in Vercel by setting:

- `CACHE_STORE=array`
- `SESSION_DRIVER=cookie`
- `QUEUE_CONNECTION=sync`

That is compatible with a stateless runtime, but it does not solve persistent database or uploaded file storage.

## Phase 0 Completion Criteria

Phase 0 is complete when the following checks have passed:

- Vercel remains the Laravel hosting target.
- Local development and tests use SQLite without a `DATABASE_URL`.
- Vercel Production and Preview each provide `DB_CONNECTION=pgsql`, `DATABASE_URL`, and `BLOB_READ_WRITE_TOKEN`.
- The PostgreSQL connection succeeds and `php artisan migrate --force` completes against that database.
- A Vercel Blob store is connected to the project and its token is available to the Laravel function.
- The deployed application can read production CMS data from PostgreSQL.
- Admin file uploads remain disabled in production until the current local-disk upload code is replaced with Vercel Blob writes.
- Later migrations are designed around page revisions, media metadata, media usage tracking, permissions, and audit logs.

## Phase 1

Phase 1 replaces local-disk upload writes with Vercel Blob writes and persists the returned Blob metadata in PostgreSQL.

Recommended next tasks:

1. Replace local `public` disk writes in the upload controllers with Vercel Blob uploads.
2. Store the Blob pathname and public URL in `media_files`.
3. Update deletions to remove the corresponding Blob object.
4. Add production upload tests for PDFs and images.
