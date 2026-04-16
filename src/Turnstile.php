<?php

declare(strict_types=1);

namespace VincenzoRaco\Turnstile;

use VincenzoRaco\Turnstile\DataObjects\TurnstileValidateDTO;
use VincenzoRaco\Turnstile\DataObjects\TurnstileValidateResponseDTO;
use VincenzoRaco\Turnstile\Exceptions\ConnectionException;
use VincenzoRaco\Turnstile\Exceptions\InvalidResponseException;

class Turnstile
{
    private string $turnstileUrl = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    public function __construct(
        private readonly string $secret,
    ) {}

    public function validate(
        TurnstileValidateDTO $data,
    ): TurnstileValidateResponseDTO {
        /** @var array{success: bool, 'error-codes': string[], metadata?: array{ephemeral_id?: string}, challenge_ts?: string, hostname?: string, action?: string, cdata?: string} $response */
        $response = $this->getValidation($data);

        return new TurnstileValidateResponseDTO(
            $response['success'],
            $response['challenge_ts'] ?? null,
            $response['hostname'] ?? null,
            $response['error-codes'],
            $response['action'] ?? null,
            $response['cdata'] ?? null,
            $response['metadata']['ephemeral_id'] ?? null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function getValidation(
        TurnstileValidateDTO $data,
    ): array {
        $curl = curl_init();

        if ($curl === false) {
            throw new ConnectionException('Failed to initialize cURL session');
        }

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => $this->turnstileUrl,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                ],
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => 1,
                CURLOPT_CONNECTTIMEOUT => $data->getConnectionTimeout(),
                CURLOPT_TIMEOUT => $data->getResponseTimeout(),
                CURLOPT_POSTFIELDS => json_encode(array_filter([
                    'secret' => $this->secret,
                    'response' => $data->getToken(),
                    'remoteip' => $data->getIp(),
                ])),
            ],
        );

        $response = curl_exec($curl);

        if (! is_string($response)) {
            $error = curl_error($curl);
            $errno = curl_errno($curl);
            curl_close($curl);

            throw new ConnectionException(
                sprintf('cURL request failed: [%d] %s', $errno, $error),
                $errno,
            );
        }

        curl_close($curl);

        $decoded = json_decode($response, true);

        if (! is_array($decoded)) {
            throw new InvalidResponseException(
                sprintf('Failed to decode JSON response: %s', $response),
            );
        }

        return $decoded;
    }
}
