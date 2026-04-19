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

class PointTypeStateController extends Controller
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
        $file = 'MasterData.pointtypestate';
        $this->data->page->title = 'Master Data Point Type State';
        $data = $this->data;
        
        return view($file.'.pointtype', compact('data'));
    }

    public function read(Request $request) {
        // $columns = [
        //     0 => [ 'data' => 'id', 'searchable' => true, 'orderable' => true ],
        //     1 => [ 'data' => 'name', 'searchable' => true, 'orderable' => true ],
        //     2 => [ 'data' => 'jenispoint', 'searchable' => true, 'orderable' => true ],
        //     6 => [ 'data' => 'id_induk_pointtype', 'searchable' => true, 'orderable' => true ],
        //     7 => [ 'data' => 'status', 'searchable' => true, 'orderable' => true ],
        // ];
        // $colomn = $request->order['0']['column'];
        
        // $data = [
        //     'keyword'       => $request->search['value'],
        //     'export'        => NULL,
        //     'export_type'   => NULL,
        //     'sort_by'       => $request->order['0']['column'] != 0 ? $columns[$colomn]['data'] : NULL,
        //     'sort'          => $request->order['0']['dir'],
        //     'page'          => $request->search['value'] === null ? intval($request->start / $request->length) + 1 : -1,
        //     'limit'         => $request->length, 
        // ];
        $data = $request->all();
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->get('pointtypestate', $data, $headers, session()->get('access_token'));

        //return $response['data'];
       
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
        $response = $this->apiService->post('pointtypestate', $data, $headers, session()->get('access_token'));
        return $response;
    }

    public function detail($id) {
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->get('pointtypestate/'.$id, $headers, session()->get('access_token'));
        return $response;
    }

    public function update(Request $request) {
        $data = $request->all();
        $data['updated_at'] = Carbon::now()->toIso8601String();
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->put('pointtypestate/'.$request->id_pointtype_state, $data, $headers, session()->get('access_token'));
        return $response;
    }

    public function destroy($id) {
        $data = [];
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->delete('pointtypestate/'.$id, $data, $headers, session()->get('access_token'));
        return $response;
    }

    public function read_all(Request $request) {
        $data = [
            'page'          => -1
        ];
        
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->get('pointtypestate', $data, $headers, session()->get('access_token'));
        return $response;
    }
    
    public function copy(Request $request) {
        $data = $request->all();
        $data['created_at'] = Carbon::now()->toIso8601String();
        $data['updated_at'] = Carbon::now()->toIso8601String();
        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->post('pointtypestate/copy', $data, $headers, session()->get('access_token'));
        return $response;
    }
}
