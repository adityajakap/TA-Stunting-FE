<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Artikel;

class UserArtikelController extends Controller
{
    protected \App\Services\ApiClient $api;

    public function __construct(\App\Services\ApiClient $api)
    {
        $this->api = $api;
    }

    // Tampilkan daftar semua artikel untuk user
    public function index(Request $request)
    {
        $response = $this->api->get('/artikel', [
            'search' => $request->search,
            'paginate' => 12,
            'page' => $request->page ?? 1
        ]);
        
        $data = $response->successful() ? $response->json() : ['data' => [], 'total' => 0, 'per_page' => 12, 'current_page' => 1];
        
        $items = collect($data['data'])->map(function ($item) {
            return (new Artikel)->forceFill((array)$item);
        });

        $artikels = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $data['total'] ?? 0,
            $data['per_page'] ?? 12,
            $data['current_page'] ?? 1,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('orangtua.artikel.index', compact('artikels'));
    }

    // Tampilkan detail satu artikel
    public function show($id)
    {
        $response = $this->api->get("/artikel/{$id}", ['increment_views' => true]);
        if (!$response->successful()) abort(404);
        
        $artikel = (new Artikel)->forceFill($response->json());
        return view('orangtua.artikel.show', compact('artikel'));
    }
}