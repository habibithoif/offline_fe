<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ApiService;
use App\Services\ApplicationService;
use Carbon\Carbon;

class RefEpCuacaController extends Controller
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
        return view('MasterData.ref-ep-cuaca.index', ['data' => $this->data]);
    }

    public function read(Request $request)
    {
        $payload = $request->all();
        $response = $this->apiRequest('get', 'ref-ep-cuacas', $payload);
        
        return $response;
    }

    public function store(Request $request) {
        $data = $request->all();
        
        $response = $this->apiRequest('post', 'ref-ep-cuacas', $data);
        
        return $response;
    }

    public function update(Request $request) {
        $data = $request->all();
        $data['updated_at'] = Carbon::now()->toIso8601String();

        $response = $this->apiRequest('put', 'ref-ep-cuacas/'.$request->id, $data);
        return $response;
    }

    public function destroy($id) {
        $data = [];

        $response = $this->apiRequest('delete', 'ref-ep-cuacas/'.$id, $data);

        return $response;
    }
}
