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

## Known issues / gotchas

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
- Ribbons: price = size variant price if >0, else color variant price, else 0.
