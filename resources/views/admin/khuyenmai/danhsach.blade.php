@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Danh sách khuyến mãi</div>

                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif

                    <p>
                        <a href="{{ route('admin.khuyenmai.them') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm mới
                        </a>
                    </p>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-file-excel me-1"></i>
                            Nhập / Xuất dữ liệu
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Form nhập Excel -->
                                <div class="col-md-6">
                                    <form action="{{ route('admin.khuyenmai.nhap') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="input-group mb-3">
                                            <input type="file" class="form-control @error('file_excel') is-invalid @enderror" id="file_excel" name="file_excel" accept=".xlsx, .xls">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-upload me-1"></i> Nhập từ Excel
                                            </button>
                                        </div>
                                        @error('file_excel')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </form>
                                </div>

                                <!-- Nút xuất Excel -->
                                <div class="col-md-6 text-end">
                                    <a href="{{ route('admin.khuyenmai.xuat') }}" class="btn btn-success">
                                        <i class="fas fa-download me-1"></i> Xuất ra Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table class="table table-bordered table-hover table-sm mb-0">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="20%">Tên khuyến mãi</th>
                                <th width="20%">Thời gian</th>
                                <th width="10%">Phần trăm</th>
                                <th width="10%">Trạng thái</th>
                                <th width="15%">Số lượng SP</th>
                                <th width="5%">Sửa</th>
                                <th width="5%">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($khuyenmai as $value)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $value->tenkhuyenmai }}</td>
                                <td>{{ date('d/m/Y', strtotime($value->ngaybatdau)) }} - {{ date('d/m/Y', strtotime($value->ngayketthuc)) }}</td>
                                <td class="text-end">{{ $value->phantram }}%</td>
                                <td class="text-center">
                                    @if($value->trangthai == 1)
                                    <span class="badge bg-success">Hiện</span>
                                    @else
                                    <span class="badge bg-danger">Ẩn</span>
                                    @endif
                                </td>
                                <td class="text-end">{{ $value->sanPham->count() }} sản phẩm</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.khuyenmai.sua', ['id' => $value->id]) }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.khuyenmai.xoa', ['id' => $value->id]) }}"
                                        onclick="return confirm('Bạn có muốn xóa khuyến mãi {{ $value->tenkhuyenmai }} không?')">
                                        <i class="fas fa-trash-alt text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach

                            @if($khuyenmai->count() == 0)
                            <tr>
                                <td colspan="8" class="text-center">Không có dữ liệu</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection