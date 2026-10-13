<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AnggotaApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WilayahApiController;
use App\Http\Controllers\Api\AnggotaProfileApiController;
use App\Http\Controllers\Api\DocumentApiController;
use App\Http\Controllers\Api\PengurusDashboardApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Legacy User Endpoint
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Legacy Sync Endpoints (Static Key)
Route::prefix('anggota')->middleware('throttle:60,1')->group(function () {
    Route::get('/location', [AnggotaApiController::class, 'getAnggotaByLocation']);
    Route::get('/updates/latest', [AnggotaApiController::class, 'getLastUpdatedAnggota']);
    Route::get('/{id}', [AnggotaApiController::class, 'getAnggotaById']);
});

/*
|--------------------------------------------------------------------------
| ASKLIN MOBILE API VERSION 1 (REST API)
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->middleware('throttle:60,1')->group(function () {

    // --- 1. Autentikasi Publik ---
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    });

    // --- 2. Master Data Wilayah (Publik) ---
    Route::prefix('wilayah')->group(function () {
        Route::get('/provinsi', [WilayahApiController::class, 'getProvinces']);
        Route::get('/kota', [WilayahApiController::class, 'getCities']);
        Route::get('/kecamatan', [WilayahApiController::class, 'getDistricts']);
        Route::get('/kelurahan', [WilayahApiController::class, 'getVillages']);
    });

    // --- 3. Endpoints Terproteksi (Bearer Token Sanctum) ---
    Route::middleware('auth:sanctum')->group(function () {

        // Autentikasi Sesi
        Route::prefix('auth')->group(function () {
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
        });

        // Modul Anggota (Klinik)
        Route::prefix('anggota')->group(function () {
            Route::get('/profil', [AnggotaProfileApiController::class, 'getProfil']);
            Route::get('/status-keanggotaan', [AnggotaProfileApiController::class, 'getStatusKeanggotaan']);
            Route::get('/dokumen-saya', [AnggotaProfileApiController::class, 'getDokumenSaya']);
        });

        // Modul Dokumen Digital (Unduh Aman PDF Anti-IDOR)
        Route::prefix('dokumen')->group(function () {
            Route::get('/sk-anggota/download', [DocumentApiController::class, 'downloadSkAnggota']);
            Route::get('/sertifikat/download', [DocumentApiController::class, 'downloadSertifikatAnggota']);
        });

        // Modul Pengurus (Cabang, Daerah, Pusat)
        Route::prefix('pengurus')->group(function () {
            Route::get('/dashboard', [PengurusDashboardApiController::class, 'getDashboard']);
            Route::get('/anggota', [PengurusDashboardApiController::class, 'getAnggotaList']);
            Route::get('/anggota/{id}', [PengurusDashboardApiController::class, 'getAnggotaDetail']);
        });
    });
});