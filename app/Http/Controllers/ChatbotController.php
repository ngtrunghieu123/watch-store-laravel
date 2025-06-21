<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatbotMessage;
use App\Models\SanPham;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function sendMessage(Request $request)
    {
        try {
            $message = $request->input('message');
            $sessionId = $request->input('session_id', Str::random(30));

            Log::info('Chatbot request received', [
                'message' => $message,
                'session_id' => $sessionId
            ]);

            // Lưu tin nhắn của người dùng
           $chatMessage  = ChatbotMessage::create([
                'nguoidung_id' => Auth::check() ? Auth::id() : null,
                'session_id' => $sessionId,
                'message' => $message,
                'is_bot' => false
            ]);

            // Xử lý tin nhắn và tạo phản hồi
            $botResponse = $this->processMessage($message);

            // Lưu phản hồi của bot
            $botMessage = ChatbotMessage::create([
                'nguoidung_id' => null,
                'session_id' => $sessionId,
                'message' => $botResponse['message'],
                'is_bot' => true
            ]);

            return response()->json([
                'response' => $botResponse['message'],
                'session_id' => $sessionId,
                'products' => $botResponse['products'] ?? []
            ]);
        } catch (\Exception $e) {
            // Log lỗi
            Log::error('Chatbot error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'response' => 'Xin lỗi, có lỗi xảy ra. Vui lòng thử lại sau.',
                'error' => true
            ], 500);
        }
    }

    private function processMessage($message)
    {
        $message = strtolower($message);
        $response = [];

        // Xử lý tin nhắn chào hỏi
        if (Str::contains($message, ['xin chào', 'hello', 'hi', 'chào'])) {
            $response['message'] = 'Chào bạn! Tôi là trợ lý ảo của ENTERNALUX. Tôi có thể giúp bạn tìm kiếm sản phẩm hoặc trả lời các câu hỏi về shop. Bạn cần giúp đỡ gì?';
        }
        // Xử lý yêu cầu gợi ý sản phẩm
        else if (Str::contains($message, ['gợi ý', 'sản phẩm', 'khuyên', 'tư vấn', 'mua gì', 'nên mua'])) {
            $products = SanPham::where('trangthai', 1)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get(['id', 'tensanpham', 'hinhanh', 'dongia', 'tensanpham_slug as slug']);

            $response['message'] = 'Dưới đây là một số sản phẩm mới nhất của chúng tôi:';
            $response['products'] = $products;
        }
        // Xử lý tìm kiếm sản phẩm
        else if (Str::contains($message, ['tìm', 'kiếm', 'có', 'bán'])) {
            // Lấy từ khóa tìm kiếm
            $keywords = str_replace(['tìm', 'kiếm', 'có', 'bán'], '', $message);

            $products = SanPham::where('tensanpham', 'like', "%{$keywords}%")
                ->where('trangthai', 1)
                ->orderBy('luotxem', 'desc')
                ->limit(3)
                ->get(['id', 'tensanpham', 'hinhanh', 'dongia', 'tensanpham_slug as slug']);

            if ($products->count() > 0) {
                $response['message'] = "Tôi tìm thấy những sản phẩm sau đây phù hợp với '{$keywords}':";
                $response['products'] = $products;
            } else {
                $response['message'] = "Rất tiếc, tôi không tìm thấy sản phẩm nào phù hợp với '{$keywords}'. Bạn có thể mô tả rõ hơn về sản phẩm bạn đang tìm kiếm?";
            }
        }
        // Xử lý thông tin về đơn hàng
        else if (Str::contains($message, ['đơn hàng', 'giao hàng', 'vận chuyển', 'shipping', 'đặt hàng'])) {
            $response['message'] = 'Để kiểm tra thông tin đơn hàng, bạn vui lòng đăng nhập và truy cập vào mục "Đơn hàng của tôi". Thời gian giao hàng thông thường từ 2-5 ngày tùy khu vực.';
        }
        // Xử lý câu hỏi về giá cả
        else if (Str::contains($message, ['giá', 'bao nhiêu', 'tiền'])) {
            $response['message'] = 'Để biết giá sản phẩm cụ thể, bạn có thể cho tôi biết tên sản phẩm bạn quan tâm hoặc tìm kiếm sản phẩm trên trang web của chúng tôi.';
        }
        // Mặc định
        else {
            $response['message'] = 'Cảm ơn bạn đã liên hệ với ENTERNALUX. Bạn có thể hỏi tôi về sản phẩm, đơn hàng hoặc gợi ý mua sắm. Tôi sẽ cố gắng giúp đỡ bạn tốt nhất!';
        }
        return $response;
    }

    public function getHistory(Request $request)
    {
        try {
            $sessionId = $request->input('session_id');

            Log::info('Chatbot history request', [
                'session_id' => $sessionId
            ]);

            if (!$sessionId) {
                return response()->json([
                    'error' => 'Session ID is required'
                ], 400);
            }

            $messages = ChatbotMessage::where('session_id', $sessionId)
                ->orderBy('created_at', 'asc')
                ->get();

            return response()->json(['messages' => $messages]);
        } catch (\Exception $e) {
            Log::error('Error loading chat history: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error loading chat history',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
