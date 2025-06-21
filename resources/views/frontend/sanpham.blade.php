@extends('layouts.frontend')

@section('title', isset($loai) ? $loai->tenloai : 'Sản phẩm')
@section('content')
<div class="container pb-5 mb-2 mb-md-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('frontend.sanpham') }}">Sản phẩm</a></li>
            @if(isset($loai))
            <li class="breadcrumb-item active">{{ $loai->tenloai }}</li>
            @endif
            @if(isset($hang))
            <li class="breadcrumb-item active">{{ $hang->tenhang }}</li>
            @endif
        </ol>
    </nav>

    @if(isset($tieude))
    <div class="mb-4">
        <div class="alert alert-info d-flex align-items-center">
            <i class="ci-filter-alt me-2"></i>
            <div>Đã chọn: {{ $tieude }}</div>
        </div>
    </div>
    @endif

    <!-- Kiểm tra và hiển thị danh sách sản phẩm theo loại -->
    @if(isset($loai))
    @if($sanpham->isEmpty())
    <div class="alert alert-warning">
        Không có sản phẩm.
    </div>
    @else
    <div class="mb-4">
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('frontend.sanpham.phanloai.gia', ['tenloai_slug' => $loai->tenloai_slug, 'mucgia' => 'duoi-1-trieu']) }}"
                class="btn btn-outline-primary btn-sm">
                Dưới 1 triệu
            </a>
            <a href="{{ route('frontend.sanpham.phanloai.gia', ['tenloai_slug' => $loai->tenloai_slug, 'mucgia' => '1-9-trieu']) }}"
                class="btn btn-outline-primary btn-sm">
                1-9 triệu
            </a>
            <a href="{{ route('frontend.sanpham.phanloai.gia', ['tenloai_slug' => $loai->tenloai_slug, 'mucgia' => 'tren-10-trieu']) }}"
                class="btn btn-outline-primary btn-sm">
                Trên 10 triệu
            </a>
        </div>
    </div>
    @endif
    @endif

    <!-- Kiểm tra và hiển thị danh sách sản phẩm theo hãng -->
    @if(isset($hang))
    @if($sanpham->isEmpty())
    <div class="alert alert-warning">
        Không có sản phẩm.
    </div>
    @else
    <div class="mb-4">
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('frontend.sanpham.hangsanxuat.gia', ['tenhang_slug' => $hang->tenhang_slug, 'mucgia' => 'duoi-1-trieu']) }}"
                class="btn btn-outline-primary btn-sm">
                Dưới 1 triệu
            </a>
            <a href="{{ route('frontend.sanpham.hangsanxuat.gia', ['tenhang_slug' => $hang->tenhang_slug, 'mucgia' => '1-9-trieu']) }}"
                class="btn btn-outline-primary btn-sm">
                1-9 triệu
            </a>
            <a href="{{ route('frontend.sanpham.hangsanxuat.gia', ['tenhang_slug' => $hang->tenhang_slug, 'mucgia' => 'tren-10-trieu']) }}"
                class="btn btn-outline-primary btn-sm">
                Trên 10 triệu
            </a>
        </div>
    </div>
    @endif
    @endif

    <!-- Thông báo không tìm thấy sản phẩm -->
    @if(isset($khongtimthay) && $khongtimthay)
    <div class="alert alert-warning">
        <h4>Không tìm thấy sản phẩm phù hợp với từ khóa "{{ $tukhoa }}"</h4>
    </div>

    <h3 class="mt-4">Có thể bạn quan tâm:</h3>
    <div class="row pt-2 mx-n2">
        @foreach($sanphamgoiy as $sp)
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
                    <h3 class="product-title fs-sm">{{ $sp->tensanpham }}</h3>
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
                    <!-- Thêm đánh giá sao -->
                    <div class="d-flex align-items-center mt-2">
                        <div class="star-rating">
                            @php
                            $rating = $sp->diemDanhGiaTrungBinh() ?: 5; // Nếu không có đánh giá, mặc định 5 sao
                            $count = $sp->soLuongDanhGia();
                            @endphp

                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <=$rating)
                                <i class="star-rating-icon ci-star-filled active"></i>
                                @elseif ($i - 0.5 <= $rating)
                                    <i class="star-rating-icon ci-star-half active"></i>
                                    @else
                                    <i class="star-rating-icon ci-star"></i>
                                    @endif
                                    @endfor
                        </div>
                        @if($count > 0)
                        <span class="d-inline-block fs-xs text-muted ms-1">({{ $count }})</span>
                        @endif
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
    </div>
    @endif

    <!-- Danh sách sản phẩm -->
    <div class="row pt-2 mx-n2">
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
                        <div class="product-price">
                            @if($sp->khuyenMaiHienTai())
                            <span class="text-accent">{{ number_format($sp->giaKhuyenMai()) }}đ</span>
                            <del class="fs-sm text-muted">{{ number_format($sp->dongia) }}đ</del>
                            @else
                            <span class="text-accent">{{ number_format($sp->dongia) }}đ</span>
                            @endif
                        </div>

                        <!-- Đánh giá sao -->
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
    </div>

    <!-- Phân trang -->
    <div class="d-flex justify-content-center mt-4">
        {{ $sanpham->links() }}
    </div>
</div>
@endsection