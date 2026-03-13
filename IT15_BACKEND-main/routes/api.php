<?php

use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SchoolDayController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::post('login', [ApiAuthController::class, 'login']);
Route::get('weather/forecast', [WeatherController::class, 'forecast'])->middleware('throttle:weather');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    Route::apiResource('students', StudentController::class);
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('school-days', SchoolDayController::class);

    Route::get('dashboard', [DashboardController::class, 'index']);
});