<?php

namespace App\Http\Controllers\Fasop\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ApiService;
use App\Services\ApplicationService;
use Carbon\Carbon;

class KinerjaScadaBulananController extends Controller
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
        $ref_region = $this->apiRequest('get', 'ref-region', $params = []);
        $this->data->ref_region = $ref_region['data']['Rows'] ?? [];
        $point_type = $this->apiRequest('get', 'pointtype/find-induk');
        $this->data->point_type = $point_type['data'] ?? [];
        return view('fasop.dashboard.kinerja-scada-bulanan.index', ['data' => $this->data]);
    }

    public function read(Request $request)
    {
        $payload = $request->all();
        $response = $this->apiRequest('get', 'fasop/realtime/analog', $payload);
        
        return $response;
    }
}
