@extends('layout.layout')

@section('title', __('messages.following_by', ['username' => $user->username]))

@section('content')
    <div class="follow-page">
        <div class="follow-header">
            <a href="{{ route('profile', ['id' => $user->id]) }}" class="btn btn-secondary">{{ __('messages.back_to_profile') }}</a>
            <div>
                <h1>{{ __('messages.following_users') }}</h1>
                <p>{{ $user->username }}</p>
            </div>
        </div>

        <div class="follow-list">
            @forelse ($following as $followed)
                <div class="follow-user">
                    <a href="{{ route('profile', ['id' => $followed->id]) }}" class="follow-user-main">
                        <img src="{{ $followed->profilePhotoUrl() }}" alt="{{ $followed->username }}">

                        <span>
                            <strong>{{ $followed->name }}</strong>
                            <small>{{ '@' . $followed->username }}</small>
                        </span>
                    </a>

                    @auth
                        @if(auth()->id() !== $followed->id && auth()->user()->following->contains($followed->id))
                            <form method="POST" action="{{ url('/unfollow/' . $followed->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm">{{ __('messages.unfollow') }}</button>
                            </form>
                        @endif
                    @endauth
                </div>
            @empty
                <p class="follow-empty">{{ __('messages.user_not_following_anyone') }}</p>
            @endforelse
        </div>
    </div>
@endsection
