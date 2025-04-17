@extends('index')

@section('content')
    @vite(['resources/js/checkoutCart.js'])

    <x-alert-result />



        <div class="mb-6 mt-6 ml-5">
            <p class="text-xl mb-4">Địa chỉ giao hàng: <span class="address">
                    @if($addressDefault)
                        {{ $addressDefault->address_detail }},
                        {{ $addressDefault->ward->full_name ?? '' }},
                        {{ $addressDefault->district->full_name ?? '' }},
                        {{ $addressDefault->province->full_name ?? '' }}
                    @else
                        Không có
                    @endif
                </span>
            </p>


            <label for="my_modal_1" class="btn">Chọn địa chỉ</label>
            <input type="checkbox" id="my_modal_1" class="modal-toggle" />
            <div class="modal" role="dialog">
                <div class="modal-box">
                    @if($addresses->isNotEmpty())
                        <select name="address_id" class="select w-full address-select">
                            @foreach($addresses as $address)
                                <option value="{{ $address->id }}"
                                        data-full-address="{{ $address->address_detail }}, {{ $address->ward->full_name ?? '' }}, {{ $address->district->full_name ?? '' }}, {{ $address->province->full_name ?? '' }}"
                                >
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
                    <div class="modal-action">
                        <label for="my_modal_1" class="btn save-address-btn">Lưu</label>
                        <label for="my_modal_1" class="btn">Đóng</label>
                    </div>
                </div>
            </div>

            <label for="my_modal_6" class="btn btn-add-address">Thêm địa chỉ mới</label>
            <input type="checkbox" id="my_modal_6" class="modal-toggle" />
            <div class="modal" role="dialog">
                <div class="modal-box">
                    <div>
                        <label class="block mb-1 font-medium">Tỉnh/Thành phố</label>
                        <select name="province_code" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">-- Chọn Tỉnh/TP --</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Quận/Huyện</label>
                        <select name="district_code" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">-- Chọn Quận/Huyện --</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Phường/Xã</label>
                        <select name="ward_code" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">-- Chọn Phường/Xã --</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Địa chỉ chi tiết</label>
                        <input type="text" name="address_detail" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 address_detail" placeholder="Số nhà, tên đường..." required>
                    </div>
                    <div class="modal-action">
                        <label for="my_modal_6" class="btn save-new-address-btn">Lưu</label>
                        <label for="my_modal_6" class="btn">Đóng</label>
                    </div>
                </div>
            </div>
        </div>

    <form action="{{route('cart.processCheckout')}}" method="POST" class="ml-5">
        @csrf
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">Phương thức thanh toán:</h3>
            <select name="payment_method" class="select w-2/5" required>
                <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                <option value="banking">Chuyển khoản ngân hàng</option>
            </select>
        </div>

        <div class="mt-5 flex items-center">
            <p class="text-xl font-semibold mb-2 mr-4 user-phone">
                Số điện thoại:
                <input type="text" class="input input-user-phone" name="phone" value="{{ old('phone', $phone) }}" readonly>
            </p>

            <button type="button" class="btn btn-primary edit-phone">Chỉnh sửa</button>

        </div>

        <div>
            <input type="text" class="input-address hidden" name="address" value="">
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
