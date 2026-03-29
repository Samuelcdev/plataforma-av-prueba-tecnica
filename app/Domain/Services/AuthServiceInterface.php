<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Application\DTO\Auth\AuthPayloadDto;
use App\Application\DTO\Auth\CurrentUserDto;
use App\Application\DTO\Auth\LoginDto;
use App\Domain\Entities\UserEntity;

interface AuthServiceInterface
{
    public function login(LoginDto $dto): AuthPayloadDto;

    public function logout(CurrentUserDto $dto): void;

    public function me(CurrentUserDto $dto): UserEntity;
}
