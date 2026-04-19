<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;
use App\Services\ApplicationService;
use Carbon\Carbon;

use App\Services\ApiService;

class UsersController extends Controller
{
    protected $apiService;
    protected $applicationService;
    private $data;
    public $userAccessRole;

    public function __construct(ApiService $apiService, ApplicationService $applicationService)
    {
        $this->apiService = $apiService;
        $this->applicationService = $applicationService;

        // Initialize the data by calling the service method
        $this->data = $this->applicationService->initializeData(request()->path());
        $this->userAccessRole = $this->data->accesses;
    }

    private function apiRequest($method, $endpoint, $data = [])
    {
        $headers = ['Content-Type' => 'application/json'];
        $accessToken = session()->get('access_token');

        return $this->apiService->$method($endpoint, $data, $headers, $accessToken);
    }

    public function index() {
        $data = [ 'pagesize' => 100 ];
        $response = $this->apiRequest('get', 'roles', $data);
        $this->data->role_data = $response['data']['Rows'];

        $data = $this->data;
        
        return view('setting.users.users-index', ['data' => $this->data]);
    }

    public function read(Request $request) {
        $payload = $request->all();
        $response = $this->apiRequest('get', 'users', $payload);
        
        return $response;
    }

    public function store(Request $request) {
        $data = $request->all();
        $data['role_id'] = $request->role_id;
        $data['created_at'] = Carbon::now()->toIso8601String();
        $data['updated_at'] = Carbon::now()->toIso8601String();
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->post('users', $data, $headers, session()->get('access_token'));
        return $response;
    }

    public function detail($id) {
        $data = [
            'page'          => -1
        ];

        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->get('users/'.$id, $data, $headers, session()->get('access_token'));
        return $response;
    }

    public function update(Request $request) {
        $data = $request->all();
        $data['role_id'] = $request->role_id;
        $data['updated_at'] = Carbon::now()->toIso8601String();
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->put('users/'.$request->id, $data, $headers, session()->get('access_token'));
        return $response;
    }

    public function destroy($id) {
        $data = [];
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->delete('users/'.$id, $data, $headers, session()->get('access_token'));
        return $response;
    }

    public function read_role($id) {
        $data = [
            'page'          => -1
        ];

        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->get('users/'.$id, $data, $headers, session()->get('access_token'));
        return $response;
    }

}
