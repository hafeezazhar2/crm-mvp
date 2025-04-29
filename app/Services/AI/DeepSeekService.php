<?php

namespace App\Services\AI;

use App\Contracts\AIServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeepSeekService implements AIServiceInterface
{
    private string $apiKey;
    private string $apiUrl = 'https://api.deepseek.com/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = config('services.deepseek.api_key');
    }

    public function query(string $prompt, array $context = []): string
    {
        try {
            $messages = [
                ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                ['role' => 'user', 'content' => $prompt]
            ];

            if (!empty($context)) {
                array_unshift($messages, ['role' => 'system', 'content' => implode("\n", $context)]);
            }

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ])->post($this->apiUrl, [
                'model' => 'deepseek-chat',
                'messages' => $messages,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content');
            }

            Log::error('DeepSeek API Error', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return 'Sorry, I couldn\'t process your request at the moment.';
        } catch (\Exception $e) {
            Log::error('DeepSeek Service Exception', ['error' => $e->getMessage()]);
            return 'An error occurred while processing your request.';
        }
    }
}