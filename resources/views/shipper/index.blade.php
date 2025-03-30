@extends('index')

@section('title', 'Danh sách đơn hàng')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Danh sách đơn hàng của bạn</h2>

    <!-- Bộ lọc trạng thái đơn hàng của shipper -->
    <div class="ml-5 w-64 flex flex-row p-1 text-center mb-4">
        <button class="mr-2 btn btn-info filter-btn active" data-status="processing">
            Chưa giao hàng
        </button>
        <button class="btn btn-info btn-outline filter-btn" data-status="delivered">
            Đã giao hàng
        </button>
    </div>

    <x-alert-result />

    <!-- Bảng Grid.js hiển thị đơn hàng shipper -->
    <div id="orders-table"></div>

    <!-- Modal chi tiết đơn hàng -->
    <div id="orderDetailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-2/3 max-w-3xl">
            <h3 class="text-lg font-bold mb-2">Chi tiết đơn hàng</h3>
            <div id="orderDetailContent">
                <p class="text-gray-600">Đang tải...</p>
            </div>
            <button id="closeModal" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded">Đóng</button>
        </div>
    </div>


    <!-- Grid.js script -->
    <script type="module">
        document.addEventListener("DOMContentLoaded", function () {

            let currentStatus = "processing";
            const ordersTable = document.getElementById("orders-table");
            let gridTable = null;
            const orderDetailModal = document.getElementById("orderDetailModal");
            const orderDetailContent = document.getElementById("orderDetailContent");
            const closeModal = document.getElementById("closeModal");

            async function loadOrders(status) {
                try {
                    const url = `/api/shipper/orders?status=${status}`;
                    const response = await fetch(url);
                    const data = await response.json();

                    if (!Array.isArray(data)) {
                        ordersTable.innerHTML = `<div class="p-5 text-center text-red-500">Có lỗi xảy ra khi tải dữ liệu</div>`;
                        return;
                    }

                    ordersTable.innerHTML = "";
                    if (data.length === 0) {
                        ordersTable.innerHTML = `<div class="p-5 text-center text-gray-500 text-lg font-semibold border border-gray-300 rounded-lg">
                Không có đơn hàng nào
            </div>`;
                        return;
                    }

                    const formattedData = data.map((order, index) => {
                        let actionsHtml = "";
                        if (order.order_status === 'processing' && order.shipper_id === null) {
                            actionsHtml = `
                    <form method="POST" action="/shipper/orders/${order.order_id}/accept" style="display:inline;">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                        <button class="btn btn-primary">Nhận đơn</button>
                    </form>
                    <button class="btn btn-warning view-order-btn" data-id="${order.order_id}">Xem chi tiết</button>
                        `;
                        }  else if (order.order_status === 'delivered') {
                            actionsHtml = "Đã giao hàng";
                        }

                        return [
                            index + 1,
                            order.user_full_name ?? "Không rõ",
                            new Intl.NumberFormat("vi-VN").format(order.total_price) + " VND",
                            order.order_status,
                            html(actionsHtml)
                        ];
                    });

                    if (gridTable) {
                        gridTable.updateConfig({ data: formattedData }).forceRender();
                    } else {
                        gridTable = new Grid({
                            columns: [
                                { id: "index", name: "#" },
                                { id: "user", name: "Khách hàng" },
                                { id: "total_price", name: "Tổng tiền" },
                                { id: "order_status", name: "Trạng thái" },
                                { id: "actions", name: "Hành động" }
                            ],
                            data: formattedData,
                            pagination: { enabled: true, limit: 10 },
                            sort: true,
                            search: true,
                            language: {
                                search: { placeholder: "🔍 Tìm kiếm đơn hàng..." },
                                pagination: { previous: "⬅️", next: "➡️", showing: "Hiển thị", results: () => "đơn hàng" },
                                loading: "Đang tải...",
                                noRecordsFound: "Không tìm thấy đơn hàng nào",
                                error: "Có lỗi xảy ra khi tải dữ liệu"
                            }
                        }).render(ordersTable);
                    }
                } catch (error) {
                    console.error("Error fetching orders:", error);
                    ordersTable.innerHTML = `<div class="p-5 text-center text-red-500">Có lỗi xảy ra khi tải dữ liệu</div>`;
                }
            }


            document.querySelectorAll(".filter-btn").forEach((button) => {
                button.addEventListener("click", function () {
                    document.querySelectorAll(".filter-btn").forEach((btn) => {
                        btn.classList.remove("active");
                        btn.classList.add("btn-outline");
                    });
                    currentStatus = this.dataset.status;
                    console.log("Current status changed to:", currentStatus);
                    this.classList.add("active");
                    this.classList.remove("btn-outline");
                    loadOrders(currentStatus);
                });
            });

            loadOrders(currentStatus);

            document.addEventListener("click", async function (event) {
                if (event.target && event.target.classList.contains("view-order-btn")) {
                    const orderId = event.target.dataset.id;
                    orderDetailModal.classList.remove("hidden");
                    orderDetailContent.innerHTML = "<p class='text-gray-600'>Đang tải dữ liệu...</p>";

                    try {
                        const response = await fetch(`/api/shipper/orders/${orderId}/show`);
                        if (!response.ok) {
                            throw new Error("Không thể tải chi tiết đơn hàng.");
                        }
                        const order = await response.json();

                        let html = `
                    <p><strong>Mã đơn hàng:</strong> #${order.order_id}</p>
                    <p><strong>Khách hàng:</strong> ${order.user_full_name || "Không rõ"}</p>
                    <p><strong>Tổng tiền:</strong> ${new Intl.NumberFormat("vi-VN").format(order.total_price)} VND</p>
                    <p><strong>Trạng thái:</strong> ${order.order_status}</p>
                    <p><strong>Ngày đặt hàng:</strong> ${order.created_at}</p>
                `;

                        if (order.orderDetails && order.orderDetails.length > 0) {
                            html += `<h4 class="mt-4 font-bold">Danh sách sản phẩm:</h4>`;
                            html += `<table class="w-full border-collapse mt-2">
                                <thead>
                                    <tr class="bg-gray-200">
                                        <th class="p-2">Sản phẩm</th>
                                        <th class="p-2">Số lượng</th>
                                        <th class="p-2">Đơn giá</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                            order.orderDetails.forEach(detail => {
                                html += `<tr class="border-b">
                                    <td class="p-2 flex items-center">
                                        <img src="/img/${detail.image_url}" alt="${detail.product_name}" class="w-12 h-12 mr-2">
                                        <span>${detail.product_name}</span>
                                    </td>
                                    <td class="p-2">${detail.quantity}</td>
                                    <td class="p-2">${detail.unit_price} VND</td>
                                </tr>`;
                            });
                            html += `   </tbody>
                            </table>`;
                        }

                        orderDetailContent.innerHTML = html;
                    } catch (error) {
                        console.error(error);
                        orderDetailContent.innerHTML = `<p class="text-red-500">${error.message}</p>`;
                    }
                }
            });

            closeModal.addEventListener("click", function () {
                orderDetailModal.classList.add("hidden");
            });

            fetch(`/api/shipper/orders/34/show`)
                .then(response => response.json())
                .then(order => {
                    console.log(order); // Kiểm tra dữ liệu nhận được từ API
                })
                .catch(error => console.error("Lỗi khi lấy dữ liệu đơn hàng:", error));

        });

    </script>
@endsection
