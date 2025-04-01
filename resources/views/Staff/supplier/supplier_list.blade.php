@extends('staff.staff_dashboard')

@section('title', 'Danh sách nhà cung cấp')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-5">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Danh sách nhà cung cấp</h1>

        <x-alert-result />

        <a href="{{ route('staff_supplier_add') }}" class="bg-green-500 text-white px-4 py-2 rounded mb-4 inline-block">
            Thêm Nhà Cung Cấp
        </a>

        <div class="overflow-x-auto">
            <div id="supplier-table"></div>
        </div>
    </div>

    <script type="module">

        document.addEventListener('DOMContentLoaded', function () {
            const supplierTable = document.getElementById('supplier-table');
            if (supplierTable) {
                new Grid({
                    columns: [
                        { id: 'index', name: '#' },
                        { id: 'name', name: 'Tên nhà cung cấp' },
                        { id: 'email', name: 'Email' },
                        { id: 'phone', name: 'Số điện thoại' },
                        { id: 'address', name: 'Địa chỉ' },
                        { id: 'actions', name: 'Hành động' }
                    ],
                    pagination: {
                        enabled: true,
                        limit: 10
                    },
                    sort: true,
                    search: true,
                    server: {
                        url: '/api/admin/suppliers',
                        then: data => data.map((supplier, index) => [
                            index + 1,
                            supplier.name,
                            supplier.email,
                            supplier.phone,
                            supplier.address,
                            html(`
                                <a href="/staff/suppliers/${supplier.supplier_id}/edit" class="text-blue-500 hover:underline mr-2">Sửa</a>
                                <form action="/staff/suppliers/${supplier.supplier_id}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa nhà cung cấp này?');">
                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-700">Xóa</button>
                                </form>
                            `)
                        ])
                    },
                    language: {
                        'search': {
                            'placeholder': '🔍 Tìm kiếm nhà cung cấp...'
                        },
                        'pagination': {
                            'previous': '⬅️',
                            'next': '➡️',
                            'showing': 'Hiển thị',
                            'results': () => 'kết quả'
                        },
                        'loading': 'Đang tải...',
                        'noRecordsFound': 'Không tìm thấy nhà cung cấp nào',
                        'error': 'Có lỗi xảy ra khi tải dữ liệu'
                    }
                }).render(supplierTable);
            }
        });
    </script>
@endsection
