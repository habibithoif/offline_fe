<?php 
// app/Http/Controllers/ExportController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    public function saveFile(Request $request)
    {
        $data = $request->input('data');
        $filename = $request->input('filename', 'ExportedData');
        $type = $request->input('export_type', 'xls');

        return Response::make($data, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => "attachment; filename=\"$filename.$type\"",
        ]);
    }
}
?>