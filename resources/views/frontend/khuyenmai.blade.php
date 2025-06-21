@extends('layouts.frontend')

@section('title', 'Sản Phẩm Khuyến Mãi')

@section('content')
<div class="container pb-5 mb-2 mb-md-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active">Khuyến mãi đặc biệt</li>
        </ol>
    </nav>

    <div class="mb-4">
        <div class="alert alert-info d-flex align-items-center">
            <i class="ci-discount fs-lg me-2"></i>
            <div>
                <h5 class="mb-1">Khuyến mãi đặc biệt</h5>
                <p class="mb-0">Danh sách sản phẩm đang được giảm giá đặc biệt.</p>
            </div>
        </div>
    </div>

    <!-- Danh sách sản phẩm khuyến mãi -->
    <div class="row pt-2 mx-n2">
        @if($sanpham->count() > 0)
        @foreach($sanpham as $sp)
        <div class="col-lg-3 col-md-4 col-sm-6 px-2 mb-4">
            <div class="card product-card h-100">
                @if($sp->khuyenMaiHienTai())
                <span class="badge bg-danger position-absolute top-0 start-0 m-2">-{{ $sp->khuyenMaiHienTai()->phantram }}%</span>
                @endif

                <a href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $sp->loaisanpham->tenloai_slug, 'tensanpham_slug' => $sp->tensanpham_slug]) }}">
                    <img src="{{ asset('storage/app/private/' . $sp->hinhanh) }}"
                        alt="{{ $sp->tensanpham }}"
                        class="card-img-top"
                        style="height: 200px; object-fit: contain;">
                </a>
                <div class="card-body">
                    <h3 class="product-title fs-sm">
                        <a href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $sp->loaisanpham->tenloai_slug, 'tensanpham_slug' => $sp->tensanpham_slug]) }}">
                            {{ $sp->tensanpham }}
                        </a>
                    </h3>
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Phần giá -->
                        @if($sp->khuyenMaiHienTai())
                        <div class="product-price">
                            <span class="text-accent">{{ number_format($sp->giaKhuyenMai()) }}đ</span>
                            <del class="fs-sm text-muted">{{ number_format($sp->dongia) }}đ</del>
                        </div>
                        @else
                        <div class="product-price">
                            <span class="text-accent">{{ number_format($sp->dongia) }}đ</span>
                        </div>
                        @endif

                        <!-- Phiên bản rút gọn cho đánh giá sao -->
                        <div class="d-flex align-items-center">
                            @php
                            $rating = $sp->diemDanhGiaTrungBinh() ?: 5;
                            $count = $sp->soLuongDanhGia();
                            @endphp
                            <i class="ci-star-filled star-rating-icon active"></i>
                            <span class="fs-xs ms-1">{{ number_format($rating, 1) }}</span>
                            @if($count > 0)
                            <span class="d-inline-block fs-xs text-muted ms-1">({{ $count }})</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('frontend.giohang.them', ['tensanpham_slug' => $sp->tensanpham_slug]) }}"
                        class="btn btn-primary btn-sm w-100">
                        <i class="ci-cart fs-sm me-1"></i>Thêm vào giỏ
                    </a>
                </div>
                <div class="card-footer">
                    <form action="{{ route('user.yeuthich.them', ['id' => $sp->id]) }}" method="post" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                            <i class="ci-heart"></i> Yêu thích
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
        @else
        <div class="col-12">
            <div class="alert alert-warning">
                <h4>Không có sản phẩm khuyến mãi!</h4>
                <p>Hiện tại không có sản phẩm nào đang được khuyến mãi.</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Phân trang -->
    <div class="d-flex justify-content-center mt-4">
        {{ $sanpham->links() }}
    </div>
</div>
@endsection