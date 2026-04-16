<?php

use VincenzoRaco\Turnstile\Enums\TurnstileError;

test('from() returns correct case for each known error code', function (string $code, TurnstileError $expected) {
    expect(TurnstileError::from($code))->toBe($expected);
})->with([
    ['missing-input-secret', TurnstileError::MISSING_INPUT_SECRET],
    ['invalid-input-secret', TurnstileError::INVALID_INPUT_SECRET],
    ['missing-input-response', TurnstileError::MISSING_INPUT_RESPONSE],
    ['invalid-input-response', TurnstileError::INVALID_INPUT_RESPONSE],
    ['bad-request', TurnstileError::BAD_REQUEST],
    ['timeout-or-duplicate', TurnstileError::TIMEOUT_OR_DUPLICATE],
    ['internal-error', TurnstileError::INTERNAL_ERROR],
    ['undocumented', TurnstileError::UNDOCUMENTED],
]);

test('getDescription returns a non-empty string for every case', function (TurnstileError $case) {
    expect($case->getDescription())->toBeString()->not->toBeEmpty();
})->with(TurnstileError::cases());

test('each case has a unique description', function () {
    $descriptions = array_map(
        fn (TurnstileError $case) => $case->getDescription(),
        TurnstileError::cases(),
    );

    expect($descriptions)->toHaveCount(count(array_unique($descriptions)));
});
