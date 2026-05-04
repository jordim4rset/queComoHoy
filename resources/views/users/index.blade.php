@extends('layout.layout')

@section('content')
    <div class="users-search-container">
        <h1>Usuarios</h1>

        <div class="user-search-box">
            <input type="search" id="user-search" placeholder="Buscar usuario..." autocomplete="off">
        </div>

        <ul id="user-list" class="user-list">
            @forelse($users as $user)
                <li class="user-item" data-name="{{ strtolower($user->name) }}" data-username="{{ strtolower($user->username) }}">
                    <a href="{{ route('profile', $user->id) }}">
                        <strong>{{ $user->name }}</strong>
                        <span class="user-username">{{ '@' . $user->username }}</span>
                    </a>
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
