<?php

namespace App\Http\Controllers;

use App\Models\TahapanPerkembangan;
use App\Models\TahapanPerkembanganData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TahapanPerkembanganDataController extends Controller
{
    protected \App\Services\ApiClient $api;

    public function __construct(\App\Services\ApiClient $api)
    {
        $this->api = $api;
    }

    public function index(Request $request)
    {
        if ((session('user')['role'] ?? '') !== 'orangtua') {
            abort(403, 'Unauthorized');
        }

        $childId = session('selected_child_id');
        
        $kategoriOptions = collect([
            (object)['id' => 'Motorik', 'name' => 'Motorik'],
            (object)['id' => 'Bahasa', 'name' => 'Bahasa'],
            (object)['id' => 'Gigi', 'name' => 'Gigi'],
        ]);

        $selectedKategori = $request->input('kategori', []);

        $response = $this->api->get("/children/{$childId}/perkembangan", [
            'kategori' => $selectedKategori
        ]);
        
        $json = $response->successful() ? $response->json() : ['child' => null, 'milestones' => []];
        $childData = $json['child'] ?? [];
        $milestonesGrouped = $json['milestones'] ?? [];

        $child = (new \App\Models\Child)->forceFill((array)$childData);

        // Map the nested arrays to objects
        $groupedData = collect($milestonesGrouped)->map(function ($items) {
            return collect($items)->map(function ($item) {
                return (object)[
                    'tahapan' => (new TahapanPerkembangan)->forceFill((array)$item['tahapan']),
                    'achieved_data' => $item['pencapaian'] ? (new TahapanPerkembanganData)->forceFill((array)$item['pencapaian']) : null,
                    'status_detail' => (object)$item['status_detail']
                ];
            });
        });

        return view('orangtua.tahapan_perkembangan.index', [
            'groupedData' => $groupedData,
            'kategoris' => $kategoriOptions,
            'kategoriIds' => $selectedKategori,
            'action' => route('orangtua.tahapan_perkembangan.index'),
            'child' => $child
        ]);
    }

    public function create()
    {
        if ((session('user')['role'] ?? '') !== 'orangtua') {
            abort(403, 'Unauthorized');
        }

        $response = $this->api->get('/tahapan-master');
        $tahapanPerkembangan = collect($response->successful() ? $response->json() : [])->map(function ($item) {
            return (new TahapanPerkembangan)->forceFill((array)$item);
        });
        
        return view('orangtua.tahapan_perkembangan.create', compact('tahapanPerkembangan'));
    }

    public function edit($id)
    {
        if ((session('user')['role'] ?? '') !== 'orangtua') {
            abort(403, 'Unauthorized');
        }

        // To edit, we need the specific achievement data.
        // It's not straightforward without a specific GET /children/{child}/perkembangan/{id} endpoint.
        // As a workaround, we can fetch all and filter, or just rely on the API to update it.
        $childId = session('selected_child_id');
        $response = $this->api->get("/children/{$childId}/perkembangan");
        
        $tahapanPerkembanganData = null;
        if ($response->successful()) {
            $milestonesGrouped = $response->json()['milestones'] ?? [];
            foreach ($milestonesGrouped as $category => $items) {
                foreach ($items as $item) {
                    if ($item['pencapaian'] && $item['pencapaian']['id'] == $id) {
                        $tahapanPerkembanganData = (new TahapanPerkembanganData)->forceFill((array)$item['pencapaian']);
                        break 2;
                    }
                }
            }
        }

        if (!$tahapanPerkembanganData) abort(404);

        $responseMaster = $this->api->get('/tahapan-master');
        $tahapanPerkembangan = collect($responseMaster->successful() ? $responseMaster->json() : [])->map(function ($item) {
            return (new TahapanPerkembangan)->forceFill((array)$item);
        });

        return view('orangtua.tahapan_perkembangan.edit', compact('tahapanPerkembanganData', 'tahapanPerkembangan'));
    }

    public function store(Request $request)
    {
        if ((session('user')['role'] ?? '') !== 'orangtua') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'tahapan_perkembangan_id' => 'required|numeric',
            'tanggal_pencapaian' => 'required|date|before_or_equal:today',
            'catatan' => 'nullable|string',
        ]);

        $childId = session('selected_child_id');
        $response = $this->api->post("/children/{$childId}/perkembangan", $request->only([
            'tahapan_perkembangan_id', 'tanggal_pencapaian', 'catatan'
        ]));

        if ($response->successful()) {
            return redirect()->route('orangtua.tahapan_perkembangan.index')->with('success', 'Pencapaian tahapan perkembangan berhasil ditambahkan.');
        }

        return redirect()->back()->withErrors(['message' => 'Gagal menyimpan data'])->withInput();
    }

    public function update(Request $request, $id)
    {
        if ((session('user')['role'] ?? '') !== 'orangtua') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'tanggal_pencapaian' => 'required|date|before_or_equal:today',
            'catatan' => 'nullable|string',
        ]);

        $childId = session('selected_child_id');
        $response = $this->api->put("/children/{$childId}/perkembangan/{$id}", $request->only([
            'tanggal_pencapaian', 'catatan'
        ]));

        if ($response->successful()) {
            return redirect()->route('orangtua.tahapan_perkembangan.index')->with('success', 'Pencapaian tahapan perkembangan berhasil diupdate.');
        }

        return redirect()->back()->withErrors(['message' => 'Gagal memperbarui data'])->withInput();
    }

    public function destroy($id)
    {
        if ((session('user')['role'] ?? '') !== 'orangtua') {
            abort(403, 'Unauthorized');
        }
        
        $childId = session('selected_child_id');
        $this->api->delete("/children/{$childId}/perkembangan/{$id}");

        return redirect()->route('orangtua.tahapan_perkembangan.index')->with('success', 'Pencapaian tahapan perkembangan berhasil dihapus.');
    }
}