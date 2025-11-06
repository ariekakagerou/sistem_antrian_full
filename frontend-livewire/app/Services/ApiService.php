<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = env('API_BASE_URL', 'http://localhost:8000/api');
        $this->token = session('api_token');
    }

    /**
     * Set token untuk autentikasi
     */
    public function setToken($token)
    {
        $this->token = $token;
        session(['api_token' => $token]);
        return $this;
    }

    /**
     * Get HTTP client dengan header
     */
    protected function client()
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        if ($this->token) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }

        return Http::withHeaders($headers)
            ->timeout(30)
            ->retry(3, 100);
    }

    /**
     * GET request
     */
    public function get($endpoint, $params = [])
    {
        try {
            $response = $this->client()->get($this->baseUrl . $endpoint, $params);
            return $this->handleResponse($response);
        } catch (\Exception $e) {
            Log::error('API GET Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * POST request
     */
    public function post($endpoint, $data = [])
    {
        try {
            $response = $this->client()->post($this->baseUrl . $endpoint, $data);
            return $this->handleResponse($response);
        } catch (\Exception $e) {
            Log::error('API POST Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * PUT request
     */
    public function put($endpoint, $data = [])
    {
        try {
            $response = $this->client()->put($this->baseUrl . $endpoint, $data);
            return $this->handleResponse($response);
        } catch (\Exception $e) {
            Log::error('API PUT Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * DELETE request
     */
    public function delete($endpoint)
    {
        try {
            $response = $this->client()->delete($this->baseUrl . $endpoint);
            return $this->handleResponse($response);
        } catch (\Exception $e) {
            Log::error('API DELETE Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle response dari API
     */
    protected function handleResponse($response)
    {
        if ($response->successful()) {
            return $response->json();
        }

        if ($response->status() === 401) {
            session()->forget('api_token');
            throw new \Exception('Unauthorized. Please login again.');
        }

        throw new \Exception($response->json()['message'] ?? 'API request failed');
    }

    // ==================== LOKET ENDPOINTS ====================

    /**
     * Get semua loket
     */
    public function getLokets()
    {
        return $this->get('/loket');
    }

    /**
     * Get loket by ID
     */
    public function getLoket($id)
    {
        return $this->get("/loket/{$id}");
    }

    // ==================== ANTRIAN ENDPOINTS ====================

    /**
     * Get semua antrian
     */
    public function getAntrians($params = [])
    {
        return $this->get('/antrian', $params);
    }

    /**
     * Get antrian by ID
     */
    public function getAntrian($id)
    {
        return $this->get("/antrian/{$id}");
    }

    /**
     * Get antrian by loket
     */
    public function getAntrianByLoket($loketId)
    {
        return $this->get("/antrian/loket/{$loketId}");
    }

    /**
     * Buat antrian baru
     */
    public function createAntrian($data)
    {
        return $this->post('/antrian', $data);
    }

    /**
     * Panggil antrian
     */
    public function panggilAntrian($id)
    {
        return $this->post("/antrian/{$id}/panggil");
    }

    /**
     * Selesaikan antrian
     */
    public function selesaiAntrian($id)
    {
        return $this->post("/antrian/{$id}/selesai");
    }

    /**
     * Update antrian
     */
    public function updateAntrian($id, $data)
    {
        return $this->put("/antrian/{$id}", $data);
    }

    /**
     * Delete antrian
     */
    public function deleteAntrian($id)
    {
        return $this->delete("/antrian/{$id}");
    }

    // ==================== AUTH ENDPOINTS ====================

    /**
     * Login
     */
    public function login($email, $password)
    {
        $response = $this->post('/login', [
            'email' => $email,
            'password' => $password,
        ]);

        if (isset($response['token'])) {
            $this->setToken($response['token']);
        }

        return $response;
    }

    /**
     * Register
     */
    public function register($data)
    {
        return $this->post('/register', $data);
    }

    /**
     * Logout
     */
    public function logout()
    {
        $response = $this->post('/logout');
        session()->forget('api_token');
        return $response;
    }

    /**
     * Get current user
     */
    public function getCurrentUser()
    {
        return $this->get('/user');
    }
}
