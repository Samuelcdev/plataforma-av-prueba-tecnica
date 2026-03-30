<?php

declare(strict_types=1);

use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;

beforeEach(function (): void {
    $this->seed(DatabaseSeeder::class);
});

function loginForHotels(TestCase $testCase, string $username = 'admin.super', string $password = 'Admin123!'): string
{
    $response = $testCase->postJson('/api/v1/auth/login', [
        'username' => $username,
        'password' => $password,
        'device_name' => 'test-hotels',
    ]);

    $response->assertOk();

    return (string) $response->json('data.token');
}

test('admin can execute hotels crud flow', function (): void {
    $token = loginForHotels($this);

    $indexResponse = $this->withToken($token)->getJson('/api/v1/hotels');
    $indexResponse
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Hotels retrieved')
        ->assertJsonStructure(['success', 'message', 'total', 'data']);

    $createPayload = [
        'username' => 'hotel.test.new',
        'nit' => '900999001-9',
        'document_type' => 'NIT',
        'name' => 'Hotel Test Nuevo',
        'phone' => '+5712345678',
        'address' => 'Calle 123 #45-67',
    ];

    $createResponse = $this->withToken($token)->postJson('/api/v1/hotels', $createPayload);
    $createResponse
        ->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Hotel created')
        ->assertJsonPath('data.name', 'Hotel Test Nuevo')
        ->assertJsonPath('data.nit', '900999001-9');

    $hotelId = (string) $createResponse->json('data.id');

    $showResponse = $this->withToken($token)->getJson("/api/v1/hotels/{$hotelId}");
    $showResponse
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Hotel retrieved')
        ->assertJsonPath('data.id', $hotelId);

    $updatePayload = [
        'nit' => '900999001-9',
        'document_type' => 'NIT',
        'name' => 'Hotel Test Actualizado',
        'phone' => '+5799999999',
        'address' => 'Av. Siempre Viva 742',
    ];

    $updateResponse = $this->withToken($token)->putJson("/api/v1/hotels/{$hotelId}", $updatePayload);
    $updateResponse
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Hotel updated')
        ->assertJsonPath('data.name', 'Hotel Test Actualizado');

    $deleteResponse = $this->withToken($token)->deleteJson("/api/v1/hotels/{$hotelId}");
    $deleteResponse
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Hotel deleted');

    $this->withToken($token)
        ->getJson("/api/v1/hotels/{$hotelId}")
        ->assertNotFound()
        ->assertJsonPath('success', false);
});

test('store hotel validates required fields', function (): void {
    $token = loginForHotels($this);

    $response = $this->withToken($token)->postJson('/api/v1/hotels', [
        'username' => 'hotel.invalid',
        'nit' => '900999002-0',
        'document_type' => 'NIT',
        // missing name
    ]);

    $response
        ->assertUnprocessable()
        ->assertJsonPath('success', false);
});
