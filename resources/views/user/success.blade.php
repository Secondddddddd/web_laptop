@extends('index')

@section('content')
    <div class="container mx-auto text-center py-10">
        <h1 class="text-3xl font-bold mb-4">Đặt hàng thành công!</h1>
        <p>Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ xử lý đơn hàng của bạn trong thời gian sớm nhất.</p>
        <a href="{{ route('home') }}" class="btn btn-primary mt-5">Quay về trang chủ</a>
    </div>
@endsection
