@extends('layout.layout')

@section('title')
    Login
@endsection

@section('content')

<div class="auth-container">
    <h1>INICIAR SESIÓN</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('auth.login.post') }}" method="POST" class="auth-form">
        @csrf

        <div class="form-group">
            <label for="email">Email o Usuario:</label>
            <input
                type="text"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                class="form-control @error('email') is-invalid @enderror"
            >
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Contraseña:</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                class="form-control @error('password') is-invalid @enderror"
            >
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-submit">Iniciar Sesión</button>
    </form>

    <p class="auth-link">
        ¿No tienes cuenta? <a href="{{ route('auth.signup') }}">Regístrate aquí</a>
    </p>
</div>

@endsection
