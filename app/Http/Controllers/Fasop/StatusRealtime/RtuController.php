<?php

namespace App\Http\Controllers\Fasop\StatusRealtime;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ApiService;
use App\Services\ApplicationService;
use Carbon\Carbon;

class RtuController extends Controller
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
            'id_induk_pointtype' => '3d3918194288469980f47ebd5ae0d733',
        ];
        $point_type = $this->apiRequest('get', 'pointtype/find-child', $params);
        $this->data->point_types = $point_type['data'];
        return view('fasop.realtime.rtu.index', ['data' => $this->data]);
    }

    public function read(Request $request)
    {
        $payload = $request->all();
        $payload['jenispoint'] = 'RTU';
        $response = $this->apiRequest('get', 'fasop/realtime/rtu', $payload);
        
        return $response;
    }
}
