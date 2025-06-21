<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KhuyenMai extends Model
{
    use HasFactory;

    protected $table = 'khuyenmai';

    protected $fillable = [
        'tenkhuyenmai',
        'slug',
        'mota',
        'ngaybatdau',
        'ngayketthuc',
        'phantram',
        'trangthai'
    ];

    public function sanPham()
    {
        return $this->belongsToMany(SanPham::class, 'sanpham_khuyenmai', 'khuyenmai_id', 'sanpham_id');
    }
}
