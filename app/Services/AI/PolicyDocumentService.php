<?php

namespace App\Services\AI;

use App\Models\PolicyDocument;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PolicyDocumentService
{
    private string $pythonApiUrl;

    public function __construct()
    {
        $this->pythonApiUrl = config('services.ai.python_api_url');
    }

    public function queryPolicyDocuments(string $question, array $documentIds = []): string
    {
        try {
            $documents = empty($documentIds) 
                ? PolicyDocument::all() 
                : PolicyDocument::whereIn('id', $documentIds)->get();

            $response = Http::post("{$this->pythonApiUrl}/query-policies", [
                'question' => $question,
                'documents' => $documents->map(fn($doc) => [
                    'id' => $doc->id,
                    'title' => $doc->title,
                    'content' => $doc->content
                ])->toArray()
            ]);

            if ($response->successful()) {
                return $response->json('answer');
            }

            Log::error('AI Policy Query Failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return 'Sorry, I couldn\'t process your request at the moment.';
        } catch (\Exception $e) {
            Log::error('AI Policy Query Exception', ['error' => $e->getMessage()]);
            return 'An error occurred while processing your request.';
        }
    }
}
