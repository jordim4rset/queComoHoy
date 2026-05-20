@extends('layout.layout')

@section('title')
    {{ __('messages.signup') }}
@endsection

@section('content')

<div class="auth-container">
    <h1>{{ __('messages.signup_title') }}</h1>

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

    <form action="{{ route('auth.signup.post') }}" method="POST" enctype="multipart/form-data" class="auth-form">
        @csrf

        <div class="form-group">
            <label for="name">{{ __('messages.full_name') }}:</label>
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
            <label for="username">{{ __('messages.username') }}:</label>
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
            <label for="email">{{ __('messages.email') }}:</label>
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

        <div class="form-group">
            <label for="password_confirmation">{{ __('messages.password_confirm') }}:</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
                class="form-control"
            >
        </div>

        <div class="form-group">
            <label for="profile_photo">{{ __('messages.profile_photo') }}:</label>
            <input
                type="file"
                id="profile_photo"
                name="profile_photo"
                accept="image/*"
                class="form-control @error('profile_photo') is-invalid @enderror"
            >
            @error('profile_photo')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-submit">{{ __('messages.signup') }}</button>
    </form>

    <p class="auth-link">
        {{ __('messages.have_account') }} <a href="{{ route('auth.login') }}">{{ __('messages.login_here') }}</a>
    </p>
</div>

@endsection
