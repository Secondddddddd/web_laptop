@extends('admin.admin_dashboard')

@section('title', 'Danh sách người dùng')

@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-4">Danh sách người dùng</h2>

        <a href="{{ route('admin_user_add') }}" class="bg-green-500 text-white px-4 py-2 rounded mb-4 inline-block">
            + Thêm Người Dùng
        </a>

        <x-alert-result />

        <div class="overflow-x-auto">
            <table class="w-full border-collapse bg-white shadow-md rounded-lg">
                <thead>
                <tr class="bg-gray-200 text-gray-700">
                    <th class="px-4 py-2">ID</th>
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
                        <td class="px-4 py-2">{{ $user->full_name }}</td>
                        <td class="px-4 py-2">{{ $user->email }}</td>
                        <td class="px-4 py-2">{{ $user->phone ?? 'N/A' }}</td>
                        <td class="px-4 py-2 capitalize">{{ $user->role }}</td>
                        <td class="px-4 py-2">{{ date('d/m/Y', strtotime($user->created_at)) }}</td>
                        <td class="px-4 py-2 justify-center items-center">
                            <a href="{{ route('admin_user_detail', ['id' => $user->user_id]) }}" class="bg-blue-500 text-white px-2 py-1 rounded">Chi tiết</a>
                            <a href="{{ route('admin_user_edit', ['id' => $user->user_id]) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">Chỉnh sửa</a>
                            <form action="{{ route('admin_user_disable', ['id' => $user->user_id]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn thay đổi trạng thái tài khoản này?');">
                                @csrf
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-700">Vô hiệu hóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- Thanh phân trang -->
        <div class="mt-4 mb-4 flex justify-center">
            {{ $users->links('vendor.pagination.tailwind') }}
        </div>
    </div>
@endsection
