@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Thêm khuyến mãi</div>

                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif
                    <form action="{{ route('admin.khuyenmai.them') }} " method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="tenkhuyenmai">Tên khuyến mãi</label>
                            <input type="text" class="form-control @error('tenkhuyenmai') is-invalid @enderror" id="tenkhuyenmai" name="tenkhuyenmai" value="{{ old('tenkhuyenmai') }}" required />
                            @error('tenkhuyenmai')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="mota">Mô tả</label>
                            <textarea class="form-control @error('mota') is-invalid @enderror" id="mota" name="mota">{{ old('mota') }}</textarea>
                            @error('mota')
                            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="ngaybatdau">Ngày bắt đầu</label>
                                    <input type="date" class="form-control @error('ngaybatdau') is-invalid @enderror" id="ngaybatdau" name="ngaybatdau" value="{{ old('ngaybatdau', date('Y-m-d')) }}" required />
                                    @error('ngaybatdau')
                                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="ngayketthuc">Ngày kết thúc</label>
                                    <input type="date" class="form-control @error('ngayketthuc') is-invalid @enderror" id="ngayketthuc" name="ngayketthuc" value="{{ old('ngayketthuc', date('Y-m-d', strtotime('+7 days'))) }}" required />
                                    @error('ngayketthuc')
                                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="phantram">Phần trăm giảm giá</label>
                            <div class="input-group">
                                <input type="number" min="1" max="100" class="form-control @error('phantram') is-invalid @enderror" id="phantram" name="phantram" value="{{ old('phantram', 10) }}" required />
                                <span class="input-group-text">%</span>
                                @error('phantram')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" id="trangthai" name="trangthai" value="1" checked /> <label class="form-check-label" for="trangthai">Kích hoạt</label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Chọn sản phẩm</label>

                            <!-- Thêm bộ lọc tìm kiếm -->
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" class="form-control" id="searchProduct" placeholder="Tìm theo tên sản phẩm..." onkeyup="filterProducts()">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-filter"></i></span>
                                        <select class="form-select" id="filterBrand" onchange="filterProducts()">
                                            <option value="">-- Tất cả hãng --</option>
                                            @php
                                            $brands = \App\Models\HangSanXuat::orderBy('tenhang')->get();
                                            @endphp
                                            @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->tenhang }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="checkAll()">Chọn tất cả</button>
                                    <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="uncheckAll()">Bỏ chọn tất cả</button>
                                </div>
                            </div>

                            <!-- Hiển thị số lượng sản phẩm đã lọc -->
                            <div class="mb-2">
                                <small class="text-muted">Hiển thị <span id="countProducts">{{ $sanpham->count() }}</span> sản phẩm</small>
                            </div>

                            <!-- Danh sách sản phẩm -->
                            <div class="product-list p-4 border rounded" style="max-height: 500px; overflow-y: auto;">
                                <div class="row">
                                    @foreach($sanpham as $sp)
                                    <div class="col-md-3 mb-2 product-item"
                                        data-name="{{ strtolower($sp->tensanpham) }}"
                                        data-brand="{{ $sp->hangsanxuat_id }}">
                                        <div class="form-check border p-2 rounded">
                                            <input class="form-check-input product-checkbox" type="checkbox" id="sanpham_{{ $sp->id }}"
                                                name="sanpham_id[]" value="{{ $sp->id }}" />
                                            <label class="form-check-label" for="sanpham_{{ $sp->id }}">
                                                <div>{{ $sp->tensanpham }}</div>
                                                <small class="text-muted">{{ $sp->hangSanXuat->tenhang }}</small>
                                                <div class="text-danger">{{ number_format($sp->dongia, 0, ',', '.') }}đ</div>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @error('sanpham_id')
                            <div class="text-danger"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Thêm vào CSDL</button>
                        <a href="{{ route('admin.khuyenmai') }}" class="btn btn-secondary ms-2"><i class="fas fa-arrow-left"></i> Quay lại</a>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@section('javascript')
<script>
    function filterProducts() {
        const searchText = document.getElementById('searchProduct').value.toLowerCase();
        const brandFilter = document.getElementById('filterBrand').value;
        const productItems = document.getElementsByClassName('product-item');
        let visibleCount = 0;

        for (let i = 0; i < productItems.length; i++) {
            const item = productItems[i];
            const productName = item.getAttribute('data-name');
            const brandId = item.getAttribute('data-brand');

            // Kiểm tra điều kiện tìm kiếm
            const matchesSearch = productName.includes(searchText);
            const matchesBrand = brandFilter === '' || brandId === brandFilter;

            // Hiển thị hoặc ẩn sản phẩm dựa trên kết quả tìm kiếm
            if (matchesSearch && matchesBrand) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        }

        // Cập nhật số lượng sản phẩm hiển thị
        document.getElementById('countProducts').textContent = visibleCount;
    }

    function checkAll() {
        console.log('Checking all products');
        const visibleProducts = document.querySelectorAll('.product-item[style="display: block"] .product-checkbox, .product-item:not([style="display: none"]) .product-checkbox');
        console.log('Visible products:', visibleProducts.length);

        for (let i = 0; i < visibleProducts.length; i++) {
            visibleProducts[i].checked = true;
        }
    }

    function uncheckAll() {
        console.log('Unchecking all products');
        const visibleProducts = document.querySelectorAll('.product-item[style="display: block"] .product-checkbox, .product-item:not([style="display: none"]) .product-checkbox');
        console.log('Visible products:', visibleProducts.length);

        for (let i = 0; i < visibleProducts.length; i++) {
            visibleProducts[i].checked = false;
        }
    }
</script>
@endsection
@endsection