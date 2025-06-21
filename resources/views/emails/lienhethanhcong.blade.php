<x-mail::message>
Xin chào {{ config('app.name', 'Laravel') }}!
## Thông tin:
chủ đề: **{{ $lienhe->ChuDe }}**<br>
Điện thoại: **{{ $lienhe->DienThoai }}**<br>
Địa chỉ email: **{{ $lienhe->Email }}**<br>
Nội dung:**{{ $lienhe->NoiDung }}**
</x-mail::message>