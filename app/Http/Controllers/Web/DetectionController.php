<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ApiClient;
use Illuminate\Http\Request;

class DetectionController extends Controller
{
    protected ApiClient $api;

    public function __construct(ApiClient $api)
    {
        $this->api = $api;
    }

    public function create()
    {
        $childId = session('selected_child_id');
        $response = $this->api->get("/children/{$childId}/detections");
        $semua = $response->successful() ? $response->json() : [];

        return view('orangtua.detections.deteksi', compact('semua'));
    }

    public function store(Request $request)
    {
        $childId = session('selected_child_id');

        $request->validate([
            'umur'          => 'required|integer',
            'jenis_kelamin' => 'required|in:L,P',
            'berat_badan'   => 'required|numeric',
            'tinggi_badan'  => 'required|numeric',
        ]);

        $response = $this->api->post("/children/{$childId}/detections", $request->only([
            'umur', 'jenis_kelamin', 'berat_badan', 'tinggi_badan',
        ]));

        if ($response->successful()) {
            return redirect()->route('orangtua.detections.create')->with('success', 'Deteksi berhasil disimpan!');
        }

        return back()->with('error', $response->json('message', 'Gagal menyimpan deteksi.'));
    }

    public function destroy($id)
    {
        $childId = session('selected_child_id');
        $response = $this->api->delete("/children/{$childId}/detections/{$id}");

        if ($response->successful()) {
            return redirect()->back()->with('success', 'Data berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Gagal menghapus data.');
    }

    // Admin
    public function adminIndex()
    {
        $response = $this->api->get('/admin/detections');
        $semua = $response->successful() ? collect($response->json())->map(function ($item) {
            $childRelation = $item['child'] ?? null;
            unset($item['child']);
            
            $detection = (new \App\Models\Detection)->forceFill((array)$item);
            if ($childRelation) {
                $childData = $childRelation;
                $userRelation = $childData['user'] ?? null;
                unset($childData['user']);
                $child = (new \App\Models\Child)->forceFill((array)$childData);
                if ($userRelation) {
                    $child->setRelation('user', (new \App\Models\User)->forceFill((array)$userRelation));
                }
                $detection->setRelation('child', $child);
            }
            return $detection;
        }) : collect();
        return view('admin.detections.index', compact('semua'));
    }

    public function adminCreate()
    {
        $response = $this->api->get('/admin/children');
        $users = collect($response->successful() ? $response->json() : [])->map(function ($item) {
            $userRelation = $item['user'] ?? null;
            unset($item['user']);
            $child = (new \App\Models\Child)->forceFill((array)$item);
            if ($userRelation) {
                $child->setRelation('user', (new \App\Models\User)->forceFill((array)$userRelation));
            }
            return $child;
        });

        return view('admin.detections.create', compact('users'));
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'child_id'      => 'required|integer',
            'umur'          => 'required|integer',
            'jenis_kelamin' => 'required|in:L,P',
            'berat_badan'   => 'required|numeric',
            'tinggi_badan'  => 'required|numeric',
        ]);

        $response = $this->api->post("/admin/detections", $request->only([
            'child_id', 'umur', 'jenis_kelamin', 'berat_badan', 'tinggi_badan',
        ]));

        if ($response->successful()) {
            return redirect()->route('admin.detections.index')->with('success', 'Deteksi berhasil disimpan!');
        }

        return back()->with('error', collect($response->json('errors'))->flatten()->first() ?: $response->json('message', 'Gagal menyimpan deteksi.'));
    }

    public function exportPdf()
    {
        $response = $this->api->get('/admin/detections');
        $semua = $response->successful() ? collect($response->json())->map(function ($item) {
            $childRelation = $item['child'] ?? null;
            unset($item['child']);
            
            $detection = (new \App\Models\Detection)->forceFill((array)$item);
            if ($childRelation) {
                $childData = $childRelation;
                $userRelation = $childData['user'] ?? null;
                unset($childData['user']);
                $child = (new \App\Models\Child)->forceFill((array)$childData);
                if ($userRelation) {
                    $child->setRelation('user', (new \App\Models\User)->forceFill((array)$userRelation));
                }
                $detection->setRelation('child', $child);
            }
            return $detection;
        }) : collect();

        $html = view('admin.detections.pdf', compact('semua'))->render();
        
        $pdf = \PDF::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        
        return $pdf->download('Laporan_Data_Deteksi_Stunting_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}
