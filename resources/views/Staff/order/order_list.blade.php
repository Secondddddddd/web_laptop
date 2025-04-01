@extends('staff.staff_dashboard')

@section('title', 'Order List')

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Danh sách đơn hàng</h2>

    <x-alert-result />

    <div class="ml-5 w-64 flex flex-row p-1 text-center mb-4">
        <button class="mr-2 btn btn-info filter-btn active" data-status="pending">Chờ xác nhận</button>
        <button class="mr-2 btn btn-info btn-outline filter-btn" data-status="processing">Đang xử lý</button>
        <button class="mr-2 btn btn-info btn-outline filter-btn" data-status="delivered">Đã giao</button>
        <button class="btn btn-info btn-outline filter-btn" data-status="cancelled">Đã hủy</button>
    </div>

    <div class="overflow-x-auto">
        <div id="order-table"></div>
    </div>

    <script type="module">

        document.addEventListener("DOMContentLoaded", function () {
            let currentStatus = "pending";
            const orderTable = document.getElementById("order-table");
            let gridTable = null;

            async function loadOrders(status) {
                console.log("Fetching orders with status:", status);

                try {
                    const response = await fetch(`/api/admin/orders?status=${status}`);
                    const data = await response.json();

                    console.log("Received data:", data);

                    if (!Array.isArray(data)) {
                        console.error("Data is not an array:", data);
                        return;
                    }

                    orderTable.innerHTML = "";

                    if (data.length === 0) {
                        // If no data, manually update the table
                        orderTable.innerHTML = `
                    <div class="p-5 text-center text-gray-500 text-lg font-semibold border border-gray-300 rounded-lg">
                        Không có đơn hàng nào
                    </div>
                `;
                        return;
                    }

                    // Format data for Grid.js
                    const formattedData = data.map((order, index) => [
                        index + 1,
                        order.user_id,
                        new Intl.NumberFormat("vi-VN").format(order.total_price) + " VND",
                        order.payment_method.toUpperCase(),
                        order.order_status,
                        new Intl.DateTimeFormat("vi-VN", {
                            year: "numeric",
                            month: "2-digit",
                            day: "2-digit",
                            hour: "2-digit",
                            minute: "2-digit",
                            second: "2-digit",
                            timeZone: "Asia/Ho_Chi_Minh"
                        }).format(new Date(order.created_at)),
                        html(`
                    <a href="/staff/orders_detail/${order.order_id}" class="text-blue-500 hover:underline mr-2">Xem chi tiết</a>
                    <form action="/staff/orders/${order.order_id}/accept" method="POST" style="display:inline;">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')}">
                        <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded-lg hover:bg-green-700">Chấp nhận</button>
                    </form>
                    <form action="/staff/orders/${order.order_id}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-700">Xóa</button>
                    </form>
                `),
                    ]);

                    // If Grid.js instance exists, update it
                    if (gridTable) {
                        gridTable.updateConfig({
                            data: formattedData,
                        }).forceRender();
                    } else {
                        // Initialize Grid.js if it doesn't exist
                        gridTable = new Grid({
                            columns: [
                                { id: "index", name: "#" },
                                { id: "user_id", name: "ID Khách hàng" },
                                { id: "total_price", name: "Tổng tiền" },
                                { id: "payment_method", name: "Thanh toán" },
                                { id: "order_status", name: "Trạng thái" },
                                { id: "created_at", name: "Ngày đặt hàng" },
                                { id: "actions", name: "Hành động" },
                            ],
                            pagination: { enabled: true, limit: 10 },
                            sort: true,
                            search: true,
                            data: formattedData,
                            language: {
                                search: { placeholder: "🔍 Tìm kiếm đơn hàng..." },
                                pagination: { previous: "⬅️", next: "➡️", showing: "Hiển thị", results: () => "đơn hàng" },
                                loading: "Đang tải...",
                                noRecordsFound: "Không tìm thấy đơn hàng nào",
                                error: "Có lỗi xảy ra khi tải dữ liệu",
                            },
                        }).render(orderTable);
                    }
                } catch (error) {
                    console.error("Error fetching orders:", error);
                }
            }

            // Event listener for filter buttons
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

            // Initial Load
            loadOrders(currentStatus);
        });

    </script>


@endsection
