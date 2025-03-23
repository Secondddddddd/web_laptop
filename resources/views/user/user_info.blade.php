@extends('index')

@section('title', 'Thông tin cá nhân')

@section('content')

    @vite(['resources/js/user_info.js'])

    <div class="flex h-auto border shadow">
        <!-- Cột trái (Menu điều hướng) -->
        <div class="w-1/4 border-r-2">
                <div class="flex items-center">
                    <div class="avatar">
                        <div class="w-24 rounded-full m-1">
                            <img src="{{asset('avatar/'.Auth::user()->avatar ?? 'avatar/avatar_default.jpg') }}" alt="avatar"/>
                        </div>
                    </div>
                    <p class=" text-lg text-center">
                        {{Auth::user()->full_name}}
                    </p>
                </div>
            <div class="grid text-2xl" id="profile-menu">
                <div class="menu-item hover:bg-gray-400">
                    <p class="mt-2 mb-3 text-primary ml-2">Thông tin cá nhân</p>
                </div>
                <div class="menu-item hover:bg-gray-400">
                    <p class="mb-3 ml-2">Địa chỉ</p>
                </div>
                <div class="menu-item hover:bg-gray-400">
                    <p class="ml-2 mb-3">Đổi mật khẩu</p>
                </div>
                <div class="menu-item hover:bg-gray-400">
                    <p class="ml-2 mb-3">Lịch sử giao dịch</p>
                </div>
            </div>
        </div>

        <!-- Cột phải (Nội dung) -->
        <div class="flex-1">
            <!-- User Info: mặc định hiển thị nếu không có lựa chọn nào được lưu -->
            <div class="h-full user-info-component mb-5">
                <x-user_info name="{{ Auth::user()->full_name }}" email="{{ Auth::user()->email }}"
                             avatar="{{ asset('avatar/' . Auth::user()->avatar ?? 'avatar/avatar_default.jpg') }}"
                             phone="{{ Auth::user()->phone }}" />
            </div>
            <!-- Address Component, ẩn mặc định -->
            <div class="h-full address-component hidden">
                <x-address :addresses="$addresses" :provinces="$provinces" :action="route('address.store')" />
            </div>
            <!-- Change Password Component, ẩn mặc định -->
            <div class="change-password hidden mb-3">
                <x-change_password action="{{ route('user.change_password') }}" />
            </div>
            <!-- Transaction History Component, ẩn mặc định -->
            <div class="transaction-history hidden">
                <x-transaction-history-component />
            </div>
        </div>

    </div>

@endsection
