@extends('layout.layout')

@section('title', 'Show Ingredients')

@section('content')

    @foreach ($ingrediente as $var)
        <p>{{ $var }}</p>
    @endforeach
@endsection
