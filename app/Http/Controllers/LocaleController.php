<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocaleController extends Controller
{
    public function __invoke(Request $request)
    {
        // Se obtienen los idiomas disponibles desde la configuración: config/app.php
        $supported = config('app.supported_locales', ['es', 'en']);

        // Se valida el campo recibido
        $data = $request->validate([
            'locale' => ['required', 'in:' . implode(',', $supported)],
        ]);

        $locale = $data['locale'];

        // Si está logueado, persistir en BD
        if (Auth::check()) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $user->update(['lang' => $locale]);
        }

        app()->setLocale($locale);

        // Se redirige a la página anterior creando la cookie 'locale' con el idioma seleccionado
        return back()->withCookie(cookie('locale', $locale, 60 * 24 * 365));
    }
}
