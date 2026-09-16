---
name: dataplant-data-modeling
description: Use when designing DataPlant database tables, relationships, measurement frequency, areas, variables, permissions, audit fields, MySQL schema, seed data, and future-proof industrial data structures.
metadata:
  short-description: Data model for industrial measurements
---

# DataPlant Data Modeling

Use this skill when shaping the database or domain model.

## Core Model

DataPlant stores industrial operational measurements. The model should answer:

- What area does this data belong to?
- What variable was measured?
- Who measured it?
- When was it measured?
- What frequency/schedule does the variable use?
- What value and unit were recorded?
- Is the value within expected limits?
- Can the data be charted and audited later?

## Recommended Initial Entities

- `areas`: factory areas such as Efluentes/PTAR, Caldera, MP4, TPM, ORP, Center Line.
- `measurement_frequencies`: daily, per shift, hourly, custom.
- `measurement_variables`: variable name, area, unit, frequency, optional limits, active flag.
- `measurements`: variable, user, measured date/time, optional shift, value, observation, source/status metadata.
- `activity_logs` or audit model: important user/admin/system actions.
- Spatie permission tables if roles/permissions are implemented.

## Modeling Rules

- Do not split area into line and sector by default.
- Keep shift optional; hourly and daily variables should not require fake shift values.
- Store numeric values as decimals with enough precision for industrial indicators.
- Store units on variables, not only on measurements, unless units can vary by record.
- Use seed data that is fictitious but industrially realistic.
- Keep LILA and inspection matrices as future scope.

## First Real Area: Efluentes/PTAR

Good first variables include:

- Caudal entrada arroyo.
- Salida Parshall.
- DQO.
- SST.
- Oxigeno reactor.
- Relacion F/M.
- Edad del lodo.
- Humedad de lodos.
- M3/Ton diario.
- M3/Ton acumulado.

Start with a manageable subset before implementing every historical KPI.
