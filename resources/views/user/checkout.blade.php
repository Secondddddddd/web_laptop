@extends('index')

@section('content')
    @vite(['resources/js/checkout.js'])
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

            <div class="mt-5 flex items-center">
                <p class="text-xl font-semibold mb-2 mr-4 user-phone">
                    Số điện thoại:
                    <input type="text" class="input input-user-phone" name="phone" value="{{ old('phone', $phone) }}" readonly>
                </p>

                <button type="button" class="btn btn-primary edit-phone">Chỉnh sửa</button>

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
            <div>
                <input type="text" class="input-address hidden" name="address" value="">
            </div>
            <!-- Nút xác nhận -->
            <div class="mt-6">
                <button type="submit" class="btn btn-success">Xác nhận đặt hàng</button>
            </div>
        </form>
@endsection
