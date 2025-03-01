<?php

namespace VincenzoRaco\Turnstile\DataObjects;

use Carbon\CarbonImmutable;
use VincenzoRaco\Turnstile\Enums\TurnstileError;

readonly class TurnstileValidateResponseDTO
{
    /**
     * @param  string[]  $errors
     */
    public function __construct(
        private bool $success,
        private string $challengeTimestamp,
        private string $hostname,
        private array $errors,
        private string $action,
        private string $customerData,
        private ?string $ephemeralId,
    ) {}

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function isFailure(): bool
    {
        return ! $this->isSuccess();
    }

    public function getChallengeDatetime(): CarbonImmutable
    {
        return CarbonImmutable::parse($this->challengeTimestamp);
    }

    public function getHostname(): string
    {
        return $this->hostname;
    }

    /**
     * @return TurnstileError[]
     */
    public function getErrors(): array
    {
        return array_map(fn (string $errorCode): TurnstileError => TurnstileError::from($errorCode), $this->errors);
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getCustomerData(): string
    {
        return $this->customerData;
    }

    public function getEphemeralId(): ?string
    {
        return $this->ephemeralId;
    }
}
