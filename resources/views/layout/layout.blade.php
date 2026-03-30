<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('styles/styles.css') }}">
</head>
<body>
    @include('layout.partials.nav')
    @include('layout.partials.aside')
    @yield('content')
    @include('layout.partials.footer')
</body>
</html>
