@extends('layouts.frontend')

@section('title', 'Đánh giá sản phẩm của tôi')

@section('content')
<div class="container pb-5 mb-2 mb-md-4">
    <div class="row">
        <!-- Sidebar -->
        <aside class="col-lg-4 pt-4 pt-lg-0 pe-xl-5">
            <div class="bg-white rounded-3 shadow-lg pt-1 mb-5 mb-lg-0">
                <div class="d-md-flex justify-content-between align-items-center text-center text-md-start p-4">
                    <div class="d-md-flex align-items-center">
                        <div class="img-thumbnail rounded-circle position-relative flex-shrink-0 mx-auto mb-2 mx-md-0 mb-md-0" style="width: 6.375rem;">
                            @if(Auth::user()->hinhanh)
                            <img class="rounded-circle" src="{{ asset('storage/app/publicavatar/' . Auth::user()->hinhanh) }}" alt="{{ Auth::user()->name }}">
                            @else
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fs-3" style="width: 100%; height: 6.375rem;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            @endif
                        </div>
                        <div class="ps-md-3">
                            <h3 class="fs-base mb-0">{{ Auth::user()->name }}</h3>
                            <span class="text-accent fs-sm">{{ Auth::user()->email }}</span>
                        </div>
                    </div>
                </div>
                <div class="d-lg-block collapse" id="account-menu">
                    <ul class="list-unstyled mb-0">
                        <li class="border-bottom mb-0">
                            <a class="nav-link-style d-flex align-items-center px-4 py-3" href="{{ route('user.donhang') }}">
                                <i class="ci-bag opacity-60 me-2"></i>Đơn hàng<span class="fs-sm text-muted ms-auto">{{ optional(Auth::user()->donHang)->count() ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="border-bottom mb-0">
                            <a class="nav-link-style d-flex align-items-center px-4 py-3" href="{{ route('user.yeuthich') }}">
                                <i class="ci-heart opacity-60 me-2"></i>Sản phẩm yêu thích<span class="fs-sm text-muted ms-auto">{{ optional(Auth::user()->yeuthich)->count() ?? 0 }}</span>
                            </a>
                        </li>
                        <li class="border-bottom mb-0">
                            <a class="nav-link-style d-flex align-items-center px-4 py-3 active" href="{{ route('user.danhgia') }}">
                                <i class="ci-star opacity-60 me-2"></i>Đánh giá của tôi<span class="fs-sm text-muted ms-auto">{{ optional(Auth::user()->danhGia)->count() ?? 0 }}</span>
                            </a>
                        </li>
                    </ul>
                    <div class="bg-secondary px-4 py-3">
                        <h3 class="fs-sm mb-0 text-muted">Thiết lập tài khoản</h3>
                    </div>
                    <ul class="list-unstyled mb-0">
                        <li class="border-bottom mb-0">
                            <a class="nav-link-style d-flex align-items-center px-4 py-3" href="{{ route('user.hosocanhan') }}">
                                <i class="ci-user opacity-60 me-2"></i>Hồ sơ cá nhân
                            </a>
                        </li>
                        <li class="d-lg-none border-top mb-0">
                            <a class="nav-link-style d-flex align-items-center px-4 py-3" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <i class="ci-sign-out opacity-60 me-2"></i>Đăng xuất
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="post" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>

        <!-- Content -->
        <section class="col-lg-8">
            <div class="d-flex flex-column h-100 bg-light rounded-3 shadow-lg p-4">
                <div class="py-2 p-md-3">
                    <!-- Title -->
                    <div class="d-sm-flex align-items-center justify-content-between pb-4 text-center text-sm-start">
                        <h1 class="h3 mb-3 text-nowrap">Đánh giá sản phẩm của tôi</h1>
                    </div>

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <!-- Đánh giá -->
                    @if($danhGias->count() > 0)
                    @foreach($danhGias as $danhGia)
                    <div class="card border-0 shadow mb-4">
                        <div class="card-header bg-light d-flex align-items-center justify-content-between">
                            <div>
                                <a href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $danhGia->sanPham->loaiSanPham->tenloai_slug, 'tensanpham_slug' => $danhGia->sanPham->tensanpham_slug]) }}" class="text-decoration-none fw-medium text-dark">
                                    {{ $danhGia->sanPham->tensanpham }}
                                </a>
                            </div>
                            <div>
                                @if($danhGia->kiemduyet)
                                <span class="badge bg-success">Đã duyệt</span>
                                @else
                                <span class="badge bg-secondary">Chờ duyệt</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="mb-2">
                                    @for($i=1; $i<=5; $i++)
                                        @if($i <=$danhGia->sosao)
                                        <i class="fas fa-star text-warning"></i>
                                        @else
                                        <i class="far fa-star text-secondary"></i>
                                        @endif
                                        @endfor
                                        <span class="text-muted ms-2 fs-sm">{{ \Carbon\Carbon::parse($danhGia->created_at)->format('d/m/Y H:i') }}</span>
                                </div>

                                <p class="mb-2">{{ $danhGia->binhluan }}</p>

                                @if($danhGia->hinhanh)
                                <div class="my-3">
                                    <a href="{{ asset('storage/app/public/'.$danhGia->hinhanh) }}" target="_blank" data-lightbox="review-images-{{ $danhGia->id }}">
                                        <img src="{{ asset('storage/app/public/'.$danhGia->hinhanh) }}" class="img-thumbnail" style="max-height: 120px" alt="Hình ảnh đánh giá">
                                    </a>
                                </div>
                                @endif
                            </div>

                            <div class="mt-2">
                                <a href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $danhGia->sanPham->loaiSanPham->tenloai_slug, 'tensanpham_slug' => $danhGia->sanPham->tensanpham_slug]) }}" class="btn btn-sm btn-outline-success me-2">
                                    <i class="fas fa-eye me-1"></i> Xem chi tiết đánh giá
                                </a>

                                @if(Auth::check() && $danhGia->nguoidung_id == Auth::id())
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $danhGia->id }}">
                                    <i class="fas fa-edit me-1"></i> Sửa đánh giá
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <!-- Phân trang -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $danhGias->links() }}
                    </div>
                    @else
                    <div class="card border-0 shadow">
                        <div class="card-body text-center py-5">
                            <i class="ci-star display-1 text-muted mb-4"></i>
                            <h3 class="h5 mb-2">Bạn chưa có đánh giá nào</h3>
                            <p class="text-muted mb-4">Hãy mua sắm và đánh giá sản phẩm để chia sẻ trải nghiệm của bạn với người khác!</p>
                            <a href="{{ route('frontend.home') }}" class="btn btn-primary">Mua sắm ngay</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>
@foreach($danhGias as $danhGia)
<!-- Modal chỉnh sửa đánh giá -->
<div class="modal fade" id="editModal{{ $danhGia->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $danhGia->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('user.danhgia.sua', ['id' => $danhGia->id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel{{ $danhGia->id }}">Sửa đánh giá sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Star Rating -->
                    <div class="mb-3">
                        <label class="form-label">Đánh giá của bạn:</label>
                        <div class="star-rating">
                            <input type="hidden" name="sosao" id="rating-value-{{ $danhGia->id }}" value="{{ $danhGia->sosao }}">
                            <div class="stars">
                                @for($i=5; $i>=1; $i--)
                                <i class="star fas fa-star {{ $danhGia->sosao >= $i ? 'active' : '' }}" data-value="{{ $i }}" data-target="#rating-value-{{ $danhGia->id }}"></i>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <!-- Nhận xét -->
                    <div class="mb-3">
                        <label for="binhluan{{ $danhGia->id }}" class="form-label">Nhận xét:</label>
                        <textarea class="form-control" id="binhluan{{ $danhGia->id }}" name="binhluan" rows="3">{{ $danhGia->binhluan }}</textarea>
                    </div>

                    <!-- Hình ảnh -->
                    <div class="mb-3">
                        <label for="hinhanh{{ $danhGia->id }}" class="form-label">Hình ảnh (không bắt buộc):</label>
                        <input class="form-control" type="file" id="hinhanh{{ $danhGia->id }}" name="hinhanh" accept="image/*">
                        @if($danhGia->hinhanh)
                        <div class="mt-2">
                            <img src="{{ asset('storage/app/public/'.$danhGia->hinhanh) }}" alt="Hình ảnh đánh giá" class="img-thumbnail" style="max-height: 100px">
                            <div class="form-text">Tải lên hình ảnh mới sẽ thay thế hình ảnh hiện tại.</div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Cập nhật đánh giá</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- JavaScript cho đánh giá sao -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý đánh giá sao cho tất cả modal
        document.querySelectorAll('.star-rating .star').forEach(function(star) {
            star.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                const targetId = this.getAttribute('data-target');
                const ratingValue = document.querySelector(targetId);
                ratingValue.value = value;

                // Lấy tất cả các ngôi sao trong cùng một nhóm sao
                const starsContainer = this.closest('.stars');
                const stars = starsContainer.querySelectorAll('.star');

                // Xóa trạng thái active của tất cả các ngôi sao
                stars.forEach(s => s.classList.remove('active'));

                // Thêm trạng thái active cho ngôi sao được chọn và các ngôi sao có giá trị nhỏ hơn
                stars.forEach(s => {
                    if (parseInt(s.getAttribute('data-value')) <= value) {
                        s.classList.add('active');
                    }
                });
            });
        });
    });
</script>

<!-- CSS cho đánh giá sao -->
<style>
    .star-rating .stars {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-start;
    }

    .star-rating .star {
        font-size: 24px;
        color: #ddd;
        cursor: pointer;
        padding: 0 2px;
    }

    .star-rating .star.active {
        color: #ffc107;
    }

    .star-rating .star:hover,
    .star-rating .star:hover~.star {
        color: #ffc107;
    }
</style>
@endsection