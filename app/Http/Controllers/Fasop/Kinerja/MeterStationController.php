<?php

namespace App\Http\Controllers\Fasop\Kinerja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ApiService;
use App\Services\ApplicationService;
use Carbon\Carbon;

class MeterStationController extends Controller
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
            'id_induk_pointtype' => '3fc4d6b66faf4089a020344d16ad4208',
        ];
        $point_type = $this->apiRequest('get', 'pointtype/find-child', $params);
        $this->data->point_types = $point_type['data'];
        return view('fasop.kinerja.meter-station.index', ['data' => $this->data]);
    }

    public function read(Request $request)
    {
        $payload = $request->all();
        $payload['jenispoint'] = 'METERSTATION';
        $response = $this->apiRequest('get', 'fasop/kinerja/ms', $payload);
        
        return $response;
    }
}
