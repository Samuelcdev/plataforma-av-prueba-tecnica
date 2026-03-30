<?php

declare(strict_types=1);

use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;

beforeEach(function (): void {
    $this->seed(DatabaseSeeder::class);
});

function loginForOrders(TestCase $testCase, string $username, string $password, string $deviceName): string
{
    $response = $testCase->postJson('/api/v1/auth/login', [
        'username' => $username,
        'password' => $password,
        'device_name' => $deviceName,
    ]);

    $response->assertOk();

    return (string) $response->json('data.token');
}

function orderPayload(string $name, string $startDate, string $endDate): array
{
    return [
        'name' => $name,
        'service_type' => 'Evento corporativo',
        'start_date' => $startDate,
        'end_date' => $endDate,
        'items' => [
            [
                'item_id' => '70000000-0000-0000-0000-000000000001',
                'quantity' => 2,
            ],
        ],
    ];
}

test('hotel can execute orders crud flow', function (): void {
    $hotelToken = loginForOrders($this, 'hotel.andes', 'Hotel123!', 'test-orders-hotel');

    $indexResponse = $this->withToken($hotelToken)->getJson('/api/v1/orders');
    $indexResponse
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Orders retrieved')
        ->assertJsonStructure(['success', 'message', 'total', 'data']);

    $createResponse = $this->withToken($hotelToken)->postJson(
        '/api/v1/orders',
        orderPayload('Evento QA 1', '2026-04-20 08:00:00', '2026-04-20 12:00:00'),
    );
    $createResponse
        ->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Order created')
        ->assertJsonPath('data.name', 'Evento QA 1')
        ->assertJsonPath('data.status', 'active');

    $orderId = (string) $createResponse->json('data.id');

    $showResponse = $this->withToken($hotelToken)->getJson("/api/v1/orders/{$orderId}");
    $showResponse
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Order retrieved')
        ->assertJsonPath('data.id', $orderId);

    $updateResponse = $this->withToken($hotelToken)->putJson(
        "/api/v1/orders/{$orderId}",
        orderPayload('Evento QA 1 actualizado', '2026-04-20 09:00:00', '2026-04-20 13:00:00'),
    );
    $updateResponse
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Order updated')
        ->assertJsonPath('data.name', 'Evento QA 1 actualizado');

    $cancelResponse = $this->withToken($hotelToken)->postJson("/api/v1/orders/{$orderId}/cancel");
    $cancelResponse
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Order cancelled')
        ->assertJsonPath('data.status', 'cancelled');
});

test('store order validates payload', function (): void {
    $hotelToken = loginForOrders($this, 'hotel.andes', 'Hotel123!', 'test-orders-validation');

    $response = $this->withToken($hotelToken)->postJson('/api/v1/orders', [
        'name' => 'Evento invalido',
        'service_type' => 'Tipo',
        'start_date' => '2026-04-20 10:00:00',
        'end_date' => '2026-04-20 09:00:00',
        'items' => [],
    ]);

    $response
        ->assertUnprocessable()
        ->assertJsonPath('success', false);
});

test('admin cannot assign operative if it overlaps with another event', function (): void {
    $hotelToken = loginForOrders($this, 'hotel.andes', 'Hotel123!', 'test-orders-overlap-hotel');
    $adminToken = loginForOrders($this, 'admin.super', 'Admin123!', 'test-orders-overlap-admin');

    $firstOrderResponse = $this->withToken($hotelToken)->postJson(
        '/api/v1/orders',
        orderPayload('Evento Asignacion A', '2026-04-22 08:00:00', '2026-04-22 12:00:00'),
    );
    $firstOrderResponse->assertCreated();
    $firstOrderId = (string) $firstOrderResponse->json('data.id');

    $secondOrderResponse = $this->withToken($hotelToken)->postJson(
        '/api/v1/orders',
        orderPayload('Evento Asignacion B', '2026-04-22 10:00:00', '2026-04-22 13:00:00'),
    );
    $secondOrderResponse->assertCreated();
    $secondOrderId = (string) $secondOrderResponse->json('data.id');

    $operativeId = '50000000-0000-0000-0000-000000000001';

    $assignFirstResponse = $this->withToken($adminToken)->postJson(
        "/api/v1/orders/{$firstOrderId}/assign-operatives",
        ['operative_ids' => [$operativeId]],
    );
    $assignFirstResponse
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Operatives assigned');

    $assignSecondResponse = $this->withToken($adminToken)->postJson(
        "/api/v1/orders/{$secondOrderId}/assign-operatives",
        ['operative_ids' => [$operativeId]],
    );

    $assignSecondResponse
        ->assertStatus(400)
        ->assertJsonPath('success', false);

    expect((string) $assignSecondResponse->json('message'))
        ->toContain('ya esta asignado para otro evento');
});
