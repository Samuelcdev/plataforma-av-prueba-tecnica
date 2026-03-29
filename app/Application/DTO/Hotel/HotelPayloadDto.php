<?php

declare(strict_types=1);

namespace App\Application\DTO\Hotel;

use App\Domain\Entities\HotelEntity;
use App\Domain\Entities\UserEntity;

final class HotelPayloadDto
{
    public function __construct(
        private HotelEntity $hotel,
        private UserEntity $user,
    ) {
    }

    public function getHotel(): HotelEntity
    {
        return $this->hotel;
    }

    public function getUser(): UserEntity
    {
        return $this->user;
    }
}
