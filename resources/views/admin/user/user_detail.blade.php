@extends('admin.admin_dashboard')

@section('title', 'Thông tin người dùng')

@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-4">Thông tin người dùng</h2>

        <div class="bg-white shadow-md rounded-lg p-6 max-w-3xl mx-auto">
            <div class="flex items-center space-x-4 mb-6">
                <img src="{{ asset('avatar/' . $user->avatar) }}" alt="Avatar" class="w-24 h-24 rounded-full border border-gray-300">
                <div>
                    <h3 class="text-xl font-semibold">{{ $user->full_name }}</h3>
                    <p class="text-gray-600">{{ ucfirst($user->role) }}</p>
                    <p class="mt-1">
                        <span class="px-3 py-1 rounded-lg text-white {{ $user->status === 'inactive' ? 'bg-red-500' : 'bg-green-500' }}">
                            {{ $user->status === 'inactive' ? 'Đã vô hiệu hóa' : 'Đang hoạt động' }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="font-semibold">Email:</p>
                    <p class="text-gray-700">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="font-semibold">Số điện thoại:</p>
                    <p class="text-gray-700">{{ $user->phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-semibold">Ngày tạo tài khoản:</p>
                    <p class="text-gray-700">{{ date('d/m/Y', strtotime($user->created_at)) }}</p>
                </div>
            </div>

            <div class="mt-6 flex space-x-2">
                <a href="{{ route('admin_user_list') }}" class="bg-gray-500 px-4 py-2 rounded text-white">Quay lại</a>
            </div>
        </div>
    </div>
@endsection
