# Day 9: SQLite Data Dictionary

- Generated: 3 August 2026
- Source: `storage/app/local-plans/day9-schema-and-migrations.md`
- Scope: local `database/database.sqlite` only; no external database was connected to or written.
- Notation: “Nullable: Yes” means the field may be `NULL`; defaults reflect the current SQLite definitions.

## Table Overview

This dictionary covers the current schema tables: `board_members`, `cache`, `cache_locks`, `careers`, `contact_messages`, `failed_jobs`, `job_batches`, `jobs`, `media_files`, `migrations`, `news_posts`, `pages`, `password_reset_tokens`, `portfolio_items`, `sessions`, `site_settings`, `users`.

## `board_members`

| Field | SQLite type | Nullable | Default | Keys and notes |
| --- | --- | --- | --- | --- |
| `id` | `INTEGER` | No | `—` | Primary key, auto-increment |
| `name` | `varchar` | No | `—` | — |
| `role` | `varchar` | No | `—` | — |
| `bio` | `TEXT` | Yes | `—` | — |
| `photo` | `varchar` | Yes | `—` | — |
| `sort_order` | `INTEGER` | No | `'0'` | — |
| `created_at` | `datetime` | Yes | `—` | — |
| `updated_at` | `datetime` | Yes | `—` | — |

Constraints and indexes: Primary key id.

## `cache`

| Field | SQLite type | Nullable | Default | Keys and notes |
| --- | --- | --- | --- | --- |
| `key` | `varchar` | No | `—` | Primary key |
| `value` | `TEXT` | No | `—` | — |
| `expiration` | `INTEGER` | No | `—` | Index: cache_expiration_index |

Constraints and indexes: Primary key key; Non-unique index cache_expiration_index (expiration).

## `cache_locks`

| Field | SQLite type | Nullable | Default | Keys and notes |
| --- | --- | --- | --- | --- |
| `key` | `varchar` | No | `—` | Primary key |
| `owner` | `varchar` | No | `—` | — |
| `expiration` | `INTEGER` | No | `—` | Index: cache_locks_expiration_index |

Constraints and indexes: Primary key key; Non-unique index cache_locks_expiration_index (expiration).

## `careers`

| Field | SQLite type | Nullable | Default | Keys and notes |
| --- | --- | --- | --- | --- |
| `id` | `INTEGER` | No | `—` | Primary key, auto-increment |
| `title` | `varchar` | No | `—` | — |
| `slug` | `varchar` | No | `—` | Unique index: careers_slug_unique |
| `location` | `varchar` | Yes | `—` | — |
| `employment_type` | `varchar` | Yes | `—` | — |
| `posted_at` | `date` | Yes | `—` | — |
| `closes_at` | `date` | Yes | `—` | — |
| `body` | `TEXT` | Yes | `—` | — |
| `is_active` | `tinyint(1)` | No | `'1'` | — |
| `created_at` | `datetime` | Yes | `—` | — |
| `updated_at` | `datetime` | Yes | `—` | — |

Constraints and indexes: Primary key id; Unique index careers_slug_unique (slug).

## `contact_messages`

| Field | SQLite type | Nullable | Default | Keys and notes |
| --- | --- | --- | --- | --- |
| `id` | `INTEGER` | No | `—` | Primary key, auto-increment |
| `name` | `varchar` | No | `—` | — |
| `email` | `varchar` | No | `—` | — |
| `organization` | `varchar` | Yes | `—` | — |
| `message` | `TEXT` | No | `—` | — |
| `status` | `varchar` | No | `'new'` | — |
| `created_at` | `datetime` | Yes | `—` | — |
| `updated_at` | `datetime` | Yes | `—` | — |

Constraints and indexes: Primary key id.

## `failed_jobs`

| Field | SQLite type | Nullable | Default | Keys and notes |
| --- | --- | --- | --- | --- |
| `id` | `INTEGER` | No | `—` | Primary key, auto-increment |
| `uuid` | `varchar` | No | `—` | Unique index: failed_jobs_uuid_unique |
| `connection` | `varchar` | No | `—` | Index: failed_jobs_connection_queue_failed_at_index |
| `queue` | `varchar` | No | `—` | Index: failed_jobs_connection_queue_failed_at_index |
| `payload` | `TEXT` | No | `—` | — |
| `exception` | `TEXT` | No | `—` | — |
| `failed_at` | `datetime` | No | `CURRENT_TIMESTAMP` | Index: failed_jobs_connection_queue_failed_at_index |

Constraints and indexes: Primary key id; Unique index failed_jobs_uuid_unique (uuid); Non-unique index failed_jobs_connection_queue_failed_at_index (connection, queue, failed_at).

## `job_batches`

