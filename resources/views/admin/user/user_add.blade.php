@extends('admin.admin_dashboard')

@section('title', 'Thêm người dùng')

@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-4">Thêm người dùng mới</h2>

        <x-alert-result />

        {{-- Hiển thị lỗi validate --}}
        @if ($errors->any())
            <div id="validation-alert" class="alert alert-warning flex items-center justify-between p-4 mb-4 rounded-lg bg-yellow-100 border border-yellow-400 text-yellow-700">
        <span class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current mr-2" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </span>
                <button onclick="closeAlert('validation-alert')" class="ml-2 text-yellow-800 hover:text-yellow-900">&times;</button>
            </div>
        @endif

        <form action="{{ route('admin_user_store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-6">
            @csrf
            <div class="mb-4">
                <label class="block font-semibold">Họ và Tên:</label>
                <input type="text" name="full_name" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold">Email:</label>
                <input type="email" name="email" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold">Mật khẩu:</label>
                <input type="password" name="password" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold">Số điện thoại:</label>
                <input type="text" name="phone" class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label class="block font-semibold">Địa chỉ:</label>
                <textarea name="address" class="w-full border rounded p-2"></textarea>
            </div>

            <div class="mb-4">
                <label class="block font-semibold">Vai trò:</label>
                <select name="role" class="w-full border rounded p-2">
                    <option value="customer">Khách hàng</option>
                    <option value="admin">Admin</option>
                    <option value="staff">Nhân viên</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-semibold">Ảnh đại diện:</label>
                <input type="file" name="avatar" class="w-full border rounded p-2">
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Thêm người dùng</button>
                <a href="{{ route('admin_user_list') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Quay lại</a>
            </div>
        </form>
    </div>
@endsection
