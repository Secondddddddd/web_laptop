@extends('index')

@section('title', 'Trang Chủ')

@section('content')

    <!-- CSS cho slideshow quảng cáo và slider sản phẩm -->
    <style>
        /* CSS cho Slideshow Quảng cáo (tùy chỉnh bằng JS) */
        .mySlides {
            display: none;
            position: relative;
        }
        .numberText {
            position: absolute;
            bottom: 8px;
            left: 8px;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            font-size: 14px;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .fade {
            animation: fade 1.5s;
        }
        @keyframes fade {
            from { opacity: 0.4; }
            to { opacity: 1; }
        }
    </style>

    <!-- Wrapper tổng với 3 cột -->
    <div class="max-w-7xl mx-auto px-4 py-6 grid grid-cols-3 gap-4">
        <!-- Slideshow Quảng cáo bên trái (chiếm 2/3) -->
        <div class="slideshow-container relative w-full h-96 mx-auto rounded-lg overflow-hidden col-span-2">
            <div class="mySlides fade">
                <div class="numberText">1 / 4</div>
                <a href="{{ route('product.detail', ['product_id' => 48, 'product']) }}">
                    <img src="{{ asset('img_ads/dell_inspiron_7445.jpg') }}" class="w-full h-96 object-cover rounded-lg" alt="Dell Inspiron">
                </a>
            </div>
            <div class="mySlides fade">
                <div class="numberText">2 / 4</div>
                <a href="{{ route('product.detail', ['product_id' => 50, 'product']) }}">
                    <img src="{{ asset('img_ads/geekPro.jpg') }}" class="w-full h-96 object-cover rounded-lg" alt="GeekPro">
                </a>
            </div>
            <div class="mySlides fade">
                <div class="numberText">3 / 4</div>
                <a href="{{ route('product.detail', ['product_id' => 51, 'product']) }}">
                    <img src="{{ asset('img_ads/lenovo_loq.jpg') }}" class="w-full h-96 object-cover rounded-lg" alt="Lenovo Loq">
                </a>
            </div>
            <div class="mySlides fade">
                <div class="numberText">4 / 4</div>
                <a href="{{ route('product.detail', ['product_id' => 49, 'product']) }}">
                    <img src="{{ asset('img_ads/nitro_v15.jpg') }}" class="w-full h-96 object-cover rounded-lg" alt="Nitro V15">
                </a>
            </div>

            <!-- Nút điều hướng cho slideshow quảng cáo -->
            <a class="prev absolute top-1/2 left-0 transform -translate-y-1/2 bg-gray-800 bg-opacity-50 text-white px-4 py-2 rounded-r-lg cursor-pointer" onclick="plusSlides(-1)">❮</a>
            <a class="next absolute top-1/2 right-0 transform -translate-y-1/2 bg-gray-800 bg-opacity-50 text-white px-4 py-2 rounded-l-lg cursor-pointer" onclick="plusSlides(1)">❯</a>
        </div>

        <!-- Hình ảnh cố định bên phải (chiếm 1/3) -->
        <div class="grid grid-rows-2 h-96 col-span-1 gap-4">
            <a href="{{ route('product.detail', ['product_id' => 52, 'product']) }}">
                <img src="{{ asset('img_ads/thinkbook.jpg') }}" class="w-full h-full object-cover rounded-lg shadow-md" alt="ThinkBook">
            </a>
            <a href="{{ route('product.detail', ['product_id' => 53, 'product']) }}">
                <img src="{{ asset('img_ads/xiaoxin_pro16.jpg') }}" class="w-full h-full object-cover rounded-lg shadow-md" alt="XiaoXin Pro 16">
            </a>
        </div>
    </div>

    <div class="container p-5 mx-auto w-[1200px]">
        <!-- Swiper Container -->
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <!-- Chia sản phẩm thành nhóm 4 sản phẩm mỗi slide -->
                @foreach ($laptops->take(20)->chunk(5) as $chunk)
                    <div class="swiper-slide">
                        <div class="grid grid-cols-5 gap-4">
                            @foreach ($chunk as $laptop)
                                <div class="border p-3 text-center rounded shadow">
                                    <img src="{{ asset('img/'.$laptop->image_url) }}" alt="{{ $laptop->name }}" class="block mx-auto w-48 h-32 object-cover rounded">
                                    <h3 class="mt-2 font-semibold">{{ $laptop->name }}</h3>
                                    <p class="text-blue-500 font-bold">{{ number_format($laptop->price, 0, ',', '.') }}đ</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Pagination & Navigation -->
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>

    <!-- Nhúng Swiper CSS qua CDN (nếu chưa thêm qua Vite) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />

    <!-- Nhúng Swiper JS qua CDN -->
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>
    <script>
        // Khởi tạo Swiper
        const swiper = new Swiper('.mySwiper', {
            loop: true,
            spaceBetween: 20,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    </script>
    <!-- JavaScript cho Custom Ads Slideshow -->
    <script>
        // Khởi tạo slideshow quảng cáo
        let slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function showSlides(n) {
            const slides = document.getElementsByClassName("mySlides");
            if (n > slides.length) { slideIndex = 1; }
            if (n < 1) { slideIndex = slides.length; }
            for (let i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slides[slideIndex - 1].style.display = "block";
        }

        // Tự động chuyển quảng cáo sau 5 giây
        setInterval(() => { plusSlides(1); }, 5000);
    </script>
@endsection
