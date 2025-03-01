<?php

namespace VincenzoRaco\Turnstile\DataObjects;

readonly class TurnstileValidateDTO
{
    public function __construct(
        private string $token,
        private ?string $ip = null,
        private int $connectionTimeout = 10,
        private int $responseTimeout = 30,
    ) {}

    public function getToken(): string
    {
        return $this->token;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function getConnectionTimeout(): int
    {
        return $this->connectionTimeout;
    }

    public function getResponseTimeout(): int
    {
        return $this->responseTimeout;
    }
}
