# AGENTS.md

## Project

Laravel 13 (PHP 8.3) backend — hotel room types & rate pricing. No API routes yet; only `web.php` with a welcome view.

## Commands

```bash
composer setup          # full setup: install, env, key, migrate, npm install, npm run build
composer test           # clears config cache + runs php artisan test
php artisan dev         # dev server (no file watcher timeout)
npm run build           # vite build
```

Single test: `php artisan test --filter=TestClassName`

Linting: `vendor/bin/pint` (Laravel Pint — PHP-CS-Fixer wrapper, no custom config).

## Key gotcha

- `.env` uses **MySQL** (`DB_CONNECTION=mysql`), but `phpunit.xml` forces **SQLite in-memory** for tests. Do not assume MySQL is required to run tests.
- `composer test` clears config cache before running — this is intentional. Don't skip it.

## Code style

- 4-space indent, LF line endings (`.editorconfig`).
- Pint for PHP formatting; no PHPStan or Larastan installed.
- Tailwind CSS v4 via `@tailwindcss/vite`; Vite 8 with `laravel-vite-plugin`.
- No API route file exists. Controllers (`RoomTypeController`, `RateController`) are stubs.

## Domain

- `RoomType` has many `Rate`s (FK: `room_type_id`, cascade delete).
- `Rate` has `price` (decimal 10,2) and `valid_from` (date).
- Both models use `$fillable`; no casts or accessors defined yet.
