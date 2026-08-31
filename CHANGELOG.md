# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [0.5.0] - 2026-08-31

### Added
- `AdminUserSeeder` — seeds a default super admin account (`admin@license-management.local` / `ChangeMe123!`) so a fresh install has a way to log in without manual `tinker` commands. Idempotent: only creates the account if it doesn't already exist, so re-running seeders never clobbers a password that's since been changed. Wired into `DatabaseSeeder`, runnable via `php artisan migrate --seed` or `php artisan db:seed`.

### Changed
- README setup flow now seeds the default admin instead of documenting a manual `tinker` snippet.

## [0.4.0] - 2026-08-31

### Added
- Delete action for licenses (`DELETE /admin/licenses/{id}`), with a confirmation prompt before submitting.
- Pencil (Edit) and trash-bin (Delete) icons in the admin license table's Actions column, alongside the existing Suspend/Activate control.

### Fixed
- License status badges (Active/Inactive/Expired/Suspended) not showing distinct colors — the compiled Tailwind CSS had been built before the badge markup existed, so the color utility classes were never included in the bundle. Rebuilt assets to pick them up.

## [0.3.0] - 2026-08-31

### Added
- Auto-generated License IDs: `LIC-XXXXX-XXXXX-XXXXX` using the Crockford Base32 alphabet (excludes ambiguous characters like `0/O`, `1/I/L`, `U`), assigned on creation and guaranteed unique via retry-on-collision. The field is no longer mass-assignable or present on the create form; it's shown read-only on edit.
- Sortable license list: click the Host, Expiry, or Status column headers to sort (`?sort=&direction=`); click again to reverse. Sort state is preserved alongside search and pagination.

## [0.2.0] - 2026-08-31

### Added
- App renamed to `license_management_server`.
- Admin web UI (Laravel Breeze, Blade + Tailwind):
  - License management: add, edit, suspend, and activate licenses.
  - Search across license ID, host, and status.
  - Pagination (15 per page).
  - Standard account management carried over from Breeze: update password, forgot/reset password by email.

## [0.1.0] - 2026-08-31

### Added
- Initial Laravel application scaffold (server/API app).
- `licenses` table/migration: `license_id`, `license_expiry`, `license_status` (active/inactive/expired/suspended), `max_active_user`, `max_attachment_size_mb`, `host`.
- `License` Eloquent model.
- Public API endpoint `GET /api/license/{host}` returning the license record for a host, or a 404 if none exists.