@extends('admin.admin_dashboard')

@section('title', 'Product Manage')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-5">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Danh sách sản phẩm</h1>
        <button class="btn btn-outline btn-primary add-product-btn mb-4">Thêm sản phẩm mới</button>

        <x-alert-result />

        <div class="overflow-x-auto">
            <table class="w-full bg-white border border-gray-300 rounded-lg shadow-md">
                <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-3 text-left border-b">#</th>
                    <th class="px-4 py-3 text-left border-b">Tên sản phẩm</th>
                    <th class="px-4 py-3 text-left border-b">Giá</th>
                    <th class="px-4 py-3 text-left border-b">Số lượng</th>
                    <th class="px-4 py-3 text-left border-b">Danh mục</th>
                    <th class="px-4 py-3 text-left border-b">Nhà cung cấp</th>
                    <th class="px-4 py-3 text-center border-b">Hình ảnh</th>
                    <th class="px-4 py-3 text-center border-b">Hành động</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($products as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 border-b">{{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}</td>
                        <td class="px-4 py-3 border-b font-semibold">{{ $product->name }}</td>
                        <td class="px-4 py-3 border-b text-green-600 font-bold">{{ number_format($product->price, 2) }} đ</td>
                        <td class="px-4 py-3 border-b text-center">{{ $product->stock_quantity }}</td>
                        <td class="px-4 py-3 border-b">{{ $product->category->name ?? 'Không có' }}</td>
                        <td class="px-4 py-3 border-b">{{ $product->supplier->name ?? 'Không có' }}</td>
                        <td class="px-4 py-3 border-b flex justify-center">
                            <img src="{{ asset('img/'.$product->image_url ?? 'default.jpg') }}" alt="Product Image" class="h-12 w-12 rounded">
                        </td>
                        <td class="px-4 py-3 border-b text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('admin_product_edit', ['product_id' => $product->product_id]) }}" class="text-blue-500 hover:underline">Sửa</a>
                                <form action="{{ route('admin_product_delete', ['product_id' => $product->product_id]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-700">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Thanh Phân Trang -->
        <div class="mb-4 mt-4 flex justify-center">
            {{ $products->links('vendor.pagination.tailwind') }}
        </div>
    </div>
@endsection
