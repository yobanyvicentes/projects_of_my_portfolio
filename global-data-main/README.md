# Global Data

Global Data is a Laravel and React analytical dashboard for exploring economic and development indicators from external data providers through a common interface.

## Purpose

Economic indicators are often distributed across different providers with different APIs, terminology and coverage. This project provides a single analytical workspace for browsing provider-specific series and comparing normalized data where live integrations are available.

## Current implementation

The application includes provider pages for:

- World Bank
- IMF
- OECD
- UN Data
- DBnomics
- FRED

At the current snapshot, **World Bank and DBnomics have live provider integrations**. The IMF, OECD, UN Data and FRED sections are interface/provider shells with preview states and planned integration metadata rather than completed live API connections.

The comparison workflow uses the providers that are currently live.

## Features

- overview of supported and planned data sources
- country, indicator and year filters
- live World Bank series retrieval
- live DBnomics series retrieval
- normalized records for cross-source analysis
- provider-specific methodology notices
- reusable loading, empty and error states
- comparison interface for live sources
- chart-based presentation using React components

## Technology stack

- PHP 8.1+
- Laravel 10
- Inertia.js
- React 18
- Recharts
- Vite
- Guzzle / HTTP provider services

## Architecture

The codebase separates page orchestration, provider integrations and normalization logic:

- `app/Http/Controllers/EconomicDashboardPageController.php` — page routing and provider requests
- `app/Services/EconomicDashboard/` — catalog, comparison and provider services
- `app/Services/EconomicDashboard/Providers/` — provider-specific integrations
- `resources/js/` — React/Inertia interface
- `config/economic-dashboard.php` — source, indicator and default-country configuration
- `routes/web.php` — dashboard routes

This structure allows each external provider to be implemented independently while presenting a consistent UI and normalized comparison model.

## Running locally

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
npm run build
php artisan serve
```

For frontend development:

```bash
npm run dev
```

Some provider requests require internet access and are subject to the availability, limits and response formats of the corresponding third-party APIs.

## Interpretation note

Indicators with similar labels are not automatically methodologically equivalent across providers. The application is designed to surface source and methodology differences rather than treating every series as directly interchangeable.

## Project status

This repository is a working analytical prototype with live World Bank and DBnomics integrations plus an extensible provider architecture. Provider pages that still use preview data are intentionally documented as incomplete rather than represented as finished integrations.
