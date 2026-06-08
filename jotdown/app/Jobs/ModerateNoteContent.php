namespace App\Jobs;

use App\Models\Note;
use App\Models\Report;
use App\Services\GeminiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ModerateNoteContent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $note;
    protected $report;

    // Có thể truyền Note (Pre-screening) hoặc Report (Smart Triage)
    public function __construct(Note $note, Report $report = null)
    {
        $this->note = $note;
        $this->report = $report;
    }

    public function handle(GeminiService $gemini)
    {
        $context = $this->report 
            ? "Lý do bị người dùng báo cáo: {$this->report->reason}" 
            : "Chế độ: Kiểm duyệt chủ động trước khi public.";

        $prompt = "
        Bạn là hệ thống kiểm duyệt. Phân tích nội dung sau:
        Tiêu đề: {$this->note->title}
        Nội dung: {$this->note->content}
        Ngữ cảnh: {$context}

        Trả về JSON:
        {
            \"is_safe\": boolean,
            \"severity_score\": integer (0-100),
            \"reason\": \"Lý do vi phạm ngắn gọn nếu có, an toàn thì để null\"
        }
        ";

        $result = $gemini->call($prompt);

        if ($result) {
            if ($this->report) {
                // Xử lý Report Triage cho Admin
                $this->report->update([
                    'severity_score' => $result['severity_score'],
                    'ai_summary' => $result['reason']
                ]);
            } else {
                // Pre-screening khi người dùng vừa public note
                if (!$result['is_safe']) {
                    $this->note->update([
                        'is_public' => false, // Kéo về private ngay lập tức
                    ]);
                    
                    // TODO: Insert record vào bảng notifications để báo cho user
                }
            }
        }
    }
}