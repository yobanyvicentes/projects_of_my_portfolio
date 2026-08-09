# E-commerce Admin Frontend

This project is a React administration interface for managing an e-commerce catalogue and its supporting entities through a REST API.

## Implemented functionality

The application requires authentication before exposing the administration routes and includes interfaces for:

- products
- brands
- categories
- users

Each module provides list/view workflows and editing screens, while product data is also presented through a card-based catalogue view.

## Authentication and API access

The frontend authenticates against the companion backend and stores the returned token in browser `localStorage`. Axios is configured to send that token in the `access-token` request header.

The snapshot currently points to:

```text
https://back-admin-ecommerce.onrender.com/
```

The corresponding backend source is also included in this portfolio under `backend_ecommerce_admin-main`.

## Technology stack

- React 18
- React Router
- Axios
- react-jwt
- SweetAlert2
- Create React App / react-scripts

## Main code areas

- `src/components/app/App.js` — authenticated routing and application shell
- `src/components/auth/` — login and logout workflows
- `src/components/product/` — product views and editing
- `src/components/brand/` — brand management
- `src/components/category/` — category management
- `src/components/user/` — user management
- `src/services/` — API calls by domain
- `src/helpers/axios-config.js` — API base URL and token header configuration

## Running locally

Install dependencies and start the development server:

```bash
npm install
npm start
```

The development application is served on the standard Create React App development port unless configured otherwise.

To create a production build:

```bash
npm run build
```

## Backend dependency

The interface is not a standalone application: catalogue and authentication operations depend on the REST backend being reachable. If the deployed backend URL changes, update the Axios base URL before running the frontend against another environment.

## Project scope

This is a portfolio snapshot of an administrative CRUD application. The authentication implementation reflects the architecture used when the project was built; storing bearer tokens in `localStorage` is part of this snapshot and should be reassessed when adapting the project for a production security model.
