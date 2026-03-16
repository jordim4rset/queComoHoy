<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Recipe;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot(): void
    {
        
        Route::model('recipe', Recipe::class, function () {
            abort(404);
        });
    }
}
