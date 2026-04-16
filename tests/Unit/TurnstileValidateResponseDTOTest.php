<?php

use VincenzoRaco\Turnstile\DataObjects\TurnstileValidateResponseDTO;
use VincenzoRaco\Turnstile\Enums\TurnstileError;

function createResponseDTO(
    bool $success = true,
    string $challengeTimestamp = '2024-01-15T12:00:00Z',
    string $hostname = 'example.com',
    array $errors = [],
    string $action = 'login',
    string $customerData = 'test-data',
    ?string $ephemeralId = null,
): TurnstileValidateResponseDTO {
    return new TurnstileValidateResponseDTO(
        success: $success,
        challengeTimestamp: $challengeTimestamp,
        hostname: $hostname,
        errors: $errors,
        action: $action,
        customerData: $customerData,
        ephemeralId: $ephemeralId,
    );
}

test('isSuccess returns true when success is true', function () {
    $dto = createResponseDTO(success: true);

    expect($dto->isSuccess())->toBeTrue();
});

test('isSuccess returns false when success is false', function () {
    $dto = createResponseDTO(success: false);

    expect($dto->isSuccess())->toBeFalse();
});

test('isFailure returns true when success is false', function () {
    $dto = createResponseDTO(success: false);

    expect($dto->isFailure())->toBeTrue();
});

test('isFailure returns false when success is true', function () {
    $dto = createResponseDTO(success: true);

    expect($dto->isFailure())->toBeFalse();
});

test('isSuccess and isFailure are always opposite', function (bool $success) {
    $dto = createResponseDTO(success: $success);

    expect($dto->isSuccess())->toBe(! $dto->isFailure());
})->with([true, false]);

test('getChallengeDatetime returns a DateTimeImmutable instance', function () {
    $dto = createResponseDTO(challengeTimestamp: '2024-01-15T12:00:00Z');

    expect($dto->getChallengeDatetime())->toBeInstanceOf(DateTimeImmutable::class);
});

test('getChallengeDatetime returns the correct date', function () {
    $dto = createResponseDTO(challengeTimestamp: '2024-06-20T15:30:00Z');

    $datetime = $dto->getChallengeDatetime();

    expect($datetime->format('Y'))->toBe('2024')
        ->and($datetime->format('m'))->toBe('06')
        ->and($datetime->format('d'))->toBe('20')
        ->and($datetime->format('H'))->toBe('15')
        ->and($datetime->format('i'))->toBe('30');
});

test('getHostname returns the hostname', function () {
    $dto = createResponseDTO(hostname: 'my-site.org');

    expect($dto->getHostname())->toBe('my-site.org');
});

test('getErrors maps string error codes to TurnstileError enum cases', function () {
    $dto = createResponseDTO(errors: ['missing-input-secret', 'bad-request']);

    $errors = $dto->getErrors();

    expect($errors)->toHaveCount(2)
        ->and($errors[0])->toBe(TurnstileError::MISSING_INPUT_SECRET)
        ->and($errors[1])->toBe(TurnstileError::BAD_REQUEST);
});

test('getErrors returns empty array when no errors', function () {
    $dto = createResponseDTO(errors: []);

    expect($dto->getErrors())->toBeEmpty()
        ->and($dto->getErrors())->toBeArray();
});

test('getAction returns the action string', function () {
    $dto = createResponseDTO(action: 'signup');

    expect($dto->getAction())->toBe('signup');
});

test('getCustomerData returns the customer data', function () {
    $dto = createResponseDTO(customerData: 'user-123');

    expect($dto->getCustomerData())->toBe('user-123');
});

test('getEphemeralId returns null when null', function () {
    $dto = createResponseDTO(ephemeralId: null);

    expect($dto->getEphemeralId())->toBeNull();
});

test('getEphemeralId returns string when provided', function () {
    $dto = createResponseDTO(ephemeralId: 'eph-abc-123');

    expect($dto->getEphemeralId())->toBe('eph-abc-123');
});
