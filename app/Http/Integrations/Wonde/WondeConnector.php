<?php

namespace App\Http\Integrations\Wonde;

use Saloon\Http\Connector;

class WondeConnector extends Connector
{
    public function resolveBaseUrl(): string
    {
        return (string) config('services.wonde.base_url');
    }

    public function __construct(string $token = null)
    {
        $token = $token ?? (string) config('services.wonde.token');
        $this->withTokenAuth($token);
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    public function defaultConfig(): array
    {
        return [
            'timeout' => 60,
        ];
    }
}
