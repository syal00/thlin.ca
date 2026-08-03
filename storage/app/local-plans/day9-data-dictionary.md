# 工作日 9：SQLite 数据字典

- 生成日期：2026 年 8 月 3 日
- 来源：`storage/app/local-plans/day9-schema-and-migrations.md`
- 检查范围：仅本地 `database/database.sqlite`；未连接或写入任何外部数据库。
- 记法：可空性“是”表示字段可为 `NULL`；“默认值”以 SQLite 当前定义记录。

## 表总览

本字典覆盖当前 schema 的 17 张表：`board_members`、`cache`、`cache_locks`、`careers`、`contact_messages`、`failed_jobs`、`job_batches`、`jobs`、`media_files`、`migrations`、`news_posts`、`pages`、`password_reset_tokens`、`portfolio_items`、`sessions`、`site_settings`、`users`。

## `board_members`

| 字段 | SQLite 类型 | 可空 | 默认值 | 键/说明 |
| --- | --- | --- | --- | --- |
| `id` | `integer` | 否 | — | 主键，自增 |
| `name` | `varchar` | 否 | — | — |
| `role` | `varchar` | 否 | — | — |
| `bio` | `text` | 是 | — | — |
| `photo` | `varchar` | 是 | — | — |
| `sort_order` | `integer` | 否 | `0` | — |
| `created_at` | `datetime` | 是 | — | — |
| `updated_at` | `datetime` | 是 | — | — |

约束与索引：主键 `id`；无显式唯一约束、外键或非主键索引。

## `cache`

| 字段 | SQLite 类型 | 可空 | 默认值 | 键/说明 |
| --- | --- | --- | --- | --- |
| `key` | `varchar` | 否 | — | 主键 |
| `value` | `text` | 否 | — | — |
| `expiration` | `integer` | 否 | — | 索引 `cache_expiration_index` |

约束与索引：主键 `key`；非唯一索引 `cache_expiration_index (expiration)`；无外键。

## `cache_locks`

| 字段 | SQLite 类型 | 可空 | 默认值 | 键/说明 |
| --- | --- | --- | --- | --- |
| `key` | `varchar` | 否 | — | 主键 |
| `owner` | `varchar` | 否 | — | — |
| `expiration` | `integer` | 否 | — | 索引 `cache_locks_expiration_index` |

约束与索引：主键 `key`；非唯一索引 `cache_locks_expiration_index (expiration)`；无外键。

## `careers`

| 字段 | SQLite 类型 | 可空 | 默认值 | 键/说明 |
| --- | --- | --- | --- | --- |
| `id` | `integer` | 否 | — | 主键，自增 |
| `title` | `varchar` | 否 | — | — |
| `slug` | `varchar` | 否 | — | 唯一索引 `careers_slug_unique` |
| `location` | `varchar` | 是 | — | — |
| `employment_type` | `varchar` | 是 | — | — |
| `posted_at` | `date` | 是 | — | — |
| `closes_at` | `date` | 是 | — | — |
| `body` | `text` | 是 | — | — |
| `is_active` | `tinyint(1)` | 否 | `1` | — |
| `created_at` | `datetime` | 是 | — | — |
| `updated_at` | `datetime` | 是 | — | — |

约束与索引：主键 `id`；唯一索引 `careers_slug_unique (slug)`；无外键。

## `contact_messages`

| 字段 | SQLite 类型 | 可空 | 默认值 | 键/说明 |
| --- | --- | --- | --- | --- |
| `id` | `integer` | 否 | — | 主键，自增 |
| `name` | `varchar` | 否 | — | — |
| `email` | `varchar` | 否 | — | — |
| `organization` | `varchar` | 是 | — | — |
| `message` | `text` | 否 | — | — |
| `status` | `varchar` | 否 | `'new'` | — |
| `created_at` | `datetime` | 是 | — | — |
| `updated_at` | `datetime` | 是 | — | — |

约束与索引：主键 `id`；无显式唯一约束、外键或非主键索引。

## `failed_jobs`

| 字段 | SQLite 类型 | 可空 | 默认值 | 键/说明 |
| --- | --- | --- | --- | --- |
| `id` | `integer` | 否 | — | 主键，自增 |
| `uuid` | `varchar` | 否 | — | 唯一索引 `failed_jobs_uuid_unique` |
| `connection` | `varchar` | 否 | — | 复合索引第 1 列 |
| `queue` | `varchar` | 否 | — | 复合索引第 2 列 |
| `payload` | `text` | 否 | — | — |
| `exception` | `text` | 否 | — | — |
| `failed_at` | `datetime` | 否 | `CURRENT_TIMESTAMP` | 复合索引第 3 列 |

约束与索引：主键 `id`；唯一索引 `failed_jobs_uuid_unique (uuid)`；非唯一复合索引 `failed_jobs_connection_queue_failed_at_index (connection, queue, failed_at)`；无外键。

