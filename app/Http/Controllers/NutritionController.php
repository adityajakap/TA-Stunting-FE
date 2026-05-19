<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use Illuminate\Http\Request;
use App\Models\NutritionRecommendation;

class NutritionController extends Controller
{
    protected ApiClient $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    public function index(Request $request)
    {
        if ((session('user')['role'] ?? '') !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $response = $this->api->get('/admin/nutrition', [
            'search' => $request->search,
            'paginate' => 12,
            'page' => $request->page ?? 1
        ]);
        
        $data = $response->successful() ? $response->json() : ['data' => [], 'total' => 0, 'per_page' => 12, 'current_page' => 1];
        
        $items = collect($data['data'])->map(function ($item) {
            return (new NutritionRecommendation)->forceFill((array)$item);
        });

        $menus = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $data['total'] ?? 0,
            $data['per_page'] ?? 12,
            $data['current_page'] ?? 1,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.nutrition.index', compact('menus'));
    }

    public function create()
    {
        if ((session('user')['role'] ?? '') !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('admin.nutrition.create');
    }

    public function store(Request $request)
    {
        if ((session('user')['role'] ?? '') !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required',
            'nutrition' => 'required',
            'ingredients' => 'required',
            'instructions' => 'required',
            'category' => 'required|in:pagi,siang,malam,snack',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->except('image');
        $file = $request->file('image');

        $response = $this->api->postMultipart('/admin/nutrition', $data, $file, 'image');

        if ($response->successful()) {
            return redirect()->route('admin.nutrition.index')->with('success', 'Menu berhasil ditambahkan');
        }

        return back()->with('error', 'Gagal menambahkan menu')->withInput();
    }

    public function edit($id)
    {
        if ((session('user')['role'] ?? '') !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $response = $this->api->get("/admin/nutrition/{$id}");
        if (!$response->successful()) abort(404);
        
        $menu = (new NutritionRecommendation)->forceFill($response->json());
        return view('admin.nutrition.edit', compact('menu'));
    }

    public function update(Request $request, $id)
    {
        if ((session('user')['role'] ?? '') !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name' => 'required',
            'nutrition' => 'required',
            'ingredients' => 'required',
            'instructions' => 'required',
            'category' => 'required|in:pagi,siang,malam,snack',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->except(['image', '_method']);
        $data['_method'] = 'PUT'; // API client uses POST for multipart, so we spoof PUT
        $file = $request->file('image');

        $response = $this->api->postMultipart("/admin/nutrition/{$id}", $data, $file, 'image');

        if ($response->successful()) {
            return redirect()->route('admin.nutrition.index')->with('success', 'Menu berhasil diperbarui');
        }

        return back()->with('error', 'Gagal memperbarui menu')->withInput();
    }

    public function destroy($id)
    {
        if ((session('user')['role'] ?? '') !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $this->api->delete("/admin/nutrition/{$id}");

        return redirect()->route('admin.nutrition.index')->with('success', 'Menu berhasil dihapus');
    }

    public function user(Request $request)
    {
        if ((session('user')['role'] ?? '') !== 'orangtua') {
            abort(403, 'Unauthorized');
        }

        $kategoriList = ['pagi', 'siang', 'malam', 'snack'];
        $kategoris = collect($kategoriList)->map(function($kategori) {
            return (object)[
                'id' => $kategori,
                'name' => ucfirst($kategori)
            ];
        });

        $kategoriIds = $request->input('kategori', []);

        $response = $this->api->get('/nutrition', [
            'kategori' => $kategoriIds
        ]);
        
        $json = $response->successful() ? $response->json() : [];
        $menus = collect($json)->map(function ($item) {
            return (new NutritionRecommendation)->forceFill((array)$item);
        });

        return view('orangtua.nutritionUs.index', compact('menus', 'kategoris', 'kategoriIds'));
    }

    public function userShow($id)
    {
        if ((session('user')['role'] ?? '') !== 'orangtua') {
            abort(403, 'Unauthorized');
        }

        $response = $this->api->get("/nutrition/{$id}");
        if (!$response->successful()) abort(404);

        $menu = (new NutritionRecommendation)->forceFill((array)$response->json());
        return view('orangtua.nutritionus.show', compact('menu'));
    }
}
