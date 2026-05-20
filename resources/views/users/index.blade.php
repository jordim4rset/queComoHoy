@extends('layout.layout')

@section('title')
    {{ __('messages.search_users') }}
@endsection

@section('content')
    <div class="users-search-container">
        <h1>{{ __('messages.users') }}</h1>

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

        <div class="user-search-box">
            <input type="search" id="user-search" placeholder="{{ __('messages.search_user') }}" autocomplete="off">
        </div>

        <ul id="user-list" class="user-list">
            @forelse($users as $user)
                <li class="user-item" data-name="{{ strtolower($user->name) }}" data-username="{{ strtolower($user->username) }}">
                    <a href="{{ route('profile', ['id' => $user->id]) }}">
                        <img src="{{ $user->profilePhotoUrl() }}" alt="{{ $user->username }}">
                        <span>
                            <strong>{{ $user->name }}</strong>
                            <small>{{ '@' . $user->username }}</small>
                            @if($user->isBanned())
                                <small class="ban-status">{{ __('messages.banned_indefinitely') }}</small>
                            @endif
                        </span>
                    </a>

                    @auth
                        @if(auth()->user()->rol === 'admin' && auth()->id() !== $user->id)
                            <div class="user-admin-actions">
                                @if($user->isBanned())
                                    <form method="POST" action="{{ route('users.unban', $user) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-secondary">{{ __('messages.unban') }}</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('users.ban', $user) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">{{ __('messages.ban') }}</button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    @endauth
                </li>
            @empty
                <li class="user-item-empty">{{ __('messages.no_users') }}</li>
            @endforelse
        </ul>
    </div>

@endsection
