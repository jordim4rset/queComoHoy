<?php

use App\Http\Controllers\BlockController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\LikeController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('/login', [LoginController::class, 'loginForm'])->name('auth.login');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/signup', [LoginController::class, 'signupForm'])->name('auth.signup');
Route::post('/signup', [LoginController::class, 'signup'])->name('auth.signup.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/shop', function () {
    return view('shop');
})->name('shop');

Route::post('/demo/chefpoints/add', function () {
    $user = User::findOrFail(Auth::id());

    $user->chefpoints += 1000;
    $user->save();

    return back()->with('success', '+1000 ChefPoints añadidos.');
})->middleware('auth')->name('demo.chefpoints.add');

Route::post('/demo/chefpoints/remove', function () {
    $user = User::findOrFail(Auth::id());

    $user->chefpoints = max(0, $user->chefpoints - 1000);
    $user->save();

    return back()->with('success', '1000 ChefPoints eliminados.');
})->middleware('auth')->name('demo.chefpoints.remove');

//RUTAS EVENTOS
Route::resource('events', EventController::class);
Route::post('/events/{event}/toggle', [EventController::class, 'toggle'])->name('events.toggle')->middleware('auth');
Route::get('/eventos', [EventController::class, 'publicIndex'])->name('eventos.index');
Route::get('/eventos/{event}', [EventController::class, 'show'])->name('eventos.show');

//RUTAS RECETAS
Route::get('/recetas', [RecipeController::class, 'index'])
    ->middleware('auth')
    ->name('recetas.index');
Route::resource('recetas', RecipeController::class)
    ->middleware('auth')
    ->only(['create', 'store', 'edit', 'update', 'destroy']);
Route::get('/recetas/{receta}', [RecipeController::class, 'show'])
    ->name('recetas.show');




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

Route::get('/profile/{id}', [UserProfileController::class, 'show'])->name('profile');

Route::get('/users', [UserController::class, 'index'])->middleware('auth')->name('users.index');
Route::put('/users', [UserController::class, 'updateCurrent'])->middleware('auth')->name('users.updateCurrent');

//Rutas de bloquear
Route::post('/block', [BlockController::class, 'block'])->name('block');
Route::post('/unblock', [BlockController::class, 'unblock'])->name('unblock');

//Ruta Perfil de Usuario
Route::get('/usuarios/{user}', [UserProfileController::class, 'show'])
    ->name('users.show');

//Ingredientes
Route::middleware('auth')->group(function () {
    Route::get('/ingredientes/ajax/buscar', [IngredientController::class, 'searchForRecipe'])
        ->name('ingredientes.searchForRecipe');

    Route::post('/ingredientes/ajax/crear', [IngredientController::class, 'storeFromRecipe'])
        ->name('ingredientes.storeFromRecipe');
});

Route::resource('ingredientes', IngredientController::class);

/**Ruta de likes */
Route::post('/recipes/{id}/like', [LikeController::class, 'toggle'])->middleware('auth')->name('recipes.like');
Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
