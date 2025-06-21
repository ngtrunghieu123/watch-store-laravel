<?php

namespace App\Http\Controllers;

use App\Models\DonHang;
use App\Models\DonHang_ChiTiet;
use App\Models\TinhTrang;
use Illuminate\Http\Request;
use App\Models\SanPham;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class DonHangController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function getDanhSach()
    {
        $donhang = DonHang::with(['nguoidung', 'tinhtrang'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.donhang.danhsach', compact('donhang'));
    }
    public function getThongKe(Request $request)
    {
        // Lấy tháng và năm hiện tại hoặc từ request
        $thang = $request->input('thang', date('m'));
        $nam = $request->input('nam', date('Y'));

        // Thống kê tổng số đơn hàng trong tháng
        $tongDonHang = DonHang::whereMonth('created_at', $thang)
            ->whereYear('created_at', $nam)
            ->count();

        // Thống kê tổng doanh thu trong tháng // nhớ sửa lại cột cho id tình trạng 3
        $tongDoanhThu = DonHang::with('DonHang_ChiTiet')
            ->whereMonth('created_at', $thang)
            ->whereYear('created_at', $nam)
            ->where('tinhtrang_id', '!=', 4)
            ->get()
            ->sum('tongtien');

        // Đếm số đơn theo trạng thái
        $donMoi = DonHang::whereMonth('created_at', $thang)
            ->whereYear('created_at', $nam)
            ->where('tinhtrang_id', 1)
            ->count();

        $donDangXuLy = DonHang::whereMonth('created_at', $thang)
            ->whereYear('created_at', $nam)
            ->where('tinhtrang_id', 2)
            ->count();

        $donDaGiao = DonHang::whereMonth('created_at', $thang)
            ->whereYear('created_at', $nam)
            ->where('tinhtrang_id', 3)
            ->count();

        $donHuy = DonHang::whereMonth('created_at', $thang)
            ->whereYear('created_at', $nam)
            ->where('tinhtrang_id', 4)
            ->count();

        // Tính tổng số lượng sản phẩm đã bán
        $sanPhamDaBan = DonHang_ChiTiet::whereHas('donhang', function ($query) use ($thang, $nam) {
            $query->whereMonth('created_at', $thang)
                ->whereYear('created_at', $nam)
                ->where('tinhtrang_id', '!=', 4);
        })
            ->sum('soluongban');

        // Dữ liệu cho biểu đồ doanh thu theo ngày
        $doanhThuTheoNgay = [];
        $soNgayTrongThang = cal_days_in_month(CAL_GREGORIAN, $thang, $nam);

        for ($i = 1; $i <= $soNgayTrongThang; $i++) {
            $ngay = sprintf('%d-%02d-%02d', $nam, $thang, $i);

            $doanhThu = DonHang::with('DonHang_ChiTiet')
                ->whereDate('created_at', $ngay)
                ->where('tinhtrang_id', '!=', 4)
                ->get()
                ->sum('tongtien');

            $doanhThuTheoNgay[] = [
                'ngay' => $i,
                'doanhthu' => $doanhThu
            ];
        }

        // Dữ liệu cho biểu đồ tròn trạng thái đơn hàng
        $trangThaiDonHang = [
            ['trangthai' => 'Đơn hàng mới', 'soluong' => $donMoi, 'color' => '#0dcaf0'],
            ['trangthai' => 'Đang xử lý', 'soluong' => $donDangXuLy, 'color' => '#ffc107'],
            ['trangthai' => 'Đã giao hàng', 'soluong' => $donDaGiao, 'color' => '#198754'],
            ['trangthai' => 'Đã hủy', 'soluong' => $donHuy, 'color' => '#dc3545']
        ];

        // Lấy top 5 sản phẩm bán chạy
        $topSanPham = DonHang_ChiTiet::select(
            'donhang_chitiet.sanpham_id',
            DB::raw('SUM(donhang_chitiet.soluongban) as tong_ban'),
            'sanpham.tensanpham'
        )
            ->join('donhang', 'donhang.id', '=', 'donhang_chitiet.donhang_id')
            ->join('sanpham', 'sanpham.id', '=', 'donhang_chitiet.sanpham_id')
            ->whereMonth('donhang.created_at', $thang)
            ->whereYear('donhang.created_at', $nam)
            ->where('donhang.tinhtrang_id', '!=', 4)
            ->groupBy('donhang_chitiet.sanpham_id', 'sanpham.tensanpham')
            ->orderBy('tong_ban', 'desc')
            ->limit(5)
            ->get();

        return view('admin.donhang.thongke', compact(
            'thang',
            'nam',
            'tongDonHang',
            'tongDoanhThu',
            'donMoi',
            'donDangXuLy',
            'donDaGiao',
            'donHuy',
            'sanPhamDaBan',
            'doanhThuTheoNgay',
            'trangThaiDonHang',
            'topSanPham'
        ));
    }

    public function getTimKiem(Request $request)
    {
        $query = DonHang::with(['nguoidung', 'tinhtrang']);

        // Tìm theo tên khách hàng
        if ($request->has('tenkhachhang') && $request->tenkhachhang != '') {
            $query->whereHas('nguoidung', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->tenkhachhang . '%');
            });
        }

        // Tìm theo số điện thoại
        if ($request->has('dienthoai') && $request->dienthoai != '') {
            $query->where('dienthoaigiaohang', 'like', '%' . $request->dienthoai . '%');
        }

        // Sắp xếp và phân trang
        $donhang = $query->orderBy('created_at', 'desc')->paginate(10);

        // Thêm tham số tìm kiếm vào link phân trang
        $donhang->appends($request->all());

        return view('admin.donhang.danhsach', compact('donhang'));
    }
    public function getThem() {}
    public function postThem(Request $request) {}
    public function getSua($id)
    {
        $donhang = DonHang::find($id);
        $tinhtrang = TinhTrang::all();
        return view('admin.donhang.sua', compact('donhang', 'tinhtrang'));
    }
    public function postSua(Request $request, $id)
    {
        $request->validate([
            'tinhtrang_id' => ['required'],
            'dienthoaigiaohang' => ['required', 'string', 'regex:/^[0-9]{10}$/'],
            'diachigiaohang' => ['required', 'string', 'max:255'],
        ]);

        $orm = DonHang::find($id);
        $orm->tinhtrang_id = $request->tinhtrang_id;
        $orm->dienthoaigiaohang = $request->dienthoaigiaohang;
        $orm->diachigiaohang = $request->diachigiaohang;
        $orm->save();
        return redirect()->route('admin.donhang');
    }
    public function getXoa($id)
    {
        DonHang_ChiTiet::where('donhang_id', $id)->delete();

        $orm = DonHang::find($id);
        $orm->delete();
        return redirect('donhang')->route('admin.donhang');
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
    public function show(DonHang $donHang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DonHang $donHang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DonHang $donHang)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DonHang $donHang)
    {
        //
    }
}
