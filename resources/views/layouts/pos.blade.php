<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kasir | Toko Campalagian</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://unpkg.com/lucide@latest"></script>
    @stack('css')
</head>
<body>
    @yield('content')
    <script>lucide.createIcons();</script>
    @stack('js')
</body>
</html>
