@extends('layout.layout')

@section('title', __('messages.create_recipe'))

@section('content')

    <h1>{{ __('messages.create_recipe') }}</h1>

    <form action="{{ route('recetas.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label>{{ __('messages.recipe_name') }}:</label>
        <input type="text" name="name" required><br>

        <label>{{ __('messages.description') }}:</label>
        <textarea name="description" required></textarea><br>

        <label>{{ __('messages.time_minutes') }}:</label>
        <input type="number" name="time"><br>

        <label>{{ __('messages.tags') }}:</label>
        <input type="text" name="tags"><br>

        <label>{{ __('messages.visibility') }}:</label>
        <input type="checkbox" name="visibility"><br>

        <div class="recipe-upload-grid">
            <div class="recipe-upload-field">
                <label>{{ __('messages.upload_cover') }}:</label>
                <input type="file" name="image" accept="image/*" required>
            </div>
            <div class="recipe-upload-field">
                <label>{{ __('messages.upload_video') }}:</label>
                <input type="file" name="video" accept="video/mp4,video/quicktime,video/x-msvideo,video/webm">
                @error('video')
                    <span style="color: red;">{{ $message }}</span><br>
                @enderror
            </div>
        </div>

        @include('recipes.partials.ingredients-form')

        <button type="submit">{{ __('messages.save') }}</button>
    </form>

    <a href="{{ route('recetas.index') }}">{{ __('messages.back_list') }}</a>
@endsection
