@extends('layouts.frontend')
@section('title', 'Sản phẩm yêu thích')
@section('content')
<div class="container pb-5 mb-2 mb-md-4">
    <div class="row">
        <div class="col-md-12">
            <h2 class="h4 pb-3">Sản phẩm yêu thích</h2>

            @if($yeuthich->isEmpty())
            <div class="alert alert-warning">
                Chưa có sản phẩm yêu thích nào.
            </div>
            @else
            <div class="row row-cols-2 row-cols-md-4 g-3"> <!-- Thay đổi grid system -->
                @foreach($yeuthich as $sp)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="position-relative">
                            @if($sp->sanpham->khuyenMaiHienTai())
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">-{{ $sp->sanpham->khuyenMaiHienTai()->phantram }}%</span>
                            @endif
                            <img src="{{ asset('storage/app/private/' . $sp->sanpham->hinhanh) }}"
                                class="card-img-top p-2"
                                style="height: 160px; object-fit: contain;">
                        </div>
                        <div class="card-body p-3">
                            <h6 class="card-title text-truncate">{{ $sp->sanpham->tensanpham }}</h6>

                            <!-- Giá và đánh giá sao -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <!-- Phần giá -->
                                <div>
                                    @if($sp->sanpham->khuyenMaiHienTai())
                                    <span class="text-danger fw-bold">{{ number_format($sp->sanpham->giaKhuyenMai()) }}đ</span>
                                    <del class="text-muted ms-2 small">{{ number_format($sp->sanpham->dongia) }}đ</del>
                                    @else
                                    <span class="text-danger fw-bold">{{ number_format($sp->sanpham->dongia) }}đ</span>
                                    @endif
                                </div>

                                <!-- Đánh giá sao rút gọn -->
                                <div class="d-flex align-items-center">
                                    @php
                                    $rating = $sp->sanpham->diemDanhGiaTrungBinh() ?: 5;
                                    @endphp
                                    <i class="ci-star-filled star-rating-icon active me-1"></i>
                                    <span class="fs-xs">{{ number_format($rating, 1) }}</span>
                                </div>
                            </div>

                            <!-- Nút hành động -->
                            <div class="d-grid gap-2">
                                <a href="{{ route('frontend.giohang.them', ['tensanpham_slug' => $sp->sanpham->tensanpham_slug]) }}"
                                    class="btn btn-primary btn-sm">
                                    <i class="ci-cart"></i> Thêm vào giỏ
                                </a>

                                <div class="d-flex gap-1">
                                    <a href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $sp->sanpham->loaisanpham->tenloai_slug, 'tensanpham_slug' => $sp->sanpham->tensanpham_slug]) }}"
                                        class="btn btn-outline-secondary btn-sm flex-grow-1">
                                        <i class="ci-eye"></i> Chi tiết
                                    </a>
                                    <a href="{{ route('user.yeuthich.xoa', ['id' => $sp->sanpham_id]) }}"
                                        class="btn btn-outline-danger btn-sm flex-grow-1">
                                        <i class="ci-heart-filled"></i> Xóa
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
<style>
    .btn-icon {
        aspect-ratio: 1;
        padding: 0.25rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .star-rating-icon.active {
        color: #ffc107;
    }

    .card-title {
        height: 2.5rem;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
</style>
@endsection