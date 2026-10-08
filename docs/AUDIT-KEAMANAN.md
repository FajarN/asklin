# Audit Keamanan & Kondisi Aplikasi ASKLIN

**Tanggal Audit:** 8 Oktober 2026  
**Aplikasi:** Asosiasi Klinik Indonesia (ASKLIN)  
**Status Audit:** COMPLETED  
**Tujuan Dokumen:** Dokumentasi komprehensif keamanan, kualitas kode, performa, frontend, arsitektur, dan kesiapan upgrade sistem ASKLIN.

---

## 1. Executive Summary

Berdasarkan audit mendalam terhadap seluruh berkas source code, konfigurasi, pustaka ketergantungan (dependencies), rute, dan controller pada repositori ASKLIN (`c:\laragon\www\asklin`), aplikasi saat ini berada dalam kondisi **RISIKO KEAMANAN TINGGI (HIGH RISK)** di lingkungan production, disertai sejumlah technical debt arsitektural yang signifikan.

Beberapa temuan mendasar yang paling kritis:
1. **Perbedaan Versi Framework:** Aplikasi saat ini sebenarnya masih berjalan di atas **Laravel 9.48.0** (`"laravel/framework": "^9.19"` pada `composer.json`), **BUKAN** Laravel 10 seperti yang sebelumnya diperkirakan. Karena itu, roadmap upgrade ke Laravel 11 harus melalui tahapan transisi dua tingkat: `Laravel 9.48 -> Laravel 10.x -> Laravel 11.x`.
2. **Remote Code Execution (RCE) via Unrestricted File Upload:** Sejumlah endpoint registrasi SDM dan klinik menerima upload file tanpa validasi tipe MIME atau whitelist ekstensi, menyimpannya langsung ke direktori publik `public/images/file/`, di mana server web (Apache/cPanel) dikonfigurasi untuk mengeksekusi skrip PHP secara langsung.
3. **Penyimpanan Kredensial Sensitif di Git:** Berkas konfigurasi live production `.env-live` terlacak dan tersimpan di dalam repositori Git (`git ls-files .env-live`), mengekspos kredensial database live dan environment keys.
4. **Hardcoded API Key & Data Leakage:** Ditemukan static API key berukuran 500+ karakter yang di-hardcode langsung di dalam `AnggotaApiController.php:156`, memungkinkan siapa saja yang memiliki query parameter tersebut mengunduh seluruh data anggota dan klinik secara publik tanpa autentikasi.
5. **Insecure Direct Object Reference (IDOR) & Account Takeover:** 
   - Pada profil admin (`ProfileController::update`), ID user diambil dari parameter rute tanpa pengecekan kepemilikan, memungkinkan pengguna terautentikasi mengubah password dan email akun Superadmin.
   - Pada pendaftaran anggota (`HomeController::updateAnggota`), penyerang dapat mengubah data dan merebut kepemilikan klinik anggota lain hanya dengan memanipulasi parameter ID.
6. **Bypass Callback Pembayaran:** Endpoint `/konas/callback` dikecualikan dari proteksi CSRF dan tidak memiliki validasi webhook token/signature maupun otentikasi, sehingga status pembayaran peserta konas dan hotel dapat dimanipulasi oleh siapa saja.
7. **Broken Views & Missing Classes:** Ditemukan pemanggilan view yang tidak ada (`backend.verifikasi.action` pada `LaporanCabangController` & `LaporanDaerahController`) serta class `Regency` yang tidak di-import pada `DashboardController`, yang memicu HTTP 500 error saat diakses.
8. **Kerentanan Dependensi:** Audit Composer mendeteksi **63 advisories keamanan pada 18 paket** (termasuk TCPDF, DomPDF, dan Guzzle), serta Audit NPM mendeteksi **13 kerentanan** (1 Critical, 10 High).

Dokumentasi ini menyajikan rincian bukti teknis berbasis baris kode nyata, penilaian dampak terhadap database production, serta matriks prioritas perbaikan yang aman tanpa mengubah skema database.

---

## 2. Scope Audit

Pemeriksaan audit mencakup seluruh komponen kode pada direktori aplikasi:
- **Backend Architecture & Controllers:** `app/Http/Controllers/` (Backend, Frontend, Api, Auth, Subdirektori `konas`, `laporan`, `masterdata`, `ruang_pengurus`, `secret`, `web`).
- **Models & ORM:** Seluruh model pada `app/Models/` (41 Model Eloquent) dan event hooks.
- **Middleware & Kernel:** `app/Http/Kernel.php`, `app/Http/Middleware/` (`VerifyCsrfToken`, `Authenticate`, `LimitContactSubmission`, dll).
- **Rute Aplikasi:** `routes/web.php` (332 rute), `routes/api.php`, `routes/console.php`.
- **Konfigurasi & Server:** `config/*.php` (`app`, `auth`, `session`, `database`, `filesystems`, `cors`, `view`, `app_settings`), `.env.example`, `.env-live`, `public/.htaccess`, `php.ini`.
- **Frontend & Views:** `resources/views/` (Layouts, Partials, Dashboard, Verifikasi, Anggota, Laporan, Konas, Frontend Web), aset JavaScript, CSS, dan duplikat `views/` di root.
- **Dependencies & Tools:** `composer.json`, `composer.lock`, `package.json`, `package-lock.json`, `vite.config.js`.

---

## 3. Kondisi Teknologi Saat Ini

| Komponen | Kondisi Eksisting | Keterangan Teknis |
|---|---|---|
| **Framework PHP** | Laravel Framework 9.48.0 | `composer.json` mencatat `"laravel/framework": "^9.19"`. Versi ini sudah End-of-Life (EOL). |
| **PHP Runtime** | PHP 8.3.21 (CLI Aktif) / Target composer: `^8.0.2` | Runtime lokal siap untuk Laravel 11, namun deklarasi composer masih menargetkan PHP 8.0+. |
| **Frontend Engine** | 100% Laravel Blade | Tidak ada Inertia, Vue, React, maupun Livewire. |
| **UI Kit / Template** | Stisla (Bootstrap 4) | Menggunakan jQuery, Stisla scripts, FontAwesome 5, Select2, DataTables BS4. |
| **Build Tool** | Vite 4.0.0 (`vite.config.js`) | Vite terkonfigurasi untuk `resources/sass/app.scss` dan `resources/js/app.js`, namun layout utama masih meload CSS/JS statis dari `public/assets/`. |
| **Database** | MySQL / MariaDB (Production Active) | **STRUKTUR TERKUNCI (FROZEN)**. Dilarang migrasi, alter, drop, atau manipulasi skema. |
| **Authentication** | Laravel UI (`laravel/ui` 4.2) | Session-based login (`AuthenticatesUsers` trait), Sanctum terpasang. |
| **Authorization** | Spatie Laravel Permission v5.8 | Role & Permission berbasis middleware (`role`, `permission`), belum memakai Policy/Gates secara menyeluruh. |
| **DataTables Engine** | `yajra/laravel-datatables` v9.0 | Masih menggunakan engine v9 untuk Laravel 9. |
| **PDF Generator** | `spipu/html2pdf` v5.2 & `barryvdh/laravel-dompdf` v3.1 | Menarik dependensi TCPDF yang memiliki CVE tinggi. |
| **Excel Exporter** | `spatie/simple-excel` v3.7 | Digunakan untuk ekspor laporan data anggota. |
| **Wilayah Data** | `laravolt/indonesia` v0.33.0 | Referensi tabel provinsi, kabupaten/kota, kecamatan, kelurahan. |

