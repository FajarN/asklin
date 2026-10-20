<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AnggotaApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WilayahApiController;
use App\Http\Controllers\Api\AnggotaProfileApiController;
use App\Http\Controllers\Api\DocumentApiController;
use App\Http\Controllers\Api\PengurusDashboardApiController;
use App\Http\Controllers\Api\BeritaApiController;
use App\Http\Controllers\Api\AgendaApiController;
use App\Http\Controllers\Api\OrganisasiApiController;
use App\Http\Controllers\Api\PembayaranApiController;
use App\Http\Controllers\Api\PemilihanApiController;

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
        Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:5,1');
    });

    // --- 2. Master Data Wilayah (Publik) ---
    Route::prefix('wilayah')->group(function () {
        Route::get('/provinsi', [WilayahApiController::class, 'getProvinces']);
        Route::get('/kota', [WilayahApiController::class, 'getCities']);
        Route::get('/kecamatan', [WilayahApiController::class, 'getDistricts']);
        Route::get('/kelurahan', [WilayahApiController::class, 'getVillages']);
    });

    // --- 3. Informasi Berita & Artikel (Publik) ---
    Route::prefix('berita')->group(function () {
        Route::get('/', [BeritaApiController::class, 'index']);
        Route::get('/{identifier}', [BeritaApiController::class, 'show']);
    });

    // --- 4. Agenda & Kegiatan (Publik) ---
    Route::prefix('agenda')->group(function () {
        Route::get('/', [AgendaApiController::class, 'index']);
        Route::get('/{identifier}', [AgendaApiController::class, 'show']);
    });

    // --- 5. Direktori Struktur Organisasi (Publik) ---
    Route::prefix('organisasi')->group(function () {
        Route::get('/struktur', [OrganisasiApiController::class, 'index']);
    });

    // --- 3. Endpoints Terproteksi (Bearer Token Sanctum) ---
    Route::middleware('auth:sanctum')->group(function () {

        // Autentikasi Sesi
        Route::prefix('auth')->group(function () {
            Route::get('/me', [AuthController::class, 'me']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/change-password', [AuthController::class, 'changePassword']);
            Route::post('/update-photo', [AuthController::class, 'updatePhoto']);
        });

        // Modul Anggota (Klinik)
        Route::prefix('anggota')->group(function () {
            Route::get('/profil', [AnggotaProfileApiController::class, 'getProfil']);
            Route::get('/status-keanggotaan', [AnggotaProfileApiController::class, 'getStatusKeanggotaan']);
            Route::get('/dokumen-saya', [AnggotaProfileApiController::class, 'getDokumenSaya']);
            Route::post('/pendaftaran', [AnggotaProfileApiController::class, 'simpanPendaftaran']);
            Route::post('/sdm', [AnggotaProfileApiController::class, 'simpanSdm']);
        });

        // Modul Dokumen Digital (Unduh Aman PDF Anti-IDOR)
        Route::prefix('dokumen')->group(function () {
            Route::get('/sk-anggota/download', [DocumentApiController::class, 'downloadSkAnggota']);
            Route::get('/sertifikat/download', [DocumentApiController::class, 'downloadSertifikatAnggota']);
        });

        // Modul Pembayaran Iuran (Klinik)
        Route::prefix('pembayaran')->group(function () {
            Route::get('/status', [PembayaranApiController::class, 'getStatus']);
            Route::get('/riwayat', [PembayaranApiController::class, 'getRiwayat']);
            Route::post('/konfirmasi', [PembayaranApiController::class, 'konfirmasiPembayaran']);
        });

        // Modul Validasi Pembayaran (Bendahara / Pengurus)
        Route::prefix('bendahara')->group(function () {
            Route::get('/pembayaran', [PembayaranApiController::class, 'getBendaharaList']);
            Route::post('/pembayaran/{id}/verifikasi', [PembayaranApiController::class, 'verifikasiPembayaran']);
        });

        // Modul Pemilihan Ketua Cabang & Daerah (5 Tahunan)
        Route::prefix('pemilihan')->group(function () {
            Route::get('/aktif', [PemilihanApiController::class, 'getPemilihanAktif']);
            Route::get('/{id}', [PemilihanApiController::class, 'getDetail']);
            Route::post('/{id}/vote', [PemilihanApiController::class, 'submitVote']);
            Route::get('/{id}/hasil', [PemilihanApiController::class, 'getHasil']);
        });

        // Modul Pengurus (Cabang, Daerah, Pusat)
        Route::prefix('pengurus')->group(function () {
            Route::get('/dashboard', [PengurusDashboardApiController::class, 'getDashboard']);
            Route::get('/anggota', [PengurusDashboardApiController::class, 'getAnggotaList']);
            Route::get('/anggota/{id}', [PengurusDashboardApiController::class, 'getAnggotaDetail']);
            Route::post('/verifikasi/{id}', [PengurusDashboardApiController::class, 'prosesVerifikasi']);
        });
    });
});