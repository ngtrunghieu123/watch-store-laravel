@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">Sửa người dùng</div>
    <div class="card-body">
        <form action="{{route('admin.nguoidung.sua', ['id' => $nguoidung->id])}}" method="post">
            @csrf
            <div class="mt-3">
                <label class="form-lable" for="name"><b>Họ và tên</b></label>
                <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name"
                    value="{{$nguoidung->name}}" required />
                @error('name')
                <div class="invalid-feedback"><strong>{{$message}}</strong></div>
                @enderror
            </div>
            <div class="mt-3">
                <label class="form-lable" for="email"><b>Địa chỉ email</b></label>
                <input class="form-control @error('email') is-invalid @enderror" type="text" id="email" name="email"
                    value="{{$nguoidung->email}}" required />
                @error('email')
                <div class="invalid-feedback"><strong>{{$message}}</strong></div>
                @enderror
            </div>
            <div class="mt-3">
                <label class="form-lable" for="role"><b>Quyền hạn</b></label>
                <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
                    <option value="">--Chọn--</option>
                    <option value="admin" {{($nguoidung->role == 'admin') ? 'selected' : ''}}>Quản trị viên</option>
                    <option value="user" {{($nguoidung->role == 'user') ? 'selected' : ''}}>Khách hàng</option>
                </select>
                @error('role')
                <div class="invalid-feedback"><strong>{{$message}}</strong></div>
                @enderror
            </div>
            <div class="mb-3 form-check">
                <input class="form-check-input" type="checkbox" id="change_password_checkbox"
                    name="change_password_checkbox" />
                <label class="form-check-label" for="change_password_checkbox">Đổi mật khẩu</label>
            </div>

            <div id="change_password_group">
                <div class="mb-3">
                    <label class="form-lable" for="password"><b>Mật khẩu mới</b></label>
                    <input class="form-control @error('password') is-invalid @enderror" type="password" id="password"
                        name="password" required />
                    @error('password')
                    <div class="invalid-feedback"><strong>{{$message}}</strong></div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="password_confirmation"><b>Xác nhận mật khẩu mới</b></label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                        id="password_confirmation" name="password_confirmation" required />
                    @error('password_confirmation')
                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                    @enderror
                </div>
                <!-- <div id="change_password_group">
                <div class="mb-3">
                    <label class="form-lable" for="password"><b>Mật khẩu mới</b></label>
                    <input class="form-control  is-invalid" type="password" id="password"
                        name="password" />
                </div>

                <div class="mb-3">
                    <label class="form-label" for="password_confirmation"><b>Xác nhận mật khẩu mới</b></label>
                    <input type="password" class="form-control is-invalid"
                        id="password_confirmation" name="password_confirmation" />

                </div> -->
            </div>
            <button class="btn btn-primary"><i class="fa-light fa-user-plus"></i>Cập nhật</button>
    </div>
    </form>
</div>
</div>
@endsection
@section('javascript')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        $("#change_password_group").hide();
        $("#change_password_checkbox").change(function() {
            if ($(this).is(":checked")) {
                $("#change_password_group").show();
                $("#change_password_group :input").attr("required", "required");
            } else {
                $("#change_password_group").hide();
                $("#change_password_group :input").removeAttr("required");
            }
        });
    });
</script>
@endsection