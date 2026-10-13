# ANALISIS AWAL KONDISI APLIKASI ASKLIN & KESIAPAN MOBILE APP (FLUTTER + LARAVEL REST API)

**Tanggal Audit:** 13 Oktober 2026  
**Aplikasi:** Asosiasi Klinik Indonesia (ASKLIN)  
**Tujuan Dokumen:** Laporan audit menyeluruh terhadap arsitektur backend Laravel eksisting, model data, otentikasi, perizinan wilayah, keamanan, serta pemetaan kelayakan untuk pengembangan REST API dan Mobile App Android Flutter.

---

## 1. Ringkasan Arsitektur Aplikasi Saat Ini

Aplikasi ASKLIN saat ini merupakan sistem informasi organisasi berbasis web yang berjalan di atas lingkungan:
- **Framework Backend:** Laravel 9.48.0 (`"laravel/framework": "^9.19"` pada `composer.json`).
- **Bahasa Pemrograman:** PHP 8.x (CLI aktif terdeteksi PHP 8.3, kompatibel dengan PHP ^8.0.2).
- **Database Engine:** MySQL / MariaDB (skema aktif di production, status: **TERKUNCI / FROZEN**, tidak boleh sembarang di-alter atau di-drop).
- **Otentikasi Web Eksisting:** Laravel UI (`laravel/ui` 4.2) berbasis sesi web (`AuthenticatesUsers` trait).
- **Otorisasi & Hak Akses:** Spatie Laravel Permission v5.8 (`spatie/laravel-permission`).
- **Referensi Wilayah:** `laravolt/indonesia` v0.33.0 (tabel: `indonesia_provinces`, `indonesia_cities`, `indonesia_districts`, `indonesia_villages`).
- **Frontend Eksisting:** 100% Laravel Blade dengan styling Bootstrap 4 / template Stisla, Tailwind CSS parsial, jQuery, dan Yajra DataTables BS4.
- **Engine Dokumen & PDF:** `spipu/html2pdf` v5.2 dan `barryvdh/laravel-dompdf` v3.1.
- **Library API Token:** `laravel/sanctum` v3.0 sudah terpasang di `composer.json` dan trait `HasApiTokens` sudah aktif pada model `App\Models\User`.

### Pola Arsitektur Saat Ini
1. Arsitektur backend bersifat monolitik klasik (Model-View-Controller) yang berorientasi pada penyajian view Blade dan respons AJAX server-side DataTables.
2. Belum ada API layer yang matang untuk aplikasi mobile. Berkas `routes/api.php` hanya memiliki stub default `Route::middleware('auth:sanctum')->get('/user')` dan endpoint legacy sinkronisasi `AnggotaApiController` yang memanfaatkan static API key parameter.
3. Bisnis proses utama (pendaftaran anggota, verifikasi bertingkat, penerbitan SK pengurus, sertifikat anggota, dan rekapitulasi data) tersebar di antara `Frontend\HomeController`, `Backend\AnggotaController`, `Backend\VerifikasiAnggotaController`, `Backend\laporan\*`, dan `Backend\ruang_pengurus\*`.

---

## 2. Fitur yang Sudah Tersedia dan Dapat Digunakan Kembali

Berdasarkan audit source code nyata, beberapa fitur dan modul logika backend sudah sangat solid dan dapat langsung diadaptasi menjadi service layer REST API tanpa mengubah proses bisnis:

1. **Model Pengguna & Tokenisasi Sanctum:**
   - Model `App\Models\User` telah mengimplementasikan `HasApiTokens` dan `HasRoles`.
   - Tabel `personal_access_tokens` sudah dibuat oleh migrasi Laravel default (`2019_12_14_000001_create_personal_access_tokens_table.php`).
   - Tidak diperlukan modifikasi skema tabel user untuk mendukung otentikasi token bearer Sanctum.

