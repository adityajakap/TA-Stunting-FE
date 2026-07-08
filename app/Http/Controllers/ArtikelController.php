<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Artikel;
use Illuminate\Support\Facades\Storage;

class ArtikelController extends Controller
{
    protected \App\Services\ApiClient $api;

    public function __construct(\App\Services\ApiClient $api)
    {
        $this->api = $api;
    }

    public function index(Request $request)
    {
        $response = $this->api->get('/admin/artikel', [
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

        $search = $request->input('search');
        return view('admin.artikel.index', compact('artikels', 'search'));
    }

    public function create()
    {
        $response = $this->api->get('/admin/kategori');
        $kategoris = $response->successful() ? $response->json() : [];
        return view('admin.artikel.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kategoris' => 'nullable|array',
        ]);

        $data = $request->except('image');
        $file = $request->file('image');

        $response = $this->api->postMultipart('/admin/artikel', $data, $file, 'image');

        if ($response->successful()) {
            return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil ditambahkan!');
        }

        return back()->with('error', 'Gagal menambahkan artikel')->withInput();
    }

    public function show($id)
    {
        $response = $this->api->get("/admin/artikel/{$id}");
        if (!$response->successful()) abort(404);
        
        $artikel = (new Artikel)->forceFill($response->json());
        return view('admin.artikel.show', compact('artikel'));
    }

    public function edit($id)
    {
        $response = $this->api->get("/admin/artikel/{$id}");
        if (!$response->successful()) abort(404);
        
        $artikel = (new Artikel)->forceFill($response->json());
        return view('admin.artikel.edit', compact('artikel'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'kategoris' => 'nullable|array',
        ]);

        $data = $request->except(['image', '_method']);
        $data['_method'] = 'PUT'; // API client uses POST for multipart, so we spoof PUT
        $file = $request->file('image');

        $response = $this->api->postMultipart("/admin/artikel/{$id}", $data, $file, 'image');

        if ($response->successful()) {
            return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil diperbarui!');
        }

        return back()->with('error', 'Gagal memperbarui artikel')->withInput();
    }

    public function destroy($id)
    {
        $this->api->delete("/admin/artikel/{$id}");
        return redirect()->route('admin.artikel.index')->with('success', 'Artikel berhasil dihapus!');
    }
}
