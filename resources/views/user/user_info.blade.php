@extends('index')

@section('title', 'Thông tin cá nhân')

@section('content')

    @vite(['resources/js/user_info.js'])

    <div class="flex h-96 border mx-[200px] shadow">
        <!-- Cột trái màu xanh dương nhạt -->
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
            <div class="grid text-2xl ml-2">
                <p class="mt-2 mb-3 text-primary">Thông tin cá nhân</p>
                <p class="mb-3">Địa chỉ</p>
                <p >Đổi mật khẩu</p>
            </div>
        </div>

        <!-- Cột phải màu vàng -->
        <div class="w-3/4">
            <div class="h-full hidden user-info-component">
            <x-user_info name="{{Auth::user()->full_name}}" email="{{Auth::user()->email}}"
            avatar="{{asset('avatar/'.Auth::user()->avatar ?? 'avatar/avatar_default.jpg')}}"
            phone="{{Auth::user()->phone}}"/>
            </div>
            <div class="h-full address-component">
                <x-address :addresses="$addresses" :provinces="$provinces" :action="route('address.store')" />
            </div>
            <div class="change-password hidden">
                <x-change_password action="#"/>
            </div>
        </div>
    </div>

@endsection
