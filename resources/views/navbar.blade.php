

<nav class="bg-white shadow-md mb-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800 flex items-center">
                    <img src="{{ asset('icon/web_logo.png') }}" alt="Logo" class="h-10 inline">
                    Laptop Store
                </a>
            </div>

            <!-- Menu (Căn giữa) -->
            <div class="flex-1 flex justify-center items-center space-x-8">
                <a href="/" class="text-gray-700 hover:text-blue-600">Trang chủ</a>
                <a href="{{route('laptops')}}" class="text-gray-700 hover:text-blue-600">Laptop</a>
                <a href="{{route('accessories')}}" class="text-gray-700 hover:text-blue-600">Phụ kiện</a>
                <a href="#" class="text-gray-700 hover:text-blue-600">Liên hệ</a>
            </div>

            <!-- Giỏ hàng & Tài khoản -->
            <div class="flex items-center space-x-4 ml-4">



                <!-- Tài khoản -->
                @auth
                    <!-- Giỏ hàng -->
                    <a href="{{route('user.cart')}}" class="relative">
                        🛒 <span id="cart-count" class="absolute top-0 right-0 bg-red-500 text-white text-xs px-2 rounded-full">
                              {{ auth()->check() ? $totalQuantity : 0 }}
                        </span>
                    </a>
                    <div class="relative" id="user-dropdown">
                        <button class="flex items-center space-x-2" id="dropdown-toggle">
                            <img src="{{ asset('avatar/'.Auth::user()->avatar ?? 'avatar/avatar_default.jpg') }}" alt="Avatar" class="w-8 h-8 rounded-full">
                            <span class="text-gray-700">{{ Auth::user()->full_name }}</span>
                        </button>

                        <div id="dropdown-menu" class="absolute right-0 w-48 mt-2 bg-white border rounded shadow-lg hidden z-50">
                            <a href="{{route('user.info')}}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Thông tin cá nhân</a>
                            <a href="{{route('user.info')}}" class="block px-4 py-2 text-gray-700 hover:bg-gray-200">Lịch sử giao dịch</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" id="logout-button" class="w-full text-left block px-4 py-2 text-red-600 hover:bg-gray-200">
                                    Đăng xuất
                                </button>
                            </form>
                        </div>

                    </div>
                @else
                    <!-- Khi chưa đăng nhập -->
                    <div class="flex items-center space-x-2">
                        <img src="{{ asset('avatar/avatar_default.jpg') }}" alt="Avatar" class="w-8 h-8 rounded-full">
                        <span class="text-gray-700 mr-5">Khách</span>
                        <a href="{{ route('login') }}" class="text-blue-600 ml-5">Đăng nhập</a>
                        /
                        <a href="{{ route('register_customer_submit') }}" class="text-blue-600">Đăng ký</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
        document.addEventListener("DOMContentLoaded", function () {
        const dropdownToggle = document.getElementById("dropdown-toggle");
        const dropdownMenu = document.getElementById("dropdown-menu");

        // Toggle hiển thị menu khi nhấn vào nút dropdown
        dropdownToggle.addEventListener("click", function (event) {
        event.stopPropagation(); // Ngăn sự kiện click lan ra ngoài
        dropdownMenu.classList.toggle("hidden");
    });

        // Ẩn dropdown khi click ra ngoài
        document.addEventListener("click", function (event) {
        if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.classList.add("hidden");
    }
    });
    });


            function updateCartQuantity() {
            fetch("{{ url('/cart/quantity') }}") // Gửi yêu cầu lấy số lượng giỏ hàng
                .then(response => response.json()) // Chuyển đổi phản hồi thành JSON
                .then(data => {
                    document.getElementById("cart-count").innerText = data.totalQuantity; // Cập nhật số lượng hiển thị
                })
                .catch(error => console.error("Lỗi khi cập nhật giỏ hàng:", error));
        }

            document.addEventListener("DOMContentLoaded", function () {
            updateCartQuantity(); // Cập nhật số lượng khi tải trang

            document.querySelectorAll("form[action*='cart/add']").forEach(form => {
            form.addEventListener("submit", function (event) {
            event.preventDefault(); // Ngăn chặn form gửi yêu cầu HTTP thông thường
            let formData = new FormData(this);

            fetch(this.action, {
            method: "POST",
            body: formData,
            headers: {
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN": document.querySelector("meta[name='csrf-token']").getAttribute("content")
        }
        })
            .then(response => response.json())
            .then(data => {
            if (data.success) {
            updateCartQuantity(); // Cập nhật số lượng giỏ hàng
                // Tạo một div thông báo mới
                let notification = document.createElement("div");
                notification.className = "bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 alert fixed top-5 right-5 shadow-md";
                notification.innerHTML = `<p>${data.message}</p>`;

                document.body.appendChild(notification);

                // Tự động ẩn sau 5 giây
                setTimeout(() => notification.remove(), 5000);
        } else if (data.message) {
                // Tạo một div thông báo mới
                let notification = document.createElement("div");
                notification.className = "bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 alert fixed top-5 right-5 shadow-md";
                notification.innerHTML = `<p>${data.message}</p>`;

                document.body.appendChild(notification);

                // Tự động ẩn sau 5 giây
                setTimeout(() => notification.remove(), 5000);
        }
        })
            .catch(error => console.error("Lỗi khi thêm giỏ hàng:", error));
        });
        });
        });

            // Khi người dùng logout, đặt số lượng giỏ hàng về 0
            document.querySelector("#logout-button")?.addEventListener("click", function() {
            document.getElementById("cart-count").innerText = "0";
        });
</script>
