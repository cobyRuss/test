# HappyStem — Project Notes (handoff doc)

Live context for whoever works on this project next. Written 2026-08-12.

## What the app is

HappyStem by Carmencita — a flower shop web app. A Laravel port of the original
plain-PHP site (still at `C:\xampp\htdocs\happystem`, v1). This repo is the
current Laravel app ("main" branch).

Two customer-facing experiences:
- **Shop**: browse products, add to cart, checkout (GCash, 100% paid upfront with screenshot verification).
- **Customize**: build a custom bouquet — pick flowers (+ size/color variants, **per-color
  quantities**), fillers, wrapper color, **ribbon**, and arrangement style. Custom design
  is stored as a structured `items` array in the cart session (`custom_arrangement`) plus a
  text `description`, and the breakdown is persisted to `order_items.description`.

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
- **Flower stock → product availability cascade.** ~~`customization_options.stock_quantity`
  (default 100; 0 = out of stock) drives product availability through a new `flower_product`
  pivot (`product_id` + `flower_id`, FK cascade).~~ **REPLACED 2026-08-15 — see "Stock rework"
  below.** Availability is now active-based (a flower is available iff `is_active`; a product
  is available iff it and all its linked flowers are active). UI: storefront cards/detail pages get an
  "Unavailable" overlay and disabled buttons; category filter buttons show "(Unavailable)" when a whole
  category is unavailable; customize page hides inactive flowers/fillers and never loads inactive
  variants; cart add endpoint
  rejects unavailable products. Admin: product form has "Flowers used" multi-select +
  Active toggle, edit modal populates them, products table shows an Availability badge.
- Fixed/checked intermittent **419 "Page Expired"** on login (CSRF): verified sessions
  table exists; was a stale-session/cookie issue, not a bug.
- **Admin flowers table cleaned up** (Customization tab):
  - No more slug/`name` column or slug input field. Slugs are auto-derived from the
    Display Name server-side in `DashboardController::slugifyFlowerName()` ("Local Roses"
    -> `local_roses`; empty -> `flower_<timestamp>`), so the internal `name` column still
    gets populated but is never shown or typed by hand.
  - Inline editing: click display name / name / price / sort right in the table;
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

> **Update 2026-08-15 (later):** the collapsible/accordion cards below were **replaced by
> modals** — see "Modal-based admin dashboard" under Latest work. Each section is now a
> `.section-card` button opening a `.edit-modal.section-modal`; pagination params and
> page-state restore still apply (state now saves the open **modal**, not a card).

- **Collapsible accordion cards.** The Customization tab's cards (Flowers, Flower
  Variants, Fillers, Wrapper Colors, Ribbons, Ribbon Variants, Styles) are now
  click-to-expand/collapse dropdowns. Only ONE card can be open at a time (accordion),
  and clicking an open card closes it. Most cards start collapsed on load (a couple, e.g.
  Add New Product and Flowers, start open).
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
    **Fixed 2026-08-15:** the open card is captured within the **active tab panel only**
    (was picking the first open card globally — e.g. "Add New Product" in the hidden
    Products tab — so after a save from Flower Variants the wrong card was reopened).
    Now scroll + tab + open card all persist after a save (verified in Edge via Playwright).

## Latest work

### Admin order details modal (2026-08-16)

- **Admin stays in the admin side now.** Clicking an order number in the admin Orders tab
  opens an **`#orderDetailsModal`** on the dashboard instead of leaving for the customer-side
  `/orders/{id}` page. It fetches `GET /admin/orders/{id}/details`
  (`DashboardController::orderDetails`, route `admin.orders.details`, under `auth:admin`)
  which returns the partial `resources/views/admin/order_details.blade.php` and injects it
  into the modal (`openOrderDetails()`).
- The modal shows: items + custom-arrangement breakdown, totals, payment (method/status,
  GCash ref + screenshot thumbnail that opens the existing `#gcashLightbox`), sender
  (name/email/phone + Anonymous badge), recipient & delivery, message for recipient, and
  special instructions. Uses the LATEST gcash_payments row (`orderByDesc('id')`) to match
  the dashboard's screenshot thumbnail logic.
- The customer-side `orders.show` page is still used by customers (their order details +
  pay/cancel actions); admins no longer need it.

### Checkout redesign: sender/recipient info + messages (2026-08-16)

