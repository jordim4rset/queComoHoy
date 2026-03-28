<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

Route::get('/', App\Http\Controllers\IndexController::class);
Route::resource('recipes', RecipeController::class);
Route::resource('events', EventController::class);

