# Current Database Architecture

## Current scope

- Application: Laravel on Vercel through `vercel-php`.
- Current database: SQLite at `database/database.sqlite`.
- Current default connection: `DB_CONNECTION=sqlite`.
- Current scope: self-contained development and demonstration database; no PostgreSQL, Neon, or SQL Server deployment.

## Operating rules

- Use Laravel migrations for schema changes; do not manually modify the SQLite file.
- Back up SQLite before schema changes, content imports, or deployment-sensitive edits.
- Run automated tests against an isolated SQLite test database, never against the working content database.
- Do not commit SQLite backup files, credentials, or external database connection strings.
- Do not configure `DATABASE_URL` or `DATABASE_DIRECT_URL` for the current scope.

## Vercel persistence limitation

Vercel functions are stateless and do not offer a durable shared disk for SQLite writes. The deployed application can use the current SQLite content for demonstration, but changes made by CMS users must not be considered durable across redeploys, function instances, or concurrent writes.

This limitation is accepted for the current project scope. It must be stated in delivery and deployment discussions; the current Vercel demo is not a production-grade multi-user CMS database deployment.

## Environment configuration

Local development and the current project configuration use:

```env
APP_ENV=local
DB_CONNECTION=sqlite
FILESYSTEM_DISK=local
```

Do not add PostgreSQL, Neon, `DATABASE_URL`, `DATABASE_DIRECT_URL`, or external database migration settings to Vercel for this scope.

## Future production database

If the client requires durable multi-user editing, long-term CMS persistence, or a production service level, use a separately approved migration to the client’s Microsoft SQL Server 2016/2019 environment.

Before that migration, the team must prepare:

1. SQL Server test-instance, network, TLS, firewall, VPN, and least-privilege account requirements.
2. Laravel/PHP compatibility checks for Microsoft ODBC Driver, `pdo_sqlsrv`, and `sqlsrv`.
3. SQLite schema and data dictionary, type mapping, backup, import rehearsal, validation, and rollback plan.
4. Content freeze and verified cutover window.

No external database migration is part of the current delivery.