2. **Mesin Pelaporan Terpadu (`App\Services\LaporanService`):**
   - Layanan ini sudah memiliki metode mutakhir untuk menghitung statistik, grafik pertumbuhan, komposisi status klinik, distribusi wilayah, hingga rekapitulasi:
     - `getDashboardStats(scope, request)`
     - `getGrowthChartData(scope, request)`
     - `getStatusChartData(scope, request)`
     - `getRegionalDistributionChartData(scope, request)`
     - `getBreakdownChartData(scope, request)`
     - `getRekapitulasiData(scope, request)`
   - Sangat modular dan mengembalikan struktur array PHP murni yang dapat langsung di-wrap menjadi respons JSON untuk dashboard Pengurus Pusat, Daerah, maupun Cabang.

3. **Pola Pembatasan Wilayah Organisasi (`RegionalScoping`):**
   - Trait `App\Traits\RegionalScoping` telah menyediakan abstraksi scoping:
     - Admin Cabang: dikunci ke `id_kota == auth()->user()->kota`.
     - Admin Daerah: dikunci ke `id_provinsi == auth()->user()->provinsi`.
     - Pengurus Pusat / Superadmin: akses lintas wilayah nasional (bebas memilih provinsi/kota).
   - Logika pemetaan wilayah serupa juga diimplementasikan secara matang di `LaporanService::resolveScope()`.

4. **Basis Data Wilayah Republik Indonesia:**
   - Tersedia tabel master kode provinsi (2 digit), kabupaten/kota (4 digit), kecamatan (6 digit), dan kelurahan (10 digit) melalui pustaka `laravolt/indonesia`.

5. **Template & Generator Dokumen PDF (SK & Sertifikat):**
   - View Blade PDF sudah siap digunakan untuk mencetak dokumen:
     - SK Anggota: view `backend.anggota.printsk` (menggunakan Html2Pdf).
     - Sertifikat Anggota: view `frontend.print_sertifikat` dan `backend.anggota.print`.
     - SK Pengurus: view `backend.ruang_pengurus.sk.print` (menggunakan DomPDF).
   - Logika watermark, QR Code, dan nomor izin telah berjalan di sistem website.

---

## 3. Fitur yang Belum Tersedia atau Perlu Penyesuaian

Audit menemukan beberapa kesenjangan fungsional yang memerlukan penyesuaian di sisi backend:

1. **Ketiadaan Auth Controller Khusus REST API (Perlu Penyesuaian / Baru):**
   - Saat ini otentikasi login/register berbasis session cookies dan redirect Blade form (`LoginController`, `RegisterController`).
   - Belum ada endpoint `POST /api/v1/auth/login` yang memvalidasi kredensial, memeriksa status aktif, dan menerbitkan `PlaintextToken` via Sanctum.
   - Belum ada endpoint `POST /api/v1/auth/logout` yang mencabut token (`currentAccessToken()->delete()`).

2. **Controller Wilayah Mengembalikan String HTML (Perlu Penyesuaian):**
   - `App\Http\Controllers\WilayahController` menghasilkan raw HTML `<option value="...">...</option>` untuk kebutuhan dropdown AJAX web.
   - Aplikasi mobile Flutter membutuhkan respons format JSON murni `[{"code": "32", "name": "JAWA BARAT"}, ...]`. Dibutuhkan endpoint JSON baru di `routes/api.php`.

3. **Pengambilan Dokumen PDF Secara Direct Stream (Perlu Penyesuaian):**
   - Fungsi `downloadSK` dan `cetakSertifikat` pada `HomeController` memanggil `ob_end_clean()` dan langsung melakukan output binary PDF via library Html2Pdf dengan `exit()`.
   - Di mobile app, disarankan endpoint mengembalikan metadata dokumen terlebih dahulu (nomor SK, status masa berlaku, status bayar, link download ber-token sementara / signed URL), atau streaming response dengan header `Content-Type: application/pdf` yang clean tanpa mematikan lifecycle framework (`exit()`).

