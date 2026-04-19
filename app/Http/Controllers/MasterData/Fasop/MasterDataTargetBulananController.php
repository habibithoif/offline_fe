<?php

namespace App\Http\Controllers\MasterData\Fasop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ApiService;
use App\Services\ApplicationService;
use Carbon\Carbon;

class MasterDataTargetBulananController extends Controller
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
        $pointtype = $this->apiRequest('get', 'pointtype/find-induk');
        $this->data->point_type = $pointtype['data'];
        return view('MasterData.fasop.target-bulanan.index', ['data' => $this->data]);
    }

    public function read(Request $request)
    {
        $payload = $request->all();
        $response = $this->apiRequest('get', 'ref-target-kinerjas', $payload);
        
        return $response;
    }

    public function store(Request $request) {
        $data = $request->all();
        
        $response = $this->apiRequest('post', 'ref-target-kinerjas', $data);
        
        return $response;
    }

    public function update($id, Request $request) {
        $data = $request->all();

        $response = $this->apiRequest('put', 'ref-target-kinerjas/'.$id, $data);
        
        return $response;
    }

    public function destroy($id) {
        $data = [];

        $response = $this->apiRequest('delete', 'ref-target-kinerjas/'.$id, $data);

        return $response;
    }
}
