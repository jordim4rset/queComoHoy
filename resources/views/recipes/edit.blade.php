@extends('layout.layout')

@section('title', __('messages.edit_recipe'))

@section('content')
<h1>{{ __('messages.edit_recipe') }}</h1>

<form action="{{ route('recetas.update', ['receta' => $receta->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <label>{{ __('messages.recipe_name') }}:</label>
    <input type="text" name="name" value="{{ $receta->name }}" required><br>

    <label>{{ __('messages.description') }}:</label>
    <textarea name="description" required>{{ $receta->description }}</textarea><br>

    <label>{{ __('messages.time_minutes') }}:</label>
    <input type="number" name="time" value="{{ $receta->time }}"><br>

    <label>{{ __('messages.tags') }}:</label>
    <input type="text" name="tags" value="{{ $receta->tags }}"><br>

    <label>{{ __('messages.visibility') }}:</label>
    <input type="checkbox" name="visibility" {{ $receta->visibility ? 'checked' : '' }}><br>

    <div class="recipe-upload-grid">
        <div class="recipe-upload-field">
            <label>{{ __('messages.upload_cover') }}:</label>
            <input type="file" name="image" accept="image/*">
            @if($receta->image)
                <small>{{ __('messages.cover_loaded') }}</small>
            @endif
            @error('image')
                <span class="error-message">{{ $message }}</span><br>
            @enderror
        </div>
        <div class="recipe-upload-field">
            <label>{{ __('messages.upload_video') }}:</label>
            <input type="file" name="video" accept="video/mp4,video/quicktime,video/x-msvideo,video/webm">
            @if($receta->video)
                <small>{{ __('messages.video_loaded') }}</small>
            @endif
            @error('video')
                <span class="error-message">{{ $message }}</span><br>
            @enderror
        </div>
    </div>

    @include('recipes.partials.ingredients-form')

    <button type="submit">{{ __('messages.update') }}</button>
</form>

<a href="{{ route('recetas.show', ['receta' => $receta->id]) }}">{{ __('messages.cancel') }}</a>
@endsection
