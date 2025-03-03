@extends('admin.admin_dashboard')

@section('title', 'Danh sách người dùng')

@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-4">Danh sách người dùng</h2>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full border-collapse bg-white shadow-md rounded-lg">
                <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Ảnh đại diện</th>
                    <th class="px-4 py-2">Họ và Tên</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Số điện thoại</th>
                    <th class="px-4 py-2">Vai trò</th>
                    <th class="px-4 py-2">Ngày tạo</th>
                    <th class="px-4 py-2">Hành động</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr class="border-b text-center">
                        <td class="px-4 py-2">{{ $user->user_id }}</td>
                        <td class="px-4 py-2">
                            <img src="{{ asset('avatar/' . $user->avatar) }}" alt="Avatar" class="w-10 h-10 rounded-full">
                        </td>
                        <td class="px-4 py-2">{{ $user->full_name }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">{{ $user->phone ?? 'N/A' }}</td>
                        <td class="px-4 py-2 capitalize">{{ $user->role }}</td>
                        <td class="px-4 py-2">{{ date('d/m/Y', strtotime($user->created_at)) }}</td>
                        <td class="px-4 py-2">
                            <a href="#" class="bg-blue-500 text-white px-2 py-1 rounded">Chi tiết</a>
                            <a href="#" class="bg-red-500 text-white px-2 py-1 rounded">Xóa</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
