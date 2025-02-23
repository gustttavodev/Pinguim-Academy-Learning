<?php

namespace App\Services\Github;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Client
{
    private string $baseURL = 'https://api.github.com';

    public function http(): PendingRequest
    {
        return Http::withOptions([
            'base_uri' => $this->baseURL,
        ])
            ->withHeaders([
                'Accept' => 'application/vnd.github+json',
                'X-Github-Api-Version' => '2022-11-28'
            ])
            ->withToken(config('services.github_access_token'));
    }
}
