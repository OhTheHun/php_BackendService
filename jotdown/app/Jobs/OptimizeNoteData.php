namespace App\Jobs;

use App\Models\Note;
use App\Models\Tag;
use App\Services\GeminiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class OptimizeNoteData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $note;

    public function __construct(Note $note)
    {
        $this->note = $note;
    }

    public function handle(GeminiService $gemini)
    {
        $prompt = "
        Phân tích ghi chú sau:
        Tiêu đề: {$this->note->title}
        Nội dung: {$this->note->content}
        
        Trả về JSON với 2 key:
        - 'tags': Mảng tối đa 5 từ khóa phân loại ngắn gọn (1-2 từ).
        - 'search_keywords': Một chuỗi chứa các danh từ/động từ chính quan trọng nhất, cách nhau bằng dấu phẩy.
        ";

        $result = $gemini->call($prompt);

        if ($result) {
            // Cập nhật search_keywords
            if (isset($result['search_keywords'])) {
                $this->note->update(['search_keywords' => $result['search_keywords']]);
            }

            // Cập nhật Tags
            if (isset($result['tags']) && is_array($result['tags'])) {
                $tagIds = [];
                foreach ($result['tags'] as $tagName) {
                    $tag = Tag::firstOrCreate(['name' => trim($tagName)]);
                    $tagIds[] = $tag->id;
                }
                $this->note->tags()->syncWithoutDetaching($tagIds);
            }
        }
    }
}