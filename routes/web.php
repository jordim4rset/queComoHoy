<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

// Auth Routes
Route::get('/login', [LoginController::class, 'loginForm'])->name('auth.login');
Route::post('/login', [LoginController::class, 'login'])->name('auth.login.post');
Route::get('/signup', [LoginController::class, 'signupForm'])->name('auth.signup');
Route::post('/signup', [LoginController::class, 'signup'])->name('auth.signup.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::resource('events', EventController::class);
Route::resource('recetas', RecipeController::class);
Route::resource('ingredientes', IngredientController::class);
Route::get('/', IndexController::class)->name('index');
