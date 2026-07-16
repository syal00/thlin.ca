# THLIN — Complete Work Log (GitHub + Local + CMS)

**Project:** thlin.ca — Laravel CMS rebuild of thehealthline.ca  
**Branch:** `main.test`  
**Period:** May 27, 2026 → July 14, 2026  
**Repo:** https://github.com/syal00/thlin.ca  

This file includes **everything done so far**, whether or not it is already pushed to GitHub, **plus a full CMS section**.

---

## Sync status (as of July 14, 2026)

| Item | On GitHub? | Notes |
|---|---|---|
| Site redesign (simple pages, About/Board/Contact) | **Yes** | Commit `df7f6bd` |
| Vercel Cloudinary boot fix | **Yes** | Commit `a293e2b` |
| Responsive header/content frames | **Yes** | Commit `6b9541d` |
| Phases 1–3 (cleanup, security, polish) | **Yes** | `b30796a` … `c24f32a` |
| CMS admin redesign, pages, news, careers, etc. | **Yes** | Multiple commits on `main.test` |
| This work-log document (`docs/bugs-and-improvements.md`) | **No (local only)** | Untracked until you commit/push it |
| Exports like `thlin-alignment-review.zip` | Sometimes on GitHub by accident | Prefer keeping out of git |

> Rule used below:  
> - **[GitHub]** = landed in commits on `main.test` / origin  
> - **[Local / session]** = done in Cursor sessions; may live in code that was later committed under a broader message, or only exists as docs until pushed  

---

## Big picture counts

| Category | Count (approx.) |
|---|---|
| Bugs / defects fixed | **28** |
| CMS features & CMS bugs fixed | **25** |
| Public UX / layout improvements | **18** |
| Deploy / infra / data | **14** |
| Docs & process | **6** |
| **Total tracked** | **~91** |

---

# PART 1 — CMS (Admin + content system)

Everything editors use to run the site. Access: `/admin` (login required).

## 1.1 What the CMS can manage

| CMS area | URL (admin) | Model / data | Public effect |
|---|---|---|---|
| **Dashboard** | `/admin` | Counts / overview | — |
| **Pages** | `/admin/pages` | `Page` | Products, partners, about, custom pages |
| **News** | `/admin/news` | `NewsPost` | `/about/news` + article pages |
| **Careers** | `/admin/careers` | `Career` | `/about/careers` (jobs listed inline) |
| **Board** | `/admin/board` | `BoardMember` | `/about/board` |
| **Portfolio** | `/admin/portfolio` | `PortfolioItem` | Portfolio / home featured |
| **Media** | `/admin/media` | `MediaFile` | Uploads for pages/editor |
| **Messages** | `/admin/messages` | `ContactMessage` | Contact form inbox |
| **Site settings** | `/admin/settings` | `SiteSetting` | Home labels, CTAs, shared copy |
| **Users** | `/admin/users` | `User` | Who can log into admin |
| **Inline editing guide** | `/admin/inline-editing` | — | How-to for public click-to-edit |
| **Login / logout** | `/admin/login` | Auth | Session gate |

**Status:** CMS core is **on GitHub**.

---

## 1.2 CMS features built (from the start)

| # | Feature | Status | Notes / commits |
|---|---|---|---|
| 1 | Full admin auth (login, throttle, logout) | **[GitHub]** | `febaa16` + later harden |
| 2 | Private admin user CRUD | **[GitHub]** | `febaa16` |
| 3 | Pages CRUD + publish / unpublish / preview | **[GitHub]** | Built-in + custom pages |
| 4 | Page templates (`show`, `board`, `news`, `careers`, portfolio…) | **[GitHub]** | `PageController` match |
| 5 | News posts CRUD | **[GitHub]** | List + public detail |
| 6 | Careers CRUD | **[GitHub]** | Listed on careers page |
| 7 | Board members CRUD (photo, role, bio) | **[GitHub]** | Public board loop |
| 8 | Portfolio items CRUD | **[GitHub]** | Featured / past |
| 9 | Media library | **[GitHub]** | Later Cloudinary/Vercel-aware |
| 10 | Contact messages inbox (read / delete) | **[GitHub]** | From `/contact` form |
| 11 | Site settings (home text, nav labels, etc.) | **[GitHub]** | `092086f` + fallbacks |
| 12 | TinyMCE rich text in admin | **[GitHub]** | Self-hosted or CDN key |
| 13 | Editor image upload | **[GitHub]** | `EditorUploadController` |
| 14 | **Inline editing on the live site** | **[GitHub]** | Click fields while logged in (`4dddd68`) |
| 15 | Inline image upload | **[GitHub]** | Type-hint fix `7201b16` |
| 16 | Modern admin workspace UI / dashboard | **[GitHub]** | `e4b2a5e` |
| 17 | Admin pagination on list screens | **[GitHub]** | Phase 3 `c24f32a` |
| 18 | Page editor attribution | **[GitHub]** | `b9deba0` |
| 19 | Custom pages + child pages in nav | **[GitHub]** | `CustomPageController` |
| 20 | Live content import into CMS | **[GitHub]** | `e15f506` |
| 21 | Postgres full-text search (CMS content searchable) | **[GitHub]** | Phase 3 |
| 22 | Cloudinary-backed uploads for serverless | **[GitHub]** | Phase 2 `7bd312f` |
| 23 | About body formatter (CMS HTML → intro/timeline) | **[GitHub]** | In `df7f6bd` designing |
| 24 | robots.txt admin-related controls | **[GitHub]** | Phase 3 |
| 25 | Inline-editing help page in admin | **[GitHub]** | `/admin/inline-editing` |

