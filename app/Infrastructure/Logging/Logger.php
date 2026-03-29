<?php

namespace App\Infrastructure\Logging;

use Illuminate\Support\Facades\Log;

class Logger implements LoggerInterface
{
    private function mergeContext(array $context): array
    {
        return array_merge($context, MDCContext::getInstance()->all());
    }

    public function debug(string $message, array $context = []): void
    {
        Log::channel('debug')->debug($message, $this->mergeContext($context));
    }

    public function info(string $message, array $context = []): void
    {
        Log::channel('info')->info($message, $this->mergeContext($context));
    }

    public function warning(string $message, array $context = []): void
    {
        Log::channel('warning')->warning($message, $this->mergeContext($context));
    }

    public function error(string $message, array $context = []): void
    {
        Log::channel('error')->error($message, $this->mergeContext($context));
    }

    public function critical(string $message, array $context = []): void
    {
        Log::channel('critical')->critical($message, $this->mergeContext($context));
    }
}
