@extends('admin.admin_dashboard')

@section('title', 'Thêm Sản Phẩm Mới')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-5">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Thêm Sản Phẩm Mới</h1>

        @if (session('success'))
            <div id="success-alert" class="alert alert-success flex items-center justify-between p-4 mb-4 rounded-lg bg-green-100 border border-green-400 text-green-700">
        <span class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current mr-2" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </span>
                <button onclick="closeAlert('success-alert')" class="ml-2 text-green-800 hover:text-green-900">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div id="error-alert" class="alert alert-danger flex items-center justify-between p-4 mb-4 rounded-lg bg-red-100 border border-red-400 text-red-700">
        <span class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current mr-2" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            {{ session('error') }}
        </span>
                <button onclick="closeAlert('error-alert')" class="ml-2 text-red-800 hover:text-red-900">&times;</button>
            </div>
        @endif

        {{-- Hiển thị lỗi validate --}}
        @if ($errors->any())
            <div id="validation-alert" class="alert alert-warning flex items-center justify-between p-4 mb-4 rounded-lg bg-yellow-100 border border-yellow-400 text-yellow-700">
        <span class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current mr-2" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </span>
                <button onclick="closeAlert('validation-alert')" class="ml-2 text-yellow-800 hover:text-yellow-900">&times;</button>
            </div>
        @endif


        <form action="{{route('admin_product_add')}}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
            @csrf

            <!-- Tên sản phẩm -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Tên sản phẩm</label>
                <input type="text" name="name" class="input input-bordered w-full" required>
            </div>

            <!-- Mô tả -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Mô tả</label>
                <textarea name="description" class="textarea textarea-bordered w-full"></textarea>
            </div>

            <!-- Giá -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Giá (VNĐ)</label>
                <input type="number" name="price" class="input input-bordered w-full" step="0.01" required>
            </div>

            <!-- Số lượng -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Số lượng</label>
                <input type="number" name="stock_quantity" class="input input-bordered w-full" required>
            </div>

            <!-- Chọn danh mục -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Danh mục</label>
                <select name="category_id" class="select select-bordered w-full">
                    <option value="">Chọn danh mục</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Chọn nhà cung cấp -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Nhà cung cấp</label>
                <select name="supplier_id" class="select select-bordered w-full">
                    <option value="">Chọn nhà cung cấp</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->supplier_id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Hình ảnh -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Hình ảnh</label>
                <input type="file" name="image" class="file-input file-input-bordered w-full">
            </div>

            <!-- Nút submit -->
            <div class="text-right">
                <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
            </div>
        </form>
    </div>
@endsection
