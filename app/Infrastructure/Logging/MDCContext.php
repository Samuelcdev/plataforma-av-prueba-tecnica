<?php

namespace App\Infrastructure\Logging;

use Carbon\Carbon;
use Illuminate\Support\Str;

class MDCContext
{
    private static ?self $instance = null;
    private array $data = [];

    private function __construct()
    {
        $this->data = [
            'request_id' => Str::uuid()->toString(),
            'timestamp' => Carbon::now()->toIso8601String(),
        ];
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data[$key] ?? $default;
    }

    public function all(): array
    {
        return $this->data;
    }

    public function clear(): void
    {
        $this->data = [
            'request_id' => Str::uuid()->toString(),
            'timestamp' => Carbon::now()->toIso8601String(),
        ];
    }

    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }
}
