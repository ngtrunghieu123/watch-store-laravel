@extends('layouts.frontend')

@section('title', $sanpham->tensanpham)

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('frontend.home') }}">Trang chủ</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('frontend.sanpham') }}">Sản phẩm</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('frontend.sanpham.phanloai', ['tenloai_slug' => $sanpham->loaisanpham->tenloai_slug]) }}">
                    {{ $sanpham->loaisanpham->tenloai }}
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('frontend.sanpham.hangsanxuat', ['tenhang_slug' => $sanpham->hangsanxuat->tenhang_slug]) }}">
                    {{ $sanpham->hangsanxuat->tenhang }}
                </a>
            </li>
            <li class="breadcrumb-item active">{{ $sanpham->tensanpham }}</li>
        </ol>
    </nav>

    <!-- Chi tiết sản phẩm -->
    <div class="row">
        <!-- Ảnh sản phẩm -->
        <div class="col-lg-7 pe-lg-0">
            <div class="product-gallery">
                <div class="product-gallery-preview">
                    <div class="image-zoom shadow-sm border rounded p-3 bg-white">
                        <img src="{{ asset('storage/app/private/' . $sanpham->hinhanh) }}"
                            alt="{{ $sanpham->tensanpham }}"
                            style="width: 100%; height: 400px; object-fit: contain; transition: transform .3s ease;"
                            class="img-fluid product-image">
                    </div>
                </div>
            </div>
        </div>

        <style>
            .image-zoom {
                overflow: hidden;
                background: white;
            }

            .image-zoom:hover .product-image {
                transform: scale(1.1);
            }

            .product-image {
                display: block;
                margin: auto;
            }
        </style>

        <!-- Thông tin sản phẩm -->
        <div class="col-lg-5 pt-4 pt-lg-0">
            <div class="product-details ms-auto pb-3">
                <h1 class="h3 mb-3">{{ $sanpham->tensanpham }}</h1>
                <div class="mb-3">
                    @if($sanpham->khuyenMaiHienTai())
                    <span class="h3 fw-normal text-accent me-1">{{ number_format($sanpham->giaKhuyenMai()) }}đ</span>
                    <del class="text-muted fs-lg me-3">{{ number_format($sanpham->dongia) }}đ</del>
                    <span class="badge bg-danger">-{{ $sanpham->khuyenMaiHienTai()->phantram }}%</span>
                    @else
                    <span class="h3 fw-normal text-accent me-1">{{ number_format($sanpham->dongia) }}đ</span>
                    @endif
                </div>
                <from>
                    <div class="product-details">
                        <h4>Mô tả sản phẩm</h4>
                        <p class="product-description">
                            {{ $sanpham->motasanpham }}
                        </p>
                    </div>
                </from>

                <div class="bg-white rounded-3 shadow-lg p-4 mb-4">
                    <form action="{{ route('frontend.giohang.them', ['tensanpham_slug' => $sanpham->tensanpham_slug]) }}" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Số lượng:</label>
                            <div class="d-flex justify-content-start">
                                <input type="number"
                                    name="soluong"
                                    id="soluong"
                                    class="form-control text-center"
                                    value="1"
                                    min="1"
                                    max="{{ $sanpham->soluong }}"
                                    style="width:100px; -webkit-appearance: none; -moz-appearance: textfield;"
                                    {{ $sanpham->soluong <= 0 ? 'disabled' : '' }}>
                            </div>
                            @if($sanpham->soluong > 0)
                            <small class="text-muted">Còn {{ $sanpham->soluong }} sản phẩm</small>
                            @else
                            <small class="text-danger font-weight-bold">Hết hàng</small>
                            @endif
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-muted me-2">Trạng thái:</span>
                                @if($sanpham->soluong > 10)
                                <span class="badge bg-success">Còn hàng</span>
                                @elseif($sanpham->soluong > 0)
                                <span class="badge bg-warning text-dark">Sắp hết hàng</span>
                                @else
                                <span class="badge bg-danger">Hết hàng</span>
                                @endif
                            </div>
                        </div>
                        @if($sanpham->soluong > 0)
                        <button type="submit" class="btn btn-primary btn-shadow d-block w-100 mb-3">
                            <i class="ci-cart fs-lg me-2"></i>Thêm vào giỏ
                        </button>
                        @else
                        <button type="button" class="btn btn-secondary btn-shadow d-block w-100 mb-3" disabled>
                            <i class="ci-cart fs-lg me-2"></i>Hết hàng
                        </button>
                        @endif
                    </form>
                    <!-- <form action="{{ route('user.dathang.nhanh') }}" method="POST">
                        @csrf
                        <input type="hidden" name="sanpham_id" value="{{ $sanpham->id }}">
                        <input type="hidden" name="soluong" id="soluong_dathang">
                        <button type="submit" class="btn btn-accent btn-shadow d-block w-100"
                            onclick="document.getElementById('soluong_dathang').value = document.getElementById('soluong').value">
                            <i class="ci-card fs-lg me-2"></i>Đặt hàng ngay
                        </button>
                    </form> -->
                </div>
            </div>
        </div>
    </div>
    <!-- Phần đánh giá sản phẩm - thêm vào sau phần mô tả sản phẩm -->
    <div class="card mt-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Đánh giá sản phẩm</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="text-center">
                        <h1 class="display-4">{{ number_format($sanpham->diemDanhGiaTrungBinh(), 1) }}</h1>
                        <div class="mb-2">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <=round($sanpham->diemDanhGiaTrungBinh()))
                                <i class="fas fa-star text-warning fs-4"></i>
                                @else
                                <i class="far fa-star text-secondary fs-4"></i>
                                @endif
                                @endfor
                        </div>
                        <p class="text-muted">{{ $sanpham->soLuongDanhGia() }} đánh giá</p>
                    </div>

                    @php $thongKe = $sanpham->thongKeDanhGia(); @endphp

                    @for ($i = 5; $i >= 1; $i--)
                    <div class="d-flex align-items-center mb-2">
                        <div class="text-nowrap me-3">
                            <span>{{ $i }}</span>
                            <i class="fas fa-star text-warning ms-1"></i>
                        </div>
                        <div class="progress w-100">
                            @php
                            $percent = $sanpham->soLuongDanhGia() > 0 ? ($thongKe[$i] / $sanpham->soLuongDanhGia()) * 100 : 0;
                            @endphp
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $percent }}%" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="text-muted ms-3 small">
                            {{ $thongKe[$i] }}
                        </div>
                    </div>
                    @endfor
                </div>

                <div class="col-lg-8">
                    @if (Auth::check())
                    @php
                    // Kiểm tra xem người dùng đã mua sản phẩm này chưa và đơn hàng đã giao thành công chưa
                    $daMuaHang = App\Models\DonHang::join('donhang_chitiet', 'donhang.id', '=', 'donhang_chitiet.donhang_id')
                    ->where('donhang.nguoidung_id', Auth::id())
                    ->where('donhang_chitiet.sanpham_id', $sanpham->id)
                    ->where('donhang.tinhtrang_id', 3) // 3 là trạng thái "Đã giao hàng"
                    ->exists();

                    // Kiểm tra đánh giá hiện có
                    $danhGiaSanPham = App\Models\DanhGiaSanPham::where('sanpham_id', $sanpham->id)
                    ->where('nguoidung_id', Auth::id())
                    ->first();
                    @endphp
                    @if($daMuaHang)

                    <form action="{{ route('user.danhgia.them') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="sanpham_id" value="{{ $sanpham->id }}">

                        <div class="mb-3">
                            <label class="form-label">Đánh giá của bạn:</label>
                            <div class="star-rating">
                                <input type="hidden" name="sosao" id="rating-value" value="{{ isset($danhGiaSanPham) ? $danhGiaSanPham->sosao : 5 }}">
                                <div class="stars">
                                    @for($i=5; $i>=1; $i--)
                                    <i class="star fas fa-star {{ (isset($danhGiaSanPham) && $danhGiaSanPham->sosao >= $i) || !isset($danhGiaSanPham) && $i <= 5 ? 'active' : '' }}" data-value="{{ $i }}"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <style>
                            .star-rating .stars {
                                display: flex;
                                flex-direction: row-reverse;
                                justify-content: flex-end;
                            }

                            .star-rating .star {
                                font-size: 24px;
                                color: #ddd;
                                cursor: pointer;
                                padding: 0 2px;
                            }

                            .star-rating .star:hover,
                            .star-rating .star.active {
                                color: #ffc107;
                            }

                            /* Khi hover vào ngôi sao, tất cả các ngôi sao bên phải cũng sẽ sáng lên */
                            .star-rating .star:hover~.star {
                                color: #ffc107;
                            }
                        </style>

                        <div class="mb-3">
                            <label for="binhluan" class="form-label">Nhận xét:</label>
                            <textarea class="form-control" id="binhluan" name="binhluan" rows="3" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này...">{{ isset($danhGiaSanPham) ? $danhGiaSanPham->binhluan : '' }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="hinhanh" class="form-label">Hình ảnh (không bắt buộc):</label>
                            <input class="form-control" type="file" id="hinhanh" name="hinhanh" accept="image/*">
                            @if(isset($danhGiaSanPham) && $danhGiaSanPham->hinhanh)
                            <div class="mt-2">
                                <img src="{{ asset('storage/app/public/'.$danhGiaSanPham->hinhanh) }}" alt="Hình ảnh đánh giá" class="img-thumbnail" style="max-height: 100px">
                            </div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">
                            {{ isset($danhGiaSanPham) ? 'Cập nhật đánh giá' : 'Gửi đánh giá' }}
                        </button>
                    </form>
                    @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Bạn cần mua sản phẩm và nhận hàng thành công mới có thể đánh giá sản phẩm này.
                    </div>
                    @endif
                    @else
                    <div class="alert alert-info">
                        Vui lòng <a href="{{ route('user.dangnhap') }}">đăng nhập</a> để đánh giá sản phẩm này.
                    </div>
                    @endif
                </div>
            </div>

            <hr class="my-4">

            <!-- Danh sách đánh giá -->
            <div>
                <h5>Đánh giá từ khách hàng</h5>

                @php
                $danhGias = $sanpham->danhGia()
                ->where('kiemduyet', 1)
                ->orderBy('created_at', 'desc')
                ->paginate(5);
                @endphp

                @if($danhGias->count() > 0)
                @foreach($danhGias as $danhGia)
                <div class="border-bottom py-3">
                    <div class="d-flex">
                        <div class="me-3">
                            @if($danhGia->nguoiDung->anhdaidien)
                            <img src="{{ asset('storage/'.$danhGia->nguoiDung->anhdaidien) }}" class="rounded-circle" width="50" height="50" alt="{{ $danhGia->nguoiDung->name }}">
                            @else
                            <div class="d-flex align-items-center">
                                <!-- <h6 class="mb-0 me-2">{{ $danhGia->nguoiDung->name }}</h6> -->
                                @php
                                $nguoiDungDaMua = App\Models\DonHang::join('donhang_chitiet', 'donhang.id', '=', 'donhang_chitiet.donhang_id')
                                ->where('donhang.nguoidung_id', $danhGia->nguoidung_id)
                                ->where('donhang_chitiet.sanpham_id', $sanpham->id)
                                ->where('donhang.tinhtrang_id', 3) // id 3 là trạng thái "Đã giao hàng"
                                ->exists();
                                @endphp
                                <!-- @if($nguoiDungDaMua)
                                <span class="badge bg-success me-2">Đã mua hàng</span>
                                @endif
                                <span class="text-muted small">{{ \Carbon\Carbon::parse($danhGia->created_at)->format('d/m/Y H:i') }}</span> -->
                            </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center">
                                <h6 class="mb-0 me-2">{{ $danhGia->nguoiDung->name }}</h6>
                                <span class="text-muted small">{{ \Carbon\Carbon::parse($danhGia->created_at)->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="mb-2">
                                @for($i=1; $i<=5; $i++)
                                    @if($i <=$danhGia->sosao)
                                    <i class="fas fa-star text-warning"></i>
                                    @else
                                    <i class="far fa-star text-secondary"></i>
                                    @endif
                                    @endfor
                            </div>
                            <p>{{ $danhGia->binhluan }}</p>

                            @if($danhGia->hinhanh)
                            <div class="mt-2">
                                <a href="{{ asset('storage/app/public/'.$danhGia->hinhanh) }}" target="_blank" data-lightbox="review-images">
                                    <img src="{{ asset('storage/app/public/'.$danhGia->hinhanh) }}" class="img-thumbnail" style="max-height: 100px" alt="Hình ảnh đánh giá">
                                </a>
                            </div>
                            @endif

                            <!-- @if(Auth::check() && $danhGia->nguoidung_id == Auth::id())
                            <div class="mt-2">
                                <form action="{{ route('user.danhgia.xoa', ['id' => $danhGia->id]) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa đánh giá này không?')">Xóa</button>
                                </form>
                                @endif
                            </div> -->
                        </div>
                    </div>
                    @endforeach

                    <div class="mt-3">
                        {{ $danhGias->links() }}
                    </div>
                    @else
                    <div class="alert alert-info">
                        Chưa có đánh giá nào cho sản phẩm này.
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sản phẩm liên quan -->
        <div class="pt-4 mt-4">
            <h2 class="h4 text-center pb-3">Sản phẩm liên quan</h2>
            <div class="row pt-2 mx-n2">
                @foreach($splienquan as $sp)
                <div class="col-lg-3 col-md-4 col-sm-6 px-2 mb-3">
                    <div class="card product-card h-100 border-0 shadow-sm product-hover">
                        <a href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $sp->loaisanpham->tenloai_slug, 'tensanpham_slug' => $sp->tensanpham_slug]) }}">
                            <img src="{{ asset('storage/app/private/' . $sp->hinhanh) }}"
                                alt="{{ $sp->tensanpham }}"
                                class="card-img-top"
                                style="height: 200px; object-fit: contain;">
                        </a>
                        <div class="card-body py-2">
                            <h3 class="product-title fs-sm">
                                <a href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $sp->loaisanpham->tenloai_slug, 'tensanpham_slug' => $sp->tensanpham_slug]) }}">
                                    {{ $sp->tensanpham }}
                                </a>
                            </h3>

                            <!-- Gộp phần giá và đánh giá sao vào cùng một dòng -->
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

                <style>
                    .product-hover {
                        transition: all 0.3s ease;
                    }

                    .product-hover:hover {
                        transform: translateY(-5px);
                        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
                    }
                </style>
                @endforeach
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-rating .star');
            const ratingValue = document.getElementById('rating-value');

            stars.forEach(function(star) {
                star.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    ratingValue.value = value;

                    // Xóa trạng thái active của tất cả các ngôi sao
                    stars.forEach(s => s.classList.remove('active'));

                    // Thêm trạng thái active cho ngôi sao được chọn và các ngôi sao có giá trị nhỏ hơn
                    stars.forEach(s => {
                        if (s.getAttribute('data-value') <= value) {
                            s.classList.add('active');
                        }
                    });
                });
            });
        });
    </script>
    @endsection