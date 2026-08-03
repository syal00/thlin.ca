# 工作日 9：SQLite Schema 与 Migration 清单

- 生成日期：2026 年 8 月 3 日
- 来源：`/Users/eldonshen/Desktop/thlin.ca/storage/app/local-plans/day9.md`
- 检查范围：仅本地 SQLite 数据库与 Laravel migration 状态；未连接或写入任何外部数据库。

## Schema 摘要

- 连接名称：`sqlite`
- SQLite 版本：`3.53.3`
- 本地数据库文件：`database/database.sqlite`
- 表数量：17

## 表清单

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

## Migration 清单

| Migration | Batch | 状态 |
| --- | ---: | --- |
| `0001_01_01_000000_create_users_table` | 1 | 已执行 |
| `0001_01_01_000001_create_cache_table` | 1 | 已执行 |
| `0001_01_01_000002_create_jobs_table` | 1 | 已执行 |
| `2026_05_27_000001_create_pages_table` | 1 | 已执行 |
| `2026_05_27_000002_create_cms_tables` | 1 | 已执行 |
| `2026_06_11_210426_add_cms_fields_to_pages_table` | 2 | 已执行 |
| `2026_06_11_210427_create_media_files_table` | 2 | 已执行 |
| `2026_06_11_212643_add_parent_id_to_pages_table` | 2 | 已执行 |
| `2026_06_11_213213_reset_parent_id_on_built_in_pages` | 2 | 已执行 |
| `2026_06_11_221934_change_pages_meta_description_to_text` | 2 | 已执行 |
| `2026_06_21_000000_add_editor_attribution_to_pages_table` | 3 | 已执行 |
| `2026_06_17_000001_add_seo_fields_to_pages_table` | 4 | 已执行 |
| `2026_06_17_000002_create_contact_messages_table` | 4 | 已执行 |
| `2026_06_17_000003_create_site_settings_table` | 4 | 已执行 |
| `2026_06_22_000001_add_cloudinary_and_fulltext_search` | 5 | 已执行 |
| `2026_07_30_000001_add_foreign_key_indexes` | 6 | 已执行 |
| `2026_07_31_000002_add_custom_html_to_pages_table` | 7 | 已执行 |

## 验证

已运行下列只读命令并与本文件核对：

```sh
php artisan db:show --database=sqlite
php artisan migrate:status
```

命令输出与本文件一致：共 17 张表、17 条 migration，且均为已执行状态。
