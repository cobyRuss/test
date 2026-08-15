# HappyStem — Project Notes (handoff doc)

Live context for whoever works on this project next. Written 2026-08-12.

## What the app is

HappyStem by Carmencita — a flower shop web app. A Laravel port of the original
plain-PHP site (still at `C:\xampp\htdocs\happystem`, v1). This repo is the
current Laravel app ("main" branch).

Two customer-facing experiences:
- **Shop**: browse products, add to cart, checkout (COD / GCash with down payment + verification).
- **Customize**: build a custom bouquet — pick flowers (+ size/color variants), fillers, wrapper color, **ribbon**, and arrangement style. Custom design is stored as a text `description` in the cart session (`custom_arrangement`) and passed through to order items.

Admin side (`/admin/login`): products, categories, services, payments (GCash verify), orders, reports, messages, and a **Customization** tab for managing flowers, flower variants, wrapper colors, ribbons, styles, and fillers.

## Environment

- Project root: `C:\xampp\htdocs\test`
- XAMPP on Windows (bash shell in terminal)
- PHP: `C:/xampp/php/php.exe` (NOT on PATH — always call the full path)
- MySQL client: `C:/xampp/mysql/bin/mysql.exe`
- DB: `happystem_db`, host `127.0.0.1`, user `root`, **no password**
- SESSION_DRIVER=database (sessions live in the `sessions` table)
- Default admin: `admin` / `admin123`

### How to run

```bash
"C:/xampp/php/php.exe" artisan serve --host=127.0.0.1 --port=8000
```

Then http://127.0.0.1:8000 (customer) or http://127.0.0.1:8000/admin/login (admin).

### Useful DB commands

```bash
"C:/xampp/mysql/bin/mysql.exe" -u root -h 127.0.0.1 -e "USE happystem_db; SHOW TABLES;"
"C:/xampp/mysql/bin/mysqldump.exe" -u root -h 127.0.0.1 happystem_db > database.sql
```

## Git workflow

- Branches: `main` (current work) and `v2` (older feature branch; mostly merged).
- **`database.sql` is tracked and is the source of truth for the DB dump.** After
  changing the DB schema or seed data, regenerate it (`mysqldump` above) and commit.
- Repo: https://github.com/cobyRuss/test.git
- Never commit unrelated/orphan files (e.g. unreferenced test uploads in `public/images/`).

## What's been done recently

- Imported `v2` branch's DB into `happystem_db` (replaced old data). v2 removed
  filler images/prices; image files still exist in `public/images/` but rows have `image_url = NULL`.
- Added **ribbon** customization type:
  - DB: `customization_options.type` enum extended with `'ribbon'`; seeded
    "Satin Ribbon" (id 41) + "Organza Ribbon" (id 42) with color/size variants.
  - Customize page: new Ribbon section after Wrapper Color.
  - Admin: Ribbons + Ribbon Variants (Colors/Sizes) cards, edit modal.
- Added **hex-or-image swatches** everywhere colors are shown: flower color variants,
  wrapper colors, and ribbon colors. If a variant/option has `image_url`, the swatch
  renders the image (patterns); otherwise it falls back to `hex_color`.
- Ribbon selection is toggleable: click the same color/size again to deselect; picking
  another ribbon clears the previous ribbon's selection (one ribbon at a time).
- **Products are now multi-category (many-to-many).** New pivot table `category_product`
  (`product_id` + `category_id`); the old `products.category` string column was dropped
  and its data migrated into the pivot. Products appear in every category they belong to
  (shop filter, home filter, related items, admin filter). Admin product form is now a
  multi-select (`categories[]`). Seeder attaches categories via the relation.
- **Flower stock → product availability cascade.** `customization_options.stock_quantity`
  (default 100; 0 = out of stock) drives product availability through a new `flower_product`
  pivot (`product_id` + `flower_id`, FK cascade). `Product::is_available` is true only if
  `is_active` AND every linked flower has `stock_quantity > 0`. Products with no flower
  links are always available (unless manually deactivated). `products.is_active` (admin
  toggle) also hides products. UI: storefront cards/detail pages get an "Unavailable"
  overlay and disabled buttons; category filter buttons show "(Unavailable)" when a whole
  category is out of stock; customize page hides out-of-stock flowers; cart add endpoint
  rejects unavailable products. Admin: product form has "Flowers used" multi-select +
  Active toggle, edit modal populates them, products table shows an Availability badge,
  flower form/table/modal show Stock Qty. Seeder links products to flowers by category.
