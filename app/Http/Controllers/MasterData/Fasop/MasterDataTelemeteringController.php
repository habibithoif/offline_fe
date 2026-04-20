<?php

namespace App\Http\Controllers\MasterData\Fasop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ApiService;
use App\Services\ApplicationService;
use Carbon\Carbon;

class MasterDataTelemeteringController extends Controller
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
        $ref_region_filter = $this->apiRequest('get', 'cpoint', ['point_type' => 'TELEMETERING']);
        $this->data->ref_region_filter = $ref_region_filter['data']['Rows'] ?? [];
        $this->data->pointtype_name = 'TELEMETERING';
        $data['id_induk_pointtype'] = '798be05c4df249459a475745a0de66c6';
        $point_type = $this->apiRequest('get', 'pointtype/find-child', $data);
        $this->data->point_type = $point_type['data'] ?? [];
        return view('MasterData.fasop.telemetering.index', ['data' => $this->data]);
    }

    public function read(Request $request)
    {
        $payload = $request->all();
        $payload['point_type'] = 'TELEMETERING';
        if ($request->has('hitung_kinerja')) {
            $payload['hitung_kinerja'] = filter_var($request->hitung_kinerja, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        }
        $response = $this->apiRequest('get', 'master-data/telemetering', $payload);
        
        return $response;
    }

    public function store(Request $request) {
        $data = $request->all();
        $data['kinerja'] = $request->hitung_kinerja ? 1 : 0;
        
        $response = $this->apiRequest('post', 'master-data/telemetering', $data);
        
        return $response;
    }

    public function update(Request $request) {
        $data = $request->all();
        $data['kinerja'] = $request->hitung_kinerja ? 1 : 0;
        $response = $this->apiRequest('put', 'master-data/telemetering/'.$request->id, $data);
        return $response;
    }

    public function destroy($id) {
        $data = [];

        $response = $this->apiRequest('delete', 'master-data/telemeteing/'.$id, $data);

        return $response;
    }

    public function findValueBy(Request $request)
    {
        $payload = $request->all();
        $response = $this->apiRequest('get', 'master-data/telemeteing/findValueBy', $payload);
        
        return $response;
    }
}
