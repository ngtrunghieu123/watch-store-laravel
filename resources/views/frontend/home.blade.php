@extends('layouts.frontend')
@section('title', 'Trang chủ')
@section('content')
<section class="container mt-4 mb-grid-gutter">
    <section class="container mb-4">
        <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="row g-0 flex-column flex-md-row">
                    <!-- Banner trái -->
                    <div class="col-md-7 position-relative">
                        <!-- Ảnh banner -->
                        <img src="{{ asset('public/img/student-discount-banner.png') }}"
                            alt="Giảm giá cho sinh viên"
                            class="w-100 h-100 object-fit-cover rounded-start-3"
                            style="aspect-ratio: 16/9; height: auto; object-fit: cover;">

                        <!-- Gradient overlay -->
                        <div class="position-absolute top-0 start-0 w-100 h-100 rounded-start-3"
                            style="background: linear-gradient(to right, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 100%);">
                        </div>

                        <!-- Text overlay -->
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center px-5 py-4">
                            <h2 class="display-5 text-light fw-bold">GIẢM GIÁ ĐẶC BIỆT</h2>
                            <h3 class="h2 text-light mb-3">Dành riêng cho <span class="text-warning">SINH VIÊN</span></h3>
                            <p class="text-light mb-4">Giảm tới 50% cho các dòng đồng hồ cao cấp khi mua với thẻ sinh viên</p>
                            <a href="{{ route('frontend.khuyenmai') }}" class="btn btn-danger btn-lg shadow-sm" style="transition: all 0.3s;">
                                <i class="ci-tag me-2"></i>Xem ưu đãi ngay
                            </a>
                        </div>
                    </div>

                    <!-- Countdown + sản phẩm -->
                    <div class="col-md-5 bg-danger rounded-end-3 d-flex flex-column h-100 justify-content-center text-center px-4 py-5">
                        <!-- Countdown -->
                        <h3 class="h2 text-light mb-4">Săn đồng hồ giá sốc</h3>
                        <div class="bg-light rounded-3 p-3 mb-4">
                            <div class="countdown-timer text-center" id="student-countdown">
                                <div class="row g-2">
                                    <div class="col-3">
                                        <div class="bg-white rounded-3 p-2">
                                            <span class="days display-6 fw-bold text-danger">00</span>
                                            <div class="small text-muted">Ngày</div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="bg-white rounded-3 p-2">
                                            <span class="hours display-6 fw-bold text-danger">00</span>
                                            <div class="small text-muted">Giờ</div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="bg-white rounded-3 p-2">
                                            <span class="minutes display-6 fw-bold text-danger">00</span>
                                            <div class="small text-muted">Phút</div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="bg-white rounded-3 p-2">
                                            <span class="seconds display-6 fw-bold text-danger">00</span>
                                            <div class="small text-muted">Giây</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Featured product -->
                        @if($sanphamkhuyenmai->isNotEmpty())
                        @php $featuredProduct = $sanphamkhuyenmai->first(); @endphp
                        <div class="bg-white rounded-3 p-3 shadow-sm">
                            <div class="row align-items-center g-3">
                                <div class="col-4">
                                    <a href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $featuredProduct->loaisanpham->tenloai_slug, 'tensanpham_slug' => $featuredProduct->tensanpham_slug]) }}">
                                        <img src="{{ asset('storage/app/private/'.$featuredProduct->hinhanh) }}"
                                            class="img-fluid rounded-2"
                                            alt="{{ $featuredProduct->tensanpham }}"
                                            style="transition: transform 0.3s;"
                                            onmouseover="this.style.transform='scale(1.05)';"
                                            onmouseout="this.style.transform='scale(1)';">
                                    </a>
                                </div>
                                <div class="col-8 text-start">
                                    <h5 class="fw-bold mb-2">{{ $featuredProduct->tensanpham }}</h5>
                                    <div class="fs-sm mb-2">
                                        <span class="text-danger fw-bold">{{ number_format($featuredProduct->giaKhuyenMai(), 0, ',', '.') }}đ</span>
                                        <del class="text-muted ms-2">{{ number_format($featuredProduct->dongia, 0, ',', '.') }}đ</del>
                                    </div>
                                    <a href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $featuredProduct->loaisanpham->tenloai_slug, 'tensanpham_slug' => $featuredProduct->tensanpham_slug]) }}"
                                        class="btn btn-sm btn-outline-danger">
                                        Chi tiết <i class="ci-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CSS Hover Nút -->
    <style>
        .btn-danger:hover {
            opacity: 0.8;
        }
    </style>

    <section class="container mb-4">
        <div class="tns-carousel">
            <div class="tns-carousel-inner"
                data-carousel-options='{
                "items": 1,
                "nav": true,
                "navPosition": "bottom",
                "navAsThumbnails": true,
                "loop": true,
                "autoHeight": true,
                "controls": true,
                "controlsPosition": "bottom",
                "mouseDrag": true,
                "speed": 500,
                "autoplay": true,
                "autoplayTimeout": 4000
             }'>

                <!-- Đồng hồ cơ mới -->
                @foreach($donghocomoi as $sp)
                <div class="bg-faded-info rounded-3 py-4">
                    <div class="row align-items-center">
                        <div class="col-md-5 px-4 pe-sm-0 ps-sm-5">
                            <span class="badge bg-danger">Đồng hồ cơ mới</span>
                            <h3 class="mt-4 mb-1 text-body fw-light">Sản phẩm mới</h3>
                            <h2 class="mb-1">{{ $sp->tensanpham }}</h2>
                            @if($sp->khuyenMaiHienTai())
                            <p class="h5 text-danger fw-bold mb-4">
                                {{ number_format($sp->giaKhuyenMai()) }}đ
                                <del class="fs-sm text-muted">{{ number_format($sp->dongia) }}đ</del>
                            </p>
                            @else
                            <p class="h5 text-body fw-light mb-4">{{ number_format($sp->dongia) }}đ</p>
                            @endif
                            <a class="btn btn-accent" href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $sp->loaisanpham->tenloai_slug, 'tensanpham_slug' => $sp->tensanpham_slug]) }}">
                                Xem chi tiết<i class="ci-arrow-right fs-ms ms-1"></i>
                            </a>
                        </div>
                        <div class="col-md-7">
                            <img src="{{ asset('storage/app/private/'.$sp->hinhanh) }}"
                                alt="{{ $sp->tensanpham }}"
                                class="img-fluid rounded"
                                style="max-height: 400px; object-fit: contain;" />
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- Đồng hồ thông minh  -->
                @foreach($donghothongminhmoi as $sp)
                <div class="bg-faded-info rounded-3 py-4">
                    <div class="row align-items-center">
                        <div class="col-md-5 px-4 pe-sm-0 ps-sm-5">
                            <span class="badge bg-danger">Đồng hồ thông minh mới</span>
                            <h3 class="mt-4 mb-1 text-body fw-light">Sản phẩm mới</h3>
                            <h2 class="mb-1">{{ $sp->tensanpham }}</h2>
                            @if($sp->khuyenMaiHienTai())
                            <p class="h5 text-danger fw-bold mb-4">
                                {{ number_format($sp->giaKhuyenMai()) }}đ
                                <del class="fs-sm text-muted">{{ number_format($sp->dongia) }}đ</del>
                            </p>
                            @else
                            <p class="h5 text-body fw-light mb-4">{{ number_format($sp->dongia) }}đ</p>
                            @endif
                            <a class="btn btn-accent" href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $sp->loaisanpham->tenloai_slug, 'tensanpham_slug' => $sp->tensanpham_slug]) }}">
                                Xem chi tiết<i class="ci-arrow-right fs-ms ms-1"></i>
                            </a>
                        </div>
                        <div class="col-md-7">
                            <img src="{{ asset('storage/app/private/'.$sp->hinhanh) }}"
                                alt="{{ $sp->tensanpham }}"
                                class="img-fluid rounded"
                                style="max-height: 400px; object-fit: contain;" />
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</section>
<section class="container">
    <div class="tns-carousel border-end">
        <div class="tns-carousel-inner" data-carousel-options="{ &quot;nav&quot;: false, &quot;controls&quot;: false, &quot;autoplay&quot;: true, &quot;autoplayTimeout&quot;: 4000, &quot;loop&quot;: true, &quot;responsive&quot;: {&quot;0&quot;:{&quot;items&quot;:1},&quot;360&quot;:{&quot;items&quot;:2},&quot;600&quot;:{&quot;items&quot;:3},&quot;991&quot;:{&quot;items&quot;:4},&quot;1200&quot;:{&quot;items&quot;:4}} }">
            @foreach ($hangsanxuat as $value)
            <div>
                <a class="d-block bg-white border py-4 py-sm-5 px-2"
                    href="{{ route('frontend.sanpham.hangsanxuat', ['tenhang_slug' => $value->tenhang_slug]) }}"
                    style="margin-right:-.0625rem;">
                    <img class="d-block mx-auto"
                        src="{{ asset('storage/app/private/'.$value->hinhanh) }}"
                        style="width:165px;"
                        alt="{{ $value->tenhang }}" />
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="container pt-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center pt-1 border-bottom pb-4 mb-4">
        <h2 class="h3 mb-0 pt-3 me-2">Sản phẩm khuyến mãi</h2>
        <div class="pt-3">
            <a class="btn btn-outline-accent btn-sm" href="{{ route('frontend.khuyenmai') }}">
                Xem tất cả<i class="ci-arrow-right ms-1 me-n1"></i>
            </a>
        </div>
    </div>

    <div class="row pt-2 mx-n2">
        @if($sanphamkhuyenmai->count() > 0)
        @foreach($sanphamkhuyenmai as $sp)
        <div class="col-lg-6 col-md-6 col-sm-6 px-2 mb-4">
            <div class="card product-card h-100">
                <div class="d-flex">
                    <div class="position-relative" style="width: 40%;">
                        @if($sp->khuyenMaiHienTai())
                        <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                            -{{ $sp->khuyenMaiHienTai()->phantram }}%
                        </span>
                        @endif
                        <a href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $sp->loaisanpham->tenloai_slug, 'tensanpham_slug' => $sp->tensanpham_slug]) }}">
                            <img src="{{ asset('storage/app/private/'.$sp->hinhanh) }}"
                                class="img-fluid p-2"
                                style="height: 180px; object-fit: contain;" alt="{{ $sp->tensanpham }}">
                        </a>
                    </div>
                    <div class="card-body py-2" style="width: 60%;">
                        <a class="product-meta d-block fs-xs pb-1" href="#">{{ $sp->loaisanpham->tenloai }}</a>
                        <h3 class="product-title fs-sm">
                            <a href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $sp->loaisanpham->tenloai_slug, 'tensanpham_slug' => $sp->tensanpham_slug]) }}">
                                {{ $sp->tensanpham }}
                            </a>
                        </h3>
                        <div class="d-flex justify-content-between">
                            <div class="product-price">
                                @if($sp->khuyenMaiHienTai())
                                <span class="text-accent">{{ number_format($sp->giaKhuyenMai(), 0, ',', '.') }}<small>đ</small></span>
                                <del class="fs-sm text-muted">{{ number_format($sp->dongia, 0, ',', '.') }}<small>đ</small></del>
                                @else
                                <span class="text-accent">{{ number_format($sp->dongia, 0, ',', '.') }}<small>đ</small></span>
                                @endif
                            </div>
                            <!-- Thêm đánh giá sao ở đây -->
                            <div class="d-flex align-items-center mt-2">
                                <div class="star-rating">
                                    @php
                                    $rating = $sp->diemDanhGiaTrungBinh() ?: 5; // Mặc định 5 sao nếu không có đánh giá
                                    $count = $sp->soLuongDanhGia();
                                    @endphp

                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <=$rating)
                                        <i class="ci-star-filled star-rating-icon active"></i>
                                        @elseif ($i - 0.5 <= $rating)
                                            <i class="ci-star-half star-rating-icon active"></i>
                                            @else
                                            <i class="ci-star star-rating-icon"></i>
                                            @endif
                                            @endfor
                                </div>
                                @if($count > 0)
                                <span class="d-inline-block fs-xs text-muted ms-1">({{ $count }})</span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-2">
                            <a class="btn btn-primary btn-sm" href="{{ route('frontend.giohang.them', ['tensanpham_slug' => $sp->tensanpham_slug]) }}">
                                <i class="ci-cart fs-sm me-1"></i>Thêm giỏ hàng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @else
        <div class="col-12">
            <div class="alert alert-info">Hiện không có sản phẩm khuyến mãi nào.</div>
        </div>
        @endif
    </div>
