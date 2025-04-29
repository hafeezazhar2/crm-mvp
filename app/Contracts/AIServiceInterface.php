<?php

namespace App\Contracts;

interface AIServiceInterface
{
    public function query(string $prompt, array $context = []): string;
}