## `job_batches`

| 字段 | SQLite 类型 | 可空 | 默认值 | 键/说明 |
| --- | --- | --- | --- | --- |
| `id` | `varchar` | 否 | — | 主键 |
| `name` | `varchar` | 否 | — | — |
| `total_jobs` | `integer` | 否 | — | — |
| `pending_jobs` | `integer` | 否 | — | — |
| `failed_jobs` | `integer` | 否 | — | — |
| `failed_job_ids` | `text` | 否 | — | — |
| `options` | `text` | 是 | — | — |
| `cancelled_at` | `integer` | 是 | — | — |
| `created_at` | `integer` | 否 | — | — |
| `finished_at` | `integer` | 是 | — | — |

约束与索引：主键 `id`；无显式唯一约束、外键或非主键索引。

## `jobs`

| 字段 | SQLite 类型 | 可空 | 默认值 | 键/说明 |
| --- | --- | --- | --- | --- |
| `id` | `integer` | 否 | — | 主键，自增 |
| `queue` | `varchar` | 否 | — | 索引 `jobs_queue_index` |
| `payload` | `text` | 否 | — | — |
| `attempts` | `integer` | 否 | — | — |
| `reserved_at` | `integer` | 是 | — | — |
| `available_at` | `integer` | 否 | — | — |
| `created_at` | `integer` | 否 | — | — |

约束与索引：主键 `id`；非唯一索引 `jobs_queue_index (queue)`；无外键。

## `media_files`

| 字段 | SQLite 类型 | 可空 | 默认值 | 键/说明 |
| --- | --- | --- | --- | --- |
| `id` | `integer` | 否 | — | 主键，自增 |
| `title` | `varchar` | 否 | — | — |
| `original_name` | `varchar` | 否 | — | — |
| `file_name` | `varchar` | 否 | — | — |
| `file_path` | `varchar` | 否 | — | — |
| `file_type` | `varchar` | 否 | `'pdf'` | — |
| `mime_type` | `varchar` | 否 | — | — |
| `file_size` | `integer` | 是 | — | — |
| `description` | `text` | 是 | — | — |
| `uploaded_by` | `integer` | 是 | — | 外键、索引 `media_files_uploaded_by_index` |
| `created_at` | `datetime` | 是 | — | — |
| `updated_at` | `datetime` | 是 | — | — |
| `cloudinary_public_id` | `varchar` | 是 | — | — |

约束与索引：主键 `id`；外键 `uploaded_by → users.id`，删除时 `SET NULL`；非唯一索引 `media_files_uploaded_by_index (uploaded_by)`；无显式唯一约束。

## `migrations`

| 字段 | SQLite 类型 | 可空 | 默认值 | 键/说明 |
| --- | --- | --- | --- | --- |
| `id` | `integer` | 否 | — | 主键，自增 |
| `migration` | `varchar` | 否 | — | — |
| `batch` | `integer` | 否 | — | — |

约束与索引：主键 `id`；无显式唯一约束、外键或非主键索引。

## `news_posts`

| 字段 | SQLite 类型 | 可空 | 默认值 | 键/说明 |
| --- | --- | --- | --- | --- |
| `id` | `integer` | 否 | — | 主键，自增 |
| `slug` | `varchar` | 否 | — | 唯一索引 `news_posts_slug_unique` |
| `title` | `varchar` | 否 | — | — |
| `published_at` | `date` | 是 | — | — |
| `location` | `varchar` | 是 | — | — |
| `excerpt` | `text` | 是 | — | — |
| `body` | `text` | 是 | — | — |
| `image` | `varchar` | 是 | — | — |
| `is_published` | `tinyint(1)` | 否 | `1` | — |
| `created_at` | `datetime` | 是 | — | — |
| `updated_at` | `datetime` | 是 | — | — |

约束与索引：主键 `id`；唯一索引 `news_posts_slug_unique (slug)`；无外键。

## `pages`

| 字段 | SQLite 类型 | 可空 | 默认值 | 键/说明 |
| --- | --- | --- | --- | --- |
| `id` | `integer` | 否 | — | 主键，自增 |
| `slug` | `varchar` | 否 | — | 唯一索引 `pages_slug_unique` |
| `title` | `varchar` | 否 | — | 复合索引第 4 列 |
| `section` | `varchar` | 否 | `'general'` | 复合索引/索引 |
| `meta_description` | `varchar` | 是 | — | — |
| `excerpt` | `text` | 是 | — | — |
| `body` | `text` | 是 | — | — |
| `template` | `varchar` | 否 | `'standard'` | — |
| `sort_order` | `integer` | 否 | `0` | 两个非唯一索引 |
| `is_published` | `tinyint(1)` | 否 | `1` | 复合索引 |
| `created_at` | `datetime` | 是 | — | — |
| `updated_at` | `datetime` | 是 | — | — |
| `hero_title` | `varchar` | 是 | — | — |
| `hero_subtitle` | `varchar` | 是 | — | — |
| `page_type` | `varchar` | 否 | `'built_in'` | — |
| `status` | `varchar` | 否 | `'published'` | 复合索引 |
| `show_in_navigation` | `tinyint(1)` | 否 | `0` | — |
| `navigation_label` | `varchar` | 是 | — | — |
| `published_at` | `datetime` | 是 | — | — |
| `parent_id` | `integer` | 是 | — | 自引用外键、复合索引第 1 列 |
| `created_by` | `integer` | 是 | — | 外键、索引 `pages_created_by_index` |
| `updated_by` | `integer` | 是 | — | 外键、索引 `pages_updated_by_index` |
| `meta_title` | `varchar` | 是 | — | — |
| `meta_keywords` | `text` | 是 | — | — |
| `custom_html` | `text` | 是 | — | — |

