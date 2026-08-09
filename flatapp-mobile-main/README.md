# FlatNZ Mobile

FlatNZ Mobile is a .NET MAUI Blazor Hybrid client for the FlatApp shared-living platform. It provides a mobile interface for household membership, chores, shopping, finance-related workflows, receipts and activity tracking through the Laravel backend API.

## Current capabilities

### Authentication and account

- registration and login
- logout
- secure token storage
- in-app privacy information
- account deletion request flow

### Flats and membership

- create a flat
- request to join a flat
- switch the active flat
- display and copy invite codes
- view household members and roles
- approve or reject join requests when authorised

### Household workflows

- dashboard and activity history
- create, edit, complete and archive chores
- add, deactivate, reactivate and purchase shopping items
- view expenses and balances
- record settlements/payments
- manage receipts
- upload JPG, PNG and PDF receipt files

## Technology stack

- .NET MAUI
- Blazor Hybrid
- C#
- typed HTTP services
- Laravel API backend
- Laravel Sanctum bearer-token authentication

The project targets Android and also includes MAUI target configuration for iOS, Mac Catalyst and Windows where the development platform supports those targets.

## Architecture

The application separates interface code from HTTP and configuration concerns:

- `Components/` — pages and reusable UI components
- `Services/` — domain-specific API clients
- `Configuration/` — runtime settings, including API configuration
- `Resources/` — icons, splash assets, images and fonts
- `Platforms/` — platform-specific settings

This keeps API access outside the UI components and makes individual household modules easier to maintain.

## Backend API

The default production API is:

```text
https://flat.yobany.top/api
```

The API configuration is maintained in the mobile project's `Configuration` area. For local or staging development, use the project's supported environment/configuration mechanism to point the client at the appropriate backend.

## Running the Android project

From the application project directory:

```bash
dotnet restore
dotnet build -f net10.0-android
```

To run on a connected Android device or emulator:

```bash
dotnet build -t:Run -f net10.0-android
```

## Relationship to FlatApp

This repository contains the mobile client. The Laravel web application and backend workflows are included separately in this portfolio under `flatapp`.

## Scope

FlatNZ Mobile is a client application and therefore depends on a reachable FlatApp API for authenticated and household operations. The repository documents the mobile implementation itself rather than presenting the backend as part of the same codebase.
