<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\DonHang;
use App\Models\DonHang_ChiTiet;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Mail\DatHangThanhCongEmail;
use Illuminate\Support\Facades\Mail;
use App\Models\BinhLuanBaiViet;
use App\Models\YeuThich;
use App\Models\SanPham;
use App\Models\DanhGiaSanPham;
use Illuminate\Support\Facades\Storage;





class KhachHangController extends Controller
{
    //
    public function __construct() {}
    public function getHome()
    {
        if (Auth::check()) {
            $nguoidung = NguoiDung::find(Auth::user()->id);
            return view('user.home', compact('nguoidung'));
        } else
            return redirect()->route('user.dangnhap');
    }
    public function getDanhGiaSanPham($id)
    {
        // Tìm đơn hàng và kiểm tra quyền truy cập
        $donhang = DonHang::with(['DonHang_ChiTiet.sanpham.loaisanpham'])
            ->where('nguoidung_id', Auth::id())
            ->findOrFail($id);

        // Kiểm tra trạng thái đơn hàng - chỉ cho phép đánh giá đơn hàng đã giao (tinhtrang_id = 3)
        if ($donhang->tinhtrang_id != 3) {
            return redirect()->route('user.donhang')
                ->with('error', 'Chỉ có thể đánh giá đơn hàng đã giao hàng.');
        }

        // Kiểm tra xem đơn hàng có sản phẩm không
        if ($donhang->DonHang_ChiTiet->isEmpty()) {
            return redirect()->route('user.donhang')
                ->with('error', 'Không tìm thấy sản phẩm để đánh giá.');
        }

        // Hiển thị trang chi tiết đơn hàng với tham số để hiển thị form đánh giá
        return redirect()->route('user.donhang.chitiet', [
            'id' => $donhang->id,
            'action' => 'review'
        ]);
    }
    public function postHuyDonHang(Request $request, $id)
    {
        // Tìm đơn hàng
        $donhang = DonHang::where('id', $id)
            ->where('nguoidung_id', Auth::id())
            ->first();

        // Kiểm tra xem đơn hàng có tồn tại và thuộc về người dùng hiện tại không
        if (!$donhang) {
            return redirect()->route('user.donhang')->with('error', 'Không tìm thấy đơn hàng!');
        }

        // Kiểm tra trạng thái đơn hàng
        if ($donhang->tinhtrang_id != 1) {
            return redirect()->route('user.donhang')->with('error', 'Không thể hủy đơn hàng đã được xử lý!');
        }

        // Cập nhật trạng thái đơn hàng sang "Đã hủy"
        $donhang->tinhtrang_id = 4; // ID trạng thái "Đã hủy đơn hàng"
        //$donhang->ghichu = "Đơn hàng đã bị hủy bởi khách hàng";
        $donhang->save();

        // Cập nhật lại số lượng sản phẩm trong kho 
        if ($donhang->donHangChiTiet && count($donhang->donHangChiTiet) > 0) {
            foreach ($donhang->donHangChiTiet as $ct) {
                $sanpham = SanPham::find($ct->sanpham_id);
                if ($sanpham) {
                    $sanpham->soluong += $ct->soluong;
                    $sanpham->save();
                }
            }
        }

        // Thông báo thành công
        return redirect()->route('user.donhang')->with('success', 'Đơn hàng đã được hủy thành công!');
    }
    public function getDanhGia()
    {
        $danhGias = DanhGiaSanPham::with(['sanPham', 'sanPham.loaiSanPham'])
            ->where('nguoidung_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.danhgia', compact('danhGias'));
    }

