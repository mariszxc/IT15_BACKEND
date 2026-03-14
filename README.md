# IT15 Backend (Laravel REST API)

Laravel backend for the Student Portal dashboard project.

## Requirements Coverage

- REST API for authentication, dashboard analytics, students, courses/programs/subjects, and school days
- Laravel Sanctum token authentication
- Protected API routes with auth:sanctum
- Weather endpoints with throttling and graceful fallback handling
- Seeder data for:
  - minimum 500 student records
  - minimum 20 courses
  - full-year school day calendar with holidays/events/attendance

## Tech Stack

- Laravel 12
- Laravel Sanctum
- MySQL or PostgreSQL

## Setup Instructions

### 1) Install dependencies

- composer install

### 2) Configure environment

- copy .env.example .env
- Set DB credentials in .env

### 3) Generate key and run database

- php artisan key:generate
- php artisan migrate --seed

### 4) Run backend server

- php artisan serve

Default local URL:

- http://127.0.0.1:8000

## API Endpoints

### Public

- POST /api/register
- POST /api/login
- GET /api/weather/current
- GET /api/weather/forecast

### Protected (Bearer token required)

- POST /api/logout
- GET /api/me
- GET /api/dashboard
- GET|POST|PUT|PATCH|DELETE /api/students
- GET|POST|PUT|PATCH|DELETE /api/courses
- GET|POST|PUT|PATCH|DELETE /api/programs
- GET|POST|PUT|PATCH|DELETE /api/subjects
- GET|POST|PUT|PATCH|DELETE /api/school-days

## Sample Auth Flow

1. Register:
   - POST /api/register
2. Login:
   - POST /api/login
3. Use token:
   - Authorization: Bearer <token>
4. Validate session:
   - GET /api/me

## Notes

- Weather API is rate limited through the weather limiter.
- If third-party weather/geocoding providers are temporarily unreachable, fallback weather payload is returned so frontend can still render.
