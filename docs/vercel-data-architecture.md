# Vercel Data Architecture Plan

## Current Deployment

- Production/preview URL: `https://thlin-ca.vercel.app/`
- Runtime: Laravel on Vercel through `vercel-php`
- Current local database: SQLite at `database/database.sqlite`
- Current local upload disk: Laravel `public` disk backed by `storage/app/public`

The deployed URL responds from Vercel, so future user editing and uploads must be planned for a serverless runtime.

## Phase 1 Decision

For Vercel deployment, application data must be split into two persistent services:

1. PostgreSQL for structured data:
   - pages
   - page revisions
   - users and roles
   - media metadata
   - media usage references
   - audit logs

2. Object storage for uploaded file bodies:
   - images
   - PDFs
   - documents
   - future large media assets

SQLite remains acceptable for local development and automated tests, but it should not be the production or preview database for user-editable content on Vercel.

## Vercel Constraints

Vercel Functions should be treated as stateless:

- Do not use `database/database.sqlite` as a writable production database.
- Do not use `storage/app/public` as persistent upload storage.
- Do not rely on files created at runtime surviving redeploys or function invocations.
- Use `/tmp` only for temporary processing, such as image resizing before upload to object storage.

## Target Environment Variables

Production/preview should eventually provide:

```env
DB_CONNECTION=pgsql
DATABASE_URL=postgres://...

FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
AWS_DEFAULT_REGION=...
AWS_BUCKET=...
AWS_ENDPOINT=...
AWS_URL=...
AWS_USE_PATH_STYLE_ENDPOINT=true
```

If Vercel Blob is selected instead of S3/R2, the upload implementation will need a Vercel Blob client rather than Laravel's default S3 disk.

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

## Phase 1 Completion Criteria

Phase 1 is complete when the team agrees to the following:

- Vercel remains the Laravel hosting target.
- PostgreSQL will be used for production/preview structured data.
- Uploaded files will use object storage, not Vercel local filesystem storage.
- SQLite is limited to local development and tests.
- Later database migrations will be designed around page revisions, media metadata, media usage tracking, permissions, and audit logs.

## Next Phase

Phase 2 should connect PostgreSQL and verify that Vercel can read/write CMS data from it.

Recommended next tasks:

1. Select a Postgres provider, such as Neon, Supabase, Railway, or another managed Postgres service.
2. Add `DB_CONNECTION=pgsql` and `DATABASE_URL` to Vercel environment variables.
3. Run Laravel migrations against the selected Postgres database.
4. Deploy and confirm that pages load from Postgres.
