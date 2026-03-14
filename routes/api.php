<?php

use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SchoolDayController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\WeatherController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::post('login', [ApiAuthController::class, 'login']);
Route::post('register', [ApiAuthController::class, 'register']);
Route::get('weather/forecast', [WeatherController::class, 'forecast'])->middleware('throttle:weather');
Route::get('weather/current', [WeatherController::class, 'current'])->middleware('throttle:weather');

// Dev-only helper to quickly generate a token for manual testing.
Route::get('token-test', function () {
    $email = 'test@example.com';
    $password = 'password123';

    $user = User::firstOrCreate(
        ['email' => $email],
        ['name' => 'Test User', 'password' => Hash::make($password)]
    );

    $token = $user->createToken('token-test')->plainTextToken;

    return response($token, 200)->header('Content-Type', 'text/plain');
});

Route::middleware(['auth:sanctum', 'auth.context'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    Route::get('dashboard', [DashboardController::class, 'index']);

    Route::apiResource('students', StudentController::class);
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('programs', CourseController::class)->parameters(['programs' => 'course']);
    Route::apiResource('subjects', CourseController::class)->parameters(['subjects' => 'course']);
    Route::apiResource('school-days', SchoolDayController::class);
});