<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class YeuThich extends Model
{
    protected $table = 'yeuthich';

    protected $fillable = [
        'nguoidung_id',
        'sanpham_id'
    ];

    public function nguoidung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoidung_id');
    }

    public function sanpham()
    {
        return $this->belongsTo(SanPham::class, 'sanpham_id');
    }
}
