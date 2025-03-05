<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
<div class="flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-semibold text-center text-gray-700 mb-6">Quên Mật Khẩu</h2>

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

        <form action="{{ route('password.verify') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-600 mb-1">Nhập email của bạn</label>
                <input type="email" name="email" class="w-full p-3 border rounded" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded hover:bg-blue-700">
                Xác Nhận Email
            </button>
        </form>
    </div>
</div>
</body>
</html>