    public function getDatHang()
    {
        // Bổ sung code tại đây
        if (Auth::check()) {
            // Khôi phục giỏ hàng từ DB
            Cart::restore(Auth::user()->id);
            return view('user.dathang');
        } else
            return redirect()->route('user.dangnhap');
    }
    public function postDatHang(Request $request)
    {
        // Kiểm tra dữ liệu 
        $this->validate($request, [
            'diachigiaohang' => ['required', 'string', 'max:255'],
            'dienthoaigiaohang' => ['required', 'string', 'regex:/^[0-9]{10}$/'],
            'payment_method' => ['required', 'string'],
        ]);

        // LƯU THÔNG TIN VÀO SESSION - Đây là bước quan trọng!
        session([
            'diachigiaohang' => $request->diachigiaohang,
            'dienthoaigiaohang' => $request->dienthoaigiaohang,
        ]);

        // Log để xác minh thông tin đã được lưu
        \Log::info('Thông tin giao hàng đã lưu vào session', [
            'diachigiaohang' => $request->diachigiaohang,
            'dienthoaigiaohang' => $request->dienthoaigiaohang,
            'payment_method' => $request->payment_method
        ]);

        // Kiểm tra phương thức thanh toán
        if ($request->payment_method == 'vnpay') {
            return redirect()->route('user.vnpay.payment');
        } else if ($request->payment_method == 'momo') {
            return redirect()->route('user.momo.payment');
        }

        // Xử lý đặt hàng thông thường
        $dh = new DonHang();
        $dh->nguoidung_id = Auth::user()->id;
        $dh->tinhtrang_id = 1; // Đơn hàng mới
        $dh->diachigiaohang = $request->diachigiaohang;
        $dh->dienthoaigiaohang = $request->dienthoaigiaohang;
        $dh->save();

        // Lưu chi tiết đơn hàng
        foreach (Cart::content() as $value) {
            $ct = new DonHang_ChiTiet();
            $ct->donhang_id = $dh->id;
            $ct->sanpham_id = $value->id;
            $ct->soluongban = $value->qty;
            $ct->dongiaban = $value->price;
            $ct->save();

            // Cập nhật số lượng sản phẩm trong kho
            $sanpham = SanPham::find($value->id);
            if ($sanpham) {
                $sanpham->soluong -= $value->qty; // Giảm số lượng tồn kho đi số lượng đã đặt
                if ($sanpham->soluong < 0) $sanpham->soluong = 0; // Đảm bảo không âm
                $sanpham->save();
            }
        }

        // Gửi email xác nhận
        Mail::to(Auth::user()->email)->send(new DatHangThanhCongEmail($dh));

        // Xóa giỏ hàng
        Cart::destroy();
        Cart::erase(Auth::user()->id);

        return redirect()->route('user.dathangthanhcong')
            ->with('success', 'Đặt hàng thành công!');
    }

    // public function postDatHang(Request $request)
    // {
    //     // Bổ sung code tại đây
    //     // Kiểm tra
    //     $this->validate($request, [
    //         'diachigiaohang' => ['required', 'string', 'max:255'],
    //         'dienthoaigiaohang' => ['required', 'string', 'max:255'],
    //     ]);

    //     // Lưu vào đơn hàng
    //     $dh = new DonHang();
    //     $dh->nguoidung_id = Auth::user()->id;
    //     $dh->tinhtrang_id = 1; // Đơn hàng mới
    //     $dh->diachigiaohang = $request->diachigiaohang;
    //     $dh->dienthoaigiaohang = $request->dienthoaigiaohang;
    //     $dh->save();

    //     // Lưu vào đơn hàng chi tiết
    //     foreach (Cart::content() as $value) {
    //         $ct = new DonHang_ChiTiet();
    //         $ct->donhang_id = $dh->id;
    //         $ct->sanpham_id = $value->id;
    //         $ct->soluongban = $value->qty;
    //         $ct->dongiaban = $value->price;
    //         $ct->save();
    //     }
    //     // Gởi email
    //     Mail::to(Auth::user()->email)->send(new DatHangThanhCongEmail($dh));
    //     // Xóa giỏ hàng
    //     Cart::destroy();
    //     Cart::erase(Auth::user()->id);
    //     return redirect()->route('user.dathangthanhcong');
    // }

