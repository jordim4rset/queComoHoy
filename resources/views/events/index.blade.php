@extends('layout.layout')

@section('title', 'Lista de Eventos')

@section('content')
    <h1>Eventos</h1>

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px">
        @auth
            @if(Auth::user()->rol === 'admin')
                <a href="{{ route('events.create') }}" class="btn">Crear evento</a>
            @endif
        @endauth
    </div>

    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif

    <div
        class="events-grid"
        data-infinite-scroll-container
        style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px"
    >
        @if($eventos->count())
            @include('events.partials.event-cards', ['eventos' => $eventos])
        @else
            <p>No hay eventos disponibles.</p>
        @endif

        <div
            data-infinite-scroll-trigger
            data-next-page-url="{{ $eventos->nextPageUrl() }}"
            aria-hidden="true"
        ></div>
    </div>

@endsection
