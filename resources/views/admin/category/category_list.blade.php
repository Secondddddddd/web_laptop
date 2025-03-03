@extends('admin.admin_dashboard')

@section('title', 'Category List')

@section('content')

    <div class="container">
        <h2 class="mb-4">Category List</h2>
        <a href="{{ route('admin_category_add') }}" class="bg-green-500 text-white px-4 py-2 rounded mb-4 inline-block">
            Thêm Thể Loại
        </a>
        <table class="table-auto border-collapse border border-gray-400 w-full">
            <thead>
            <tr class="bg-gray-200">
                <th class="border border-gray-400 p-2">ID</th>
                <th class="border border-gray-400 p-2">Category Name</th>
                <th class="border border-gray-400 p-2">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($categories as $category)
                <tr class="hover:bg-gray-100">
                    <td class="border border-gray-400 p-2">{{ $category->category_id }}</td>
                    <td class="border border-gray-400 p-2">{{ $category->name }}</td>
                    <td class="border border-gray-400 p-2">
                        <a href="{{ route('admin_category_edit', ['id' => $category->category_id]) }}" class="bg-blue-500 text-white px-2 py-1 rounded">Edit</a>
                        <form action="{{ route('admin_category_delete', ['id' => $category->category_id]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Xóa</button>
                        </form>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- Thanh phân trang -->
        <div class="mt-4 mb-4 flex justify-center">
            {{ $categories->links('vendor.pagination.tailwind') }}
        </div>

    </div>
@endsection
