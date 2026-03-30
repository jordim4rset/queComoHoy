@extends('layout.laoyut');

@section('title', 'Show Ingredients')

@section('content')

    @foreach ($ingr as $var)
        <p>{{ $var }}</p>
    @endforeach
@endsection

