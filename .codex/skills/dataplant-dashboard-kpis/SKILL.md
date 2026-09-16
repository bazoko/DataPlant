---
name: dataplant-dashboard-kpis
description: Use when designing or implementing DataPlant dashboards, charts, KPIs, filters, cards, operational summaries, chart data queries, or Laravel views for industrial measurement analytics.
metadata:
  short-description: Dashboards and KPIs for DataPlant
---

# DataPlant Dashboard KPIs

Use this skill when turning DataPlant measurements into charts and operational insight.

## Dashboard Principles

Dashboards must answer operational questions, not decorate the app.

Prioritize:

- Last recorded value.
- Trend over time.
- Average, minimum, and maximum for a selected period.
- Values outside configured limits.
- Measurements loaded vs expected frequency.
- Comparisons by area, variable, shift, responsible user, and date range.

## First Dashboard Scope

For Efluentes/PTAR, start with a simple dashboard that can be explained easily:

- Date range filter.
- Area filter if multiple areas exist.
- Variable selector.
- Line chart for selected variable over time.
- Summary cards for last value, average, min, max, and count.
- Table of latest measurements.

## Implementation Guidance

- Use the database as source of truth.
- Prefer server-side aggregate queries for simple charts.
- Keep chart labels and units clear.
- Avoid requiring Power BI for the first demo; internal Laravel charts make DataPlant stronger as a standalone portfolio project.
- Keep future Power BI export/integration possible through clean data and CSV/API later.

## Future Scope

Later dashboards may include alerts, expected vs actual loading, multi-variable comparison, and richer Efluentes/PTAR KPI pages.
