<!--  Trang này chưa có tác dụng gì hết sau này fix sau -->
@extends('layouts.frontend')
@section('title', 'Khuyến mãi')
@section('content')
<div class="page-title-overlap bg-dark pt-4">
    <div class="container d-lg-flex justify-content-between py-2 py-lg-3">
        <div class="order-lg-2 mb-3 mb-lg-0 pt-lg-2">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-light flex-lg-nowrap justify-content-center justify-content-lg-start">
                    <li class="breadcrumb-item"><a class="text-nowrap" href="{{ route('frontend.home') }}"><i class="ci-home"></i>Trang chủ</a></li>
                    <li class="breadcrumb-item text-nowrap active" aria-current="page">Khuyến mãi</li>
                </ol>
            </nav>
        </div>
        <div class="order-lg-1 pe-lg-4 text-center text-lg-start">
            <h1 class="h3 text-light mb-0">{{ $khuyenmai->tenkhuyenmai }}</h1>
        </div>
    </div>
</div>

<div class="container pb-5 mb-2 mb-md-4">
    <div class="row pt-3 mx-n2">
        <div class="col-lg-12 mb-3">
            <div class="alert alert-warning">
                <h5 class="text-center">Khuyến mãi giảm {{ $khuyenmai->phantram }}%</h5>
                <p class="text-center mb-0">
                    {!! $khuyenmai->mota !!}
                </p>
                <p class="text-center">Khuyến mãi còn {{ Carbon\Carbon::parse($khuyenmai->ngayketthuc)->diffInDays(Carbon\Carbon::now()) + 1 }} ngày nữa kết thúc!</p>
            </div>
        </div>

        <!-- Sản phẩm khuyến mãi -->
        @foreach($sanpham as $sp)
        <div class="col-lg-3 col-md-4 col-sm-6 px-2 mb-4">
            <div class="card product-card">
                <span class="badge bg-danger position-absolute top-0 start-0 m-2">-{{ $khuyenmai->phantram }}%</span>
                <a class="card-img-top d-block overflow-hidden" href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $sp->loaisanpham->tenloai_slug, 'tensanpham_slug' => $sp->tensanpham_slug]) }}">
                    <img src="{{ env('APP_URL') . '/storage/app/' . $sp->hinhanh }}" alt="{{ $sp->tensanpham }}">
                </a>
                <div class="card-body py-2">
                    <a class="product-meta d-block fs-xs pb-1" href="{{ route('frontend.sanpham.loai', ['tenloai_slug' => $sp->loaisanpham->tenloai_slug]) }}">{{ $sp->loaisanpham->tenloai }}</a>
                    <h3 class="product-title fs-sm"><a href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $sp->loaisanpham->tenloai_slug, 'tensanpham_slug' => $sp->tensanpham_slug]) }}">{{ $sp->tensanpham }}</a></h3>

                    <!-- Gộp phần giá và đánh giá sao vào cùng một dòng -->
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Phần giá -->
                        <div class="product-price">
                            <span class="text-accent">{{ number_format($sp->giaKhuyenMai()) }}<small>đ</small></span>
                            <del class="fs-sm text-muted">{{ number_format($sp->dongia) }}<small>đ</small></del>
                        </div>

                        <!-- Phần đánh giá sao -->
                        <div class="d-flex align-items-center">
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
                </div>
            </div>
            <hr class="d-sm-none">
        </div>
        @endforeach
    </div>
</div>
@endsection