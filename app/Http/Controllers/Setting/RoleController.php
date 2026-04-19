<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ApiService;
use App\Services\ApplicationService;
use Carbon\Carbon;

class RoleController extends Controller
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

    public function index()
    {
        return view('Setting.role.role-index', ['data' => $this->data]);
    }

    public function read(Request $request)
    {
        $payload = $request->all();
        $response = $this->apiRequest('get', 'roles', $payload);
        
        return $response;
    }

    public function store(Request $request) {
        $data = $request->all();
        $data['created_at'] = Carbon::now()->toIso8601String();
        $data['updated_at'] = Carbon::now()->toIso8601String();
        
        $response = $this->apiRequest('post', 'roles', $data);
        
        return $response;
    }

    public function detail($id) {

        $data = [ 'page' => -1 ];
        $headers = ['Content-Type' => 'application/json'];
        $rs_roles   = $this->apiRequest('get', 'roles/'.$id, $data);
        $rs_menu_akses = $this->apiRequest('get', 'menu/akses', $data);

        // $this->data->page->title = 'Pengaturan Roles Akses '.$rs_roles['data']['display_name'];
        $this->data->menu_akses = $rs_menu_akses['data'];
        $this->data->role_id = $id;
        $this->data->role = $rs_roles['data'];

        $data = $this->data;
        
        return view('setting.role.role-access', ['data' => $this->data]);
    }

    public function update(Request $request) {
        $data = $request->all();
        $data['updated_at'] = Carbon::now()->toIso8601String();

        $response = $this->apiRequest('put', 'roles/'.$request->id, $data);
        return $response;
    }

    public function destroy($id) {
        $data = [];

        $response = $this->apiRequest('delete', 'menu-role/'.$id, $data);
        $response = $this->apiRequest('delete', 'roles/'.$id, $data);

        return $response;
    }

    public function read_access($role_id) {
        $data = [
            'page'          => -1
        ];

        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->get('menu-role/'.$role_id, $data, $headers, session()->get('access_token'));
        return $response;
    }

    public function update_access(Request $request) {
        $roleId = $request->input('role_id');
        $menus  = $request->input('menu', []); // Ambil menu yang dipilih
        $akses  = $request->input('akses', []); // Ambil akses berdasarkan menu

        $data = [];
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->delete('menu-role/'.$roleId, $data, $headers, session()->get('access_token'));

        foreach ($menus as $menuId) {
            $data = [];
            $data = [
                'role_id'   => $roleId,
                'menu_id'   => $menuId,
                'akses'     => $akses[$menuId] ?? '' // Simpan dalam format JSON
            ];
            $data['created_at'] = Carbon::now()->toIso8601String();
            $data['updated_at'] = Carbon::now()->toIso8601String();

            $headers = ['Content-Type' => 'application/json'];
            $response = $this->apiService->post('menu-role', $data, $headers, session()->get('access_token'));
        }
        return $response;
    }
}
