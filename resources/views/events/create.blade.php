@extends('layout.layout')

@section('title', __('messages.create_event'))

@section('content')
    <h1>{{ __('messages.create_event') }}</h1>

    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label>{{ __('messages.event_title') }}:</label>
        <input type="text" name="title" value="{{ old('title') }}"><br>
        @error('title')
            <span class="error-message">{{ $message }}</span><br>
        @enderror

        <label>{{ __('messages.description') }}:</label>
        <textarea name="description">{{ old('description') }}</textarea><br>
        @error('description')
            <span class="error-message">{{ $message }}</span><br>
        @enderror

        <label>{{ __('messages.images_multiple') }}:</label>
        <input type="file" name="images[]" multiple><br>

        <label>{{ __('messages.active') }}:</label>
        <input type="checkbox" name="active"><br>

        <button type="submit">{{ __('messages.save') }}</button>
    </form>

    <a href="{{ route('events.index') }}">{{ __('messages.back_list') }}</a>
@endsection
