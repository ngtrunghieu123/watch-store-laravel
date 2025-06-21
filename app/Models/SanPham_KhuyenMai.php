<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SanPham_KhuyenMai extends Model
{
    use HasFactory;

    protected $table = 'sanpham_khuyenmai';

    protected $fillable = [
        'sanpham_id',
        'khuyenmai_id'
    ];
}
