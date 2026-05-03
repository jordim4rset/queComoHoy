<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\UserController;

// Auth Routes
Route::get('/login', [LoginController::class, 'loginForm'])->name('auth.login');
Route::post('/login', [LoginController::class, 'login'])->name('auth.login.post');
Route::get('/signup', [LoginController::class, 'signupForm'])->name('auth.signup');
Route::post('/signup', [LoginController::class, 'signup'])->name('auth.signup.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/account', function () {
    return view('auth.account');
})->middleware('auth')->name('account');

Route::resource('events', EventController::class);
Route::resource('recipes', RecipeController::class);
Route::resource('ingredientes', IngredientController::class);
Route::get('/', IndexController::class)->name('index');



Route::middleware('auth:sanctum')->group(function () {

    Route::post('/follow/{id}', [FollowController::class, 'follow']);
    Route::delete('/unfollow/{id}', [FollowController::class, 'unfollow']);

    Route::get('/users/{id}/followers', [FollowController::class, 'followers']);
    Route::get('/users/{id}/following', [FollowController::class, 'following']);

});


// seguir / dejar de seguir
Route::post('/follow/{id}', [FollowController::class, 'follow'])->middleware('auth');
Route::delete('/unfollow/{id}', [FollowController::class, 'unfollow'])->middleware('auth');

// vistas
Route::get('/users/{id}/followers', [FollowController::class, 'followersView'])->name('user.followers');
Route::get('/users/{id}/following', [FollowController::class, 'followingView'])->name('user.following');

Route::get('/profile/{id}', [UserController::class, 'show']);

Route::get('/users', [UserController::class, 'index']);