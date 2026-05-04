@extends('layout.layout')

@section('title')
    Mi Perfil
@endsection

@section('content')

<div class="account-container">
    <h1 class="account-title">MI PERFIL</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="profile-card">
        <div class="card-header">
            <h2>Información de Mi Cuenta</h2>
        </div>

        <div class="card-body">
            <div class="info-group">
                <label class="info-label">Nombre:</label>
                <span class="info-value">{{ auth()->user()->name }}</span>
            </div>

            <div class="info-group">
                <label class="info-label">Nombre de Usuario:</label>
                <span class="info-value">{{ auth()->user()->username }}</span>
            </div>

            <div class="info-group">
                <label class="info-label">Email:</label>
                <span class="info-value">{{ auth()->user()->email }}</span>
            </div>

            <div class="info-group">
                <label class="info-label">Rol:</label>
                <span class="info-value">{{ ucfirst(auth()->user()->rol) }}</span>
            </div>

            <div class="info-group">
                <label class="info-label">Miembro desde:</label>
                <span class="info-value">{{ auth()->user()->created_at->format('d/m/Y') }}</span>
            </div>
            <div class="info-group">
                <label class="info-label"><a href="{{ route('user.following', auth()->id()) }}">Siguiendo</a>:</label>
                <span class="info-value">{{ auth()->user()->following()->count() }}</span>
            </div>
            <div class="info-group">
                <label class="info-label"><a href="{{ route('user.followers', auth()->id()) }}">Seguidores</a>:</label>
                <span class="info-value">{{ auth()->user()->followers()->count() }}</span>
            </div>
        </div>

        <div class="card-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">Cerrar Sesión</button>
            </form>
        </div>
    </div>
</div>

@endsection
