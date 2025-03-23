@vite(['resources/js/address.js'])

<x-alert-result />

<div class="p-5 bg-white rounded-lg shadow-md h-full">
    <h2 class="text-2xl font-semibold mb-4">Danh sách địa chỉ</h2>

    <!-- Nút thêm mới -->
    <div class="text-right mb-4">
        <button onclick="toggleAddressForm()"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            + Thêm địa chỉ mới
        </button>
    </div>

    <!-- Danh sách địa chỉ -->
    @if($addresses->count())
        <ul class="space-y-3">
            @foreach($addresses as $address)
                <li class="p-4 bg-gray-100 rounded-lg flex justify-between items-center">
                    <span>
                        {{ $address->address_detail }},
                        {{ $address->ward->full_name ?? '' }},
                        {{ $address->district->full_name ?? '' }},
                        {{ $address->province->full_name ?? '' }}

                    @if($address->is_default)
                            <span class="ml-2 px-2 py-1 bg-green-500 text-white text-xs rounded">Mặc định</span>
                        @endif
                    </span>
                    <div class="space-x-2">
                        <button onclick="deleteAddress({{ $address->id }})" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                            Xóa
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-gray-500">Chưa có địa chỉ nào.</p>
    @endif

    <!-- Overlay và Form Thêm Địa Chỉ dạng Modal -->
    <div id="addressModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-xl relative">

            <!-- Nút đóng -->
            <button onclick="toggleAddressForm()" class="absolute top-2 right-2 text-gray-500 hover:text-red-500 text-2xl">
                &times;
            </button>

            <h3 class="text-xl font-semibold mb-4">Thêm địa chỉ mới</h3>
            <form action="{{ $action }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block mb-1 font-medium">Tỉnh/Thành phố</label>
                    <select name="province_code" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">-- Chọn Tỉnh/TP --</option>
                        @foreach($provinces as $province)
                            <option value="{{ $province->code }}">{{ $province->full_name }}</option>
                        @endforeach
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
                    <input type="text" name="address_detail" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Số nhà, tên đường..." required>
                </div>
                <div>
                    <label class="inline-flex items-center">
                        <input type="hidden" name="is_default" value="0">
                        <input type="checkbox" name="is_default" value="1" class="form-checkbox rounded text-blue-600 focus:ring-blue-500">
                        <span class="ml-2">Đặt làm địa chỉ mặc định</span>
                    </label>
                </div>
                <div class="text-right">
                    <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">Lưu địa chỉ</button>
                </div>
            </form>
        </div>
    </div>
</div>
