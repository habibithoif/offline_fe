<?php

namespace App\Http\Controllers\Fasop\History;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ApiService;
use App\Services\ApplicationService;
use Carbon\Carbon;

class TelemeteringController extends Controller
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
        $this->data->pointtype_name = 'TELEMETERING';
        $params = [
            'id_induk_pointtype' => '798be05c4df249459a475745a0de66c6',
        ];
        $point_type = $this->apiRequest('get', 'pointtype/find-child', $params);
        $this->data->point_types = $point_type['data'];
        $ref_region = $this->apiRequest('get', 'ref-region', $params = []);
        $this->data->ref_region = $ref_region['data']['Rows'] ?? [];
        return view('fasop.history.telemetering.index', ['data' => $this->data]);
    }

    public function read(Request $request)
    {
        $payload = $request->all();
        $payload['point_type'] = 'TELEMETERING';
        $response = $this->apiRequest('get', 'fasop/history/tm', $payload);
        
        return $response;
    }
}
