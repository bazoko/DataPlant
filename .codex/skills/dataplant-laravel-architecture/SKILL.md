---
name: dataplant-laravel-architecture
description: Use when building or modifying the DataPlant Laravel application, including project setup, MySQL configuration, MVC modules, authentication, roles, permissions, migrations, seeders, routes, Blade views, tests, and Git-ready structure.
metadata:
  short-description: Laravel architecture for DataPlant
---

# DataPlant Laravel Architecture

Use this skill when working on the DataPlant codebase.

## Current Direction

DataPlant is being rebuilt from scratch in the repository root. The old implementation is in `ejemplo/` and should be treated as reference only. Do not copy old code blindly.

The new app should use:

- Laravel MVC.
- MySQL as the primary database.
- Professional role/permission handling, preferably `spatie/laravel-permission` after validation.
- Blade views unless the user explicitly asks for another frontend stack.
- Tests for permissions and core workflows.
- Documentation that helps explain the project in school/job interviews.

## Architecture Rules

- Keep `areas` as the main operational grouping. Do not add `lineas` or `sectores` unless a concrete requirement appears.
- Model measurement frequency explicitly instead of forcing all records into shifts.
- Keep LILA out of the first implementation unless explicitly requested.
- Prefer small modules with clear ownership: Area, MeasurementVariable, Measurement, Dashboard, Audit, User/Role/Permission.
- Preserve GitHub portfolio quality: clear README, clean commits, no `.env`, no vendor/node_modules, meaningful seed data.

## Authentication And Permissions

- Do not add Laravel Breeze unless the user decides to rebuild authentication around it.
- For professional permissions, validate and then use Spatie Laravel Permission.
- Avoid keeping a `role` field on `users` if Spatie is implemented, because it can conflict with Spatie role relations.
- Prefer roles such as `admin`, `supervisor`, `operario`, and `consulta`.
- Permissions should be understandable and demonstrable, such as `areas.view`, `areas.manage`, `measurements.create`, `measurements.view`, `dashboard.view`, `users.manage`, `roles.manage`.

## MySQL Defaults

Configure the project for MySQL by default in `.env.example`, with placeholders such as:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dataplant
DB_USERNAME=root
DB_PASSWORD=
```

Do not commit real credentials.

## Verification

For code changes, run the most relevant checks available:

- `php artisan test`
- `php artisan route:list`
- Import checks when appropriate

If dependencies are missing or MySQL is unavailable, explain exactly what remains unverified.
