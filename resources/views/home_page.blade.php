@extends('index')

@section('title', 'Trang Chủ')

@section('content')

    <!-- Wrapper tổng -->
    <div class="max-w-7xl mx-auto px-4 py-6 grid grid-cols-3 gap-4">

        <!-- Slideshow bên trái (2/3) -->
        <div class="slideshow-container relative w-full h-96 mx-auto rounded-lg overflow-hidden col-span-2">

            <div class="mySlides fade">
                <div class="numberText">1 / 4</div>
                <a href="#"><img src="{{ asset('img_ads/dell_inspiron_7445.jpg') }}" class="w-full h-96 object-cover rounded-lg"></a>
            </div>

            <div class="mySlides fade">
                <div class="numberText">2 / 4</div>
                <a href="#"><img src="{{ asset('img_ads/geekPro.jpg') }}" class="w-full h-96 object-cover rounded-lg"></a>
            </div>

            <div class="mySlides fade">
                <div class="numberText">3 / 4</div>
                <a href="#"><img src="{{ asset('img_ads/lenovo_loq.jpg') }}" class="w-full h-96 object-cover rounded-lg"></a>
            </div>

            <div class="mySlides fade">
                <div class="numberText">4 / 4</div>
                <a href="#"><img src="{{ asset('img_ads/nitro_v15.jpg') }}" class="w-full h-96 object-cover rounded-lg"></a>
            </div>

            <!-- Nút điều hướng -->
            <a class="prev absolute top-1/2 left-0 transform -translate-y-1/2 bg-gray-800 bg-opacity-50 text-white px-4 py-2 rounded-r-lg cursor-pointer" onclick="plusSlides(-1)">❮</a>
            <a class="next absolute top-1/2 right-0 transform -translate-y-1/2 bg-gray-800 bg-opacity-50 text-white px-4 py-2 rounded-l-lg cursor-pointer" onclick="plusSlides(1)">❯</a>

        </div>

        <!-- Hình ảnh bên phải (1/3) -->
        <div class="grid grid-rows-[1fr_1fr] h-96 col-span-1">
            <a href="#"><img src="{{ asset('img_ads/thinkbook.jpg') }}" class="w-full h-full object-cover rounded-lg shadow-md"></a>
            <a href="#"><img src="{{ asset('img_ads/xiaoxin_pro16.jpg') }}" class="w-full h-full object-cover rounded-lg shadow-md"></a>
        </div>

    </div>

    <!-- CSS -->
    <style>
        .mySlides {
            display: none;
            position: relative; /* Giúp căn chỉnh chính xác các phần tử con */
        }

        .numberText {
            position: absolute;
            bottom: 8px;
            background: rgba(0, 0, 0, 0.6); /* Nền tối giúp dễ đọc số */
            color: white;
            font-size: 14px;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .fade { animation: fade 1.5s; }
        @keyframes fade { from { opacity: 0.4 } to { opacity: 1 } }
    </style>

    <!-- JavaScript -->
    <script>
        let slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            let i;
            let slides = document.getElementsByClassName("mySlides");

            if (n > slides.length) { slideIndex = 1 }
            if (n < 1) { slideIndex = slides.length }

            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }

            slides[slideIndex-1].style.display = "block";
        }

        // Tự động chuyển slide sau 5 giây
        setInterval(() => { plusSlides(1); }, 5000);
    </script>

@endsection
