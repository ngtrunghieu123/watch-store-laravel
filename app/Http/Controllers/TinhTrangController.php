<?php

namespace App\Http\Controllers;

use App\Models\TinhTrang;
use Illuminate\Http\Request;
use Str;

class TinhTrangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getDanhSach()
    {
        $tinhtrang = TinhTrang::all();
        return view('admin.tinhtrang.danhsach', compact('tinhtrang'));
    }

    public function getThem()
    {
        return view('admin.tinhtrang.them');
    }
    public function postThem(Request $request)
    {
        $request->validate([
            'tinhtrang' => ['required', 'string', 'max:255', 'unique:tinhtrang']
        ]);
        $orm = new TinhTrang();
        $orm->tinhtrang = $request->tinhtrang;
        $orm->save();
        return redirect()->route('admin.tinhtrang');
    }
    public function getSua($id)
    {
        $tinhtrang = TinhTrang::find($id);
        return view('admin.tinhtrang.sua', compact('tinhtrang'));
    }
    public function postSua(Request $request, $id)
    {
        $request->validate([
            'tinhtrang' => ['required', 'string', 'max:255', 'unique:tinhtrang,tinhtrang,' . $id]
        ]);
        $orm = TinhTrang::find($id);
        $orm->tinhtrang = $request->tinhtrang;
        $orm->save();
        return redirect()->route('admin.tinhtrang');
    }
    public function getXoa($id)
    {
        $orm = TinhTrang::find($id);
        $orm->delete();
        return redirect()->route('admin.tinhtrang');
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
    public function show(TinhTrang $tinhTrang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TinhTrang $tinhTrang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TinhTrang $tinhTrang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TinhTrang $tinhTrang)
    {
        //
    }
}
