@extends('staff.staff_dashboard')

@section('title', 'Chỉnh sửa thông tin')

@section('content')
    <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
        <h2 class="text-2xl font-bold text-gray-700 mb-4">Chỉnh sửa Nhà Cung Cấp</h2>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('staff_supplier_update', $supplier->supplier_id) }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Tên nhà cung cấp:</label>
                <input type="text" name="name" value="{{ $supplier->name }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Người liên hệ:</label>
                <input type="text" name="contact_name" value="{{ $supplier->contact_name }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Điện thoại:</label>
                <input type="text" name="phone" value="{{ $supplier->phone }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Email:</label>
                <input type="email" name="email" value="{{ $supplier->email }}" class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Địa chỉ:</label>
                <textarea name="address" class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-300">{{ $supplier->address }}</textarea>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition">
                Cập nhật
            </button>
        </form>
    </div>
@endsection