约束与索引：主键 `id`；唯一索引 `pages_slug_unique (slug)`；外键 `parent_id → pages.id`（删除时 `SET NULL`）、`created_by → users.id`（删除时 `SET NULL`）、`updated_by → users.id`（删除时 `SET NULL`）；非唯一索引 `pages_parent_id_status_sort_order_title_index (parent_id, status, sort_order, title)`、`pages_section_is_published_index (section, is_published)`、`pages_created_by_index (created_by)`、`pages_updated_by_index (updated_by)`。

## `password_reset_tokens`

| 字段 | SQLite 类型 | 可空 | 默认值 | 键/说明 |
| --- | --- | --- | --- | --- |
| `email` | `varchar` | 否 | — | 主键 |
| `token` | `varchar` | 否 | — | — |
| `created_at` | `datetime` | 是 | — | — |

约束与索引：主键 `email`；无显式唯一约束、外键或非主键索引。

## `portfolio_items`

| 字段 | SQLite 类型 | 可空 | 默认值 | 键/说明 |
| --- | --- | --- | --- | --- |
| `id` | `integer` | 否 | — | 主键，自增 |
| `title` | `varchar` | 否 | — | — |
| `excerpt` | `text` | 是 | — | — |
| `url` | `varchar` | 是 | — | — |
| `image` | `varchar` | 是 | — | — |
| `featured` | `tinyint(1)` | 否 | `0` | — |
| `sort_order` | `integer` | 否 | `0` | — |
| `created_at` | `datetime` | 是 | — | — |
| `updated_at` | `datetime` | 是 | — | — |

约束与索引：主键 `id`；无显式唯一约束、外键或非主键索引。

## `sessions`

| 字段 | SQLite 类型 | 可空 | 默认值 | 键/说明 |
| --- | --- | --- | --- | --- |
| `id` | `varchar` | 否 | — | 主键 |
| `user_id` | `integer` | 是 | — | 索引 `sessions_user_id_index` |
| `ip_address` | `varchar` | 是 | — | — |
| `user_agent` | `text` | 是 | — | — |
| `payload` | `text` | 否 | — | — |
| `last_activity` | `integer` | 否 | — | 索引 `sessions_last_activity_index` |

约束与索引：主键 `id`；非唯一索引 `sessions_user_id_index (user_id)`、`sessions_last_activity_index (last_activity)`；当前 SQLite 定义没有外键。

## `site_settings`

| 字段 | SQLite 类型 | 可空 | 默认值 | 键/说明 |
| --- | --- | --- | --- | --- |
| `id` | `integer` | 否 | — | 主键，自增 |
| `key` | `varchar` | 否 | — | 唯一索引 `site_settings_key_unique` |
| `value` | `text` | 是 | — | — |
| `type` | `varchar` | 否 | `'text'` | — |
| `group` | `varchar` | 是 | — | — |
| `created_at` | `datetime` | 是 | — | — |
| `updated_at` | `datetime` | 是 | — | — |

约束与索引：主键 `id`；唯一索引 `site_settings_key_unique (key)`；无外键。

## `users`

| 字段 | SQLite 类型 | 可空 | 默认值 | 键/说明 |
| --- | --- | --- | --- | --- |
| `id` | `integer` | 否 | — | 主键，自增 |
| `name` | `varchar` | 否 | — | — |
| `email` | `varchar` | 否 | — | 唯一索引 `users_email_unique` |
| `email_verified_at` | `datetime` | 是 | — | — |
| `password` | `varchar` | 否 | — | — |
| `remember_token` | `varchar` | 是 | — | — |
| `created_at` | `datetime` | 是 | — | — |
| `updated_at` | `datetime` | 是 | — | — |

约束与索引：主键 `id`；唯一索引 `users_email_unique (email)`；无外键。

## 覆盖验证

- 本文件包含 17 个以表名命名的章节，与 `day9-schema-and-migrations.md` 的 17 张表清单一致。
- 字段、类型、可空性、默认值、主键、外键、唯一约束和显式非主键索引均以当前 SQLite schema 为准。
