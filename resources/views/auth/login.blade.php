@extends('layout.layout')

@section('title')
    {{ __('messages.login') }}
@endsection

@section('content')

<div class="auth-container">
    <h1>{{ __('messages.login_title') }}</h1>

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

    <form action="{{ route('login') }}" method="POST" class="auth-form">
        @csrf

        <div class="form-group">
            <label for="email">{{ __('messages.email_or_user') }}:</label>
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
            <label for="password">{{ __('messages.password') }}:</label>
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

        <button type="submit" class="btn-submit">{{ __('messages.login') }}</button>
    </form>

    <p class="auth-link">
        {{ __('messages.no_account') }} <a href="{{ route('auth.signup') }}">{{ __('messages.signup_here') }}</a>
    </p>
</div>

@endsection