---

## 1.3 CMS bugs fixed

| # | Bug | Fix | Status |
|---|---|---|---|
| 1 | No way to manage admin users | User management module | **[GitHub]** |
| 2 | Content only editable in heavy forms | Inline editing on public pages | **[GitHub]** |
| 3 | Site settings migration clashed with SEO migration | Retimed migration `8eaf860` | **[GitHub]** |
| 4 | Site setting view helper missing / broke views | Helper fix `f4f8093` | **[GitHub]** |
| 5 | Inline upload URL type hint crashed | `7201b16` | **[GitHub]** |
| 6 | Missing `site_settings` table crashed site | Graceful handle `e2c233f` | **[GitHub]** |
| 7 | Homepage labels hard-coded | Site settings + fallbacks | **[GitHub]** |
| 8 | Admin UI hard to use on mobile | Redesigned workspace `e4b2a5e` | **[GitHub]** |
| 9 | Long admin lists unwieldy | Pagination phase 3 | **[GitHub]** |
| 10 | Uploads fail on Vercel (local disk) | Blob plan → Cloudinary path phase 2 | **[GitHub]** |
| 11 | Prod deploy died requiring Cloudinary at build | Optional `CLOUDINARY_URL` at boot `a293e2b` | **[GitHub]** |
| 12 | Board CMS body duplicated member loop | Don’t render body on board view | **[GitHub]** |
| 13 | Editing `pages/show` didn’t change News/Careers | Documented correct views per template | **[GitHub]** (code) + **[Local]** (this doc) |
| 14 | TinyMCE / CDN config unclear for prod | Config + self-hosted flag phase 2 | **[GitHub]** |
| 15 | SQLite → Postgres content move painful | Import tooling `49e6148` | **[GitHub]** |
| 16 | Dead one-time import / duplicate JS clutter | Phase 1 cleanup `b30796a` | **[GitHub]** |

---

## 1.4 CMS models (data owned by admin)

| Model | Purpose |
|---|---|
| `Page` | Built-in section pages + custom pages (body, SEO, template, publish) |
| `NewsPost` | News index + detail articles |
| `Career` | Job postings (inline on careers page) |
| `BoardMember` | Directors (photo, name, role, bio) |
| `PortfolioItem` | Featured / past projects |
| `MediaFile` | Uploaded assets |
| `ContactMessage` | Contact form submissions |
| `SiteSetting` | Key/value site-wide copy & switches |
| `User` | Admin accounts |

---

## 1.5 Public routes driven by CMS

| Public URL | Driven by |
|---|---|
| `/` | `Page` (home) + featured portfolio + settings |
| `/products/*`, `/partners/*`, `/about/*` | `Page` (+ template data) |
| `/about/news`, `/about/news/{slug}` | `Page` + `NewsPost` |
| `/about/careers` | `Page` + `Career` |
| `/about/board` | `Page` + `BoardMember` |
| `/about/annual-reports` | `Page` (special view) |
| `/contact` | Contact form → `ContactMessage` |
| `/search` | Site search (incl. full-text when on Postgres) |
| Custom `/{slug}`, `/{parent}/{child}` | Custom `Page`s |

---

# PART 2 — Public site bugs & layout (all time)

## 2.1 Deploy / foundation

| # | Issue | Fix | GitHub? |
|---|---|---|---|
| 1 | Needed full Laravel rebuild vs legacy .NET | Initial CMS site `90f639c` | Yes |
| 2 | Not deployable on Vercel | Serverless PHP config | Yes |
| 3 | Neon `DATABASE_URL` / storage broken | `4ad6eff` | Yes |
| 4 | Postgres seed / meta_description limits | `1b4ac55` | Yes |
| 5 | SQLite incompatible meta migration | `70b15af` | Yes |
| 6 | Homepage media broken on Vercel | `45c6941` | Yes |
| 7 | Hero animation failed live | Fallback mesh `940446b` | Yes |
| 8 | Inner pages used home animation | Static heroes `347d3a0` | Yes |

## 2.2 Design / CSS / layout

