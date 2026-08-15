# AGENTS.md — HappyStem (opencode working notes)

Instructions for the coding agent at the start of every session on this project.

## Reminder for the user (say this at the start of EVERY new session)

The user is working on their final thesis with HappyStem and often starts new
sessions out of worry about running out of tokens/context. At the start of each
session, briefly reassure them:

> New session, no problem — I don't remember our old chats, but all the work is
> saved on disk. AGENTS.md (auto-loaded) + PROJECT_NOTES.md have the full
> handoff: setup, DB, what's been done, known issues. Read `PROJECT_NOTES.md` to
> pick up right where we left off. Nothing is lost when you start fresh.

Keep it to 1–3 sentences. Then continue with the task as normal.

## Environment (Windows / XAMPP)

- Project root: `C:\xampp\htdocs\test` (this repo). Shell is bash (win32).
- PHP is NOT on PATH: always use `"C:/xampp/php/php.exe"` (e.g. `php artisan`).
- MySQL: `"C:/xampp/mysql/bin/mysql.exe"` (client), `mysqldump.exe` alongside it.
- DB: `happystem_db`, host `127.0.0.1`, user `root`, no password.
- `SESSION_DRIVER=database`. `.env` has `DB_DATABASE=happystem_db`.
- App timezone: **Asia/Manila** (`config/app.php`), not UTC — PHP/local time is used everywhere.
- App runs via `php artisan serve --host=127.0.0.1 --port=8000` (or XAMPP Apache at `/test/public`).
- Admin login: `/admin/login`, default `admin` / `admin123`. If the admin pages 500,
  MySQL is probably not running — start it (XAMPP Control → MySQL, or
  `C:\xampp\mysql\bin\mysqld.exe --defaults-file=C:/xampp/mysql/bin/my.ini`).
- Read `PROJECT_NOTES.md` for the full handoff context and known issues.

## Always do first

1. `git status` and `git log --oneline -10` to see current state.
2. Check DB tables with the mysql client if the task touches data.

## Code conventions

- Laravel project; follow existing patterns in `app/`, `resources/views/`.
- Blade views: inline `<style>` and `@push('scripts')` at the bottom — keep consistent.
- Admin CRUD lives in `app/Http/Controllers/Admin/DashboardController.php` as a big
  `switch ($request->input('action'))` in `handlePostActions()`, with a matching form
  (hidden `action` input) in `resources/views/admin/dashboard.blade.php` and a modal.
- Images upload via `storeUploadedImage()` (saves to `public/images/`); hex via
  `normalizeHexColor()`. Both already handle image+hex; image takes priority over hex.
- Custom bouquet is text-only: `description` string built client-side in
  `resources/views/customize/index.blade.php` (JS `summaryText()`), stored in session
  as `custom_arrangement` by `CartController@addCustom`.

## Database / dump rules

- **`database.sql` is the committed source of truth.** After any schema/seed change,
  regenerate it:
  `"C:/xampp/mysql/bin/mysqldump.exe" -u root -h 127.0.0.1 happystem_db > database.sql`
  then commit it. Windows writes CRLF; git normalizes to LF — that's fine.
- Do NOT commit orphan files (unreferenced uploads in `public/images/`).

## Verification checklist

- Lint changed PHP: `"C:/xampp/php/php.exe" -l <file>`
- Compile blades: `"C:/xampp/php/php.exe" artisan view:cache`
- Smoke test pages with curl against a running `artisan serve`:
  `curl -s -o /dev/null -w "%{http_code}\n" http://127.0.0.1:8000/<path>`
- Test admin POST actions by logging in with a temporary admin user (create, test,
  delete afterward) or via existing admin creds.

## Key decisions (do not silently undo)

- Ribbons: `customization_options.type = 'ribbon'`; color/size variants on
  `customization_option_variants`.
- Colors everywhere (flower variants, wrapper colors, ribbons) support **hex OR image**
  swatches; `image_url` wins over `hex_color`.
- Ribbon selection is single-choice + toggleable (click again to remove).
- Ribbon price: size variant price > 0 wins, else color variant price, else 0.
- Stock was **merged into Active (2026-08-15)**: no `stock_quantity`, no `in_stock` —
  "if active is on, it means it's in stock." One Active toggle per item (flowers, variants,
  ribbons, fillers, colors, styles). `CustomizationOption::isAvailable()` = `is_active`.
  A flower/filler is available iff active; a product is available iff `is_active` AND every
  linked flower is active (`Product::is_available`, `categoryAvailability()`). Inactive
  flowers/fillers are hidden on the customize page; inactive variants are never loaded.
- Admin dashboard is sticky after saves: `saveDashboardState()`/`restoreDashboardState()`
  (sessionStorage `hs_scroll` + `hs_open_card`) — the open card is captured within the
  **active tab panel only**, scroll + card restore after the POST redirect.
- Payment: **both COD and GCash require a 50% GCash down payment** before confirmation.
  `orders.payment_status` flow: `pending_downpayment` (Unpaid) → `partial` (Deposit
  Paid) → `completed` (Fully Paid on delivery). GCash screenshot is required.
- Admin dashboard sidebar: solid `#8a9b6e`, sticky (never scrolls with content).
