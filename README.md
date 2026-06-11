# thlin.ca — THLIN Corporate Website Rebuild

Laravel (PHP Blade) redesign of the **thehealthline.ca Information Network** corporate site.

## Tech stack

| Layer | Technology |
|-------|------------|
| Backend | PHP 8.4, Laravel 13, Blade |
| Database | SQLite (default) or SQL Server |
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

## CMS features

- Controlled editing for built-in pages
- Custom page creation with draft/publish workflow
- TinyMCE WYSIWYG editor for page content
- Image upload inside page content
- Internal and external links in page content
- PDF upload for Annual Reports
- Uploaded Files library with copy-link support
- Optional Resources navigation dropdown for custom pages
- Public search includes published custom pages

After CMS changes, run:

```bash
php artisan migrate
php artisan storage:link
php artisan view:clear
php artisan cache:clear
php artisan config:clear
```

TinyMCE currently uses `no-api-key` for local testing. For production, create a free TinyMCE API key and replace `no-api-key` in the TinyMCE script URL inside the page form partial.

**Vercel upload warning:** Uploaded files are not persistent on Vercel serverless storage. Use Cloudinary, AWS S3, or Supabase Storage for real production uploads.

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
| Custom pages | `/annual-reports`, `/privacy-policy`, … |

## Database tables

- `pages` — static page content (slug, title, body, template, CMS fields)
- `media_files` — uploaded PDF files for Annual Reports and resources
- `news_posts` — news articles
- `careers` — job postings
- `board_members` — board of directors
- `portfolio_items` — portfolio (featured items show on home)
- `users` — admin login

## SQL Server

Set in `.env`:

```env
DB_CONNECTION=sqlsrv
DB_HOST=your-server
DB_DATABASE=thlin
DB_USERNAME=...
DB_PASSWORD=...
```

Requires `pdo_sqlsrv`. Then run `php artisan migrate --seed`.

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
