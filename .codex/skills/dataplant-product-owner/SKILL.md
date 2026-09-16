---
name: dataplant-product-owner
description: Use when defining DataPlant product scope, roadmap, academic defense, professional positioning, feature priorities, or what belongs in the current/future/out-of-scope product.
metadata:
  short-description: Product direction for DataPlant
---

# DataPlant Product Owner

Use this skill to keep DataPlant coherent as a product, not merely a set of Laravel screens.

## Product North Star

DataPlant is an industrial data capture and dashboard system for a factory context similar to Softys. It should help shift owners, operators, supervisors, and administrators replace scattered spreadsheets with structured operational data, auditable workflows, permissions, and useful charts.

The project must be credible for school evaluation and polished enough to show as portfolio work for jobs.

## Decisions Already Made

- Product name: DataPlant.
- Start from scratch with the new approach.
- Database: MySQL.
- Old project lives under `ejemplo/` and is reference material only.
- Do not divide the model into lines and sectors by default.
- Use `areas` as the main factory grouping.
- Shifts are `manana`, `tarde`, and `noche`, but not every variable is shift-based.
- Measurement frequency must support daily, per-shift, hourly, and custom schedules.
- First real module should be Efluentes/PTAR.
- LILA and related inspections are future scope unless explicitly requested.
- Prefer a professional roles/permissions package after validation.
- GitHub portfolio quality matters, but repository publishing is a separate task until requested.

## How To Decide Scope

When a requested feature is ambiguous, classify it as:

- Current core: users, roles/permissions, areas, forms, variables, measurements, audit, dashboards.
- Near future: limits, alerts, richer exports, Power BI integration, validation workflows.
- Future scope: LILA, complex inspection matrices, mobile/PWA, notifications, enterprise integrations.
- Out of scope unless requested: recreating all previous Excel/Power BI logic at once.

Prefer thin vertical slices that can be demonstrated end to end: define area, define variables, load measurements, view table, view chart, audit actions.

## Communication Style

When explaining DataPlant, describe the industrial problem, the workflow, the data captured, and the decision it enables. Avoid presenting it as a generic CRUD app.
