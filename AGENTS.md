# DataPlant Project Guidance

## Direction

This repository is the fresh DataPlant rebuild. The previous Laravel prototype lives in `ejemplo/` and is reference material only.

DataPlant is an industrial measurement capture and dashboard system for a factory context similar to Softys. It should be credible for school evaluation and polished enough for GitHub/CV portfolio use.

## Technical Baseline

- Use Laravel MVC.
- Use MySQL as the primary development database.
- Do not use SQLite as the project default, though tests may use SQLite in memory.
- Keep old files under `ejemplo/` untouched unless the user explicitly asks to inspect or migrate something.
- Use professional roles/permissions with `spatie/laravel-permission`.
- Do not add Laravel Breeze unless the user explicitly decides to rebuild authentication around it.

## Domain Rules

- Use `areas` as the main factory grouping.
- Do not split areas into lines/sectors by default.
- Support flexible measurement frequency: daily, per shift, hourly, and custom.
- Shifts are `manana`, `tarde`, and `noche`, but shift must remain optional.
- First real industrial module: Efluentes/PTAR.
- LILA and complex inspection matrices are future scope unless explicitly requested.

## Quality Bar

- Build in small, defendable vertical slices.
- Keep README and docs useful for GitHub portfolio presentation.
- Use fictitious seed data only.
- Run `php artisan test` when code changes.
- Explain any verification that could not run because MySQL or credentials are not ready.
