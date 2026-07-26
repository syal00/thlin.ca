# Admin-Managed Deployment and Database Status

This document describes the current database scope when the Vercel project owner retains deployment access. It does not authorize a PostgreSQL, Neon, or SQL Server migration.

## Current decision

- Keep the Vercel plan unchanged and do not share the owner's login or password.
- Keep SQLite as the current project database.
- Do not create PostgreSQL or Neon databases.
- Do not configure `DB_CONNECTION=pgsql`, `DATABASE_URL`, `DATABASE_DIRECT_URL`, or remote database migration commands.
- Do not run `php artisan migrate --force` against an external database for this scope.
- Before schema or content changes, create and verify a SQLite backup.

## Responsibilities

| Role | Responsibility |
| --- | --- |
| Developer | Code changes, local SQLite migrations, SQLite backups, automated tests, and database documentation. |
| Vercel administrator | Deployment access, deployment status, and reporting deployment errors without sharing credentials. |
| Client | Approves any future production database migration and supplies SQL Server access only for that separate phase. |

## Current deployment checks

Before asking the Vercel administrator to deploy a branch:

1. Confirm `.env` uses `DB_CONNECTION=sqlite`.
2. Back up `database/database.sqlite` outside the Git worktree.
3. Run the approved SQLite migration and test checks locally.
4. Confirm no PostgreSQL connection string, migration command, or database credential is included in the branch or deployment request.
5. Send the administrator the branch name and public verification URLs only.

## SQLite limitation on Vercel

Vercel functions do not provide a durable shared filesystem for SQLite writes. The current demonstration may read bundled SQLite content, but CMS changes made in a serverless instance must not be treated as durable across redeploys, instance changes, or concurrent writes.

For the current scope, do not claim production-grade persistent CMS editing on Vercel. Record important content changes in the verified local SQLite backup until the client approves a production database phase.

## Future SQL Server phase

The client’s Microsoft SQL Server 2016/2019 environment is the preferred future target. That work must be separately approved and must include:

- a dedicated test database and minimal-permission account;
- network, TLS, VPN, firewall, and PHP `pdo_sqlsrv`/ODBC prerequisites;
- SQLite backup, data export/import rehearsal, record-count checks, and a rollback plan;
- a planned content-freeze and deployment window.

No SQL Server credentials, connection strings, or migration commands belong in this repository before that approval.

## Related documents

- `README.md`
- `docs/vercel-data-architecture.md`
