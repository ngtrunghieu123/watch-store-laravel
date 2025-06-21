<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoaiSanPhamController;
use App\Http\Controllers\HangSanXuatController;
use App\Http\Controllers\SanPhamController;
use App\Http\Controllers\TinhTrangController;
use App\Http\Controllers\DonHangController;
use App\Http\Controllers\NguoiDungController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\KhachHangController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChuDeController;
use App\Http\Controllers\BaiVietController;
use App\Http\Controllers\KhuyenMaiController;
use App\Http\Controllers\BinhLuanBaiVietController;
use App\Http\Controllers\DanhGiaSanPhamController;
use App\Http\Controllers\DanhGiaController;
use App\Http\Controllers\ChatbotController;

// Đăng ký, đăng nhập, Quên mật khẩu
Auth::routes();
// Google OAuth
Route::get('/login/google', [HomeController::class, 'getGoogleLogin'])->name('google.login');
Route::get('/login/google/callback', [HomeController::class, 'getGoogleCallback'])->name('google.callback');
// Các trang dành cho khách chưa đăng nhập
Route::name('frontend.')->group(function () {
    // Trang chủ
    Route::get('/', [HomeController::class, 'getHome'])->name('home');
    Route::get('/home', [HomeController::class, 'getHome'])->name('home');

    // Trang sản phẩm
    Route::get('/san-pham/hang/{tenhang_slug}/gia/{mucgia}', [HomeController::class, 'getSanPhamTheoHangVaGia'])->name('sanpham.hangsanxuat.gia');
    Route::get('/san-pham/hang/{tenhang_slug}', [HomeController::class, 'getSanPhamTheoHang'])->name('sanpham.hangsanxuat');
    Route::get('/san-pham/gia/{mucgia}', [HomeController::class, 'getSanPhamTheoGia'])->name('sanpham.gia');
    Route::get('/san-pham/{tenloai_slug}/gia/{mucgia}', [HomeController::class, 'getSanPhamTheoLoaiVaGia'])->name('sanpham.phanloai.gia');
    Route::get('/san-pham/{tenloai_slug}/{tensanpham_slug}', [HomeController::class, 'getSanPham_ChiTiet'])->name('sanpham.chitiet');
    Route::get('/san-pham/{tenloai_slug}', [HomeController::class, 'getSanPham'])->name('sanpham.phanloai');
    Route::get('/san-pham', [HomeController::class, 'getSanPham'])->name('sanpham');

    // khuyen mai   
    Route::get('/khuyen-mai', [HomeController::class, 'getKhuyenMai'])->name('khuyenmai');
    // Tin tức
    Route::get('/bai-viet', [HomeController::class, 'getBaiViet'])->name('baiviet');
    Route::get('/bai-viet/{tenchude_slug}', [HomeController::class, 'getBaiViet'])->name('baiviet.chude');
    Route::get('/bai-viet/{tenchude_slug}/{tieude_slug}', [HomeController::class, 'getBaiViet_ChiTiet'])->name('baiviet.chitiet');

    // Trang giỏ hàng
    Route::get('/gio-hang', [HomeController::class, 'getGioHang'])->name('giohang');
    Route::get('/gio-hang/them/{tensanpham_slug}', [HomeController::class, 'getGioHang_Them'])->name('giohang.them');
    Route::get('/gio-hang/xoa/{row_id}', [HomeController::class, 'getGioHang_Xoa'])->name('giohang.xoa');
    Route::get('/gio-hang/giam/{row_id}', [HomeController::class, 'getGioHang_Giam'])->name('giohang.giam');
    Route::get('/gio-hang/tang/{row_id}', [HomeController::class, 'getGioHang_Tang'])->name('giohang.tang');
    Route::post('/gio-hang/cap-nhat', [HomeController::class, 'postGioHang_CapNhat'])->name('giohang.capnhat');

    // Trang tìm kiếm
    Route::get('/tim-kiem', [HomeController::class, 'getTimKiem'])->name('timkiem');
    // Tuyển dụng
    Route::get('/tuyen-dung', [HomeController::class, 'getTuyenDung'])->name('tuyendung');
    Route::post('/send', [App\Http\Controllers\ChatbotController::class, 'sendMessage'])->name('send');
    Route::get('/history', [App\Http\Controllers\ChatbotController::class, 'getHistory'])->name('history');
    // Liên hệ
    Route::get('/lien-he', [HomeController::class, 'getLienHe'])->name('lienhe');
    Route::post('/lien-he', [HomeController::class, 'postLienHe'])->name('lienhe');
    Route::get('/lien-he-thang-cong', [HomeController::class, 'getLienHeThangCong'])->name('lienhethanhcong');
});