- **Registration simplified** (2026-08-16): register now collects only first name / last
  name / email / password / confirm password. New migration
  `2026_08_16_000001_add_first_last_name_to_customers_table.php` added `customers.first_name`
  / `last_name` (backfilled from `full_name` for existing rows) and made `phone`/`address`
  nullable. `AuthController::register` now saves `first_name` + `last_name` and derives
  `full_name = "First Last"`; phone/municipality/address are left null and filled at
  checkout. Register page (`resources/views/auth/register.blade.php`) is a FlowerStore-style
  split-screen (brand panel + form card) using HappyStem's logo (`images/qqq.png`) and colors.
- **Checkout is now FlowerStore-style** (imitated from flowerstore.ph/home/information):
  - **Sender Information**: email (read-only autofill), first/last name (autofilled inputs),
    and phone with a 🇵🇭 +63 prefix — a 10-digit number (capped at 10, digits-only, JS
    strips `+63…`/`09…` prefixes on paste). Stored as local format `0`+10 digits
    (e.g. `09171234567`).
  - **Recipient toggle** (two side-by-side buttons): "I'll receive the order" (default,
    recipient = sender) vs "Someone else will receive it" (reveals recipient first/last
    name + phone, also +63/10-digit). Address (municipality select, barangay, street) is
    always shown since a delivery address is always needed; `delivery_date` stays.
  - **Message & Instructions**: "Optional: Message for recipient" (max 400 chars with a
    live `0/400` counter; placeholder reminds the sender to say who they are) and "Optional:
    Special instructions for the rider/merchant" with the disclaimer "we cannot promise any
    exact hour for your delivery" and an example placeholder.
  - **Sender Anonymous** checkbox ("Yes, I want to make the sender anonymous.").
  - Button is now **"Go to Payment"** (single-page checkout still redirects to
    `/orders/{id}/gcash` after placing).
  - `CheckoutController::store` validates `sender_phone`/`recipient_phone` with
    `/^9\d{9}$/`, `recipient_mode in:me,someone_else`, and `message_for_recipient max:400`.
- **New `orders` columns** (migration `2026_08_16_000002_add_checkout_details_to_orders_table.php`):
  `sender_phone`, `recipient_name`, `recipient_phone`, `recipient_barangay`,
  `recipient_street`, `message_for_recipient`, `sender_anonymous`. `delivery_address` is
  built as `street, barangay, municipality, Abra`; `special_instructions` is reused for the
  rider/merchant instructions.
- **Order details (`orders/show.blade.php`) now show Sender, Recipient & Delivery, Message
  for recipient, Special instructions, and an Anonymous badge** — visible to the customer
  AND the admin. Also fixed a pre-existing bug: admins got **404** on `/orders/{id}` because
  `OrderController::findOrder()` scoped orders to the web guard's `customer_id`; it now skips
  the customer scoping when `Auth::guard('admin')->check()`.
- Verified end-to-end via curl for both recipient modes (someone_else with message/anonymous
  flag, and me with no message), order details render for customer and admin, test data
  cleaned up. `database.sql` regenerated.

### Payment flow: GCash only, 100% upfront (2026-08-16)

- **COD removed entirely.** Checkout now has only the GCash option; `CheckoutController`
  validates `payment_method => in:gcash` and stores `down_payment` = full total,
  `remaining_balance` = 0. (The DB enums `payment_method('gcash','cod')` and
  `payment_status('pending_downpayment','partial','completed','pending_cod')` are UNCHANGED —
  `cod`/`pending_cod`/`partial` values still exist for legacy orders.)
- **GCash details are now configurable:** new `config/happystem.php` (`gcash_number`,
  `gcash_account_name`, overridable via `GCASH_NUMBER` / `GCASH_ACCOUNT_NAME` in `.env`).
  Shown on the **checkout page** (so customers know where to pay before ordering) and the
  GCash payment page. Current values: `09353505610` / `Ross Terence L. Marzo`.
- **Payment status flow:** `pending_downpayment` (Unpaid) → `partial` (Payment Submitted,
  awaiting verification) → `completed` (Paid). Admin **Verify** (`verify_gcash`) now sets
  `payment_status = 'completed'` directly (was `'partial'`); `GcashPaymentController::store`
  records `payment_type = 'full_payment'` (was `'down_payment'`).
- **Labels updated everywhere:** admin orders badges and customer order page now show
  Unpaid / Payment Submitted / Paid (no more "Deposit Paid / Fully Paid / 50%"). Cancel
  pages say "GCash payments are refunded" instead of "down payments".
