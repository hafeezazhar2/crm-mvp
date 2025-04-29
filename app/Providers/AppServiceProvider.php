<?php

namespace App\Providers;

use App\Contracts\AIServiceInterface;
use App\Services\AI\DeepSeekService;
use App\Services\AI\OpenAIService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AIServiceInterface::class, function () {
            return match (config('services.ai.default')) {
                'deepseek' => new DeepSeekService(),
                default => new OpenAIService(),
            };
        });
    }

    public function boot(): void
    {
        //
    }
}