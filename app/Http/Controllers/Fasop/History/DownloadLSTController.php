<?php

namespace App\Http\Controllers\Fasop\History;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ApiService;
use App\Services\ApplicationService;
use Illuminate\Support\Facades\Http;

class DownloadLSTController extends Controller
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
        return view('fasop.history.download-lst.index', ['data' => $this->data]);
    }

    public function read(Request $request)
    {
        $response = $this->apiRequest('get', 'files', $request->all());
        return $response;
    }

    // public function download($filename)
    // {
        // $filename = urldecode($filename);
        // $token = session()->get('access_token');
        // var_dump(env('API_URL'));
        // $response = Http::withToken($token)
        //     ->withOptions(['stream' => true])
        //     ->get("http://127.0.0.1:8000/api/v1/portal/files/download/".$filename);

        // return response()->streamDownload(function () use ($response) {
        //     echo $response->body();
        // }, $filename, [
        //     'Content-Type' => $response->header('Content-Type'),
        // ]);
    // }

    public function download($filename)
    {
        $filename = urldecode($filename);

        $response = $this->apiRequest('get', "files/download/".$filename);

        return response()->streamDownload(function () use ($response) {
            echo $response->getBody()->getContents();
        }, $filename, [
            'Content-Type' => $response->getHeaderLine('Content-Type')
        ]);
    }
}