- **Admin views the GCash screenshot inline** — no more leaving for the customer-side order
  page. Added `#gcashLightbox` (read-only modal + "Open in new tab") to the admin dashboard;
  the Payments tab shows a 48px thumbnail per pending payment and the Orders tab Payment
  column shows a 36px thumbnail when a screenshot exists (`loadOrders()` gained a
  `gcash_screenshot` subquery). Click a thumbnail → `openGcashLightbox()` stays on admin.
- Verified end-to-end via curl (customer login → cart → checkout → place order →
  submit screenshot → admin verify), test order cleaned up afterward.

## Latest work (2026-08-15)

### Labels + admin sidebar tweaks

- **Customize labels:** flower/filler price labels changed from `/stem` to **`/bouquet`**
  (3 spots in `resources/views/customize/index.blade.php`: flower price line, filler price
  line, and the JS `updateFlowerPrice()` suffix). The live stem counter still says
  "N stems"; `total_stems` unchanged.
- **Admin green sidebar now stretches to the bottom of the page:** `align-self: stretch`
  on `.admin-nav` in `resources/views/admin/dashboard.blade.php` (was `flex-start`, which
  left white space below the green box on short content). Still sticky + capped at
  `100vh - 62px` with internal scroll on long tabs (verified via Playwright).
- Flower-color photos for the per-color swatches were uploaded via the admin; the 9 new
  `public/images/flower_*.{jpg,png}` files are referenced by
  `customization_option_variants.image_url` / `customization_options.image_url` (verified)
  so they are committed, and `database.sql` was regenerated to capture the new references.

### Custom bouquet: per-color quantities + image swatches (bug fix)

- **Bug fixed:** quantities were tracked per flower with a single shared color, so picking
  colors merged counts (3 red → switch to white showed 8 white instead of 3 red + 5 white).
- **New model:** each flower's **color variant has its own quantity stepper** (image swatch
  with a `+`/`−` stepper under it). Multiple colors of one flower are separate line items
  in the summary + thumbs + totals, e.g. `Local Roses (Red) × 3 — ₱225` and
  `Local Roses (White) × 5 — ₱375`, stem counter `8 stems`, total `₱680`.
- **Image swatches only:** hex fallback removed for flower color options — always the
  variant photo (`image_url`); flowers without any image show a leaf placeholder. The
  user will upload photos for every color (no hex dot under the name).
- Flowers WITHOUT colors (e.g. Sunflowers, size-only) keep the single flower-level stepper.
- **Structured data model:** `cart.addCustom` now accepts an `items` JSON array and stores
  it in the session as `custom_arrangement.items` = `[{flower, color, size, qty}]` plus
  `total_stems`. `normalizeCustomItems()` in `CartController` sanitizes it (int qty ≥ 1,
  skips invalid rows). The `description` text is still built client-side (`summaryText()`)
  for display.
- **Orders persist the breakdown:** new nullable `order_items.description` column
  (migration `2026_08_15_000001_add_description_to_order_items_table.php`, already in
  `database.sql`). Checkout stores it for custom items; orders/show, cancel, cancel-success
  render it under the item name.
- Verified end-to-end with Playwright + Edge: built 3 red + 5 white roses + Red wrapper,
  added to cart (description + ₱680), placed a COD order, confirmed `order_items.description`
  and the order page show both lines. Test data cleaned up afterward.
- **Gotchas hit during testing (fixed/noted):** a stale `artisan serve` process can serve a
  `production` env with no app key (restart it); test bcrypt hashes must use cost 12
  (app default) or Laravel's RehashPassword 500s on login (it writes to a `password`
  column that doesn't exist — real users use cost 12 so they're unaffected).

### Modal-based admin dashboard

- **Only Products, Services, and Customization use modals (2026-08-15).** The collapsible
  accordion cards were replaced with `.section-card` buttons that open a
  `.edit-modal.section-modal` (`.edit-modal-box.wide` + `.modal-head` with a
  `.modal-close` X). 16 modals total: Products = 2 (Add Product, Products list),
  Services = 7 (Add Service Photo + one per service category), Customization = 7
  (Flowers, Flower Variants, Fillers, Wrapper Colors, Ribbons, Ribbon Variants, Styles).
- **Categories, Orders, Messages, and Reports are plain cards** (like the Payments tab) —
  the user decided those didn't need modals. Categories = Add New Category + Product
  Categories cards; Orders = filter + badges + table + pagination card; Messages = search
  + message list + pagination card; Reports = generate-form card + `#printArea`
  (stat cards, Top Products, Sales by Municipality, Sales Trend) + print button.