- Fixed/checked intermittent **419 "Page Expired"** on login (CSRF): verified sessions
  table exists; was a stale-session/cookie issue, not a bug.
- **Admin flowers table cleaned up** (Customization tab):
  - No more slug/`name` column or slug input field. Slugs are auto-derived from the
    Display Name server-side in `DashboardController::slugifyFlowerName()` ("Local Roses"
    -> `local_roses`; empty -> `flower_<timestamp>`), so the internal `name` column still
    gets populated but is never shown or typed by hand.
  - Inline editing: click display name / name / price / stock / sort right in the table;
    the row's Save button submits everything (shared hidden `flowerEditForm`). No Edit modal.
  - Active is a toggle switch (green = on, red = off).
  - Thumbnail is clickable -> enlarges in a lightbox with a "Replace photo" option that
    picks a new file; the new file submits with the next Save on that row.
  - Delete is a trash icon with a confirm.
- **Same inline-edit cleanup applied to the rest of the Customization tab** — flower
  variants, ribbon variants (both share `variantEditForm`/`variantDeleteForm`), fillers,
  wrapper colors, ribbons, and styles all use the same pattern: no slug fields anywhere
  (Add forms too), click-to-edit inline inputs, Save + trash buttons, green/red Active
  switch, and a **single shared photo lightbox** (`#photoLightbox`) for enlarge/replace.
  Wrapper colors and ribbons also get a "Remove photo" option in the lightbox (sets
  `clear_image`, since they can fall back to `hex_color`). The old edit modals
  (`editColorModal`, `editRibbonModal`, `editStyleModal`, `editVariantModal`,
  `editFillerModal`) were deleted. Color/variant hex is preserved/editable inline.

## Admin dashboard UX (2026-08-13)

> **Update 2026-08-15:** the collapsible/accordion card behavior described below was
> removed — cards are always expanded now. Everything else (item counts, pagination
> params, page-state restore) still applies.

- **Collapsible accordion cards.** The Customization tab's cards (Flowers, Flower
  Variants, Fillers, Wrapper Colors, Ribbons, Ribbon Variants, Styles) are now
  click-to-expand/collapse dropdowns. Only ONE card can be open at a time (accordion),
  and clicking an open card closes it. Everything starts collapsed on load.
- **Add-form + list cards merged.** Each list card now contains its add form on top
  (separated by a dashed border) — no more separate "Add X" cards. Products and Services
  tabs use the same collapsible pattern (Products list, Add Product, Add Service Photo,
  and each service-photo category card).
- **Item counts on every card title** (e.g. `Flowers (7)`, `Flower Variants (33)`,
  `Fillers (7)`, `Ribbon Variants (14)`).
- **Pagination (20/page)** on every Customization list via query params: `cfpage`
  (flowers), `fvpage` (flower variants), `fpage` (fillers), `cpage` (wrapper colors),
  `rpage` (ribbons), `rvpage` (ribbon variants), `spage` (styles). Flower/ribbon
  variant tables are now flat (parent name in a column) instead of nested per parent.
- **Category slug removed from the admin UI.** The Add Category form only asks for a
  Display Name; the slug is auto-derived via `slugifyFlowerName()` and the Slug column
  was dropped from the table. Slugs are still stored/used for URLs + filtering.
- **"Page won't reset" behavior** (important — see gotcha below):
  - `DashboardController::handlePostActions()` now redirects back to the referer URL's
    query string, so after any add/edit/delete you keep your tab, filters, and
    pagination page (e.g. stays on orders page 2). Query is the ONLY part of the
    referer reused (no open-redirect risk).
  - Browser-side state restore: before any form submit, `saveDashboardState()` stores
    the scroll position + open accordion card (panel id + card index) in sessionStorage
    (`hs_scroll`, `hs_open_card`); `restoreDashboardState()` re-applies them after the
    reload. Hooked on a capture-phase `submit` listener + explicit calls before the
    inline-edit hidden forms' `.submit()` and the order-status `onchange` submit.

