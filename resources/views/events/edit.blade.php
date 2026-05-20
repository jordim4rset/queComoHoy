@extends('layout.layout')

@section('title', __('messages.edit_event'))

@section('content')
    <h1>{{ __('messages.edit_event') }}</h1>

    <form action="{{ route('events.update', ['event' => $event->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>{{ __('messages.event_name') }}:</label>
        <input type="text" name="name" value="{{ old('name', $event->name) }}"><br>

        <label>{{ __('messages.event_title') }} ({{ __('messages.optional') }}):</label>
        <input type="text" name="title" value="{{ old('title', $event->title) }}"><br>
        @error('title')
            <span style="color: red;">{{ $message }}</span><br>
        @enderror

        <label>{{ __('messages.description') }}:</label>
        <textarea name="description">{{ old('description', $event->description) }}</textarea><br>
        @error('description')
            <span style="color: red;">{{ $message }}</span><br>
        @enderror

        <label>{{ __('messages.current_images') }}:</label>
        <div>
            @if($event->images)
                @foreach($event->images as $img)
                    <img src="{{ asset('storage/' . $img) }}" alt="" style="height:80px;margin:4px">
                @endforeach
            @endif
        </div>

        <label>{{ __('messages.upload_more_images') }}:</label>
        <input type="file" name="images[]" multiple><br>

        <label>{{ __('messages.active') }}:</label>
        <input type="checkbox" name="active" {{ $event->active ? 'checked' : '' }}><br>

        <button type="submit">{{ __('messages.update') }}</button>
    </form>

    <a href="{{ route('events.index') }}">{{ __('messages.back') }}</a>
@endsection
