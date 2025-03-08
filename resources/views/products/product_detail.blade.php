@extends('index')

@section('title', $product->name)

@section('content')

    <x-alert-result />

    <div class="container mx-auto p-4 ">
        <div class="breadcrumbs text-sm">
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="{{route('laptops')}}">Laptop</a></li>
                <li>{{$product->name}}</li>
            </ul>
        </div>
        <!-- Bố cục chính -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Hình ảnh sản phẩm (Nhỏ, căn trái) -->
                <div class="flex justify-center md:justify-start border">
                    <img src="{{ asset('img/'.$product->image_url) }}"
                         alt="{{ $product->name }}"
                         class="w-64 h-auto rounded-md shadow-md">
                </div>

                <!-- Thông tin sản phẩm (Căn phải) -->
                <div class="md:col-span-2">
                    <h2 class="text-3xl font-bold">{{ $product->name }}</h2>
                    <p class="text-2xl font-semibold text-red-500 mt-2">
                        {{ number_format($product->price, 0, ',', '.') }} VNĐ
                    </p>

                    <!-- Rating -->
                    <div class="mt-3">
                        @auth
                            <form action="{{ route('reviews.store', $product->product_id) }}" method="POST" class="flex flex-col items-start mt-3">
                                @csrf
                                <x-rating name="rating-9" selectedRating="{{ $product->reviews->avg('rating') }}"/>
                                <button type="submit" class="btn btn-primary mt-2">Gửi đánh giá</button>
                            </form>
                        @else
                            <!-- Hiển thị thông báo nếu chưa đăng nhập -->
                            <div class="mt-3 text-red-500">
                                <x-rating name="rating-9" selectedRating="{{ $product->reviews->avg('rating') }}"/>
                                <p class="mt-2">Bạn cần <a href="{{ route('login') }}" class="text-blue-500 underline">đăng nhập</a> để đánh giá sản phẩm.</p>
                            </div>
                        @endauth
                    </div>






                    <!-- Nút mua hàng -->
                    <div class="mt-4 flex space-x-4">
                        <form action="{{ route('cart.add', $product->product_id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">Thêm vào giỏ hàng</button>
                        </form>
                        <form action="{{ route('order.buy_now', $product->product_id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Mua ngay</button>
                        </form>
                    </div>
                </div>
            </div>

        <!-- Mô tả sản phẩm -->
        <div class="mt-6">
            <h3 class="text-2xl font-semibold mb-2">Mô tả sản phẩm</h3>
            <p class="text-gray-600">{{ $product->description }}</p>
        </div>
    </div>
@endsection
