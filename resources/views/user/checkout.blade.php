@extends('index')

@section('content')
        <!-- Danh sách sản phẩm -->
        <div>
            <div class="overflow-x-auto">
                <table class="table border">
                    <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Giá tiền</th>
                        <th>Tổng giá tiền</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="flex flex-row gap-4">
                                    <img class="w-24 h-24 rounded-md object-cover"
                                         src="{{ asset('img/'.$product->image_url) }}"
                                         alt="{{ $product->name }}">
                                    <p class="font-semibold">{{ $product->name }}</p>
                                </div>
                            </td>
                            <td class="text-center">{{ $quantity }}</td>
                            <td class="text-center">{{ number_format($product->price, 0, ',', '.') }} đ</td>
                            <td class="text-right">{{ number_format($product->price * $quantity, 0, ',', '.') }} đ</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <!-- Hiển thị tổng tiền -->
        <div class="flex justify-start mt-6">
            <p class="text-xl font-bold">Tổng tiền:
                <span class="text-red-500">{{ number_format($product->price * $quantity, 0, ',', '.') }} đ</span>
            </p>
        </div>

        <form action="{{ route('order.checkout_submit') }}" method="POST" class="ml-5">
            @csrf

            <!-- Chọn địa chỉ giao hàng -->
            <div class="mb-6 mt-6">
                <p class="text-xl mb-4">Địa chỉ giao hàng</p>
                @if(!empty($addresses) && count($addresses) > 0)
                    <select name="address_id" class="select w-2/5 whitespace-nowrap" required>
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

            <!-- Chọn phương thức thanh toán -->
            <div class=" mt-6">
                <h3 class="text-lg font-semibold mb-2">Phương thức thanh toán:</h3>
                <select name="payment_method" class="select w-2/5" required>
                    <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                    <option value="banking">Chuyển khoản ngân hàng</option>
                </select>
            </div>

            <!-- Input ẩn gửi dữ liệu sản phẩm -->
            <input type="hidden" name="product_id" value="{{ $product->product_id }}">
            <input type="hidden" name="quantity" value="{{ $quantity }}">

            <!-- Nút xác nhận -->
            <div class="mt-6">
                <button type="submit" class="btn btn-success">Xác nhận đặt hàng</button>
            </div>
        </form>
@endsection
