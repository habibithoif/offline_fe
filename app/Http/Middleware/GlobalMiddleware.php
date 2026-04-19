<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\GlobalController;
use Illuminate\Support\Facades\View;

class GlobalMiddleware
{
    /**
     * Handle an incoming request.
     */

    public function handle(Request $request, Closure $next): Response
    {
        $globalController = app(GlobalController::class);

        $cekAkses = $globalController->cek_status_hakaskses();
        if ($cekAkses === 0) {
            return redirect()->to('/'); 
        }

        $data_user = $globalController->user_detail();
        View::share('user', $data_user['data']);

        $data = $globalController->show_menu($data_user['data']['id']);
        // print_r($data);
        View::share('menu', $data);

        return $next($request);
    }
}

