<?php

declare(strict_types=1);

namespace VincenzoRaco\Turnstile;

use VincenzoRaco\Turnstile\DataObjects\TurnstileValidateDTO;
use VincenzoRaco\Turnstile\DataObjects\TurnstileValidateResponseDTO;

class Turnstile
{
    private string $turnstileUrl = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    public function __construct(
        private readonly string $secret,
    ) {}

    public function validate(
        TurnstileValidateDTO $data,
    ): TurnstileValidateResponseDTO {
        $response = $this->getValidation($data);

        return new TurnstileValidateResponseDTO(
            $response['success'],
            $response['challenge_ts'],
            $response['hostname'],
            $response['error-codes'],
            $response['action'],
            $response['cdata'],
            $response['metadata']['ephemeral_id'] ?? null,
        );
    }

    private function getValidation(
        TurnstileValidateDTO $data,
    ): array {
        curl_setopt_array(
            $curl = curl_init(),
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

        curl_close($curl);

        return json_decode($response, true);
    }
}
