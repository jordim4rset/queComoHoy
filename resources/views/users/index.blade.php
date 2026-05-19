@extends('layout.layout')

@section('title')
    Buscar usuarios
@endsection

@section('content')
    <div class="users-search-container">
        <h1>Usuarios</h1>

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
            <input type="search" id="user-search" placeholder="Buscar usuario..." autocomplete="off">
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
                                <small class="ban-status">Baneado indefinidamente</small>
                            @endif
                        </span>
                    </a>

                    @auth
                        @if(auth()->user()->rol === 'admin' && auth()->id() !== $user->id)
                            <div class="user-admin-actions">
                                @if($user->isBanned())
                                    <form method="POST" action="{{ route('users.unban', $user) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-secondary">Desbanear</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('users.ban', $user) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Banear</button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    @endauth
                </li>
            @empty
                <li class="user-item-empty">No hay usuarios disponibles.</li>
            @endforelse
        </ul>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('user-search');
            const userItems = document.querySelectorAll('.user-item');

            searchInput.addEventListener('input', function () {
                const term = this.value.trim().toLowerCase();

                userItems.forEach(function (item) {
                    const name = item.dataset.name;
                    const username = item.dataset.username;
                    const matches = name.includes(term) || username.includes(term);

                    item.style.display = matches ? '' : 'none';
                });
            });
        });
    </script>
@endsection
