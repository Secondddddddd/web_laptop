@extends('index')

@section('title', 'Giỏ hàng')

@section('content')
    <div class="container mx-auto p-4">
        <h2 class="text-3xl font-bold mb-4">Giỏ hàng của bạn</h2>

        @if(session('cart') && count(session('cart')) > 0)
            <table class="w-full border">
                <thead>
                <tr>
                    <th class="p-2">Sản phẩm</th>
                    <th class="p-2">Hình ảnh</th>
                    <th class="p-2">Số lượng</th>
                    <th class="p-2">Giá</th>
                    <th class="p-2">Xóa</th>
                </tr>
                </thead>
                <tbody>
                @foreach(session('cart') as $id => $item)
                    <tr>
                        <td class="p-2">{{ $item['name'] }}</td>
                        <td class="p-2"><img src="{{ asset('img/'.$item['image']) }}" width="50"></td>
                        <td class="p-2">
                            <form action="{{ route('cart.update', $id) }}" method="POST">
                                @csrf
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="w-16 border p-1">
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                            </form>
                        </td>
                        <td class="p-2">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }} VNĐ</td>
                        <td class="p-2">
                            <form action="{{ route('cart.remove', $id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                <p class="text-xl font-semibold">Tổng tiền:
                    {{ number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], session('cart'))), 0, ',', '.') }} VNĐ
                </p>
                <form action="{{ route('cart.checkout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success mt-2">Thanh toán</button>
                </form>
            </div>
        @else
            <p>Giỏ hàng của bạn đang trống.</p>
        @endif
    </div>
@endsection
