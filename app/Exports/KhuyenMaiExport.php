<?php

namespace App\Exports;

use App\Models\KhuyenMai;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithMapping;

class KhuyenMaiExport implements FromCollection, WithHeadings, WithCustomStartCell, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function headings(): array
    {
        return [
            'ID',
            'Tên khuyến mãi',
            'Slug',
            'Mô tả',
            'Ngày bắt đầu',
            'Ngày kết thúc',
            'Phần trăm giảm giá',
            'Trạng thái',
            'Sản phẩm áp dụng',
        ];
    }

    public function map($khuyenMai): array
    {
        // Lấy tên các sản phẩm áp dụng khuyến mãi, cách nhau bởi dấu phẩy
        $danhSachSanPham = $khuyenMai->sanPham->pluck('tensanpham')->implode(', ');

        return [
            $khuyenMai->id,
            $khuyenMai->tenkhuyenmai,
            $khuyenMai->slug,
            $khuyenMai->mota,
            $khuyenMai->ngaybatdau,
            $khuyenMai->ngayketthuc,
            $khuyenMai->phantram,
            $khuyenMai->trangthai ? 'Kích hoạt' : 'Vô hiệu',
            $danhSachSanPham,
        ];
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function collection()
    {
        return KhuyenMai::all();
    }
}
