# DataPlant project guidance

## Product direction

This repository is evolving from a generic Laravel forms app into DataPlant: an industrial data capture and dashboard system for a factory context similar to Softys.

The core product is:

- Users work by role, area, shift, and assigned forms.
- Operators or shift owners load operational measurements from factory areas.
- Measurements are stored as structured data, not isolated spreadsheets.
- Supervisors and administrators review history, audit activity, permissions, and charts.
- The system should look credible for an academic project and polished enough for a professional demo.

## Domain rules

- Use the name DataPlant for new product-facing documentation unless the user asks otherwise.
- Treat `areas` as the main organizational unit. Do not split the model into `lineas` and `sectores` by default; introduce sub-areas, machines, or assets only when a concrete workflow needs them.
- Shifts are `manana`, `tarde`, and `noche`, but not every measurement is shift-based. Some measurements may be hourly, daily, per shift, or custom frequency.
- Model measurement frequency explicitly instead of forcing every record into one pattern.
- Efluentes/PTAR is the best first real industrial area to model, because prior project context already includes KPI and dashboard logic for it.
- LILA and related inspection logic are future scope unless the user explicitly asks to work on them.

## Laravel approach

- Preserve Laravel MVC patterns already present in the repo.
- Keep changes scoped and understandable for a school defense.
- Prefer migrations, Eloquent models, controllers, Blade views, and tests that match the current project style.
- Do not introduce Laravel Breeze into the existing app unless the user explicitly decides to rebuild authentication.
- For professional roles and permissions, prefer validating `spatie/laravel-permission` before implementation. Current caveat: the app has a `users.role` column, and Spatie recommends avoiding a `role` field/property that can conflict with its role relations.
- Audit important actions: create, update, delete, permission changes, and administrative changes.

## UX principles

- Build an operational interface, not a marketing landing page.
- Prioritize fast data entry, readable tables, filters, and clear dashboard views.
- Forms should support factory use: date, time, shift when relevant, area, responsible user, variable, value, unit, observations, and validation state.
- Dashboards should answer operational questions: trends, last value, averages, out-of-range values, comparisons by area/frequency, and data completeness.

## Verification

- Before changing behavior, inspect the existing route, controller, model, migration, view, and test patterns.
- After code changes, run `php artisan test` when feasible.
- When changing routes or permissions, also run or inspect `php artisan route:list`.
- Keep documentation in `docs/` updated when the product model or workflow changes.
