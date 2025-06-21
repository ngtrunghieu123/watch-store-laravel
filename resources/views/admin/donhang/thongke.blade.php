@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Thống kê đơn hàng</h1>

    <!-- Form chọn tháng năm -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-calendar me-1"></i> Chọn thời gian thống kê
        </div>
        <div class="card-body">
            <form method="get" action="{{ route('admin.donhang.thongke') }}" class="row align-items-end">
                <div class="col-md-4 mb-3">
                    <label for="thang" class="form-label">Tháng</label>
                    <select class="form-select" id="thang" name="thang">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $thang == $i ? 'selected' : '' }}>Tháng {{ $i }}</option>
                            @endfor
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="nam" class="form-label">Năm</label>
                    <select class="form-select" id="nam" name="nam">
                        @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                        <option value="{{ $i }}" {{ $nam == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i> Lọc
                    </button>
                    <a href="{{ route('admin.donhang') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Hiển thị thống kê tổng quan -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div>Tổng đơn hàng</div>
                            <h2 class="mb-0">{{ number_format($tongDonHang) }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-shopping-cart fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div>Tổng doanh thu</div>
                            <h2 class="mb-0">{{ number_format($tongDoanhThu) }}<sup>đ</sup></h2>
                        </div>
                        <div>
                            <i class="fas fa-money-bill fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-dark mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div>Sản phẩm đã bán</div>
                            <h2 class="mb-0">{{ number_format($sanPhamDaBan) }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-box fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div>Đơn hàng đã hủy</div>
                            <h2 class="mb-0">{{ number_format($donHuy) }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-ban fa-3x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê trạng thái đơn hàng -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-tasks me-1"></i> Trạng thái đơn hàng
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">Đơn hàng mới</h5>
                                    <h3 class="mb-0">{{ number_format($donMoi) }}</h3>
                                </div>
                                <div><i class="fas fa-file-invoice fa-3x opacity-50"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card text-dark bg-warning">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">Đang xử lý</h5>
                                    <h3 class="mb-0">{{ number_format($donDangXuLy) }}</h3>
                                </div>
                                <div><i class="fas fa-truck-loading fa-3x opacity-50"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">Đã giao hàng</h5>
                                    <h3 class="mb-0">{{ number_format($donDaGiao) }}</h3>
                                </div>
                                <div><i class="fas fa-check-circle fa-3x opacity-50"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">Đã hủy</h5>
                                    <h3 class="mb-0">{{ number_format($donHuy) }}</h3>
                                </div>
                                <div><i class="fas fa-times-circle fa-3x opacity-50"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 5 sản phẩm bán chạy -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-medal me-1"></i> Top 5 sản phẩm bán chạy
        </div>
        <div class="card-body">
            @if($topSanPham->count() > 0)
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>STT</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng đã bán</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topSanPham as $index => $sp)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $sp->tensanpham }}</td>
                        <td>{{ number_format($sp->tong_ban) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="alert alert-info mb-0">
                Không có dữ liệu sản phẩm bán ra trong tháng này.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection