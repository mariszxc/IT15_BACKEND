# Full-Stack School Dashboard (React + Laravel)

This project contains:

- `frontend` in the repository root (React + Vite)
- `backend/` (Laravel REST API + Sanctum auth)

Features included:

- User authentication (register/login/logout/me)
- Dashboard visualizations (enrollment, course distribution, attendance)
- Weather integration (current weather + 5-day forecast)
- Loading states, error handling, validation feedback, and responsive UI

## Tech Stack

- Frontend: React, React Router, Recharts, Axios, Bootstrap
- Backend: Laravel 12, Sanctum, Eloquent ORM
- Database: MySQL or PostgreSQL

## Project Structure

- Frontend key files:
  - `src/components/auth/Login.jsx`
  - `src/components/dashboard/Dashboard.jsx`
  - `src/components/dashboard/EnrollmentChart.jsx`
  - `src/components/dashboard/CourseDistributionChart.jsx`
  - `src/components/dashboard/AttendanceChart.jsx`
  - `src/components/weather/WeatherWidget.jsx`
  - `src/components/weather/ForecastDisplay.jsx`
  - `src/components/common/Navbar.jsx`
  - `src/components/common/LoadingSpinner.jsx`
  - `src/components/common/ErrorBoundary.jsx`
  - `src/services/api.js`
  - `src/services/weatherApi.js`
- Backend key files:
  - `backend/routes/api.php`
  - `backend/app/Http/Controllers/Api/*`
  - `backend/database/migrations/*`
  - `backend/database/seeders/DatabaseSeeder.php`

## Prerequisites

- Node.js 18+
- PHP 8.2+
- Composer 2+
- MySQL or PostgreSQL

## Backend Setup (Laravel)

1. Go to backend:

	- `cd backend`

2. Install dependencies:

	- `composer install`

3. Create environment file:

	- `copy .env.example .env`

4. Configure database + weather key in `.env`:

	- `DB_CONNECTION=mysql` (or `pgsql`)
	- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
	- `WEATHER_API_KEY=your_openweather_api_key`

5. Generate key:

	- `php artisan key:generate`

6. Run migrations and seeders:

	- `php artisan migrate --seed`

7. Start API server:

	- `php artisan serve`

Backend default URL: `http://127.0.0.1:8000`

Demo seeded account:

- Email: `admin@example.com`
- Password: `Password123!`

## Frontend Setup (React)

1. From repo root:

	- `npm install`

2. Create environment file:

	- `copy .env.example .env`

3. Start dev server:

	- `npm run dev`

   or

  - `npm start`

Frontend default URL: `http://localhost:5173`

## Security Notes

- API keys are stored in backend environment variables (`backend/.env`)
- Frontend sends authenticated requests using Bearer tokens
- Input validation and sanitization are applied in both frontend and backend
- CORS is configured in `backend/config/cors.php`

## API Endpoints

- `POST /api/auth/register`
- `POST /api/auth/login`
- `GET /api/auth/me` (auth required)
- `POST /api/auth/logout` (auth required)
- `GET /api/dashboard/metrics` (auth required)
- `GET /api/weather/current` (auth required)
- `GET /api/weather/forecast` (auth required)
