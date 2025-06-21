@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>Đơn hàng</div>
            <div>
                <a href="{{ route('admin.donhang.thongke') }}" class="btn btn-success">
                    <i class="fas fa-chart-bar me-1"></i> Thống kê đơn hàng
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- <p><a href="{{route('admin.donhang.them')}}" class="btn btn-info"><i class="fa-light fa-plus"></i>Thêm mới</a></p> -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-search me-1"></i> Tìm kiếm đơn hàng
            </div>
            <div class="card-body">
                @if($donhang->count() == 0)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-1"></i> Không tìm thấy đơn hàng nào phù hợp với điều kiện tìm kiếm.
                </div>
                @endif
                <form action="{{ route('admin.donhang.timkiem') }}" method="get">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" name="tenkhachhang" placeholder="Tên khách hàng" value="{{ request('tenkhachhang') }}">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="text" class="form-control" name="dienthoai" placeholder="Số điện thoại" value="{{ request('dienthoai') }}">
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i> Tìm kiếm
                            </button>
                            <a href="{{ route('admin.donhang') }}" class="btn btn-secondary ms-1">
                                <i class="fas fa-sync-alt me-1"></i> Làm mới
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <table class="table table-bordered table-hover table-sm mb-0">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="15%">Khách hàng</th>
                    <th width="45%">Thông tin giao hàng</th>
                    <th width="15%">Ngày đặt</th>
                    <th width="10%">Tình trạng</th>
                    <th width="5%">Sửa</th>
                    <!-- <th width="5%">Xóa</th> -->
                </tr>
            </thead>
            <tbody>
                @foreach ($donhang as $value)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$value->NguoiDung->name}}</td>
                    <td>
                        <span class="d-block">Điện thoại: <strong>{{$value->dienthoaigiaohang}}</strong></span>
                        <span class="d-block">Địa chỉ: <strong>{{$value->diachigiaohang}}</strong></span>
                        <span class="d-block">Sản phẩm</span>
                        <table class="table table-bordered table-hover table-sm mb-0">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Sản phẩm</th>
                                    <th width="5%">SL</th>
                                    <th width="15%">Đơn giá</th>
                                    <th>Thuế VAT</th>
                                    <th width="15%">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $tongtien = 0; @endphp
                                @foreach ($value->DonHang_ChiTiet as $chitiet)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$chitiet->SanPham->tensanpham}}</td>
                                    <td>{{$chitiet->soluongban}}</td>
                                    <td class="text-end">{{number_format($chitiet->dongiaban)}}<sup><u>đ</u></sup></td>
                                    <td class="text-end">{{number_format($chitiet->soluongban * $chitiet->dongiaban * 0.1)}}<sup><u>đ</u></sup></td>
                                    <td class="text-end">
                                        {{number_format($chitiet->soluongban * $chitiet->dongiaban )}}<sup><u>đ</u></sup>
                                    </td>

                                </tr>
                                @php
                                $tongtien += $chitiet->soluongban * $chitiet->dongiaban * 1.1; // 10% VAT
                                @endphp
                                @endforeach
                                <tr>
                                    <td colspan="4">Tổng tiền sản phẩm:</td>
                                    <td class="text-end">
                                        <strong>{{number_format($tongtien)}}</strong><sup><u>đ</u></sup>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td>{{$value->created_at->format('d/m/Y H:i:s')}}</td>
                    <td>{{$value->TinhTrang->tinhtrang}}</td>
                    <td class="text-center">
                        <a href="{{route('admin.donhang.sua', ['id' => $value->id])}}">
                            <i class="fa-light fa-edit"></i>
                        </a>
                    </td>
                    <!-- <td class="text-center">
                        <a href="{{ route('admin.donhang.xoa', ['id' => $value->id]) }}"
                            onclick="return confirm('Bạn có muốn xóa đơn hàng của khách {{ $value->NguoiDung->name }} không?')">
                            <i class="fa-light fa-trash-alt text-danger"></i>
                        </a>
                    </td> -->

                </tr>

                @endforeach
            </tbody>

        </table>

    </div>
</div>
@endsection