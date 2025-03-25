@extends('admin.admin_dashboard')

@section('title', 'Danh sách người dùng')

@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-4">Danh sách người dùng</h2>

        <a href="{{ route('admin_user_add') }}" class="bg-green-500 text-white px-4 py-2 rounded mb-4 inline-block">
            + Thêm Người Dùng
        </a>

        <x-alert-result />

        <div id="userTable"></div>
    </div>

    <!-- Grid.js -->
    <script src="https://unpkg.com/gridjs/dist/gridjs.umd.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/gridjs/dist/theme/mermaid.min.css">

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            new Grid({
                columns: [
                    { name: "ID", width: "10%" },
                    "Họ và Tên",
                    "Email",
                    "Số điện thoại",
                    "Ngày tạo",
                    { name: "Hành động", width: "20%", formatter: (cell, row) => gridjs.html(`
                        <a href="/admin/users/${row.cells[0].data}" class="bg-blue-500 text-white px-2 py-1 rounded">Chi tiết</a>
                        <a href="/admin/users/edit/${row.cells[0].data}" class="bg-yellow-500 text-white px-2 py-1 rounded">Chỉnh sửa</a>
                        <form action="/admin/users/disable/${row.cells[0].data}" method="POST" style="display:inline;">
                            <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-700">Vô hiệu hóa</button>
                        </form>
                    `)}
                ],
                server: {
                    url: "{{ route('admin.users.json') }}",
                    then: data => data.map(user => [
                        user.user_id,
                        user.full_name,
                        user.email,
                        user.phone || "N/A",
                        new Date(user.created_at).toLocaleDateString("vi-VN"),
                        null // Cột này sẽ được xử lý bởi formatter
                    ])
                },
                search: true,
                pagination: {
                    limit: 10
                },
                sort: true,
                language: {
                    search: {
                        placeholder: "Tìm kiếm..."
                    },
                    pagination: {
                        previous: "Trước",
                        next: "Sau",
                        showing: "Hiển thị",
                        results: () => "kết quả"
                    }
                }
            }).render(document.getElementById("userTable"));
        });
    </script>
@endsection
