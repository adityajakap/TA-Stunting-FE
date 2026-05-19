<?php

namespace App\Http\Controllers;

use App\Models\TahapanPerkembangan;

class TahapanPerkembanganController extends Controller
{
    protected \App\Services\ApiClient $api;

    public function __construct(\App\Services\ApiClient $api)
    {
        $this->api = $api;
    }

    public function index()
    {
        if ((session('user')['role'] ?? '') !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $response = $this->api->get('/tahapan-master');
        $tahapanPerkembangan = collect($response->successful() ? $response->json() : [])->map(function ($item) {
            return (new TahapanPerkembangan)->forceFill((array)$item);
        });

        return view('admin.tahapan_perkembangan.index', compact('tahapanPerkembangan'));
    }
}