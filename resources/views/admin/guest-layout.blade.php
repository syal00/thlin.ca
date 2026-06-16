<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') - {{ config('thlin.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/thlin.css') }}?v={{ @filemtime(public_path('css/thlin.css')) ?: '1' }}">
    @stack('head')
</head>
<body class="admin-login-page">
    @yield('content')
    @stack('scripts')
</body>
</html>
