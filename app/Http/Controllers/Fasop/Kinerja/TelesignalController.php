<?php

namespace App\Http\Controllers\Fasop\Kinerja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ApiService;
use App\Services\ApplicationService;
use Carbon\Carbon;

class TelesignalController extends Controller
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
        $params = [
            'id_induk_pointtype' => '5a9d6c6fa33345cc9c0f06e244b7d00c',
        ];
        $point_type = $this->apiRequest('get', 'pointtype/find-child', $params);
        $this->data->point_types = $point_type['data'];
        $ref_region = $this->apiRequest('get', 'ref-region', $params = []);
        $this->data->ref_region = $ref_region['data']['Rows'] ?? [];
        return view('fasop.kinerja.telesignal.index', ['data' => $this->data]);
    }

    public function read(Request $request)
    {
        $payload = $request->all();
        $payload['jenispoint'] = 'TELESIGNAL';
        $response = $this->apiRequest('get', 'fasop/kinerja/ts', $payload);
        
        return $response;
    }
}
