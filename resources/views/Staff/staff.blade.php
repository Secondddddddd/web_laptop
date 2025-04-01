@extends('staff.staff_dashboard')

@section('title', 'Staff Dashboard')

@section('content')
    <div class="p-6">
        <h1 class="text-3xl font-bold text-gray-700 mb-6">Bảng Điều Khiển</h1>

        <!-- Thống kê tổng quan -->
        <div class="grid grid-cols-2 gap-6 mb-6">
            <!-- User -->
            <div class="bg-blue-500 text-white p-6 rounded-lg flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold">Tổng người dùng</h2>
                    <p class="text-3xl font-bold">{{ $userCount }}</p>
                </div>
                <i class="fa-solid fa-user text-5xl"></i>
            </div>

            <!-- Sản phẩm -->
            <div class="bg-green-500 text-white p-6 rounded-lg flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold">Tổng sản phẩm</h2>
                    <p class="text-3xl font-bold">{{ $productCount }}</p>
                </div>
                <i class="fa-solid fa-box-open text-5xl"></i>
            </div>

            <!-- Nhà cung cấp -->
            <div class="bg-orange-500 text-white p-6 rounded-lg flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold">Nhà cung cấp</h2>
                    <p class="text-3xl font-bold">{{ $supplierCount }}</p>
                </div>
                <i class="fa-solid fa-truck text-5xl"></i>
            </div>

            <!-- Đơn hàng đã hoàn thành -->
            <div class="bg-purple-500 text-white p-6 rounded-lg flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold">Đơn hàng hoàn thành</h2>
                    <p class="text-3xl font-bold">{{ $completedOrders }}</p>
                </div>
                <i class="fa-solid fa-receipt text-5xl"></i>
            </div>
        </div>

        <!-- Bảng ghi chép doanh thu tháng -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Doanh thu tháng</h2>
            <table class="w-full border-collapse bg-white">
                <thead class="bg-gray-200">
                <tr>
                    <th class="p-3 text-left">Ngày</th>
                    <th class="p-3 text-left">Số đơn hàng</th>
                    <th class="p-3 text-left">Tổng doanh thu</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($monthlyRevenue as $day => $data)
                    <tr class="border-b">
                        <td class="p-3">{{ $day }}</td>
                        <td class="p-3">{{ $data['orders'] }}</td>
                        <td class="p-3 text-green-600 font-bold">{{ number_format($data['revenue'], 0, ',', '.') }} VND</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
