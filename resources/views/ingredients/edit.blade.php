@extends('layout.layout')

@section('title', __('messages.edit_ingredient'))

@section('content')
    <section class="ingredients-page ingredients-form-page">
        <div class="ingredients-page-header">
            <div>
                <h1>{{ __('messages.edit_ingredient') }}</h1>
                <p>{{ __('messages.edit_ingredient_help') }}</p>
            </div>
        </div>

        <form class="ingredient-panel" action="{{ route('ingredientes.update', ['ingrediente' => $ingrediente->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <label>{{ __('messages.name') }}:</label>
            <input type="text" name="name" required value="{{ $ingrediente->name }}">

            <label>{{ __('messages.category') }}:</label>
            <select name="category" id="category">
                @foreach(\App\Models\Ingredient::CATEGORIES as $category)
                    <option value="{{ $category }}" {{ $ingrediente->category === $category ? 'selected' : '' }}>{{ $category }}</option>
                @endforeach
            </select>

            <button type="submit">{{ __('messages.save') }}</button>
        </form>
    </section>
@endsection
