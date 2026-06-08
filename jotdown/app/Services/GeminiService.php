<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected $apiKey;
    protected $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    public function call($prompt, $expectJson = true)
    {
        $payload = [
            'contents' => [['parts' => [['text' => $prompt]]]]
        ];

        // Ép AI trả về JSON chuẩn xác
        if ($expectJson) {
            $payload['generationConfig'] = ['responseMimeType' => 'application/json'];
        }

        try {
            $response = Http::timeout(15)->post($this->apiUrl . '?key=' . $this->apiKey, $payload);

            if ($response->successful()) {
                $text = $response->json('candidates.0.content.parts.0.text');
                return $expectJson ? json_decode($text, true) : $text;
            }
            Log::error('Gemini API Error: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Gemini Connection Error: ' . $e->getMessage());
        }

        return null;
    }
}