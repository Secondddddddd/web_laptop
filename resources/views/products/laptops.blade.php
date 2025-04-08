@extends('index')

@section('title', 'Laptop')

@section('content')
    <div class="container mx-auto p-4">
        <h2 class="text-3xl font-semibold mb-4">Danh sách Laptop</h2>

        <!-- Thanh tìm kiếm -->
        <form method="GET" action="{{ route('laptops') }}" class="mb-4 flex">
            <input type="text" name="search" placeholder="Tìm kiếm laptop..."
                   class="border p-2 flex-grow rounded-l"
                   value="{{ request('search') }}">
            <button type="submit" class="bg-blue-500 text-white px-4 rounded-r">Tìm</button>
        </form>

        <!-- Danh sách Laptop -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @forelse ($products as $product)
                @continue($product->stock_quantity == 0) {{-- Bỏ qua sản phẩm nếu số lượng bằng 0 --}}

                <div class="border p-4 rounded shadow bg-white flex flex-col items-center">
                    <img src="{{ asset('img/'.$product->image_url) }}"
                         alt="{{ $product->name }}"
                         class="w-full aspect-[3/4] object-cover mb-3 rounded-md">

                    <h3 class="text-lg font-bold text-center">{{ $product->name }}</h3>
                    <p class="text-gray-600 text-center">Giá: {{ number_format($product->price, 0, ',', '.') }} VNĐ</p>
                    <a href="{{ route('product.detail', ['product_id' => $product->product_id, 'product_slug' => Str::slug($product->name)]) }}"
                       class="text-blue-500 mt-2 block">
                        Xem chi tiết
                    </a>
                </div>
            @empty
                <p class="text-center text-gray-500">Không có sản phẩm nào để hiển thị.</p>
            @endforelse

        </div>
    </div>
@endsection
