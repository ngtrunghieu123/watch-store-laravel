<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BinhLuanBaiViet extends Model
{
    //
    protected $table = 'binhluanbaiviet';

    public function BaiViet(): BelongsTo
    {
        return $this->belongsTo(BaiViet::class, 'baiviet_id', 'id');
    }

    public function NguoiDung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'nguoidung_id', 'id');
    }
}
