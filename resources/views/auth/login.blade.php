<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Thêm favicon -->
    <link rel="icon" type="image/png" href="{{ asset('icon/web_logo.png') }}">
</head>
<body class="bg-gray-100">

<!-- Navbar -->
<nav class="bg-white shadow-md p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="{{route('home')}}" class="text-xl font-bold text-blue-600">Web Laptop</a>
    </div>
</nav>

<!-- Form Đăng nhập -->
<div class="flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-semibold text-center text-gray-700 mb-6">Đăng nhập</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-600 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-600 p-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login_submit') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-600 mb-1">Email</label>
                <input type="email" name="email" class="w-full p-3 border rounded" placeholder="Nhập email của bạn" required>
            </div>

            <div class="mb-4 relative">
                <label class="block text-gray-600 mb-1">Mật khẩu</label>
                <input type="password" id="password" name="password" class="w-full p-3 border rounded pr-10" placeholder="Nhập mật khẩu" required>

                <!-- Icon/Emoji khỉ mở mắt / che mắt -->
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-3 top-5 flex items-center justify-center text-gray-500 text-3xl">
                    🐵
                </button>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded hover:bg-blue-700">
                Đăng nhập
            </button>
        </form>

        <div class="flex justify-between items-center mt-4">
            <a href="{{route('password.request')}}" class="text-blue-500 hover:underline">Quên mật khẩu?</a>
            <a href="{{route('register')}}" class="text-blue-500 hover:underline">Đăng ký</a>
        </div>

    </div>
</div>

<!-- Script để xử lý bật/tắt hiển thị mật khẩu -->
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordField = document.getElementById('password');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            this.textContent = '🙈'; // Khỉ che mắt khi hiển thị mật khẩu
        } else {
            passwordField.type = 'password';
            this.textContent = '🐵'; // Khỉ mở mắt khi ẩn mật khẩu
        }
    });
</script>

</body>
</html>
