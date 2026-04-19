<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ApiService;
use App\Services\ApplicationService;
use Carbon\Carbon;

class PointTypeController extends Controller
{
    protected $apiService;
    protected $applicationService;
    private $data;
    public $userAccess;

    public function __construct(ApiService $apiService, ApplicationService $applicationService)
    {
        $this->apiService = $apiService;
        $this->applicationService = $applicationService;
        
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
        return view('MasterData.PointType.index', ['data' => $this->data]);
    }

    public function read(Request $request)
    {
        $payload = $request->all();
        $response = $this->apiRequest('get', 'pointtype', $payload);
        
        return $response;
    }

    public function store(Request $request) {
        $data = $request->all();
        $data['created_at'] = Carbon::now()->toIso8601String();
        $data['updated_at'] = Carbon::now()->toIso8601String();

        $response = $this->apiRequest('post', 'pointtype', $data);
        
        return $response;
    }

    public function update(Request $request) {
        $data = $request->all();
        $data['updated_at'] = Carbon::now()->toIso8601String();

        $response = $this->apiRequest('put', 'pointtype/'.$request->id, $data);
        return $response;
    }

    public function destroy($id) {
        $data = [];

        $response = $this->apiRequest('delete', 'pointtype/'.$id, $data);

        return $response;
    }

    public function findValueBy(Request $request)
    {
        $payload = $request->all();
        $response = $this->apiRequest('get', 'pointtype/findValueBy', $payload);
        
        return $response;
    }
}