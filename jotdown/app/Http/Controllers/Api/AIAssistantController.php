<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GeminiService;

class AIAssistantController extends Controller
{
    public function summarize(Request $request, GeminiService $gemini)
    {
        // Kiểm tra quyền Premium theo đồ án
        if (!$request->user()->is_premium) {
            return response()->json(['message' => 'Tính năng AI chỉ dành cho gói Premium.'], 403);
        }

        $request->validate(['content' => 'required|string']);

        $prompt = "Tóm tắt nội dung sau thành các gạch đầu dòng ngắn gọn, giữ nguyên ngôn ngữ gốc:\n\n" . $request->input('content');
        
        // Gọi AI, expectJson = false vì ta chỉ cần text thuần
        $summary = $gemini->call($prompt, false);

        if (!$summary) {
            return response()->json(['message' => 'Lỗi kết nối AI, vui lòng thử lại.'], 500);
        }

        return response()->json(['summary' => $summary]);
    }
    
    public function fixGrammar(Request $request, GeminiService $gemini)
    {
        if (!$request->user()->is_premium) {
            return response()->json(['message' => 'Tính năng AI chỉ dành cho gói Premium.'], 403);
        }

        $request->validate(['content' => 'required|string']);

        $prompt = "Sửa các lỗi chính tả và hành văn trong đoạn sau, chỉ trả về nội dung đã sửa, không thêm giải thích:\n\n" . $request->input('content');
        
        $fixedText = $gemini->call($prompt, false);

        return response()->json(['fixed_content' => $fixedText]);
    }
    public function testModeration(Request $request, GeminiService $gemini)
{
    $request->validate(['content' => 'required|string']);

    // Viết prompt ép AI đóng vai trò kiểm duyệt và trả về định dạng chuẩn JSON
    $prompt = "Bạn là hệ thống kiểm duyệt tự động. Phân tích đoạn văn sau xem có chứa từ ngữ xúc phạm, chửi thề, độc hại hay spam không. Chỉ trả về một chuỗi JSON thuần túy (không có markdown) gồm 2 thuộc tính: 'is_safe' (true hoặc false) và 'reason' (lý do cụ thể):\n\n" . $request->input('content');

    $result = $gemini->call($prompt, false);

    // Xóa bỏ các ký tự thừa (nếu AI lỡ bọc code markdown ```json) để hiển thị đẹp trên Thunder Client
    $cleanResult = str_replace(['```json', '```'], '', $result);

    return response($cleanResult)->header('Content-Type', 'application/json');
}
}