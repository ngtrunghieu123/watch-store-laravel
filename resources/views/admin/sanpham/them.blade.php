@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">Thêm sản phẩm</div>
    <div class="card-body">
        <form action="{{ route('admin.sanpham.them') }}" method="post" enctype="multipart/form-data">
            <!-- thêm enctype để xử lý file upload -->
            @csrf
            <div class="mb-3">
                <label for="loaisanpham_id" class="form-label"><b>Loại sản phẩm</b></label>
                <div class="input-group">
                    <select
                        class="form-control @error('loaisanpham_id') is-invalid @enderror"
                        name="loaisanpham_id"
                        id="loaisanpham_id"
                        required>
                        @foreach ($loaisanphamlist as $value_Loai)
                        <option value="{{ $value_Loai->id }}" {{ old('loaisanpham_id') == $value_Loai->id ? 'selected' : '' }}>{{ $value_Loai->tenloai }}</option>
                        @endforeach
                    </select>
                    @error('loaisanpham_id')
                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fas fa-caret-down"></i> <!-- Biểu tượng tam giác -->
                        </span>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="hangsanxuat_id" class="form-label"><b>Hãng sản xuất</b></label>
                <div class="input-group">
                    <select
                        class="form-control @error('hangsanxuat_id') is-invalid @enderror"
                        name="hangsanxuat_id"
                        id="hangsanxuat_id"
                        required>
                        @foreach ($hangsanxuatlist as $value_Hang)
                        <option value="{{ $value_Hang->id }}" {{ old('hangsanxuat_id') == $value_Hang->id ? 'selected' : '' }}>{{ $value_Hang->tenhang }}</option>
                        @endforeach
                    </select>
                    @error('hangsanxuat_id')
                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="fas fa-caret-down"></i> <!-- Biểu tượng tam giác -->
                        </span>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="tensanpham" class="form-label"><b>Tên sản phẩm</b></label>
                <input
                    type="text"
                    class="form-control @error('tensanpham') is-invalid @enderror"
                    id="tensanpham"
                    name="tensanpham"
                    value="{{ old('tensanpham') }}"
                    required />
                @error('tensanpham')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="soluong" class="form-label"><b>Số Lượng</b></label>
                <input
                    type="number"
                    class="form-control @error('soluong') is-invalid @enderror"
                    id="soluong"
                    name="soluong"
                    min="1"
                    value="{{ old('soluong') }}"
                    required />
                @error('soluong')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="dongia" class="form-label"><b>Đơn Giá</b></label>
                <input
                    type="number"
                    class="form-control @error('dongia') is-invalid @enderror"
                    id="dongia"
                    name="dongia"
                    min="0"
                    step="0.01"
                    value="{{ old('dongia') }}"
                    required />
                @error('dongia')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="hinhanh">Hình ảnh sản phẩm</label>
                <input type="file" class="form-control @error('hinhanh') is-invalid @enderror" id="hinhanh" name="hinhanh" value="{{ old('hinhanh') }}" />
                @error('hinhanh')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>
            <!-- <div class="mb-3">
                <label for="hinhanh" class="form-label"><b>Hình Ảnh</b></label>
                <input 
                    type="file" 
                    class="form-control @error('hinhanh') is-invalid @enderror" 
                    id="hinhanh" 
                    name="hinhanh" 
                />
                @error('hinhanh')
                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div> -->

            <div class="mb-3">
                <label for="motasanpham" class="form-label"><b>Mô Tả Sản Phẩm</b></label>
                <textarea
                    class="form-control @error('motasanpham') is-invalid @enderror"
                    id="motasanpham"
                    name="motasanpham"
                    rows="4">{{ old('motasanpham') }}</textarea>
                @error('motasanpham')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary"><i class="fa-light fa-save"></i> Thêm vào CSDL</button>
        </form>
    </div>
</div>
@endsection