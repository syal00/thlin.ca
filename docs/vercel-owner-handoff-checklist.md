# Vercel Owner Handoff Checklist

## Current Status

- Vercel access model: free personal account; developers cannot be added as project members.
- Project owner: **not yet confirmed**.
- Owner contact channel: **not yet confirmed**.
- This checklist is preparation only. No Vercel setting has been changed.

Do not share the owner's Vercel password. The owner performs Dashboard changes and reports only whether each named setting was applied.

## Configuration Package To Prepare

The developer will provide the owner with the following values through an approved secure channel when the PostgreSQL cutover is authorized:

```dotenv
DB_CONNECTION=pgsql
DATABASE_URL=postgresql://POOLED_CONNECTION
DB_SSLMODE=require
CLOUDINARY_URL=cloudinary://CREDENTIALS
CACHE_STORE=array
SESSION_DRIVER=cookie
QUEUE_CONNECTION=sync
```

Rules:

- Apply the variables to the Preview environment used by `main.test`.
- Preserve the existing `APP_KEY`; never generate a replacement during migration.
- Do not add `DATABASE_DIRECT_URL` to Vercel. It belongs only in the protected GitHub Environment used for migrations.
- Do not paste real database or Cloudinary credentials into Git, issues, pull requests, or ordinary chat.

## Owner Actions For Cutover Day

1. Confirm the Vercel project and the Preview deployment associated with `main.test`.
2. Add or update the approved environment variables listed above.
3. Confirm each variable name and environment scope without returning its secret value.
4. Trigger a fresh deployment of `main.test` after the developer confirms that PostgreSQL schema and data are ready.
5. Send the deployment URL and success/failure status to the developer.
6. If validation fails, restore the previous successful deployment without deleting PostgreSQL or Cloudinary data.

## Developer Verification

After the owner reports completion, verify:

- `/up` responds successfully.
- The home page and search load from PostgreSQL.
- `/admin/login` loads and an approved administrator can sign in.
- CMS edits persist after a second deployment.
- Vercel logs contain no database connection or configuration errors.

## External Dependency Checkpoint

By July 22, record one of the following statuses in the project handoff notes:

- **Owner confirmed:** proceed with the July 23–24 Vercel cutover route.
- **Owner unavailable or unconfirmed:** finish code, tests, PostgreSQL preparation, and this handoff package, but report Vercel deployment as externally blocked.
