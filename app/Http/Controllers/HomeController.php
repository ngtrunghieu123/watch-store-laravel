<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Models\LoaiSanPham;
use App\Models\HangSanXuat;
use App\Models\SanPham;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Mail;
use App\Models\DonHang;
use App\Models\DonHang_ChiTiet;
use App\Mail\DatHangThanhCongEmail;
use App\Models\ChuDe;
use App\Models\BaiViet;
use App\Models\KhuyenMai;
use App\Models\SanPham_KhuyenMai;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\CustomerAuthenticate;
use App\Mail\LienHeThanhCongEmail;




class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth')->only(
            'getGioHang',
            'getGioHang_Them',
            'getGioHang_Xoa',
            'getGioHang_Giam',
            'getGioHang_Tang',
            'postGioHang_CapNhat'
        );
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getHome()
    {
        $loaisanpham = LoaiSanPham::orderBy('tenloai')->get();
        $hangsanxuat = HangSanXuat::orderBy('tenhang')->get();

        // Lấy sản phẩm khuyến mãi hiện tại cho banner và hiển thị
        $today = date('Y-m-d');
        $sanphamkhuyenmai = SanPham::with(['khuyenMai' => function ($query) use ($today) 
        {
            $query->where('trangthai', 1)
                ->where('ngaybatdau', '<=', $today)
                ->where('ngayketthuc', '>=', $today);
        }])
            ->whereHas('khuyenMai', function ($query) use ($today) {
                $query->where('trangthai', 1)
                    ->where('ngaybatdau', '<=', $today)
                    ->where('ngayketthuc', '>=', $today);
            })
            ->orderBy('updated_at', 'desc')
            ->take(2)
            ->get();

        // lấy sản phẩm đa dạng theo loại
        $sanphamtheoloai = [];

        // Với mỗi loại sản phẩm, lấy các sản phẩm từ các hãng khác nhau
        foreach ($loaisanpham as $lsp) {
            // Lấy danh sách các hãng có sản phẩm thuộc loại này
            $dsHang = HangSanXuat::whereHas('sanPham', function ($query) use ($lsp) {
                $query->where('loaisanpham_id', $lsp->id);
            })
                ->get();

            $sanpham = collect(); // Khởi tạo một collection để lưu sản phẩm

            // Với mỗi hãng, lấy 1-2 sản phẩm mới nhất thuộc loại này
            foreach ($dsHang as $hang) {
                $sp = SanPham::where('loaisanpham_id', $lsp->id)
                    ->where('hangsanxuat_id', $hang->id)
                    ->orderBy('created_at', 'desc')
                    ->take(1) // Lấy 1 sản phẩm mỗi hãngx
                    ->get();

                $sanpham = $sanpham->merge($sp);

                // Giới hạn tối đa 8 sản phẩm cho mỗi loại
                if ($sanpham->count() >= 8) break;
            }

            $sanphamtheoloai[$lsp->id] = $sanpham;
        }

        // Lấy 4 đồng hồ cơ mới nhất từ các hãng khác nhau
        $donghocomoi = collect();
        $hangsanxuatDHC = HangSanXuat::whereHas('sanPham', function ($query) {
            $query->where('loaisanpham_id', 1); // ID của đồng hồ cơ
        })
            ->take(4)
            ->get();

        foreach ($hangsanxuatDHC as $hang) {
            $sp = SanPham::where('loaisanpham_id', 1)
                ->where('hangsanxuat_id', $hang->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($sp) $donghocomoi->push($sp);

            if ($donghocomoi->count() >= 4) break;
        }

        // Lấy 4 đồng hồ thông minh mới nhất từ các hãng khác nhau
        $donghothongminhmoi = collect();
        $hangsanxuatDHTM = HangSanXuat::whereHas('sanPham', function ($query) {
            $query->where('loaisanpham_id', 2); // ID của đồng hồ thông minh
        })
            ->take(4)
            ->get();

        foreach ($hangsanxuatDHTM as $hang) {
            $sp = SanPham::where('loaisanpham_id', 2)
                ->where('hangsanxuat_id', $hang->id)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($sp) $donghothongminhmoi->push($sp);

            if ($donghothongminhmoi->count() >= 4) break;
        }

        return view('frontend.home', compact(
            'loaisanpham',
            'hangsanxuat',
            'donghocomoi',
            'donghothongminhmoi',
            'sanphamkhuyenmai',
            'sanphamtheoloai'
        ));
    }


    public function getKhuyenMai()
    {
        $today = date('Y-m-d');

        // Lấy tất cả khuyến mãi hiện tại
        $khuyenmai = KhuyenMai::where('trangthai', 1)
            ->where('ngaybatdau', '<=', $today)
            ->where('ngayketthuc', '>=', $today)
            ->get();

        if ($khuyenmai->isNotEmpty()) {
            // Lấy tất cả sản phẩm có khuyến mãi (không trùng lặp)
            $sanphamIDs = [];
            foreach ($khuyenmai as $km) {
                $sanphamIDs = array_merge($sanphamIDs, $km->sanPham->pluck('id')->toArray());
            }
            $sanphamIDs = array_unique($sanphamIDs);

            // Lấy sản phẩm với phân trang
            $sanpham = SanPham::with(['loaisanpham', 'khuyenMai' => function ($q) use ($today) {
                $q->where('trangthai', 1)
                    ->where('ngaybatdau', '<=', $today)
                    ->where('ngayketthuc', '>=', $today);
            }])
                ->whereIn('id', $sanphamIDs)
                ->paginate(12);

            return view('frontend.khuyenmai', compact('khuyenmai', 'sanpham'));
        }

        return redirect()->route('frontend.home')
            ->with('warning', 'Hiện tại không có chương trình khuyến mãi nào!');
    }
    public function getSanPhamTheoGia($mucgia)
    {
        $loaisanpham = LoaiSanPham::all();
        $hangsanxuat = HangSanXuat::all();
        $query = SanPham::query();

        switch ($mucgia) {
            case 'duoi-1-trieu':
                $query->where('dongia', '<', 1000000);
                $tieude = 'Dưới 1 triệu';
                break;

            case '1-9-trieu':
                $query->whereBetween('dongia', [1000000, 9000000]);
                $tieude = 'Từ 1 - 9 triệu';
                break;

            case 'tren-10-trieu':
                $query->where('dongia', '>=', 10000000);
                $tieude = 'Trên 10 triệu';
                break;

            default:
                return redirect()->route('frontend.sanpham');
        }

        $sanpham = $query->orderBy('dongia', 'desc')->paginate(12);

        return view('frontend.sanpham', compact('sanpham', 'loaisanpham', 'hangsanxuat', 'tieude', 'mucgia'));
    }
    public function getSanPhamTheoHangVaGia($tenhang_slug, $mucgia)
    {
        $loaisanpham = LoaiSanPham::all();
        $hangsanxuat = HangSanXuat::all();
        $hang = HangSanXuat::where('tenhang_slug', $tenhang_slug)->firstOrFail();

        $query = SanPham::where('hangsanxuat_id', $hang->id);

        switch ($mucgia) {
            case 'duoi-1-trieu':
                $query->where('dongia', '<', 1000000);
                $tieude = $hang->tenhang . ' dưới 1 triệu';
                break;

            case '1-9-trieu':
                $query->whereBetween('dongia', [1000000, 9000000]);
                $tieude = $hang->tenhang . ' từ 1-9 triệu';
                break;

            case 'tren-10-trieu':
                $query->where('dongia', '>=', 10000000);
                $tieude = $hang->tenhang . ' trên 10 triệu';
                break;
        }

        $sanpham = $query->orderBy('dongia', 'desc')->paginate(12);

        return view('frontend.sanpham', compact('sanpham', 'loaisanpham', 'hangsanxuat', 'hang', 'tieude', 'mucgia'));
    }
    public function getSanPhamTheoLoaiVaGia($tenloai_slug, $mucgia)
    {
        $loaisanpham = LoaiSanPham::all();
        $hangsanxuat = HangSanXuat::all();
        $loai = LoaiSanPham::where('tenloai_slug', $tenloai_slug)->firstOrFail();

        $query = SanPham::where('loaisanpham_id', $loai->id);

        switch ($mucgia) {
            case 'duoi-1-trieu':
                $query->where('dongia', '<', 1000000);
                $tieude = $loai->tenloai . ' dưới 1 triệu';
                break;

            case '1-9-trieu':
                $query->whereBetween('dongia', [1000000, 9000000]);
                $tieude = $loai->tenloai . ' từ 1-9 triệu';
                break;

            case 'tren-10-trieu':
                $query->where('dongia', '>=', 10000000);
                $tieude = $loai->tenloai . ' trên 10 triệu';
                break;
        }

        $sanpham = $query->orderBy('dongia', 'desc')->paginate(12);

        return view('frontend.sanpham', compact('sanpham', 'loaisanpham', 'hangsanxuat', 'loai', 'tieude', 'mucgia'));
    }

    public function getTimKiem(Request $request)
    {
        $tukhoa = $request->tukhoa;
        $query = SanPham::query();

        $query->where(function ($q) use ($tukhoa) {
            $q->where('tensanpham', 'like', '%' . $tukhoa . '%')
                ->orWhereHas('HangSanXuat', function ($q) use ($tukhoa) {
                    $q->where('tenhang', 'like', '%' . $tukhoa . '%');
                });
        });

        $sanpham = $query->paginate(12);

        // Nếu không tìm thấy sản phẩm nào
        if ($sanpham->total() == 0) {
            // Lấy 8 sản phẩm mới nhất để gợi ý
            $sanphamgoiy = SanPham::orderBy('created_at', 'desc')
                ->take(8)
                ->get();

            return view('frontend.sanpham', [
                'sanpham' => $sanpham,
                'tukhoa' => $tukhoa,
                'sanphamgoiy' => $sanphamgoiy,
                'khongtimthay' => true
            ]);
        }

        return view('frontend.sanpham', [
            'sanpham' => $sanpham,
            'tukhoa' => $tukhoa
        ]);
    }
    public function getSanPhamTheoHang($tenhang_slug)
    {
        try {
            // Tìm hãng theo slug
            $hang = HangSanXuat::where('tenhang_slug', $tenhang_slug)->firstOrFail();

            // Lấy sản phẩm của hãng
            $sanpham = SanPham::where('hangsanxuat_id', $hang->id)
                ->orderBy('tensanpham')
                ->paginate(12);

            return view('frontend.sanpham', compact('sanpham', 'hang'));
        } catch (\Exception $e) {
            // Ghi log lỗi
            \Log::error('Lỗi tìm sản phẩm theo hãng: ' . $e->getMessage());
            // Chuyển về trang sản phẩm với thông báo
            return redirect()
                ->route('frontend.sanpham')
                ->with('warning', 'Không tìm thấy sản phẩm của hãng này');
        }
    }
    public function getSanPham($tenloai_slug = '')
    {
        if ($tenloai_slug) {
            $loai = LoaiSanPham::where('tenloai_slug', $tenloai_slug)->firstOrFail();

            // Đổi tên biến để khớp với tên muốn truyền vào view
            $sanpham = SanPham::where('loaisanpham_id', $loai->id)
                ->paginate(12);

            return view('frontend.sanpham', compact('sanpham', 'loai'));
        }

        $sanpham = SanPham::paginate(12);
        return view('frontend.sanpham', compact('sanpham'));
    }

    public function getSanPham_ChiTiet($tenloai_slug = '', $tensanpham_slug = '')
    {
        $sanpham = SanPham::where('tensanpham_slug', $tensanpham_slug)->firstOrFail();

        // Lấy sản phẩm liên quan cùng loại
        $splienquan = SanPham::where('loaisanpham_id', operator: $sanpham->loaisanpham_id)
            ->where('id', '!=', $sanpham->id)
            ->limit(4)
            ->get();

        return view('frontend.sanpham_chitiet', compact('sanpham', 'splienquan'));
    }
    public function postDatHangNhanh(Request $request)
    {

        // Lấy thông tin sản phẩm được chọn
        $sanpham = SanPham::findOrFail($request->sanpham_id);
        $soluong = $request->soluong;

        // Thêm sản phẩm được chọn vào giỏ hàng
        Cart::add([
            'id' => $sanpham->id,
            'name' => $sanpham->tensanpham,
            'price' => $sanpham->dongia,
            'qty' => $soluong,
            'weight' => 0,
            'options' => [
                'image' => $sanpham->hinhanh,
                'loai_slug' => $sanpham->loaisanpham->tenloai_slug,
                'sanpham_slug' => $sanpham->tensanpham_slug
            ]
        ]);

        return redirect()->route('frontend.giohang');
    }
    public function getBaiViet($tenchude_slug = '')
    {
        // Bổ sung code tại đây
        if (empty($tenchude_slug)) {
            $title = 'Tin tức';
            $baiviet = BaiViet::where('kichhoat', 1)
                ->where('kiemduyet', 1)
                ->orderBy('created_at', 'desc')
                ->paginate(6);
        } else {
            $chude = ChuDe::where('tenchude_slug', $tenchude_slug)
                ->firstOrFail();
            $title = $chude->tenchude;
            $baiviet = BaiViet::where('kichhoat', 1)
                ->where('kiemduyet', 1)
                ->where('chude_id', $chude->id)
                ->orderBy('created_at', 'desc')
                ->paginate(6);
        }

        return view('frontend.baiviet', compact('title', 'baiviet'));
    }

    public function getBaiViet_ChiTiet($tenchude_slug = '', $tieude_slug = '')
    {
        // Bổ sung code tại đây
        
        $tieude_id = explode('.', $tieude_slug);
        $tieude = explode('-', $tieude_id[0]);
        $baiviet_id = $tieude[count($tieude) - 1];

        
        $baiviet = BaiViet::where('kichhoat', 1)
            ->where('kiemduyet', 1)
            ->where('id', $baiviet_id)
            ->firstOrFail();

        if (!$baiviet) abort(404);

        // Cập nhật lượt xem
        $daxem = 'BV' . $baiviet_id;
        if (!session()->has($daxem)) {
            $orm = BaiViet::find($baiviet_id);
            $orm->luotxem = $baiviet->luotxem + 1;
            $orm->save();
            session()->put($daxem, 1);
        }

        $baivietcungchuyemuc = BaiViet::where('kichhoat', 1)
            ->where('kiemduyet', 1)
            ->where('chude_id', $baiviet->chude_id)
            ->where('id', '!=', $baiviet_id)
            ->orderBy('created_at', 'desc')
            ->take(4)->get();

        return view('frontend.baiviet_chitiet', compact('baiviet', 'baivietcungchuyemuc', 'baiviet_id'));
    }
    public function getGioHang()
    {
        if (Cart::count() > 0)
            return view('frontend.giohang');
        else
            return view('frontend.giohangrong');
    }
    public function getGioHang_Them($tensanpham_slug = '', Request $request)
    {
        try {
            $sanpham = SanPham::where('tensanpham_slug', $tensanpham_slug)->firstOrFail();

            // Kiểm tra sản phẩm trong giỏ
            $cartItem = Cart::search(function ($cartItem) use ($sanpham) {
                return $cartItem->id === $sanpham->id;
            })->first();

            // Xóa giỏ hàng trong DB trước khi thêm mới
            if (Auth::check()) {
                Cart::erase(Auth::user()->id);
            }

            // Lấy giá khuyến mãi nếu có
            $giaban = $sanpham->giaKhuyenMai(); // Sử dụng giá khuyến mãi
            $khuyenmai = $sanpham->khuyenMaiHienTai(); // Lấy thông tin khuyến mãi nếu có

            if ($cartItem) {
                // Xóa và thêm lại để đẩy lên đầu
                Cart::remove($cartItem->rowId);
                Cart::add([
                    'id' => $sanpham->id,
                    'name' => $sanpham->tensanpham,
                    'price' => $giaban,
                    'qty' => $cartItem->qty,
                    'weight' => 0,
                    'options' => [
                        'image' => $sanpham->hinhanh,
                        'giagoc' => $sanpham->dongia,
                        'khuyenmai' => $khuyenmai ? $khuyenmai->phantram : 0
                    ]
                ]);
            } else {
                Cart::add([
                    'id' => $sanpham->id,
                    'name' => $sanpham->tensanpham,
                    'price' => $giaban,
                    'qty' => $request->query('soluong', 1),
                    'weight' => 0,
                    'options' => [
                        'image' => $sanpham->hinhanh,
                        'giagoc' => $sanpham->dongia,
                        'khuyenmai' => $khuyenmai ? $khuyenmai->phantram : 0
                    ]
                ]);
            }

            // Lưu toàn bộ giỏ hàng vào DB
            if (Auth::check()) {
                Cart::store(Auth::user()->id);
            }

            return redirect()->back()->with('toast_success', 'Đã thêm sản phẩm vào giỏ hàng');
        } catch (\Exception $e) {
            \Log::error('Lỗi thêm giỏ hàng: ' . $e->getMessage());
            return redirect()->back()->with('warning', 'Có lỗi xảy ra khi thêm vào giỏ hàng!');
        }
    }

    public function getGioHang_Xoa($row_id)
    {
        // Bổ sung code tại đây
        Cart::remove($row_id);
        
        Cart::erase(Auth::user()->id);
        
        return redirect()->route('frontend.giohang');
    }

    public function getGioHang_Giam($row_id)
    {
        // Bổ sung code tại đây
        $row = Cart::get($row_id);

        // Nếu số lượng là 1 thì không giảm được nữa
        if ($row->qty > 1) {
            Cart::update($row_id, $row->qty - 1);
        }

        return redirect()->route('frontend.giohang');
    }

    public function getGioHang_Tang($row_id)
    {
        // Bổ sung code tại đây
        $row = Cart::get($row_id);

        // Không được tăng vượt quá 10 sản phẩm
        if ($row->qty < 10) {
            Cart::update($row_id, $row->qty + 1);
        }

        return redirect()->route('frontend.giohang');
    }

    public function postGioHang_CapNhat(Request $request)
    {
        // Bổ sung code tại đây
        foreach ($request->qty as $row_id => $quantity) {
            if ($quantity <= 0)
                Cart::update($row_id, 1);
            else if ($quantity > 10)
                Cart::update($row_id, 10);
            else
                Cart::update($row_id, $quantity);
        }

        return redirect()->route('frontend.giohang');
    }

    public function getTuyenDung()
    {

        $baiviet = BaiViet::where('kichhoat', 1)
            ->where('kiemduyet', 1)
            ->where('chude_id', 4)
            ->orderBy('created_at', 'desc')
            ->firstOrFail();

        if (!$baiviet) abort(404);

        $baiviet_id = $baiviet->id;
        // Cập nhật lượt xem
        $daxem = 'BV' . $baiviet_id;
        if (!session()->has($daxem)) {
            $orm = BaiViet::find($baiviet_id);
            $orm->luotxem = $baiviet->luotxem + 1;
            $orm->save();
            session()->put($daxem, 1);
        }

        $baivietcungchuyemuc = BaiViet::where('kichhoat', 1)
            ->where('kiemduyet', 1)
            ->where('chude_id', $baiviet->chude_id)
            ->where('id', '!=', $baiviet_id)
            ->orderBy('created_at', 'desc')
            ->take(4)->get();

        return view('frontend.tuyendung', compact('baiviet', 'baivietcungchuyemuc', 'baiviet_id'));
    }
    public function postLienHe(Request $request)
    {
        $request->validate([
            'HoVaTen' => ['required', 'string', 'max:255'],
            'Email' => ['required', 'email'],
            'DienThoai' => ['required', 'string', 'max:20'],
            'ChuDe' => ['required', 'string'],
            'NoiDung' => ['required', 'string'],
        ]);

        Mail::to("hieupoka5789@gmail.com")->send(new LienHeThanhCongEmail($request));

        return redirect()->route('frontend.lienhethanhcong');
    }
    public function getLienHeThangCong()
    {
        return view('frontend.lienhethanhcong');
    }
    public function getLienHe()
    {
        return view('frontend.lienhe');
    }

    // Trang đăng ký dành cho khách hàng
    public function getDangKy()
    {
        return view('user.dangky');
    }


    // Trang đăng nhập dành cho khách hàng
    public function getDangNhap()
    {
        if (Auth::check())
            return redirect()->route('user.home');
        else
            return view('user.dangnhap');
    }

    public function getQuenMatKhau()
    {
        return view('user.quenmatkhau');
    }
    public function getGoogleLogin()
    {
        return Socialite::driver('google')->redirect();
    }
    public function getGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')
                ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
                ->stateless()
                ->user();
        } catch (Exception $e) {
            return redirect()->route('user.dangnhap')->with('warning', 'Lỗi xác thực. Xin vui lòng thử lại!');
        }

        $existingUser = NguoiDung::where('email', $user->email)->first();
        if ($existingUser) {
            // Nếu người dùng đã tồn tại thì đăng nhập
            Cart::restore($existingUser->id);
            Cart::store($existingUser->id);
            Auth::login($existingUser, true);
            if ($existingUser->role == 'admin') {
                return redirect()->route('admin.home');
            } else {
                return redirect()->route('user.home');
            }
        } else {
            // Nếu chưa tồn tại người dùng thì thêm mới
            $newUser = NguoiDung::create([
                'name' => $user->name,
                'email' => $user->email,
                'username' => Str::before($user->email, '@'),
                'password' => Hash::make('larashop@2024'), // Gán mật khẩu tự do
            ]);

            // Sau đó đăng nhập
            Auth::login($newUser, true);
            return redirect()->route('user.home');
        }
    }
}
