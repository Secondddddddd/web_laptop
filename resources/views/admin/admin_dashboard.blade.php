<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Laptop Store')</title>
    @vite(['resources/css/app.css','resources/css/admin_dashboard.css', 'resources/js/app.js'])
    @vite(['resources/js/admin_dashboard.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
<div class="container flex h-screen w-screen">
    <!-- Sidebar trái -->
    <div class="w-1/4 bg-black-200">
        <div class="">
            <a href="/" class="border-b-2 flex items-center space-x-2 p-2">
            <img src="{{asset('icon/web_logo.png')}}" alt="Web logo" class="w-10 h-10 rounded-full">
            <p class="text-lg font-semibold text-gray-500">
                Web Laptop
            </p>
            </a>
        </div>
        <div class="border-b-2 flex items-center space-x-2 p-2">
            <img src="{{asset('avatar/geats_chibi.jpg')}}" alt="Web logo" class="w-12 h-12 rounded-full border-2 border-black">
            <p class="text-lg font-semibold text-gray-500 ">Admin</p>
        </div>
        <div class="mt-5">
            <p class="text-xl font-bold text-gray-600">Dashboard</p>

            <div class="flex items-center border-b-2 border-t-2 space-x-3 p-3 products-manage dashboard-item">
                <p class="text-xl font-medium">Quản lý Sản phẩm</p>
                <i class="fa-solid fa-box-open text-2xl"></i>
            </div>

            <div class="flex items-center border-b-2 border-t-2 space-x-3 p-3 categories-manage dashboard-item">
                <p class="text-xl font-medium">Quản lý thể loại</p>
                <i class="fa-solid fa-list text-2xl"></i>
            </div>

            <div class="flex items-center border-b-2 border-t-2 space-x-3 p-3 accounts-manage dashboard-item">
                <p class="text-xl font-medium">Quản lý tài khoản</p>
                <i class="fa-solid fa-user text-2xl"></i>
            </div>

            <div class="flex items-center border-b-2 border-t-2 space-x-3 p-3 suppliers-manage dashboard-item">
                <p class="text-xl font-medium">Nhà cung cấp</p>
                <i class="fa-solid fa-truck text-2xl"></i>
            </div>
            <div class="flex items-center border-b-2 border-t-2 space-x-3 p-3 orders-manage dashboard-item">
                <p class="text-xl font-medium">Quản lý đơn hàng</p>
                <i class="fa-solid fa-receipt text-2xl"></i>
            </div>
            <div class="flex items-center border-b-2 border-t-2 space-x-3 p-3 shipper-list dashboard-item">
                <p class="text-xl font-medium">Quản lý tài khoản shipper</p>
                <i class="fa-solid fa-receipt text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Nội dung chính -->
    <div class="w-3/4 flex flex-col">
        <!-- Thanh trên cùng -->
        <div class="">
            <nav class="border-b-2 mb-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16">
                        <!-- Menu (Căn giữa) -->
                        <div class="flex-1 flex items-center space-x-8">
                            <a href="/" class="text-gray-700 hover:text-blue-600">Trang chủ</a>
                            <a href="{{route('laptops')}}" class="text-gray-700 hover:text-blue-600">Laptop</a>
                            <a href="{{route('accessories')}}" class="text-gray-700 hover:text-blue-600">Phụ kiện</a>
                            <a href="#" class="text-gray-700 hover:text-blue-600">Liên hệ</a>
                        </div>
                    </div>
                </div>
            </nav>

        </div>

        <!-- Khu vực nội dung chính -->
        <div class="flex-1">
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>
