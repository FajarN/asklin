<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

// Wilayah
Route::post('getKota', [App\Http\Controllers\WilayahController::class, 'getKota'])->name('getKota');
Route::post('getKecamatan', [App\Http\Controllers\WilayahController::class, 'getKecamatan'])->name('getKecamatan');
Route::post('getKelurahan', [App\Http\Controllers\WilayahController::class, 'getKelurahan'])->name('getKelurahan');

Route::get('/cekqrcode/{id}', [App\Http\Controllers\CekQRCodeController::class, 'cekqrcode'])->name('cekqrcode');
Route::get('/cekqrsertifikat/{id}', [App\Http\Controllers\CekQRSertifikatController::class, 'cekqrsertifikat'])->name('cekqrsertifikat');

// Frontend
Route::get('/', [App\Http\Controllers\Frontend\HomeController::class, 'index'])->name('home');
Route::get('/pendaftaran_event', [App\Http\Controllers\Frontend\HomeController::class, 'pendaftaran_event'])->name('pendaftaran_event');
Route::post('/pendaftaran_event/daftar', [App\Http\Controllers\Frontend\HomeController::class, 'daftarEvent'])->name('pendaftaran_event.daftar');
Route::get('/sejarah', [App\Http\Controllers\Frontend\HomeController::class, 'sejarah'])->name('sejarah');
Route::get('/visi_misi', [App\Http\Controllers\Frontend\HomeController::class, 'visimisi'])->name('visimisi');
Route::get('/latar_belakang', [App\Http\Controllers\Frontend\HomeController::class, 'latarbelakang'])->name('latarbelakang');
Route::get('/pengurus_pusat', [App\Http\Controllers\Frontend\HomeController::class, 'penguruspusat'])->name('penguruspusat');
Route::get('/agenda_kegiatan', [App\Http\Controllers\Frontend\HomeController::class, 'agendakegiatan'])->name('agendakegiatan');
Route::get('/berita', [App\Http\Controllers\Frontend\HomeController::class, 'berita'])->name('berita');
Route::get('/berita/{slug}', [App\Http\Controllers\Frontend\HomeController::class, 'beritaDetail'])->name('berita.detail');
Route::get('/event-asklin', [App\Http\Controllers\Frontend\HomeController::class, 'event'])->name('event_asklin');
Route::get('/event-asklin/{slug}', [App\Http\Controllers\Frontend\HomeController::class, 'eventDetail'])->name('event_asklin.detail');
Route::get('/galery', [App\Http\Controllers\Frontend\HomeController::class, 'galery'])->name('galery');
Route::get('/kontak', [App\Http\Controllers\Frontend\HomeController::class, 'kontak'])->name('kontak');
Route::get('/konas', [App\Http\Controllers\Frontend\KonasController::class, 'index'])->name('konas');
Route::get('/konas/profil_acara', [App\Http\Controllers\Frontend\KonasController::class, 'profil'])->name('konas.profil');
Route::get('/konas/pendahuluan', [App\Http\Controllers\Frontend\KonasController::class, 'pendahuluan'])->name('konas.pendahuluan');
Route::get('/konas/sambutan_gubernur_aceh', [App\Http\Controllers\Frontend\KonasController::class, 'sambutan_gubernur_aceh'])->name('konas.sambutan_gubernur_aceh');
Route::get('/konas/sambutan_ketum', [App\Http\Controllers\Frontend\KonasController::class, 'sambutan_ketum'])->name('konas.sambutan_ketum');
Route::get('/konas/sambutan_ketua_aceh', [App\Http\Controllers\Frontend\KonasController::class, 'sambutan_ketua_aceh'])->name('konas.sambutan_ketua_aceh');
Route::get('/konas/sambutan_ketua_panitia', [App\Http\Controllers\Frontend\KonasController::class, 'sambutan_ketua_panitia'])->name('konas.sambutan_ketua_panitia');
Route::get('/konas/tour', [App\Http\Controllers\Frontend\KonasController::class, 'tour'])->name('konas.tour');
Route::get('/konas/konas_agenda', [App\Http\Controllers\Frontend\KonasController::class, 'konas_agenda'])->name('konas.konas_agenda');
Route::get('/konas/registrasi', [App\Http\Controllers\Frontend\KonasController::class, 'registrasi'])->name('konas.registrasi');
Route::post('/konas/registrasi/store', [App\Http\Controllers\Frontend\KonasController::class, 'store'])->name('konas.storepeserta');
Route::post('/konas/callback', [App\Http\Controllers\Frontend\KonasController::class, 'callback'])->name('konas.callback');
Route::group(['middleware' => ['auth']], function () {
    Route::get('/konas/akun', [App\Http\Controllers\Frontend\KonasController::class, 'akun'])->name('konas.akun');
    Route::get('/konas/bayar', [App\Http\Controllers\Frontend\KonasController::class, 'bayar'])->name('konas.bayar');
    Route::get('/konas/cetak_kartu/{id}', [App\Http\Controllers\Frontend\KonasController::class, 'cetakKartu'])->name('cetak_kartu');
    Route::get('/konas/cetak_invoice/{id}', [App\Http\Controllers\Frontend\KonasController::class, 'cetakInvoice'])->name('cetak_invoice');
    Route::post('/konas/akun/tipe', [App\Http\Controllers\Frontend\KonasController::class, 'getTipe'])->name('konas.getTipe');
    Route::post('/konas/akun/tipe_kamar', [App\Http\Controllers\Frontend\KonasController::class, 'getTipeHotel'])->name('konas.getTipeHotel');
    Route::post('/konas/akun/store', [App\Http\Controllers\Frontend\KonasController::class, 'storeHotel'])->name('konas.booking_hotel');
    Route::post('/konas/akun/storepenerbangan', [App\Http\Controllers\Frontend\KonasController::class, 'storePenerbangan'])->name('penerbanganku');
    Route::get('/user-profile', [App\Http\Controllers\Frontend\HomeController::class, 'UserProfile'])->name('user.profile');
    Route::post('/daftar-anggota', [App\Http\Controllers\Frontend\HomeController::class, 'CreateAnggota'])->name('create.anggota');
    Route::get('/syarat-ketentuan', [App\Http\Controllers\Frontend\HomeController::class, 'SyaratKetentuan'])->name('syarat.ketentuan');
    Route::get('/pendaftaran-anggota', [App\Http\Controllers\Frontend\HomeController::class, 'PendaftaranAnggota'])->name('pendaftaran.anggota');
    Route::put('/update-anggota/{id}', [App\Http\Controllers\Frontend\HomeController::class, 'UpdateAnggota'])->name('update.anggota');
    Route::get('/pendaftaran-sdm', [App\Http\Controllers\Frontend\HomeController::class, 'PendaftaranSDM'])->name('pendaftaran.sdm');
    Route::get('/pendaftaran-sdm/sdm_pjk', [App\Http\Controllers\Frontend\HomeController::class, 'sdm_pjk'])->name('pendaftaran.sdm.pjk');
    Route::post('/pendaftaran-sdm/sdm_pjk_add', [App\Http\Controllers\Frontend\HomeController::class, 'store_sdm_pj'])->name('pendaftaran.sdm.pjk.add');
    Route::post('/pendaftaran-sdm/sdm_pjk_delete', [App\Http\Controllers\Frontend\HomeController::class, 'delete_sdm_pj'])->name('pendaftaran.sdm.pjk.delete');
    Route::get('/pendaftaran-sdm/sdm_dp', [App\Http\Controllers\Frontend\HomeController::class, 'sdm_dp'])->name('pendaftaran.sdm.dp');
    Route::post('/pendaftaran-sdm/sdm_dp_add', [App\Http\Controllers\Frontend\HomeController::class, 'store_sdm_dp'])->name('pendaftaran.sdm.dp.add');
    Route::get('/pendaftaran-sdm/sdm_tk', [App\Http\Controllers\Frontend\HomeController::class, 'sdm_tk'])->name('pendaftaran.sdm.tk');
    Route::post('/pendaftaran-sdm/sdm_tk_add', [App\Http\Controllers\Frontend\HomeController::class, 'store_sdm_tk'])->name('pendaftaran.sdm.tk.add');
    Route::get('/pendaftaran-sdm/sdm_tkl', [App\Http\Controllers\Frontend\HomeController::class, 'sdm_tkl'])->name('pendaftaran.sdm.tkl');
    Route::post('/pendaftaran-sdm/sdm_tkl_add', [App\Http\Controllers\Frontend\HomeController::class, 'store_sdm_tkl'])->name('pendaftaran.sdm.tkl.add');
    Route::get('/pendaftaran-sdm/sdm_lain', [App\Http\Controllers\Frontend\HomeController::class, 'sdm_lain'])->name('pendaftaran.sdm.lain');
    Route::post('/pendaftaran-sdm/sdm_lain_add', [App\Http\Controllers\Frontend\HomeController::class, 'store_sdm_lain'])->name('pendaftaran.sdm.lain.add');
    Route::post('/pendaftaran-sdm/sdm_lain_delete', [App\Http\Controllers\Frontend\HomeController::class, 'delete_sdm_lain'])->name('pendaftaran.sdm.lain.delete');
    Route::get('/pendaftaran-sdm/sdm_rumah_sakit', [App\Http\Controllers\Frontend\HomeController::class, 'sdm_rumah_sakit'])->name('pendaftaran.sdm.rumah_sakit');
    Route::post('/pendaftaran-sdm/sdm_rumah_sakit_add', [App\Http\Controllers\Frontend\HomeController::class, 'store_sdm_rumah_sakit'])->name('pendaftaran.sdm.rumah_sakit.add');
    Route::post('/pendaftaran-sdm/sdm_rumah_sakit_delete', [App\Http\Controllers\Frontend\HomeController::class, 'delete_sdm_rumah_sakit'])->name('pendaftaran.sdm.rumah_sakit.delete');
    Route::get('/pendaftaran-sdm/sdm_asuransi', [App\Http\Controllers\Frontend\HomeController::class, 'sdm_asuransi'])->name('pendaftaran.sdm.asuransi');
    Route::post('/pendaftaran-sdm/sdm_asuransi_add', [App\Http\Controllers\Frontend\HomeController::class, 'store_sdm_asuransi'])->name('pendaftaran.sdm.asuransi.add');
    Route::post('/pendaftaran-sdm/sdm_asuransi_delete', [App\Http\Controllers\Frontend\HomeController::class, 'delete_sdm_asuransi'])->name('pendaftaran.sdm.asuransi.delete');
    Route::get('/pendaftaran-sdm/sdm_foto', [App\Http\Controllers\Frontend\HomeController::class, 'sdm_foto'])->name('pendaftaran.sdm.foto');
    Route::post('/pendaftaran-sdm/sdm_foto_add', [App\Http\Controllers\Frontend\HomeController::class, 'store_sdm_foto'])->name('pendaftaran.sdm.foto.add');
    Route::post('/pendaftaran-sdm/sdm_foto_delete', [App\Http\Controllers\Frontend\HomeController::class, 'delete_sdm_foto'])->name('pendaftaran.sdm.foto.delete');
    Route::post('/draft', [App\Http\Controllers\Frontend\HomeController::class, 'draft'])->name('draft');
    Route::post('/submit-akhir', [App\Http\Controllers\Frontend\HomeController::class, 'submitAkhir'])->name('submit_akhir');
    Route::post('/upload-foto', [App\Http\Controllers\Frontend\HomeController::class, 'uploadFotoKlinik'])->name('upload_foto');
    Route::get('/tagihan', [App\Http\Controllers\Frontend\HomeController::class, 'tagihan'])->name('tagihan');
    Route::get('/getTagihan', [App\Http\Controllers\Frontend\HomeController::class, 'getTagihan'])->name('getTagihan');
    Route::post('/storeTagihan', [App\Http\Controllers\Frontend\HomeController::class, 'storeTagihan'])->name('tagihan.store');
    Route::get('/sertifikat', [App\Http\Controllers\Frontend\HomeController::class, 'sertifikat'])->name('sertifikat');
    Route::get('/getSertifikat', [App\Http\Controllers\Frontend\HomeController::class, 'getSertifikat'])->name('getSertifikat');
    Route::get('/download_sk/{id}', [App\Http\Controllers\Frontend\HomeController::class, 'downloadSK'])->name('downloadSK');
    Route::get('/cetak_sertifikat/{id}', [App\Http\Controllers\Frontend\HomeController::class, 'cetakSertifikat'])->name('cetak_sertifikat');
});

