@extends('staff.staff_dashboard')

@section('title', 'Product Manage')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-5">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Danh sách sản phẩm</h1>
        <button class="btn btn-outline btn-primary add-product-btn mb-4">Thêm sản phẩm mới</button>

        <x-alert-result />

        <div class="overflow-x-auto">
            <div id="product-table"></div>
        </div>
    </div>
    <script type="module">
        document.addEventListener('DOMContentLoaded', function () {
            const productTable = document.getElementById('product-table');
            if (productTable) {
                new Grid({
                    columns: [
                        { id: 'index', name: '#' },
                        { id: 'name', name: 'Tên sản phẩm' },
                        { id: 'price', name: 'Giá' },
                        { id: 'stock_quantity', name: 'Số lượng' },
                        { id: 'category', name: 'Danh mục' },
                        { id: 'supplier', name: 'Nhà cung cấp' },
                        { id: 'image', name: 'Hình ảnh' },
                        { id: 'actions', name: 'Hành động' },
                    ],
                    pagination: {
                        enabled: true,
                        limit: 10
                    },
                    sort: true,
                    search: true,
                    server: {
                        url: '/api/admin/products',
                        then: data => data.map((product, index) => [
                            index + 1,
                            product.name,
                            parseFloat(product.price).toLocaleString('vi-VN', { style: 'currency', currency: 'VND' }),
                            product.stock_quantity,
                            product.category?.name || 'Không có',
                            product.supplier?.name || 'Không có',
                            html(`<img src="/img/${product.image_url ?? 'default.jpg'}" class="h-12 w-12 rounded" alt="Hình ảnh">`),
                            html(`
                        <a href="/staff/products/${product.product_id}/edit" class="text-blue-500 hover:underline mr-2">Sửa</a>
                        <form action="/staff/products/${product.product_id}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-700">Xóa</button>
                        </form>
                    `)
                        ])
                    },
                    language: {
                        'search': {
                            'placeholder': '🔍 Tìm kiếm sản phẩm...'
                        },
                        'pagination': {
                            'previous': '⬅️',
                            'next': '➡️',
                            'showing': 'Hiển thị',
                            'results': () => 'sản phẩm'
                        },
                        'loading': 'Đang tải...',
                        'noRecordsFound': 'Không tìm thấy sản phẩm nào',
                        'error': 'Có lỗi xảy ra khi tải dữ liệu'
                    }
                }).render(productTable);
            }
        });
    </script>
@endsection