4. **Response DataTables Server-Side vs JSON REST Pagination (Perlu Penyesuaian):**
   - Endpoint query daftar anggota saat ini menggunakan Yajra DataTables (`Datatables::of($data)->make(true)`). Format responsnya dirancang khusus untuk plugin jQuery DataTables di browser (`draw`, `recordsTotal`, `recordsFiltered`).
   - Aplikasi Flutter membutuhkan standar pagination REST API standar (`current_page`, `data`, `per_page`, `total`, `last_page`).

5. **Hardcoded API Key di `AnggotaApiController` (Perlu Ditinggalkan / Diganti):**
   - `AnggotaApiController` eksisting menggunakan string API key statis yang rentan. Endpoint ini tidak boleh dijadikan basis arsitektur mobile; aplikasi Flutter harus menggunakan bearer token Sanctum.

---

## 4. Struktur Role dan Relasi Wilayah Berdasarkan Implementasi Aktual

Berdasarkan `database/seeders/CreateAdminUserSeeder.php`, `database/seeders/PermissionTableSeeder.php`, dan pembacaan controller, sistem hak akses ASKLIN memiliki struktur riil sebagai berikut:

### Daftar Role Utama
1. **Superadmin:** Hak akses penuh seluruh sistem (bypass seluruh batasan).
2. **Admin Pusat / Pengurus Pusat:** Hak akses data nasional, verifikasi akhir anggota, approval SK, monitoring seluruh provinsi dan cabang.
3. **Admin Daerah (Pengurus Daerah - Tingkat Provinsi):** Terikat pada atribut `provinsi` (kode 2 digit, misal `'32'` untuk Jawa Barat). Dapat melihat data seluruh cabang dan anggota di provinsinya.
4. **Admin Cabang (Pengurus Cabang - Tingkat Kabupaten/Kota):** Terikat pada atribut `kota` (kode 4 digit, misal `'3216'` untuk Kab. Bekasi) dan `provinsi`. Hanya berwenang melihat data anggota dan pengurus di kabupaten/kota tersebut.
5. **Klinik (Anggota):** Role untuk pengguna fasilitas pelayanan kesehatan/klinik. Terikat melalui relasi `anggota.id_user == users.id`.
6. **Sekjen:** Pejabat Pengurus Pusat yang memverifikasi berkas administratif sebelum pengesahan Ketua Umum.
7. **Ketua Umum:** Pimpinan tertinggi yang mengesahkan SK dan menandatangani sertifikat.
8. **Bendahara:** Pejabat yang memvalidasi iuran keanggotaan dan pembayaran.

### Atribut Wilayah pada Model `User`
Tabel `users` memiliki 4 kolom wilayah hasil migrasi `2023_01_29_121820_add_info_to_users.php`:
- `provinsi` (kode provinsi, varchar)
- `kota` (kode kota/kabupaten, varchar)
- `kecamatan` (kode kecamatan, varchar)
- `kelurahan` (kode kelurahan, varchar)

### Alur Pendaftaran & Status Anggota (Klinik)
Kolom `status` pada tabel `anggota` memiliki state machine aktual sebagai berikut:
1. `waiting` / `proses` → Baru mendaftar atau sedang melengkapi data umum dan SDM klinik.
2. `Diverifikasi Cabang` / `Terverifikasi Cabang` → Telah diperiksa oleh Admin Cabang setempat.
3. `Diverifikasi Daerah` / `Terverifikasi Daerah` → Diverifikasi oleh Admin Daerah.
4. `Verifikasi Sekjen` / `Verifikasi Bendahara` → Pemeriksaan dokumen pusat dan status pembayaran.
5. `approved` → Disetujui penuh oleh Pengurus Pusat; diterbitkan `no_anggota` resmi, SK Anggota, dan Sertifikat.
6. `Perlu Perbaikan` / `ditolak pusat` → Berkas dikembalikan dengan catatan perbaikan di kolom `keterangan`.

