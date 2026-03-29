<?php

use App\Domain\Exceptions\ConflictException;
use App\Domain\Exceptions\DomainException;
use App\Domain\Exceptions\EntityNotFoundException;
use App\Domain\Exceptions\InvalidCredentialsException;
use App\Domain\Exceptions\UnauthorizedDomainException;
use App\Domain\Exceptions\ValidationException;

test('domain exception extends runtime exception and exposes context', function () {
    $exception = new DomainException(
        'Domain failed',
        ['entity' => 'User', 'id' => 'u-1'],
        'DOMAIN_FAILED',
    );

    expect($exception)
        ->toBeInstanceOf(\RuntimeException::class)
        ->and($exception->getMessage())->toBe('Domain failed')
        ->and($exception->getContext())->toBe(['entity' => 'User', 'id' => 'u-1'])
        ->and($exception->getErrorCode())->toBe('DOMAIN_FAILED');
});

test('validation exception normalizes errors and exposes them', function () {
    $exception = ValidationException::withErrors([
        'email' => 'Email is required',
        'password' => ['Too short', 'Must include a symbol'],
    ]);

    expect($exception->getErrors())
        ->toBe([
            'email' => ['Email is required'],
            'password' => ['Too short', 'Must include a symbol'],
        ])
        ->and($exception->getErrorCode())->toBe('VALIDATION_ERROR')
        ->and($exception->getContext())->toHaveKey('errors');
});

test('entity not found exception for builds message and context', function () {
    $exception = EntityNotFoundException::for('User', '123');

    expect($exception->getMessage())->toBe('User with id [123] was not found.')
        ->and($exception->getErrorCode())->toBe('ENTITY_NOT_FOUND')
        ->and($exception->getContext())->toBe(['entity' => 'User', 'id' => '123']);
});

test('invalid credentials default builds standardized exception', function () {
    $exception = InvalidCredentialsException::default();

    expect($exception->getMessage())->toBe('Invalid credentials.')
        ->and($exception->getErrorCode())->toBe('INVALID_CREDENTIALS');
});

test('conflict and unauthorized factories keep reason and context', function () {
    $conflict = ConflictException::because('Order already assigned.', ['order_id' => 'o-1']);
    $unauthorized = UnauthorizedDomainException::accessDenied('Only admins can assign orders.', ['role' => 'hotel']);

    expect($conflict->getMessage())->toBe('Order already assigned.')
        ->and($conflict->getContext())->toBe(['order_id' => 'o-1'])
        ->and($conflict->getErrorCode())->toBe('DOMAIN_CONFLICT');

    expect($unauthorized->getMessage())->toBe('Only admins can assign orders.')
        ->and($unauthorized->getContext())->toBe(['role' => 'hotel'])
        ->and($unauthorized->getErrorCode())->toBe('UNAUTHORIZED_ACTION');
});
