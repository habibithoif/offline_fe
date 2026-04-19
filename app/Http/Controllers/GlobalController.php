<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use App\Services\ApiService;

class GlobalController extends Controller
{
    protected $apiService;

    public function __construct(Request $request, ApiService $apiService) {
        
        $this->data = new \stdClass();
        $this->apiService = $apiService;
    }

    public function cek_status_hakaskses() {
        $data = [
            'refresh_token'      => session()->get('refresh_token'),
        ];

        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->post('auth/refresh-token', $data, $headers, session()->get('access_token'));

        if (is_array($response)){
            if (isset($response['success']) && $response['success'] === true) {
                $accessToken = $response['data']['access_token'] ?? null;
                $refreshToken = $response['data']['refresh_token'] ?? null;
                
                if ($accessToken) {
                    Session::put('access_token', $accessToken);
                    Session::put('refresh_token', $refreshToken);
                }
    
                return 1;
            }
        }
        Session::forget('access_token');
        Session::forget('refresh_token');
        return 0;
    }

    public function user_detail()
    {
        $data = [];
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->post('auth/details/', $data, $headers, session()->get('access_token'));

        if (is_array($response) && isset($response['data'])) {

            return $response;
        }

        return []; 
    }

    public function show_menu($user_id)
    {
        $data = [];
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->get('users/'.$user_id.'/menus', $data, $headers, session()->get('access_token'));

        if (is_array($response) && isset($response['data'])) {

            return $response;
        }

        return []; 
    }
}
