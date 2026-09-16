---
name: dataplant-github-portfolio
description: Use when preparing DataPlant for GitHub portfolio presentation, repository hygiene, README quality, commit structure, screenshots, demo instructions, CV-facing descriptions, or safe publishing.
metadata:
  short-description: GitHub portfolio readiness for DataPlant
---

# DataPlant GitHub Portfolio

Use this skill when preparing the project to be saved or presented on GitHub.

## Goals

The repository should look useful for school submissions, job applications, and CV links.

A portfolio-ready DataPlant repo should have:

- Clear README with purpose, stack, features, screenshots, setup, and demo credentials.
- No committed `.env`, vendor, node_modules, database dumps, or real credentials.
- Meaningful commit messages.
- MySQL setup instructions.
- Seed data that is fictitious and safe to publish.
- Explanation of the industrial context without exposing private company data.

## GitHub Account Note

The user plans to create a new GitHub account for repositories useful for work/CV. Treat GitHub publishing as separate until explicitly requested.

## Repository Hygiene

Before publishing, check:

- `.gitignore` excludes sensitive/generated files.
- `.env.example` is complete and safe.
- `composer install`, `npm install`, migrations, seeders, and tests are documented.
- README explains why DataPlant matters beyond being CRUD.

## Positioning Text

Describe DataPlant as an industrial data capture and analytics web app built with Laravel and MySQL, focused on replacing spreadsheet-based operational records with structured data, permissions, audit history, and dashboards.
