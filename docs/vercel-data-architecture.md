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

1. A dedicated SQL Server 2016/2019 test instance and database, network path, TLS, firewall/VPN rules, and a least-privilege account delivered through an approved channel.
2. Laravel/PHP compatibility checks for Microsoft ODBC Driver, `pdo_sqlsrv`, and `sqlsrv` on the target runtime.
3. The SQLite schema/data dictionary and a reviewed SQLite-to-SQL Server type map, including primary keys, foreign keys, unique constraints, indexes, timestamps, booleans, JSON/text fields, and full-text-search alternatives.
4. A rehearsed export/import procedure with identity-value correction, record-count and referential-integrity checks, application/CMS smoke tests, a verified SQLite backup, and a rollback plan.
5. A content freeze, approved cutover window, and post-cutover owner acceptance.

No external database migration, credential, connection string, import, or remote migration command is part of the current delivery.
