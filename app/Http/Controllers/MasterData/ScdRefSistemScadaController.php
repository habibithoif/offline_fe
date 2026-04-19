<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ApiService;
use App\Services\ApplicationService;
use Carbon\Carbon;

class ScdRefSistemScadaController extends Controller
{
    protected $apiService;
    protected $applicationService;
    private $data;
    public $userAccess;

    public function __construct(ApiService $apiService, ApplicationService $applicationService)
    {
        $this->apiService = $apiService;
        $this->applicationService = $applicationService;

        // Initialize the data by calling the service method
        $this->data = $this->applicationService->initializeData(request()->path());
        $this->userAccess = $this->data->accesses;
    }

    private function apiRequest($method, $endpoint, $data = [])
    {
        $headers = ['Content-Type' => 'application/json'];
        $accessToken = session()->get('access_token');

        return $this->apiService->$method($endpoint, $data, $headers, $accessToken);
    }

    public function index()
    {
        return view('MasterData.scd-ref-sistem-scada.index', ['data' => $this->data]);
    }

    public function read(Request $request)
    {
        $payload = $request->all();
        $response = $this->apiRequest('get', 'scd-ref-sistem-scadas', $payload);
        
        return $response;
    }

    public function store(Request $request) {
        $data = $request->all();
        
        $response = $this->apiRequest('post', 'scd-ref-sistem-scadas', $data);
        
        return $response;
    }

    public function update(Request $request, $id) {
        $data = $request->all();
        $data['updated_at'] = Carbon::now()->toIso8601String();

        $response = $this->apiRequest('put', 'scd-ref-sistem-scadas/'.$id, $data);
        return $response;
    }

    public function destroy($id) {
        $data = [];

        $response = $this->apiRequest('delete', 'scd-ref-sistem-scadas/'.$id, $data);

        return $response;
    }
}
