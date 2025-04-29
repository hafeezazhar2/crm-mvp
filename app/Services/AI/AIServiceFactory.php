<?php

namespace App\Services\AI;

use App\Contracts\AIServiceInterface;
use App\Services\AI\DeepSeekService;
use App\Services\AI\OpenAIService;
use InvalidArgumentException;

class AIServiceFactory
{
    public static function create(string $service): AIServiceInterface
    {
        return match ($service) {
            'openai' => app(OpenAIService::class),
            'deepseek' => app(DeepSeekService::class),
            default => throw new InvalidArgumentException("Unsupported AI service: $service"),
        };
    }
}