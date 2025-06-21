<?php

namespace App\Imports;

use App\Models\SanPham;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SanPhamImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new SanPham([
            'loaisanpham_id' => $row['loaisanpham_id'],
            'hangsanxuat_id' => $row['hangsanxuat_id'],
            'tensanpham' => $row['tensanpham'],
            'tensanpham_slug' => $row['tensanpham_slug'],
            'soluong' => $row['soluong'],
            'dongia' => $row['dongia'],
            'hinhanh' => $row['hinhanh'],
        ]);
    }
}
