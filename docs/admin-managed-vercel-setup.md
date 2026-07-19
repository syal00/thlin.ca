# Admin-Managed Vercel Production Setup

This runbook is for the temporary no-upgrade workflow where the Vercel project owner keeps production access and runs the online configuration steps for the development team.

## Decision

- Do not upgrade the Vercel plan just to add project members.
- Do not share a personal Vercel login or password.
- Developers continue code, migrations, tests, and documentation in GitHub.
- The Vercel administrator performs production/preview configuration from this checklist.

This keeps subscription costs unchanged, but production troubleshooting and deployments require administrator support until project access is granted later.

## Roles

| Role | Owns |
| --- | --- |
| Developer | Code changes, migrations, local SQLite testing, migration dry runs, PR notes, deployment checklist updates |
| Vercel administrator | Vercel project settings, Neon/PostgreSQL creation, Vercel Blob store, environment variables, production migration execution, deployment approval |

## Execution Flow

1. Developer prepares a branch or PR with the database/storage changes.
2. Developer confirms local development still uses SQLite.
3. Developer sends the administrator the required environment variable list and migration commands.
4. Administrator creates the managed PostgreSQL database.
5. Administrator creates the Vercel Blob store.
6. Administrator adds environment variables to Vercel Preview and Production.
7. Administrator runs database migrations against PostgreSQL.
8. Administrator deploys the target branch.
9. Developer verifies the public site and admin CMS behavior from the deployed URL.

## Developer Checklist

Complete these before asking the administrator to touch Vercel:

- Confirm `.env` remains local-only and uses SQLite:

  ```env
  DB_CONNECTION=sqlite
  FILESYSTEM_DISK=local
  ```

- Run local migrations and tests:

  ```bash
  php artisan migrate --seed
  php artisan test
  ```

- Confirm the production env contract matches `.env.production.example`:

  ```env
  DB_CONNECTION=pgsql
  DATABASE_URL=postgresql://USER:PASSWORD@HOST:5432/DATABASE?sslmode=require
  DB_SSLMODE=require
  BLOB_READ_WRITE_TOKEN=vercel_blob_rw_REPLACE_ME
  CACHE_STORE=array
  SESSION_DRIVER=cookie
  QUEUE_CONNECTION=sync
  ```

- If content needs to be copied from local SQLite to PostgreSQL, dry-run the import locally first:

  ```bash
  php artisan thlin:import-sqlite database/database.sqlite --dry-run
  ```

- Send the administrator:
  - target branch or PR link
  - required env vars
  - whether migration is schema-only or also imports SQLite content
  - rollback note
  - verification URLs to check after deployment

## Administrator Checklist

### 1. Create PostgreSQL

Create a managed PostgreSQL database for the Vercel project. Neon is recommended for this project.

Required output:

- SSL-enabled PostgreSQL connection string
- database name
- note whether the database is for Preview, Production, or both

The connection string should look like:

```text
postgresql://USER:PASSWORD@HOST/DATABASE?sslmode=require
```

### 2. Create Vercel Blob Store

Create or connect a Vercel Blob store for uploaded files such as PDFs, annual reports, and page images.

Required output:

- `BLOB_READ_WRITE_TOKEN`
- confirmation that the Blob store is connected to the same Vercel project

Do not send the token in regular chat if avoidable. Add it directly to Vercel environment variables.

### 3. Configure Vercel Environment Variables

In the Vercel project settings, add these variables to both Preview and Production unless the team explicitly asks for only one environment:

```env
DB_CONNECTION=pgsql
DATABASE_URL=postgresql://USER:PASSWORD@HOST/DATABASE?sslmode=require
DB_SSLMODE=require
BLOB_READ_WRITE_TOKEN=vercel_blob_rw_REPLACE_ME
CACHE_STORE=array
SESSION_DRIVER=cookie
QUEUE_CONNECTION=sync
```

Keep the existing Vercel runtime variables from `vercel.json`. Do not commit real credentials to GitHub.

### 4. Run Migrations

Use a clean local clone or trusted CI/deployment shell with the target branch checked out.

Install dependencies:

```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

Create a temporary `.env` from `.env.production.example`, fill in the real PostgreSQL and Blob values, then run:

```bash
php artisan config:clear
php artisan migrate --force
```

If the team approved importing the current SQLite public content into an empty PostgreSQL database, run:

```bash
php artisan thlin:import-sqlite database/database.sqlite --dry-run
php artisan thlin:import-sqlite database/database.sqlite --fresh --force
```

Important:

- Do not run `migrate:fresh` on a database that contains production data.
- Do not import `users`, `contact_messages`, or `media_files` unless the developer explicitly asks for those flags.
- The `--include-media` option imports metadata only. PDF/image files still need Blob migration.

### 5. Deploy

Deploy the target branch after environment variables and migrations are complete.

After deployment, confirm:

- the public site loads
- `/admin/login` loads
- CMS pages can be read from PostgreSQL
- file upload actions are not enabled for production until Blob upload implementation is complete
- Vercel deployment logs do not show database connection errors

## Rollback

If deployment fails:

1. Revert the Vercel deployment to the previous successful deployment.
2. Keep the PostgreSQL database and Blob store intact for debugging.
3. Do not delete production data.
4. Send the deployment error logs to the developer.

If migrations fail:

1. Stop before deploying.
2. Save the exact migration error.
3. Confirm whether the database was empty or had existing data.
4. Ask the developer before retrying with any destructive command.

## Message To Send The Administrator

```text
It looks like Vercel requires an upgrade to add project members, so let's avoid changing the subscription for now.

Please keep Vercel access under your account/team and run the production setup from our checklist:
1. Create/connect Neon PostgreSQL for the Vercel project.
2. Create/connect Vercel Blob for PDFs and images.
3. Add the required environment variables to Preview and Production.
4. Run the Laravel migrations against PostgreSQL.
5. Deploy the target branch.

I will continue code, migrations, tests, and documentation in GitHub. I do not need your personal Vercel login/password.
```

## Related Docs

- `docs/phase-0-checklist.md`
- `docs/vercel-data-architecture.md`
- `docs/sqlite-to-postgresql-migration.md`
- `.env.production.example`
