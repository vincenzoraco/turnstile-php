<?php

namespace VincenzoRaco\Turnstile\Enums;

enum TurnstileError: string
{
    case MISSING_INPUT_SECRET = 'missing-input-secret';
    case INVALID_INPUT_SECRET = 'invalid-input-secret';
    case MISSING_INPUT_RESPONSE = 'missing-input-response';
    case INVALID_INPUT_RESPONSE = 'invalid-input-response';
    case BAD_REQUEST = 'bad-request';
    case TIMEOUT_OR_DUPLICATE = 'timeout-or-duplicate';
    case INTERNAL_ERROR = 'internal-error';
    case UNDOCUMENTED = 'undocumented';

    public function getDescription(): string
    {
        return match ($this) {
            self::MISSING_INPUT_SECRET => 'The secret parameter was not passed.',
            self::INVALID_INPUT_SECRET => 'The secret parameter was invalid, did not exist, or is a testing secret key with a non-testing response.',
            self::MISSING_INPUT_RESPONSE => 'The response parameter (token) was not passed.',
            self::INVALID_INPUT_RESPONSE => 'The response parameter (token) is invalid or has expired. Most of the time, this means a fake token has been used. If the error persists, contact customer support',
            self::BAD_REQUEST => 'The request was rejected because it was malformed.',
            self::TIMEOUT_OR_DUPLICATE => 'The response parameter (token) has already been validated before. This means that the token was issued five minutes ago and is no longer valid, or it was already redeemed.',
            self::INTERNAL_ERROR => 'An internal error happened while validating the response. The request can be retried.',
            self::UNDOCUMENTED => 'An error occurred.'
        };
    }
}
