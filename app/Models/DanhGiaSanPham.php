<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhGiaSanPham extends Model
{
    use HasFactory;

    protected $table = 'danhgiasanpham';

    protected $fillable = [
        'sanpham_id',
        'nguoidung_id',
        'sosao',
        'binhluan',
        'hinhanh',
        'kiemduyet',
        'kichhoat'
    ];

    // Quan hệ với sản phẩm
    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'sanpham_id', 'id');
    }

    // Quan hệ với người dùng
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoidung_id', 'id');
    }

    // Lọc đánh giá đã được kiểm duyệt
    public function scopeDaKiemDuyet($query)
    {
        return $query->where('kiemduyet', 1)->where('kichhoat', 1);
    }
}
