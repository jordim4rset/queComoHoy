@extends('layout.layout')

@section('title', __('messages.followers_of', ['username' => $user->username]))

@section('content')
    <div class="follow-page">
        <div class="follow-header">
            <a href="{{ route('profile', ['id' => $user->id]) }}" class="btn btn-secondary">{{ __('messages.back_to_profile') }}</a>
            <div>
                <h1>{{ __('messages.followers') }}</h1>
                <p>{{ $user->username }}</p>
            </div>
        </div>

        <div class="follow-list">
            @forelse ($followers as $follower)
                <div class="follow-user">
                    <a href="{{ route('profile', ['id' => $follower->id]) }}" class="follow-user-main">
                        <img src="{{ $follower->profilePhotoUrl() }}" alt="{{ $follower->username }}">

                        <span>
                            <strong>{{ $follower->name }}</strong>
                            <small>{{ '@' . $follower->username }}</small>
                        </span>
                    </a>

                    @auth
                        @if(auth()->id() !== $follower->id)
                            @if(auth()->user()->following->contains($follower->id))
                                <form method="POST" action="{{ url('/unfollow/' . $follower->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary btn-sm">{{ __('messages.unfollow') }}</button>
                                </form>
                            @else
                                <form method="POST" action="{{ url('/follow/' . $follower->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm">{{ __('messages.follow') }}</button>
                                </form>
                            @endif
                        @endif
                    @endauth
                </div>
            @empty
                <p class="follow-empty">{{ __('messages.user_no_followers') }}</p>
            @endforelse
        </div>
    </div>
@endsection
