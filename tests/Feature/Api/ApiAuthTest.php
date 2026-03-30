<?php

declare(strict_types=1);

use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;

beforeEach(function (): void {
    $this->seed(DatabaseSeeder::class);
});

function apiLogin(TestCase $testCase, string $username, string $password): string
{
    $response = $testCase->postJson('/api/v1/auth/login', [
        'username' => $username,
        'password' => $password,
        'device_name' => 'test-suite',
    ]);

    $response->assertOk();

    return (string) $response->json('data.token');
}

test('login succeeds with valid credentials', function (): void {
    $response = $this->postJson('/api/v1/auth/login', [
        'username' => 'admin.super',
        'password' => 'Admin123!',
        'device_name' => 'test-suite',
    ]);

    $response
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Login successful')
        ->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'token',
                'token_type',
                'user' => ['id', 'username', 'role_id'],
            ],
        ]);
});

test('login fails with invalid credentials', function (): void {
    $response = $this->postJson('/api/v1/auth/login', [
        'username' => 'admin.super',
        'password' => 'wrong-password',
        'device_name' => 'test-suite',
    ]);

    $response
        ->assertUnauthorized()
        ->assertJsonPath('success', false)
        ->assertJsonPath('message', 'Invalid credentials.');
});

test('me returns authenticated user', function (): void {
    $token = apiLogin($this, 'admin.super', 'Admin123!');

    $response = $this->withToken($token)->getJson('/api/v1/auth/me');

    $response
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', 'Authenticated user retrieved')
        ->assertJsonPath('data.username', 'admin.super');
});

test('me fails without token', function (): void {
    $response = $this->getJson('/api/v1/auth/me');

    $response
        ->assertUnauthorized()
        ->assertJsonPath('success', false)
        ->assertJsonPath('message', 'Unauthenticated.');
});
