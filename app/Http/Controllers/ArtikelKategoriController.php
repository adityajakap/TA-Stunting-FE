<?php

namespace App\Http\Controllers;

use App\Models\ArtikelKategori;
use Illuminate\Http\Request;

class ArtikelKategoriController extends Controller
{
    protected \App\Services\ApiClient $api;

    public function __construct(\App\Services\ApiClient $api)
    {
        $this->api = $api;
    }

    public function index()
    {
        $response = $this->api->get('/admin/kategori');
        $kategoris = collect($response->successful() ? $response->json() : [])->map(function ($item) {
            return (new ArtikelKategori)->forceFill((array)$item);
        });
        
        return view('admin.artikel.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.artikel.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $response = $this->api->post('/admin/kategori', ['name' => $request->name]);

        if ($response->successful()) {
            return redirect()->route('admin.artikel.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
        }

        return back()->with('error', 'Gagal menambahkan kategori')->withInput();
    }

    public function edit($id)
    {
        $response = $this->api->get("/admin/kategori/{$id}");
        if (!$response->successful()) abort(404);
        
        $kategori = (new ArtikelKategori)->forceFill($response->json());
        return view('admin.artikel.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $response = $this->api->put("/admin/kategori/{$id}", ['name' => $request->name]);

        if ($response->successful()) {
            return redirect()->route('admin.artikel.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
        }

        return back()->with('error', 'Gagal memperbarui kategori')->withInput();
    }

    public function destroy($id)
    {
        $this->api->delete("/admin/kategori/{$id}");
        return redirect()->route('admin.artikel.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
