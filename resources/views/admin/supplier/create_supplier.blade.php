@extends('admin.admin_dashboard')

@section('title', 'Thêm Nhà Cung Cấp')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-5">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Thêm Nhà Cung Cấp Mới</h1>

        <x-alert-result />

        <form action="{{ route('admin_supplier_store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block font-medium text-gray-700">Tên nhà cung cấp <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       class="input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="contact_name" class="block font-medium text-gray-700">Người liên hệ</label>
                <input type="text" name="contact_name" id="contact_name" value="{{ old('contact_name') }}"
                       class="input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
            </div>

            <div>
                <label for="phone" class="block font-medium text-gray-700">Số điện thoại</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                       class="input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
            </div>

            <div>
                <label for="email" class="block font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                       class="input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
                @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="address" class="block font-medium text-gray-700">Địa chỉ</label>
                <input type="text" name="address" id="address" value="{{ old('address') }}"
                       class="input mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
            </div>

            <div class="flex justify-between">
                <a href="{{ route('admin_supplier_list') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Quay lại</a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Thêm Mới</button>
            </div>
        </form>
    </div>
@endsection