- **Modals live inside their tab panel** (`<div class="tab-panel">`), so they only show
  when that tab is active. They open via `data-modal` on the section-card, close via the
  X (`.modal-close`) or the backdrop click. Accordion CSS/JS removed.
- **Sticky save tracks the open modal:** `saveDashboardState(modalId)` /
  `restoreDashboardState()` use sessionStorage `hs_scroll` + `hs_open_modal` +
  `hs_modal_scroll` (modal box scroll). The capture submit listener grabs the form's
  enclosing `.section-modal`; the inline-edit hidden-form saves pass the row's modal id.
  `editProductModal`, `editServicePhotoModal`, and `photoLightbox` are NOT `.section-modal`
  so they never reopen stale after a reload. Verified in Edge via Playwright (inline-edit
  save from Flower Variants reopens `modalFlowerVariants` + restores scroll/tab).
- **Sticky pagination:** every `.pagination a` link calls `saveDashboardState()` on click,
  so switching page 1 → 2 keeps the modal open (Products/Services/Customization) or just
  the scroll position (Orders/Messages plain cards). Verified in Edge: products page 2
  keeps `modalProducts` open with the active page = 2.
- **Messages got pagination + search:** `loadMessages(Request $request)` now paginates at
  **20/page** (`mpage` param) with a `message_search` LIKE filter over name/email/message;
  the Messages card has a search form and renders pagination only when >1 page.
- **Orders per page bumped 15 → 20** (`$ordersPerPage = 20` in `loadOrders()`).

### Payment flow rework: 50% GCash down payment for BOTH methods

> **Superseded 2026-08-16** — see "Payment flow: GCash only, 100% upfront" under Latest
> work. COD is gone and payments are 100% upfront. History kept for reference.

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
- The collapsible/accordion card behavior introduced 2026-08-13 was removed, then
  **restored** the same day (user request) — cards are click-to-collapse accordions again.

### Reports fix

- **Daily and Weekly reports were 500-ing.** Cause: `loadReports()` set `$trend = []`
  (plain array) for daily/weekly, but the blade called `$trend->isNotEmpty()` (a
  Collection method) → `Call to a member function isNotEmpty() on array`. Fixed by
  initializing `$trend = collect();` (`DashboardController.php`).

### Timezone

- App timezone changed **UTC → Asia/Manila** (`config/app.php`). Report "Generated:"
  timestamps (and all app times) now show Philippine local time instead of being 8
  hours behind. `created_at` values already stored in the DB are still UTC-based.

### Stock rework: on/off switches instead of stock counts

- **Removed the numeric stock count.** `customization_options.stock_quantity` (flowers) is
  gone; flower availability is no longer a per-flower count.
- **`in_stock` has been REMOVED again (2026-08-15) — merged into `is_active`.** The user
  decided stock and active are "basically the same": *if active is on, it means in stock*.
  So there is **no separate stock state anywhere**. Migration
  `2026_08_13_000002_add_product_availability.php` no longer creates `in_stock` columns
  (live DB columns dropped via ALTER + `database.sql` regenerated).
- **Availability rules (active-only):**
  - `CustomizationOption::isAvailable()` = `is_active`. A flower or filler is available
    iff it's active. Variants are only ever loaded when active (= in stock).
  - `Product::is_available`: `is_active` AND every linked flower `isAvailable()`.
  - `HomeController` / `ProductController` `categoryAvailability()` SQL: a product counts
    as available unless `products.is_active` is false or a linked flower is inactive.
- **Admin:** one **Active** toggle per item (flowers, variants, ribbons, fillers, colors,
  styles) — no Stock column/select anywhere. The inline-edit save writes only `is_active`.
- **Customize page:** deactivated fillers/flowers are hidden; deactivated variants are not
  loaded at all (no `oos` dimming — everything shown is in stock by definition).
- **Sticky save (2026-08-15):** `saveDashboardState()`/`restoreDashboardState()` were
  fixed so the open accordion card is captured **within the active tab panel only** (was
  capturing the first open card globally, e.g. "Add New Product" in the hidden Products
  tab), and the scroll + card are restored after reload. Verified in Edge via Playwright:
  save from Flower Variants → scroll position, tab, and open card all persist.

## Known issues / gotchas

- **Do NOT re-introduce the full-AJAX dashboard.** A fetch-based form interceptor that
  swapped `.admin-body` HTML (`loadDashboard`/`bindAjaxForms`) was tried and REVERTED —
  it broke saves in the browser (page reset + no save, likely CSRF/fetch issues). The
  current design is standard full-page form submissions + the state-preservation above,
  which is reliable. If someone wants true no-reload later, test carefully in-browser.
