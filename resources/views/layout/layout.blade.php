<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('styles/styles.css') }}">
</head>
<body>
    <header class="app-header">
        @include('layout.partials.nav')
    </header>

    <aside class="sidebar-left">
        @include('layout.partials.aside')
    </aside>

    <main class="main-content">
        @yield('content')

        @include('layout.partials.footer')
    </main>

    <script src="{{ asset('javascript/main.js') }}"></script>
</body>
</html>
