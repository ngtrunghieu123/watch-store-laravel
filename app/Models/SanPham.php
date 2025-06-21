<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class SanPham extends Model
{
    protected $table = 'sanpham';
    protected $fillable = [
        'loaisanpham_id',
        'hangsanxuat_id',
        'tensanpham',
        'tensanpham_slug',
        'soluong',
        'dongia',
        'hinhanh',
        'motasanpham',
    ];


    public function LoaiSanPham(): BelongsTo
    {
        return $this->belongsTo(LoaiSanPham::class, 'loaisanpham_id', 'id');
    }

    public function HangSanXuat(): BelongsTo
    {
        return $this->belongsTo(HangSanXuat::class, 'hangsanxuat_id', 'id');
    }

    public function DonHang_ChiTiet(): HasMany
    {
        return $this->hasMany(DonHang_ChiTiet::class, 'sanpham_id', 'id');
    }

    // Thêm phương thức relation với KhuyenMai
    public function khuyenMai()
    {
        return $this->belongsToMany(KhuyenMai::class, 'sanpham_khuyenmai', 'sanpham_id', 'khuyenmai_id');
    }

    // Thêm phương thức lấy khuyến mãi hiện tại
    public function khuyenMaiHienTai()
    {
        $today = date('Y-m-d');
        return $this->khuyenMai()
            ->where('trangthai', 1)
            ->where('ngaybatdau', '<=', $today)
            ->where('ngayketthuc', '>=', $today)
            ->orderBy('phantram', 'desc')
            ->first();
    }

    // Thêm phương thức tính giá khuyến mãi
    public function giaKhuyenMai()
    {
        $khuyenmai = $this->khuyenMaiHienTai();
        if ($khuyenmai) {
            return $this->dongia * (1 - $khuyenmai->phantram / 100);
        }
        return $this->dongia;
    }

    public function danhGia()
    {
        return $this->hasMany(DanhGiaSanPham::class, 'sanpham_id', 'id');
    }

    // Phương thức lấy đánh giá trung bình
    public function diemDanhGiaTrungBinh()
    {
        return $this->danhGia()
            ->where('kiemduyet', 1)
            ->where('kichhoat', 1)
            ->avg('sosao') ?? 0;
    }

    // Phương thức lấy số lượng đánh giá
    public function soLuongDanhGia()
    {
        return $this->danhGia()
            ->where('kiemduyet', 1)
            ->where('kichhoat', 1)
            ->count();
    }

    // Phương thức lấy thống kê theo số sao
    public function thongKeDanhGia()
    {
        $thongKe = [];
        for ($i = 1; $i <= 5; $i++) {
            $thongKe[$i] = $this->danhGia()
                ->where('sosao', $i)
                ->where('kiemduyet', 1)
                ->where('kichhoat', 1)
                ->count();
        }
        return $thongKe;
    }
}
