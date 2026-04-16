<?php

use VincenzoRaco\Turnstile\DataObjects\TurnstileValidateDTO;

test('it creates DTO with default values when only token is provided', function () {
    $dto = new TurnstileValidateDTO('test-token');

    expect($dto->getToken())->toBe('test-token')
        ->and($dto->getIp())->toBeNull()
        ->and($dto->getConnectionTimeout())->toBe(10)
        ->and($dto->getResponseTimeout())->toBe(30);
});

test('it creates DTO with all parameters', function () {
    $dto = new TurnstileValidateDTO(
        token: 'my-token',
        ip: '192.168.1.1',
        connectionTimeout: 5,
        responseTimeout: 15,
    );

    expect($dto->getToken())->toBe('my-token')
        ->and($dto->getIp())->toBe('192.168.1.1')
        ->and($dto->getConnectionTimeout())->toBe(5)
        ->and($dto->getResponseTimeout())->toBe(15);
});

test('getToken returns the token string', function () {
    $dto = new TurnstileValidateDTO('abc123');

    expect($dto->getToken())->toBe('abc123');
});

test('getIp returns null when not provided', function () {
    $dto = new TurnstileValidateDTO('token');

    expect($dto->getIp())->toBeNull();
});

test('getIp returns the IP when provided', function () {
    $dto = new TurnstileValidateDTO('token', '10.0.0.1');

    expect($dto->getIp())->toBe('10.0.0.1');
});

test('getConnectionTimeout returns the connection timeout', function () {
    $dto = new TurnstileValidateDTO('token', connectionTimeout: 20);

    expect($dto->getConnectionTimeout())->toBe(20);
});

test('getResponseTimeout returns the response timeout', function () {
    $dto = new TurnstileValidateDTO('token', responseTimeout: 60);

    expect($dto->getResponseTimeout())->toBe(60);
});
