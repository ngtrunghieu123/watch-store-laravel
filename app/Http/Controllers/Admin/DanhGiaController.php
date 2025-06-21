<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DanhGiaSanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DanhGiaController extends Controller
{
    public function getDanhSach()
    {
        $danhgia = DanhGiaSanPham::with(['sanPham', 'nguoiDung'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.danhgiasanpham.danhsach', compact('danhgia'));
    }

    public function getDuyet($id)
    {
        $danhgia = DanhGiaSanPham::findOrFail($id);
        $danhgia->kiemduyet = 1;
        $danhgia->save();

        return redirect()->route('admin.danhgiasanpham')->with('success', 'Đã duyệt đánh giá thành công');
    }

    public function postDuyetAll(Request $request)
    {
        $ids = $request->ids;

        if (!$ids) {
            return redirect()->route('admin.danhgiasanpham')->with('error', 'Vui lòng chọn ít nhất một đánh giá');
        }

        DanhGiaSanPham::whereIn('id', $ids)
            ->update(['kiemduyet' => 1]);

        return redirect()->route('admin.danhgiasanpham')->with('success', 'Đã duyệt các đánh giá đã chọn');
    }

    public function postHuyDuyetAll(Request $request)
    {
        $ids = $request->ids;

        if (!$ids) {
            return redirect()->route('admin.danhgiasanpham')->with('error', 'Vui lòng chọn ít nhất một đánh giá');
        }

        DanhGiaSanPham::whereIn('id', $ids)
            ->update(['kiemduyet' => 0]);

        return redirect()->route('admin.danhgiasanpham')->with('success', 'Đã hủy duyệt các đánh giá đã chọn');
    }

    public function getXoa($id)
    {
        $danhgia = DanhGiaSanPham::findOrFail($id);

        // Xóa hình ảnh nếu có
        if ($danhgia->hinhanh && file_exists(storage_path('app/public/' . $danhgia->hinhanh))) {
            unlink(storage_path('app/public/' . $danhgia->hinhanh));
        }

        $danhgia->delete();

        return redirect()->route('admin.danhgiasanpham')->with('success', 'Đã xóa đánh giá thành công');
    }
}