// Backend
Route::group(['middleware' => ['auth'], 'prefix' => 'backend'], function () {
    // Backend
    Route::get('/dashboard', [App\Http\Controllers\Backend\DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/profile', [App\Http\Controllers\Backend\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update/{id}', [App\Http\Controllers\Backend\ProfileController::class, 'update'])->name('profile.update');
    Route::group(['middleware' => ['role:Superadmin'], 'prefix' => 'secret'], function () {
        Route::prefix('users')->controller(App\Http\Controllers\Backend\secret\UserController::class)->group(function () {
            Route::get('/', 'index')->name('users.index');
            Route::get('/list', 'list')->name('users.list');
            Route::get('/create', 'create')->name('users.create');
            Route::get('/edit/{id}', 'edit')->name('users.edit');
            Route::post('/store', 'store')->name('users.store');
            Route::put('/update/{id}', 'update')->name('users.update');
            Route::post('/delete', 'destroy')->name('users.delete');
        });
        Route::prefix('roles')->controller(App\Http\Controllers\Backend\secret\RoleController::class)->group(function () {
            Route::get('/', 'index')->name('roles.index');
            Route::get('/list', 'list')->name('roles.list');
            Route::post('/edit', 'edit')->name('roles.edit');
            Route::post('/store', 'store')->name('roles.store');
            Route::post('/delete', 'destroy')->name('roles.delete');
        });
        Route::prefix('permissions')->controller(App\Http\Controllers\Backend\secret\PermissionController::class)->group(function () {
            Route::get('/', 'index')->name('permissions.index');
            Route::get('/list', 'list')->name('permissions.list');
            Route::post('/edit', 'edit')->name('permissions.edit');
            Route::post('/store', 'store')->name('permissions.store');
            Route::post('/delete', 'destroy')->name('permissions.delete');
        });
        Route::prefix('berita_kategori')->controller(App\Http\Controllers\Backend\BeritaKategoriController::class)->group(function () {
            Route::get('/', 'index')->name('berita_kategori.index');
            Route::get('/list', 'list')->name('berita_kategori.list');
            Route::post('/edit', 'edit')->name('berita_kategori.edit');
            Route::post('/store', 'store')->name('berita_kategori.store');
            Route::post('/delete', 'destroy')->name('berita_kategori.delete');
        });
        Route::prefix('event_kategori')->controller(App\Http\Controllers\Backend\EventKategoriController::class)->group(function () {
            Route::get('/', 'index')->name('event_kategori.index');
            Route::get('/list', 'list')->name('event_kategori.list');
            Route::post('/edit', 'edit')->name('event_kategori.edit');
            Route::post('/store', 'store')->name('event_kategori.store');
            Route::post('/delete', 'destroy')->name('event_kategori.delete');
        });
        Route::prefix('fasilitas')->controller(App\Http\Controllers\Backend\FasilitasController::class)->group(function () {
            Route::get('/', 'index')->name('fasilitas.index');
            Route::get('/list', 'list')->name('fasilitas.list');
            Route::post('/edit', 'edit')->name('fasilitas.edit');
            Route::post('/store', 'store')->name('fasilitas.store');
            Route::post('/delete', 'destroy')->name('fasilitas.delete');
        });
        Route::prefix('fasilitas_klinik')->controller(App\Http\Controllers\Backend\FasilitasKlinikController::class)->group(function () {
            Route::get('/', 'index')->name('fasilitas_klinik.index');
            Route::get('/list', 'list')->name('fasilitas_klinik.list');
            Route::post('/edit', 'edit')->name('fasilitas_klinik.edit');
            Route::post('/store', 'store')->name('fasilitas_klinik.store');
            Route::post('/delete', 'destroy')->name('fasilitas_klinik.delete');
        });
        Route::prefix('kategori_pembayaran')->controller(App\Http\Controllers\Backend\PembayaranKategoriController::class)->group(function () {
            Route::get('/', 'index')->name('kategori_pembayaran.index');
            Route::get('/list', 'list')->name('kategori_pembayaran.list');
            Route::post('/edit', 'edit')->name('kategori_pembayaran.edit');
            Route::post('/store', 'store')->name('kategori_pembayaran.store');
            Route::post('/delete', 'destroy')->name('kategori_pembayaran.delete');
        });
    });

    Route::prefix('berita')->controller(App\Http\Controllers\Backend\BeritaController::class)->group(function () {
        Route::get('/', 'index')->name('berita.index');
        Route::get('/list', 'list')->name('berita.list');
        Route::get('/create', 'create')->name('berita.create');
        Route::get('/edit/{id}', 'edit')->name('berita.edit');
        Route::post('/store', 'store')->name('berita.store');
        Route::put('/update/{id}', 'update')->name('berita.update');
        Route::post('/delete', 'destroy')->name('berita.delete');
    });

    Route::prefix('events')->controller(App\Http\Controllers\Backend\EventController::class)->group(function () {
        Route::get('/', 'index')->name('events.index');
        Route::get('/list', 'list')->name('events.list');
        Route::get('/create', 'create')->name('events.create');
        Route::get('/edit/{id}', 'edit')->name('events.edit');
        Route::post('/store', 'store')->name('events.store');
        Route::put('/update/{id}', 'update')->name('events.update');
        Route::post('/delete', 'destroy')->name('events.delete');
    });

    Route::prefix('agenda')->controller(App\Http\Controllers\Backend\AgendaController::class)->group(function () {
        Route::get('/', 'index')->name('agenda.index');
        Route::get('/list', 'list')->name('agenda.list');
        Route::get('/create', 'create')->name('agenda.create');
        Route::get('/edit/{id}', 'edit')->name('agenda.edit');
        Route::post('/store', 'store')->name('agenda.store');
        Route::put('/update/{id}', 'update')->name('agenda.update');
        Route::post('/delete', 'destroy')->name('agenda.delete');
    });

    Route::prefix('layanan')->controller(App\Http\Controllers\Backend\LayananController::class)->group(function () {
        Route::get('/', 'index')->name('layanan.index');
        Route::get('/list', 'list')->name('layanan.list');
        Route::post('/edit', 'edit')->name('layanan.edit');
        Route::post('/store', 'store')->name('layanan.store');
        Route::post('/delete', 'destroy')->name('layanan.delete');
    });

    Route::prefix('verifikasi_anggota')->controller(App\Http\Controllers\Backend\VerifikasiAnggotaController::class)->group(function () {
        Route::get('/', 'index')->name('verifikasi_anggota.index');
        Route::get('/list', 'list')->name('verifikasi_anggota.list');
        Route::post('/edit', 'edit')->name('verifikasi_anggota.edit');
        Route::post('/store', 'store')->name('verifikasi_anggota.store');
        Route::post('/delete', 'destroy')->name('verifikasi_anggota.delete');
        Route::get('/verify/{id}', 'verify')->name('verifikasi_anggota.verify');
        Route::put('/verify/update/{id}', 'verifyUpdate')->name('verifikasi_anggota.update_anggota');
        Route::get('/verify/sdm/sdm_pjk/{id}', 'sdm_pjk')->name('verifikasi_anggota.sdm.pjk');
        Route::get('/verify/sdm/sdm_dp/{id}', 'sdm_dp')->name('verifikasi_anggota.sdm.dp');
        Route::get('/verify/sdm/sdm_tk/{id}', 'sdm_tk')->name('verifikasi_anggota.sdm.tk');
        Route::get('/verify/sdm/sdm_tkl/{id}', 'sdm_tkl')->name('verifikasi_anggota.sdm.tkl');
        Route::get('/verify/sdm/sdm_lain/{id}', 'sdm_lain')->name('verifikasi_anggota.sdm.lain');
        Route::get('/verify/sdm/sdm_rumah_sakit/{id}', 'sdm_rumah_sakit')->name('verifikasi_anggota.sdm.rumah_sakit');
        Route::get('/verify/sdm/sdm_asuransi/{id}', 'sdm_asuransi')->name('verifikasi_anggota.sdm.asuransi');
        Route::get('/verify/sdm/sdm_foto/{id}', 'sdm_foto')->name('verifikasi_anggota.sdm.foto');
        Route::post('/verify/verifikasi_bendahara', 'verifyBendahara')->name('verifikasi_anggota.bendahara');
        Route::post('/verify/edit_bendahara', 'editBendahara')->name('verifikasi_anggota.edit_bendahara');
    });

    Route::prefix('verifikasi')->controller(App\Http\Controllers\Backend\VerifikasiController::class)->group(function () {
        Route::get('/', 'index')->name('verifikasi.index');
        Route::get('/list', 'list')->name('verifikasi.list');
        Route::get('/print/{id}', 'print')->name('verifikasi.print');
        Route::get('/printsk/{id}', 'printsk')->name('verifikasi.printsk');
    });

    Route::prefix('kerjasama_asuransi')->controller(App\Http\Controllers\Backend\KerjasamaAsuransiController::class)->group(function () {
        Route::get('/', 'index')->name('kerjasama_asuransi.index');
        Route::get('/list', 'list')->name('kerjasama_asuransi.list');
    });

    Route::prefix('sdm_klinik')->controller(App\Http\Controllers\Backend\SDMKlinikController::class)->group(function () {
        Route::get('/', 'index')->name('sdm_klinik.index');
        Route::get('/list', 'list')->name('sdm_klinik.list');
    });

    Route::prefix('pembayaran')->controller(App\Http\Controllers\Backend\PembayaranController::class)->group(function () {
        Route::get('/', 'index')->name('pembayaran.index');
        Route::get('/pangkal', 'pangkal')->name('pembayaran.pangkal');
        Route::get('/list', 'list')->name('pembayaran.list');
        Route::get('/list_pangkal', 'list_pangkal')->name('pembayaran.list_pangkal');
        Route::post('/edit', 'edit')->name('pembayaran.edit');
        Route::post('/updateStatus', 'updateStatus')->name('pembayaran.updateStatus');
        Route::post('/store', 'store')->name('pembayaran.store');
        Route::post('/delete', 'destroy')->name('pembayaran.delete');
    });

    Route::prefix('galery')->controller(App\Http\Controllers\Backend\GaleryController::class)->group(function () {
        Route::get('/', 'index')->name('galery.index');
        Route::get('/list', 'list')->name('galery.list');
        Route::post('/edit', 'edit')->name('galery.edit');
        Route::post('/store', 'store')->name('galery.store');
        Route::post('/delete', 'destroy')->name('galery.delete');
    });

    Route::prefix('sertifikat')->controller(App\Http\Controllers\Backend\SertifikatController::class)->group(function () {
        Route::get('/', 'index')->name('sertifikat.index');
        Route::get('/list', 'list')->name('sertifikat.list');
        Route::post('/edit', 'edit')->name('sertifikat.edit');
        Route::post('/store', 'store')->name('sertifikat.store');
        Route::post('/delete', 'destroy')->name('sertifikat.delete');
    });

    Route::prefix('pembayaran_pusat')->controller(App\Http\Controllers\Backend\PembayaranPusatController::class)->group(function () {
        Route::get('/', 'index')->name('pembayaran_pusat.index');
        Route::get('/list', 'list')->name('pembayaran_pusat.list');
        Route::post('/edit', 'edit')->name('pembayaran_pusat.edit');
        Route::post('/store', 'store')->name('pembayaran_pusat.store');
        Route::post('/delete', 'destroy')->name('pembayaran_pusat.delete');
    });

    Route::prefix('laporan')->controller(App\Http\Controllers\Backend\LaporanPusatController::class)->group(function () {
        Route::get('/pusat', 'index')->name('laporan_pusat');
        Route::get('/pusat/list', 'list')->name('laporan_pusat.list');
    });

    Route::prefix('laporan')->controller(App\Http\Controllers\Backend\LaporanDaerahController::class)->group(function () {
        Route::get('/daerah', 'index')->name('laporan_daerah');
        Route::get('/daerah/list', 'list')->name('laporan_daerah.list');
    });

    Route::prefix('laporan')->controller(App\Http\Controllers\Backend\LaporanCabangController::class)->group(function () {
        Route::get('/cabang', 'index')->name('laporan_cabang');
        Route::get('/cabang/list', 'list')->name('laporan_cabang.list');
    });

    Route::prefix('konas-master-hotel')->controller(App\Http\Controllers\Backend\KonasMasterHotelController::class)->group(function () {
        Route::get('/', 'index')->name('konas_master_hotel.index');
        Route::get('/list', 'list')->name('konas_master_hotel.list');
        Route::post('/edit', 'edit')->name('konas_master_hotel.edit');
        Route::post('/store', 'store')->name('konas_master_hotel.store');
        Route::post('/delete', 'destroy')->name('konas_master_hotel.delete');
    });

    Route::prefix('konas-master-tipe-hotel')->controller(App\Http\Controllers\Backend\KonasMasterTipeHotelController::class)->group(function () {
        Route::get('/', 'index')->name('konas_master_tipe_hotel.index');
        Route::get('/list', 'list')->name('konas_master_tipe_hotel.list');
        Route::post('/edit', 'edit')->name('konas_master_tipe_hotel.edit');
        Route::post('/store', 'store')->name('konas_master_tipe_hotel.store');
        Route::post('/delete', 'destroy')->name('konas_master_tipe_hotel.delete');
    });

    Route::prefix('peserta-konas')->controller(App\Http\Controllers\Backend\KonasPesertaController::class)->group(function () {
        Route::get('/', 'index')->name('konas.index');
        Route::get('/list', 'list')->name('konas.list');
        Route::post('/edit', 'edit')->name('konas.edit');
        Route::post('/updateStatus', 'updateStatus')->name('konas.updateStatus');
        Route::get('/detail', 'detail')->name('konas.detail');
        Route::post('/store', 'store')->name('konas.store');
        Route::post('/delete', 'destroy')->name('konas.delete');
        Route::get('/approve/{id}', 'approve')->name('konas.approve');
        Route::post('/status_pembayaran', 'status_pembayaran')->name('konas.status_pembayaran');
        Route::post('/delete', 'destroy')->name('konas.delete');
        Route::get('/invoice/{id}', 'invoice')->name('konas.invoice');
    });

    Route::prefix('booking-hotel')->controller(App\Http\Controllers\Backend\KonasBookingController::class)->group(function () {
        Route::get('/', 'index')->name('konas_booking.index');
        Route::get('/list', 'list')->name('konas_booking.list');
        Route::post('/edit', 'edit')->name('konas_booking.edit');
        Route::post('/store', 'store')->name('konas_booking.store');
        Route::post('/delete', 'destroy')->name('konas_booking.delete');
    });

    Route::prefix('data-penerbangan')->controller(App\Http\Controllers\Backend\KonasPenerbanganController::class)->group(function () {
        Route::get('/', 'index')->name('konas_penerbangan.index');
        Route::get('/list', 'list')->name('konas_penerbangan.list');
        Route::post('/edit', 'edit')->name('konas_penerbangan.edit');
        Route::post('/store', 'store')->name('konas_penerbangan.store');
        Route::post('/delete', 'destroy')->name('konas_penerbangan.delete');
    });

    Route::prefix('partner')->controller(App\Http\Controllers\Backend\KonasPartnerController::class)->group(function () {
        Route::get('/', 'index')->name('partner.index');
        Route::get('/list', 'list')->name('partner.list');
        Route::post('/edit', 'edit')->name('partner.edit');
        Route::post('/store', 'store')->name('partner.store');
        Route::post('/delete', 'destroy')->name('partner.delete');
    });
});
