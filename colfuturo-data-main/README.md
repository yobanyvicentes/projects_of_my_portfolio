# COLFUTURO Data

COLFUTURO Data is a Laravel web application for exploring records from COLFUTURO's Scholarship Loan Program through filters, summary indicators, rankings, maps and study-destination recommendations.

## What the application does

The application provides an analytical interface for examining postgraduate mobility patterns by promotion year, academic area, origin, destination and programme characteristics.

Implemented features include:

- KPI summaries for profiles, beneficiaries, selected applicants, countries and postgraduate universities
- top-10 rankings for destination countries, postgraduate universities, academic areas and origin departments
- male/female comparisons within aggregated rankings
- filters for promotion year, country, department, academic area, postgraduate type and other profile attributes
- origin and destination maps
- a paginated profile explorer
- a recommendation endpoint based on **postgraduate type** and **academic area**
- CSV import support for loading the source dataset into the application database

## Recommendation logic

The public recommender accepts two optional study-intention inputs:

- postgraduate type
- academic area

It uses matching historical records to produce aggregated recommendations for countries, universities, programmes and destination cities. The recommender is an exploratory decision-support feature; it is not a predictive admissions or scholarship model.

## Technology stack

- PHP / Laravel
- Blade templates
- Eloquent ORM
- JavaScript
- Chart.js
- Leaflet / OpenStreetMap
- Tailwind CSS
- CSV-based data import

## Project structure

Key areas of the codebase include:

- `app/Http/Controllers/AcademicInsightsController.php` — page and recommendation requests
- `app/Services/AcademicInsights/AcademicInsightsService.php` — dashboard aggregation, maps, explorer queries and recommendation logic
- `app/Models/ColfuturoProfile.php` — profile model and filters
- `resources/views/academic-insights/` — analytical interface
- `routes/` — application routes

## Running locally

Install the PHP and JavaScript dependencies, configure the environment, migrate the database and start the application:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run build
php artisan serve
```

Configure the database connection in `.env` before importing data.

## Data note

The application code is published as a portfolio artefact. Source data should be obtained and used in accordance with the applicable COLFUTURO terms, privacy requirements and intellectual-property conditions. The repository should not be treated as a licence to redistribute third-party data.
