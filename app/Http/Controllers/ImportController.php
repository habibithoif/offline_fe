<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\ApiService;
use App\Services\ApplicationService;
use Carbon\Carbon;


class ImportController extends Controller
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
        $headers = [];
        $accessToken = session()->get('access_token');

        return $this->apiService->$method($endpoint, $data, $headers, $accessToken);
    }

    public function review(Request $request) {
        $data = $request->all(); 
        // // 🔥 ambil file
        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file');
        } 
             
        $response = $this->apiRequest('post', 'import/preview', $data);
        
        return $response;
    }

    public function save(Request $request) {
        $data = $request->all();        
        $response = $this->apiRequest('post', 'import/save', $data);
        
        return $response;
    }
    
    public function downloadTemplate(Request $request) {
        $data = $request->all();        
        $name = $request->input('template_name').'.xlsx';        
        $response = $this->apiRequest('get', 'import/downloadTemplate', $data);
        
        return response()->streamDownload(function () use ($response) {
            echo $response->getBody()->getContents();
        }, $name, [
            'Content-Type' => $response->getHeaderLine('Content-Type')
        ]);
    }
}
?>