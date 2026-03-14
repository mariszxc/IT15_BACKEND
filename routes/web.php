<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\DashboardReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'index']);
Route::get('/dashboard/reports', [DashboardReportController::class, 'index'])->middleware('dashboard.identity');
