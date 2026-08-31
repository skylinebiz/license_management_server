# License Management Server

A Laravel-based license management system with a JSON API for host-based license lookups and an admin web UI for managing licenses.

## Features

- **Licenses table** — license ID, expiry, status, max active users, max attachment size (MB), and host.
- **Auto-generated License IDs** — format `LIC-XXXXX-XXXXX-XXXXX` (Crockford Base32 alphabet, no ambiguous characters), assigned server-side and guaranteed unique. Never user-editable.
- **Public API** — `GET /api/license/{host}` returns license data for a given host.
- **Admin panel** (session-based login, no public self-registration):
  - Add / edit / delete licenses (delete asks for confirmation first).
  - Search by license ID, host, or status.
  - Sort by host, expiry, or status (click a column header; click again to reverse).
  - Pagination (15 per page).
  - Standard Laravel Breeze account management: update password, forgot/reset password by email.

## Requirements

- PHP 8.3+
- Composer
- Node.js + npm
- MySQL

## Setup

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate
```

Create the database, then run migrations and seed the default super admin:

```bash
php artisan migrate --seed
npm run build
php artisan serve
```

There's no public sign-up form — the `AdminUserSeeder` (run above via `--seed`, or on its own with `php artisan db:seed`) creates a default super admin account:

| Email | Password |
|---|---|
| `admin@license-management.local` | `ChangeMe123!` |

It's idempotent — safe to re-run `php artisan db:seed` any time; it only creates the account if that email doesn't already exist, so it won't overwrite a password you've since changed.

Log in at `/login` with the credentials above and **change that password immediately** from `/profile`.

## API

### `GET /api/license/{host}`

Returns the license record for the given host.

```
GET /api/license/example.com
```

**200 OK**
```json
{
  "id": 1,
  "license_id": "LIC-337A3-49TJJ-WYGT4",
  "license_expiry": "2027-03-10T00:00:00.000000Z",
  "license_status": "active",
  "max_active_user": 10,
  "max_attachment_size_mb": 25,
  "host": "example.com",
  "created_at": "...",
  "updated_at": "..."
}
```

**404 Not Found** if no license matches the host:
```json
{ "message": "License not found for the given host." }
```

## Admin panel routes

| Route | Description |
|---|---|
| `GET /login` | Admin login |
| `GET /admin/licenses` | List, search, sort, paginate licenses |
| `GET /admin/licenses/create` | Add a license |
| `GET /admin/licenses/{id}/edit` | Edit a license |
| `DELETE /admin/licenses/{id}` | Delete a license (confirmation required in the UI) |
| `GET /profile` | Update profile / password |
| `GET /forgot-password` | Request a password reset email |

## Notes

- Password reset emails use whatever `MAIL_MAILER` is configured in `.env`. In local development this defaults to `log`, meaning reset links are written to `storage/logs/laravel.log` instead of actually being emailed — configure real SMTP credentials before relying on this in anything beyond local dev.
- If you add new Tailwind utility classes to a Blade view, run `npm run build` again — Tailwind only compiles classes it can see in scanned files at build time.

See [CHANGELOG.md](CHANGELOG.md) for the history of this project.
