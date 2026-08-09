# FlatApp

FlatApp is a shared-living management web application built with Laravel. It centralises common flatmate workflows such as joining a household, managing members, assigning chores, coordinating shopping, uploading receipts and reviewing household activity.

## Purpose

Shared-house coordination is often spread across chat messages, notes and informal agreements. FlatApp brings those recurring workflows into one authenticated application so members can manage household responsibilities from a common workspace.

## Implemented features

The web application currently includes:

- user registration, login and authenticated dashboard access
- flat creation and current-flat switching
- join-by-code requests
- approval and rejection of join requests
- household roles and member profile management
- activity history
- chore creation and completion
- shared shopping-list management
- receipt upload and removal

The landing page also exposes the main product flows for creating an account, signing in or joining a flat with a code.

## Technology stack

- PHP 8.3+
- Laravel 13
- Laravel Breeze authentication
- Blade
- Eloquent ORM
- Vite
- Tailwind CSS
- Alpine.js

## Main application areas

- `routes/web.php` — browser routes and authenticated workflows
- `app/Http/Controllers/` — flat, membership, chores, shopping, receipts and activity logic
- `app/Models/` — application data models
- `resources/views/` — Blade user interface
- `database/migrations/` — relational database schema

## Local setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
php artisan serve
```

Configure the database and any environment-specific values in `.env` before running migrations.

For active frontend development, use:

```bash
npm run dev
```

## Related mobile application

A separate .NET MAUI Blazor Hybrid mobile client is included in this portfolio under `flatapp-mobile-main`. The mobile project uses the FlatApp backend as its application service layer.

## Scope

This repository represents the Laravel web application and its implemented household-management workflows. Features should be evaluated from the code and routes present in this snapshot rather than from the default Laravel framework capabilities.
