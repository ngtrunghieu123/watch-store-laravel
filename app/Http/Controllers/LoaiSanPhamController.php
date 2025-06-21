<?php

namespace App\Http\Controllers;

use App\Models\LoaiSanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoaiSanPhamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getDanhSach()
    {
        $loaisanpham = LoaiSanPham::all();
        return view('admin.loaisanpham.danhsach', compact('loaisanpham'));
    }

    public function getThem()
    {
        return view('admin.loaisanpham.them');
    }

    public function postThem(Request $request)
    {
        // Kiểm tra
        $request->validate([
            'tenloai' => ['required', 'string', 'max:255', 'unique:loaisanpham']
        ]);
        $orm = new LoaiSanPham();
        $orm->tenloai = $request->tenloai;
        $orm->tenloai_slug = Str::slug($request->tenloai, '-');
        $orm->save();
        return redirect()->route('admin.loaisanpham');
    }

    public function getSua($id)
    {

        $loaisanpham = LoaiSanPham::find($id); // find rỗng là null findOrFail là lỗi 404
        return view('admin.loaisanpham.sua', compact('loaisanpham'));
    }
    public function postSua(Request $request, $id)
    {
        //check
        $request->validate([
            'tenloai' => ['required', 'string', 'max:255', 'unique:loaisanpham,tenloai,' . $id],
        ]);

        $orm = LoaiSanPham::find($id);
        $orm->tenloai = $request->tenloai;
        $orm->tenloai_slug = Str::slug($request->tenloai, '-');
        $orm->save();
        return redirect()->route('admin.loaisanpham');
    }

    public function getXoa($id)
    {
        $orm = LoaiSanPham::find($id);
        $orm->delete();

        return redirect()->route('admin.loaisanpham');
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
    public function show(LoaiSanPham $loaiSanPham)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LoaiSanPham $loaiSanPham)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LoaiSanPham $loaiSanPham)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LoaiSanPham $loaiSanPham)
    {
        //
    }
}
