<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    @include('layout.partials.nav')
    @yield('content')
    @include('layout.partials.footer')
</body>
</html>
