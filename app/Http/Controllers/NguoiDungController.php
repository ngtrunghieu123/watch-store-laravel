<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NguoiDungController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getDanhSach()
    {
        $nguoidung = NguoiDung::all();
        return view('admin.nguoidung.danhsach', compact('nguoidung'));
    }

    public function getThem()
    {
        return view('admin.nguoidung.them');
    }

    public function postThem(Request $request)
    {
        // Kiểm tra
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:nguoidung'],
            'role' => ['required'],
            'password' => ['required', 'min:4', 'confirmed'],
        ]);
        $orm = new NguoiDung();
        $orm->name = $request->name;
        $orm->username = Str::before($request->email, '@');
        $orm->email = $request->email;
        $orm->password = Hash::make($request->password);
        $orm->role = $request->role;
        $orm->save();
        return redirect()->route('admin.nguoidung');
        // Sau khi thêm thành công thì tự động chuyển về trang danh sách

    }

    public function getSua($id)
    {

        $nguoidung = NguoiDung::find($id); // find rỗng là null findOrFail là lỗi 404
        return view('admin.nguoidung.sua', compact('nguoidung'));
    }
    public function postSua(Request $request, $id)
    {
        //check
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:nguoidung,email,' . $id],
            'role' => ['required'],
            'password' => ['confirmed'],
        ]);
        $orm = NguoiDung::find($id);
        $orm->name = $request->name;
        $orm->username = Str::before($request->email, '@');
        $orm->email = $request->email;
        $orm->role = $request->role;
        if (!empty($request->password)) $orm->password = Hash::make($request->password);
        $orm->save();
        return redirect()->route('admin.nguoidung');
    }

    public function getXoa($id)
    {

        $orm = NguoiDung::find($id);
        $orm->delete();

        return redirect()->route('admin.nguoidung');
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
    public function show(NguoiDung $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NguoiDung $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NguoiDung $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NguoiDung $user)
    {
        //
    }
}
