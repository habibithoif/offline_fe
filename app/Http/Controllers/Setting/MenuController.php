<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Services\ApplicationService;

class MenuController extends Controller
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

    // Helper method to handle API requests
    private function apiRequest($method, $endpoint, $data = [])
    {
        $headers = ['Content-Type' => 'application/json'];
        $accessToken = session()->get('access_token');

        return $this->apiService->$method($endpoint, $data, $headers, $accessToken);
    }

    public function index()
    {
        $this->data->icons = $this->apiRequest('get', 'icons')['data'];
        $data = $this->data;
        return view('Setting.menu.menu-index', compact('data'));
    }

    public function read(Request $request)
    {
        $columns = [
            0 => ['data' => 'id', 'searchable' => true, 'orderable' => true],
            1 => ['data' => 'name', 'searchable' => true, 'orderable' => true],
            2 => ['data' => 'display', 'searchable' => true, 'orderable' => true],
            3 => ['data' => 'path', 'searchable' => false, 'orderable' => false],
            4 => ['data' => 'icon', 'searchable' => true, 'orderable' => true],
            5 => ['data' => 'no', 'searchable' => true, 'orderable' => true],
            6 => ['data' => 'parent_id', 'searchable' => true, 'orderable' => true],
            7 => ['data' => 'status', 'searchable' => true, 'orderable' => true],
        ];

        $columnIndex = $request->order[0]['column'];
        $data = [
            'keyword' => $request->search['value'],
            'export' => null,
            'export_type' => null,
            'sort_by' => $columns[$columnIndex]['data'] ?? null,
            'sort' => $request->order[0]['dir'],
            'page' => $request->search['value'] === null ? (int)($request->start / $request->length) + 1 : -1,
            'limit' => $request->length,
        ];

        $response = $this->apiRequest('get', 'menu', $data);

        return response()->json([
            "draw" => (int)$request->draw,
            "recordsTotal" => $response['data']['total'] ?? 0,
            "recordsFiltered" => $response['data']['total'] ?? 0,
            "data" => $response['data']['data'] ?? $response['data']
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->only(['name', 'display', 'path', 'icon', 'no', 'parent_id', 'status']);
        $data['akses'] = $request->get('akses', '');
        $data['created_at'] = Carbon::now()->toIso8601String();
        $data['updated_at'] = Carbon::now()->toIso8601String();

        $response = $this->apiRequest('post', 'menu', $data);
        
        return response()->json($response);
    }

    public function detail($id)
    {
        $response = $this->apiRequest('get', 'menu/' . $id);
        
        return response()->json($response);
    }

    public function update(Request $request)
    {
        $data = $request->only(['name', 'display', 'path', 'icon', 'no', 'parent_id', 'status']);
        $data['updated_at'] = Carbon::now()->toIso8601String();

        $response = $this->apiRequest('put', 'menu/' . $request->id, $data);
        
        return response()->json($response);
    }

    public function destroy($id)
    {
        $response = $this->apiRequest('delete', 'menu/' . $id);
        
        return response()->json($response);
    }

    public function read_all(Request $request)
    {
        $data = ['page' => -1, 'limit' => 100];
        $response = $this->apiRequest('get', 'menu', $data);
        
        return response()->json($response);
    }
}
