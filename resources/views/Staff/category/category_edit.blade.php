@extends('staff.staff_dashboard')

@section('title', 'Edit Category')

@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-4">Chỉnh sửa thể loại</h2>

        <x-alert-result />

        <form action="{{ route('staff_category_update', ['id' => $category->category_id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Tên thể loại</label>
                <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full p-2 border rounded-lg" required>
                @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Cập nhật
            </button>
            <a href="{{ route('staff_category_list') }}" class="ml-4 text-gray-500 hover:text-gray-700">Quay lại</a>
        </form>
    </div>
@endsection