// Trang khách hàng
Route::get('/khach-hang/dang-ky', [HomeController::class, 'getDangKy'])->name('user.dangky');
Route::get('/khach-hang/dang-nhap', [HomeController::class, 'getDangNhap'])->name('user.dangnhap');
Route::get('/khach-hang/quen-mat-khau', [HomeController::class, 'getQuenMatKhau'])->name('user.quenmatkhau');

// Trang tài khoản khách hàng
Route::prefix('khach-hang')->name('user.')->middleware('auth')->group(function () {
    // Trang chủ
    Route::get('/', [KhachHangController::class, 'getHome'])->name('home');
    Route::get('/home', [KhachHangController::class, 'getHome'])->name('home');

    // Đặt hàng
    Route::get('/dat-hang', [KhachHangController::class, 'getDatHang'])->name('dathang');
    Route::post('/dat-hang', [KhachHangController::class, 'postDatHang'])->name('dathang');
    Route::post('/dat-hang-nhanh', [HomeController::class, 'postDatHangNhanh'])->name('dathang.nhanh');
    Route::get('/dat-hang-thanh-cong', [KhachHangController::class, 'getDatHangThanhCong'])->name('dathangthanhcong');

    // Xem và cập nhật trạng thái đơn hàng
    Route::get('/don-hang', [KhachHangController::class, 'getDonHang'])->name('donhang');
    Route::get('/don-hang/{id}', [KhachHangController::class, 'getDonHangChiTiet'])->name('donhang.chitiet');
    Route::post('/don-hang/{id}', [KhachHangController::class, 'postDonHang'])->name('donhang.chitiet');
    Route::post('/donhang/huy/{id}', [KhachHangController::class, 'postHuyDonHang'])->name('donhang.huy');
    // Cập nhật thông tin tài khoản
    Route::get('/ho-so-ca-nhan', [KhachHangController::class, 'getHoSoCaNhan'])->name('hosocanhan');
    Route::post('/ho-so-ca-nhan', [KhachHangController::class, 'postHoSoCaNhan'])->name('hosocanhan');
    // Sản phẩm yêu thích
    Route::get('/yeu-thich', [KhachHangController::class, 'getYeuThich'])->name('yeuthich');
    Route::post('/yeu-thich/them/{id}', [KhachHangController::class, 'postYeuThichThem'])->name('yeuthich.them');
    Route::get('/yeu-thich/xoa/{id}', [KhachHangController::class, 'getYeuThichXoa'])->name('yeuthich.xoa');
    // Đăng xuất
    Route::post('/dang-xuat', [KhachHangController::class, 'postDangXuat'])->name('dangxuat');
    // Bình luận
    Route::post('/binh-luan-them/{baiviet_id}', [KhachHangController::class, 'postBinhluan'])->name('binhluan.them');
    // Thanh toán VNPay 
    Route::get('/thanh-toan/vnpay', [KhachHangController::class, 'vnpay_payment'])->name('vnpay.payment');
    // Route::get('/thanh-toan/vnpay/return', [KhachHangController::class, 'vnpay_return'])->name('vnpay.return');

    // Thanh toán Momo
    Route::get('/thanh-toan/momo', [KhachHangController::class, 'momo_payment'])->name('momo.payment');

    // Quản lý ảnh đại diện
    Route::post('/avatar/update', [KhachHangController::class, 'postAvatar'])->name('avatar.update');
    Route::delete('/avatar/delete', [KhachHangController::class, 'deleteAvatar'])->name('avatar.delete');
    // danh gia tu trang don hang
    Route::get('/tai-khoan/don-hang/danh-gia/{id}', [KhachHangController::class, 'getDanhGiaSanPham'])->name('donhang.danhgia.sanpham');
    // Đánh giá sản phẩm
    Route::get('/danh-gia', [KhachHangController::class, 'getDanhGia'])->name('danhgia');
    Route::post('/danh-gia/them', [DanhGiaSanPhamController::class, 'postThem'])->name('danhgia.them');
    Route::post('/danh-gia/sua/{id}', [DanhGiaSanPhamController::class, 'postSua'])->name('danhgia.sua');
    Route::get('/danh-gia/xoa/{id}', [DanhGiaSanPhamController::class, 'getXoa'])->name('danhgia.xoa');
});
// Trang tài khoản quản lý
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // Trang chủ
    Route::get('/', [AdminController::class, 'getHome'])->name('home');
    Route::get('/home', [AdminController::class, 'getHome'])->name('home');
    // Quản lý Loại sản phẩm
    Route::get('/loaisanpham', [LoaiSanPhamController::class, 'getDanhSach'])->name('loaisanpham');
    Route::get('/loaisanpham/them', [LoaiSanPhamController::class, 'getThem'])->name('loaisanpham.them');
    Route::post('/loaisanpham/them', [LoaiSanPhamController::class, 'postThem'])->name('loaisanpham.them');
    Route::get('/loaisanpham/sua/{id}', [LoaiSanPhamController::class, 'getSua'])->name('loaisanpham.sua');
    Route::post('/loaisanpham/sua/{id}', [LoaiSanPhamController::class, 'postSua'])->name('loaisanpham.sua');
    Route::get('/loaisanpham/xoa/{id}', [LoaiSanPhamController::class, 'getXoa'])->name('loaisanpham.xoa');
    // Quản lý Hãng sản xuất
    Route::get('/hangsanxuat', [HangSanXuatController::class, 'getDanhSach'])->name('hangsanxuat');
    Route::get('/hangsanxuat/them', [HangSanXuatController::class, 'getThem'])->name('hangsanxuat.them');
    Route::post('/hangsanxuat/them', [HangSanXuatController::class, 'postThem'])->name('hangsanxuat.them');
    Route::get('/hangsanxuat/sua/{id}', [HangSanXuatController::class, 'getSua'])->name('hangsanxuat.sua');
    Route::post('/hangsanxuat/sua/{id}', [HangSanXuatController::class, 'postSua'])->name('hangsanxuat.sua');
    Route::get('/hangsanxuat/xoa/{id}', [HangSanXuatController::class, 'getXoa'])->name('hangsanxuat.xoa');
    Route::post('/hangsanxuat/nhap', [HangSanXuatController::class, 'postNhap'])->name('hangsanxuat.nhap');
    Route::get('/hangsanxuat/xuat', [HangSanXuatController::class, 'getXuat'])->name('hangsanxuat.xuat');
    // Quản lý Sản phẩm
    Route::get('/sanpham', [SanPhamController::class, 'getDanhSach'])->name('sanpham');
    Route::get('/sanpham/them', [SanPhamController::class, 'getThem'])->name('sanpham.them');
    Route::post('/sanpham/them', [SanPhamController::class, 'postThem'])->name('sanpham.them');
    Route::get('/sanpham/sua/{id}', [SanPhamController::class, 'getSua'])->name('sanpham.sua');
    Route::post('/sanpham/sua/{id}', [SanPhamController::class, 'postSua'])->name('sanpham.sua');
    Route::get('/sanpham/xoa/{id}', [SanPhamController::class, 'getXoa'])->name('sanpham.xoa');
    Route::post('/sanpham/nhap', [SanPhamController::class, 'postNhap'])->name('sanpham.nhap');
    Route::get('/sanpham/xuat', [SanPhamController::class, 'getXuat'])->name('sanpham.xuat');
    // Quản lý Tình trạng
    Route::get('/tinhtrang', [TinhTrangController::class, 'getDanhSach'])->name('tinhtrang');
    Route::get('/tinhtrang/them', [TinhTrangController::class, 'getThem'])->name('tinhtrang.them');
    Route::post('/tinhtrang/them', [TinhTrangController::class, 'postThem'])->name('tinhtrang.them');
    Route::get('/tinhtrang/sua/{id}', [TinhTrangController::class, 'getSua'])->name('tinhtrang.sua');
    Route::post('/tinhtrang/sua/{id}', [TinhTrangController::class, 'postSua'])->name('tinhtrang.sua');
    Route::get('/tinhtrang/xoa/{id}', [TinhTrangController::class, 'getXoa'])->name('tinhtrang.xoa');
    // Quản lý Đơn hàng
    Route::get('/donhang', [DonHangController::class, 'getDanhSach'])->name('donhang');
    Route::get('/donhang/them', [DonHangController::class, 'getThem'])->name('donhang.them');
    Route::post('/donhang/them', [DonHangController::class, 'postThem'])->name('donhang.them');
    Route::get('/donhang/sua/{id}', [DonHangController::class, 'getSua'])->name('donhang.sua');
    Route::post('/donhang/sua/{id}', [DonHangController::class, 'postSua'])->name('donhang.sua');
    Route::get('/donhang/xoa/{id}', [DonHangController::class, 'getXoa'])->name('donhang.xoa');
    Route::get('/donhang/timkiem', [DonHangController::class, 'getTimKiem'])->name('donhang.timkiem');
    Route::get('/donhang/thongke', [DonHangController::class, 'getThongKe'])->name('donhang.thongke');
    // Quản lý Tài khoản người dùng
    Route::get('/nguoidung', [NguoiDungController::class, 'getDanhSach'])->name('nguoidung');
    Route::get('/nguoidung/them', [NguoiDungController::class, 'getThem'])->name('nguoidung.them');
    Route::post('/nguoidung/them', [NguoiDungController::class, 'postThem'])->name('nguoidung.them');
    Route::get('/nguoidung/sua/{id}', [NguoiDungController::class, 'getSua'])->name('nguoidung.sua');
    Route::post('/nguoidung/sua/{id}', [NguoiDungController::class, 'postSua'])->name('nguoidung.sua');
    Route::get('/nguoidung/xoa/{id}', [NguoiDungController::class, 'getXoa'])->name('nguoidung.xoa');

    // Quản lý Chủ đề
    Route::get('/chude', [ChuDeController::class, 'getDanhSach'])->name('chude');
    Route::get('/chude/them', [ChuDeController::class, 'getThem'])->name('chude.them');
    Route::post('/chude/them', [ChuDeController::class, 'postThem'])->name('chude.them');
    Route::get('/chude/sua/{id}', [ChuDeController::class, 'getSua'])->name('chude.sua');
    Route::post('/chude/sua/{id}', [ChuDeController::class, 'postSua'])->name('chude.sua');
    Route::get('/chude/xoa/{id}', [ChuDeController::class, 'getXoa'])->name('chude.xoa');

    // Quản lý Bài viết
    Route::get('/baiviet', [BaiVietController::class, 'getDanhSach'])->name('baiviet');
    Route::get('/baiviet/them', [BaiVietController::class, 'getThem'])->name('baiviet.them');
    Route::post('/baiviet/them', [BaiVietController::class, 'postThem'])->name('baiviet.them');
    Route::get('/baiviet/sua/{id}', [BaiVietController::class, 'getSua'])->name('baiviet.sua');
    Route::post('/baiviet/sua/{id}', [BaiVietController::class, 'postSua'])->name('baiviet.sua');
    Route::get('/baiviet/xoa/{id}', [BaiVietController::class, 'getXoa'])->name('baiviet.xoa');
    Route::get('/baiviet/kiemduyet/{id}', [BaiVietController::class, 'getKiemDuyet'])->name('baiviet.kiemduyet');
    Route::get('/baiviet/kichhoat/{id}', [BaiVietController::class, 'getKichHoat'])->name('baiviet.kichhoat');

    // Quản lý Bình luận bài viết
    Route::get('/binhluanbaiviet', [BinhLuanBaiVietController::class, 'getDanhSach'])->name('binhluanbaiviet');
    Route::get('/binhluanbaiviet/them', [BinhLuanBaiVietController::class, 'getThem'])->name('binhluanbaiviet.them');
    Route::post('/binhluanbaiviet/them', [BinhLuanBaiVietController::class, 'postThem'])->name('binhluanbaiviet.them');
    Route::get('/binhluanbaiviet/sua/{id}', [BinhLuanBaiVietController::class, 'getSua'])->name('binhluanbaiviet.sua');
    Route::post('/binhluanbaiviet/sua/{id}', [BinhLuanBaiVietController::class, 'postSua'])->name('binhluanbaiviet.sua');
    Route::get('/binhluanbaiviet/xoa/{id}', [BinhLuanBaiVietController::class, 'getXoa'])->name('binhluanbaiviet.xoa');
    Route::get('/binhluanbaiviet/kiemduyet/{id}', [BinhLuanBaiVietController::class, 'getKiemDuyet'])->name('binhluanbaiviet.kiemduyet');
    Route::get('/binhluanbaiviet/kichhoat/{id}', [BinhLuanBaiVietController::class, 'getKichHoat'])->name('binhluanbaiviet.kichhoat');
    // Quản lý Khuyến mãi
    Route::get('/khuyenmai', [KhuyenMaiController::class, 'getDanhSach'])->name('khuyenmai');
    Route::get('/khuyenmai/them', [KhuyenMaiController::class, 'getThem'])->name('khuyenmai.them');
    Route::post('/khuyenmai/them', [KhuyenMaiController::class, 'postThem'])->name('khuyenmai.them');
    Route::get('/khuyenmai/sua/{id}', [KhuyenMaiController::class, 'getSua'])->name('khuyenmai.sua');
    Route::post('/khuyenmai/sua/{id}', [KhuyenMaiController::class, 'postSua'])->name('khuyenmai.sua');
    Route::get('/khuyenmai/xoa/{id}', [KhuyenMaiController::class, 'getXoa'])->name('khuyenmai.xoa');
    Route::post('/khuyenmai/nhap', [KhuyenMaiController::class, 'postNhap'])->name('khuyenmai.nhap');
    Route::get('/khuyenmai/xuat', [KhuyenMaiController::class, 'getXuat'])->name('khuyenmai.xuat');

    // Quản lý đánh giá sản phẩm
    Route::get('/danhgiasanpham', [DanhGiaController::class, 'getDanhSach'])->name('danhgiasanpham');
    Route::get('/danhgiasanpham/duyet/{id}', [DanhGiaController::class, 'getDuyet'])->name('danhgiasanpham.duyet');
    Route::post('/danhgiasanpham/duyet-all', [DanhGiaController::class, 'postDuyetAll'])->name('danhgiasanpham.duyetAll');
    Route::post('/danhgiasanpham/huyduyet-all', [DanhGiaController::class, 'postHuyDuyetAll'])->name('danhgiasanpham.huyduyetAll');
    Route::get('/danhgiasanpham/xoa/{id}', [DanhGiaController::class, 'getXoa'])->name('danhgiasanpham.xoa');
});
