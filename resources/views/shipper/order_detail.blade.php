@extends('index')

@section('title', 'Đơn hàng đang giao')

@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-4">Chi tiết đơn hàng #{{ $order->order_id }}</h2>

        <!-- Thông tin đơn hàng -->
        <div class="bg-white p-4 rounded-lg shadow-md mb-6">
            <h3 class="text-lg font-semibold mb-2">Thông tin đơn hàng</h3>
            <p><strong>Khách hàng:</strong> {{ $order->user->full_name }}</p>
            <p><strong>Email:</strong> {{ $order->user->email }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->user->phone ?? 'Chưa có' }}</p>
            <p><strong>Địa chỉ giao hàng:</strong> {{ $order->address }}</p>
            <p><strong>Tổng tiền:</strong> {{ number_format($order->total_price, 0, ',', '.') }} VND</p>
            <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
        </div>

        <!-- Danh sách sản phẩm trong đơn hàng -->
        <div class="bg-white p-4 rounded-lg shadow-md mb-6">
            <h3 class="text-lg font-semibold mb-2">Danh sách sản phẩm</h3>
            <table class="w-full">
                <thead class="bg-gray-200">
                <tr>
                    <th class="p-2">#</th>
                    <th class="p-2">Sản phẩm</th>
                    <th class="p-2">Số lượng</th>
                    <th class="p-2">Đơn giá</th>
                    <th class="p-2">Thành tiền</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($order->orderDetails as $index => $detail)
                    <tr class="border-r-2">
                        <td class="p-2">{{ $index + 1 }}</td>
                        <td class="p-2 flex items-center">
                            <img src="/img/{{ $detail->product->image_url }}" alt="{{ $detail->product->name }}" class="w-16 h-16 mr-2">
                            <span>{{ $detail->product->name }}</span>
                        </td>
                        <td class="p-2">{{ $detail->quantity }}</td>
                        <td class="p-2">{{ number_format($detail->unit_price, 0, ',', '.') }} VND</td>
                        <td class="p-2">{{ number_format($detail->subtotal, 0, ',', '.') }} VND</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Form nhập mã OTP để xác nhận giao hàng -->
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-2">Xác nhận giao hàng</h3>
            <p>Vui lòng nhập mã OTP đã được gửi tới khách hàng để xác nhận giao hàng thành công.</p>
            <form action="{{ route('shipper.orders.confirm') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="otp_code" class="block font-medium mb-1">Mã OTP:</label>
                    <input type="text" name="otp_code" id="otp_code" class="w-full p-2 border rounded" placeholder="Nhập mã OTP">
                </div>
                <button type="submit" class="btn btn-success">Xác nhận giao hàng</button>
            </form>
        </div>
    </div>
@endsection
