<nav class="bg-white shadow-md mb-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="#" class="text-xl font-bold text-gray-800 flex items-center">
                    <img src="" alt="Logo" class="h-10 inline">
                    Laptop Store
                </a>
            </div>

            <!-- Menu (Căn giữa) -->
            <div class="flex-1 flex justify-center items-center space-x-8">
                <a href="#" class="text-gray-700 hover:text-blue-600">Trang chủ</a>
                <a href="#" class="text-gray-700 hover:text-blue-600">Sản phẩm</a>
                <a href="#" class="text-gray-700 hover:text-blue-600">Phụ kiện</a>
                <a href="#" class="text-gray-700 hover:text-blue-600">Liên hệ</a>
            </div>

            <!-- Thanh tìm kiếm (Căn giữa) -->
            <div class="relative flex items-center">
                <input type="text" placeholder="Tìm kiếm..." class="border rounded-lg px-3 py-2">
                <button class="absolute right-2 top-2 text-gray-500">
                    🔍
                </button>
            </div>

            <!-- Giỏ hàng & Tài khoản -->
            <div class="flex items-center space-x-4 ml-4">
                <a href="#" class="relative">
                    🛒 <span class="absolute top-0 right-0 bg-red-500 text-white text-xs px-2 rounded-full">3</span>
                </a>

                @auth
                    <a href="#" class="text-gray-700"> Admin</a>
                    <form action="#" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-red-500">Đăng xuất</button>
                    </form>
                @else
                    <p>
                        <a href="#" class="text-blue-600">Đăng nhập</a>
                        /
                        <a href="#" class="text-blue-600">Đăng ký</a>
                    </p>
                @endauth
            </div>
        </div>
    </div>
</nav>
