<?php

declare(strict_types=1);

namespace App\Presentation\Http\Controllers;

use App\Application\DTO\Hotel\CreateHotelDto;
use App\Application\DTO\Hotel\GetHotelsDto;
use App\Application\DTO\Hotel\UpdateHotelDto;
use App\Application\Services\HotelService;
use App\Presentation\Http\Requests\GetHotelsRequest;
use App\Presentation\Http\Requests\StoreHotelRequest;
use App\Presentation\Http\Requests\UpdateHotelRequest;
use App\Presentation\Http\Resources\HotelResource;
use App\Presentation\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;

final class HotelController extends Controller
{
    public function __construct(private HotelService $hotelService)
    {
    }

    public function index(GetHotelsRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $dto = new GetHotelsDto(
            search: isset($payload['search']) ? trim((string) $payload['search']) : null,
        );

        $hotels = $this->hotelService->index($dto);

        return ApiResponse::success(
            data: array_map(
                static fn ($hotel): array => HotelResource::toArray($hotel),
                $hotels,
            ),
            message: 'Hotels retrieved',
            status: 200,
        );
    }

    public function show(string $id): JsonResponse
    {
        $hotel = $this->hotelService->show($id);

        return ApiResponse::success(
            data: HotelResource::toArray($hotel),
            message: 'Hotel retrieved',
            status: 200,
        );
    }

    public function store(StoreHotelRequest $request): JsonResponse
    {
        $payload = $request->validated();

        $dto = new CreateHotelDto(
            username: (string) $payload['username'],
            nit: (string) $payload['nit'],
            documentType: (string) $payload['document_type'],
            name: (string) $payload['name'],
            phone: isset($payload['phone']) ? (string) $payload['phone'] : null,
            address: isset($payload['address']) ? (string) $payload['address'] : null,
        );

        $hotel = $this->hotelService->store($dto);

        return ApiResponse::success(
            data: HotelResource::toArray($hotel),
            message: 'Hotel created',
            status: 201,
        );
    }

    public function update(UpdateHotelRequest $request, string $id): JsonResponse
    {
        $payload = $request->validated();

        $dto = new UpdateHotelDto(
            id: $id,
            nit: (string) $payload['nit'],
            documentType: (string) $payload['document_type'],
            name: (string) $payload['name'],
            phone: isset($payload['phone']) ? (string) $payload['phone'] : null,
            address: isset($payload['address']) ? (string) $payload['address'] : null,
        );

        $hotel = $this->hotelService->update($dto);

        return ApiResponse::success(
            data: HotelResource::toArray($hotel),
            message: 'Hotel updated',
            status: 200,
        );
    }

    public function destroy(string $id): JsonResponse
    {
        $this->hotelService->destroy($id);

        return ApiResponse::success(
            data: null,
            message: 'Hotel deleted',
            status: 200,
        );
    }
}