## Latest work (2026-08-15)

### Payment flow rework: 50% GCash down payment for BOTH methods

- COD is no longer "pay everything on delivery" — **both COD and GCash orders now
  require a 50% down payment via GCash** before the order is confirmed.
- Checkout always redirects to `/orders/{id}/gcash` (no direct `orders.show` after order).
- Every order stores `down_payment` + `remaining_balance` (was GCash-only before).
- GCash screenshot is now **required** (server-side validation `screenshot => required,
  image, max:5120` + required file input in `orders/gcash.blade.php`).
- Payment status flow: `pending_downpayment` (Unpaid) → `partial` (Deposit Paid, after
  admin verifies the GCash payment) → `completed` (Fully Paid, admin marks paid on
  delivery). `pending_cod` is legacy and only still handled for display.
- Order show page (`orders/show.blade.php`): friendly status labels
  (Unpaid / Deposit Paid / Fully Paid / COD), a payment summary box showing the
  deposit + balance-due-on-delivery, and the "Pay Down Payment"/"Submit GCash
  Reference" buttons now show for COD orders too.
- Admin orders table: payment badges for all statuses (`Unpaid`, `Deposit Paid`,
  `Fully Paid`, `COD`) and the "Mark paid" button now shows for every non-completed
  order (not just GCash).

### Admin dashboard nav (styling)

- Sidebar nav is now a **single solid sage green (`#8a9b6e`)** on every menu — it was
  briefly a pink→green gradient (uncommitted) which looked like it changed color per
  menu as it scrolled. The sidebar is also **sticky again** (`position: sticky;
  top: 62px`) so it does not scroll away with the content.
- The collapsible/accordion card behavior introduced 2026-08-13 was removed — cards
  are always expanded.

### Reports fix

- **Daily and Weekly reports were 500-ing.** Cause: `loadReports()` set `$trend = []`
  (plain array) for daily/weekly, but the blade called `$trend->isNotEmpty()` (a
  Collection method) → `Call to a member function isNotEmpty() on array`. Fixed by
  initializing `$trend = collect();` (`DashboardController.php`).

### Timezone

- App timezone changed **UTC → Asia/Manila** (`config/app.php`). Report "Generated:"
  timestamps (and all app times) now show Philippine local time instead of being 8
  hours behind. `created_at` values already stored in the DB are still UTC-based.

## Known issues / gotchas

- **Do NOT re-introduce the full-AJAX dashboard.** A fetch-based form interceptor that
  swapped `.admin-body` HTML (`loadDashboard`/`bindAjaxForms`) was tried and REVERTED —
  it broke saves in the browser (page reset + no save, likely CSRF/fetch issues). The
  current design is standard full-page form submissions + the state-preservation above,
  which is reliable. If someone wants true no-reload later, test carefully in-browser.
- **Session-based scroll/card restore uses sessionStorage** — it only survives one
  reload, so a browser hard-refresh goes back to collapsed/default. That's intentional.
- **419 on login**: session/cookie staleness. Hard-refresh or clear cookies if it recurs.
  SESSION_LIFETIME=120 in `.env`.
- **v2 DB lost filler photos/prices** (fillers are ₱0 with no image). Decided to keep as-is;
  can restore from `main`'s old dump if needed. Image files are still on disk.
- There were **5 orphan images** in `public/images/` (`flower_1786528xxx_*.jpg`) not
  referenced by any DB row — test uploads. They were deleted (kept untracked, not committed).
- Test user / admin accounts exist in DB for development (e.g. `test@example.com`).

## Data model notes (customization)

- `customization_options`: `type` in (flower, color, style, addon, filler, **ribbon**).
  Wrapper colors are `type = 'color'`. Has `image_url` + `hex_color` (image wins over hex).
- `customization_option_variants`: `variant_type` in (size, color). Flowers and ribbons
  use these. `image_url` = pattern/size image; `hex_color` = solid swatch color.
- Flowers: per-stem price on the option; size/color variants can override price (>0 replaces base).
  The `name` column is a slug derived from `display_name` — treat it as internal, not user-facing.
  Same auto-slug rule applies to colors, ribbons, styles, and fillers on add/edit.
- Ribbons: price = size variant price if >0, else color variant price, else 0.
