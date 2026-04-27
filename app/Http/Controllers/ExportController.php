<?php 
// app/Http/Controllers/ExportController.php
// namespace App\Http\Controllers;

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
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;

// class ExportController extends Controller
// {
//     public function export(Request $request)
//     {
//         $table  = $request->table;
//         $format = $request->format ?? 'csv';

//         $rows = \DB::table($table)->get();

//         $filename = $table . '.' . $format;

//         return response()->streamDownload(function () use ($rows) {

//             $handle = fopen('php://output', 'w');

//             if(count($rows)){
//                 fputcsv($handle, array_keys((array)$rows[0]));
//             }

//             foreach ($rows as $row){
//                 fputcsv($handle, (array)$row);
//             }

//             fclose($handle);

//         }, $filename, [
//             'Content-Type' => 'text/csv',
//         ]);
//     }
// }
?>