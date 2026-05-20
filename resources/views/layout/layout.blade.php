<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('styles/styles.css') }}">
</head>
<body data-no-results-text="{{ __('messages.no_results') }}" data-delete-comment-confirm="{{ __('messages.delete_comment_confirm') }}" data-delete-comment-error="{{ __('messages.delete_comment_error') }}">
    <header class="app-header">
        @include('layout.partials.nav')
    </header>
    <aside class="sidebar-left" id="mobile-menu">
        @include('layout.partials.aside')
    </aside>
    <main class="main-content">
        <div class="page-content">
            @yield('content')
        </div>
        @include('layout.partials.footer')
    </main>
    <script src="{{ asset('javascript/main.js') }}"></script>
</body>
</html>
