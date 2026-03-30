<?php

use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

Route::resource('recipes', RecipeController::class);
Route::resource('ingredientes', IngredientController::class);
Route::get('/', IndexController::class);
