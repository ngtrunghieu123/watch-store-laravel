<?php

namespace App\Http\Controllers;

use App\Models\HangSanXuat;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Imports\HangSanXuatImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\HangSanXuatExport;

class HangSanXuatController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function getDanhSach()
    {
        $hangsanxuat = HangSanXuat::all();
        return view('admin.hangsanxuat.danhsach', compact('hangsanxuat'));
    }

    public function getThem()
    {
        return view('admin.hangsanxuat.them');
    }
    public function postThem(Request $request)
    {
        $request->validate([
            'tenhang' => ['required', 'string', 'max:255', 'unique:hangsanxuat'],
            'hinhanh' => ['nullable', 'image', 'max:2048'],
        ]);

        $path = null;
        if ($request->hasFile('hinhanh')) {

            $filename = Str::slug($request->tenhang, '-') . '.' . $request->file('hinhanh')->extension();
            $path = Storage::putFileAs('hang-san-xuat', $request->file('hinhanh'), $filename);
        }
        $orm = new HangSanXuat();
        $orm->tenhang = $request->tenhang;
        $orm->tenhang_slug = Str::slug($request->tenhang, '-');
        $orm->hinhanh = $path ?? null;
        $orm->save();

        return redirect()->route('admin.hangsanxuat');
    }


    public function getSua($id)
    {
        $hangsanxuat = HangSanXuat::find($id);
        return view('admin.hangsanxuat.sua', compact('hangsanxuat'));
    }
    public function postSua(Request $request, $id)
    {
        $request->validate([
            'tenhang' => ['required', 'string', 'max:255', 'unique:hangsanxuat,tenhang,' . $id],
            'hinhanh' => ['nullable', 'image', 'max:2048'],
        ]);
        $path = null;
        if ($request->hasFile('hinhanh')) {

            $orm = HangSanXuat::find($id);
            if (!empty($orm->hinhanh)) Storage::delete($orm->hinhanh);


            $filename = Str::slug($request->tenhang, '-') . '.' . $request->file('hinhanh')->extension();;
            $path = Storage::putFileAs('hang-san-xuat', $request->file('hinhanh'), $filename);
        }
        $orm = HangSanXuat::find($id);
        $orm->tenhang = $request->tenhang;
        $orm->tenhang_slug = Str::slug($request->tenhang, '-');
        $orm->hinhanh = $path ?? $orm->hinhanh ?? null;
        $orm->save();

        return redirect()->route('admin.hangsanxuat');
    }

    public function getXoa($id)
    {
        $orm = HangSanXuat::find($id);
        $orm->delete();
        if (!empty($orm->hinhanh)) Storage::delete($orm->hinhanh);

        return redirect()->route('admin.hangsanxuat');
    }
    public function postNhap(Request $request)
    {
        Excel::import(new HangSanXuatImport, $request->file('file_excel'));
        return redirect()->route('admin.hangsanxuat');
    }
    // Xuất ra Excel
    public function getXuat()
    {
        return Excel::download(new HangSanXuatExport, 'danh-sach-hang-san-xuat.xlsx');
    }
    public function index()
    {
        //
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
    public function show(HangSanXuat $hangSanXuat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HangSanXuat $hangSanXuat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HangSanXuat $hangSanXuat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HangSanXuat $hangSanXuat)
    {
        //
    }
}
