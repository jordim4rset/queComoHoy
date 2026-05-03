<?php

/**
 * @author Alex Fonta
 * @version 1.0
 */

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function signupForm()
    {
        return view('auth.signup');
    }

    public function signup(SignupRequest $request): RedirectResponse
    {
        $user = new User();
        $user->username = $request->username;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        // Si es rick con contraseña rick, asigna rol admin, sino member
        if ($request->username === 'rick' && $request->password === 'rick') {
            $user->rol = 'admin';
        } else {
            $user->rol = 'member';
        }

        $user->save();

        Auth::login($user);

        return redirect()->route('index');
    }

    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $input = $request->input('email');
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        $credentials = ['email' => $input, 'password' => $password];
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('index'));
        }

        $credentials = ['username' => $input, 'password' => $password];
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('index'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index');
    }
}