---

## 4. Security Findings

---

### [CRITICAL] Remote Code Execution (RCE) via Unrestricted File Upload pada Pendaftaran SDM & Media Klinik

- **Lokasi:**  
  `app/Http/Controllers/Frontend/HomeController.php:638-651` (`store_sdm_pj`)  
  `app/Http/Controllers/Frontend/HomeController.php:712-718` (`store_sdm_dp`)  
  `app/Http/Controllers/Frontend/HomeController.php:765-771` (`store_sdm_tk`)  
  `app/Http/Controllers/Frontend/HomeController.php:814-820` (`store_sdm_tkl`)  
  `app/Http/Controllers/Frontend/HomeController.php:866-872` (`store_sdm_lain`)  
  `app/Http/Controllers/Frontend/HomeController.php:966-970` (`store_sdm_asuransi`)  
  `app/Http/Controllers/Frontend/HomeController.php:1022-1026` (`store_sdm_foto`)  
  `public/.htaccess:55-57`
- **Masalah:**  
  Metode-metode upload berkas SDM klinik dan media tidak melakukan validasi ekstensi, tipe MIME, maupun ukuran berkas sama sekali. Ekstensi berkas diambil langsung dari nama file klien via `$request->file(...)->getClientOriginalExtension()`, lalu dipindahkan ke folder publik `public/images/file/`. Pada saat yang sama, `public/.htaccess` mendefinisikan handler PHP aktif (`AddHandler application/x-httpd-ea-php81 .php .php8 .phtml`) tanpa memblokir eksekusi skrip di subfolder uploads.
- **Dampak:**  
  Penyerang dengan akun klinik dapat mengunggah file PHP (misal: `shell.php` atau `backdoor.phtml`), lalu mengakses `https://asklin.org/images/file/sip-<timestamp>.php` untuk mengeksekusi perintah sistem (Remote Code Execution) dan menguasai server production seutuhnya.
- **Bukti Source Code:**
  ```php
  // app/Http/Controllers/Frontend/HomeController.php:638-641
  if ($request->hasFile('file_sip_pj')) {
      $img_ext = $request->file('file_sip_pj')->getClientOriginalExtension();
      $file_sip = 'sip-' . time() . '.' . $img_ext;
      $path = $request->file('file_sip_pj')->move($destinationPath, $file_sip);
  }
  ```
  ```apache
  # public/.htaccess:55-57
  <IfModule mime_module>
    AddHandler application/x-httpd-ea-php81 .php .php8 .phtml
  </IfModule>
  ```
- **Rekomendasi:**  
  1. Tambahkan validasi ketat pada request: `'file_sip_pj' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'`.
  2. Gunakan hash nama acak dan hindari `$file->getClientOriginalExtension()`; gunakan `$file->extension()` bawaan PHP MIME detector.
  3. Simpan file di direktori non-eksekusi (private storage disk) atau pasang `.htaccess` di dalam `public/images/` dengan arahan `php_flag engine off` dan `RemoveHandler .php .phtml .php8`.
- **Status:** RESOLVED (Session 1 Remediated: Validasi tipe file/MIME/size, penamaan unik acak, dan proteksi direktori telah diimplementasikan di seluruh metode upload HomeController).

---

### [CRITICAL] Berkas Kredensial Produksi Terlacak di Git Repository (`.env-live`)

- **Lokasi:**  
  Root repository: `.env-live`  
  `.gitignore:14-16`
- **Masalah:**  
  Berkas `.env-live` yang berisi kredensial live production tercatat di dalam pelacakan Git (`git ls-files .env-live`). Berkas `.gitignore` hanya mengabaikan `.env`, `.env.backup`, `.env.production`, tetapi melewatkan `.env-live`.
- **Dampak:**  
  Setiap pihak atau kolaborator yang memiliki akses read ke git repository (atau jika repo terpublikasi) dapat melihat database username, password production, mail credentials, app key, dan rahasia infrastruktur lainnya.
- **Bukti Source Code:**
  ```bash
  $ git ls-files .env-live
  .env-live
  ```
- **Rekomendasi:**  
  1. Keluarkan berkas dari indeks Git tanpa menghapus file lokal: `git rm --cached .env-live`.
  2. Tambahkan `.env*` dan `.env-live` ke dalam `.gitignore`.
  3. Segera rotasi password database dan API credential production yang pernah tercatat di berkas tersebut.
- **Status:** RESOLVED (Session 1 Remediated: Berkas `.env-live` & `error_log` telah dikeluarkan dari Git tracking dan `.gitignore` diperbarui mengabaikan `.env*`).

---

### [CRITICAL] Hardcoded API Key & Unauthenticated Sensitive Data Leakage pada API Anggota

- **Lokasi:**  
  `app/Http/Controllers/Api/AnggotaApiController.php:153-157`  
  `routes/api.php:23-29`
- **Masalah:**  
  Rute API `/api/anggota/*` tidak dilindungi middleware autentikasi Sanctum/token standar. Validasi otorisasi mengandalkan fungsi private `validateApiKey()` yang berisi string token statis sepanjang 500+ karakter yang di-hardcode langsung di source code aplikasi.
- **Dampak:**  
  Siapapun yang membaca repository dapat menyalin static API key tersebut dan melakukan ekstraksi massal (data scraping/dump) seluruh basis data anggota ASKLIN (nama klinik, kontak, penanggung jawab, izin klinik, alamat lengkap).
- **Bukti Source Code:**
  ```php
  // app/Http/Controllers/Api/AnggotaApiController.php:153-157
  private function validateApiKey($apiKey)
  {
      // Untuk testing lokal, gunakan hardcoded key
      return $apiKey === '+HBmMyXqMLFPvhh4hd7EaYP/kFFQnas8t16vI74cErHScWG2YFzyoTIXyt+kxfmMn+PxxgO1WtAB5sxKSRVKhZhp2pHFFREzQ3v6t4DUfLylyzgz7b...';
  }
  ```
- **Rekomendasi:**  
  Hapus hardcoded string. Gunakan Sanctum token (`auth:sanctum`) atau simpan hash API key yang terdaftar di tabel khusus/environment variable yang aman.
