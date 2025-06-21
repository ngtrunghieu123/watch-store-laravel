<?php

namespace App\Imports;

use App\Models\KhuyenMai;
use App\Models\SanPham;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class KhuyenMaiImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Bỏ qua hàng nếu không có tên khuyến mãi
        if (empty($row['ten_khuyen_mai'])) {
            return null;
        }

        // Tạo slug nếu không có
        $slug = isset($row['slug']) && !empty($row['slug'])
            ? $row['slug']
            : Str::slug($row['ten_khuyen_mai']);

        // Tạo khuyến mãi mới
        $khuyenMai = new KhuyenMai([
            'tenkhuyenmai' => $row['ten_khuyen_mai'],
            'slug' => $slug,
            'mota' => $row['mo_ta'] ?? '',
            'ngaybatdau' => $row['ngay_bat_dau'],
            'ngayketthuc' => $row['ngay_ket_thuc'],
            'phantram' => $row['phan_tram_giam_gia'],
            'trangthai' => isset($row['trang_thai']) ? (int)$row['trang_thai'] : 1,
        ]);

        $khuyenMai->save();

        // Xử lý danh sách sản phẩm áp dụng
        if (!empty($row['san_pham_ap_dung'])) {
            $danhSachTenSanPham = explode(',', $row['san_pham_ap_dung']);

            foreach ($danhSachTenSanPham as $tenSanPham) {
                // Tìm sản phẩm theo tên
                $sanPham = SanPham::where('tensanpham', trim($tenSanPham))->first();

                // Nếu tìm thấy, thêm vào khuyến mãi
                if ($sanPham) {
                    $khuyenMai->sanPham()->attach($sanPham->id);
                }
            }
        }

        return $khuyenMai;
    }
}
