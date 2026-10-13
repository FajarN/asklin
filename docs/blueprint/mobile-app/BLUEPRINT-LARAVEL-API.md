# BLUEPRINT PENGEMBANGAN LARAVEL REST API UNTUK ASKLIN MOBILE APP

**Tanggal Penyusunan:** 13 Oktober 2026  
**Status:** DRAFT BLUEPRINT (Tahap Perencanaan - Belum Ada Perubahan Kode)  
**Aplikasi Backend:** ASKLIN (Asosiasi Klinik Indonesia)  
**Target Client:** Flutter Android Mobile App (ASKLIN Mobile)  
**Lokasi File:** `/docs/blueprint/mobile-app/BLUEPRINT-LARAVEL-API.md`

---

## 1. Ringkasan Kondisi Backend Saat Ini

1. **Framework & Dependensi:**
   - Framework: **Laravel 9.48.0** (PHP ^8.0.2 / CLI lokal PHP 8.3).
   - Package Token: `laravel/sanctum` ^3.0 sudah terpasang.
   - Package Otorisasi: `spatie/laravel-permission` ^5.8 sudah terpasang.
   - Package Wilayah: `laravolt/indonesia` ^0.33.0 sudah terpasang.
   - Package PDF: `spipu/html2pdf` ^5.2 dan `barryvdh/laravel-dompdf` ^3.1.
2. **Kondisi Database Production:**
   - Engine MySQL / MariaDB dengan basis data relasional.
   - **Status Skema: TERKUNCI (FROZEN)**. Implementasi REST API tidak diperkenankan menambah migrasi yang mengubah struktur kolom yang sudah berjalan di website live.
3. **Kondisi Rute API Eksisting:**
   - Berkas `routes/api.php` hanya memiliki route default `auth:sanctum` dan endpoint sinkronisasi lama `AnggotaApiController` berbasis parameter API key statis yang tidak aman.
   - Seluruh rute fungsional saat ini berada di `routes/web.php` dan terikat erat pada view Blade serta engine jQuery DataTables.

---

## 2. Arsitektur Backend & Alur Komunikasi Sistem

```
+-----------------------------------------------------------+
|               Flutter Android Mobile App                  |
|  - Dio HTTP Client dengan Interceptor Bearer Token        |
|  - Secure Storage (Encrypted JWT/Sanctum Token)           |
+-----------------------------+-----------------------------+
                              |
                     HTTPS / JSON Payload
                              |
                              v
+-----------------------------------------------------------+
|              Laravel 9.x REST API Layer                   |
|  - Route Group: /api/v1/* (routes/api.php)                |
|  - Middleware: auth:sanctum, throttle:60,1                |
|  - Middleware Kustom: VerifyRegionalAccess                |
|  - Form Request Validation & JsonResponseFormatter        |
+-----------------------------+-----------------------------+
                              |
                              v
+-----------------------------------------------------------+
|               Service & Domain Logic                      |
|  - AuthService (Token Issuance & Revocation)              |
|  - LaporanService (Data Monitoring Pusat, Daerah, Cabang) |
|  - DocumentService (Secure Stream SK & Sertifikat)        |
|  - AnggotaProfileService (Info Klinik, SDM, Status)       |
+-----------------------------+-----------------------------+
                              |
                              v
+-----------------------------------------------------------+
|                  MySQL Database (Existing)                |
|  - users, personal_access_tokens, model_has_roles         |
|  - anggota, sdm, fasilitas_klinik, sertifikat, pembayaran |
|  - struktur_organisasi, sk_pengurus, indonesia_regions    |
+-----------------------------------------------------------+
```

### Prinsip Utama Komunikasi:
1. **Stateless Authentication:** Komunikasi sepenuhnya bebas dari sesi cookie PHP. Menggunakan header `Authorization: Bearer <token>`.
2. **Strict Regional Isolation:** Server menentukan wilayah pengguna dari token autentikasi pengguna (`users.provinsi`, `users.kota`), bukan dari parameter yang dikirim oleh perangkat mobile.
3. **Non-Disruptive Extension:** Penambahan rute API di `routes/api.php` tidak akan mengubah atau mengganggu rute web existing di `routes/web.php`.

---

## 3. Komponen Existing yang Dapat Digunakan Kembali

1. **`App\Models\User`:** Sudah menggunakan trait `HasApiTokens` dan `HasRoles`.
2. **`App\Services\LaporanService`:** Menyediakan fungsi agregasi data monitoring untuk cabang, daerah, dan pusat:
   - `resolveScope()`
   - `getDashboardStats()`
   - `getGrowthChartData()`
   - `getStatusChartData()`
   - `getRegionalDistributionChartData()`
   - `getRekapitulasiData()`
