# FlatNZ Mobile

FlatNZ Mobile is the native mobile companion app for FlatNZ, built with **.NET MAUI Blazor Hybrid**.

It is designed for flatmates who need a practical mobile-first tool to manage shared living: home setup, member access, chores, shopping, expenses, receipts, and activity tracking, all connected to a Laravel backend API.

---

## What this project is

FlatNZ Mobile is part of the wider **FlatNZ ecosystem**, a flatmate management platform focused on real shared-house workflows.

The mobile app is not just a demo shell. It already supports core user and household operations such as:

- authentication
- flat creation and join flows
- join request review
- member visibility
- chores
- shopping
- finance and balances
- receipts
- activity history
- privacy and account deletion flows

This repository is mainly intended for:

- technical reviewers
- recruiters evaluating architecture and implementation quality
- developers maintaining or extending the mobile app
- testers generating Android review builds

---

## Product focus

FlatNZ helps flatmates reduce friction in shared living by centralising the kinds of tasks that usually get scattered across chats, screenshots, notes, and bank transfers.

The mobile experience is focused on:

- clear household ownership and membership flows
- shared task coordination
- shopping-to-finance continuity
- receipt-backed expense tracking
- admin review flows for access requests
- privacy and account controls appropriate for store publishing

---

## Current capabilities

The app currently includes:

### Authentication and account
- Login
- Registration
- Logout
- Secure token storage
- In-app privacy policy
- In-app account deletion request flow

### Flats and membership
- Create flat flow
- Join flat request flow
- Current flat switching
- Invite-code display
- Clipboard copy for invite codes
- Members screen with active flatmates and roles
- Admin join-request review with approve/reject actions

### Household operations
- Dashboard with module shortcuts
- Activity preview and activity log screen
- Bottom navigation for Home, Chores, Shopping, Finance, and Settings

### Chores
- List chores
- Create chores
- Edit chores
- Complete chores
- Archive/remove chores

### Shopping
- Add shopping items
- Deactivate/reactivate items
- Purchase flow connected to finance

### Finance and receipts
- Expense tracking
- Balances
- Settlements/payments
- Receipt management
- Receipt upload for JPG, PNG, and PDF

### UX
- Styled confirmation dialogs for sensitive actions
- Mobile-first dark UI with black, white, and bright blue accents

---

## Tech stack

- **.NET MAUI**
- **Blazor Hybrid**
- **C#**
- **Typed HTTP services**
- **Laravel API backend**
- **Laravel Sanctum bearer-token authentication**

---

## Architecture notes

The app uses a service-oriented mobile structure where UI pages call typed HTTP services instead of mixing transport logic directly into the components.

Key project areas include:

- `Components/` for pages and UI composition
- `Services/` for domain-specific API access
- `Configuration/` for runtime settings such as API base URL
- `Resources/` for app icons, splash assets, images, and fonts
- `Platforms/` for Android and platform-specific configuration

This structure makes the project easier to review, maintain, and evolve.

---

## API base URL

The mobile app currently targets:

```txt
https://flat.yobany.top/api