- **Session-based scroll/modal restore uses sessionStorage** — it only survives one
  reload, so a browser hard-refresh goes back to the dashboard defaults (no modal open).
  That's intentional.
- **419 "Page Expired"** happens when a stale admin tab submits after the session was
  regenerated — logging in again (admin **or** customer) calls `session()->regenerate()`,
  which mints a new CSRF token and invalidates the tokens in every already-open tab. Common
  in dev because the same browser is used for the customer shop and the admin panel. **Fixed
  2026-08-16:** `bootstrap/app.php` has a `TokenMismatchException` render handler, but it was
  dead code — Laravel converts that exception to `HttpException(419)` in `prepareException()`
  *before* render callbacks run, so a `TokenMismatchException` type-hint never matched. The
  handler now type-hints `HttpException`, checks `getStatusCode() === 419`, and redirects
  back with `session('error')` = "Your session expired — please try again." (dashboard already
  renders `session('error')`). The reloaded page carries a fresh token, so the action works on
  the second click. SESSION_LIFETIME=120 in `.env` (idle >2h also triggers it).
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

## Latest work (2026-08-16): products use flower VARIANTS + pcs, availability switch

- **"Flowers used" on Add/Edit Product now picks flower VARIANTS (color/size) with a pcs
  number, not bare flowers.** Pivot is now `product_flower_variants(product_id, variant_id,
  quantity)` (migration `2026_08_16_000003_create_product_flower_variants_table.php`,
  in `database.sql`). Migration backfilled each old `flower_product` link → first active
  variant of that flower (else first variant) with a random qty 5–30, appended an
  `Includes: Nx Flower (Variant), …` line to existing product descriptions, then dropped
  `flower_product`. `down()` recreates `flower_product` from distinct parent flowers.
- **Product model:** `flowers()` replaced by `flowerVariants()` (`belongsToMany` via the new
  pivot, `withPivot('quantity')`). `Product::is_available` = `is_active` AND every linked
  variant is active AND its parent flower option is active. `CustomizationOption::products()`
  (old `flower_product` usage, unused in the codebase) removed.
- **Controllers updated** (`HomeController`, `ProductController`, `CartController`):
  `with('flowers')` → `with('flowerVariants')`; both `categoryAvailability()` helpers now use
  `whereDoesntHave('flowerVariants', …)` checking variant `is_active` OR parent option
  `is_active`.
- **Admin dashboard (`DashboardController` + `dashboard.blade.php`):**
  - Add/Edit Product modals: the old `flowers[]` multi-select is now a **grouped variant
    picker** (flower → its variants, each with a checkbox + pcs number input; pcs inputs are
    disabled when unchecked). Only variants whose parent option is `type = 'flower'` are shown
    and (controller-side) accepted — a non-flower variant is silently ignored.
  - **Availability is a toggle switch** (reused `.switch`/`.slider`, checkbox `is_active`) in
    both modals — replaces the Yes/No `<select>`.
  - **Auto description:** on Add, if description is left blank the controller generates
    `Includes: Nx Flower (Variant), …` from the submitted variants (blank pcs → random 5–30,
    e.g. "Includes: 18x Gerbera (Yellow), 12x Gerbera (Red)."). Random numbers are the
    intended placeholder — the user will write real descriptions later.
  - `syncProductVariants()` in the controller syncs `variants[id]=1` + `variant_qty[id]`.
  - Edit button now carries `data-variants` JSON (`{variant_id: qty}`) and the edit JS
    restores checked variants + qty + the switch state.
  - Hardened `edit_product` so a blank `image_url`/no new upload **keeps the existing image**
    (previously an empty input hit NOT NULL on `products.image_url` → 500).
- **DB gotcha:** `products.id` is plain `int`, but `customization_option_variants.id` is
  `bigint unsigned` — the pivot's FKs must each match their target column type, otherwise
  MySQL errno 150 (that's why the migration's `product_id` is `integer`, not `unsignedBigInteger`).
- Verified end-to-end against the XAMPP Apache server (port 8000): add product with 2 variants
  (blank qty → random, explicit qty kept) → auto description; edit → uncheck one variant, change
  qty, toggle active off → shop hides it, "Pastel Tulip Elegance" (linked to the *inactive*
  Tulips White variant) correctly renders "Not available at the moment"; test data deleted.
- **Testing note:** curl needs the real session cookie — it's named **`happystem-session`**
  (not `laravel_session`), so grep the cookie jar for that name or the dashboard fetches return
  the login page.
