<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth;

use App\Application\Security\PasswordHasherInterface;
use Illuminate\Contracts\Hashing\Hasher;

final class LaravelPasswordHasher implements PasswordHasherInterface
{
    public function __construct(private Hasher $hasher)
    {
    }

    public function hash(string $plainPassword): string
    {
        return $this->hasher->make($plainPassword);
    }

    public function verify(string $plainPassword, string $hashedPassword): bool
    {
        return $this->hasher->check($plainPassword, $hashedPassword);
    }
}