    public function getDatHangThanhCong()
    {
        // Xử lý khi trở về từ VNPAY
        if (request()->has('vnp_ResponseCode') && request()->has('vnp_TxnRef')) {
            \Log::info('VNPay Return - Thông tin session', [
                'diachigiaohang' => session('diachigiaohang'),
                'dienthoaigiaohang' => session('dienthoaigiaohang')
            ]);

            if (request()->vnp_ResponseCode == '00') {
                // Xử lý đơn hàng khi thanh toán VNPAY thành công
                $this->processPaymentSuccess('vnpay', 'Thanh toán qua VNPAY. Mã giao dịch: ' . request()->vnp_TransactionNo);
            } else {
                return redirect()->route('user.dathang')
                    ->with('error', 'Thanh toán VNPAY thất bại. Mã lỗi: ' . request()->vnp_ResponseCode);
            }
        }

        // Xử lý khi trở về từ MoMo
        if (request()->has('resultCode') && request()->has('orderId')) {
            \Log::info('MoMo Return - Thông tin session', [
                'diachigiaohang' => session('diachigiaohang'),
                'dienthoaigiaohang' => session('dienthoaigiaohang')
            ]);

            if (request()->resultCode == '0') {
                // Giải mã extraData nếu có
                if (request()->has('extraData') && !empty(request()->extraData)) {
                    try {
                        $extraData = json_decode(base64_decode(request()->extraData), true);
                        \Log::info('MoMo extraData:', $extraData);
                    } catch (\Exception $e) {
                        \Log::error('Lỗi giải mã extraData: ' . $e->getMessage());
                    }
                }

                // Xử lý đơn hàng khi thanh toán MoMo thành công
                $this->processPaymentSuccess('momo', 'Thanh toán qua MoMo. Mã giao dịch: ' . request()->transId);
            } else {
                return redirect()->route('user.dathang')
                    ->with('error', 'Thanh toán MoMo thất bại. Mã lỗi: ' . request()->resultCode);
            }
        }

        // Hiển thị trang thành công
        return view('user.dathangthanhcong');
    }

    // Phương thức chung xử lý đơn hàng khi thanh toán thành công
    private function processPaymentSuccess($paymentMethod)
    {
        // KIỂM TRA SESSION - Đảm bảo thông tin giao hàng vẫn tồn tại
        if (!session('diachigiaohang') || !session('dienthoaigiaohang')) {
            \Log::error('Không tìm thấy thông tin giao hàng trong session khi xử lý thanh toán', [
                'diachigiaohang' => session('diachigiaohang'),
                'dienthoaigiaohang' => session('dienthoaigiaohang'),
                'payment_method' => $paymentMethod
            ]);
            return redirect()->route('user.dathang')
                ->with('error', 'Không tìm thấy thông tin giao hàng. Vui lòng thử lại.');
        }

        // Tạo đơn hàng mới
        $dh = new DonHang();
        $dh->nguoidung_id = Auth::user()->id;
        $dh->tinhtrang_id = 1; // Đơn hàng mới
        $dh->diachigiaohang = session('diachigiaohang');
        $dh->dienthoaigiaohang = session('dienthoaigiaohang');
        $dh->save();

        // Lưu chi tiết đơn hàng 
        foreach (Cart::content() as $value) {
            $ct = new DonHang_ChiTiet();
            $ct->donhang_id = $dh->id;
            $ct->sanpham_id = $value->id;
            $ct->soluongban = $value->qty;
            $ct->dongiaban = $value->price;
            $ct->save();

            // Cập nhật số lượng sản phẩm trong kho
            $sanpham = SanPham::find($value->id);
            if ($sanpham) {
                $sanpham->soluong -= $value->qty;
                if ($sanpham->soluong < 0) $sanpham->soluong = 0;
                $sanpham->save();
            }
        }

        try {
            Mail::to(Auth::user()->email)->send(new DatHangThanhCongEmail($dh));
        } catch (\Exception $e) {
            \Log::error("Lỗi gửi email: " . $e->getMessage());
        }

        // Xóa giỏ hàng và session
        Cart::destroy();
        Cart::erase(Auth::user()->id);
        session()->forget(['diachigiaohang', 'dienthoaigiaohang', 'vnpay_txnref', 'momo_orderid']);

        // Ghi log giao dịch thành công
        \Log::info("Thanh toán {$paymentMethod} thành công", [
            'user_id' => Auth::id(),
            'order_id' => $dh->id,
            'payment_method' => $paymentMethod
        ]);

        return true;
    }

    public function getDonHang()
    {
        // Lấy tất cả đơn hàng của người dùng
        $donhang = DonHang::with('tinhtrang')
            ->where('nguoidung_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.donhang', compact('donhang'));
    }

    public function getDonHangChiTiet($id)
    {
        // Lấy chi tiết đơn hàng với eager loading
        $donhang = DonHang::with(['DonHang_ChiTiet.sanpham.loaisanpham', 'tinhtrang'])
            ->where('nguoidung_id', Auth::id())
            ->findOrFail($id);

        if ($donhang->nguoidung_id != Auth::id()) {
            return redirect()->route('user.donhang')
                ->with('warning', 'Bạn không có quyền xem đơn hàng này');
        }

        // Kiểm tra xem có phải đang hiển thị form đánh giá không
        $showReviewSection = request()->has('action') && request()->action == 'review';

        return view('user.donhang_chitiet', compact('donhang', 'showReviewSection'));
    }

