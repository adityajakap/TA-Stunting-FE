<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Services\ApiClient;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected ApiClient $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    public function orangtua()
    {
        // Get list of children from API
        $response = $this->api->get('/children');
        $children = collect($response->successful() ? $response->json() : [])
            ->map(fn($c) => (new Child)->forceFill((array)$c));

        // Get dashboard stats (menus & articles)
        $dashResponse = $this->api->get('/dashboard/orangtua');
        $dashData = $dashResponse->successful() ? json_decode($dashResponse->body()) : (object)['menus' => (object)[], 'artikels' => []];
        $menus = (array)$dashData->menus;
        $artikels = collect($dashData->artikels);

        $user = session('user');
        $selectedChildId = session('selected_child_id');
        
        // Auto-select first child if none is currently selected in session
        if (!$selectedChildId && $children->isNotEmpty()) {
            $selectedChildId = $children->first()->id;
            session(['selected_child_id' => $selectedChildId]);
        }

        $selectedChild = $children->firstWhere('id', $selectedChildId);
        
        // Simpan nama anak aktif di session agar navbar bisa mengaksesnya dengan mudah
        if ($selectedChild) {
            session(['active_child_name' => $selectedChild->nama_lengkap_anak]);
        }

        return view('orangtua.dashboard', compact('user', 'children', 'selectedChild', 'selectedChildId', 'menus', 'artikels'));
    }

    public function admin()
    {
        $user = session('user');

        // Get admin stats
        $statsResponse = $this->api->get('/admin/dashboard');
        $stats = $statsResponse->successful() ? $statsResponse->json() : [];

        return view('admin.dashboard', compact('user', 'stats'));
    }

    public function selectChild(Request $request)
    {
        $request->validate(['child_id' => 'required|integer']);
        session(['selected_child_id' => $request->child_id]);
        return redirect()->back()->with('success', 'Anak berhasil dipilih.');
    }

    public function addChild(Request $request)
    {
        $request->validate([
            'nama_lengkap_anak' => 'required|string|max:255',
            'tanggal_lahir'     => 'required|date',
            'nik_anak'          => 'nullable|string|max:20',
        ]);

        $response = $this->api->post('/children', $request->only([
            'nama_lengkap_anak', 'tanggal_lahir', 'nik_anak',
        ]));

        if ($response->successful()) {
            $child = $response->json();
            session(['selected_child_id' => $child['id']]);
            return redirect()->route('orangtua.dashboard')->with('success', 'Anak berhasil ditambahkan!');
        }

        return back()->with('error', 'Gagal menambahkan anak. Coba lagi.');
    }
}
