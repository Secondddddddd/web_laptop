<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laptop Store')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

@include('navbar')

@yield('content')

@include('footer')
</body>
</html>
