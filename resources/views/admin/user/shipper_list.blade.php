@extends('admin.admin_dashboard')

@section('title', 'Danh sách Shipper')

@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-4">Danh sách Shipper</h2>

        <!-- Bộ lọc trạng thái shipper -->
        <div class="ml-5 w-64 flex flex-row p-1 text-center mb-4">
            <button class="mr-2 btn btn-info filter-btn active" data-status="pending">
                Chờ xác nhận
            </button>
            <button class="btn btn-info btn-outline filter-btn" data-status="active">
                Đã xác nhận
            </button>
        </div>

        <x-alert-result />

        <!-- Bảng Grid.js -->
        <div id="shipperTable"></div>
    </div>

    <!-- Modal hiển thị chi tiết Shipper -->
    <div id="shipperDetailModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 max-w-lg relative">
            <!-- Nút đóng -->
            <button
                class="absolute top-2 right-2 text-gray-500 hover:text-red-500 text-2xl font-bold"
                onclick="closeShipperModal()"
            >
                &times;
            </button>

            <h3 class="text-xl font-semibold mb-4">Chi tiết Shipper</h3>
            <div id="shipperDetailContent">
                <!-- Nội dung chi tiết shipper sẽ được tải ở đây -->
                <p>Đang tải dữ liệu...</p>
            </div>

            <div class="mt-4 text-right">
                <button onclick="closeShipperModal()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                    Đóng
                </button>
            </div>
        </div>
    </div>

    <!-- Grid.js (sử dụng module được import từ app.js nếu có) -->
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {
            let currentStatus = "pending";
            const shipperTable = document.getElementById("shipperTable");
            let gridTable = null;

            async function loadShippers(status) {
                try {
                    const response = await fetch(`/api/admin/shippers/json?status=${status}`);
                    const data = await response.json();

                    // Nếu dữ liệu trả về không phải là mảng, hiển thị thông báo lỗi
                    if (!Array.isArray(data)) {
                        shipperTable.innerHTML = `<div class="p-5 text-center text-red-500">Có lỗi xảy ra khi tải dữ liệu</div>`;
                        return;
                    }

                    shipperTable.innerHTML = "";
                    if(data.length === 0){
                        shipperTable.innerHTML = `<div class="p-5 text-center text-gray-500">Danh sách trống</div>`;
                        return;
                    }

                    // Format dữ liệu cho Grid.js
                    const formattedData = data.map((shipper, index) => {
                        let actionsHtml = "";
                        if (shipper.status === 'pending') {
                            // Trạng thái chờ xác nhận: hiển thị nút Chấp nhận, Xem chi tiết, Hủy bỏ
                            actionsHtml = `
                             <button class="text-blue-500 hover:underline mr-2" onclick="viewShipperDetail(${shipper.user_id})">
                                    Xem chi tiết
                                </button>
                            <button class="text-green-500 hover:underline mr-2" onclick="activateShipper(${shipper.user_id})">
                                    Chấp nhận
                                </button>
                            <form action="/admin/shippers/disable/${shipper.user_id}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn hủy shipper này?');">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')}">
                                <button type="submit" class="text-red-500 hover:underline">Hủy bỏ</button>
                            </form>
                        `;
                        } else if (shipper.status === 'active') {
                            // Trạng thái đã xác nhận: hiển thị nút Xem chi tiết và Hủy bỏ
                            actionsHtml = `
                             <button class="text-blue-500 hover:underline mr-2" onclick="viewShipperDetail(${shipper.user_id})">
                                    Xem chi tiết
                                </button>
                            <form action="/admin/shippers/disable/${shipper.user_id}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn hủy shipper này?');">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')}">
                                <button type="submit" class="text-red-500 hover:underline">Hủy bỏ</button>
                            </form>
                        `;
                        }
                        return [
                            index + 1,
                            shipper.full_name,
                            shipper.email,
                            shipper.phone || "N/A",
                            new Date(shipper.created_at).toLocaleDateString("vi-VN"),
                            html(actionsHtml)
                        ];
                    });

                    // Nếu đã khởi tạo grid, cập nhật dữ liệu, ngược lại khởi tạo grid
                    if (gridTable) {
                        gridTable.updateConfig({ data: formattedData }).forceRender();
                    } else {
                        gridTable = new Grid({
                            columns: [
                                { id: "index", name: "#" },
                                { id: "full_name", name: "Họ và Tên" },
                                { id: "email", name: "Email" },
                                { id: "phone", name: "Số điện thoại" },
                                { id: "created_at", name: "Ngày tạo" },
                                { id: "actions", name: "Hành động" }
                            ],
                            data: formattedData,
                            pagination: { enabled: true, limit: 10 },
                            sort: true,
                            search: true,
                            language: {
                                search: { placeholder: "Tìm kiếm..." },
                                pagination: { previous: "Trước", next: "Sau", showing: "Hiển thị", results: () => "kết quả" }
                            }
                        }).render(shipperTable);
                    }
                } catch (error) {
                    console.error("Error fetching shippers:", error);
                    shipperTable.innerHTML = `<div class="p-5 text-center text-red-500">Có lỗi xảy ra khi tải dữ liệu</div>`;
                }
            }

            // Gán sự kiện cho các nút filter
            document.querySelectorAll(".filter-btn").forEach(button => {
                button.addEventListener("click", function () {
                    // Cập nhật giao diện các nút filter
                    document.querySelectorAll(".filter-btn").forEach(btn => {
                        btn.classList.remove("active");
                        btn.classList.add("btn-outline");
                    });
                    currentStatus = this.dataset.status;
                    this.classList.add("active");
                    this.classList.remove("btn-outline");

                    // Tải lại dữ liệu shipper theo trạng thái mới
                    loadShippers(currentStatus);
                });
            });

            // Load dữ liệu ban đầu với trạng thái mặc định
            loadShippers(currentStatus);
        });

        // Hàm hiển thị modal + gọi API lấy chi tiết Shipper
        window.viewShipperDetail = async function(shipperId) {
            const modal = document.getElementById("shipperDetailModal");
            const contentDiv = document.getElementById("shipperDetailContent");

            // Hiển thị modal
            modal.classList.remove("hidden");
            contentDiv.innerHTML = "<p>Đang tải dữ liệu...</p>";

            try {
                const res = await fetch(`/api/admin/shippers/detail/${shipperId}`);
                if (!res.ok) {
                    throw new Error("Không thể tải chi tiết Shipper");
                }
                const data = await res.json();

                let html = `
                    <p><strong>ID:</strong> ${data.user_id}</p>
                    <p><strong>Họ và tên:</strong> ${data.full_name}</p>
                    <p><strong>Email:</strong> ${data.email}</p>
                    <p><strong>Số điện thoại:</strong> ${data.phone || "N/A"}</p>
                    <p><strong>Trạng thái:</strong> ${data.status}</p>
                    <p><strong>Ngày tạo:</strong> ${new Date(data.created_at).toLocaleDateString("vi-VN")}</p>
                `;

                if (data.avatar && data.avatar !== "avatar_default.jpg") {
                    html += `
                        <div class="mt-3 flex items-center">
                            <img src="/avatar/${data.avatar}" alt="Avatar Shipper" class="w-16 h-16 rounded-full mr-3" />
                            <span>Avatar</span>
                        </div>
                    `;
                }else{
                    html += `
                        <div class="mt-3 flex items-center">
                            <span>Avatar</span>
                            <img src="/avatar/avatar_default.jpg" alt="Avatar Shipper" class="w-16 h-16 rounded-full mr-3" />
                        </div>
                    `;
                }

                contentDiv.innerHTML = html;
            } catch (error) {
                console.error(error);
                contentDiv.innerHTML = `<p class="text-red-500">${error.message}</p>`;
            }
        }

        // Hàm đóng modal
        window.closeShipperModal = function() {
            document.getElementById("shipperDetailModal").classList.add("hidden");
        }

        function activateShipper(shipperId) {
            if (!confirm("Bạn có chắc muốn kích hoạt tài khoản shipper này?")) return;

            window.location.href=`/admin/shippers/activate/${shipperId}`;
        }

        window.activateShipper = activateShipper;
    </script>
@endsection
