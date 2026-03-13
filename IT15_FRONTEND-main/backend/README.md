# Backend API (Laravel)

This backend provides RESTful APIs for authentication, dashboard charts, and weather integration.

## Setup

1. Install dependencies:

   - `composer install`

2. Create env file:

   - `copy .env.example .env`

3. Configure environment:

   - Database (`DB_*`)
   - `CORS_ALLOWED_ORIGINS`
   - `WEATHER_API_KEY`

4. Generate app key:

   - `php artisan key:generate`

5. Run migrations + seed:

   - `php artisan migrate --seed`

6. Start server:

   - `php artisan serve`

## Auth

Uses Laravel Sanctum personal access tokens (Bearer token).

## Endpoints

- `POST /api/auth/register`
- `POST /api/auth/login`
- `GET /api/auth/me`
- `POST /api/auth/logout`
- `GET /api/dashboard/metrics`
- `GET /api/weather/current`
- `GET /api/weather/forecast`
