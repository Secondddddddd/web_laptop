@extends('admin.admin_dashboard')

@section('title', 'Thêm thể loại')

@section('content')


    <div class="container mx-auto px-4 mt-5">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Thêm thể loại mới</h1>

        <x-alert-result />

        <form action="{{ route('admin_category_store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Tên thể loại:</label>
                <input type="text" id="name" name="name" class="border border-gray-300 p-2 w-full rounded" required>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Thêm</button>
        </form>
    </div>
@endsection
