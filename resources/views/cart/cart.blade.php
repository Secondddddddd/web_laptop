@extends('index')

@section('content')
    @vite(['resources/js/cart.js'])
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Giỏ hàng của bạn</h2>

        <x-alert-result />

        <!-- Toast thông báo -->
        <div id="toast" class="toast toast-top toast-center hidden">
            <div class="alert alert-error">
                <span>Vui lòng chọn ít nhất 1 sản phẩm để thanh toán.</span>
            </div>
        </div>

        @if(empty($cart) || count($cart) == 0)
            <p class="text-gray-600">Giỏ hàng của bạn đang trống.</p>
        @else
            <table class="w-full border-collapse border border-gray-300 text-center mb-2">
                <thead>
                <tr class="bg-gray-200">
                    <th class="border p-2">
                            <input type="checkbox" class="checkbox product-checkbox-all" />
                    </th>
                    <th class="border p-2">Sản phẩm</th>
                    <th class="border p-2">Giá</th>
                    <th class="border p-2">Số lượng</th>
                    <th class="border p-2">Tổng</th>
                    <th class="border p-2">Hành động</th>
                </tr>
                </thead>
                <tbody>
                @foreach($cart as $id => $product)
                    <tr data-id="{{ $id }}">
                        <td class="border p-2">
                            <input type="checkbox" class="checkbox product-checkbox" data-price="{{ $product['price'] * $product['quantity'] }}">
                        </td>
                        <td class="border p-2">
                            <div class="grid grid-cols-[auto,1fr] items-center gap-2">
                                <img src="{{ asset('img/' . $product['image']) }}" alt="Sản phẩm" class="w-16 h-16">
                                <p class="text-center">{{ $product['name'] }}</p>
                            </div>
                        </td>
                        <td class="border p-2 price-product" data-price="{{ $product['price'] }}">{{ number_format($product['price'], 0, ',', '.') }} VND</td>
                        <td class="border p-2">
                            <div class="flex items-center border rounded-md w-48">
                                <button type="button" class="w-10 h-10 flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded-l-md" id="btn-decrease">-</button>
                                <input type="number" name="quantity" id="quantity-product" class="text-center w-full border-x outline-none" min="0" max="{{$product['stock_quantity']  }}" value="{{ $product['quantity'] }}"/>
                                <button type="button" class="w-10 h-10 flex items-center justify-center bg-gray-200 hover:bg-gray-300 rounded-r-md" id="btn-increase">+</button>
                            </div>
                        </td>
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

            <div class="mt-5">
                <p class="text-2xl">Tổng số tiền là: <span class="total_price">0</span> </p>
            </div>

            <div class="mt-5">
                <a href="{{ route('home') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Tiếp tục mua sắm</a>
                <button id="payment-btn" class="btn bg-blue-500 text-white px-4 py-2 rounded">
                    Thanh toán
                </button>
            </div>
        @endif
    </div>
@endsection