</section>


<!-- hiển thị sản phẩm theo loại -->
@foreach($loaisanpham as $lsp)
<section class="container pt-3 pb-2">
    <div class="d-flex flex-wrap justify-content-between align-items-center pt-1 border-bottom pb-4 mb-4">
        <h2 class="h3 mb-0 pt-3 me-2">{{ $lsp->tenloai }}</h2>
        <div class="pt-3">
            <a class="btn btn-outline-accent btn-sm" href="{{ route('frontend.sanpham.phanloai', ['tenloai_slug' => $lsp->tenloai_slug]) }}">
                Xem tất cả<i class="ci-arrow-right ms-1 me-n1"></i>
            </a>
        </div>
    </div>
    <div class="row pt-2 mx-n2">
        @if(isset($sanphamtheoloai[$lsp->id]) && $sanphamtheoloai[$lsp->id]->count() > 0)
        @foreach($sanphamtheoloai[$lsp->id] as $sp)
        <div class="col-lg-3 col-md-4 col-sm-6 px-2 mb-4">
            <div class="card product-card">
                <a class="card-img-top d-block overflow-hidden" href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $lsp->tenloai_slug, 'tensanpham_slug' => $sp->tensanpham_slug]) }}">
                    @if($sp->khuyenMaiHienTai())
                    <div class="badge bg-danger position-absolute top-0 start-0 m-2">
                        -{{ $sp->khuyenMaiHienTai()->phantram }}%
                    </div>
                    @endif
                    <img class="d-block mx-auto" src="{{ asset('storage/app/private/'.$sp->hinhanh) }}" style="width:165px;" />
                </a>
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between">
                        <a class="product-meta d-block fs-xs pb-1" href="#">{{ $lsp->tenloai }}</a>
                        <span class="text-muted fs-xs">{{ $sp->hangSanXuat->tenhang }}</span>
                    </div>
                    <h3 class="product-title fs-sm">
                        <a href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $lsp->tenloai_slug, 'tensanpham_slug' => $sp->tensanpham_slug]) }}">{{ $sp->tensanpham }}</a>
                    </h3>
                    <div class="d-flex justify-content-between">
                        <div class="product-price">
                            @if($sp->khuyenMaiHienTai())
                            <span class="text-accent">{{ number_format($sp->giaKhuyenMai(), 0, ',', '.') }}<small>đ</small></span>
                            <del class="fs-sm text-muted">{{ number_format($sp->dongia, 0, ',', '.') }}<small>đ</small></del>
                            @else
                            <span class="text-accent">{{ number_format($sp->dongia, 0, ',', '.') }}<small>đ</small></span>
                            @endif
                        </div>
                        <!-- Thêm đánh giá sao ở đây -->
                        <div class="d-flex align-items-center mt-2">
                            <div class="star-rating">
                                @php
                                $rating = $sp->diemDanhGiaTrungBinh() ?: 5; // Mặc định 5 sao nếu không có đánh giá
                                $count = $sp->soLuongDanhGia();
                                @endphp

                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <=$rating)
                                    <i class="ci-star-filled star-rating-icon active"></i>
                                    @elseif ($i - 0.5 <= $rating)
                                        <i class="ci-star-half star-rating-icon active"></i>
                                        @else
                                        <i class="ci-star star-rating-icon"></i>
                                        @endif
                                        @endfor
                            </div>
                            @if($count > 0)
                            <span class="d-inline-block fs-xs text-muted ms-1">({{ $count }})</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body card-body-hidden">
                    <a class="btn btn-primary btn-sm d-block w-100 mb-2" href="{{ route('frontend.giohang.them', ['tensanpham_slug' => $sp->tensanpham_slug]) }}">
                        <i class="ci-cart fs-sm me-1"></i>Thêm vào giỏ hàng
                    </a>
                    <form action="{{ route('user.yeuthich.them', ['id' => $sp->id]) }}" method="post" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                            <i class="ci-heart"></i> Yêu thích
                        </button>
                    </form>
                </div>
            </div>
            <hr class="d-sm-none">
        </div>
        @endforeach
        @else
        <div class="col-12">
            <div class="alert alert-info">Không có sản phẩm nào thuộc loại này.</div>
        </div>
        @endif
    </div>
