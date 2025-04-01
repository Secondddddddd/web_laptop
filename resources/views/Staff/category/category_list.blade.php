@extends('staff.staff_dashboard')

@section('title', 'Category List')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-5">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Danh sách danh mục</h2>

        <x-alert-result />

        <a href="{{ route('staff_category_add') }}"
           class="bg-green-500 text-white px-4 py-2 rounded mb-4 inline-block">
            Thêm Thể Loại
        </a>

        <div class="overflow-x-auto">
            <div id="category-table"></div>
        </div>
    </div>

    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            const categoryTable = document.getElementById('category-table');
            if (categoryTable) {
                new Grid({
                    columns: [
                        { id: 'index', name: '#' },
                        { id: 'name', name: 'Tên danh mục' },
                        { id: 'actions', name: 'Hành động' },
                    ],
                    pagination: {
                        enabled: true,
                        limit: 10
                    },
                    sort: true,
                    search: true,
                    server: {
                        url: '/api/admin/categories',
                        then: data => data.map((category, index) => [
                            index + 1,
                            category.name,
                            html(`
                                <a href="/staff/category/${category.category_id}/edit" class="text-blue-500 hover:underline mr-2">Sửa</a>
                                <form action="/staff/category/${category.category_id}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-700">Xóa</button>
                                </form>
                            `)
                        ])
                    },
                    language: {
                        'search': {
                            'placeholder': '🔍 Tìm kiếm danh mục...'
                        },
                        'pagination': {
                            'previous': '⬅️',
                            'next': '➡️',
                            'showing': 'Hiển thị',
                            'results': () => 'danh mục'
                        },
                        'loading': 'Đang tải...',
                        'noRecordsFound': 'Không tìm thấy danh mục nào',
                        'error': 'Có lỗi xảy ra khi tải dữ liệu'
                    }
                }).render(categoryTable);
            }
        });
    </script>
@endsection
