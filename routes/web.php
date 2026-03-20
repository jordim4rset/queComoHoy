<?php

use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

Route::resource('recipes', RecipeController::class);
Route::get('/', App\Http\Controllers\IndexController::class);
