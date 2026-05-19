<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * ApiClient - centralized HTTP client for FE to call the BE API.
 * Automatically attaches the Bearer token from session.
 */
class ApiClient
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.api.base_url', env('API_BASE_URL', 'http://localhost:8001/api')), '/');
    }

    protected function client()
    {
        $token = session('api_token');

        return Http::baseUrl($this->baseUrl)
            ->acceptJson()
            ->when($token, fn($http) => $http->withToken($token))
            ->timeout(15);
    }

    public function get(string $endpoint, array $query = [])
    {
        return $this->client()->get($endpoint, $query);
    }

    public function post(string $endpoint, array $data = [])
    {
        return $this->client()->post($endpoint, $data);
    }

    public function put(string $endpoint, array $data = [])
    {
        return $this->client()->put($endpoint, $data);
    }

    public function delete(string $endpoint)
    {
        return $this->client()->delete($endpoint);
    }

    public function postMultipart(string $endpoint, array $data, $file = null, string $fileKey = 'image')
    {
        $client = $this->client();
        if ($file) {
            $client->attach($fileKey, file_get_contents($file->getPathname()), $file->getClientOriginalName());
        }
        return $client->post($endpoint, $data);
    }
}