3. **`App\Traits\RegionalScoping`:** Logika filter query database berdasarkan peran dan kota/provinsi user login.
4. **View Blade Template Dokumen:** View `backend.anggota.printsk` dan `frontend.print_sertifikat` digunakan ulang oleh service generator dokumen tanpa mendesain ulang layout dokumen resmi.
5. **Data Master Wilayah:** Tabel `indonesia_provinces`, `indonesia_cities`, `indonesia_districts`, dan `indonesia_villages`.

---

## 4. Kesenjangan Antara Fitur Saat Ini & Kebutuhan Mobile

| Kebutuhan Mobile | Kondisi Eksisting | Kesenjangan / Gap | Solusi Penyesuaian |
|---|---|---|---|
| Login via Mobile | Form Web Session Login | Tidak ada endpoint penerbitan token | Buat `AuthController::login` via Sanctum |
| Logout Mobile | Web Session Invalidation | Tidak ada endpoint pencabutan token | Buat `AuthController::logout` mencabut token aktif |
| Master Wilayah | `WilayahController` return HTML `<option>` | Tidak bisa diparsing JSON oleh Flutter | Buat `Api\WilayahController` return JSON array |
| Daftar & Detail Anggota | Yajra DataTables Server-side BS4 | Format respons khusus jQuery DataTables | Buat `Api\AnggotaController` dengan pagination standar |
| Unduh SK / Sertifikat | Direct render & `exit()` di web | Menghentikan eksekusi, rawan IDOR lintas wilayah | Buat API endpoint dengan metadata & secure signed download |
| Monitoring Dashboard | Memerlukan session admin web | Belum ada endpoint JSON terproteksi token | Buat `Api\DashboardController` adaptif role |

---

## 5. Rancangan Autentikasi API

### Mekanisme Autentikasi
- **Driver:** Laravel Sanctum Token (`HasApiTokens`).
- **Nama Token:** `asklin-mobile-app`.
- **Masa Berlaku Token:** 30 hari (dapat dikonfigurasi melalui `config/sanctum.php` setting `expiration => 43200` menit).
- **Format Header Request:** `Authorization: Bearer {plainTextToken}`.
- **Pencabutan Token (Logout):** Menghapus record token aktif pada tabel `personal_access_tokens` via `$request->user()->currentAccessToken()->delete()`.

---

## 6. Rancangan Role, Permission, dan Pembatasan Wilayah

Backend bertanggung jawab penuh memvalidasi batasan wilayah pada setiap request.

### Matriks Kewenangan Data:
1. **Role: Klinik (Anggota ASKLIN)**
   - Hanya dapat mengakses data klinik miliknya (`anggota.id_user == auth()->user()->id`).
   - Hanya dapat melihat dan mengunduh SK dan Sertifikat klinik miliknya.
   - Dilarang mengakses dashboard pengurus dan data klinik lain.
2. **Role: Admin Cabang (Pengurus Cabang)**
   - Terikat pada kota user (`users.kota`).
   - Query otomatis difilter: `where('id_kota', auth()->user()->kota)`.
   - Hanya dapat melihat data ringkasan dan daftar anggota di kota kewenangannya.
3. **Role: Admin Daerah (Pengurus Daerah)**
   - Terikat pada provinsi user (`users.provinsi`).
   - Query otomatis difilter: `where('id_provinsi', auth()->user()->provinsi)`.
   - Dapat memfilter data antarkabupaten/kota di provinsinya.
4. **Role: Superadmin, Admin Pusat, Sekjen, Ketua Umum, Bendahara (Pengurus Pusat)**
   - Memiliki wewenang nasional (`is_national_admin = true`).
   - Dapat mengakses statistik agregat se-Indonesia serta memfilter provinsi dan kota manapun.

---

## 7. Format Respons JSON Standar

Semua endpoint API wajib mengembalikan struktur JSON terstandarisasi untuk memudahkan deserialisasi model di Dart/Flutter:

### Format Respons Sukses
```json
{
  "success": true,
  "message": "Pesan deskriptif sukses",
  "data": { ... },
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 72
  }
}
```

### Format Respons Gagal / Error
```json
{
  "success": false,
  "message": "Pesan kesalahan yang user-friendly",
  "errors": {
    "email": ["Format email tidak valid."],
    "password": ["Password minimal 8 karakter."]
  }
}
```

---

## 8. Daftar Rinci Endpoint API Berdasarkan Modul

### Modul A: Autentikasi & Akun Pengguna

#### 1. `POST /api/v1/auth/login`
- **Label Status:** **Baru**
- **Tujuan:** Autentikasi email dan password, menerbitkan bearer token Sanctum, serta mengembalikan profil pengguna beserta role dan wilayahnya.
- **Autentikasi:** Publik (`guest`).
- **Parameter Body (JSON):**
  - `email` (string, required, email)
  - `password` (string, required)
  - `device_name` (string, optional, default: "android_mobile")
- **Validasi:** Validasi email dan password tidak boleh kosong.
- **Respons Sukses (HTTP 200):**
  ```json
  {
    "success": true,
    "message": "Login berhasil",
    "data": {
      "token": "1|abcde12345...",
      "user": {
        "id": 15,
        "name": "Klinik Sehat Utama",
        "email": "klinik@asklin.org",
        "role": "Klinik",
        "wilayah": {
          "provinsi_code": "32",
          "kota_code": "3216",
          "provinsi_name": "JAWA BARAT",
          "kota_name": "KABUPATEN BEKASI"
        }
      }
    }
  }
  ```
- **Respons Error:** HTTP 401 (`Kredensial tidak valid`), HTTP 422 (`Validasi input gagal`).

#### 2. `POST /api/v1/auth/logout`
- **Label Status:** **Baru**
- **Tujuan:** Mencabut token akses aktif.
- **Autentikasi:** `auth:sanctum`.
- **Respons Sukses (HTTP 200):**
  ```json
  {
    "success": true,
    "message": "Logout berhasil dan sesi telah diakhiri"
  }
  ```

#### 3. `GET /api/v1/auth/me`
- **Label Status:** **Perlu penyesuaian** (menggantikan default `/api/user`)
- **Tujuan:** Mendapatkan profil lengkap pengguna terautentikasi dan informasi keanggotaan terikat.
- **Autentikasi:** `auth:sanctum`.
- **Respons Sukses (HTTP 200):**
  ```json
  {
    "success": true,
    "message": "Data pengguna berhasil diambil",
    "data": {
      "user": { "id": 15, "name": "Klinik Sehat", "email": "klinik@asklin.org", "role": "Klinik" },
      "anggota": {
        "id": 8,
        "no_anggota": "3171011001-00125.1.1",
        "nama_klinik": "Klinik Sehat Utama",
        "status": "approved",
        "jenis_klinik": "Pratama"
      }
    }
  }
  ```

---

### Modul B: Profil & Keanggotaan (Role Anggota / Klinik)

#### 4. `GET /api/v1/anggota/profil`
- **Label Status:** **Baru**
- **Tujuan:** Mengambil detail lengkap klinik milik pengguna yang sedang login (data umum, alamat, izin operasional, SDM).
- **Autentikasi:** `auth:sanctum`.
- **Permission:** Role `Klinik`.
- **Respons Sukses (HTTP 200):** Detail lengkap objek `Anggota` dengan nama wilayah terhubung.
- **Respons Error:** HTTP 404 (`Profil anggota belum terdaftar`).

#### 5. `GET /api/v1/anggota/status-keanggotaan`
- **Label Status:** **Baru**
- **Tujuan:** Mengambil status tahapan pendaftaran/keanggotaan, histori verifikasi cabang dan pusat, status iuran pembayaran, dan masa berlaku izin.
- **Autentikasi:** `auth:sanctum`.
- **Respons Sukses (HTTP 200):**
  ```json
  {
    "success": true,
    "data": {
      "status": "approved",
      "status_label": "Anggota Resmi Aktif",
      "no_anggota": "3171011001-00125.1.1",
      "verifikasi_cabang": "2024-03-15 10:20:00",
      "verifikasi_pusat": "2024-03-20 14:30:00",
      "status_pembayaran": "1",
      "status_pembayaran_label": "Lunas",
      "tgl_akhir_ijin": "2027-05-10"
    }
  }
  ```

#### 6. `GET /api/v1/anggota/dokumen-saya`
- **Label Status:** **Baru**
- **Tujuan:** Mendapatkan daftar dokumen yang dimiliki oleh klinik (SK Anggota, Sertifikat Anggota, Berkas SIO).
- **Autentikasi:** `auth:sanctum`.
- **Respons Sukses (HTTP 200):**
  ```json
  {
    "success": true,
    "data": [
      {
        "type": "sk_anggota",
        "title": "Surat Keputusan Anggota",
        "nomor": "3171011001-00125.1.1",
        "is_available": true,
        "download_url": "/api/v1/dokumen/sk-anggota/download"
      },
      {
        "type": "sertifikat",
        "title": "Sertifikat Keanggotaan ASKLIN",
        "nomor": "CERT-2024-00125",
        "is_available": true,
        "masa_berlaku_sampai": "2027-03-20",
        "download_url": "/api/v1/dokumen/sertifikat/download"
      }
    ]
  }
  ```

---

### Modul C: Dokumen Digital & Download Aman

#### 7. `GET /api/v1/dokumen/sk-anggota/download`
- **Label Status:** **Perlu penyesuaian**
- **Tujuan:** Mengunduh atau streaming file PDF Surat Keputusan (SK) Anggota klinik yang bersangkutan.
- **Autentikasi:** `auth:sanctum` atau URL dengan temporary signature.
- **Keamanan:** Memverifikasi strictly kepemilikan (`anggota.id_user == auth()->id()`). Menghilangkan kerentanan IDOR.
- **Respons Sukses (HTTP 200):** Binary stream PDF dengan header `Content-Type: application/pdf; Content-Disposition: attachment; filename="sk_anggota_{no}.pdf"`.

#### 8. `GET /api/v1/dokumen/sertifikat/download`
- **Label Status:** **Perlu penyesuaian**
- **Tujuan:** Mengunduh atau streaming file PDF Sertifikat Anggota klinik yang bersangkutan.
- **Autentikasi:** `auth:sanctum`.
- **Keamanan:** Memverifikasi kepemilikan dan status approval lunas (`status_pembayaran == '1'`).
- **Respons Sukses (HTTP 200):** Binary stream PDF `Content-Type: application/pdf`.

---

### Modul D: Dashboard Monitoring Pengurus (Cabang, Daerah, Pusat)

#### 9. `GET /api/v1/pengurus/dashboard`
- **Label Status:** **Baru** (memanfaatkan `LaporanService`)
- **Tujuan:** Mengambil indikator utama (KPI cards) dashboard monitoring sesuai tingkat kepengurusan:
  - Pengurus Cabang: total anggota di kota tersebut, disetujui, menunggu verifikasi, iuran lunas/tertunda.
  - Pengurus Daerah: total anggota di provinsi, agregasi per cabang/kota, status verifikasi daerah.
  - Pengurus Pusat: total nasional, komposisi jenis klinik (Utama/Pratama), sebaran badan hukum.
- **Autentikasi:** `auth:sanctum`.
- **Permission:** Role `Admin Cabang`, `Admin Daerah`, `Admin Pusat`, `Superadmin`, `Sekjen`, `Ketua Umum`, `Bendahara`.
- **Respons Sukses (HTTP 200):**
  ```json
  {
    "success": true,
    "data": {
      "scope": {
        "level": "cabang",
        "wilayah_name": "KABUPATEN BEKASI",
        "is_locked": true
      },
      "stats": {
        "total_klinik": 142,
        "approved": 128,
        "waiting_approval": 10,
        "perlu_perbaikan": 4,
        "iuran_lunas": 120,
        "iuran_tertunda": 22
      },
      "jenis_klinik": { "pratama": 110, "utama": 32 }
    }
  }
  ```

#### 10. `GET /api/v1/pengurus/anggota`
- **Label Status:** **Baru**
- **Tujuan:** Menampilkan daftar anggota klinik di wilayah kewenangan pengurus dengan pencarian, filter status, dan pagination standar REST.
- **Autentikasi:** `auth:sanctum`.
- **Parameter Query:**
  - `page` (integer, optional)
  - `per_page` (integer, optional, default: 15)
  - `search` (string, optional, mencari nama klinik / nomor anggota)
  - `status` (string, optional: approved, waiting, dll)
  - `id_kota` (string, hanya berlaku untuk Daerah dan Pusat)
- **Respons Sukses (HTTP 200):** JSON terpaginasi berisi daftar anggota.

#### 11. `GET /api/v1/pengurus/anggota/{id}`
- **Label Status:** **Baru**
- **Tujuan:** Melihat detail klinik tertentu oleh pengurus.
- **Autentikasi:** `auth:sanctum`.
- **Keamanan:** Memvalidasi bahwa klinik yang diakses berada dalam wilayah wewenang pengurus (`RegionalScoping`). Jika di luar wilayah, return HTTP 403 Forbidden.

---

### Modul E: Data Master & Wilayah

#### 12. `GET /api/v1/wilayah/provinsi`
- **Label Status:** **Baru**
- **Tujuan:** Mendapatkan daftar seluruh provinsi di Indonesia.
- **Autentikasi:** Publik atau `auth:sanctum`.
- **Respons Sukses (HTTP 200):**
  ```json
  {
    "success": true,
    "data": [
      { "code": "11", "name": "ACEH" },
      { "code": "32", "name": "JAWA BARAT" }
    ]
  }
  ```

#### 13. `GET /api/v1/wilayah/kota?provinsi_code={code}`
- **Label Status:** **Baru**
- **Tujuan:** Mendapatkan daftar kabupaten/kota berdasarkan kode provinsi.
- **Autentikasi:** Publik atau `auth:sanctum`.

---

## 9. Penanganan Error, Validasi, Rate Limiting & Keamanan

1. **Standardized Error Handling:**
   - Override `unauthenticated` handler pada `app/Exceptions/Handler.php` agar selalu mengembalikan JSON HTTP 401 saat request meminta header `Accept: application/json`.
   - Mengembalikan HTTP 403 saat terjadi pelanggaran izin atau akses lintas wilayah.
   - Mengembalikan HTTP 422 saat terjadi kegagalan validasi data form.
2. **Rate Limiting:**
   - Terapkan `throttle:60,1` (maksimal 60 request per menit per IP/token) pada group rute API.
   - Endpoint login diterapkan pembatasan ketat `throttle:5,1` (maksimal 5 percobaan login gagal per menit untuk mencegah brute-force credential stuffing).
3. **Mencegah IDOR (Insecure Direct Object Reference):**
   - Menghindari penggunaan parameter `$id` yang dikirim dari mobile untuk menentukan siapa pemilik data.
   - Selalu mengambil konteks kepemilikan dari `Auth::user()`.

---

## 10. Strategi Pengujian & Verifikasi

1. **Feature Tests (PHPUnit):**
   - `AuthApiTest`: Uji login sukses, login gagal, format token, dan pencabutan token.
   - `AnggotaAccessTest`: Uji bahwa user Klinik A tidak dapat membaca atau mengunduh dokumen Klinik B (harus return 403/404).
   - `RegionalIsolationTest`: Uji bahwa Admin Cabang Kab. Bogor tidak dapat membaca data klinik di Kota Bandung.
2. **Postman / Manual API Testing:**
   - Menggunakan Postman Collection terstruktur untuk menguji setiap endpoint sebelum integrasi Flutter dilakukan.

---

## 11. Rencana Pembagian Sesi Implementasi Laravel API

Pekerjaan implementasi backend dibagi menjadi **8 sesi terstruktur dan berurutan**:

---

### Sesi API-1: Persiapan Fondasi API & Standardisasi Response
1. **Nomor & Nama Sesi:** API-1 — Persiapan Fondasi API, Formatter JSON & Exception Handler
2. **Tujuan:** Menyiapkan struktur dasar controller API, middleware response formatter, exception handler JSON, dan konfigurasi Sanctum.
3. **Prasyarat:** Memastikan environment Laravel 9 berjalan normal dan `composer.json` sinkron.
4. **File/Modul yang Diperiksa/Diubah:**
   - `config/sanctum.php`
   - `app/Exceptions/Handler.php`
   - `app/Http/Kernel.php`
5. **Langkah Implementasi Spesifik:**
   - Pastikan middleware `EnsureFrontendRequestsAreStateful` terkonfigurasi dengan benar untuk API jika dibutuhkan, atau gunakan purely bearer token.
   - Buat trait/helper `ApiResponse` untuk standardisasi format JSON (`success`, `message`, `data`, `meta`, `errors`).
   - Sesuaikan `app/Exceptions/Handler.php` untuk merender `AuthenticationException` dan `ValidationException` sebagai respons JSON terstandarisasi ketika request mengharapkan JSON.
6. **File Baru yang Direncanakan:**
   - `app/Http/Controllers/Api/BaseApiController.php`
   - `app/Traits/ApiResponseFormatter.php`
7. **File Existing yang Mungkin Berubah:**
   - `app/Exceptions/Handler.php`
8. **Hal yang Tidak Boleh Diubah:**
   - Rute web eksisting di `routes/web.php`
   - Struktur database MySQL
9. **Risiko & Dependensi:**
   - Penanganan exception tidak boleh mengganggu tampilan halaman error Blade pada website.
10. **Daftar Pengujian:**
    - Request tanpa header token ke endpoint terproteksi mengembalikan JSON HTTP 401 (bukan redirect ke `/login`).
11. **Kriteria Penerimaan Terverifikasi:**
    - Format JSON sukses dan error konsisten 100%.
12. **Output / Dokumentasi:**
    - Base controller dan helper response siap digunakan.
13. **Kondisi Sebelum Lanjut ke Sesi Berikutnya:**
    - Uji coba request dummy menghasilkan output JSON sesuai standar format.

---

### Sesi API-2: Endpoint Autentikasi (Login, Logout, Me)
1. **Nomor & Nama Sesi:** API-2 — Implementasi Autentikasi REST API (Sanctum Token)
2. **Tujuan:** Menyediakan endpoint login kredensial, penerbitan bearer token, pencabutan token (logout), dan pengecekan profil user login.
3. **Prasyarat:** Sesi API-1 selesai.
4. **File/Modul yang Diperiksa/Diubah:**
   - `app/Models/User.php`
   - `routes/api.php`
5. **Langkah Implementasi Spesifik:**
   - Buat `App\Http\Controllers\Api\AuthController`.
   - Implementasikan method `login(Request $request)` dengan validasi `email` dan `password`.
   - Kembalikan token string Sanctum, detail user, role aktif, dan relasi wilayah.
   - Implementasikan method `logout(Request $request)` dan `me(Request $request)`.
   - Daftarkan rute pada `routes/api.php` dengan prefix `v1/auth`.
6. **File Baru yang Direncanakan:**
   - `app/Http/Controllers/Api/AuthController.php`
   - `app/Http/Requests/Api/LoginRequest.php`
7. **File Existing yang Mungkin Berubah:**
   - `routes/api.php`
8. **Hal yang Tidak Boleh Diubah:**
   - `app/Http/Controllers/Auth/LoginController.php` (autentikasi web Blade tetap utuh).
9. **Risiko & Dependensi:**
   - Brute-force login; mitigasi dengan middleware `throttle:5,1`.
10. **Daftar Pengujian:**
    - Login dengan email/password benar menghasilkan HTTP 200 dan token.
    - Login dengan password salah menghasilkan HTTP 401.
    - Logout mencabut token dari tabel `personal_access_tokens`.
11. **Kriteria Penerimaan Terverifikasi:**
    - Token bearer dapat digunakan untuk mengakses endpoint terproteksi `GET /api/v1/auth/me`.
12. **Output / Dokumentasi:**
    - Dokumentasi curl request dan Postman payload untuk modul Auth.
13. **Kondisi Sebelum Lanjut ke Sesi Berikutnya:**
    - Autentikasi token diverifikasi berhasil pada user dengan role Klinik, Admin Cabang, Admin Daerah, dan Pengurus Pusat.

---

### Sesi API-3: Endpoint Data Master & Referensi Wilayah
1. **Nomor & Nama Sesi:** API-3 — API Master Data Wilayah (JSON)
2. **Tujuan:** Menyediakan endpoint JSON untuk daftar provinsi, kabupaten/kota, kecamatan, dan kelurahan.
3. **Prasyarat:** Sesi API-1 dan API-2 selesai.
4. **File/Modul yang Diperiksa/Diubah:**
   - Model `Laravolt\Indonesia\Models\*`
   - `routes/api.php`
5. **Langkah Implementasi Spesifik:**
   - Buat `App\Http\Controllers\Api\WilayahApiController`.
   - Buat method `getProvinces()`, `getCities(Request $request)`, `getDistricts(Request $request)`, `getVillages(Request $request)`.
   - Pastikan data diurutkan berdasarkan abjad (`name ASC`).
6. **File Baru yang Direncanakan:**
   - `app/Http/Controllers/Api/WilayahApiController.php`
7. **File Existing yang Mungkin Berubah:**
   - `routes/api.php`
8. **Hal yang Tidak Boleh Diubah:**
   - `app/Http/Controllers/WilayahController.php` (dipakai oleh form Blade website).
9. **Risiko & Dependensi:**
   - Ukuran data kelurahan cukup besar; wajib menggunakan filter kode parent di query string.
10. **Daftar Pengujian:**
    - Endpoint provinsi mengembalikan array objek kode dan nama.
    - Endpoint kota memfilter kabupaten/kota berdasarkan `provinsi_code`.
11. **Kriteria Penerimaan Terverifikasi:**
    - Data dapat di-fetch cepat (<200ms) dengan format JSON valid.
12. **Output / Dokumentasi:**
    - Endpoint list wilayah siap diintegrasikan Flutter.
13. **Kondisi Sebelum Lanjut ke Sesi Berikutnya:**
    - Semua level wilayah teruji mengembalikan data JSON yang benar.

---

### Sesi API-4: Endpoint Profil & Status Keanggotaan (Anggota)
1. **Nomor & Nama Sesi:** API-4 — Modul Profil & Keanggotaan Klinik
2. **Tujuan:** Menyediakan endpoint profil klinik, status verifikasi keanggotaan, histori pembayaran, dan daftar dokumen milik sendiri.
3. **Prasyarat:** Sesi API-2 selesai.
4. **File/Modul yang Diperiksa/Diubah:**
   - Model `App\Models\Anggota`, `SDM`, `Sertifikat`
   - `routes/api.php`
5. **Langkah Implementasi Spesifik:**
   - Buat `App\Http\Controllers\Api\AnggotaProfileApiController`.
   - Query data klinik secara ketat menggunakan `Anggota::where('id_user', auth()->id())->first()`.
   - Buat API Resource `AnggotaResource` untuk transformasi data yang bersih dan aman.
   - Sediakan informasi status pendaftaran bertingkat (Cabang -> Daerah -> Sekjen -> Approved).
6. **File Baru yang Direncanakan:**
   - `app/Http/Controllers/Api/AnggotaProfileApiController.php`
   - `app/Http/Resources/AnggotaProfileResource.php`
7. **File Existing yang Mungkin Berubah:**
   - `routes/api.php`
8. **Hal yang Tidak Boleh Diubah:**
   - Proses bisnis dan kolom pada tabel `anggota`.
9. **Risiko & Dependensi:**
   - User dengan role non-klinik mengakses endpoint ini; tangani dengan pengecekan ketersediaan data anggota yang anggun (graceful 404/empty state).
10. **Daftar Pengujian:**
    - User login Klinik A hanya mendapatkan profil miliknya sendiri.
    - Kolom tanggal terformat rapi sesuai ISO-8601 / `d-m-Y`.
11. **Kriteria Penerimaan Terverifikasi:**
    - Payload JSON memuat informasi klinik lengkap dengan nama wilayah yang sudah di-join.
12. **Output / Dokumentasi:**
    - Endpoint profil dan status keanggotaan siap pakai.
13. **Kondisi Sebelum Lanjut ke Sesi Berikutnya:**
    - Terverifikasi tidak ada kebocoran data antaranggota.

---

### Sesi API-5: Endpoint Pengunduhan Aman SK & Sertifikat
1. **Nomor & Nama Sesi:** API-5 — Unduh Aman Dokumen SK & Sertifikat Anggota
2. **Tujuan:** Menyediakan endpoint metadata dokumen dan binary stream PDF untuk SK Anggota dan Sertifikat dengan perlindungan IDOR.
3. **Prasyarat:** Sesi API-4 selesai.
4. **File/Modul yang Diperiksa/Diubah:**
   - View `backend.anggota.printsk` dan `frontend.print_sertifikat`
   - `routes/api.php`
5. **Langkah Implementasi Spesifik:**
   - Buat `App\Http\Controllers\Api\DocumentApiController`.
   - Implementasikan pengecekan otorisasi: verifikasi status klinik sudah `approved` dan `status_pembayaran == 1` sebelum mengizinkan render sertifikat.
   - Gunakan `response()->streamDownload(...)` atau tangkap output buffer PDF tanpa memanggil `exit()`.
   - Sediakan header HTTP `Content-Type: application/pdf` yang valid.
6. **File Baru yang Direncanakan:**
   - `app/Http/Controllers/Api/DocumentApiController.php`
   - `app/Services/DocumentGenerationService.php`
7. **File Existing yang Mungkin Berubah:**
   - `routes/api.php`
8. **Hal yang Tidak Boleh Diubah:**
   - Desain visual layout PDF eksisting.
9. **Risiko & Dependensi:**
   - Beban komputasi PDF tinggi; pastikan alokasi memori PHP mencukupi.
10. **Daftar Pengujian:**
    - Anggota yang belum approved ditolak mengunduh SK (HTTP 403).
    - Anggota belum lunas ditolak mengunduh Sertifikat (HTTP 403).
    - File PDF hasil unduhan dapat dibuka tanpa error corrupt di PDF viewer.
11. **Kriteria Penerimaan Terverifikasi:**
    - Berkas SK dan sertifikat terunduh sempurna dengan proteksi hak akses ketat.
12. **Output / Dokumentasi:**
    - Endpoint dokumen siap terintegrasi dengan PDF viewer mobile.
13. **Kondisi Sebelum Lanjut ke Sesi Berikutnya:**
    - Keamanan unduhan dokumen lolos pengujian isolasi IDOR.

---

### Sesi API-6: Endpoint Dashboard Monitoring Pengurus (Cabang, Daerah, Pusat)
1. **Nomor & Nama Sesi:** API-6 — Dashboard Monitoring Pengurus Adaptif Role
2. **Tujuan:** Menyediakan endpoint statistik dan ringkasan monitoring untuk Pengurus Cabang, Pengurus Daerah, dan Pengurus Pusat berbasis `LaporanService`.
3. **Prasyarat:** Sesi API-2 selesai.
4. **File/Modul yang Diperiksa/Diubah:**
   - `App\Services\LaporanService`
   - `routes/api.php`
5. **Langkah Implementasi Spesifik:**
   - Buat `App\Http\Controllers\Api\PengurusDashboardApiController`.
   - Terapkan logika resolusi scope wilayah otomatis:
     - Role Admin Cabang: dikunci ke kota user.
     - Role Admin Daerah: dikunci ke provinsi user.
     - Role Nasional: akses seluruh wilayah.
   - Bungkus hasil `LaporanService::getDashboardStats()` dan chart data ke dalam struktur JSON ringkas.
6. **File Baru yang Direncanakan:**
   - `app/Http/Controllers/Api/PengurusDashboardApiController.php`
7. **File Existing yang Mungkin Berubah:**
   - `routes/api.php`
8. **Hal yang Tidak Boleh Diubah:**
   - Logika perhitungan angka di `LaporanService`.
9. **Risiko & Dependensi:**
   - Admin Cabang mencoba mengirim query parameter `kota` lain; pastikan backend mengabaikan input tersebut dan selalu menggunakan `auth()->user()->kota`.
10. **Daftar Pengujian:**
    - Admin Cabang Bekasi hanya mendapatkan statistik Kab. Bekasi.
    - Admin Daerah Jabar hanya mendapatkan statistik Provinsi Jawa Barat.
    - Pengurus Pusat mendapatkan total nasional.
11. **Kriteria Penerimaan Terverifikasi:**
    - Indikator statistik sesuai 100% dengan data yang tampil di dashboard web.
12. **Output / Dokumentasi:**
    - Endpoint monitoring siap untuk dashboard mobile.
13. **Kondisi Sebelum Lanjut ke Sesi Berikutnya:**
    - Verifikasi batas wilayah terbukti solid tanpa celah kebocoran antarwilayah.

---

### Sesi API-7: Endpoint Daftar & Detail Anggota untuk Pengurus
1. **Nomor & Nama Sesi:** API-7 — Daftar Anggota & Detail untuk Pengurus dengan Pagination REST
2. **Tujuan:** Menyediakan endpoint penelusuran data klinik bagi pengurus sesuai wilayah kewenangan.
3. **Prasyarat:** Sesi API-6 selesai.
4. **File/Modul yang Diperiksa/Diubah:**
   - `App\Models\Anggota`
   - `routes/api.php`
5. **Langkah Implementasi Spesifik:**
   - Buat method `getAnggotaList(Request $request)` dan `getAnggotaDetail($id)`.
   - Terapkan pencarian (`keyword` nama klinik/nomor anggota), filter status, dan sorting.
   - Terapkan pagination standar Laravel (`paginate($perPage)`).
   - Validasi bahwa `$id` klinik yang diakses berada dalam cakupan wilayah pengurus yang login.
6. **File Baru yang Direncanakan:**
   - `app/Http/Resources/PengurusAnggotaListResource.php`
7. **File Existing yang Mungkin Berubah:**
   - `app/Http/Controllers/Api/PengurusDashboardApiController.php`
   - `routes/api.php`
8. **Hal yang Tidak Boleh Diubah:**
   - Query builder di website Blade DataTables.
9. **Risiko & Dependensi:**
   - Query N+1 pada data fasilitas klinik; gunakan eager loading `with(...)` atau join efisien.
10. **Daftar Pengujian:**
    - Pengujian pencarian teks parsial nama klinik.
    - Pengujian pagination page 1, 2, dst.
    - Akses detail klinik luar wilayah menghasilkan HTTP 403 Forbidden.
11. **Kriteria Penerimaan Terverifikasi:**
    - Respons JSON menyertakan meta pagination lengkap (`current_page`, `total`, `per_page`).
12. **Output / Dokumentasi:**
    - Modul list anggota pengurus siap digunakan Flutter.
13. **Kondisi Sebelum Lanjut ke Sesi Berikutnya:**
    - Seluruh endpoint pengurus teruji aman dari akses lintas wilayah.

---

### Sesi API-8: Hardening Keamanan, Automated Tests & Dokumentasi Kontrak API Final
1. **Nomor & Nama Sesi:** API-8 — Hardening, Automated Testing & Finalisasi Kontrak API
2. **Tujuan:** Melakukan pengujian integrasi otomatis (PHPUnit Feature Tests), verifikasi isolasi data, dan finalisasi spesifikasi kontrak API sebelum Flutter dimulai.
3. **Prasyarat:** Sesi API-1 sampai API-7 selesai.
4. **File/Modul yang Diperiksa/Diubah:**
   - `tests/Feature/Api/*`
   - Dokumentasi blueprint API
5. **Langkah Implementasi Spesifik:**
   - Buat rangkaian automated tests:
     - `AuthApiTest.php`
     - `AnggotaProfileApiTest.php`
     - `DocumentSecurityApiTest.php`
     - `RegionalScopeSecurityApiTest.php`
   - Jalankan seluruh suite pengujian via `php artisan test`.
   - Pastikan tidak ada regresi pada website eksisting.
6. **File Baru yang Direncanakan:**
   - `tests/Feature/Api/AuthApiTest.php`
   - `tests/Feature/Api/AnggotaApiTest.php`
   - `tests/Feature/Api/PengurusApiTest.php`
7. **File Existing yang Mungkin Berubah:**
   - Dokumentasi kontrak API
8. **Hal yang Tidak Boleh Diubah:**
   - Konfigurasi server live production.
9. **Risiko & Dependensi:**
   - Kegagalan test akibat database fixture; gunakan database testing khusus atau transaksi rollback.
10. **Daftar Pengujian:**
    - Seluruh unit & feature test API berstatus PASSED (hijau).
11. **Kriteria Penerimaan Terverifikasi:**
    - Seluruh skenario IDOR dan cross-region access berhasil dicegah dengan status HTTP 403.
12. **Output / Dokumentasi:**
    - Laporan hasil pengujian API dan Postman Collection lengkap.
13. **Kondisi Sebelum Lanjut ke Sesi Berikutnya (Tahap Flutter):**
    - Kontrak API dinyatakan final dan backend siap melayani request Flutter.

