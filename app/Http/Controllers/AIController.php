<?php

namespace App\Http\Controllers;

use App\Services\AI\AIServiceFactory;
use App\Services\AI\PolicyDocumentService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AIController extends Controller
{
    public function policyAssistant(): Response
    {
        return Inertia::render('AI/PolicyAssistant');
    }

    public function queryPolicy(Request $request, PolicyDocumentService $policyService)
    {
        $request->validate([
            'question' => 'required|string|max:1000',
            'ai_service' => 'sometimes|in:openai,deepseek'
        ]);

        $aiService = $request->input('ai_service', 'openai');
        $question = $request->input('question');

        // Get answer from policy documents first
        $policyAnswer = $policyService->queryPolicyDocuments($question);

        // If we got a good answer from policies, return that
        if (strlen($policyAnswer) > 50 && !str_contains(strtolower($policyAnswer), 'sorry')) {
            return response()->json([
                'answer' => $policyAnswer,
                'source' => 'policy_documents'
            ]);
        }

        // Otherwise fall back to general AI
        $ai = AIServiceFactory::create($aiService);
        $answer = $ai->query($question);

        return response()->json([
            'answer' => $answer,
            'source' => $aiService
        ]);
    }
}