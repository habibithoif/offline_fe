<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ApiService
{
    protected $client;
    protected $ApibaseUrl;

    public function __construct()
    {
        $this->client = new Client([
            'verify' => false, // Disable SSL verification if needed
        ]);
        //$this->ApibaseUrl = env('API_BASE_URL', 'https://emonevapi.portalsjm.com/api/v1/portal/');
        $this->ApibaseUrl = env('API_BASE_URL', 'http://127.0.0.1:8000/api/v1/portal/');
    }

    // public function request($method, $endpoint, $data = [], $headers = [], $token = null)
    // {
    //     $url = $this->ApibaseUrl . $endpoint;

    //     if ($token) {
    //         $headers['Authorization'] = "Bearer $token";
    //     }

    //     try {
    //         $response = $this->client->request($method, $url, [
    //             'headers' => $headers,
    //             'json'    => $data,
    //         ]);

    //         return json_decode($response->getBody()->getContents(), true);
    //     } catch (RequestException $e) {
    //         return [
    //             'error' => true,
    //             'message' => $e->getMessage(),
    //             'response' => $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
    //         ];
    //     }
    // }

    public function request($method, $endpoint, $data = [], $headers = [], $token = null)
    {
        $url = $this->ApibaseUrl . $endpoint;

        if ($token) {
            $headers['Authorization'] = "Bearer $token";
        }

        try {
            $response = $this->client->request($method, $url, [
                'headers' => $headers,
                'json'    => $data,
                'stream'  => true // penting untuk download
            ]);

            // kalau endpoint download -> return raw response
            if (str_contains($endpoint, 'download')) {
                return $response;
            }

            // selain download tetap JSON
            return json_decode($response->getBody()->getContents(), true);

        } catch (RequestException $e) {
            return [
                'error' => true,
                'message' => $e->getMessage(),
                'response' => $e->getResponse()
                    ? $e->getResponse()->getBody()->getContents()
                    : null
            ];
        }
    }

    public function get($endpoint, $data = [], $headers = [], $token = null)
    {
        return $this->request('GET', $endpoint, $data, $headers, $token);
    }

    public function post($endpoint, $data, $headers = [], $token = null)
    {
        $response = $this->request('POST', $endpoint, $data, $headers, $token);
        if (isset($response['success']) && $response['success'] == true){
            return $response;
        } else{
            return response()->json([
                "success" => false,
                "message" => json_decode($response['message'])->errors_string ?? 'An error occurred'
            ], 422);
        }
    }

    public function put($endpoint, $data, $headers = [], $token = null)
    {
        $response = $this->request('PUT', $endpoint, $data, $headers, $token);
        if (isset($response['success']) && $response['success'] == true){
            return $response;
        } else{
            return response()->json([
                "success" => false,
                "message" => json_decode($response['response'])->errors_string ?? 'An error occurred'
            ], 422);
        }
    }

    public function patch($endpoint, $data, $headers = [], $token = null)
    {
        return $this->request('PATCH', $endpoint, $data, $headers, $token);
    }

    public function delete($endpoint, $data = [], $headers = [], $token = null)
    {
        return $this->request('DELETE', $endpoint, $data, $headers, $token);
    }
}
