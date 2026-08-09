# IT Equipment Inventory Frontend

This project is a React frontend for managing an IT equipment inventory. It provides authenticated CRUD-style interfaces for equipment records and the reference data used to classify them.

## Implemented functionality

After authentication, the application provides routes for:

- inventory records
- users
- brands
- equipment types
- equipment statuses
- editing individual records for each of those entities

The main navigation exposes the inventory catalogue together with the supporting master-data modules.

## Authentication

Authentication is handled with Auth0 through `@auth0/auth0-react`. The application only exposes the inventory interface when the user is authenticated.

Create an Auth0 Single Page Application and configure the frontend environment with:

```env
REACT_APP_DOMAIN=
REACT_APP_CLIENT_ID=
REACT_APP_REDIRECT_URI=http://localhost:3000/
```

Use the Auth0 domain and client ID assigned to the SPA application.

## Backend API

The frontend uses Axios and currently expects the companion backend at:

```text
http://localhost:8075/
```

The backend source is included in this portfolio under `back_tres-main`. It uses Node.js, Express and MongoDB/Mongoose to manage inventory-related data.

## Technology stack

- React 18
- React Router
- Auth0 React SDK
- Axios
- SweetAlert2
- Create React App / react-scripts

## Main code areas

- `src/App.js` — authenticated routing
- `src/components/inventarios/` — inventory views and editing
- `src/components/usuarios/` — user management
- `src/components/marcas/` — brand management
- `src/components/tipos/` — equipment-type management
- `src/components/estados/` — equipment-status management
- `src/components/auth0/` — authentication components
- `src/helpers/axios-config.js` — backend base URL

## Running locally

Install the dependencies:

```bash
npm install
```

Create the `.env` file with the Auth0 values shown above, make sure the backend is running on port `8075`, and then start the React development server:

```bash
npm start
```

## Project scope

This repository contains the frontend only. Authentication depends on Auth0 and all inventory data operations depend on the companion backend. The code represents the architecture of the original project snapshot rather than a standalone hosted application.
