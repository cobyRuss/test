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

## User preferences (standing permissions & habits)

- **Standing permission granted (2026-08-21): the user allows file/system access on
  their machine at all times — never ask permission for drive/file access again.**
  Just do it (still explain what a non-trivial command does before running it).
- **When the user asks to change something in one section, remind them of sibling
  sections that share the same pattern** before/while implementing (e.g. when they
  asked to hide slugs on Flowers/Fillers/Ribbons/Styles, Wrapper Colors also had them
  and needed the change later). Proactively list affected siblings; let them decide.
- Slugs on ALL customization option types (flower, color, filler, ribbon, style) are
  now system-managed via `uniqueOptionSlug()` — never surface a slug editor in admin UI.

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
- Custom bouquet selection model: each flower's **color variant has its own quantity
  stepper** (image swatch, no hex fallback) — multiple colors of the same flower are
  separate line items, e.g. `3x Local Roses (Red)` + `5x Local Roses (White)`. Stored in
  session `custom_arrangement` as a structured array `items` = [{flower, color, size, qty}]
  plus `total_stems`, `description` (display text from JS `summaryText()`), `price`,
  `quantity` (1). Built in `resources/views/customize/index.blade.php`, stored by
  `CartController@addCustom` (via `normalizeCustomItems()`). Flowers WITHOUT color
  variants (e.g. Sunflowers) keep a single flower-level stepper (`qty`). Price per line:
  size price > 0 wins, else color price > 0, else flower base price.
- `order_items` has a nullable `description` column (added 2026-08-15); checkout persists
  the custom breakdown there and the order/cancel/cancel-success views render it.

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
  linked **flower variant** is active AND its parent flower option is active
  (`Product::is_available`, `categoryAvailability()`). Inactive
  flowers/fillers are hidden on the customize page; inactive variants are never loaded.
- Products (2026-08-16): the old `flower_product` pivot is **gone**. Products link to
  **flower variants with pcs** via `product_flower_variants(product_id, variant_id, quantity)`.
  `Product::flowerVariants()` (withPivot('quantity')); Add/Edit Product admin modals use a
  grouped variant picker (checkbox + pcs per variant; blank pcs → random 5–30) and an
  **Active switch** instead of a select. Auto description when blank on add:
  `Includes: Nx Flower (Variant), …`. `products.id` is `int` — the pivot's `product_id` FK
  must be `integer`, while `variant_id` is `unsignedBigInteger` (errno 150 otherwise).
- Fixed-product cart quantity is capped at **20 per line item** (2026-08-17):
  `CartController::MAX_PRODUCT_QTY = 20`. `add()` caps new lines and stops incrementing at
  20 (JSON message `(!) Sorry, the maximum value is reached`); `update()` clamps and flashes
  `cart_error`. Cart page shows a `−/+` stepper (`.qty-stepper` in `cart/index.blade.php`)
  that blocks increments past 20 and shows the inline warning. Custom arrangements
  (cart_id `custom`) are NOT capped.
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
- **Notifications** (2026-08-17): custom `notifications` table
  (`recipient_type` admin|customer, `recipient_id`, `type`, `title`, `body`, `link`, `is_read`,
  `read_at`), NOT Laravel's default `notifiable` morph table. Creation via
  `App\Services\NotificationService` (`sendToAdmins`, `sendToCustomer`). Triggers: admin gets
  new_order (checkout), payment_pending (GCash submitted), order_cancelled (customer cancel),
  new_message (contact form); customer gets payment_confirmed/order_status (admin
  verify/approve/decline/status/mark_paid) and admin_reply (new `reply_message` admin action —
  `contact_messages` gained `customer_id`, `admin_reply`, `replied_at`; replies notify the
  matching customer, resolved via `customer_id` then email). Polling endpoints (JSON
  `{count,items}`, mark single or all read): admin `/admin/notifications/unread|read` (polls
  every 15s), customer `/notifications/unread|read` (every 20s in `public/js/main.js`). Bell +
  dropdown: admin topbar in `admin/dashboard.blade.php` (admin links are markers `orders:{id}` /
  `payments:{id}` / `messages` handled by JS → `switchTab()` + `openOrderDetails()`); customer
  navbar in `layouts/app.blade.php` `@auth('web')` block. History pages: `/account/notifications`
  and `/account/messages` (marks all read on visit). **Chosen approach: HTTP polling, NOT
  websockets** — near-real-time, zero infra, works on XAMPP/Apache; tradeoff is up to ~15-20s
  latency vs true push (Pusher/Laravel Echo) which would need a broadcaster + external deps.
- Payment: **GCash only, 100% paid upfront** — COD removed (2026-08-16). Checkout has only the
  GCash option; `orders.down_payment` = full total, `remaining_balance` = 0. GCash number +
  account name come from `config/happystem.php` (`GCASH_NUMBER`, `GCASH_ACCOUNT_NAME` in `.env`).
  `orders.payment_status` flow: `pending_downpayment` (Unpaid) → `partial` (Payment Submitted,
  awaiting verification) → `completed` (Paid — admin Verify marks it completed). GCash
  screenshot is required; admin views it inline via the `#gcashLightbox` modal (thumbnails in
  the Payments tab and the Orders tab Payment column). **Admin payment actions are ONLY in the
  Payments tab** (Verify = payment confirmed + order auto-confirmed; Decline = payment rejected +
  order auto-cancelled). The Orders tab status dropdown **hides "cancelled" when payment is
  verified** and **locks to "cancelled" when declined**. Customers can cancel unpaid orders from
  the GCash payment page (restores fixed-product items to cart). Phone numbers are stored as
  10-digit `9xxxxxxxxx` format (no leading `0`).
- Admin dashboard sidebar: solid `#8a9b6e`, sticky (never scrolls with content), stretched to
  the bottom of the page (`align-self: stretch` on `.admin-nav`).
- Admin payment/order workflow (2026-08-21): **Payments tab = "GCash Payment History"** — every
  payment row shows a `gcash_payments.status` badge (`pending|verified|declined`, new enum
  column from migration `2026_08_21_000001`; declines also stamp `verified_by`/`verified_at`).
  Filterable All/Pending/Verified/Declined (`payment_filter`) + paginated 20/page (`ppage`).
  Verify/Decline buttons are ACTIVE only on pending rows — verified/declined rows render them
  disabled. **Orders tab**: while a payment is unreviewed (`payment_status='partial'`) the
  status cell shows "⏳ Waiting for verification" instead of the dropdown, and
  `update_order_status` rejects changes server-side. After verify: dropdown returns
  ("cancelled" hidden); after decline: locked to "cancelled"; delivered locks too. Customers can
  cancel unpaid orders from the GCash payment page (restores fixed-product items to cart).
- Reviews/Ratings **removed entirely** (2026-08-18): the `@for` Blade directive inside
  product cards within `@foreach` + `@extends('layouts.app')` caused infinite output
  generation on PHP 8.2.12 ZTS / Windows, hanging all pages that use the shared layout.
  Tables `reviews` and `review_photos` dropped; models `Review`, `ReviewPhoto` and
  `ReviewController` deleted; all star-rating display removed from home, shop, product
  detail, and admin dashboard. **Do not re-add `@for` inside `@foreach` inside
  `@extends`** — use `@php` helpers or inline logic for repeated elements.
