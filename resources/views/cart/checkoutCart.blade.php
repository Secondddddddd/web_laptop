@extends('index')

@section('content')
    <form action="{{route('cart.processCheckout')}}" method="POST" class="ml-5">
        @csrf

        <div class="mb-6 mt-6">
            <p class="text-xl mb-4">Địa chỉ giao hàng</p>
            @if($addresses->isNotEmpty())
                <select name="address_id" class="select w-2/5" required>
                    @foreach($addresses as $address)
                        <option value="{{ $address->id }}">
                            {{ $address->address_detail }},
                            {{ $address->ward->full_name ?? '' }},
                            {{ $address->district->full_name ?? '' }},
                            {{ $address->province->full_name ?? '' }}
                        </option>
                    @endforeach
                </select>
            @else
                <p>Bạn chưa có địa chỉ nào.</p>
            @endif
        </div>

        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">Phương thức thanh toán:</h3>
            <select name="payment_method" class="select w-2/5" required>
                <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                <option value="banking">Chuyển khoản ngân hàng</option>
            </select>
        </div>

        <!-- Bảng hiển thị sản phẩm -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">Sản phẩm đã chọn:</h3>
            <table class="w-full border-collapse border border-gray-300 text-center">
                <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Sản phẩm</th>
                    <th class="border p-2">Số lượng</th>
                    <th class="border p-2">Tổng giá tiền</th>
                </tr>
                </thead>
                <tbody>
                @foreach($selectedProducts as $id => $product)
                    <tr>
                        <!-- Cột sản phẩm (ảnh + tên) -->
                        <td class="border p-2">
                            <div class="flex items-center space-x-4">
                                <img src="{{ asset('img/' . $product['image']) }}" alt="{{ $product['name'] }}" class="w-16 h-16">
                                <p>{{ $product['name'] }}</p>
                            </div>
                        </td>

                        <!-- Cột số lượng -->
                        <td class="border p-2">{{ $product['quantity'] }}</td>

                        <!-- Cột tổng giá tiền -->
                        <td class="border p-2">{{ number_format($product['price'] * $product['quantity'], 0, ',', '.') }} VND</td>
                    </tr>

                    <!-- Input hidden để gửi dữ liệu -->
                    <input type="hidden" name="products[{{ $id }}][id]" value="{{ $id }}">
                    <input type="hidden" name="products[{{ $id }}][quantity]" value="{{ $product['quantity'] }}">
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <button type="submit" class="btn btn-success">Xác nhận đặt hàng</button>
        </div>
    </form>
@endsection
