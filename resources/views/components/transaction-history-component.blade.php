<div class="container mx-auto p-4 bg-white shadow-md rounded-lg">
    <h2 class="text-xl font-semibold mb-4">Lịch sử giao dịch</h2>
    <div id="transaction-grid"></div>
</div>

<!-- Modal hiển thị chi tiết đơn hàng -->
<div id="orderDetailModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 max-w-lg">
        <h3 class="text-xl font-semibold mb-4">Chi tiết đơn hàng</h3>
        <div id="orderDetailContent">
            <!-- Nội dung chi tiết đơn hàng sẽ được cập nhật ở đây -->
            <p>Đang tải dữ liệu...</p>
        </div>
        <div class="mt-4 text-right">
            <button onclick="closeOrderModal()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">Đóng</button>
        </div>
    </div>
</div>


<script type="module">

    new Grid({
        columns: [
            { id: "order_id", name: "Mã đơn hàng" },
            { id: "total_price", name: "Tổng tiền" },
            { id: "payment_method", name: "Thanh toán"},
            { id: "order_status", name: "Trạng thái" },
            { id: "created_at", name: "Ngày đặt hàng" },
            { id: "actions", name: "Hành động" },
        ],
        data: [
                @foreach($orders as $order)
            [
                "#{{ $order->order_id }}",
                "{{ number_format($order->total_price, 0, ',', '.') }} VNĐ", // Cột 2: total_price
                "{{ $order->payment_method == 'cod' ? 'COD' : 'Chuyển khoản' }}", // Cột 3: payment_method
                "{{ $order->order_status }}", // Cột 4: order_status
                "{{ $order->created_at->format('d/m/Y H:i') }}", // Cột 5: created_at
                html(`
            <button class="btn btn-primary"
            onclick="viewOrderDetail({{ $order->order_id }})"
            >Xem chi tiết</button>
                @if($order->order_status === 'pending')
                <button class='ml-3 px-2 py-1 bg-red-500 text-white rounded hover:bg-red-700'
                    onclick='cancelOrder({{ $order->order_id }})'>Hủy đơn</button>
                @else
                <button class='ml-3 px-2 py-1 bg-gray-400 text-white rounded cursor-not-allowed'
                    disabled>Hủy đơn</button>
               @endif
                `)
            ],
            @endforeach
        ],
        search: true,
        pagination: { limit: 5 },
        language: { 'search': { 'placeholder': 'Tìm kiếm...' } }
    }).render(document.getElementById("transaction-grid"));


    function cancelOrder(orderId) {
        if (confirm("Bạn có chắc muốn hủy đơn hàng này không?")) {
            fetch(`/order/cancel/${orderId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json()).then(data => {
                if (data.success) {
                    alert("Đơn hàng đã được hủy thành công.");
                    location.reload();
                } else {
                    alert("Lỗi khi hủy đơn hàng: " + data.message);
                }
            }).catch(error => console.error('Error:', error));
        }
    }

    // Hiện modal và lấy chi tiết đơn hàng từ API
    async function viewOrderDetail(orderId) {
        const modal = document.getElementById("orderDetailModal");
        modal.classList.remove("hidden");

        // Hiển thị thông báo loading
        document.getElementById("orderDetailContent").innerHTML = "<p>Đang tải dữ liệu...</p>";

        try {
            // Gửi yêu cầu đến API để lấy chi tiết đơn hàng
            const response = await fetch(`/user/order/details/${orderId}`);
            if (!response.ok) {
                throw new Error("Không thể tải chi tiết đơn hàng.");
            }
            const orderData = await response.json();

            // Tạo nội dung HTML hiển thị thông tin đơn hàng
            let contentHtml = `
                <p><strong>Mã đơn hàng:</strong> #${orderData.order_id}</p>
                <p><strong>Tổng tiền:</strong> ${orderData.total_price} VNĐ</p>
                <p><strong>Phương thức thanh toán:</strong> ${orderData.payment_method.toUpperCase()}</p>
                <p><strong>Trạng thái:</strong> ${orderData.order_status}</p>
                <p><strong>Ngày đặt hàng:</strong> ${orderData.created_at}</p>
                <p><strong>OTP:</strong> ${orderData.otp_code}</p>
                <h4 class="mt-4 font-semibold">Sản phẩm trong đơn:</h4>
                <table class="w-full border-collapse border border-gray-300 mt-2">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-2">Sản phẩm</th>
                            <th class="border p-2">Số lượng</th>
                            <th class="border p-2">Giá tiền</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            orderData.items.forEach(item => {
                contentHtml += `
                    <tr>
                        <td class="border p-2">
                            <div class="flex items-center space-x-2">
                                <img src="/img/${item.image || 'default.jpg'}" alt="${item.name}" class="w-16 h-16 rounded"/>
                                <span>${item.name}</span>
                            </div>
                        </td>
                        <td class="border p-2 text-center">${item.quantity}</td>
                        <td class="border p-2 text-right">${item.unit_price} VNĐ</td>
                    </tr>
                `;
            });
            contentHtml += `
                    </tbody>
                </table>
            `;
            document.getElementById("orderDetailContent").innerHTML = contentHtml;
        } catch (error) {
            document.getElementById("orderDetailContent").innerHTML = `<p class="text-red-500">${error.message}</p>`;
        }
    }

    // Hàm đóng modal
    function closeOrderModal() {
        document.getElementById("orderDetailModal").classList.add("hidden");
    }

    // Export các hàm nếu cần cho các file JS khác (nếu bạn sử dụng module bundler)
    window.viewOrderDetail = viewOrderDetail;
    window.closeOrderModal = closeOrderModal;
    window.cancelOrder = cancelOrder;
</script>
