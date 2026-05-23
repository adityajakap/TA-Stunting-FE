<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Child;
use App\Models\TahapanPerkembangan;
use App\Models\TahapanPerkembanganData;
use Illuminate\Http\Request;

class AdminTahapanPerkembanganDataController extends Controller
{
    protected \App\Services\ApiClient $api;

    public function __construct(\App\Services\ApiClient $api)
    {
        $this->api = $api;
    }

    // Tampilkan daftar anak (user dengan role orangtua)
    public function index(Request $request)
    {
        if ((session('user')['role'] ?? '') !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $response = $this->api->get('/admin/children');
        $childrenData = $response->successful() ? $response->json() : [];

        $children = collect($childrenData)->map(function ($childData) {
            $userRelation = $childData['user'] ?? null;
            unset($childData['user']);
            $child = (new Child)->forceFill((array)$childData);
            if ($userRelation) {
                $child->setRelation('user', (new User)->forceFill((array)$userRelation));
            }
            return $child;
        });
        
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $children = $children->filter(function ($c) use ($search) {
                return str_contains(strtolower($c->nama_lengkap_anak), $search);
            });
        }

        return view('admin.tahapan_perkembangan.children_index', compact('children'));
    }

    private function getChildMilestones($childId)
    {
        $response = $this->api->get("/admin/children/{$childId}/perkembangan");
        
        if (!$response->successful()) {
            return [null, collect()];
        }
        
        $json = $response->json();
        $childData = $json['child'] ?? [];
        $milestonesGrouped = $json['milestones'] ?? [];

        $userRelation = $childData['user'] ?? null;
        unset($childData['user']);
        $child = (new Child)->forceFill((array)$childData);
        if ($userRelation) {
            $child->setRelation('user', (new User)->forceFill((array)$userRelation));
        }

        $groupedData = collect($milestonesGrouped)->map(function ($items) {
            return collect($items)->map(function ($item) {
                return (object)[
                    'tahapan' => (new TahapanPerkembangan)->forceFill((array)$item['tahapan']),
                    'achieved_data' => $item['pencapaian'] ? (new TahapanPerkembanganData)->forceFill((array)$item['pencapaian']) : null,
                    'status_detail' => $item['status_detail']
                ];
            });
        });

        return [$child, $groupedData];
    }

    // Tampilkan daftar pencapaian untuk satu anak
    public function show($userId)
    {
        if ((session('user')['role'] ?? '') !== 'admin') {
            abort(403, 'Unauthorized');
        }

        list($child, $groupedData) = $this->getChildMilestones($userId);
        if (!$child) abort(404);

        return view('admin.tahapan_perkembangan.children_show', compact('child', 'groupedData'));
    }

    public function exportPdf($userId)
    {
        if ((session('user')['role'] ?? '') !== 'admin') {
            abort(403, 'Unauthorized');
        }

        list($child, $groupedData) = $this->getChildMilestones($userId);
        if (!$child) abort(404);

        $html = view('admin.tahapan_perkembangan.pdf', compact('child', 'groupedData'))->render();
        
        $pdf = \PDF::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('Laporan_Perkembangan_' . str_replace(' ', '_', $child->nama_lengkap_anak) . '_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    // Tampilkan form tambah pencapaian untuk anak tertentu
    public function create($userId)
    {
        if ((session('user')['role'] ?? '') !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // We just need the child's basic info for the view title, we can get it from /admin/children
        $response = $this->api->get("/admin/children");
        $childrenData = $response->successful() ? $response->json() : [];
        $childData = collect($childrenData)->firstWhere('id', $userId);
        if (!$childData) abort(404);
        
        $child = (new Child)->forceFill((array)$childData);

        $responseMaster = $this->api->get('/tahapan-master');
        $tahapanPerkembangan = collect($responseMaster->successful() ? $responseMaster->json() : [])->map(function ($item) {
            return (new TahapanPerkembangan)->forceFill((array)$item);
        });

        return view('admin.tahapan_perkembangan.children_create', compact('child', 'tahapanPerkembangan'));
    }

    // Simpan pencapaian yang ditambahkan oleh admin untuk anak tertentu
    public function store(Request $request, $userId)
    {
        if ((session('user')['role'] ?? '') !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'tahapan_perkembangan_id' => 'required|numeric',
            'tanggal_pencapaian' => 'required|date|before_or_equal:today',
            'catatan' => 'nullable|string',
        ]);

        $response = $this->api->post("/admin/children/{$userId}/perkembangan", $request->only([
            'tahapan_perkembangan_id', 'tanggal_pencapaian', 'catatan'
        ]));

        if ($response->successful()) {
            return redirect()->route('admin.perkembangan.children.show', $userId)->with('success', 'Pencapaian berhasil ditambahkan.');
        }

        return redirect()->back()->withErrors(['message' => 'Gagal menambahkan pencapaian.'])->withInput();
    }
}
