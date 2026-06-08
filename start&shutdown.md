# Project Startup and Shutdown

## Start After Boot

1. Open a terminal in the project directory:
   - `cd /Users/eldonshen/Desktop/thlin.ca`
2. Install PHP dependencies if `vendor/` is missing:
   - `composer install`
3. Create the local environment file if it is missing:
   - `cp .env.example .env`
4. Make sure the SQLite database file exists:
   - `touch database/database.sqlite`
5. If this is the first run, generate the app key and prepare the database:
   - `php artisan key:generate`
   - `php artisan migrate --seed`
6. Start the local Laravel server:
   - `php artisan serve --host=127.0.0.1 --port=8000`
7. Open the site:
   - `http://127.0.0.1:8000`

## Optional Frontend Tooling

The public site currently loads static files from `public/css/thlin.css` and `public/js/thlin.js`, so Node/Vite is not required just to view the site locally.

If you need the Laravel Vite pipeline, install and run the Node tooling:

- `npm install`
- `npm run dev`

## Admin Login

After running the seeders, the admin login is available at:

- `http://127.0.0.1:8000/admin/login`

Default local credentials come from `.env.example` unless changed in `.env`:

- Email: `admin@thlin.local`
- Password: `changeme`

## Stop Before Shutdown

1. Stop the Laravel server with `Ctrl+C` in the terminal where it is running.
2. If Vite or another Node process is running, stop it with `Ctrl+C` in that terminal too.
3. Close any remaining project terminals once all local processes have exited.