---

## 5. Risiko Keamanan dan Integrasi yang Ditemukan

Audit mendalam terhadap berkas kode mengidentifikasi risiko keamanan yang wajib diantisipasi dalam rancangan REST API mobile:

1. **Insecure Direct Object Reference (IDOR) pada Download Dokumen:**
   - Pada `Backend\AnggotaController::printsk($id)` dan `AnggotaController::print($id)`, tidak terdapat pengecekan kepemilikan dokumen klinik terhadap user login.
   - Walaupun di `Frontend\HomeController::downloadSK($id)` sudah ditambahkan pengecekan:
     ```php
     $isAdminOrStaff = $user->hasAnyRole(['Superadmin', 'Admin Pusat', 'Admin Cabang', 'Admin Daerah', ...]);
     if (!$isAdminOrStaff && $anggota->id_user != $user->id) { abort(403); }
     ```
     namun Admin Cabang belum dibatasi hanya boleh mengunduh SK klinik di wilayah kotanya sendiri (Admin Cabang Kota A bisa mengunduh SK klinik Kota B jika mengetahui ID-nya).
   - **Rekomendasi untuk API:** Backend API wajib menerapkan validasi kepemilikan multi-tier:
     - Jika role Klinik: `anggota.id_user == auth()->id()`.
     - Jika Admin Cabang: `anggota.id_kota == auth()->user()->kota`.
     - Jika Admin Daerah: `anggota.id_provinsi == auth()->user()->provinsi`.

2. **Penyimpanan Berkas Publik (Unprotected Direct File Access):**
   - Bukti pembayaran, scan izin operasional (SIO), dan berkas STR dokter saat ini disimpan di direktori publik `public/images/file/`.
   - Siapa pun yang mengetahui URL langsung dapat mengunduh berkas tersebut tanpa melewati middleware otentikasi.
   - **Rekomendasi:** Untuk endpoint mobile, URL dokumen sensitif harus dialirkan melalui route streaming API berautentikasi atau signed URL berbatas waktu.

3. **Inkonsistensi Format Tanggal dan Kolom Timestamp:**
   - Sebagian data lama (migrasi) menggunakan kolom `created_on` bertipe integer/UNIX timestamp string, sedangkan data baru menggunakan kolom standar Laravel `created_at` bertipe MySQL timestamp.
   - Format waktu verifikasi `verifikasi_pusat` dan `verifikasi_cabang` dapat bernilai `'0000-00-00 00:00:00'` atau `NULL`.
   - **Rekomendasi:** API Resource Laravel wajib memanfaatkan penanganan tanggal terpadu (seperti yang dilakukan `LaporanService::getUnifiedDateSql()`) agar parser tanggal pada aplikasi Flutter tidak mengalami crash (`FormatException`).

4. **Bug Pemanggilan `NotificationService::send`:**
   - Pada `VerifikasiAnggotaController.php:243`, metode `NotificationService::send(...)` dipanggil menggunakan parameter posisional padahal definisi aslinya di `NotificationService.php:13` mengharapkan `array $data`. Hal ini dapat memicu fatal TypeError saat eksekusi approval.

---

## 6. Kendala yang Berpotensi Memengaruhi Pengembangan Mobile

1. **Dilarang Melakukan Migrasi Skema Database:**
   - Database MySQL di production berada dalam status terkunci. Blueprint API dan Flutter harus mengadaptasi skema yang ada tanpa menambah kolom wajib baru atau mengubah tipe data tabel production.
2. **Ketergantungan Versi Framework:**
   - Laravel 9.48.0 dan PHP 8.x memiliki dukungan penuh untuk Laravel Sanctum 3.x, sehingga fungsionalitas token REST API dapat berjalan tanpa memaksa upgrade framework terlebih dahulu.
