@extends('staff.staff_dashboard')

@section('title', 'Chỉnh sửa sản phẩm')

@section('content')

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-5">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Chỉnh sửa sản phẩm</h1>

        <x-alert-result />


        {{-- Form chỉnh sửa sản phẩm --}}
        <form action="{{ route('staff_product_update', ['product_id' => $product->product_id]) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            @method('PUT')

            {{-- Tên sản phẩm --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-bold">Tên sản phẩm</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full p-2 border rounded-lg">
            </div>

            {{-- Mô tả --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-bold">Mô tả</label>
                <textarea name="description" class="w-full p-2 border rounded-lg">{{ old('description', $product->description) }}</textarea>
            </div>

            {{-- Giá --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-bold">Giá</label>
                <input type="text" name="price" value="{{ old('price', $product->price) }}" class="w-full p-2 border rounded-lg">
            </div>

            {{-- Số lượng --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-bold">Số lượng tồn kho</label>
                <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" class="w-full p-2 border rounded-lg">
            </div>
            {{-- Danh mục --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-bold">Danh mục</label>
                <select name="category_id" class="w-full p-2 border rounded-lg" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->category_id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                <p class="text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nhà cung cấp --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-bold">Nhà cung cấp</label>
                <select name="supplier_id" class="w-full p-2 border rounded-lg" required>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->supplier_id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
                @error('supplier_id')
                <p class="text-red-500">{{ $message }}</p>
                @enderror
            </div>





            {{-- Hình ảnh hiện tại --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-bold">Hình ảnh hiện tại</label>
                <img src="{{ asset('img/'.$product->image_url) }}" alt="Product Image" class="h-20 rounded-lg">
            </div>

            {{-- Ảnh mới --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-bold">Chọn ảnh mới (nếu có)</label>
                <input type="file" name="image" class="w-full p-2 border rounded-lg">
            </div>

            {{-- Nút cập nhật --}}
            <button type="submit" class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-700">Cập nhật sản phẩm</button>
        </form>
    </div>
@endsection
