# FlySeas Travels — Laravel MVP

Premium handcrafted travel itineraries for FlySeas Travels (Nagpur).
Built with **Laravel 12 · Tailwind CSS v4 · MySQL · Livewire 4**.

---

## Quick start (local)

```bash
# 1. Install deps
composer install
npm install

# 2. Configure
cp .env.example .env
php artisan key:generate

# 3. Database
mysql -u root -e "CREATE DATABASE IF NOT EXISTS flyseas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
php artisan migrate --seed

# 4. Build assets
npm run build         # production
# or
npm run dev           # hot reload during dev

# 5. Serve
php artisan serve
# → http://localhost:8000
```

---

## Routes

### Public
| Path | Description |
|------|-------------|
| `/` | Homepage with hero, featured packages, categories, enquiry form |
| `/tours` | All-tours listing with filters and pagination |
| `/tours/{slug}` | Itinerary detail with day-by-day, gallery, sticky enquiry |
| `POST /enquiry` | Submit enquiry from any form |

### Admin (login required)
| Path | Description |
|------|-------------|
| `/admin/login` | Admin sign-in |
| `/admin` | Dashboard (stats + recent enquiries) |
| `/admin/packages` | List, create, edit, delete itineraries |
| `/admin/enquiries` | Enquiry inbox with status updates |
| `POST /admin/logout` | Sign out |

---

## Default admin credentials (local only)

```
Email:    admin@flyseastravels.com
Password: flyseas2026
```

These are seeded by `AdminUserSeeder` from `ADMIN_EMAIL` / `ADMIN_PASSWORD` in `.env`.
**Change the password before deploying anywhere public.**

---

## Project structure

```
app/
  Http/
    Controllers/        Public-site controllers
    Controllers/Admin/  Admin controllers (auth, dashboard, packages, enquiries)
    Middleware/         EnsureUserIsAdmin
  Models/               Package, PackageDay, Enquiry, User
config/flyseas.php      Business contact info (phone, WhatsApp, email)
database/
  migrations/           Schema for packages, package_days, enquiries, users.is_admin
  seeders/              AdminUserSeeder + PackageSeeder
resources/
  css/app.css           Tailwind v4 + brand tokens + all component styles
  views/                Blade views (public + admin)
routes/web.php          All HTTP routes
```

---

## Auth model

- `users.is_admin` boolean column gates admin access
- `EnsureUserIsAdmin` middleware (`admin` alias) checks the flag
- Admin routes wrapped in `['auth', 'admin']` middleware
- Failed `auth` redirects to `admin.login` (configured in `bootstrap/app.php`)

---

## Tech notes

- **Tailwind CSS v4**: theme tokens defined in `resources/css/app.css` via `@theme`.
  Brand styles use vanilla CSS custom properties (`--fs-primary`, etc.) for
  compatibility with the original `brand.css` from the design prototypes.
- **Vite manifest**: must run `npm run build` (or `npm run dev`) before
  `php artisan serve` — otherwise pages throw `ViteManifestNotFoundException`.
- **Database**: configured for MySQL by default. Switch to SQLite by changing
  `DB_CONNECTION=sqlite` in `.env`.

---

## Common commands

```bash
# Reset database with fresh seed data
php artisan migrate:fresh --seed

# Reseed admin user only
php artisan db:seed --class=AdminUserSeeder

# Reseed packages only
php artisan db:seed --class=PackageSeeder

# Rebuild assets after CSS changes
npm run build

# Clear all caches if something behaves oddly
php artisan optimize:clear
```