    public function postDonHang(Request $request, $id)
    {
        // Cập nhật trạng thái đơn hàng
        $donhang = DonHang::where('nguoidung_id', Auth::id())
            ->findOrFail($id);

        if ($donhang->nguoidung_id != Auth::id()) {
            return redirect()->route('user.donhang')
                ->with('warning', 'Bạn không có quyền cập nhật đơn hàng này');
        }

        $donhang->tinhtrang_id = $request->tinhtrang_id;
        $donhang->save();

        return redirect()->route('user.donhang.chitiet', $id)
            ->with('success', 'Cập nhật trạng thái thành công');
    }

    public function postBinhLuan(Request $request, $baiviet_id)
    {
        if (Auth::check()) {
            $request->validate([
                'noidungbinhluan' => ['required', 'string'],
            ]);

            $orm = new BinhLuanBaiViet();
            $orm->baiviet_id = $baiviet_id;
            $orm->nguoidung_id = Auth::user()->id;
            $orm->noidungbinhluan = $request->noidungbinhluan;
            $orm->save();

            return redirect()->back();
        } else {
            return redirect()->route('user.dangnhap');
        }
    }

    public function getHoSoCaNhan()
    {
        // Bổ sung code tại đây
        return redirect()->route('user.home');
    }

    public function postHoSoCaNhan(Request $request)
    {
        // Bổ sung code tại đây
        $id = Auth::user()->id;

        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:nguoidung,email,' . $id],
            'password' => ['confirmed'],
        ]);

        $orm = NguoiDung::find($id);
        $orm->name = $request->name;
        $orm->username = Str::before($request->email, '@');
        $orm->email = $request->email;
        if (!empty($request->password)) $orm->password = Hash::make($request->password);
        $orm->save();

        return redirect()->route('user.home')->with('success', 'Đã cập nhật thông tin thành công.');
    }

    public function getYeuThich()
    {
        $yeuthich = YeuThich::with('sanpham')
            ->where('nguoidung_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.sanpham_yeuthich', compact('yeuthich'));
    }

    public function postYeuThichThem($id)
    {
        try {
            // Kiểm tra sản phẩm tồn tại
            $sanpham = SanPham::findOrFail(id: $id);

            // Kiểm tra người dùng đã đăng nhập
            if (!Auth::check()) {
                return redirect()->route('user.dangnhap')
                    ->with('warning', 'Vui lòng đăng nhập để thêm sản phẩm yêu thích');
            }

            // Kiểm tra đã yêu thích chưa
            $yeuthich = YeuThich::where('nguoidung_id', Auth::id())
                ->where('sanpham_id', $id)
                ->first();

            if ($yeuthich) {
                return redirect()->back()
                    ->with('warning', 'Sản phẩm đã có trong danh sách yêu thích');
            }

            // Thêm vào yêu thích
            YeuThich::create([
                'nguoidung_id' => Auth::id(),
                'sanpham_id' => $id
            ]);

            return redirect()->back()
                ->with('toast_success', 'Đã thêm vào danh sách yêu thích');
        } catch (ModelNotFoundException $e) {
            return redirect()->back()
                ->with('warning', 'Không tìm thấy sản phẩm');
        } catch (\Exception $e) {
            \Log::error('Lỗi thêm yêu thích: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau');
        }
    }

    public function getYeuThichXoa($id)
    {
        try {
            YeuThich::where('nguoidung_id', Auth::id())
                ->where('sanpham_id', $id)
                ->delete();

            return redirect()->back()
                ->with('success', 'Đã xóa khỏi danh sách yêu thích');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('warning', 'Có lỗi xảy ra khi xóa khỏi yêu thích');
        }
    }

    // github momo https://github.com/momo-wallet/payment/blob/master/php/atm/atm_momo.php

    function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

    public function momo_payment()
    {
        // Kiểm tra giỏ hàng
        if (Cart::count() == 0) {
            return redirect()->route('frontend.giohang')->with('warning', 'Giỏ hàng trống!');
        }

        // KIỂM TRA SESSION - Đảm bảo thông tin giao hàng tồn tại
        if (!session('diachigiaohang') || !session('dienthoaigiaohang')) {
            \Log::error('Không tìm thấy thông tin giao hàng trong session', [
                'diachigiaohang' => session('diachigiaohang'),
                'dienthoaigiaohang' => session('dienthoaigiaohang')
            ]);
            return redirect()->route('user.dathang')
                ->with('warning', 'Vui lòng nhập thông tin giao hàng!');
        }

        $total = (int)Cart::total(0, '', ''); // Lấy tổng giá trị giỏ hàng

        // Kiểm tra số tiền hợp lệ
        if ($total < 10000) {
            return redirect()->route('user.dathang')
                ->with('warning', 'Số tiền thanh toán tối thiểu phải từ 10.000đ');
        }
        if ($total > 50000000) {
            return redirect()->route('user.dathang')
                ->with('warning', 'Số tiền thanh toán tối đa là 50.000.000đ');
        }

        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

        $orderInfo = "Thanh toán qua MoMo";
        $amount = $total;
        $orderId = 'MO' . time();
        $redirectUrl = url('/khach-hang/dat-hang-thanh-cong');
        $ipnUrl = url('/khach-hang/dat-hang-thanh-cong');

        // LƯU MÃ ĐƠN HÀNG VÀO EXTRA DATA để có thể nhận lại
        $extraData = base64_encode(json_encode([
            'order_id' => $orderId
        ]));

        $requestId = time() . "";
        $requestType = "payWithATM";
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Enternalux Shop",
            "storeId" => "EnternaluxStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );

        // LƯU THÊM THÔNG TIN VÀO SESSION - để xác nhận khi quay lại
        session([
            'momo_orderid' => $orderId
        ]);

        // Log để kiểm tra
        \Log::info('MoMo Payment - Thông tin session trước khi redirect', [
            'diachigiaohang' => session('diachigiaohang'),
            'dienthoaigiaohang' => session('dienthoaigiaohang'),
            'momo_orderid' => $orderId
        ]);

        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);

        if (isset($jsonResult['payUrl'])) {
            return redirect($jsonResult['payUrl']);
        } else {
            \Log::error('MoMo Payment Error', $jsonResult);
            return redirect()->route('user.dathang')
                ->with('error', 'Có lỗi khi kết nối với MoMo. Vui lòng thử lại sau.');
        }
    }

    public function vnpay_payment()
    {
        // Kiểm tra giỏ hàng
        if (Cart::count() == 0) {
            return redirect()->route('frontend.giohang')->with('warning', 'Giỏ hàng trống!');
        }

        // KIỂM TRA SESSION - Đảm bảo thông tin giao hàng tồn tại
        if (!session('diachigiaohang') || !session('dienthoaigiaohang')) {
            \Log::error('Không tìm thấy thông tin giao hàng trong session', [
                'diachigiaohang' => session('diachigiaohang'),
                'dienthoaigiaohang' => session('dienthoaigiaohang')
            ]);
            return redirect()->route('user.dathang')
                ->with('warning', 'Vui lòng nhập thông tin giao hàng!');
        }

        // Cấu hình VNPAY
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_TmnCode = "YS6A0WSA";
        $vnp_HashSecret = "KHBFJCWFIQ76A27C8IKDWFKF8TV3BG9H";

        // Các thông tin khác của VNPAY
        $vnp_Returnurl = url('/khach-hang/dat-hang-thanh-cong');
        $vnp_TxnRef = 'DH' . time();
        $vnp_OrderInfo = "Thanh toan hoa don Enternalux";
        $vnp_OrderType = "billpayment";
        $vnp_Amount = (int)(Cart::total(0, '', '') * 100);
        $vnp_Locale = "vn";
        $vnp_IpAddr = request()->ip();

        // Dữ liệu gửi đến VNPay
        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        // Các bước tạo URL thanh toán 
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        $query = rtrim($query, '&');
        $vnp_Url = $vnp_Url . "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= '&vnp_SecureHash=' . $vnpSecureHash;

        // LƯU THÊM THÔNG TIN VÀO SESSION - để xác nhận khi quay lại
        session([
            'vnpay_txnref' => $vnp_TxnRef
        ]);

        // Log để kiểm tra
        \Log::info('VNPAY Payment - Thông tin session trước khi redirect', [
            'diachigiaohang' => session('diachigiaohang'),
            'dienthoaigiaohang' => session('dienthoaigiaohang'),
            'vnpay_txnref' => $vnp_TxnRef
        ]);

        // Chuyển hướng đến trang thanh toán VNPay
        return redirect($vnp_Url);
    }

    // public function vnpay_return(Request $request)
    // {
    //     \Log::info('VNPay Return Data:', $request->all());

    //     $vnp_HashSecret = "VTHBVIA4PDGNOVXVUYQW2CNPC8QI87CP";
    //     $vnp_SecureHash = $request->vnp_SecureHash;
    //     $inputData = $request->except(['vnp_SecureHash']);
    //     ksort($inputData);

    //     $hashData = urldecode(http_build_query($inputData));
    //     $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

    //     if ($secureHash == $vnp_SecureHash) {
    //         if ($request->vnp_ResponseCode == '00') {
    //             // Thay saveOrder bằng postDatHang
    //             return $this->saveOrder()
    //                 ? redirect()->route('frontend.dathangthanhcong')->with('success', 'Đã thanh toán thành công!')
    //                 : redirect()->route('frontend.giohang')->with('error', 'Có lỗi khi thanh toán!');
    //         } else {
    //             \Log::error('VNPay Payment Error:', ['ResponseCode' => $request->vnp_ResponseCode]);
    //         }
    //     } else {
    //         \Log::error('VNPay Hash không khớp');
    //     }

    //     return redirect()->route('frontend.giohang')
    //         ->with('error', 'Có lỗi khi thanh toán!');
    // }


    // private function saveOrder()
    // {
    //     try {
    //         // Lưu vào đơn hàng
    //         $dh = new DonHang();
    //         $dh->nguoidung_id = Auth::user()->id;
    //         $dh->tinhtrang_id = 1; // Đơn hàng mới
    //         $dh->diachigiaohang = session('diachigiaohang');
    //         $dh->dienthoaigiaohang = session('dienthoaigiaohang');
    //         $dh->save();

    //         // Lưu vào đơn hàng chi tiết
    //         foreach (Cart::content() as $value) {
    //             $ct = new DonHang_ChiTiet();
    //             $ct->donhang_id = $dh->id;
    //             $ct->sanpham_id = $value->id;
    //             $ct->soluongban = $value->qty;
    //             $ct->dongiaban = $value->price;
    //             $ct->save();
    //         }

    //         // Gửi email xác nhận đơn hàng
    //         Mail::to(Auth::user()->email)->send(new DatHangThanhCongEmail($dh));

    //         // Xóa giỏ hàng
    //         Cart::destroy();
    //         Cart::erase(Auth::user()->id);

    //         return true;
    //     } catch (\Exception $e) {
    //         return false;
    //     }
    // }

    // public function postAvatar(Request $request)
    // {
    //     $request->validate([
    //         'hinhanh' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048'
    //     ]);

    //     try {
    //         if ($request->hasFile('hinhanh')) {
    //             // Xóa file cũ
    //             if (!empty(Auth::user()->hinhanh)) {
    //                 Storage::delete('avatar/' . Auth::user()->hinhanh);
    //             }

    //             // Upload file mới
    //             $extension = $request->file('hinhanh')->extension();
    //             $filename = 'avatar-' . Auth::id() . '.' . $extension;

    //             // Lưu file vào thư mục avatar
    //             $path = Storage::putFileAs('avatar', $request->file('hinhanh'), $filename);

    //             // Cập nhật database
    //             $user = Auth::user();
    //             $user->hinhanh = $filename;
    //             $user->save();

    //             return back()->with('success', 'Đã cập nhật ảnh đại diện');
    //         }
    //     } catch (\Exception $e) {
    //         return back()->with('error', 'Lỗi khi cập nhật ảnh: ' . $e->getMessage());
    //     }
    // }

    // public function deleteAvatar()
    // {
    //     try {
    //         $user = Auth::user();
    //         if (!empty($user->hinhanh)) {
    //             Storage::delete('app/private/avatar/' . $user->hinhanh);
    //             $user->hinhanh = null;
    //             $user->save();
    //         }
    //         return back()->with('success', 'Đã xóa ảnh đại diện');
    //     } catch (\Exception $e) {
    //         return back()->with('error', 'Lỗi khi xóa ảnh: ' . $e->getMessage());
    //     }
    // }

    // public function postDangXuat(Request $request)
    // {
    //     // Bổ sung code tại đây
    //     return redirect()->route('frontend.home');
    // }
}