3. **Kinerja Endpoint Download PDF:**
   - Library `spipu/html2pdf` dan `barryvdh/dompdf` memerlukan memori dan waktu komputasi CPU yang relatif tinggi saat me-render HTML ke PDF. Di aplikasi mobile, pengunduhan dokumen PDF berukuran besar harus ditangani dengan background download task, progress indicator, dan caching lokal pada storage perangkat Android.

---

## 7. Pertanyaan Terbuka yang Belum Dapat Dipastikan

1. **Kebijakan Pendaftaran Mandiri Anggota Baru via Mobile App:**
   - Apakah organisasi ASKLIN ingin mengizinkan pendaftaran klinik baru secara mandiri langsung dari aplikasi mobile Android, ataukah aplikasi mobile ditujukan hanya bagi klinik yang sudah terdaftar untuk memantau status, mengunduh SK/sertifikat, dan bagi pengurus untuk monitoring?
   - *Catatan Analisis:* Proses pendaftaran klinik di web memiliki form yang sangat detail (Data Umum, Lokasi RT/RW, SDM Dokter PJ, Dokter Pendamping, Tenaga Kesehatan, Fasilitas, Kerjasama Asuransi, Foto Klinik, Unggah SIO). Jika diimplementasikan di mobile, akan membutuhkan multi-step wizard form yang cukup masif.
2. **Mekanisme Reset Password via Mobile:**
   - Apakah pengiriman OTP/link reset password akan menggunakan email SMTP (yang sudah terkonfigurasi di `smtp_settings`) atau via WhatsApp Gateway pihak ketiga di masa mendatang?
3. **Kebutuhan Push Notification (FCM):**
   - Apakah mobile app memerlukan Firebase Cloud Messaging (FCM) untuk notifikasi instan saat SK terbit atau verifikasi disetujui, atau cukup menggunakan polling notifikasi via endpoint API `SystemNotification`?

---

## 8. Keputusan Teknis yang Sudah Didukung Bukti

1. **Autentikasi API:** Menggunakan **Laravel Sanctum Bearer Token**. Bukti: package `laravel/sanctum:^3.0` sudah terinstall, tabel `personal_access_tokens` sudah tersedia, dan model `User` sudah menggunakan `HasApiTokens`.
2. **Otorisasi Role & Wilayah:** Menggunakan kombinasi **Spatie Laravel Permission** dan scope wilayah dari kolom `users.provinsi` dan `users.kota`.
3. **Penyajian Data Monitoring:** Menggunakan kembali logika query pada `App\Services\LaporanService` yang telah teruji akurasinya.
4. **Isolasi Source Code Flutter:** Proyek Flutter ditempatkan di direktori terpisah (misalnya `../asklin-mobile` atau `c:\laragon\www\asklin-mobile`), independen dari repository Laravel.

---

## 9. Rekomendasi Urutan Pengembangan

1. **Tahap 1 — Analisis & Blueprint (Tahap Saat Ini):** Membekukan spesifikasi, kontrak endpoint API, arsitektur Flutter, dan pembagian sesi.
2. **Tahap 2 — Implementasi Laravel REST API:** Membangun auth, API resource, pembatasan wilayah, endpoint profil, SK/sertifikat, dan dashboard monitoring. Diuji tuntas menggunakan automated test / Postman.
3. **Tahap 3 — Inisialisasi Proyek Mobile Flutter:** Menyiapkan arsitektur Clean/Feature-First, Dio HTTP Client, Secure Storage, dan Theme.
4. **Tahap 4 — Integrasi Fitur Mobile Bertahap:** Mengimplementasikan modul Auth, Profil Anggota, Dokumen SK & Sertifikat, serta Dashboard Monitoring Pengurus.
5. **Tahap 5 — Pengujian & Hardening:** Uji isolasi data antarwilayah, performa unduh dokumen, offline handling, dan kesiapan rilis Google Play Store.

