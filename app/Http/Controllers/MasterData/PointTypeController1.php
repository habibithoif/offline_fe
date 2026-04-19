<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

use App\Services\ApiService;

class PointTypeController1 extends Controller
{
    protected $data;
    protected $apiService;

    public function __construct(Request $request, ApiService $apiService) {
        $this->data = new \stdClass();
        $this->apiService = $apiService;
        if (!isset($this->data->page)) {
            $this->data->page = new \stdClass();
        }
    }

    public function index() {
        $file = 'MasterData.pointtype';
        $this->data->page->title = 'Master Data Point Type';
        $data = $this->data;
        
        return view($file.'.pointtype', compact('data'));
    }

    public function read(Request $request) {
        $data = $request->all();
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->get('pointtype', $data, $headers, session()->get('access_token'));
        
        $json_data = array( 
            "draw"            => intval($request->draw),
            "recordsTotal"    => isset($response['data']['total']) ? intval($response['data']['total']) : 0,  
            "recordsFiltered" => isset($response['data']['total']) ? intval($response['data']['total']) : 0,  
            "data"            => isset($response['data']['data']) ? $response['data']['data'] : $response['data']
        );
        return $json_data;
    }

    public function store(Request $request) {
        $data = $request->all();
        $data['created_at'] = Carbon::now()->toIso8601String();
        $data['updated_at'] = Carbon::now()->toIso8601String();
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->post('pointtype', $data, $headers, session()->get('access_token'));
        return $response;
    }

    public function detail($id) {
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->get('pointtype/'.$id, $headers, session()->get('access_token'));
        return $response;
    }

    public function update(Request $request) {
        $data = $request->all();
        $data['updated_at'] = Carbon::now()->toIso8601String();
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->put('pointtype/'.$request->id, $data, $headers, session()->get('access_token'));
        return $response;
    }

    public function destroy($id) {
        $data = [];
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->delete('pointtype/'.$id, $data, $headers, session()->get('access_token'));
        return $response;
    }

    public function read_all(Request $request) {
        $data = [
            'page'          => -1
        ];
        
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->get('pointtype', $data, $headers, session()->get('access_token'));
        return $response;
    }

}
