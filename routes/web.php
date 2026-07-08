<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerkembanganController;
use App\Http\Controllers\NtobController;
use App\Http\Controllers\SkdnController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\DetectionController;
use App\Http\Controllers\Web\TahapanPerkembanganController;
use App\Http\Controllers\{
    ArtikelController,
    ArtikelKategoriController,
    BMICalculatorController,
    NutritionController,
    UserArtikelController,
    AdminTahapanPerkembanganDataController,
    TahapanPerkembanganController as TahapanMasterController,
};
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

Route::get('/', fn() => redirect('/login'));

// ─── Auth ────────────────────────────────────────────────────────────────────
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register']);
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

// ─── Authenticated routes ─────────────────────────────────────────────────────
Route::middleware('api.auth')->group(function () {

    // Dashboard Orangtua
    Route::get('/orangtua/dashboard', [DashboardController::class, 'orangtua'])->name('orangtua.dashboard');
    Route::post('/orangtua/add-child',    [DashboardController::class, 'addChild'])->name('orangtua.children.store');
    Route::post('/orangtua/select-child', [DashboardController::class, 'selectChild'])->name('orangtua.children.select');

    // Dashboard Admin
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');

    // Profile
    Route::get('/profile', fn() => view('profile'))->name('profile');

    // ─── Deteksi Stunting (Orangtua) ──────────────────────────────────────────
    Route::middleware('child.selected')->group(function () {
        Route::get('/orangtua/deteksi-stunting',         [DetectionController::class, 'create'])->name('orangtua.detections.create');
        Route::post('/orangtua/deteksi-stunting',        [DetectionController::class, 'store'])->name('orangtua.detections.store');
        Route::delete('/orangtua/deteksi-stunting/{id}', [DetectionController::class, 'destroy'])->name('orangtua.detections.destroy');

        // Tahapan Perkembangan (Orangtua)
        Route::prefix('orangtua')->name('orangtua.')->group(function () {
            Route::get('tahapan_perkembangan',                 [TahapanPerkembanganController::class, 'index'])->name('tahapan_perkembangan.index');
            Route::get('tahapan_perkembangan/create',          [TahapanPerkembanganController::class, 'create'])->name('tahapan_perkembangan.create');
            Route::post('tahapan_perkembangan',                [TahapanPerkembanganController::class, 'store'])->name('tahapan_perkembangan.store');
            Route::put('tahapan_perkembangan/{id}',            [TahapanPerkembanganController::class, 'update'])->name('tahapan_perkembangan.update');
            Route::delete('tahapan_perkembangan/{id}',         [TahapanPerkembanganController::class, 'destroy'])->name('tahapan_perkembangan.destroy');
        });

        // BMI
        Route::get('/bmi',              [BMICalculatorController::class, 'showBmiData'])->name('bmi');
        Route::post('/hitung-bmi',      [BMICalculatorController::class, 'calculate'])->name('hitung-bmi');
        Route::post('/simpan-bmi',      [BMICalculatorController::class, 'save'])->name('simpan-bmi');
        Route::post('/reset-bmi',       [BMICalculatorController::class, 'reset'])->name('reset-bmi');
        Route::post('/hapus-bmi/{index}',[BMICalculatorController::class, 'deleteRow'])->name('hapus-bmi-row');
        Route::post('/calculate-calories',[BMICalculatorController::class, 'hitungKalori'])->name('calculate.calories');

        // Nutrition
        Route::get('/orangtua/nutritionUs', [NutritionController::class, 'user'])->name('orangtua.nutritionUs.index');
        Route::get('/orangtua/nutritionUs/{id}', [NutritionController::class, 'userShow'])->name('orangtua.nutritionUs.show');
    });

    // ─── Deteksi Stunting (Admin) ─────────────────────────────────────────────
    Route::get('/admin/deteksi-stunting',         [DetectionController::class, 'adminIndex'])->name('admin.detections.index');
    Route::get('/admin/deteksi-stunting/export-pdf', [DetectionController::class, 'exportPdf'])->name('admin.detections.export-pdf');
    Route::get('/admin/deteksi-stunting/create',  [DetectionController::class, 'adminCreate'])->name('admin.detections.create');
    Route::post('/admin/deteksi-stunting',        [DetectionController::class, 'adminStore'])->name('admin.detections.store');

    // ─── Admin (Artikel, Nutrisi, Perkembangan, NTOB, SKDN) ───────────────────────────────
    Route::prefix('admin')->name('admin.')->group(function () {
        
        // Laporan NTOB & SKDN
        Route::get('/ntob', [NtobController::class, 'index'])->name('ntob.index');
        Route::get('/ntob/{month}/{year}', [NtobController::class, 'show'])->name('ntob.show');
        Route::get('/ntob/{month}/{year}/pdf', [NtobController::class, 'exportPdf'])->name('ntob.pdf');
        Route::get('/skdn', [SkdnController::class, 'index'])->name('skdn.index');
        Route::get('/skdn/grafik', [SkdnController::class, 'grafik'])->name('skdn.grafik');
        Route::get('/skdn/{month}/{year}', [SkdnController::class, 'show'])->name('skdn.show');
        Route::post('/skdn/{month}/{year}/target', [SkdnController::class, 'storeTarget'])->name('skdn.target.store');
        Route::get('/skdn/{month}/{year}/pdf', [SkdnController::class, 'exportPdf'])->name('skdn.pdf');
        // Artikel
        Route::prefix('artikel/kategori')->name('artikel.kategori.')->group(function () {
            Route::get('/',              [ArtikelKategoriController::class, 'index'])->name('index');
            Route::get('/create',        [ArtikelKategoriController::class, 'create'])->name('create');
            Route::post('/',             [ArtikelKategoriController::class, 'store'])->name('store');
            Route::get('/{k}/edit',      [ArtikelKategoriController::class, 'edit'])->name('edit');
            Route::put('/{k}',           [ArtikelKategoriController::class, 'update'])->name('update');
            Route::delete('/{k}',        [ArtikelKategoriController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('artikel')->name('artikel.')->group(function () {
            Route::get('/',              [ArtikelController::class, 'index'])->name('index');
            Route::get('/create',        [ArtikelController::class, 'create'])->name('create');
            Route::post('/',             [ArtikelController::class, 'store'])->name('store');
            Route::get('/{id}',          [ArtikelController::class, 'show'])->name('show');
            Route::get('/{id}/edit',     [ArtikelController::class, 'edit'])->name('edit');
            Route::put('/{id}',          [ArtikelController::class, 'update'])->name('update');
            Route::delete('/{id}',       [ArtikelController::class, 'destroy'])->name('destroy');
        });

        // Tahapan Perkembangan (Admin)
        Route::get('tahapan_perkembangan',             fn() => redirect()->route('admin.perkembangan.children.index'))->name('tahapan_perkembangan.index');
        Route::get('perkembangan/children',             [AdminTahapanPerkembanganDataController::class, 'index'])->name('perkembangan.children.index');
        Route::get('perkembangan/export-pdf',           [AdminTahapanPerkembanganDataController::class, 'exportAllPdf'])->name('perkembangan.export-pdf');
        Route::get('perkembangan/children/{user}',      [TahapanPerkembanganController::class, 'adminShow'])->name('perkembangan.children.show');
        Route::get('perkembangan/children/{user}/pdf',  [AdminTahapanPerkembanganDataController::class, 'exportPdf'])->name('perkembangan.children.pdf');
        Route::get('perkembangan/children/{user}/create',[AdminTahapanPerkembanganDataController::class, 'create'])->name('perkembangan.children.create');
        Route::post('perkembangan/children/{user}',     [AdminTahapanPerkembanganDataController::class, 'store'])->name('perkembangan.children.store');

        // Nutrition
        Route::resource('nutrition', NutritionController::class)->except(['show']);
    });

    // ─── Artikel untuk Orangtua ───────────────────────────────────────────────
    Route::prefix('orangtua/artikel')->name('orangtua.artikel.')->group(function () {
        Route::get('/',    [UserArtikelController::class, 'index'])->name('index');
        Route::get('/{id}',[UserArtikelController::class, 'show'])->name('show');
    });
});
