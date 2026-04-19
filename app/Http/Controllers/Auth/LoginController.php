<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use App\Services\ApiService;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class LoginController extends Controller
{

    protected $apiService;
    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function showLoginForm()
    {
        return view('auth.login'); // Create a login view
    }

    public function login(Request $request)
    {
        $request->validate([
            'user' => 'required',
            'password' => 'required',
            'captcha' => 'required'
        ]);

        $username = $request->user;
        $userKey = 'login_attempts_' . $username;
        $loginAttempts = Cache::get($userKey, 0);

      
        if (strtoupper($request->captcha) !== Session::get('captcha_code')) {
            return back()->withInput()->with('error', 'Kode captcha tidak sesuai');
        }
        if ($loginAttempts >= 3) {
            return back()->withInput()->with('error', 'Terlalu banyak percobaan login gagal. Coba lagi dalam 30 detik.');
        }
        
        $data = [
            'user'      => $request->user,
            'password'  => $request->password
        ];

        $headers = ['Content-Type' => 'application/json'];
        $response = $this->apiService->post('auth/login', $data, $headers);
        
        if (is_array($response)){
            if (isset($response['success']) && $response['success'] === true) {
                $accessToken = $response['data']['access_token'] ?? null;
                $refreshToken = $response['data']['refresh_token'] ?? null;
                $expiresIn    = $response['data']['expires_in'] ?? 3600;
                
                if ($accessToken) {
                    Session::put('access_token', $accessToken);
                    Session::put('refresh_token', $refreshToken);
                    Session::put('access_token_expires_at', now()->addSeconds($expiresIn));

                    // Reset percobaan login
                    Cache::forget($userKey);

                    // --- Password expiry check ---
                    $headers = ['Content-Type' => 'application/json', 'Authorization' => "Bearer ".$accessToken];
                    $profileResponse = $this->apiService->post('auth/details', [], $headers, $accessToken);
                    $lastChange = $profileResponse['data']['last_password_change'] ?? null;
                    if ($lastChange) {
                        $lastChangeDate = Carbon::parse($lastChange, 'Asia/Jakarta');
                        $now = Carbon::now('Asia/Jakarta');
                        if ($lastChangeDate->diffInDays($now) >= 90) {
                            Session::flash('password_expired', 'Anda harus mengganti password. Password Anda sudah lebih dari 90 hari.');
                            return redirect()->route('profil.index')->with('error', 'Anda harus mengganti password. Password Anda sudah lebih dari 90 hari.');
                        }
                    }
                    // --- End password expiry check ---
                }

                return redirect()->route('fasop.dashboard.kinerja-scada-harian.index')->with('success', 'Login successful!');
            }
            
            // Tambah percobaan login, simpan selama 5 menit
            Cache::put($userKey, $loginAttempts + 1, now()->addSeconds(30));

            $errorMsg = $response->original['message'] ?? 'Login gagal.';
            $errorMsg = json_encode($errorMsg);
            $errorString = $errorMsg->message ?? 'Login gagal. Cek kembali kredensial Anda.';

            return back()->withInput()->with('error', str_replace('"', '', str_replace('"', '', $errorString)));
        }

        // Kalau respons bukan array (mungkin error koneksi)
        Cache::put($userKey, $loginAttempts + 1, now()->addSeconds(30));
        $mess = json_encode($response->original['message']) ?? 'Login gagal. Cek kembali kredensial Anda.';
        return back()->withInput()->with('error', str_replace('"', '', $mess));
    }

    public function logout()
    {
        $token = session()->get('access_token');
        
        if ($token) {
            $headers = ['Content-Type' => 'application/json'];
            $response = $this->apiService->post('auth/logout', [], $headers, $token);
        }
        Session::forget('tahun');
        Session::forget('access_token');
        Session::forget('refresh_token');
        Session::forget('access_token_expires_at');
        Session::forget('login_attempts_' . $request->user());
        Session::flush();

        return redirect()->route('login');
    }
}

