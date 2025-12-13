<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\Dokter\JadwalPeriksaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Dokter\PeriksaPasienController;
use App\Http\Controllers\Dokter\RiwayatPasienController;
use App\Http\Controllers\Pasien\PoliController as PasienPoliController;

// =========================
// AUTH
// =========================
Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =========================
// DOKTER
// =========================
Route::middleware(['auth', 'role:dokter'])
    ->prefix('dokter')
    ->group(function () {

        Route::get('/dashboard', fn () => view('dokter.dashboard'))
            ->name('dokter.dashboard');

        Route::resource('jadwal-periksa', JadwalPeriksaController::class);

        // =========================
        // PERIKSA PASIEN (CARA LAMA)
        // =========================
        Route::get('/periksa-pasien', [PeriksaPasienController::class, 'index'])
            ->name('periksa-pasien.index');

        Route::get('/periksa-pasien/{id}', [PeriksaPasienController::class, 'create'])
            ->name('periksa-pasien.create');

        Route::post('/periksa-pasien', [PeriksaPasienController::class, 'store'])
            ->name('periksa-pasien.store');

        // =========================
        // RIWAYAT PASIEN
        // =========================
        Route::get('/riwayat-pasien', [RiwayatPasienController::class, 'index'])
            ->name('dokter.riwayat-pasien.index');

        Route::get('/riwayat-pasien/{id}', [RiwayatPasienController::class, 'show'])
            ->name('dokter.riwayat-pasien.show');
    });

    
// =========================
// PASIEN
// =========================
Route::middleware(['auth', 'role:pasien'])
    ->prefix('pasien')
    ->group(function () {

        Route::get('/dashboard', fn () => view('pasien.dashboard'))
            ->name('pasien.dashboard');

        Route::get('/daftar', [PasienPoliController::class, 'get'])
            ->name('pasien.daftar');

        Route::post('/daftar', [PasienPoliController::class, 'submit'])
            ->name('pasien.daftar.submit');
    });

// =========================
// ADMIN
// =========================
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('admin.dashboard');

        Route::resource('polis', PoliController::class);
        Route::resource('dokter', DokterController::class);
        Route::resource('pasien', PasienController::class);
        Route::resource('obat', ObatController::class);

        // ===== STOK OBAT =====

        // (LAMA - redirect)
        Route::post('/obat/{id}/stok/tambah', [ObatController::class, 'addStock'])
            ->name('obat.stok.add');

        Route::post('/obat/{id}/stok/kurangi', [ObatController::class, 'reduceStock'])
            ->name('obat.stok.reduce');

        // (BARU - AJAX)
        Route::post('/obat/{id}/stok/ajax', [ObatController::class, 'updateStokAjax'])
            ->name('obat.stok.ajax');
    });
