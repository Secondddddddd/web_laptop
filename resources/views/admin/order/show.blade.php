@extends('admin.admin_dashboard')

@section('title', 'Chi tiết đơn hàng')

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-4">Chi tiết đơn hàng #{{ $order->order_id }}</h2>

    <div class="bg-white p-4 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold mb-2">Thông tin khách hàng</h3>
        <p><strong>Tên khách hàng:</strong> {{ $order->user->full_name }}</p>
        <p><strong>Email:</strong> {{ $order->user->email }}</p>
        <p><strong>Số điện thoại:</strong> {{ $order->user->phone ?? 'Chưa có' }}</p>
        <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>

        <h3 class="text-lg font-semibold mt-4">Thông tin đơn hàng</h3>
        <p><strong>Trạng thái:</strong> {{ ucfirst($order->order_status) }}</p>
        <p><strong>Phương thức thanh toán:</strong> {{ strtoupper($order->payment_method) }}</p>
        <p><strong>Tổng tiền:</strong> {{ number_format($order->total_price, 0, ',', '.') }} VND</p>
        <p><strong>Ngày đặt:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="mt-6">
        <h3 class="text-lg font-semibold mb-2">Danh sách sản phẩm</h3>
        <table class="w-full bg-white shadow-md rounded-lg">
            <thead>
            <tr class="bg-gray-200">
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">Tên sản phẩm</th>
                <th class="px-4 py-2">Số lượng</th>
                <th class="px-4 py-2">Đơn giá</th>
                <th class="px-4 py-2">Thành tiền</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($order->orderDetails as $index => $detail)
                <tr class="border-b">
                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                    <td class="px-4 py-2">{{ $detail->product->name }}</td>
                    <td class="px-4 py-2">{{ $detail->quantity }}</td>
                    <td class="px-4 py-2">{{ number_format($detail->unit_price, 0, ',', '.') }} VND</td>
                    <td class="px-4 py-2">{{ number_format($detail->subtotal, 0, ',', '.') }} VND</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin_order_list') }}" class="btn btn-secondary">Quay lại</a>
    </div>
@endsection
