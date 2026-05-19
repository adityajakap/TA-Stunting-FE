<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Child;
use App\Services\ApiClient;
use Illuminate\Http\Request;

class TahapanPerkembanganController extends Controller
{
    protected ApiClient $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    public function index(Request $request)
    {
        $childId = session('selected_child_id');
        if (!$childId) {
            return redirect()->route('orangtua.dashboard')->with('error', 'Pilih anak terlebih dahulu.');
        }

        $selectedKategori = $request->input('kategori', []);

        $response = $this->api->get("/children/{$childId}/perkembangan", [
            'kategori' => $selectedKategori,
        ]);

        $responseData = $response->successful() ? $response->json() : ['child' => null, 'milestones' => []];
        $childData = $responseData['child'] ?? [];
        $groupedData = collect($responseData['milestones'] ?? []);

        // Use Child model so computed attributes (umur_bulan) work in Blade
        $child = (new Child)->forceFill((array)$childData);

        $kategoriOptions = collect([
            (object)['id' => 'Motorik', 'name' => 'Motorik'],
            (object)['id' => 'Bahasa',  'name' => 'Bahasa'],
            (object)['id' => 'Gigi',    'name' => 'Gigi'],
        ]);

        // Normalize data structure to match what Blade expects
        $groupedData = $groupedData->map(function ($items) {
            return collect($items)->map(function ($item) {
                return (object)[
                    'tahapan'      => (object)($item['tahapan'] ?? []),
                    'achieved_data'=> isset($item['pencapaian']) ? (object)$item['pencapaian'] : null,
                    'status_detail'=> $item['status_detail'] ?? [],
                ];
            });
        });

        return view('orangtua.tahapan_perkembangan.index', [
            'groupedData'   => $groupedData,
            'kategoris'     => $kategoriOptions,
            'kategoriIds'   => $selectedKategori,
            'action'        => route('orangtua.tahapan_perkembangan.index'),
            'child'         => $child,
        ]);
    }

    public function create()
    {
        // Master tahapan dari BE
        $response = $this->api->get('/admin/children/0/perkembangan'); // returns full list
        // Simpler: just provide the list from a dedicated endpoint — but for now use session child
        $childId = session('selected_child_id');
        // Get from a simple master endpoint (we'll expose api/tahapan)
        $masterResponse = $this->api->get('/tahapan-master');
        $tahapanPerkembangan = $masterResponse->successful() ? $masterResponse->json() : [];

        return view('orangtua.tahapan_perkembangan.create', compact('tahapanPerkembangan'));
    }

    public function store(Request $request)
    {
        $childId = session('selected_child_id');

        $request->validate([
            'tahapan_perkembangan_id' => 'required|integer',
            'tanggal_pencapaian'      => 'required|date|before_or_equal:today',
            'catatan'                 => 'nullable|string',
        ]);

        $response = $this->api->post("/children/{$childId}/perkembangan", $request->only([
            'tahapan_perkembangan_id', 'tanggal_pencapaian', 'catatan',
        ]));

        if ($response->successful()) {
            return redirect()->route('orangtua.tahapan_perkembangan.index')
                ->with('success', 'Pencapaian berhasil ditambahkan.');
        }

        return back()->with('error', $response->json('message', 'Gagal menyimpan pencapaian.'));
    }

    public function update(Request $request, $id)
    {
        $childId = session('selected_child_id');

        $request->validate([
            'tanggal_pencapaian' => 'required|date|before_or_equal:today',
            'catatan'            => 'nullable|string',
        ]);

        $response = $this->api->put("/children/{$childId}/perkembangan/{$id}", $request->only([
            'tanggal_pencapaian', 'catatan',
        ]));

        if ($response->successful()) {
            return redirect()->route('orangtua.tahapan_perkembangan.index')
                ->with('success', 'Pencapaian berhasil diupdate.');
        }

        return back()->with('error', 'Gagal mengupdate pencapaian.');
    }

    public function destroy($id)
    {
        $childId = session('selected_child_id');
        $this->api->delete("/children/{$childId}/perkembangan/{$id}");
        return redirect()->route('orangtua.tahapan_perkembangan.index')
            ->with('success', 'Pencapaian dihapus.');
    }

    // Admin: show child milestones
    public function adminShow($childId)
    {
        $response = $this->api->get("/admin/children/{$childId}/perkembangan");
        $responseData = $response->successful() ? $response->json() : ['child' => null, 'milestones' => []];

        // Use Child model so computed attributes (umur_bulan) work in Blade
        $child = (new Child)->forceFill((array)($responseData['child'] ?? []));
        $groupedData = collect($responseData['milestones'] ?? [])->map(function ($items) {
            return collect($items)->map(function ($item) {
                return (object)[
                    'tahapan'      => (object)($item['tahapan'] ?? []),
                    'achieved_data'=> isset($item['pencapaian']) ? (object)$item['pencapaian'] : null,
                    'status_detail'=> $item['status_detail'] ?? [],
                ];
            });
        });

        return view('admin.tahapan_perkembangan.children_show', compact('child', 'groupedData'));
    }
}
