<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laptop Store')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Thêm favicon -->
    <link rel="icon" type="image/png" href="{{ asset('icon/web_logo.png') }}">
</head>
<body>

@include('navbar')

@yield('content')

@include('footer')
</body>
</html>
