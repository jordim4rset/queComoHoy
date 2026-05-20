@extends('layout.layout')

@section('title', __('messages.privacy_policy'))

@section('content')
    <section class="legal-page">
        <h1>{{ __('messages.privacy_policy') }}</h1>

        <p>{{ __('messages.privacy_intro') }}</p>

        <h2>{{ __('messages.privacy_data_title') }}</h2>
        <p>{{ __('messages.privacy_data_text') }}</p>

        <h2>{{ __('messages.privacy_purpose_title') }}</h2>
        <p>{{ __('messages.privacy_purpose_text') }}</p>

        <h2>{{ __('messages.privacy_storage_title') }}</h2>
        <p>{{ __('messages.privacy_storage_text') }}</p>

        <h2>{{ __('messages.legal_pending_title') }}</h2>
        <p>{{ __('messages.privacy_pending_text') }}</p>
    </section>
@endsection
