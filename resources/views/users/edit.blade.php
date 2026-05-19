@extends('layout.layout')

@section('title')
    Editar usuario
@endsection

@section('content')
    <div class="user-edit-page">
        <div class="user-edit-header">
            <h1>Editar usuario</h1>
            <a href="{{ route('profile', ['id' => auth()->id()]) }}" class="btn btn-secondary">Ver perfil</a>
        </div>

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

        <form action="{{ route('users.updateCurrent') }}" method="POST" enctype="multipart/form-data" class="user-edit-form">
            @csrf
            @method('PUT')

            <div class="user-edit-photo">
                <img
                    src="{{ auth()->user()->profilePhotoUrl() }}"
                    alt="{{ auth()->user()->username }}"
                >

                <div class="form-group">
                    <label for="profile_photo">Foto de perfil:</label>
                    <input
                        type="file"
                        id="profile_photo"
                        name="profile_photo"
                        accept="image/*"
                    >
                </div>
            </div>

            <div class="user-edit-grid">
                <div class="form-group">
                    <label for="name">Nombre:</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', auth()->user()->name) }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="username">Nombre de usuario:</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username', auth()->user()->username) }}"
                        required
                    >
                </div>

                <div class="form-group user-edit-wide">
                    <label for="email">Email:</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email', auth()->user()->email) }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password">Nueva contrasena:</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="new-password"
                    >
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar nueva contrasena:</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                    >
                </div>
            </div>

            <div class="user-edit-actions">
                <button type="submit" class="btn-submit">Guardar cambios</button>
            </div>
        </form>

        <section class="user-delete-zone">
            <div>
                <h2>Eliminar cuenta</h2>
                <p>Se borraran tu perfil, tus recetas, tus seguidores, los usuarios que sigues y los likes asociados.</p>
            </div>

            <form
                action="{{ route('users.destroyCurrent') }}"
                method="POST"
                onsubmit="return confirm('Esta accion eliminara tu cuenta y todos tus datos. ¿Quieres continuar?')"
            >
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger">Eliminar cuenta</button>
            </form>
        </section>
    </div>
@endsection
