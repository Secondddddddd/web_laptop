@extends('admin.admin_dashboard')

@section('title', 'Chỉnh sửa thông tin người dùng')

@section('content')
    <div class="container mx-auto p-6">

        <h2 class="text-2xl font-semibold mb-4">Chỉnh sửa thông tin người dùng</h2>

        <x-alert-result />

        <form action="{{ route('admin_user_update', ['id' => $user->user_id]) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="bg-white shadow-md rounded-lg p-6 max-w-3xl mx-auto">
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Ảnh đại diện</label>
                    <img src="{{ asset('avatar/' . $user->avatar) }}" alt="Avatar" class="w-24 h-24 rounded-full mb-2">
                    <input type="file" name="avatar" class="border p-2 w-full">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Họ và Tên</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" class="border p-2 w-full">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="border p-2 w-full">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Số điện thoại</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="border p-2 w-full">
                </div>


                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">Vai trò</label>
                    <select name="role" class="border p-2 w-full">
                        <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Khách hàng</option>
                        <option value="staff" {{ $user->role == 'staff' ? 'selected' : '' }}>Nhân viên</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                    </select>
                </div>

                <div class="mt-6 flex space-x-2">
                    <a href="{{ route('admin_user_list') }}" class="bg-gray-500 px-4 py-2 rounded">Hủy</a>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Lưu thay đổi</button>
                </div>
            </div>
        </form>
    </div>
@endsection
