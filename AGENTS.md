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
  (hidden `action` input) in `resources/views/admin/dashboard.blade.php`.
- Admin dashboard is **modal-based** (since 2026-08-15): every section is a
  `.section-card` button that opens a `.edit-modal.section-modal`. Only **Products,
  Services, and Customization** use modals: Products = 2 (Add Product, Products list),
  Services = 7 (Add Service Photo + one per category), Customization = 7 (Flowers,
  Flower Variants, Fillers, Wrapper Colors, Ribbons, Ribbon Variants, Styles).
  **Categories, Orders, Messages, and Reports are plain cards** (like Payments) — no
  modals there. Each section modal uses `.edit-modal-box.wide` with a `.modal-head`
  + `.modal-close`. Modals live INSIDE their tab panel (hidden when tab inactive).
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
- Admin dashboard is sticky after saves AND pagination clicks:
  `saveDashboardState()`/`restoreDashboardState()` (sessionStorage `hs_scroll` +
  `hs_open_modal` + `hs_modal_scroll`) — the open **section modal** is captured
  (the form's enclosing `.section-modal`, or the active tab panel's open modal), and
  scroll + modal are restored after the POST redirect. The global capture listener
  excludes `editProductModal`/`editServicePhotoModal`/`photoLightbox` (they aren't
  `.section-modal`, so they never reopen with stale data). Pagination links
  (`.pagination a`) also call `saveDashboardState()` on click, so switching pages
  keeps the page position and reopens the modal on modal tabs (Products/Services/
  Customization). On plain-card tabs (Orders/Messages) the scroll position persists.
- Messages: `loadMessages(Request $request)` is paginated at **20/page** with a
  `message_search` filter (name/email/message LIKE) and `mpage` param. Orders are also
  paginated at **20/page** (`opage`). Pagination only renders when >1 page.
- Payment: **both COD and GCash require a 50% GCash down payment** before confirmation.
  `orders.payment_status` flow: `pending_downpayment` (Unpaid) → `partial` (Deposit
  Paid) → `completed` (Fully Paid on delivery). GCash screenshot is required.
- Admin dashboard sidebar: solid `#8a9b6e`, sticky (never scrolls with content).
