<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Thêm favicon -->
    <link rel="icon" type="image/png" href="{{ asset('icon/web_logo.png') }}">
</head>
<body class="bg-gray-100">

<!-- Navbar -->
<nav class="bg-white shadow-md p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600">Web Laptop</a>
    </div>
</nav>

@if(session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
@endif


<!-- Form Đăng ký -->
<div class="flex items-center justify-center mt-5">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-semibold text-center text-gray-700 mb-6">Đăng ký tài khoản shipper</h2>

        <x-alert-result />

        <form action="{{ route('register_shipper_submit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-600 mb-1">Họ và Tên</label>
                <input type="text" name="full_name" class="w-full p-3 border rounded" placeholder="Nhập họ và tên" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-600 mb-1">Email</label>
                <input type="email" name="email" class="w-full p-3 border rounded" placeholder="Nhập email" required>
            </div>

            <div class="mb-4 relative">
                <label class="block text-gray-600 mb-1">Mật khẩu</label>
                <input type="password" id="password" name="password" class="password_input w-full p-3 border rounded pr-10" placeholder="Nhập mật khẩu" required>
                <button type="button" class="toggle-password-btn absolute inset-y-0 right-3 top-5 flex items-center justify-center text-gray-500 text-3xl">
                    🐵
                </button>
            </div>

            <div class="mb-4 relative">
                <label class="block text-gray-600 mb-1">Xác nhận mật khẩu</label>
                <input type="password" name="password_confirmation" class="password_input w-full p-3 border rounded" placeholder="Nhập lại mật khẩu" required>
                <button type="button" class="toggle-password-btn absolute inset-y-0 right-3 top-5 flex items-center justify-center text-gray-500 text-3xl">
                    🐵
                </button>
            </div>

            <div class="mb-4">
                <label class="block text-gray-600 mb-1">Số điện thoại</label>
                <input type="text" name="phone" class="w-full p-3 border rounded" placeholder="Nhập số điện thoại (tùy chọn)" required>
            </div>


            <div class="mb-4">
                <label class="block text-gray-600 mb-1">Ảnh đại diện (tùy chọn)</label>
                <input type="file" name="avatar" class="w-full p-3 border rounded">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded hover:bg-blue-700">
                Đăng ký
            </button>
        </form>

        <p class="mt-4 text-center text-gray-600">
            Đã có tài khoản? <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Đăng nhập</a>
        </p>
    </div>
</div>

<script>
    document.querySelectorAll('.toggle-password-btn').forEach((button) => {
        button.addEventListener('click', function () {
            // Tìm input password trong cùng container cha
            const container = this.closest('.relative');
            const passwordInput = container.querySelector('input[type="password"], input[type="text"]');

            // Toggle type và icon
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                this.textContent = '🐵';
            }
        });
    });
</script>

</body>
</html>
