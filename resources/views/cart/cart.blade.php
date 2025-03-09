@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Giỏ hàng của bạn</h2>

        @if(session('success'))
            <div class="bg-green-200 text-green-700 p-2 rounded mb-3">
                {{ session('success') }}
            </div>
        @endif

        @if(empty($cart) || count($cart) == 0)
            <p class="text-gray-600">Giỏ hàng của bạn đang trống.</p>
        @else
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">Ảnh</th>
                    <th class="border p-2">Tên sản phẩm</th>
                    <th class="border p-2">Giá</th>
                    <th class="border p-2">Số lượng</th>
                    <th class="border p-2">Tổng</th>
                    <th class="border p-2">Hành động</th>
                </tr>
                </thead>
                <tbody>
                @foreach($cart as $id => $product)
                    <tr>
                        <td class="border p-2">
                            <img src="{{ asset('images/' . $product['image']) }}" alt="Sản phẩm" class="w-16 h-16">
                        </td>
                        <td class="border p-2">{{ $product['name'] }}</td>
                        <td class="border p-2">{{ number_format($product['price'], 0, ',', '.') }} VND</td>
                        <td class="border p-2">{{ $product['quantity'] }}</td>
                        <td class="border p-2">{{ number_format($product['price'] * $product['quantity'], 0, ',', '.') }} VND</td>
                        <td class="border p-2">
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                <a href="{{ route('home') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Tiếp tục mua sắm</a>
                <a href="#" class="bg-blue-500 text-white px-4 py-2 rounded">Thanh toán</a>
            </div>
        @endif
    </div>
@endsection
