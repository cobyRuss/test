# HappyStem by Carmencita

A flower shop web application (Laravel) ported from the original PHP site.

## Requirements

- PHP 8.2+
- Composer
- MySQL / MariaDB (XAMPP recommended)

## Setup

1. Copy `.env.example` to `.env` and set your database credentials (DB `happystem_db`).
2. Install dependencies:

   ```
   composer install
   ```

3. Run migrations and seeders:

   ```
   php artisan migrate --seed
   ```

4. Start the dev server:

   ```
   php artisan serve
   ```

Open `http://127.0.0.1:8000` (customer side) or `http://127.0.0.1:8000/admin/login` (admin).

## Default Admin

- Username: `admin`
- Password: `admin123`
