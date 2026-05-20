<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {

        $supported = config('app.supported_locales', ['es', 'en']);

        $default = config('app.locale', 'es');


        $userLocale = Auth::user()?->lang;


        $cookieLocale = $request->cookie('locale');


        $locale = $userLocale ?: $cookieLocale ?: $default;

        if (!in_array($locale, $supported, true)) {
            $locale = $default;
        }


        app()->setLocale($locale);


        $response = $next($request);

        if (!$userLocale && !$cookieLocale) {
            $response->headers->setCookie(cookie('locale', $locale, 60 * 24 * 365));
        }

        return $response;
    }
}
