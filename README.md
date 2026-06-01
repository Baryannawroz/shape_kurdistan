# Public website — Laravel + Inertia (Vue 3)

Corporate / portfolio CMS on **Laravel 12** with **Inertia.js** and **Vue 3** (Composition API), **Tailwind CSS v3**, multilingual routes (`en`, `ckb`, `ar`), and an **Inertia admin** under `/admin/cms`.

## Requirements

- PHP 8.3+
- Composer
- Node 20+ (npm)
- MySQL 8+ (configured in `.env`)

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configure `.env` database credentials, then:

```bash
php artisan migrate
php artisan storage:link
npm install
npm run build
```

## Seeding

```bash
php artisan db:seed
```

- **Roles & permissions** — `RolesAndPermissionsSeeder`
- **CMS admin** — `AdminUserSeeder` creates `admin@site.com` / `password` (super-admin)
- **Legacy admin** — `admin@example.com` / `password` (super-admin)
- **CMS settings & demo content** — `CmsSettingsSeeder`, `CmsSampleDataSeeder`
- **Sections** (legacy Blade areas) — still seeded in `DatabaseSeeder`

## Local URLs (Laravel Herd)

- Public site: `https://public_website.test/{locale}` (e.g. `/en`, `/ar`, `/ckb`)
- Admin dashboard: `https://public_website.test/admin/dashboard`
- CMS: `https://public_website.test/admin/cms/...` (pages, services, projects, team, testimonials, messages, site settings)

## Frontend dev

```bash
npm run dev
```

If assets are stale after route or Vue changes:

```bash
php artisan ziggy:generate resources/js/ziggy.js
npm run build
```

## Environment variables (high level)

- `APP_URL` — used by Ziggy for absolute URLs
- `DB_*` — MySQL connection
- `APP_LOCALE` / `APP_FALLBACK_LOCALE` — defaults (`config/app.php` defines `locales` for `en`, `ckb`, `ar`)

## Adding a language

1. Add the locale to `config('app.locales')` in `config/app.php` (code, `name`, `dir`, `flag`).
2. Add `lang/{locale}/` PHP/JSON files as needed (e.g. `lang/de/nav.php`).
3. Regenerate Ziggy after route changes: `php artisan ziggy:generate resources/js/ziggy.js`.
4. Ensure translation rows exist for new content in the CMS (EN/AR/CKB tabs in admin forms).

## Notes

- **Boost `composer` post-update** may error on `boost:update` with Inertia installed; packages still install. Re-run `composer install` if needed.
- Legacy **Blade** admin (sections, posts, roles) remains; new **Vue/Inertia** CMS lives under `admin/cms`.
- **Spatie roles** used for admin access: `super-admin`, `admin`, and `editor` can open the CMS (`AdminMiddleware`).
