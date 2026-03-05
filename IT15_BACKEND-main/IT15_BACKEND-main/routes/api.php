<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('token-test', function () {
    $user = \App\Models\User::first();
    return $user->createToken('test')->plainTextToken;
});

Route::post('login', [AuthController::class, 'login']);