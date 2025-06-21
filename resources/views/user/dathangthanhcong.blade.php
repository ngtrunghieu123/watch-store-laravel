@extends('layouts.frontend')
@section('title', 'Hoàn tất đặt hàng')
@section('content')
<div class="container pb-5 mb-sm-4">
    <div class="pt-5">
        <div class="card py-3 mt-sm-3">
            <div class="card-body text-center">
                <h2 class="h4 pb-3">Cảm ơn bạn đã đặt hàng!</h2>

                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                <div class="py-4 mb-3">
                    <svg width="72" height="72" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M36 72C55.882 72 72 55.882 72 36C72 16.118 55.882 0 36 0C16.118 0 0 16.118 0 36C0 55.882 16.118 72 36 72Z" fill="#D4EDDA" />
                        <path d="M31.5 48.5L18 35L22.5 30.5L31.5 39.5L49.5 21.5L54 26L31.5 48.5Z" fill="#28A745" />
                    </svg>
                </div>

                <p class="fs-sm mb-2">Đơn hàng của bạn đã được đặt và sẽ được xử lý trong thời gian sớm nhất.</p>
                <p class="fs-sm mb-3">Bạn sẽ sớm nhận được email xác nhận đơn đặt hàng của mình.</p>

                @if(request()->has('vnp_ResponseCode') && request()->vnp_ResponseCode == '00')
                <div class="alert alert-info">
                    <h5 class="mb-2">Thanh toán VNPAY thành công</h5>
                    <p class="mb-0">Mã giao dịch: {{ request()->vnp_TransactionNo ?? 'N/A' }}</p>
                </div>
                @endif

                @if(request()->has('resultCode') && request()->resultCode == '0')
                <div class="alert alert-info">
                    <h5 class="mb-2">Thanh toán MoMo thành công</h5>
                    <p class="mb-0">Mã giao dịch: {{ request()->transId ?? 'N/A' }}</p>
                    <p class="mb-0">Mã đơn hàng: {{ request()->orderId ?? 'N/A' }}</p>
                </div>
                @endif

                <div class="mt-4">
                    <a class="btn btn-primary me-3" href="{{ route('user.donhang') }}">
                        <i class="ci-bag me-2"></i>Xem đơn hàng của tôi
                    </a>
                    <a class="btn btn-danger" href="{{ route('frontend.sanpham') }}">
                        <i class="ci-cart me-2"></i>Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection