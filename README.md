# thlin.ca — THLIN Corporate Website Rebuild

Laravel (PHP Blade) redesign of the **thehealthline.ca Information Network** corporate site.

## Live demo

| Environment | URL |
|-------------|-----|
| **Vercel (public demo)** | https://thlin-ca.vercel.app/ |
| **Local (Laravel Herd)** | http://thlin.ca.test |
| **Production reference** | https://thlin.ca |

## Tech stack

| Layer | Technology |
|-------|------------|
| Backend | PHP 8.4, Laravel 13, Blade |
| Database | SQLite for the current project scope and demo environment |
| Frontend | HTML, CSS, JavaScript |
| CMS editor | Self-hosted TinyMCE (GPL) + Cropper.js for image editing |
| Auth | Admin login + TOTP two-factor authentication (2FA) |
| Hosting | Vercel (demo), Laravel Herd (local) |

**Brand colours only:** `#185FA5` (blue), `#3B6D11` (green), `#BA7517` (orange)

## Progress to date

Work completed across CMS, security, design, and content editing:

### Security & admin access

- **Two-factor authentication (2FA)** for admin login — TOTP codes via Google Authenticator, Authy, Microsoft Authenticator, etc.
- First sign-in QR setup flow; verify step on every login after password
- Admin credentials via `THLIN_ADMIN_EMAIL` and `THLIN_ADMIN_PASSWORD` in `.env`

### Public site design

- **Homepage hero** — animated/video layer with healthline background image underneath
- **Inner page heroes** — full-width image banner with blue overlay; header floats transparently over the hero
- **Navigation** — white nav labels on hero pages; dropdown menus open one at a time with white panels
- **Products & Services, Partners, About** — dropdown triggers (not direct links) so submenus work reliably
- **Typography & layout** — Inter font, improved readability, responsive tables/embeds in page content
- **Admin edit bar** — moved to the **bottom** of the screen so it no longer blocks navigation dropdowns

### CMS — pages & content

- **Built-in page editing** — edit protected layout pages (About, Products, Board, etc.) without breaking structure
- **Custom pages** — create pages with draft/publish workflow, parent/child URLs, and menu visibility
- **Custom HTML** — paste full HTML (charts, embeds, tables) on **all** pages, built-in and custom
- **Existing page editing** — images, tables, and sections load correctly in TinyMCE when reopening a page (`CmsEditorContent` URL normalization)
- **Image tools in editor** — click any image for align left/center/right, full width, move up/down, replace, crop, resize, remove
- **Image upload** — drag/paste/upload to `/storage/`; shared upload handler with CSRF support
- **Preview mode** — “View Website” opens `?preview=1` (no admin bar); inline editing uses `?edit=1`
- **Structured content panels** — Board, News, Careers, and Portfolio pages link to their dedicated admin sections from the page editor

### CMS — special page types

| Page | Main content editor | Photos / lists |
|------|---------------------|----------------|
| Most pages | TinyMCE body + Custom HTML | In editor |
| Board of Directors | Intro text only | **Admin → Board** (or click photos on live site) |
| News | Page intro | **Admin → News** |
| Careers | Page intro | **Admin → Careers** |
| Portfolio | Page intro | **Admin → Portfolio** |

- **Inline editing** on the live site for logged-in admins — text, bios, and board/portfolio photos
- **PDF / file library** for Annual Reports and downloadable resources
- **Search** across published pages, news, careers, board, and portfolio

### Page URL rules

| Type | Example |
|------|---------|
| Built-in home | `/` |
| Built-in contact | `/contact` |
| Built-in landing | `/products-services`, `/partners`, `/about` |
| Built-in section page | `/products/healthline`, `/about/us` |
| Custom page (no parent) | `/privacy-policy`, `/basic` |
| Custom child page | `/products-services/testing`, `/about/annual-reports` |

Custom pages must be **Published** (not Draft) to appear on the public site and in menus.

## Quick start

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

Open http://thlin.ca.test (Herd) or https://thlin-ca.vercel.app/ (demo).

## Admin

- **Local:** http://thlin.ca.test/admin/login
- **Demo:** https://thlin-ca.vercel.app/admin/login
- Credentials: `THLIN_ADMIN_EMAIL` and `THLIN_ADMIN_PASSWORD` in `.env`
- **2FA:** after password, enter a 6-digit code from your authenticator app
- Apply admin email changes: `php artisan db:seed --class=AdminUserSeeder`

Manage **pages**, **news**, **careers**, **board members**, **portfolio items**, and **uploaded files** without editing code.

## CMS commands

After CMS or schema changes:

```bash
php artisan migrate
php artisan storage:link
php artisan view:clear
php artisan cache:clear
php artisan route:clear
php artisan config:clear
```

## TinyMCE

TinyMCE is **self-hosted** from `public/vendor/tinymce` (free, permanent — no API key or domain whitelist).

After `npm install`, assets copy automatically. To refresh manually:

```bash
npm install
node scripts/copy-tinymce.cjs
```

Optional: set `TINYMCE_SELF_HOSTED=false` and `TINYMCE_API_KEY` in `.env` to use Tiny Cloud instead.

## Upload storage & Vercel note

**Database and hosting warning:** SQLite is the approved database for the current scope. A Vercel serverless filesystem is not durable for database writes, so CMS changes on https://thlin-ca.vercel.app/ are for demo purposes — content may not persist reliably. Use local Herd for full CMS testing. Back up SQLite before content or schema changes. A future production database migration will be planned separately with the client’s SQL Server 2016/2019 environment.

See:

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
| Custom pages | `/privacy-policy`, `/products-services/testing`, `/basic` |

## Database tables

- `pages` — static page content (slug, title, body, custom_html, template, CMS fields)
- `media_files` — uploaded PDF files for Annual Reports and resources
- `news_posts` — news articles
- `careers` — job postings
- `board_members` — board of directors (photos & bios)
- `portfolio_items` — portfolio (featured items show on home)
- `users` — admin login (+ `two_factor_secret` for 2FA)

## Database deployment status

The current project uses SQLite (`DB_CONNECTION=sqlite`). Do not configure PostgreSQL, Neon, `DATABASE_URL`, or remote database migration commands for this scope.

SQLite is appropriate for the current self-contained demo and local development. Before a production deployment that requires durable multi-user CMS editing, the team will run a separate, approved migration to the client’s Microsoft SQL Server 2016/2019 environment.

## Project structure

```
app/Http/Controllers/       Public + Admin controllers
app/Http/Middleware/        Public preview mode (?preview=1)
app/Models/                 Page, MediaFile, NewsPost, Career, BoardMember, PortfolioItem, User
app/Support/                AdminTwoFactor, CmsEditorContent, CmsBodyFormatter, CloudinaryStorage
config/thlin.php            Site settings & navigation
config/admin.php            Default admin credentials
database/seeders/           Full content from thlin.ca spec
resources/views/            Blade templates (public + admin CMS)
public/css/                 Modular stylesheets (tokens, header, hero, navigation, …)
public/vendor/tinymce/      Self-hosted editor
```

## Reference

- **Live demo:** https://thlin-ca.vercel.app/
- **Production site:** https://thlin.ca