- **Status:** RESOLVED (Session 1 Remediated: API key kini mendukung konfigurasi via `.env`/config dengan perbandingan timing-safe `hash_equals()`, serta penambahan middleware `throttle:60,1` pada rute API anggota).

---

### [CRITICAL] Unauthenticated Payment Status Tampering pada Webhook Callback Konas

- **Lokasi:**  
  `app/Http/Controllers/Frontend/KonasController.php:57-73` (`callback`)  
  `app/Http/Middleware/VerifyCsrfToken.php:14-16`  
  `routes/web.php:54`
- **Masalah:**  
  Endpoint `POST /konas/callback` sengaja dikecualikan dari verifikasi CSRF di `VerifyCsrfToken`, namun di controller tidak ada mekanisme verifikasi tanda tangan (signature verification), secret webhook key, IP whitelist payment gateway, maupun otentikasi lainnya.
- **Dampak:**  
  Penyerang mana pun di internet dapat mengirim HTTP POST request dengan payload JSON `{"external_id": "NOMOR_DOKUMEN", "status": "PAID"}` untuk menandai peserta konas dan pemesanan hotel sebagai "lunas" tanpa membayar uang sepeser pun.
- **Bukti Source Code:**
  ```php
  // app/Http/Controllers/Frontend/KonasController.php:57-73
  public function callback()
  {
      $data = request()->all();
      $status = $data['status'];
      $external_id = $data['external_id'];
      
      KonasPeserta::where('doc_no', $external_id)->update([
          'status_pembayaran' => $status
      ]);
      
      KonasBookingHotel::where('doc_no', $external_id)->update([
          'status_pembayaran' => $status
      ]);

      return response()->json($data);
  }
  ```
- **Rekomendasi:**  
  Terapkan HMAC signature verification (misal Xendit/Midtrans/Doku header token signature) dan validasi secret callback key sebelum mengeksekusi database update.
- **Status:** RESOLVED (Session 1 Remediated: Validasi payload webhook dan verifikasi token callback Xendit telah diterapkan pada `KonasController::callback`).

---

### [CRITICAL] IDOR Berdampak Account Takeover pada Update Password Profil

- **Lokasi:**  
  `app/Http/Controllers/Backend/ProfileController.php:26-44` (`update`)  
  `routes/web.php:114`
- **Masalah:**  
  Metode `ProfileController::update(Request $request, $id)` menerima ID pengguna dari URL parameter `/backend/profile/update/{id}`, namun tidak memverifikasi apakah `$id` tersebut merupakan ID milik pengguna yang sedang login (`Auth::id()`).
- **Dampak:**  
  Pengguna dengan role apapun (bahkan role klinik biasa yang memiliki akses login backend) dapat mengirim HTTP PUT ke `/backend/profile/update/1` (ID Superadmin) dan mengganti nama, email, serta password Superadmin, menyebabkan pengambilalihan akun tertinggi secara instan (Full Account Takeover).
- **Bukti Source Code:**
  ```php
  // app/Http/Controllers/Backend/ProfileController.php:26-43
  public function update(Request $request, $id)
  {
      $this->validate($request, [
          'name' => 'required',
          'email' => 'required|email|unique:users,email,'.$id,
          'password' => 'same:confirm-password',
      ]);
      ...
      $user = User::find($id); // <-- ID diambil dari URL tanpa cek Auth::id()!
      $user->update($input);
  ```
- **Rekomendasi:**  
  Abaikan parameter `$id` dari URL. Selalu gunakan `$user = Auth::user();` untuk pembaruan profil sendiri.
- **Status:** RESOLVED (Session 1 Remediated: `ProfileController::update` kini mengunci pembaruan akun secara ketat pada `Auth::user()` sehingga IDOR account takeover dicegah sepenuhnya).

---

### [CRITICAL] IDOR Berdampak Clinic Hijacking & Data Deletion pada Update Anggota

- **Lokasi:**  
  `app/Http/Controllers/Frontend/HomeController.php:483-560` (`updateAnggota`)  
  `routes/web.php:71`
- **Masalah:**  
  Rute `PUT /update-anggota/{id}` mengeksekusi update pada `Anggota::where('id', $id)` tanpa memverifikasi apakah klinik tersebut memang milik `Auth::user()->id`. Selain itu, kolom `id_user` dalam array update diisi dengan `Auth::user()->id`, dan file SIO lama klinik korban akan dihapus via `unlink()`.
- **Dampak:**  
  User A dapat mengirim request update dengan ID klinik milik User B. Dampaknya: User A mengambil alih kepemilikan klinik User B (`id_user` beralih ke User A), seluruh data klinik User B ditimpa, dan berkas izin SIO asli milik User B terhapus permanen dari storage.
- **Bukti Source Code:**
  ```php
  // app/Http/Controllers/Frontend/HomeController.php:500-557
  $anggotaLama = Anggota::where('id', $id)->first();
  ...
  $updateData = [
      'nama_klinik' => $request->nama_klinik,
      ...
      'id_user' => Auth::user()->id, // <-- Mengubah pemilik klinik menjadi user yang request!
      ...
  ];
  Anggota::where('id', $id)->update($updateData); // <-- Tanpa verifikasi kepemilikan!
  ```
- **Rekomendasi:**  
  Gunakan klausa kepemilikan: `$anggota = Anggota::where('id', $id)->where('id_user', Auth::id())->firstOrFail();` atau implementasikan Laravel Policy (`$this->authorize('update', $anggota)`).
- **Status:** RESOLVED (Session 1 Remediated: `HomeController::updateAnggota` kini memverifikasi kepemilikan klinik dengan `id_user == Auth::id()` sebelum mengizinkan pembaruan data dan berkas SIO).

---

### [HIGH] IDOR Deletion pada Data Asuransi, Rumah Sakit, SDM, dan Foto Klinik

- **Lokasi:**  
  `app/Http/Controllers/Frontend/HomeController.php:890-896` (`delete_sdm_pj`)  
  `app/Http/Controllers/Frontend/HomeController.php:936-940` (`delete_sdm_rumah_sakit`)  
  `app/Http/Controllers/Frontend/HomeController.php:987-995` (`delete_sdm_asuransi`)  
  `app/Http/Controllers/Frontend/HomeController.php:1041-1049` (`delete_sdm_foto`)
- **Masalah:**  
  Fungsi-fungsi penghapusan SDM, rumah sakit mitra, asuransi, dan foto klinik hanya menerima parameter `$request->id` dan langsung menghapus record serta file fisik tanpa memeriksa apakah entitas tersebut terhubung dengan klinik milik pengguna yang sedang login.
- **Dampak:**  
  Pengguna dapat menghapus data rekanan dan berkas bukti klinik lain secara masif hanya dengan mengiterasi integer ID.
- **Bukti Source Code:**
  ```php
  // app/Http/Controllers/Frontend/HomeController.php:936-940
  public function delete_sdm_rumah_sakit(Request $request)
  {
      $data = RumahSakit::where('id', $request->id)->delete();
      return Response()->json($data);
  }
  ```
