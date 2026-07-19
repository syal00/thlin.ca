# thlin.ca — THLIN Corporate Website Rebuild

Laravel (PHP Blade) redesign of the **thehealthline.ca Information Network** corporate site.

## Tech stack

| Layer | Technology |
|-------|------------|
| Backend | PHP 8.4, Laravel 13, Blade |
| Database | SQLite for local development; PostgreSQL for Vercel production/preview |
| Frontend | HTML, CSS, JavaScript |
| Local URL | http://thlin.ca.test (Laravel Herd) |

**Brand colours only:** `#185FA5` (blue), `#3B6D11` (green), `#BA7517` (orange)

## Quick start

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

## Admin

- URL: http://thlin.ca.test/admin/login
- Credentials: `config/admin.php` or `.env` (`THLIN_ADMIN_EMAIL`, `THLIN_ADMIN_PASSWORD`)

Manage **pages**, **news**, **careers**, **board members**, and **portfolio items** without editing code.

## CMS features for project

The admin CMS supports:

- **Built-in page editing** — edit protected layout pages without deleting them
- **Custom page creation** — add new pages with draft/publish workflow
- **Parent/child pages** — attach custom pages under landing pages such as `/products-services` or `/about`
- **Correct URL generation** — CMS previews and Quick Internal Links use each page's `full_url`
- **TinyMCE WYSIWYG editor** — headings, lists, links, tables, images, and code view
- **Image upload in TinyMCE** — uploads to `storage/app/public/uploads/images`
- **Uploaded Files library** — PDF upload for Annual Reports with copy-link support
- **Navigation control** — show/hide custom child pages in dropdown menus
- **Search** — published built-in and custom pages, news, careers, board, and portfolio
- **Inline editing** — preserved on the public site for logged-in admins

### Page URL rules

| Type | Example |
|------|---------|
| Built-in home | `/` |
| Built-in contact | `/contact` |
| Built-in landing | `/products-services`, `/partners`, `/about` |
| Built-in section page | `/products/healthline`, `/about/us` |
| Custom page (no parent) | `/privacy-policy` |
| Custom child page | `/products-services/testing`, `/about/annual-reports` |

### CMS commands

After CMS changes, run:

```bash
php artisan migrate
php artisan storage:link
php artisan view:clear
php artisan cache:clear
php artisan route:clear
php artisan config:clear
```

### TinyMCE

TinyMCE is **self-hosted** from `public/vendor/tinymce` (free, permanent — no API key or domain whitelist).

After `npm install`, assets copy automatically. To refresh manually:

```bash
npm install
node scripts/copy-tinymce.cjs
```

Optional: set `TINYMCE_SELF_HOSTED=false` and `TINYMCE_API_KEY` in `.env` to use Tiny Cloud instead.

### Upload storage

**Vercel warning:** Uploaded files are not persistent on Vercel serverless storage. Production/preview uploads should write file bodies to Vercel Blob and store metadata in PostgreSQL. Local development may continue using Laravel's local public disk.

See:

- `docs/phase-0-checklist.md`
- `docs/admin-managed-vercel-setup.md`
- `docs/vercel-data-architecture.md`

## URL map

| Section | Example URLs |
|---------|----------------|
| Home | `/` |
| Products | `/products/healthline`, `/products/healthchat`, `/products/portfolio`, … |
| Partners | `/partners/health-care`, `/partners/municipalities`, … |
| About | `/about/us`, `/about/board`, `/about/news`, `/about/careers`, … |
| News article | `/about/news/sean-wong` |
| Contact | `/contact` |
| Search | `/search?q=…` |
| Custom pages | `/privacy-policy`, `/products-services/testing`, `/about/annual-reports` |

## Database tables

- `pages` — static page content (slug, title, body, template, CMS fields)
- `media_files` — uploaded PDF files for Annual Reports and resources
- `news_posts` — news articles
- `careers` — job postings
- `board_members` — board of directors
- `portfolio_items` — portfolio (featured items show on home)
- `users` — admin login

## Production database

Local development uses SQLite by default. Vercel Production and Preview should use PostgreSQL with an SSL-enabled connection string configured in Vercel environment variables:

```env
DB_CONNECTION=pgsql
DATABASE_URL=postgresql://USER:PASSWORD@HOST:5432/DATABASE?sslmode=require
DB_SSLMODE=require
```

Run production migrations from a trusted environment:

```bash
php artisan migrate --force
```

## Project structure

```
app/Http/Controllers/     Public + Admin
app/Models/               Page, MediaFile, NewsPost, Career, BoardMember, PortfolioItem
app/Services/SiteSearch.php
config/thlin.php          Site settings & navigation
config/admin.php          Default admin credentials
database/seeders/         Full content from thlin.ca spec
resources/views/          Blade templates
public/css/thlin.css      Brand styles (AODA-oriented)
```

## Reference

Production site: https://thlin.ca