| Field | SQLite type | Nullable | Default | Keys and notes |
| --- | --- | --- | --- | --- |
| `id` | `varchar` | No | `—` | Primary key |
| `name` | `varchar` | No | `—` | — |
| `total_jobs` | `INTEGER` | No | `—` | — |
| `pending_jobs` | `INTEGER` | No | `—` | — |
| `failed_jobs` | `INTEGER` | No | `—` | — |
| `failed_job_ids` | `TEXT` | No | `—` | — |
| `options` | `TEXT` | Yes | `—` | — |
| `cancelled_at` | `INTEGER` | Yes | `—` | — |
| `created_at` | `INTEGER` | No | `—` | — |
| `finished_at` | `INTEGER` | Yes | `—` | — |

Constraints and indexes: Primary key id.

## `jobs`

| Field | SQLite type | Nullable | Default | Keys and notes |
| --- | --- | --- | --- | --- |
| `id` | `INTEGER` | No | `—` | Primary key, auto-increment |
| `queue` | `varchar` | No | `—` | Index: jobs_queue_index |
| `payload` | `TEXT` | No | `—` | — |
| `attempts` | `INTEGER` | No | `—` | — |
| `reserved_at` | `INTEGER` | Yes | `—` | — |
| `available_at` | `INTEGER` | No | `—` | — |
| `created_at` | `INTEGER` | No | `—` | — |

Constraints and indexes: Primary key id; Non-unique index jobs_queue_index (queue).

## `media_files`

| Field | SQLite type | Nullable | Default | Keys and notes |
| --- | --- | --- | --- | --- |
| `id` | `INTEGER` | No | `—` | Primary key, auto-increment |
| `title` | `varchar` | No | `—` | — |
| `original_name` | `varchar` | No | `—` | — |
| `file_name` | `varchar` | No | `—` | — |
| `file_path` | `varchar` | No | `—` | — |
| `file_type` | `varchar` | No | `'pdf'` | — |
| `mime_type` | `varchar` | No | `—` | — |
| `file_size` | `INTEGER` | Yes | `—` | — |
| `description` | `TEXT` | Yes | `—` | — |
| `uploaded_by` | `INTEGER` | Yes | `—` | Foreign key to users.id (on delete Set Null); Index: media_files_uploaded_by_index |
| `created_at` | `datetime` | Yes | `—` | — |
| `updated_at` | `datetime` | Yes | `—` | — |
| `cloudinary_public_id` | `varchar` | Yes | `—` | — |

Constraints and indexes: Primary key id; Foreign key uploaded_by → users.id (on delete Set Null); Non-unique index media_files_uploaded_by_index (uploaded_by).

## `migrations`

| Field | SQLite type | Nullable | Default | Keys and notes |
| --- | --- | --- | --- | --- |
| `id` | `INTEGER` | No | `—` | Primary key, auto-increment |
| `migration` | `varchar` | No | `—` | — |
| `batch` | `INTEGER` | No | `—` | — |

Constraints and indexes: Primary key id.

## `news_posts`

| Field | SQLite type | Nullable | Default | Keys and notes |
| --- | --- | --- | --- | --- |
| `id` | `INTEGER` | No | `—` | Primary key, auto-increment |
| `slug` | `varchar` | No | `—` | Unique index: news_posts_slug_unique |
| `title` | `varchar` | No | `—` | — |
| `published_at` | `date` | Yes | `—` | — |
| `location` | `varchar` | Yes | `—` | — |
| `excerpt` | `TEXT` | Yes | `—` | — |
| `body` | `TEXT` | Yes | `—` | — |
| `image` | `varchar` | Yes | `—` | — |
| `is_published` | `tinyint(1)` | No | `'1'` | — |
| `created_at` | `datetime` | Yes | `—` | — |
| `updated_at` | `datetime` | Yes | `—` | — |

Constraints and indexes: Primary key id; Unique index news_posts_slug_unique (slug).

## `pages`

| Field | SQLite type | Nullable | Default | Keys and notes |
| --- | --- | --- | --- | --- |
| `id` | `INTEGER` | No | `—` | Primary key, auto-increment |
| `slug` | `varchar` | No | `—` | Unique index: pages_slug_unique |
| `title` | `varchar` | No | `—` | Index: pages_parent_id_status_sort_order_title_index |
| `section` | `varchar` | No | `'general'` | Index: pages_section_is_published_index |
| `meta_description` | `varchar` | Yes | `—` | — |
| `excerpt` | `TEXT` | Yes | `—` | — |
| `body` | `TEXT` | Yes | `—` | — |
| `template` | `varchar` | No | `'standard'` | — |
| `sort_order` | `INTEGER` | No | `'0'` | Index: pages_parent_id_status_sort_order_title_index |
| `is_published` | `tinyint(1)` | No | `'1'` | Index: pages_section_is_published_index |
| `created_at` | `datetime` | Yes | `—` | — |
| `updated_at` | `datetime` | Yes | `—` | — |
| `hero_title` | `varchar` | Yes | `—` | — |
| `hero_subtitle` | `varchar` | Yes | `—` | — |
| `page_type` | `varchar` | No | `'built_in'` | — |
| `status` | `varchar` | No | `'published'` | Index: pages_parent_id_status_sort_order_title_index |
| `show_in_navigation` | `tinyint(1)` | No | `'0'` | — |
| `navigation_label` | `varchar` | Yes | `—` | — |
| `published_at` | `datetime` | Yes | `—` | — |
| `parent_id` | `INTEGER` | Yes | `—` | Foreign key to pages.id (on delete Set Null); Index: pages_parent_id_status_sort_order_title_index |
| `created_by` | `INTEGER` | Yes | `—` | Foreign key to users.id (on delete Set Null); Index: pages_created_by_index |
| `updated_by` | `INTEGER` | Yes | `—` | Foreign key to users.id (on delete Set Null); Index: pages_updated_by_index |
| `meta_title` | `varchar` | Yes | `—` | — |
| `meta_keywords` | `TEXT` | Yes | `—` | — |
| `custom_html` | `TEXT` | Yes | `—` | — |

