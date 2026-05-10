# FlatNZ Mobile

Native mobile companion app for FlatNZ, built with .NET MAUI Blazor Hybrid.

FlatNZ helps flatmates manage a shared home from a mobile-first experience: authentication, flats, members, join requests, chores, shopping, finance, receipts, and activity.

## Current capabilities

The mobile app currently includes:

- Login, registration, logout, and secure token storage.
- Create flat and join flat request flows.
- Current flat switching support through the backend API.
- Invite-code display and clipboard copy.
- Admin join-request review with approve/reject actions.
- Members screen with active flatmates and roles.
- Activity log screen and dashboard activity preview.
- Dashboard with flat management, finance, and module shortcuts.
- Bottom navigation for Home, Chores, Shopping, Finance, and Settings.
- Chores list, create, edit, complete, and archive flows.
- Shopping list add, remove/reactivate, and purchase-to-finance flow.
- Finance expenses, balances, settlements/payments, and receipt management.
- Receipt upload for JPG, PNG, and PDF files.
- Styled FlatNZ confirmation dialogs for sensitive actions.
- Dark mobile-first UI using black, white, and bright blue accents.

## Tech stack

- .NET MAUI
- Blazor Hybrid
- C#
- Typed HTTP services
- Laravel API backend
- Laravel Sanctum bearer-token authentication

## API base URL

The mobile app calls the Laravel API at:

```txt
https://flat.yobany.top/api
```

The default URL is configured in:

```txt
FlatApp.Mobile/FlatApp.Mobile/Configuration/ApiSettings.cs
```

For local or staging testing, set the optional `FLATNZ_API_URL` environment variable before running the app. If the variable is missing, the app falls back to the production API URL.

```bash
export FLATNZ_API_URL="https://your-staging-domain.test/api"
dotnet build -t:Run -f net10.0-android
```

Android emulators usually cannot call `localhost` directly. Use the host machine address available to the emulator or a tunneled HTTPS URL.

## Main backend endpoints used

```txt
POST   /api/auth/register
POST   /api/auth/login
POST   /api/auth/logout
GET    /api/auth/me

GET    /api/flats/current
POST   /api/flats/current
POST   /api/flats
POST   /api/flats/join
GET    /api/flats/current/members
GET    /api/flats/current/join-requests
POST   /api/flats/current/join-requests/{joinRequest}/approve
POST   /api/flats/current/join-requests/{joinRequest}/reject

GET    /api/activity

GET    /api/chores
POST   /api/chores
PUT    /api/chores/{chore}
POST   /api/chores/{chore}/complete
DELETE /api/chores/{chore}

GET    /api/shopping
POST   /api/shopping
PUT    /api/shopping/{item}
DELETE /api/shopping/{item}
POST   /api/shopping/{item}/deactivate
POST   /api/shopping/{item}/reactivate
POST   /api/shopping/{item}/purchase

GET    /api/finance
POST   /api/finance/expenses
PUT    /api/finance/expenses/{expense}
DELETE /api/finance/expenses/{expense}
POST   /api/finance/settlements
DELETE /api/finance/settlements/{settlement}

GET    /api/receipts
POST   /api/receipts
DELETE /api/receipts/{receipt}
```

## Running the app

From the repository root:

```bash
cd FlatApp.Mobile/FlatApp.Mobile
dotnet restore
dotnet build -f net10.0-android
```

To run on a connected Android device or emulator:

```bash
dotnet build -t:Run -f net10.0-android
```

## Android release build

A basic release build can be created with:

```bash
dotnet publish -f net10.0-android -c Release
```

See `docs/ANDROID_RELEASE.md` for a release checklist before sharing a reviewable Android build.

## Backend deployment reminders

When backend routes change, deploy the Laravel API and clear caches:

```bash
php artisan route:clear
php artisan config:clear
php artisan optimize:clear
```

## Product direction

The next useful improvements are:

- Finance-specific styled delete confirmations.
- In-app notifications.
- More complete member role management if supported by the backend.
- Android signing and release documentation.
