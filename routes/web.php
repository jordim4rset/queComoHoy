<?php

use App\Http\Controllers\RecipeController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('/login', [LoginController::class, 'loginForm'])->name('auth.login');
Route::post('/login', [LoginController::class, 'login'])->name('auth.login.post');
Route::get('/signup', [LoginController::class, 'signupForm'])->name('auth.signup');
Route::post('/signup', [LoginController::class, 'signup'])->name('auth.signup.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::resource('recipes', RecipeController::class);
Route::get('/', App\Http\Controllers\IndexController::class);
