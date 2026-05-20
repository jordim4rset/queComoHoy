@extends('layout.layout')

@section('title', __('messages.cookies_policy'))

@section('content')
    <section class="legal-page">
        <h1>{{ __('messages.cookies_policy') }}</h1>

        <p>{{ __('messages.cookies_intro') }}</p>

        <h2>{{ __('messages.cookies_what_title') }}</h2>
        <p>{{ __('messages.cookies_what_text') }}</p>

        <h2>{{ __('messages.cookies_required_title') }}</h2>
        <p>{{ __('messages.cookies_required_text') }}</p>

        <h2>{{ __('messages.cookies_third_party_title') }}</h2>
        <p>{{ __('messages.cookies_third_party_text') }}</p>

        <h2>{{ __('messages.cookies_management_title') }}</h2>
        <p>{{ __('messages.cookies_management_text') }}</p>
    </section>
@endsection