| # | Issue | Fix | GitHub? |
|---|---|---|---|
| 9 | CTA layout broken | `fb81bd1` | Yes |
| 10 | Product layout lost | `ba92d0e`, `61257a5`, `e55c2cf` | Yes |
| 11 | Layout inconsistent after merges | `e15f506` | Yes |
| 12 | Homepage spacing / CSS conflicts | `2fc69fd` | Yes |
| 13 | Cross-page style inconsistency | `7b9b40c` | Yes |
| 14 | Shells misaligned 1200 vs 1180 | Unified 1200/24 | Yes (`df7f6bd` / CSS commits) |
| 15 | Card/sidebar “dashboard” look | Simple hero + prose + `simple-page.css` | Yes |
| 16 | Floating pill header on all breakpoints | Flush inner header; responsive frames `6b9541d` | Yes |
| 17 | Hero CTAs under accessibility bar | Extra hero bottom padding | Yes |
| 18 | Mobile nav CTA stretched full width | Compact header CTA | Yes |
| 19 | Boxed H2 treatments | Neutralized in simple-page layer | Yes |

---

# PART 3 — Features & UX (non-CMS summary)

| Improvement | GitHub? |
|---|---|
| Homepage sections, animations, images | Yes |
| Site settings-driven home copy | Yes |
| Contact form + office block simplify | Yes |
| About Us formatter (intro/timeline) | Yes |
| Annual Reports dedicated Blade | Yes |
| News listing + article detail | Yes |
| Careers listing | Yes |
| Board stacked member bios | Yes |
| Responsive padding 24 / 20 / 16 | Yes |
| Accessibility toolbar + AI help (existing UI polish) | Yes |

---

# PART 4 — Infra & docs

| Item | GitHub? |
|---|---|
| Vercel serverless + Neon Postgres | Yes |
| Phase 0 architecture checklist | Yes (`docs/phase-0-checklist.md`) |
| Vercel data architecture plan | Yes |
| Admin-managed Vercel runbook (no plan upgrade) | Yes |
| SQLite → Postgres import tool | Yes |
| Cloudinary for serverless media | Yes |
| **This complete work log** | **No — local until committed** |

---

# PART 5 — Timeline (start → now)

| When | Focus |
|---|---|
| May 27 | Foundation: Laravel CMS + content + admin |
| Jun 8–9 | Admin users, inline editing, Vercel config |
| Jun 10–11 | Design sprint; Neon; storage; deploy triggers |
| Jun 13–17 | Media, animation, CMS UX merges |
| Jun 18 | Site settings; product/CTA layout bugs |
| Jun 21–23 | Admin redesign; import tool; layout unify |
| Jun 23–25 | Phases 1–3; Vercel admin runbook |
| Jul 14 | Simple redesign; route→view map; frames; Cloudinary deploy fix; **this log** |

---

# PART 6 — How CMS pages map to public views

Editors often edit the wrong file if they think only in Blade. Controllers pick the view:

| CMS page (section/slug or template) | Blade view |
|---|---|
| Home | `pages/home.blade.php` |
| About Us (`about` / `us`) | `pages/show.blade.php` |
| Board (`template: board`) | `pages/board.blade.php` |
| News (`template: news`) | `pages/news.blade.php` |
| Single news post | `news/show.blade.php` |
| Careers (`template: careers`) | `pages/careers.blade.php` |
| Annual reports (slug) | `pages/annual-reports.blade.php` |
| Products / partners | `partials/capability-page.blade.php` via `pages/show` |
| Contact | `contact/show.blade.php` |
| Custom pages | `pages/custom-show.blade.php` |
| Portfolio template | `pages/portfolio.blade.php` |

**Admin path for content:** `/admin/pages`, `/admin/news`, `/admin/careers`, `/admin/board`, `/admin/settings`.

---

# PART 7 — Still open / not finished

| Item | Where it lives |
|---|---|
| Push this work-log to GitHub | Local file only right now |
| Delete dead card CSS / duplicate EOF blocks in `thlin.css` | Recommended follow-up |
| Wire header search UI (CSS exists) | Incomplete |
| Ensure Vercel has `CLOUDINARY_URL` for real uploads | Ops / admin checklist |
| Remove zip artifacts from repo if accidental | Git hygiene |

---

# PART 8 — Diagnose cheatsheet

1. **CMS content not showing?** Check publish status + correct model (Page vs NewsPost vs Career).  
2. **Blade edit does nothing?** Confirm template (table in Part 6).  
3. **CSS ignored?** Last stylesheet wins: `simple-page.css`.  
4. **Align edges?** Always 1200 + padding 24/20/16 — not 1180.  
5. **Vercel red X?** Inspect deploy logs; don’t throw in `AppServiceProvider` during Composer discover.  
6. **Upload fails on prod?** Cloudinary / Blob env vars (admin runbook).

---

*Includes GitHub commits **and** local/session work. Update after each phase. Ask to commit this file if you want it on GitHub too.*
