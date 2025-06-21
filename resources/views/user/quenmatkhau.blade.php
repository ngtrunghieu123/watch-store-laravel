@extends('layouts.frontend')
@section('title', 'Quên mật khẩu')
@section('content')
<div class="container py-4 my-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="h4 mb-3">Chưa có tài khoản? Đăng ký</h2>
            <p class="fs-sm text-muted mb-4">Việc đăng ký chỉ mất chưa đầy một phút nhưng mang lại cho bạn toàn quyền kiểm soát đơn hàng của mình.</p>
            <form method="post" action="{{ route('password.request') }}" class="needs-validation" novalidate>
                @csrf
                <div class="row gx-4 gy-3">
                    <div class="col-sm-6">
                        <label class="form-label" for="email.">Nhập địa chỉ email</label>
                        <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required />
                        @error('email')
                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                        @enderror
                    </div>

                    <div class="col-12 text-end">
                        <button class="btn btn-primary" type="submit"><i class="ci-user me-2 ms-n1"></i>Xác nhận</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection