@extends('layouts.frontend')
@section('title', 'Lịch sử đơn hàng')
@section('content')
<div class="container pb-5 mb-2 mb-md-4">
    <div class="row">
        <div class="col-md-12">
            <h2 class="h4 pb-3">Lịch sử đơn hàng</h2>

            <!-- Hiển thị thông báo -->
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

            @if($donhang->isEmpty())
            <div class="alert alert-warning">
                Bạn chưa có đơn hàng nào.
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Ngày đặt</th>
                            <th>Địa chỉ giao hàng</th>
                            <th>Số điện thoại</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donhang as $dh)
                        <tr>
                            <td>#{{ $dh->id }}</td>
                            <td>{{ $dh->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $dh->diachigiaohang }}</td>
                            <td>{{ $dh->dienthoaigiaohang }}</td>
                            <td>{{ number_format($dh->tongtien) }}đ</td>
                            <td>
                                @php
                                switch ($dh->tinhtrang_id) {
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

                                <span class="badge {{ $badgeClass }}">{{ $dh->TinhTrang->tinhtrang }}</span>
                            </td>
                            <td class="text-end">
                                <!-- Nút chi tiết - hiển thị cho tất cả trạng thái -->
                                <a href="{{ route('user.donhang.chitiet', ['id' => $dh->id]) }}"
                                    class="btn btn-sm btn-outline-secondary mb-1">
                                    <i class="ci-eye me-1"></i>Chi tiết
                                </a>

                                @if($dh->tinhtrang_id == 1)
                                <!-- Nút hủy đơn hàng - chỉ hiển thị khi đơn hàng mới -->
                                <a href="#" class="btn btn-sm btn-danger mb-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#huyDonHangModal{{ $dh->id }}">
                                    <i class="ci-close me-1"></i>Hủy đơn
                                </a>

                                <!-- Modal xác nhận hủy đơn hàng -->
                                <div class="modal fade" id="huyDonHangModal{{ $dh->id }}" tabindex="-1" aria-labelledby="huyDonHangModalLabel{{ $dh->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('user.donhang.huy', ['id' => $dh->id]) }}" method="post">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="huyDonHangModalLabel{{ $dh->id }}">Xác nhận hủy đơn hàng #{{ $dh->id }}</h5>
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
                                @elseif($dh->tinhtrang_id == 3)
                                <!-- Nút đánh giá đơn hàng - chỉ hiển thị khi đơn hàng đã giao -->
                                <a href="{{ route('user.donhang.danhgia.sanpham', ['id' => $dh->id]) }}"
                                    class="btn btn-sm btn-primary mb-1">
                                    <i class="ci-star me-1"></i>Đánh giá
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection