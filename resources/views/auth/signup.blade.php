@extends('layout.layout')

@section('title')
    Registro - VITALITY
@endsection

@section('content')

<div class="auth-container">
    <h1>REGISTRARSE</h1>

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

    <form action="{{ route('auth.signup.post') }}" method="POST" class="auth-form">
        @csrf

        <div class="form-group">
            <label for="name">Nombre Completo:</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
                class="form-control @error('name') is-invalid @enderror"
            >
            @error('name')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="username">Nombre de Usuario:</label>
            <input
                type="text"
                id="username"
                name="username"
                value="{{ old('username') }}"
                required
                class="form-control @error('username') is-invalid @enderror"
            >
            @error('username')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input
                type="email"
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

        <div class="form-group">
            <label for="password_confirmation">Confirmar Contraseña:</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
                class="form-control"
            >
        </div>

        <button type="submit" class="btn-submit">Registrarse</button>
    </form>

    <p class="auth-link">
        ¿Ya tienes cuenta? <a href="{{ route('auth.login') }}">Inicia sesión aquí</a>
    </p>
</div>

@endsection
