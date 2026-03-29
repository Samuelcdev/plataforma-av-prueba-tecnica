<?php

namespace App\Infrastructure\Logging;

use Illuminate\Support\Facades\Log;

class Logger implements LoggerInterface
{
    public function debug(string $message, array $context = []): void
    {
        Log::debug($message, [...$context, 'mdc' => MDCContext::getInstance()->all()]);
    }

    public function info(string $message, array $context = []): void
    {
        Log::info($message, [...$context, 'mdc' => MDCContext::getInstance()->all()]);
    }

    public function warning(string $message, array $context = []): void
    {
        Log::warning($message, [...$context, 'mdc' => MDCContext::getInstance()->all()]);
    }

    public function error(string $message, array $context = []): void
    {
        Log::error($message, [...$context, 'mdc' => MDCContext::getInstance()->all()]);
    }

    public function critical(string $message, array $context = []): void
    {
        Log::critical($message, [...$context, 'mdc' => MDCContext::getInstance()->all()]);
    }
}
