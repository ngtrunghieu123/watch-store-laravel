@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">Thêm người dùng</div>
    <div class="card-body">
        <form action="{{route('admin.nguoidung.them')}}" method="post">
            @csrf
            <div class="mt-3">
                <label class="form-lable" for="name">Họ và tên</label>
                <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name"
                    value="{{old('name')}}" required />
                @error('name')
                <div class="invalid-feedback"><strong>{{$message}}</strong></div>
                @enderror
            </div>
            <div class="mt-3">
                <label class="form-lable" for="email">Địa chỉ email</label>
                <input class="form-control @error('email') is-invalid @enderror" type="text" id="email" name="email"
                    value="{{old('email')}}" required />
                @error('email')
                <div class="invalid-feedback"><strong>{{$message}}</strong></div>
                @enderror
            </div>
            <div class="mt-3">
                <label class="form-lable" for="role">Quyền hạn</label>
                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                    <option value="">--Chọn--</option>
                    <option value="admin">Quản trị viên</option>
                    <option value="user">Khách hàng</option>
                </select>
                @error('role')
                <div class="invalid-feedback"><strong>{{$message}}</strong></div>
                @enderror
            </div>
            <div class="mt-3">
                <label class="form-lable" for="password">Mật khẩu mới</label>
                <input class="form-control @error('password') is-invalid @enderror" type="password" id="password"
                    name="password" required />
                @error('password')
                <div class="invalid-feedback"><strong>{{$message}}</strong></div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="password_confirmation">Xác nhận mật khẩu mới</label>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                    id="password_confirmation" name="password_confirmation" required />
                @error('password_confirmation')
                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                @enderror
            </div>
            <button class="btn btn-primary"><i class="fa-light fa-user-plus"></i>Thêm vào CSDL</button>
        </form>
    </div>
</div>
@endsection