<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ApiClient;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected ApiClient $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap'          => 'required|string|max:255',
            'username'              => 'required|string|max:255',
            'password'              => 'required|min:6|confirmed',
        ]);

        $response = $this->api->post('/register', $request->only([
            'nama_lengkap', 'username', 'password', 'password_confirmation',
        ]));

        if ($response->successful()) {
            return redirect()->route('login')->with('success', 'Register berhasil, silakan login.');
        }

        return back()->withErrors($response->json('errors', []))
                     ->with('error', $response->json('message', 'Registrasi gagal.'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $response = $this->api->post('/login', $request->only(['username', 'password']));

        if ($response->successful()) {
            $data = $response->json();
            session([
                'api_token' => $data['token'],
                'user'      => $data['user'],
            ]);

            $role = $data['user']['role'] ?? null;

            if ($role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($role === 'orangtua') {
                return redirect()->route('orangtua.dashboard');
            }

            return redirect()->route('login')->with('error', 'Role tidak dikenali.');
        }

        return back()->withErrors(['login' => 'Username atau password salah.']);
    }

    public function logout(Request $request)
    {
        $this->api->post('/logout');
        $request->session()->flush();
        return redirect()->route('login');
    }
}