Constraints and indexes: Primary key id; Foreign key updated_by → users.id (on delete Set Null); Foreign key created_by → users.id (on delete Set Null); Foreign key parent_id → pages.id (on delete Set Null); Non-unique index pages_updated_by_index (updated_by); Non-unique index pages_created_by_index (created_by); Non-unique index pages_parent_id_status_sort_order_title_index (parent_id, status, sort_order, title); Unique index pages_slug_unique (slug); Non-unique index pages_section_is_published_index (section, is_published).

## `password_reset_tokens`

| Field | SQLite type | Nullable | Default | Keys and notes |
| --- | --- | --- | --- | --- |
| `email` | `varchar` | No | `—` | Primary key |
| `token` | `varchar` | No | `—` | — |
| `created_at` | `datetime` | Yes | `—` | — |

Constraints and indexes: Primary key email.

## `portfolio_items`

| Field | SQLite type | Nullable | Default | Keys and notes |
| --- | --- | --- | --- | --- |
| `id` | `INTEGER` | No | `—` | Primary key, auto-increment |
| `title` | `varchar` | No | `—` | — |
| `excerpt` | `TEXT` | Yes | `—` | — |
| `url` | `varchar` | Yes | `—` | — |
| `image` | `varchar` | Yes | `—` | — |
| `featured` | `tinyint(1)` | No | `'0'` | — |
| `sort_order` | `INTEGER` | No | `'0'` | — |
| `created_at` | `datetime` | Yes | `—` | — |
| `updated_at` | `datetime` | Yes | `—` | — |

Constraints and indexes: Primary key id.

## `sessions`

| Field | SQLite type | Nullable | Default | Keys and notes |
| --- | --- | --- | --- | --- |
| `id` | `varchar` | No | `—` | Primary key |
| `user_id` | `INTEGER` | Yes | `—` | Index: sessions_user_id_index |
| `ip_address` | `varchar` | Yes | `—` | — |
| `user_agent` | `TEXT` | Yes | `—` | — |
| `payload` | `TEXT` | No | `—` | — |
| `last_activity` | `INTEGER` | No | `—` | Index: sessions_last_activity_index |

Constraints and indexes: Primary key id; Non-unique index sessions_last_activity_index (last_activity); Non-unique index sessions_user_id_index (user_id).

## `site_settings`

| Field | SQLite type | Nullable | Default | Keys and notes |
| --- | --- | --- | --- | --- |
| `id` | `INTEGER` | No | `—` | Primary key, auto-increment |
| `key` | `varchar` | No | `—` | Unique index: site_settings_key_unique |
| `value` | `TEXT` | Yes | `—` | — |
| `type` | `varchar` | No | `'text'` | — |
| `group` | `varchar` | Yes | `—` | — |
| `created_at` | `datetime` | Yes | `—` | — |
| `updated_at` | `datetime` | Yes | `—` | — |

Constraints and indexes: Primary key id; Unique index site_settings_key_unique (key).

## `users`

| Field | SQLite type | Nullable | Default | Keys and notes |
| --- | --- | --- | --- | --- |
| `id` | `INTEGER` | No | `—` | Primary key, auto-increment |
| `name` | `varchar` | No | `—` | — |
| `email` | `varchar` | No | `—` | Unique index: users_email_unique |
| `email_verified_at` | `datetime` | Yes | `—` | — |
| `password` | `varchar` | No | `—` | — |
| `remember_token` | `varchar` | Yes | `—` | — |
| `created_at` | `datetime` | Yes | `—` | — |
| `updated_at` | `datetime` | Yes | `—` | — |

Constraints and indexes: Primary key id; Unique index users_email_unique (email).

## Coverage Verification

- This file contains 17 table sections, matching the 17 entries in `day9-schema-and-migrations.md`.
- Fields, types, nullability, defaults, primary keys, foreign keys, unique constraints, and explicit non-primary-key indexes are derived from the current local SQLite schema.
