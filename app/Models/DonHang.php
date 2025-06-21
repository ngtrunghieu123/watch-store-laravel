<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class DonHang extends Model
{
    protected $table = 'donhang';

    public function NguoiDung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoidung_id', 'id');
    }

    public function TinhTrang(): BelongsTo
    {
        return $this->belongsTo(TinhTrang::class, 'tinhtrang_id', 'id');
    }

    public function DonHang_ChiTiet()
    {
        return $this->hasMany(DonHang_ChiTiet::class, 'donhang_id', 'id');
    }

    protected $appends = ['tongtien'];

    public function getTongtienAttribute()
    {
        if ($this->DonHang_ChiTiet === null) {
            return 0;
        }

        return $this->DonHang_ChiTiet->sum(function ($ct) {
            return $ct->dongiaban * $ct->soluongban * 1.1; // thuế 10%
        });
    }
}