- **Rekomendasi:**  
  Verifikasi relasi klinik sebelum delete: pastikan relasi `id_klinik` pada data cocok dengan `Anggota::where('id_user', Auth::id())->value('id')`.
- **Status:** RESOLVED (Session 1 Remediated: Seluruh metode penghapusan SDM, Rumah Sakit, Asuransi, dan Foto Klinik telah dibatasi strictly dengan klausa kepemilikan `id_klinik == $anggota->id`).

---

### [HIGH] Missing Authorization Checks pada Aksi Destruktif & Approval Backend

- **Lokasi:**  
  `app/Http/Controllers/Backend/AnggotaController.php:21` & `:291-340` (`destroy`)  
  `app/Http/Controllers/Backend/VerifikasiAnggotaController.php:17` & `:355-400` (`destroy`)  
  `app/Http/Controllers/Backend/VerifikasiAnggotaController.php:243-251` (`verifyBendahara`)
- **Masalah:**  
  Di dalam `__construct()`, controller hanya mendaftarkan middleware permission untuk metode `['only' => ['index', 'list']]`:
  ```php
  $this->middleware('permission:verifikasi-anggota', ['only' => ['index', 'list']]);
  $this->middleware('permission:data-anggota', ['only' => ['index', 'list']]);
  ```
  Sedangkan rute `destroy`, `verifyBendahara`, dan `verifyUpdate` berada di bawah middleware umum `auth` tanpa permission check di controller maupun route.
- **Dampak:**  
  Setiap user yang terotentikasi (termasuk role non-admin yang berhasil mengakses URL backend) dapat memicu HTTP DELETE ke `/backend/anggota/{id}` untuk menghapus total data anggota, SDM, asuransi, dan seluruh berkas terkait, atau memicu `/backend/verifikasi_anggota/verify/verifikasi_bendahara` untuk mengubah status pembayaran tanpa hak akses bendahara.
- **Bukti Source Code:**
  ```php
  // app/Http/Controllers/Backend/VerifikasiAnggotaController.php:243-251
  public function verifyBendahara(Request $request)
  {
      $id = $request->id;
      $data = Anggota::where('id', $id)->update([
          'status_pembayaran' => $request->status_pembayaran
      ]);
      return Response()->json($data);
  }
  ```
- **Rekomendasi:**  
  Daftarkan permission middleware spesifik pada controller atau route definition:
  `$this->middleware('permission:verifikasi-anggota-delete', ['only' => ['destroy']]);` dan `$this->middleware('role:Bendahara|Superadmin', ['only' => ['verifyBendahara']]);`.
- **Status:** OPEN

---

### [HIGH] IDOR pada Download SK Anggota & Cetak Sertifikat Publik/Anggota

- **Lokasi:**  
  `app/Http/Controllers/Frontend/HomeController.php:1165-1180` (`downloadSK`)  
  `app/Http/Controllers/Frontend/HomeController.php:1208-1225` (`cetakSertifikat`)  
  `routes/web.php:102-103`
- **Masalah:**  
  Rute `/download_sk/{id}` dan `/cetak_sertifikat/{id}` mengambil data anggota/sertifikat berdasarkan parameter ID tanpa memeriksa apakah ID tersebut milik klinik pengguna yang sedang login atau bukan.
- **Dampak:**  
  Pengguna anggota manapun dapat mengunduh Surat Keputusan (SK) resmi organisasi dan sertifikat keanggotaan klinik lain di seluruh Indonesia.
- **Bukti Source Code:**
  ```php
  // app/Http/Controllers/Frontend/HomeController.php:1165-1178
  public function downloadSK($id)
  {
      $anggota = Anggota::select(...)
          ...
          ->where('anggota.id', $id) // <-- Tidak ada validasi kepemilikan!
          ->first();
  ```
- **Rekomendasi:**  
  Tambahkan pengecekan: pastikan `$anggota->id_user === Auth::id()`, kecuali jika user memiliki role pengurus pusat / admin.
- **Status:** RESOLVED (Session 1 Remediated: Metode `downloadSK` dan `cetakSertifikat` kini memverifikasi otorisasi kepemilikan akun atau kepemilikan peran pengurus/admin sebelum dokumen PDF di-generate).

---

### [HIGH] Broken View Crash pada Modul Laporan Cabang dan Daerah

- **Lokasi:**  
  `app/Http/Controllers/Backend/laporan/LaporanCabangController.php:59`  
  `app/Http/Controllers/Backend/laporan/LaporanDaerahController.php:48`
- **Masalah:**  
  Kedua controller memanggil view DataTables action dengan nama `'backend.verifikasi.action'`. Berkas view `resources/views/backend/verifikasi/action.blade.php` **TIDAK ADA** (folder yang benar adalah `backend.verifikasi_anggota.action`).
- **Dampak:**  
  Setiap kali Admin Cabang atau Admin Daerah membuka halaman laporan dan DataTables melakukan request AJAX ke `/backend/laporan/cabang/list` atau `/backend/laporan/daerah/list`, aplikasi mengalami fatal error `View [backend.verifikasi.action] not found` (HTTP 500), merusak fungsionalitas pelaporan cabang dan daerah.
- **Bukti Source Code:**
  ```php
  // app/Http/Controllers/Backend/laporan/LaporanCabangController.php:59
  ->addColumn('action', 'backend.verifikasi.action')
  ```
- **Rekomendasi:**  
  Perbaiki target view menjadi `'backend.verifikasi_anggota.action'` atau buatkan partial view khusus laporan.
- **Status:** RESOLVED (Session 1 Remediated: Target view action pada `LaporanCabangController` dan `LaporanDaerahController` telah diperbaiki menjadi `'backend.verifikasi_anggota.action'`).

---

### [HIGH] Missing Import Class `Regency` pada Dashboard Controller

- **Lokasi:**  
  `app/Http/Controllers/Backend/DashboardController.php:101`
- **Masalah:**  
  Metode `getKota(Request $request)` memanggil model `Regency::where(...)`. Namun, pada bagian header file (`use ...`), model `Regency` tidak di-import sama sekali (hanya `City` dan `Province` yang di-import dari namespace Laravolt).
- **Dampak:**  
  Jika metode `getKota` dipanggil dari dashboard, aplikasi akan mengalami PHP Fatal Error `Class "App\Http\Controllers\Backend\Regency" not found`.
- **Bukti Source Code:**
  ```php
  // app/Http/Controllers/Backend/DashboardController.php:7-9 & 101
  use Laravolt\Indonesia\Models\City;
  use Laravolt\Indonesia\Models\Province;
  use App\Models\{Anggota};
  ...
  public function getKota(Request $request)
  {
      $kota = Regency::where('province_code', $request->id_provinsi)->get();
  ```
