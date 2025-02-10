<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;



Route::group(['middleware' => 'Admin'], function () {
    Route::get('/', [HomeController::class, 'index']);
    
});

// ********* Login *********
Route::get('/login', [AuthController::class, 'loginindex']);
// ********* Login *********


// ********* Register *********
Route::get('/register', [AuthController::class, 'registerindex']);
// ********* Register *********