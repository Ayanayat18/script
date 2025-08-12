<?php
namespace App\Services;

class ApiClient
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct(string $baseUrl, string $apiKey)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
    }

    public function get(string $path, array $params = []): array
    {
        $url = $this->baseUrl . '/' . ltrim($path, '/');
        if ($params) {
            $url .= '?' . http_build_query($params);
        }
        return $this->request('GET', $url);
    }

    public function post(string $path, array $data = []): array
    {
        $url = $this->baseUrl . '/' . ltrim($path, '/');
        return $this->request('POST', $url, $data);
    }

    private function request(string $method, string $url, array $data = []): array
    {
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $this->apiKey,
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        $res = curl_exec($ch);
        if ($res === false) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new \RuntimeException('API request failed: ' . $err);
        }
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $json = json_decode((string)$res, true);
        if ($code >= 400) {
            throw new \RuntimeException('API error: HTTP ' . $code);
        }
        return is_array($json) ? $json : [];
    }

    public function fetchServices(string $path = '/services'): array
    {
        return $this->get($path);
    }

    public function placeOrder(string $path, string $remoteServiceId, string $input): array
    {
        return $this->post($path, [
            'service_id' => $remoteServiceId,
            'input' => $input,
        ]);
    }

    public function orderStatus(string $pathWithId): array
    {
        return $this->get($pathWithId);
    }
}