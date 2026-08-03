# Day 9: SQLite Schema and Migration Inventory

- Generated: 3 August 2026
- Source: `/Users/eldonshen/Desktop/2026/2026spring/8268/iteration/steps.md`
- Scope: local SQLite database and Laravel migration status only; no external database was connected to or written.

## Schema Summary

- Connection name: `sqlite`
- SQLite version: `3.50.4`
- Local database file: `database/database.sqlite`
- Table count: 17

## Table Inventory

1. `board_members`
2. `cache`
3. `cache_locks`
4. `careers`
5. `contact_messages`
6. `failed_jobs`
7. `job_batches`
8. `jobs`
9. `media_files`
10. `migrations`
11. `news_posts`
12. `pages`
13. `password_reset_tokens`
14. `portfolio_items`
15. `sessions`
16. `site_settings`
17. `users`

## Migration Inventory

| Migration | Batch | Status |
| --- | ---: | --- |
| `0001_01_01_000000_create_users_table` | 1 | Completed |
| `0001_01_01_000001_create_cache_table` | 1 | Completed |
| `0001_01_01_000002_create_jobs_table` | 1 | Completed |
| `2026_05_27_000001_create_pages_table` | 1 | Completed |
| `2026_05_27_000002_create_cms_tables` | 1 | Completed |
| `2026_06_11_210426_add_cms_fields_to_pages_table` | 2 | Completed |
| `2026_06_11_210427_create_media_files_table` | 2 | Completed |
| `2026_06_11_212643_add_parent_id_to_pages_table` | 2 | Completed |
| `2026_06_11_213213_reset_parent_id_on_built_in_pages` | 2 | Completed |
| `2026_06_11_221934_change_pages_meta_description_to_text` | 2 | Completed |
| `2026_06_21_000000_add_editor_attribution_to_pages_table` | 3 | Completed |
| `2026_06_17_000001_add_seo_fields_to_pages_table` | 4 | Completed |
| `2026_06_17_000002_create_contact_messages_table` | 4 | Completed |
| `2026_06_17_000003_create_site_settings_table` | 4 | Completed |
| `2026_06_22_000001_add_cloudinary_and_fulltext_search` | 5 | Completed |
| `2026_07_30_000001_add_foreign_key_indexes` | 6 | Completed |
| `2026_07_31_000002_add_custom_html_to_pages_table` | 7 | Completed |

## Verification

The following read-only commands were run and checked against this file:

```sh
php artisan db:show --database=sqlite
php artisan migrate:status
```

The command output matches this inventory: 17 tables and 17 migrations, all completed.
