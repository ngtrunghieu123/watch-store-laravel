<?php

namespace App\Http\Controllers;

use App\Models\KhuyenMai;
use Illuminate\Http\Request;
use App\Models\SanPham;
use Illuminate\Support\Str;
use App\Imports\KhuyenMaiImport;
use App\Exports\KhuyenMaiExport;
use Maatwebsite\Excel\Facades\Excel;

class KhuyenMaiController extends Controller
{
    public function getDanhSach()
    {
        $khuyenmai = KhuyenMai::all();
        return view('admin.khuyenmai.danhsach', compact('khuyenmai'));
    }
    public function postNhap(Request $request)
    {
        // Kiểm tra file
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls|max:2048',
        ], [
            'file_excel.required' => 'Vui lòng chọn tập tin Excel để nhập',
            'file_excel.mimes' => 'Chỉ chấp nhận tập tin Excel (xlsx, xls)',
            'file_excel.max' => 'Kích thước tập tin không được vượt quá 2MB',
        ]);

        try {
            // Sử dụng transaction để đảm bảo tính toàn vẹn dữ liệu
            \DB::beginTransaction();

            Excel::import(new KhuyenMaiImport, $request->file('file_excel'));

            \DB::commit();
            return back()->with('success', 'Nhập dữ liệu khuyến mãi từ Excel thành công');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function getXuat()
    {
        return Excel::download(new KhuyenMaiExport, 'danh-sach-khuyen-mai.xlsx');
    }

    public function getThem()
    {
        // Lấy ngày hiện tại
        $today = date('Y-m-d');

        // Lấy danh sách các sản phẩm chưa có trong khuyến mãi nào đang có hiệu lực
        $sanpham = SanPham::whereDoesntHave('khuyenMai', function ($query) use ($today) {
            $query->where('trangthai', 1)
                ->where('ngaybatdau', '<=', $today)
                ->where('ngayketthuc', '>=', $today);
        })->get();

        return view('admin.khuyenmai.them', compact('sanpham'));
    }

    public function postThem(Request $request)
    {
        try {
            // Log dữ liệu nhận được
            \Log::info('Dữ liệu form:', $request->all());

            $validated = $request->validate([
                'tenkhuyenmai' => ['required', 'string', 'max:255', 'unique:khuyenmai'],
                'mota' => ['nullable', 'string'],
                'ngaybatdau' => ['required', 'date'],
                'ngayketthuc' => ['required', 'date', 'after_or_equal:ngaybatdau'],
                'phantram' => ['required', 'integer', 'min:1', 'max:100'],
                'trangthai' => ['nullable', 'boolean'], // để là nullable
                'sanpham_id' => ['required', 'array']
            ]);

            // Log dữ liệu đã validate
            \Log::info('Dữ liệu đã validate:', $validated);

            $orm = new KhuyenMai();
            $orm->tenkhuyenmai = $request->tenkhuyenmai;
            $orm->slug = Str::slug($request->tenkhuyenmai);
            $orm->mota = $request->mota;
            $orm->ngaybatdau = $request->ngaybatdau;
            $orm->ngayketthuc = $request->ngayketthuc;
            $orm->phantram = $request->phantram;
            $orm->trangthai = $request->has('trangthai') ? 1 : 0;
            $orm->save();

            // Log ID sau khi save
            \Log::info('ID khuyến mãi sau khi save:', ['id' => $orm->id]);

            $orm->sanPham()->attach($request->sanpham_id);

            return redirect()->route('admin.khuyenmai')->with('success', 'Thêm khuyến mãi thành công!');
        } catch (\Exception $e) {
            // Log lỗi nếu có
            \Log::error('Lỗi khi thêm khuyến mãi: ' . $e->getMessage());

            // Hiển thị lỗi cho người dùng
            return back()->withInput()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    public function getSua($id)
    {
        $khuyenmai = KhuyenMai::find($id);
        $today = date('Y-m-d');

        // Lấy các sản phẩm chưa có trong khuyến mãi nào đang có hiệu lực
        // hoặc các sản phẩm đang thuộc khuyến mãi này
        $sanpham = SanPham::where(function ($query) use ($today, $id) {
            $query->whereDoesntHave('khuyenMai', function ($q) use ($today) {
                $q->where('trangthai', 1)
                    ->where('ngaybatdau', '<=', $today)
                    ->where('ngayketthuc', '>=', $today);
            })->orWhereHas('khuyenMai', function ($q) use ($id) {
                $q->where('khuyenmai.id', $id);
            });
        })->get();

        $sanpham_khuyenmai = $khuyenmai->sanPham->pluck('id')->toArray();

        return view('admin.khuyenmai.sua', compact('khuyenmai', 'sanpham', 'sanpham_khuyenmai'));
    }


    public function postSua(Request $request, $id)
    {
        try {
            $request->validate([
                'tenkhuyenmai' => ['required', 'string', 'max:255', 'unique:khuyenmai,tenkhuyenmai,' . $id],
                'mota' => ['nullable', 'string'],
                'ngaybatdau' => ['required', 'date'],
                'ngayketthuc' => ['required', 'date', 'after_or_equal:ngaybatdau'],
                'phantram' => ['required', 'integer', 'min:1', 'max:100'],
                'trangthai' => ['nullable', 'boolean'],
                'sanpham_id' => ['required', 'array'] // lấy mã giảm giá cho nhiều sản phẩm
            ]);

            $orm = KhuyenMai::find($id);
            if (!$orm) {
                return back()->withErrors(['error' => 'Không tìm thấy khuyến mãi']);
            }

            $orm->tenkhuyenmai = $request->tenkhuyenmai;
            $orm->slug = Str::slug($request->tenkhuyenmai);
            $orm->mota = $request->mota;
            $orm->ngaybatdau = $request->ngaybatdau;
            $orm->ngayketthuc = $request->ngayketthuc;
            $orm->phantram = $request->phantram;
            $orm->trangthai = $request->has('trangthai') ? 1 : 0;
            $orm->save();

            $orm->sanPham()->sync($request->sanpham_id);

            return redirect()->route('admin.khuyenmai')->with('success', 'Cập nhật khuyến mãi thành công!');
        } catch (\Exception $e) {
            \Log::error('Lỗi khi cập nhật khuyến mãi: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
        }
    }

    public function getXoa($id)
    {
        $orm = KhuyenMai::find($id);
        $orm->sanPham()->detach();
        $orm->delete();

        return redirect()->route('admin.khuyenmai')->with('success', 'Xóa khuyến mãi thành công!');
    }
}
