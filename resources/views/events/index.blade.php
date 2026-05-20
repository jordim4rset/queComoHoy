@extends('layout.layout')

@section('title', __('messages.events_list'))

@section('content')
    <section class="events-page">
        <div class="events-header">
            <div>
                <span>{{ $eventos->total() }} {{ strtolower(__('messages.events')) }}</span>
                <h1>{{ __('messages.events') }}</h1>
            </div>

            @auth
                @if(Auth::user()->rol === 'admin')
                    <a href="{{ route('events.create') }}" class="btn">{{ __('messages.create_event') }}</a>
                @endif
            @endauth
        </div>

        @if(session('success'))
            <div class="notice">{{ session('success') }}</div>
        @endif

        @if($eventos->count())
            <div class="events-grid">
                @include('events.partials.event-cards', ['eventos' => $eventos])
            </div>
        @else
            <p>{{ __('messages.no_events') }}</p>
        @endif
    </section>
@endsection
