<x-mail::message>
Xin chào {{ Auth::user()->name }}!
Xin cảm ơn bạn đã đặt hàng tại {{ config('app.name', 'Laravel') }}.
## Thông tin giao hàng:
Điện thoại: **{{ $donhang->dienthoaigiaohang }}**
Địa chỉ giao hàng: **{{ $donhang->diachigiaohang }}**
## Thông tin đơn hàng đã đặt:
<x-mail::table>
| # | Sản phẩm | SL | Đơn giá | Thành tiền |
|:-----|:-------------------------------|:-----:|----------:|------------:|
@php $tongtien = 0; @endphp
@foreach($donhang->DonHang_ChiTiet as $chitiet)
| {{ $loop->iteration }} | {{ $chitiet->SanPham->tensanpham }} | {{ $chitiet->soluongban }} | {{ number_format($chitiet->dongiaban) }}đ |{{ number_format($chitiet->soluongban * $chitiet->dongiaban) }}đ |
@php $tongtien += $chitiet->soluongban * $chitiet->dongiaban; @endphp
@endforeach
||||| **Tạm tính: {{ number_format($tongtien) }}đ** |
||||| **Thuế (10%): {{ number_format($tongtien * 0.1) }}đ** |
||||| **Tổng cộng: {{ number_format($tongtien * 1.1) }}đ** |
</x-mail::table>
Trân trọng,<br>
{{ config('app.name', 'Laravel') }}
</x-mail::message>