- **Rekomendasi:**  
  Ganti `Regency` dengan model `City` yang sudah di-import: `City::where('province_code', $request->id_provinsi)->get();`.
- **Status:** RESOLVED (Session 1 Remediated: Pemanggilan class `Regency` di `DashboardController` telah diganti dengan model `City` bawaan Laravolt yang telah ter-import).

---

### [HIGH] Kerentanan Kritis pada Dependensi PHP & Node.js (63 Composer Advisories, 13 NPM Advisories)

- **Lokasi:**  
  `composer.lock` & `package-lock.json`
- **Masalah:**  
  - Paket `tecnickcom/tcpdf` (<6.8.0) terdampak CVE-2024-56522, CVE-2024-56521, dan CVE-2024-51058 (Local File Inclusion & Missing Certificate Validation).
  - Paket `dompdf/dompdf` (<3.1.6) terdampak CVE-2026-59943, CVE-2026-59942, CVE-2026-56722 (Arbitrary File Read via SVG data-URI & DoS).
  - Paket `guzzlehttp/guzzle` (<7.15.2) terdampak CVE-2026-69246 (Host Check Bypass).
  - Paket NPM `form-data` (Critical CRLF injection), `axios` (10 High advisories), `lodash` (Prototype Pollution), `postcss` (Arbitrary file read).
- **Dampak:**  
  Komponen PDF generation rentan terhadap Local File Inclusion (LFI) dan SSRF, serta serangan denial of service saat memproses input gambar/SVG.
- **Rekomendasi:**  
  Catat dalam roadmap upgrade: jadwalkan pembaruan versi paket pada sesi upgrade framework.
- **Status:** OPEN

---

### [MEDIUM] Memory Exhaustion Risk pada DataTables & Ekspor Laporan

- **Lokasi:**  
  `app/Http/Controllers/Backend/VerifikasiAnggotaController.php:123-125`  
  `app/Http/Controllers/Backend/AnggotaController.php:140-142`  
  `app/Http/Controllers/Backend/laporan/LaporanPusatController.php:122` & `:184` & `:286`
- **Masalah:**  
  Alih-alih memanfaatkan server-side database querying dari DataTables (`DataTables::eloquent($query)`), controller mengeksekusi `->get()` untuk menarik ribuan record ke memory PHP, lalu melakukan filter menggunakan `Collection::filter()`. Demikian juga pada saat export Excel dan PDF, seluruh data di-fetch sekaligus dalam satu waktu.
- **Dampak:**  
  Saat jumlah anggota bertambah, konsumsi RAM server melonjak tajam hingga batas memory limit tercapai, menyebabkan request timeout atau crash proses PHP-FPM. (Hal ini terlihat dari konfigurasi darurat di `php.ini` production yang menyetel `memory_limit = -1`).
- **Bukti Source Code:**
  ```php
  // app/Http/Controllers/Backend/VerifikasiAnggotaController.php:123-129
  $data = ...->get();
  return Datatables::of($data)
      ->filter(function ($instance) use ($request) {
          $instance->collection = $instance->collection->filter(...);
      })
  ```
- **Rekomendasi:**  
  Gunakan passing Query Builder langsung ke DataTables: `return Datatables::of($query)->make(true);` sehingga query limit, offset, dan filtering dieksekusi efisien di level MySQL. Pada proses export, gunakan cursor atau chunking (`chunk(200)`).
- **Status:** OPEN

---

### [MEDIUM] Paket Deprecated / Abandoned `laravelcollective/html`

- **Lokasi:**  
  `composer.json:15` (`"laravelcollective/html": "^6.3"`)
- **Masalah:**  
  Paket `laravelcollective/html` telah dinyatakan **ABANDONED** secara resmi oleh pembuatnya dan tidak mendapatkan pembaruan untuk Laravel 11.
- **Dampak:**  
  Menjadi blocker utama saat upgrade ke Laravel 11. Kode Blade yang menggunakan `{!! Form::select(...) !!}` akan error jika paket ini tidak dimigrasi.
- **Rekomendasi:**  
  Rencanakan migrasi form helper ke Blade HTML sintaks asli dengan Tailwind CSS atau ganti dengan paket modern `spatie/laravel-html`.
- **Status:** OPEN

---

### [MEDIUM] Potensi Null Pointer Exception pada Event Booted Model `Anggota`

- **Lokasi:**  
  `app/Models/Anggota.php:58-67`
- **Masalah:**  
  Model `Anggota` mendefinisikan static booted listener:
  ```php
  static::creating(function ($model) {
      $model->created_by = Auth()->user()->id;
      $model->updated_by = Auth()->user()->id;
  });
  ```
- **Dampak:**  
  Jika ada proses create/save anggota yang dieksekusi di luar sesi login web (misal: webhook callback, background job, seeder, atau artisan console command), `Auth()->user()` akan bernilai `null` dan memicu fatal error: `Attempt to read property "id" on null`.
- **Rekomendasi:**  
  Gunakan safe operator: `Auth::id() ?? null` atau `auth()->user()?->id`.
- **Status:** RESOLVED (Session 1 Remediated: Event hook `booted` pada model `Anggota` kini menggunakan safe check `auth()->id()` untuk mencegah Null Pointer Exception).

---

### [MEDIUM] Duplikasi Direktori Blade di Root (`views/`)

- **Lokasi:**  
  Folder `views/` di root direktori project.
- **Masalah:**  
  Terdapat direktori `views/` lengkap di root project yang terlacak di Git, sementara Laravel secara default membaca `resources/views/` (sesuai `config/view.php:17`).
- **Dampak:**  
  Technical debt besar yang membingungkan developer saat melakukan maintenance (ada kemungkinan developer mengedit file di `views/` tetapi perubahannya tidak berpengaruh karena aplikasi membaca `resources/views/`).
- **Rekomendasi:**  
  Setelah memastikan tidak ada custom loader yang mereferensikan root `views/`, bersihkan dan hapus folder tersebut dari git tracking pada sesi pembersihan.
- **Status:** OPEN

---

### [LOW] Potensi Unescaped Output pada SweetAlert JS Notification

- **Lokasi:**  
  `resources/views/backend/berita/create.blade.php:133`  
  `resources/views/backend/berita/edit.blade.php:161`
- **Masalah:**  
  Template Blade mencetak error validation langsung ke dalam JavaScript string literal:
  `html: '{!! implode('<br>', $errors->all()) !!}'`.
- **Dampak:**  
  Jika pesan error berisi karakter single quote `'` atau payload yang belum difilter, skrip JS browser akan rusak (syntax error) atau berpotensi memicu DOM-based XSS.
- **Rekomendasi:**  
  Gunakan directive `@json($errors->all())` dan render list item melalui manipulasi DOM yang aman.
- **Status:** OPEN

---

### [LOW] Konfigurasi Session Cookie `secure` Default Null

- **Lokasi:**  
  `config/session.php:171`
- **Masalah:**  
  `'secure' => env('SESSION_SECURE_COOKIE')`. Jika variabel `SESSION_SECURE_COOKIE` tidak diisi di `.env`, nilainya menjadi `null/false`.
- **Dampak:**  
  Cookie sesi dapat dikirim melalui sambungan plaintext HTTP jika pengguna mengakses tanpa paksaan HTTPS.
- **Rekomendasi:**  
  Setel default `'secure' => env('SESSION_SECURE_COOKIE', true)`.
- **Status:** OPEN

---

### [INFO] Pola Query Non-Agregat pada Dashboard Controller

- **Lokasi:**  
  `app/Http/Controllers/Backend/DashboardController.php:22-75`
- **Masalah:**  
  Metode `DashboardController::index` melakukan 10 query terpisah ke tabel `anggota` untuk menghitung masing-masing status (`approved`, `proses`, `ditolak pusat`, `Perlu Perbaikan`, `Diverifikasi Cabang`, `Terverifikasi Cabang`, `waiting`, `create_dokter`, dll). Bahkan query `Terverifikasi Cabang` dieksekusi dua kali secara redundan (baris 52 dan baris 70).
- **Dampak:**  
  Menimbulkan 10-12 query database berurutan setiap kali user membuka halaman dashboard utama.
- **Rekomendasi:**  
  Gabungkan menjadi 1 query agregat dengan `SELECT status, count(*) as total FROM anggota GROUP BY status`.
- **Status:** OPEN

---

## 5. Authentication Audit

1. **Login & Logout:**
   - Menggunakan Laravel UI Auth (`Auth\LoginController`), memanfaatkan trait `AuthenticatesUsers`.
   - Menggunakan default email + password credentials.
   - Logout menggunakan metode HTTP POST dengan proteksi `@csrf` (`resources/views/layouts/backend/header.blade.php`).
2. **Password Handling & Hashing:**
   - Password di-hash menggunakan algoritma Bcrypt via `Hash::make()`.
   - Password reset menggunakan token bertanda waktu 60 menit dengan tabel `password_resets`.
3. **Remember Me & Session Fixation:**
   - Fitur remember me memanfaatkan `remember_token` default Laravel.
   - Session fixation dicegah oleh middleware bawaan Laravel yang me-regenerate session ID saat login sukses.
4. **Route Tanpa Login:**
   - Halaman frontend publik: `/`, `/berita`, `/event-asklin`, `/galery`, `/kontak`, `/sejarah`, `/visi_misi`, `/latar_belakang`, `/pengurus_pusat`, `/agenda_kegiatan`, `/konas/*`.
   - Verifikasi publik QR: `/cekqrcode/{id}` dan `/cekqrsertifikat/{id}`.
   - Endpoint wilayah AJAX publik: `/getKota`, `/getKecamatan`, `/getKelurahan`.

---

## 6. Authorization Audit

1. **Role & Permission Setup:**
   - Menggunakan package `spatie/laravel-permission` (v5.8).
   - Middleware `role:Superadmin` diterapkan di grup route `/backend/secret`.
   - Role yang terdefinisi dalam aplikasi: `Superadmin`, `Admin Pusat`, `Admin Daerah`, `Admin Cabang`, `Sekjen`, `Ketua Umum`, `Bendahara`, `Admin Konas`, `Klinik`.
2. **Kelemahan Otorisasi:**
   - Controller backend hanya memproteksi metode `index` dan `list` dengan permission (`['only' => ['index', 'list']]`), membiarkan metode write/delete terbuka untuk sembarang user terautentikasi (IDOR & Privilege Escalation).
   - Tidak ada Laravel Model Policy (`app/Policies/` kosong). Otorisasi tidak terikat ke level kepemilikan instance model.

---

## 7. IDOR (Insecure Direct Object Reference) Audit

Audit menemukan 5 titik kerentanan IDOR yang sangat berbahaya:
1. **Clinic Takeover:** `PUT /update-anggota/{id}` (`HomeController::updateAnggota`) tidak mengecek relasi `id_user`.
2. **Profile / Password Takeover:** `PUT /backend/profile/update/{id}` (`ProfileController::update`) dapat mengubah akun siapapun termasuk ID 1 (Superadmin).
3. **Data Klinik Sub-entitas:** `delete_sdm_pj`, `delete_sdm_rumah_sakit`, `delete_sdm_asuransi`, `delete_sdm_foto` menghapus data entitas klinik lain berdasarkan parameter `$request->id`.
4. **Unduh SK & Cetak Sertifikat:** `downloadSK($id)` dan `cetakSertifikat($id)` mencetak dokumen resmi klinik manapun.
5. **Approval Pembayaran:** `verifyBendahara` mengubah status lunas klinik lain tanpa validasi role bendahara.

---

## 8. Input Validation Audit

1. **Form Request:** Tidak ada satupun Form Request class yang dibuat (`app/Http/Requests` tidak ada).
2. **Validasi Kontroler:** Validasi tidak konsisten; sebagian menggunakan `$this->validate()`, sebagian `$request->validate()`, dan sebagian besar metode helper SDM tidak menggunakan validasi sama sekali.
3. **Mass Assignment:** Model menggunakan properti `$fillable`. Namun, karena data `$request->all()` diteruskan ke `User::create()` dan `User::update()`, ada risiko parameter tersembunyi dapat terinjeksi.

---

## 9. SQL Injection Audit

1. **Analisis `DB::raw` & `whereRaw`:**
   - Penggunaan `whereRaw` di `VerifikasiAnggotaController:74-112` menggunakan parameterized binding `?` (`[$tahunSekarang]`), sehingga **aman** dari SQL Injection.
   - Query `GROUP_CONCAT` dan `FIND_IN_SET` pada filter fasilitas klinik menggunakan hardcoded string static, **bukan input dari user**.
2. **Status:** Tidak ditemukan kerentanan SQL Injection langsung (SQLi) yang dapat dieksploitasi pada codebase saat ini. (Verdict: SAFE / False Positive untuk SQLi).

---

## 10. XSS (Cross-Site Scripting) Audit

1. **Blade Output:** Sebagian besar view Blade menggunakan sintaks escaping standar `{{ $data }}` yang aman.
2. **Penggunaan `{!! !!}`:**
   - `resources/views/frontend/home/beritadetail.blade.php:37`: `{!! $berita->konten !!}` (WYSIWYG konten berita). Harus dipastikan editor berita hanya dapat diakses oleh user tepercaya atau konten disanitasi menggunakan HTMLPurifier.
   - `resources/views/backend/berita/create.blade.php:133`: error array dimasukkan ke SweetAlert JS literal.
3. **DataTables Action HTML:** Sebagian tombol aksi me-render atribut inline onclick `confirmDeleteVerifikasi({{ $id }}, '{{ addslashes($nama_klinik) }}', '{{ $status }}')`. Karakter `$status` tidak di-escape dengan `addslashes`, berpotensi memicu syntax error jika terdapat karakter kutip tunggal.

---

## 11. CSRF Audit

1. **Proteksi Form:** Seluruh form backend dan frontend Blade telah menyertakan directive `@csrf`.
2. **CSRF Exemption:**
   - `app/Http/Middleware/VerifyCsrfToken.php:15`: URI `'/konas/callback'` dikecualikan dari verifikasi CSRF. Karena endpoint ini juga tidak memverifikasi signature/token, hal ini membuka celah manipulasi status pembayaran peserta konas.

---

## 12. File Upload Audit

1. **Validasi File:**
   - Pada `HomeController::updateAnggota`, berkas SIO divalidasi dengan `nullable|file|mimes:pdf,jpg,jpeg,png|max:5120`.
   - Pada seluruh metode pendaftaran SDM (`file_sip_pj`, `file_str_pj`, `file_str_dp`, `bukti_as`, `foto_foto`), **TIDAK ADA VALIDASI** tipe file, ekstensi, maupun MIME.
2. **Storage Location:** Berkas disimpan ke folder publik `public/images/file/`.
3. **Eksekusi Server:** `.htaccess` di direktori `public` mengizinkan eksekusi skrip PHP melalui `AddHandler application/x-httpd-ea-php81 .php .php8 .phtml`, menjadikan upload file ini berisiko **Remote Code Execution (RCE)**.

---

## 13. Mass Assignment Audit

Seluruh 41 model Eloquent di `app/Models/` telah mengonfigurasi array `$fillable` secara eksplisit dan tidak ada model yang menggunakan `$guarded = []`. Properti penting seperti role dan permission tidak berada di tabel utama users secara langsung, melainkan melalui tabel pivot Spatie. Namun, penyerahan `$request->all()` langsung pada create user tetap perlu diwaspadai jika kolom baru ditambahkan di masa mendatang.

---

## 14. Sensitive Data Audit

1. **Credential Exposure:** Berkas `.env-live` terlacak di git repository.
2. **Hardcoded Keys:** Hardcoded API key di `AnggotaApiController.php`.
3. **Logging:** `storage/logs/laravel.log` mencatat informasi penghapusan data klinik (`id` dan `nama_klinik`), tidak ditemukan kebocoran password atau credential di log.

---

## 15. Session & Cookie Audit

- Driver sesi: `file` (standar Laravel).
- Masa berlaku sesi: 120 menit (`SESSION_LIFETIME=120`).
- `http_only`: `true` (mencegah akses JavaScript terhadap cookie sesi).
- `same_site`: `'lax'` (mitigasi CSRF lintas situs).
- `secure`: bergantung pada variabel `SESSION_SECURE_COOKIE` (perlu dipastikan bernilai `true` di server HTTPS).

---

## 16. Route Audit

Dari total 332 rute yang terdaftar dalam aplikasi:
- **Rute Publik Frontend:** Rute informasi ASKLIN, berita, agenda, konas, cek QR sertifikat.
- **Rute Anggota Frontend (Auth):** Rute profil, pengisian formulir klinik, SDM, tagihan iuran, cetak SK/sertifikat.
- **Rute Backend (Auth):**
  - Prefix `/backend/secret`: dilindungi middleware `role:Superadmin`.
  - Prefix `/backend/*` lainnya: hanya dilindungi middleware `auth` di route definition, sementara filter permission di level controller banyak yang bolong (hanya memproteksi `index` dan `list`).

---

## 17. Dependency Audit

1. **Framework Core:** `laravel/framework` 9.48.0 (EOL). Memerlukan upgrade bertahap: Laravel 9 -> Laravel 10 -> Laravel 11.
2. **Ketergantungan Kritis:**
   - `laravelcollective/html` (v6.3): Abandoned, harus diganti sebelum Laravel 11.
   - `spatie/laravel-permission` (v5.8): Perlu upgrade ke v6.x untuk kompatibilitas Laravel 11.
   - `yajra/laravel-datatables` (v9.0): Perlu upgrade ke v10/v11.
   - `tecnickcom/tcpdf` (via html2pdf): Terdampak 7 CVEs.
   - `dompdf/dompdf`: Terdampak 6 CVEs.

---

## 18. Configuration Audit

- **Environment Template:** `.env.example` bersih dan tidak memuat credential asli.
- **PHP Config Server:** Berkas `php.ini` di root mematikan batasan eksekusi: `memory_limit = -1`, `max_execution_time = -1`, `post_max_size = 8000M`. Setting ini berbahaya karena dapat menyebabkan server mengalami DoS jika terjadi infinite loop atau upload berkas berukuran gigabyte.

---

## 19. Error & Exception Audit

- `app/Exceptions/Handler.php` menggunakan penanganan standar Laravel.
- Di beberapa controller, blok `try { ... } catch (\Exception $e) { ... }` mencetak pesan error langsung via `echo $formatter->getHtmlMessage(); exit();` (pada Html2Pdf generation di `AnggotaController.php:456` dan `:493`), yang membocorkan path server lokal jika terjadi error rendering.

---

## 20. Performance Audit

1. **Query N+1 & Missing Eager Loading:**
   - Pada `UserController::list`, pemanggilan `$data->transform(...)` memanggil `$item->roles->pluck(...)`, beruntung sudah menggunakan `with('roles')`.
   - Pada `HomeController::event`, sudah menggunakan `with(['kategori'])`.
   - Namun pada `DashboardController::index`, terdapat 10 pemanggilan `count()` berturut-turut pada tabel yang sama.
2. **Pola Penarikan Data (In-Memory Processing):**
   - Datatables pada `VerifikasiAnggotaController`, `LaporanPusatController`, dan `LaporanCabangController` mengeksekusi `->get()` sebelum passing ke Datatables, membebani RAM PHP server.

---

## 21. Blade/UI Code Audit

1. **Template Engine:** Menggunakan template Stisla (berbasis Bootstrap 4.3).
2. **Asset Loading:** CSS dan JavaScript di-load secara manual melalui tag `<link>` dan `<script>` statis dari direktori `public/assets/backend/`. Vite tidak digunakan pada layout backend utama.
3. **Inline Scripts:** Sangat banyak script jQuery dan inisialisasi AJAX yang ditulis inline di dalam section `@push('js')` di setiap berkas view Blade.
4. **Duplikasi HTML:** Struktur kartu, modal konfirmasi, dan tabel diulang-ulang di setiap view tanpa komponen Blade reusable.

---

## 22. Code Quality Audit

1. **Dead Code & Duplikasi:**
   - Duplikasi folder `views/` di root.
   - Query ganda `$terverifikasi_cabang` pada `DashboardController.php:52` dan `:70`.
2. **Missing Imports:**
   - `Regency` pada `DashboardController.php:101`.
3. **Direct Echo:**
   - `WilayahController.php:16-43` menggunakan perintah PHP native `echo "<option..."` di dalam controller alih-alih mengembalikan JSON response atau Blade view.

---

## 23. Risk Priority Matrix

| Priority | Finding | Severity | Impact | Recommended Action |
|---|---|---|---|---|
| **P1** | RCE via Unrestricted File Upload pada SDM & Media | **CRITICAL** | Eksekusi kode arbitrary / Pengambilalihan server penuh | Terapkan validasi ekstensi/MIME ketat & non-aktifkan eksekusi skrip di folder upload |
| **P1** | File Kredensial Produksi (`.env-live`) Terlacak di Git | **CRITICAL** | Kebocoran kredensial database live dan secret keys | Untrack dari git (`git rm --cached`), abaikan di `.gitignore`, rotasi kredensial |
| **P1** | Hardcoded API Key pada `AnggotaApiController` | **CRITICAL** | Akses data massal tanpa otorisasi | Hapus hardcoded key, ganti dengan Sanctum token |
| **P1** | Unauthenticated Payment Webhook Bypass (`/konas/callback`) | **CRITICAL** | Pemalsuan status lunas pada pendaftaran konas & hotel | Pasang verifikasi HMAC signature / webhook secret token |
| **P1** | IDOR Account Takeover pada Profil Admin | **CRITICAL** | Pengambilalihan akun Superadmin oleh user lain | Kunci update hanya untuk `Auth::user()` tanpa memakai ID dari URL |
| **P1** | IDOR Clinic Hijacking pada Update Anggota | **CRITICAL** | Perubahan kepemilikan dan penghapusan data klinik lain | Tambahkan validasi kepemilikan `where('id_user', Auth::id())` |
| **P2** | IDOR Deletion pada SDM, Asuransi, Rumah Sakit, Foto | **HIGH** | Kerusakan/kehilangan data klinik lain secara acak | Validasi kepemilikan relasi sebelum eksekusi penghapusan |
| **P2** | Missing Permission Checks pada Action Destruktif Backend | **HIGH** | Eksekusi aksi delete & verifikasi bendahara oleh user tidak sah | Pasang middleware permission pada action `destroy` & `verifyBendahara` |
| **P2** | Broken View Crash pada Laporan Cabang & Daerah | **HIGH** | HTTP 500 error saat admin membuka laporan cabang/daerah | Arahkan nama view ke `backend.verifikasi_anggota.action` |
| **P2** | Missing Class `Regency` pada DashboardController | **HIGH** | Fatal crash saat fungsi `getKota` dipanggil | Ubah `Regency` menjadi `City` yang sudah di-import |
| **P2** | Dependensi Usang dengan CVE Tinggi (TCPDF, DomPDF, Guzzle) | **HIGH** | Potensi LFI dan DoS saat generate dokumen | Jadwalkan update dependensi saat upgrade framework |
| **P3** | Memory Exhaustion pada Datatables & Export Laporan | **MEDIUM** | Lonjakan RAM server & potensi downtime | Gunakan Query Builder server-side pada Datatables & cursor chunking |
| **P3** | Dependensi Abandoned `laravelcollective/html` | **MEDIUM** | Pemblokir upgrade Laravel 11 | Rencanakan migrasi ke Blade HTML murni / Tailwind |
| **P3** | Null Pointer Exception pada Model Boot `Anggota` | **MEDIUM** | Crash proses CLI, cron, atau background worker | Gunakan null-safe operator `Auth::id() ?? null` |
| **P3** | Duplikasi Folder `views/` di Root | **MEDIUM** | Kebingungan maintenance kode sumber | Hapus direktori duplikat `views/` dari git |
| **P4** | Multiple COUNT Query pada Dashboard Controller | **INFO** | Latensi pembukaan dashboard meningkat | Gabungkan 10 query COUNT menjadi 1 query agregat `GROUP BY` |

---

## 24. Quick Wins

Perbaikan cepat yang **sangat aman, berdampak besar, dan TIDAK menyentuh database production sama sekali**:
1. **Amankan Git:** Jalankan `git rm --cached .env-live` dan tambahkan `.env*` serta `.env-live` ke `.gitignore`.
2. **Perbaiki IDOR Profile:** Ubah `ProfileController::update` agar hanya mengupdate `Auth::user()` (1 baris perubahan).
3. **Perbaiki IDOR Clinic Hijack:** Tambahkan filter `->where('id_user', Auth::id())` pada `HomeController::updateAnggota`.
4. **Perbaiki Fatal Error Laporan:** Ganti `'backend.verifikasi.action'` menjadi `'backend.verifikasi_anggota.action'` pada `LaporanCabangController` dan `LaporanDaerahController`.
5. **Perbaiki Missing Class Dashboard:** Ganti pemanggilan `Regency` menjadi `City` pada `DashboardController:101`.
6. **Perbaiki IDOR Sub-entitas:** Validasi kepemilikan klinik sebelum menghapus SDM, Rumah Sakit, Asuransi, dan Foto.

---

## 25. Major Technical Debt

1. **Framework Tertinggal (Laravel 9.48.0):** Memerlukan upgrade bertahap: Laravel 9 -> Laravel 10 -> Laravel 11.
2. **Ketergantungan Paket Usang:** Penggunaan `laravelcollective/html` dan versi lama Spatie Permission serta Yajra DataTables.
3. **Penyatuan Server-side Querying yang Buruk:** Penggunaan `Collection::filter` setelah `->get()` alih-alih memanfaatkan SQL query engine.
4. **Template Monolitik (Stisla):** Campuran kompleks antara jQuery 3.6, Bootstrap 4, Nicescroll, dan puluhan script inline yang tersebar di seluruh view.
5. **Ketiadaan Test Coverage:** Tidak ada feature test atau unit test yang memvalidasi workflow bisnis existing.

---

## 26. Recommended Next Steps

1. **Fase 1 (Dokumentasi):** Selesaikan Audit Keamanan (`AUDIT-KEAMANAN.md`) dan Blueprint Perombakan (`BLUEPRINT-PEROMBAKAN.md`).
2. **Fase 2 (Security Hardening Awal):** Eksekusi Quick Wins keamanan (amankan `.env-live`, perbaiki IDOR kritis, tutup celah unrestricted upload) sebelum menyentuh redesign UI.
3. **Fase 3 (Upgrade Framework):** Lakukan upgrade bertahap `Laravel 9 -> 10` lalu `Laravel 10 -> 11` di branch terpisah dengan pengujian menyeluruh tanpa mengubah skema database.
4. **Fase 4 (Setup Tailwind & Design System):** Bangun design system baru dengan Tailwind CSS dan dark mode support.
5. **Fase 5 (Implementasi UI & Modul Per Sesi):** Redesign tampilan secara bertahap (Dashboard, Anggota, Verifikasi, Laporan, Konas, Administrasi) sesuai roadmap blueprint yang telah disetujui.

