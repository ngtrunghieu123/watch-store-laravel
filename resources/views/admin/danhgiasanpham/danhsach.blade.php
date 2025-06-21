@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Quản lý đánh giá sản phẩm</h5>
                </div>

                <div class="card-body">
                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form action="{{ route('admin.danhgiasanpham.duyetAll') }}" method="post" id="formAction">
                        @csrf
                        <div class="mb-3 d-flex gap-2">
                            <button type="button" class="btn btn-success" onclick="submitForm('{{ route('admin.danhgiasanpham.duyetAll') }}')">
                                <i class="fas fa-check-circle me-1"></i> Duyệt đã chọn
                            </button>
                            <button type="button" class="btn btn-warning" onclick="submitForm('{{ route('admin.danhgiasanpham.huyduyetAll') }}')">
                                <i class="fas fa-ban me-1"></i> Bỏ duyệt đã chọn
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%" class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="checkAll">
                                            </div>
                                        </th>
                                        <th width="5%" class="text-center">ID</th>
                                        <th width="15%">Người dùng</th>
                                        <th width="20%">Sản phẩm</th>
                                        <th width="5%" class="text-center">Sao</th>
                                        <th width="30%">Nhận xét</th>
                                        <th width="10%" class="text-center">Hình ảnh</th>
                                        <th width="5%" class="text-center">Trạng thái</th>
                                        <th width="5%" class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($danhgia as $dg)
                                    <tr>
                                        <td class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input checkItem" type="checkbox" name="ids[]" value="{{ $dg->id }}">
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $dg->id }}</td>
                                        <td>{{ $dg->nguoiDung->name }}</td>
                                        <td>
                                            <a href="{{ route('frontend.sanpham.chitiet', ['tenloai_slug' => $dg->sanPham->loaiSanPham->tenloai_slug, 'tensanpham_slug' => $dg->sanPham->tensanpham_slug]) }}" target="_blank">
                                                {{ $dg->sanPham->tensanpham }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            @for($i=1; $i<=5; $i++)
                                                @if($i <=$dg->sosao)
                                                <i class="fas fa-star text-warning"></i>
                                                @else
                                                <i class="far fa-star"></i>
                                                @endif
                                                @endfor
                                        </td>
                                        <td>{{ $dg->binhluan }}</td>
                                        <td class="text-center">
                                            @if($dg->hinhanh)
                                            <a href="{{ asset('storage/app/public/'.$dg->hinhanh) }}" target="_blank" data-lightbox="review-images">
                                                <img src="{{ asset('storage/app/public/'.$dg->hinhanh) }}" class="img-thumbnail" width="50" alt="Hình ảnh đánh giá">
                                            </a>
                                            @else
                                            <span class="text-muted">Không có</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($dg->kiemduyet == 1)
                                            <span class="badge bg-success">Đã duyệt</span>
                                            @else
                                            <span class="badge bg-secondary">Chưa duyệt</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                @if($dg->kiemduyet == 0)
                                                <a href="{{ route('admin.danhgiasanpham.duyet', ['id' => $dg->id]) }}" class="btn btn-sm btn-success" title="Duyệt">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                @endif

                                                <a href="{{ route('admin.danhgiasanpham.xoa', ['id' => $dg->id]) }}" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa đánh giá này không?')" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Chưa có đánh giá nào.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>

                    <!-- Phân trang -->
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $danhgia->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý checkbox "Chọn tất cả"
        document.getElementById('checkAll').addEventListener('click', function() {
            const checkboxes = document.getElementsByClassName('checkItem');
            for (let i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = this.checked;
            }
        });
    });

    function submitForm(action) {
        const form = document.getElementById('formAction');
        form.action = action;

        // Kiểm tra xem có item nào được chọn không
        const checkboxes = document.getElementsByClassName('checkItem');
        let hasChecked = false;

        for (let i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                hasChecked = true;
                break;
            }
        }

        if (!hasChecked) {
            alert('Vui lòng chọn ít nhất một đánh giá');
            return;
        }

        form.submit();
    }
</script>
@endsection