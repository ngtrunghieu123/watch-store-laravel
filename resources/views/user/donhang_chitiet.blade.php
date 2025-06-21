@extends('layouts.frontend')
@section('title', 'Chi tiết đơn hàng')
@section('content')
<div class="container pb-5 mb-2 mb-md-4">
    <div class="row">
        <div class="col-md-12">
            <h2 class="h4 pb-3">Chi tiết đơn hàng {{ $donhang->id }}</h2>
            <!-- đánh giá -->
            @if($donhang->tinhtrang_id == 3 && isset($showReviewSection) && $showReviewSection)
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Đánh giá sản phẩm</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Chọn sản phẩm bạn muốn đánh giá:</p>

                    <div class="row">
                        @foreach($donhang->DonHang_ChiTiet as $chitiet)
                        @if($chitiet->sanpham && $chitiet->sanpham->loaisanpham)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100 border">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 me-3" style="width: 60px; height: 60px;">
                                            @if($chitiet->sanpham->hinhanh)
                                            <img src="{{ asset('storage/app/private/' . $chitiet->sanpham->hinhanh) }}"
                                                alt="{{ $chitiet->sanpham->tensanpham }}"
                                                class="img-fluid" style="max-height: 60px; max-width: 60px;">
                                            @else
                                            <div class="bg-secondary d-flex align-items-center justify-content-center"
                                                style="height: 60px; width: 60px;">
                                                <i class="ci-image text-white"></i>
                                            </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="product-title fs-sm mb-1">{{ $chitiet->sanpham->tensanpham }}</h6>
                                            <div class="text-muted fs-sm">
                                                Số lượng: {{ $chitiet->soluongban }}
                                            </div>
                                            <div class="fs-sm">
                                                {{ number_format($chitiet->dongiaban) }}đ
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Nút đánh giá sản phẩm dẫn tới trang chi tiết sản phẩm -->
                                    <a href="{{ route('frontend.sanpham.chitiet', [
                                'tenloai_slug' => $chitiet->sanpham->loaisanpham->tenloai_slug, 
                                'tensanpham_slug' => $chitiet->sanpham->tensanpham_slug,
                                'review' => 'true'
                            ]) }}" class="btn btn-primary btn-sm w-100 mt-3">
                                        <i class="ci-star me-1"></i>Đánh giá sản phẩm
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            <!-- Thông tin đơn hàng -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Ngày đặt:</strong> {{ $donhang->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Địa chỉ:</strong> {{ $donhang->diachigiaohang }}</p>
                            <p><strong>Điện thoại:</strong> {{ $donhang->dienthoaigiaohang }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p><strong>Trạng thái:</strong>
                                @php
                                switch ($donhang->tinhtrang_id) {
                                case 1:
                                $badgeClass = 'bg-info';
                                break;
                                case 2:
                                $badgeClass = 'bg-warning';
                                break;
                                case 3:
                                $badgeClass = 'bg-success';
                                break;
                                default:
                                $badgeClass = 'bg-danger';
                                break;
                                }
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $donhang->TinhTrang->tinhtrang }}</span>

                            </p>
                            <p><strong>Tổng tiền:</strong> {{ number_format($donhang->tongtien) }}đ</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chi tiết sản phẩm -->
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Thuế GTGT(10%)</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donhang->DonHang_ChiTiet as $ct)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/app/private/' . $ct->sanpham->hinhanh) }}" width="60" class="me-3">
                                    {{ $ct->sanpham->tensanpham }}
                                </div>
                            </td>
                            <td>{{ number_format($ct->dongiaban) }}đ</td>
                            <td>{{ $ct->soluongban }}</td>
                            <td>{{ number_format($ct->dongiaban * $ct->soluongban * 0.1 )}}đ</td>
                            <td>{{ number_format($ct->dongiaban * $ct->soluongban * 1.1) }}đ</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <td>
                @if($donhang->tinhtrang_id == 1)
                <!-- Chỉ hiện nút hủy đơn hàng khi đơn hàng mới -->
                <a href="#" class="btn btn-sm btn-danger ms-1"
                    data-bs-toggle="modal"
                    data-bs-target="#huyDonHangModal{{ $donhang->id }}">
                    <i class="ci-close"></i> Hủy đơn
                </a>

                <!-- Modal xác nhận hủy đơn hàng -->
                <div class="modal fade" id="huyDonHangModal{{ $donhang->id }}" tabindex="-1" aria-labelledby="huyDonHangModalLabel{{ $donhang->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('user.donhang.huy', ['id' => $donhang->id]) }}" method="post">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="huyDonHangModalLabel{{ $donhang->id }}">Xác nhận hủy đơn hàng #{{ $donhang->id }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Bạn có chắc chắn muốn hủy đơn hàng này không?</p>
                                    <p><strong>Lưu ý:</strong> Sau khi hủy, bạn không thể khôi phục lại đơn hàng.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </td>
            <div class="text-end mt-4">
                <a href="{{ route('user.donhang') }}" class="btn btn-secondary">
                    <i class="ci-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>
</div>
@endsection