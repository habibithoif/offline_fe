<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    protected $data;

    public function __construct(Request $request) {
        $this->data = new \stdClass();
    }

    public function index() {

        $file = 'home';
        $data = $this->data;
        
        return view($file, compact('data'));
    }
}
