<?php

namespace App\Http\Controllers;

use App\Models\DanhGiaSanPham;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DanhGiaSanPhamController extends Controller
{
    public function postThem(Request $request)
    {
        $request->validate([
            'sanpham_id' => 'required|exists:sanpham,id',
            'sosao' => 'required|integer|min:1|max:5',
            'binhluan' => 'required|string|max:500',
            'hinhanh' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Kiểm tra xem người dùng đã đánh giá sản phẩm này chưa
        $danhGiaCu = DanhGiaSanPham::where('sanpham_id', $request->sanpham_id)
            ->where('nguoidung_id', Auth::id())
            ->first();

        if ($danhGiaCu) {
            // Cập nhật đánh giá cũ
            $danhGiaCu->sosao = $request->sosao;
            $danhGiaCu->binhluan = $request->binhluan;
            $danhGiaCu->kiemduyet = 0; // Đánh giá cập nhật cần được duyệt lại

            // Nếu có hình ảnh mới thì xóa hình cũ và lưu hình mới
            if ($request->hasFile('hinhanh')) {
                // Xóa hình cũ nếu có
                if (!empty($danhGiaCu->hinhanh) && Storage::exists('public/' . $danhGiaCu->hinhanh)) {
                    Storage::delete('public/' . $danhGiaCu->hinhanh);
                }

                // Đảm bảo thư mục tồn tại
                $storagePath = storage_path('app/public/danhgia');
                if (!file_exists($storagePath)) {
                    mkdir($storagePath, 0755, true);
                }

                // Tạo tên file duy nhất
                $fileName = time() . '_' . $request->file('hinhanh')->getClientOriginalName();

                // Lưu hình mới
                $request->file('hinhanh')->storeAs('danhgia', $fileName, 'public');
                $danhGiaCu->hinhanh = 'danhgia/' . $fileName;
            }

            $danhGiaCu->save();

            return back()->with('success', 'Cập nhật đánh giá thành công! Đánh giá của bạn sẽ được hiển thị sau khi được duyệt.');
        } else {
            // Tạo đánh giá mới
            $danhGia = new DanhGiaSanPham();
            $danhGia->sanpham_id = $request->sanpham_id;
            $danhGia->nguoidung_id = Auth::id();
            $danhGia->sosao = $request->sosao;
            $danhGia->binhluan = $request->binhluan;
            $danhGia->kiemduyet = 0; // Chưa kiểm duyệt

            // Lưu hình ảnh nếu có
            if ($request->hasFile('hinhanh')) {
                // Đảm bảo thư mục tồn tại
                $storagePath = storage_path('app/public/danhgia');
                if (!file_exists($storagePath)) {
                    mkdir($storagePath, 0755, true);
                }

                // Tạo tên file duy nhất
                $fileName = time() . '_' . $request->file('hinhanh')->getClientOriginalName();

                // Lưu hình
                $request->file('hinhanh')->storeAs('danhgia', $fileName, 'public');
                $danhGia->hinhanh = 'danhgia/' . $fileName;
            }

            $danhGia->save();

            return back()->with('success', 'Gửi đánh giá thành công! Đánh giá của bạn sẽ được hiển thị sau khi được duyệt.');
        }
    }

    public function postSua(Request $request, $id)
    {
        $request->validate([
            'sosao' => 'required|integer|min:1|max:5',
            'binhluan' => 'required|string|max:500',
            'hinhanh' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Tìm đánh giá và kiểm tra quyền sở hữu
        $danhGia = DanhGiaSanPham::where('id', $id)
            ->where('nguoidung_id', Auth::id())
            ->firstOrFail();

        // Cập nhật thông tin
        $danhGia->sosao = $request->sosao;
        $danhGia->binhluan = $request->binhluan;
        $danhGia->kiemduyet = 0; // Đánh giá sửa cần được duyệt lại

        // Nếu có hình ảnh mới
        if ($request->hasFile('hinhanh')) {
            // Xóa hình cũ nếu có
            if (!empty($danhGia->hinhanh) && Storage::exists('public/' . $danhGia->hinhanh)) {
                Storage::delete('public/' . $danhGia->hinhanh);
            }

            // Đảm bảo thư mục tồn tại
            $storagePath = storage_path('app/public/danhgia');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            // Tạo tên file duy nhất
            $fileName = time() . '_' . $request->file('hinhanh')->getClientOriginalName();

            // Lưu hình mới
            $request->file('hinhanh')->storeAs('danhgia', $fileName, 'public');
            $danhGia->hinhanh = 'danhgia/' . $fileName;
        }

        $danhGia->save();

        return redirect()->route('user.danhgia')
            ->with('success', 'Cập nhật đánh giá thành công! Đánh giá của bạn sẽ được hiển thị sau khi được duyệt.');
    }

    public function getXoa($id)
    {
        $danhGia = DanhGiaSanPham::where('id', $id)
            ->where('nguoidung_id', Auth::id())
            ->firstOrFail();

        // Xóa hình ảnh nếu có
        if (!empty($danhGia->hinhanh) && Storage::exists('public/' . $danhGia->hinhanh)) {
            Storage::delete('public/' . $danhGia->hinhanh);
        }

        $danhGia->delete();

        return redirect()->route('user.danhgia')
            ->with('success', 'Đã xóa đánh giá thành công!');
    }
}
