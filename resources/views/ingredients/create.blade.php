@extends('layout.layout')

@section('title', __('messages.create_ingredient'))

@section('content')
    <section class="ingredients-page ingredients-form-page">
        <div class="ingredients-page-header">
            <div>
                <h1>{{ __('messages.create_ingredient') }}</h1>
                <p>{{ __('messages.create_ingredient_help') }}</p>
            </div>
        </div>

        <form class="form-create-ingr ingredient-panel" action="{{ route('ingredientes.store') }}" method="POST">
            @csrf
            @if ($errors->any())
                <div class="form-errors">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <label>{{ __('messages.name') }}:</label>
            <input type="text" name="name" required value="{{ old('name') }}">

            <label>{{ __('messages.category') }}:</label>
            <select name="category" id="category">
                @foreach(\App\Models\Ingredient::CATEGORIES as $category)
                    <option value="{{ $category }}" {{ old('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                @endforeach
            </select>

            <button type="submit">{{ __('messages.save') }}</button>
        </form>
    </section>
@endsection