</section>
@endforeach
<script>
    // Thực hiện sau khi trang đã load xong
    window.onload = function() {
        console.log('Window loaded');

        // Đảm bảo các phần tử đã được tải
        const daysElement = document.querySelector('#student-countdown .days');
        const hoursElement = document.querySelector('#student-countdown .hours');
        const minutesElement = document.querySelector('#student-countdown .minutes');
        const secondsElement = document.querySelector('#student-countdown .seconds');

        if (!daysElement || !hoursElement || !minutesElement || !secondsElement) {
            console.error('Không thể tìm thấy phần tử đếm ngược');
            return;
        }

        // Đếm ngược đến cuối tháng hiện tại
        const now = new Date();
        const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59);

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = endOfMonth - now;

            // Tính toán thời gian
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Hiển thị kết quả
            daysElement.textContent = days < 10 ? '0' + days : days;
            hoursElement.textContent = hours < 10 ? '0' + hours : hours;
            minutesElement.textContent = minutes < 10 ? '0' + minutes : minutes;
            secondsElement.textContent = seconds < 10 ? '0' + seconds : seconds;

            // Khi đếm ngược kết thúc
            if (distance < 0) {
                clearInterval(countdown);
                document.querySelector('#student-countdown').innerHTML = '<div class="alert alert-warning mb-0">Chương trình đã kết thúc!</div>';
            }
        }

        // Cập nhật ngay lần đầu
        updateCountdown();

        // Cập nhật đồng hồ đếm ngược mỗi giây
        const countdown = setInterval(updateCountdown, 1000);
    };
</script>
@endsection