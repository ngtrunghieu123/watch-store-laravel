<?php

namespace App\Http\Controllers;

use App\Models\SanPham;
use App\Models\LoaiSanPham;
use App\Models\HangSanXuat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Imports\SanPhamImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SanPhamExport;




class SanPhamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getDanhSach()
    {
        $sanpham = SanPham::paginate(10);
        return view('admin.sanpham.danhsach', compact('sanpham'));
    }
    public function getThem()
    {
        $loaisanphamlist = LoaiSanPham::all();
        $hangsanxuatlist = HangSanXuat::all();

        return view('admin.sanpham.them', compact('loaisanphamlist', 'hangsanxuatlist'));
        //return view('admin.sanpham.them', ['loaiSanPhamList' => $loaiSanPhamList, 'hangSanXuatList' => $hangSanXuatList]);
    }
    public function postThem(Request $request)
    {
        // Kiểm tra
        $request->validate([
            'loaisanpham_id' => ['required'],
            'hangsanxuat_id' => ['required'],
            'tensanpham' => ['required', 'string', 'max:255', 'unique:sanpham'],
            'soluong' => ['required', 'numeric'],
            'dongia' => ['required', 'numeric'],
            'hinhanh' => ['nullable', 'image', 'max:2048'],
        ]);

        // Upload hình ảnh
        $path = null;
        if ($request->hasFile('hinhanh')) {
            // Tạo thư mục nếu chưa có
            $lsp = LoaiSanPham::find($request->loaisanpham_id);
            Storage::exists($lsp->tenloai_slug) or Storage::makeDirectory($lsp->tenloai_slug, 0775);

            // Xác định tên tập tin
            $extension = $request->file('hinhanh')->extension();// ,jpg, png, gif
            $filename = Str::slug($request->tensanpham, '-') . '.' . $extension;

            // Upload vào thư mục và trả về đường dẫn
            $path = Storage::putFileAs($lsp->tenloai_slug, $request->file('hinhanh'), $filename);
        }

        $orm = new SanPham();
        $orm->loaisanpham_id = $request->loaisanpham_id;
        $orm->hangsanxuat_id = $request->hangsanxuat_id;
        $orm->tensanpham = $request->tensanpham;
        $orm->tensanpham_slug = Str::slug($request->tensanpham, '-');
        $orm->soluong = $request->soluong;
        $orm->dongia = $request->dongia;
        $orm->hinhanh = $path ?? null;
        $orm->motasanpham = $request->motasanpham;
        $orm->save();

        // Sau khi thêm thành công thì tự động chuyển về trang danh sách
        return redirect()->route('admin.sanpham');
    }

    public function getSua($id)
    {
        $sanpham = SanPham::find($id);

        $loaisanphamlist = LoaiSanPham::all();
        $hangsanxuatlist = HangSanXuat::all();

        return view('admin.sanpham.sua', compact('sanpham', 'loaisanphamlist', 'hangsanxuatlist'));
    }
    public function postSua(Request $request, $id)
    {
        // Kiểm tra
        $request->validate([
            'loaisanpham_id' => ['required'],
            'hangsanxuat_id' => ['required'],
            'tensanpham' => ['required', 'string', 'max:255', 'unique:sanpham,tensanpham,' . $id],
            'soluong' => ['required', 'numeric'],
            'dongia' => ['required', 'numeric'],
            'hinhanh' => ['nullable', 'image', 'max:2048'],
        ]);

        // Upload hình ảnh
        $path = null;
        if ($request->hasFile('hinhanh')) {
            // Xóa tập tin cũ
            $sp = SanPham::find($id);
            if (!empty($sp->hinhanh)) Storage::delete($sp->hinhanh);

            // Xác định tên tập tin mới
            $extension = $request->file('hinhanh')->extension();
            $filename = Str::slug($request->tensanpham, '-') . '.' . $extension;

            // Upload vào thư mục và trả về đường dẫn
            $lsp = LoaiSanPham::find($request->loaisanpham_id);
            $path = Storage::putFileAs($lsp->tenloai_slug, $request->file('hinhanh'), $filename);
        }

        $orm = SanPham::find($id);
        $orm->loaisanpham_id = $request->loaisanpham_id;
        $orm->hangsanxuat_id = $request->hangsanxuat_id;
        $orm->tensanpham = $request->tensanpham;
        $orm->tensanpham_slug = Str::slug($request->tensanpham, '-');
        $orm->soluong = $request->soluong;
        $orm->dongia = $request->dongia;
        $orm->hinhanh = $path ?? $orm->hinhanh ?? null;
        $orm->motasanpham = $request->motasanpham;
        $orm->save();

        // Sau khi sửa thành công thì tự động chuyển về trang danh sách
        return redirect()->route('admin.sanpham');
    }
    public function getXoa($id)
    {
        $orm = SanPham::find($id);
        $orm->delete();
        if (!empty($orm->hinhanh)) Storage::delete($orm->hinhanh);
        return redirect()->route('admin.sanpham');
    }
    public function postNhap(Request $request)
    {
        Excel::import(new SanPhamImport, $request->file('file_excel'));
        return redirect()->route('admin.sanpham');
    }
    // Xuất ra Excel
    public function getXuat()
    {
        return Excel::download(new SanPhamExport, 'danh-sach-san-pham.xlsx');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SanPham $sanPham)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SanPham $sanPham)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SanPham $sanPham)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SanPham $sanPham)
    {
        //
    }
}
