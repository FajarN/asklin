# Blueprint Perombakan Arsitektur & Modernisasi UI ASKLIN

**Tanggal Blueprint:** 8 Oktober 2026  
**Dokumen Pendamping:** `/docs/AUDIT-KEAMANAN.md`  
**Status:** DRAFT PROPOSAL (Menunggu Persetujuan)  
**Target Utama:** Modernisasi total antarmuka (Stisla -> Tailwind CSS), pengetatan keamanan sistem, refaktorisasi controller dan query, serta persiapan upgrade terencana dari Laravel 9.48.0 ke Laravel 11.x **tanpa mengubah skema database production**.

---

## 1. Ringkasan Eksekutif & Prinsip Desain

Aplikasi Web Organisasi ASKLIN (Asosiasi Klinik Indonesia) adalah sistem aktif (*live production*) yang menangani ribuan anggota klinik, proses registrasi berjenjang (Cabang -> Daerah -> Pusat), verifikasi bendahara, penomoran sertifikat, administrasi surat menyurat, dan event nasional (Konas).

### Prinsip Utama Perombakan:
1. **Zero Database Mutation:** Database production adalah *single source of truth* yang **TIDAK BOLEH** diubah strukturnya. Dilarang menjalankan migration skema baru, `ALTER TABLE`, rename/drop column, seeder, truncate, maupun query destruktif.
2. **100% Laravel Blade Preservation:** Seluruh frontend tetap menggunakan Laravel Blade murni. **TIDAK** bermigrasi ke Vue, React, Inertia, Livewire, atau Single Page Application (SPA).
3. **Pembersihan Celah Keamanan Sebelum Redesign:** Seluruh temuan kritis (IDOR, RCE file upload, kredensial git) diselesaikan terlebih dahulu agar pondasi backend kokoh dan aman sebelum antarmuka baru diluncurkan.
4. **Tahapan Upgrade Bertingkat (Two-Hop Upgrade):** Karena aplikasi saat ini teridentifikasi berada di **Laravel 9.48.0**, proses upgrade harus melalui:
   `Laravel 9.48.0` &rarr; `Laravel 10.x` &rarr; `Laravel 11.x`.
5. **Modern Design System:** Mengganti template monolitik Stisla (Bootstrap 4) dengan Tailwind CSS kontemporer yang mendukung Light Mode, Dark Mode, responsif mobile/tablet/desktop, serta komponen Blade berbasis UI reusable.
6. **Eksekusi Bertahap Berbasis Sesi (Session-Based Execution):** Setiap pekerjaan dibagi menjadi sesi-sesi terisolasi dengan batasan berkas (*allowed files*), acceptance criteria, pengujian ketat, dan commit Git lokal tanpa `git push`.

---

## 2. Rencana Upgrade Framework: Laravel 9.48 &rarr; 10 &rarr; 11

### 2.1. Temuan Penting & Prasyarat
- **Versi Eksisting:** Laravel Framework **9.48.0** (`composer.json`: `"laravel/framework": "^9.19"`).
- **PHP CLI Aktif:** PHP **8.3.21** (Telah memenuhi prasyarat minimal Laravel 11 yaitu PHP >= 8.2).
- **Strategi Upgrade:** Tidak boleh langsung melompati Laravel 9 ke 11. Upgrade wajib dilakukan bertahap (Hop 1: Laravel 9 &rarr; 10; Hop 2: Laravel 10 &rarr; 11) untuk menjaga kompatibilitas konfigurasi dan migrasi pustaka pihak ketiga.

### 2.2. Roadmap Dependensi Composer

| Paket | Versi Saat Ini | Target Laravel 10 | Target Laravel 11 | Catatan / Rencana Solusi |
|---|---|---|---|---|
| `laravel/framework` | `^9.19` (9.48.0) | `^10.0` | `^11.0` | Upgrade bertahap via composer require |
| `php` | `^8.0.2` | `^8.1` | `^8.2` | Ubah constraint di `composer.json` |
| `laravelcollective/html` | `^6.3` | `^6.4` | **REPLACED** | **ABANDONED**. Ganti ke sintaks native Blade/Tailwind atau `spatie/laravel-html:^3.0` |
| `spatie/laravel-permission`| `^5.8` | `^5.10` / `^6.0` | `^6.0` | Upgrade ke v6 (kompatibel penuh Laravel 11) |
| `yajra/laravel-datatables` | `9.0` | `^10.0` | `^11.0` | Upgrade paket DataTables |
| `barryvdh/laravel-dompdf` | `^3.1` | `^3.1` | `^3.1` | Kompatibel, update patch keamanan |
| `spipu/html2pdf` | `^5.2` | `^5.2` | `^5.2` | Kompatibel PHP 8.2+ |
| `spatie/simple-excel` | `^3.7` | `^3.7` | `^3.7` | Kompatibel penuh |
| `laravolt/indonesia` | `^0.33.0` | `^0.33.0` | `^0.34.0` / audit | Periksa kompatibilitas model wilayah |
| `qcod/laravel-app-settings` | `^1.5` | `^1.5` | `^1.5` / review | Pastikan route provider kompatibel |
| `nunomaduro/collision` (dev) | `^6.1` | `^7.0` | `^8.1` | Dev tool update |
| `spatie/laravel-ignition` (dev)| `^1.0` | `^2.0` | `^2.4` | Error handler update |
| `phpunit/phpunit` (dev) | `^9.5.10` | `^10.0` | `^10.5` | Test suite update |

### 2.3. Penyesuaian Arsitektur Laravel 11
Pada Laravel 11, struktur aplikasi disederhanakan (*slim skeleton*):
- `app/Http/Kernel.php` dan `app/Console/Kernel.php` opsional, dipindahkan ke `bootstrap/app.php` menggunakan callback `withMiddleware()` dan `withExceptions()`.
- Karena aplikasi existing masih menggunakan struktur standar (`Kernel.php`), Laravel 11 tetap mendukung arsitektur *legacy skeleton*, sehingga transisi dapat dilakukan tanpa perlu merombak ulang struktur direktori inti.

---

## 3. Sistem Desain Baru (Tailwind CSS UI Kit)

Menggantikan Stisla (Bootstrap 4 + jQuery) dengan arsitektur UI modern berbasis utility classes Tailwind CSS.

### 3.1. Design Tokens & Visual Language
- **Color Palette (Modern Professional Healthcare):**
  - **Primary (ASKLIN Emerald / Teal):**
    - Light: `emerald-600` (`#059669`) / Hover: `emerald-700` (`#047857`)
    - Dark: `emerald-500` (`#10b981`)
  - **Neutral / Background:**
    - Light Mode: `bg-slate-50` (`#f8fafc`), card: `bg-white`, border: `border-slate-200`
    - Dark Mode: `bg-slate-950` (`#020617`), card: `bg-slate-900`, border: `border-slate-800`
  - **Text Typography:**
    - Font Family: Inter, Plus Jakarta Sans, atau Geist Sans (sans-serif)
    - Heading: `font-semibold text-slate-900 dark:text-slate-100`
    - Body: `text-sm text-slate-600 dark:text-slate-400`
  - **Status Accents:**
    - Success (Disetujui): `emerald-500` / `bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300`
    - Warning (Waiting / Perlu Perbaikan): `amber-500` / `bg-amber-50 text-amber-700`
    - Danger (Ditolak / Belum Lengkap): `rose-500` / `bg-rose-50 text-rose-700`
    - Info (Verifikasi Cabang): `sky-500` / `bg-sky-50 text-sky-700`

### 3.2. Komponen UI Reusable (Blade Components)
Seluruh elemen tampilan akan distandarisasi ke dalam folder `resources/views/components/`:
1. `<x-card>`: Container konten fleksibel dengan slot header, body, footer, dan border halus.
2. `<x-stat-card>`: Kartu KPI statistik dengan icon badge, judul, counter besar, dan indikator tren.
3. `<x-button>`: Tombol dengan varian (primary, secondary, danger, outline) dan loading spinner state.
4. `<x-badge>`: Pill badge status verifikasi dan pembayaran.
5. `<x-modal>`: Modal dialog responsif berbasis Alpine.js / Vanilla JS tanpa dependensi jQuery.
6. `<x-table>`: Tabel data dengan sticky header, hover rows, striped styling, dan empty state.
7. `<x-form.input>`, `<x-form.select>`, `<x-form.textarea>`: Form controls dengan label, error state, dan helper text.
8. `<x-alert>`: Banner notifikasi sukses/peringatan/error.
9. `<x-empty-state>`: Ilustrasi dan pesan ramah saat data kosong.
10. `<x-loading-skeleton>`: Placeholder skeleton saat AJAX memuat data.

### 3.3. Dukungan Dark Mode
- Mode diaktifkan menggunakan class `.dark` pada tag `<html>`.
- State tersimpan di `localStorage.theme` (`'light'` / `'dark'` / `'system'`).
- Script inline ultra-ringan di `<head>` untuk mencegah *flash of unstyled content* (FOUC).
- Switch toggle terletak di navbar kanan atas berdampingan dengan profil user.

---

## 4. Rencana Restrukturisasi Blade Views

Struktur folder Blade di `resources/views/` akan dirapikan secara modular tanpa mengubah URL route:

```
resources/views/
├── layouts/
│   ├── app.blade.php                 <-- Master layout backend baru (Tailwind)
│   ├── frontend.blade.php            <-- Master layout publik
│   └── partials/
│       ├── sidebar.blade.php         <-- Sidebar navigasi modern dengan collapsible menus
│       ├── navbar.blade.php          <-- Top navbar dengan breadcrumb, dark mode toggle, user menu
│       └── footer.blade.php          <-- Footer copyright ringkas
├── components/                       <-- Kumpulan Blade components mandiri
│   ├── button.blade.php
│   ├── card.blade.php
│   ├── stat-card.blade.php
│   ├── badge.blade.php
│   ├── modal.blade.php
│   ├── alert.blade.php
│   └── form/
├── backend/
│   ├── dashboard/                    <-- Redesign dashboard modular
│   │   ├── index.blade.php
│   │   └── partials/
│   │       ├── stats-overview.blade.php
│   │       ├── regional-chart.blade.php
│   │       └── recent-activities.blade.php
│   ├── verifikasi_anggota/
│   ├── anggota/
│   ├── pembayaran/
│   ├── laporan/
│   ├── konas/
│   └── ruang_pengurus/
└── auth/                             <-- Halaman login & reset password modern
```

---

## 5. Rencana Redesign Dashboard

### 5.1. Analisis Metrik Existing
Berdasarkan investigasi `DashboardController.php` dan `dashboard.blade.php`, metrik utama dashboard terbagi menjadi:

1. **KPI Status Registrasi (8 Kartu Utama):**
   - **Proses Input** (`$proses`: status `'proses'`)
   - **Waiting** (`$waiting`: status `'waiting'`)
   - **Perlu Perbaikan** (`$perbaikan`: status `'Perlu Perbaikan'`)
   - **Belum Lengkap** (`$create_dokter`: status `'create_dokter'`)
   - **Verifikasi Cabang** (`$diverifikasi_cabang`: status `'Diverifikasi Cabang'`)
   - **Terverifikasi Cabang** (`$terverifikasi_cabang`: status `'Terverifikasi Cabang'`)
   - **Disetujui Pusat** (`$approved`: status `'approved'`)
   - **Ditolak Pusat** (`$ditolak`: status `'ditolak pusat'`)

2. **Visualisasi Distribusi & Filter Wilayah:**
   - Filter bertingkat: Dropdown Provinsi & Dropdown Kabupaten/Kota.
   - Bar Chart Wilayah (`getStatistikWilayah`): Menampilkan perbandingan jumlah klinik antar-provinsi atau antar-kota.

3. **Statistik Demografis Klinik:**
   - Donut Chart Jenis Klinik (`getStatistikJenisKlinik`): Pratama vs Utama.
   - Bar/Pie Chart Badan Hukum (`getStatistikBadanHukum`): PT, CV, Yayasan, Koperasi.
   - Progress Bar Status Kepemilikan (`$perKepemilikan`): Kelompok kepemilikan klinik.

4. **Feed Aktivitas Terkini (Recent Activities):**
   - 5 data klinik terakhir yang diupdate dengan timestamp relative (`diffForHumans()`).

### 5.2. Optimalisasi Backend Query Dashboard
Saat ini controller menjalankan 10+ query terpisah. Pada desain baru, seluruh count dikonsolidasikan menjadi **1 query agregat efisien**:
```php
// Refactor: Menggantikan 10 query individual dengan 1 query agregat
$statusCounts = Anggota::selectRaw("
    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
    SUM(CASE WHEN status = 'proses' THEN 1 ELSE 0 END) as proses,
    SUM(CASE WHEN status = 'ditolak pusat' THEN 1 ELSE 0 END) as ditolak,
    SUM(CASE WHEN status = 'Perlu Perbaikan' THEN 1 ELSE 0 END) as perbaikan,
    SUM(CASE WHEN status = 'Diverifikasi Cabang' THEN 1 ELSE 0 END) as diverifikasi_cabang,
    SUM(CASE WHEN status = 'Terverifikasi Cabang' THEN 1 ELSE 0 END) as terverifikasi_cabang,
    SUM(CASE WHEN status = 'waiting' THEN 1 ELSE 0 END) as waiting,
    SUM(CASE WHEN status = 'create_dokter' THEN 1 ELSE 0 END) as create_dokter
")
->when(Auth::user()->hasRole('Admin Cabang'), fn($q) => $q->where('id_kota', Auth::user()->kota))
->when(Auth::user()->hasRole('Admin Daerah'), fn($q) => $q->where('id_provinsi', Auth::user()->provinsi))
->first();
```
*Dampak:* Mengurangi 90% waktu eksekusi database pada pembukaan halaman awal.

### 5.3. Tata Letak Dashboard Baru
- **Header:** Sambutan dinamis sesuai nama dan role pengguna, badge wilayah (Cabang/Daerah/Pusat), serta quick button ke Verifikasi Anggota.
- **Top Row (KPI Badges):** 8 kartu metrik dirancang dengan layout grid responsif (4 kolom desktop, 2 kolom tablet, 1 kolom mobile), dilengkapi warna status yang intuitif.
- **Middle Row (Interactive Analytics):**
  - Kolom Kiri (70%): Bar Chart Sebaran Wilayah dengan filter Provinsi dan Kota langsung di header card.
  - Kolom Kanan (30%): Feed Aktivitas Verifikasi Terkini.
- **Bottom Row (Demografis):** 3 kartu berdampingan untuk Jenis Klinik, Badan Hukum, dan Kepemilikan.

---

## 6. Pemetaan & Modernisasi Menu Navigasi (Sidebar)

Berikut hasil audit seluruh menu eksisting di `resources/views/layouts/backend/menu.blade.php` beserta target perbaruannya:

| Menu Lama | Fungsi & Hak Akses | Kondisi Saat Ini | Target UI Baru | Catatan Perubahan |
|---|---|---|---|---|
| **Dashboard** | Halaman metrik utama (Semua Role) | Desain Stisla statis | `<x-nav-link>` dengan icon `home` modern dan active pill | Dioptimasi query agregat |
| **Master Data &rarr; Fasilitas** | Kelola master fasilitas umum (`Superadmin`, `Admin Pusat`) | Form modal Stisla lama | Table Tailwind bersih + slide-over drawer modal | Mempertahankan CRUD |
| **Master Data &rarr; Fasilitas Klinik** | Master kriteria fasilitas klinik | Form modal Stisla lama | Table Tailwind + drawer edit | Mempertahankan CRUD |
| **Master Data &rarr; Layanan** | Master layanan kesehatan klinik | Form modal Stisla lama | Table Tailwind + drawer edit | Mempertahankan CRUD |
| **Master Data &rarr; Kategori Berita** | Kategori konten portal berita | Form modal Stisla lama | Table Tailwind ringkas | Mempertahankan CRUD |
| **Master Data &rarr; Kategori Event** | Kategori event ASKLIN | Form modal Stisla lama | Table Tailwind ringkas | Mempertahankan CRUD |
| **Master Data &rarr; Kategori Pembayaran**| Pengaturan tarif iuran/pangkal | Form modal Stisla lama | Table Tailwind dengan format rupiah | Mempertahankan CRUD |
| **Module Konas &rarr; Hotel** | Master hotel kegiatan Konas | Desain lama, form upload | Table Tailwind + badge bintang | Mempertahankan CRUD |
| **Module Konas &rarr; Tipe Hotel** | Master harga kamar hotel | Desain lama | Table Tailwind | Mempertahankan CRUD |
| **Module Konas &rarr; Peserta Konas**| Data registrasi peserta Konas | DataTables BS4 lama | DataTables Tailwind + badge lunas | Amankan webhook callback |
| **Module Konas &rarr; Booking Hotel**| Reservasi kamar peserta | DataTables BS4 lama | DataTables Tailwind | Mempertahankan relasi |
| **Module Konas &rarr; Data Penerbangan**| Manifest jadwal kedatangan bandara | DataTables BS4 lama | DataTables Tailwind | Mempertahankan workflow |
| **Module Konas &rarr; Partner** | Sponsor & mitra kegiatan Konas | Grid kartu lama | Grid kartu Tailwind modern | Mempertahankan CRUD |
| **Ruang Verifikasi &rarr; Verifikasi Anggota**| Antrian verifikasi berjenjang Cabang/Daerah/Pusat | DataTables BS4, filter usia data | DataTables Tailwind + filter chip + drawer detail | Pasang permission check ketat |
| **Data Terverifikasi &rarr; Data Anggota**| Direktori klinik yang disetujui | DataTables BS4, cetak SK/sertifikat | DataTables Tailwind + dropdown aksi cepat | Cegah IDOR cetak SK |
| **Data Terverifikasi &rarr; Asuransi Anggota**| Rekap asuransi rekanan klinik | Table agregat | Table Tailwind dengan badge pill | Mempertahankan relasi |
| **Data Terverifikasi &rarr; Expired SIO**| Monitoring izin operasional habis | Table dengan badge tanggal | Table Tailwind + indikator warna urgensi | Alert reminder SIO |
| **Ruang Administrasi &rarr; Agenda Kerja**| Jadwal kerja pengurus ASKLIN | DataTables BS4 lama | Timeline/Card view modern | Mempertahankan CRUD |
| **Ruang Administrasi &rarr; Create Sertifikat**| Penerbitan sertifikat keanggotaan | Form cetak | Form Tailwind + PDF preview | Mempertahankan format penomoran |
| **Ruang Administrasi &rarr; Expired Sertifikat**| Monitoring masa berlaku sertifikat | Table monitoring | Table Tailwind + peringatan masa habis | Mempertahankan filter tanggal |
| **Pembayaran Iuran &rarr; Verif Uang Pangkal**| Verifikasi mutasi uang pangkal | Table DataTables | Table Tailwind + preview bukti bayar | Proteksi role Bendahara |
| **Pembayaran Iuran &rarr; Verif Iuran** | Verifikasi iuran bulanan/tahunan | Table DataTables | Table Tailwind + status switch | Proteksi role Bendahara |
| **Pembayaran Iuran &rarr; Bukti Bayar Daerah/Pusat**| Upload bukti setor kas wilayah | Table DataTables | Table Tailwind + preview modal | Mempertahankan upload bukti |
| **Ruang Pengurus &rarr; Struktur Organisasi**| Bagan susunan kepengurusan | View lama | Organogram tree / card view modern | Mempertahankan tingkatan |
| **Ruang Pengurus &rarr; Surat (5 Submenu)**| Jenis, Penomoran, Surat Keluar, Tugas, Undangan | Multi-step form & DataTables | Sub-grup navigasi rapi + editor PDF print preview | Mempertahankan counter nomor otomatis |
| **Laporan &rarr; Laporan Pusat**| Ekspor komprehensif data nasional | Custom column selector, Html2Pdf | Modern interactive column picker + streaming export | Perbaiki memory exhaustion |
| **Laporan &rarr; Laporan Daerah**| Laporan wilayah provinsi | **CRASH (View tidak ada)** | DataTables Tailwind terintegrasi | **Perbaiki bug nama view** |
| **Laporan &rarr; Laporan Cabang**| Laporan wilayah kota/kabupaten | **CRASH (View tidak ada)** | DataTables Tailwind terintegrasi | **Perbaiki bug nama view** |
| **Content Website (5 Submenu)**| Slider, Event, Berita, Gallery, Kontak | Form CRUD media website | Card grid view + drag/drop uploader terproteksi | Validasi berkas upload |
| **Pengaturan &rarr; User, Group, Permission, Setting**| Manajemen RBAC & pengaturan umum | DataTables BS4 & app-settings | Interface keamanan tinggi berbasis Tailwind | Kunci strict `Superadmin` |

---

## 7. Refaktorisasi Controller & Query

Prinsip refaktor: Tidak melakukan perombakan arsitektural masif (over-engineering), melainkan fokus pada **Correctness, Security, Maintainability, dan Performance**:
1. **Server-side DataTables:** Menghapus `->get()` sebelum `DataTables::of()`. Seluruh query diserahkan ke Query Builder agar database MySQL yang melakukan pagination (`LIMIT`, `OFFSET`) dan searching (`WHERE LIKE`).
2. **Chunked Streaming Exports:** Pada ekspor Excel dan PDF (`LaporanPusatController`), gunakan `SimpleExcelWriter` dengan cursor/chunk stream alih-alih me-load seluruh baris ke RAM sekaligus.
3. **Form Request Validation:** Memindahkan validasi dari controller ke Form Request classes (`StoreSDMRequest`, `UpdateProfileRequest`, `UpdateAnggotaRequest`) guna memastikan validasi MIME dan ukuran file berlaku konsisten.
4. **Ownership Scoping:** Menyematkan local scope pada model atau query builder (`where('id_klinik', $userClinicId)`) untuk membasmi celah IDOR di setiap endpoint.

---

## 8. Protokol Keamanan Database Production (Frozen Database)

> [!CAUTION]
> **ATURAN MUTLAK:** Database production telah digunakan secara aktif dan strukturnya TIDAK BOLEH diubah sedikit pun.

### Ketentuan Wajib:
- **DILARANG:** Menjalankan `php artisan migrate`, `php artisan migrate:fresh`, `php artisan db:wipe`, `php artisan db:seed`.
- **DILARANG:** Menambah kolom, mengubah tipe data, mengganti nama kolom, atau menghapus tabel.
- **DILARANG:** Melakukan truncate atau penghapusan data massal.
- **DILARANG:** Menyesuaikan skema database hanya untuk mengakomodasi fitur tampilan baru.

### Kebijakan "DATABASE CHANGE REQUIRED":
Apabila selama proses ditemukan bahwa sebuah fitur baru idealnya membutuhkan penyesuaian kolom di database:
1. **TIDAK BOLEH** langsung mengimplementasikannya.
2. Catat dan laporkan ke dokumen dengan format khusus:
   ```markdown
   ### DATABASE CHANGE REQUIRED: [Nama Kebutuhan]
   - Alasan Kebutuhan: ...
   - Tabel Terdampak: ...
   - Kolom yang Diusulkan: ...
   - Dampak Terhadap Sistem: ...
   - Solusi Alternatif Tanpa Mengubah Database: ...
   ```
3. Implementasi pada fitur tersebut **WAJIB BERHENTI** sampai persetujuan eksplisit diberikan oleh pemilik sistem.

---

## 9. Rencana Eksekusi Berbasis Sesi (Session Breakdown)

Mengingat batasan waktu eksekusi lingkungan dan risiko tinggi pada sistem yang sedang berjalan, pekerjaan dibagi menjadi **21 sesi terisolasi**:

---

### SESSION 0 — Audit Menyeluruh & Penyusunan Blueprint (SELESAI)
- **Objective:** Audit komprehensif keamanan, kualitas kode, performa, dependensi, dan pembuatan dokumen blueprint roadmap.
- **Scope:** Pemeriksaan statis seluruh direktori codebase, verifikasi runtime framework, verifikasi dependensi composer/npm, dan pembuatan dokumen panduan.
- **Files Allowed:** `/docs/AUDIT-KEAMANAN.md`, `/docs/BLUEPRINT-PEROMBAKAN.md`.
- **Files Forbidden:** Seluruh berkas source code di luar `/docs/`.
- **Expected Result:** Dokumen audit dan blueprint komprehensif selesai dan dicommit secara lokal.
- **Rollback:** Menghapus berkas `/docs/`.

---

### SESSION 1 — Security Quick Wins & Remediasi Celah Kritis
- **Objective:** Menutup celah keamanan paling kritis (IDOR, RCE upload, kebocoran kredensial) tanpa menyentuh UI atau database.
- **Scope:**
  - Keluarkan `.env-live` dari Git tracking dan perbarui `.gitignore`.
  - Tutup IDOR pada `ProfileController::update` (kunci hanya ke `Auth::user()`).
  - Tutup IDOR pada `HomeController::updateAnggota` (kunci `where('id_user', Auth::id())`).
  - Pasang validasi file upload ketat (ekstensi & MIME) pada fungsi-fungsi pendaftaran SDM di `HomeController`.
  - Pasang verifikasi autentikasi/signature pada callback `/konas/callback`.
  - Amankan API Anggota: matikan hardcoded API key di `AnggotaApiController`.
  - Perbaiki bug crash view di `LaporanCabangController` & `LaporanDaerahController`.
  - Perbaiki import class `Regency` di `DashboardController`.
- **Files Allowed:**
  - `.gitignore`
  - `app/Http/Controllers/Backend/ProfileController.php`
  - `app/Http/Controllers/Frontend/HomeController.php`
  - `app/Http/Controllers/Frontend/KonasController.php`
  - `app/Http/Controllers/Api/AnggotaApiController.php`
  - `app/Http/Controllers/Backend/DashboardController.php`
  - `app/Http/Controllers/Backend/laporan/LaporanCabangController.php`
  - `app/Http/Controllers/Backend/laporan/LaporanDaerahController.php`
  - `app/Models/Anggota.php` (null check pada booted)
- **Files Forbidden:** Skema database, migrations, frontend view redesign.
- **Dependency:** Session 0 disetujui.
- **Testing:**
  - [ ] Validasi syntax PHP (`php -l`).
  - [ ] Pengujian manual update profil (pastikan user tidak bisa mengubah profil ID lain).
  - [ ] Pengujian update anggota (pastikan kepemilikan klinik tidak dapat direbut).
  - [ ] Pengujian upload berkas (pastikan file `.php` ditolak keras dengan pesan error validasi).
  - [ ] Cek rute laporan cabang dan daerah (pastikan tidak terjadi View Not Found).
- **Risiko:** Gangguan pada alur pendaftaran jika validasi upload terlalu restriktif.
- **Rollback:** `git checkout` pada controller terkait.

---

### SESSION 2 — Persiapan & Upgrade Hop 1: Laravel 9.48 &rarr; Laravel 10.x
- **Objective:** Mengangkat versi framework dari Laravel 9.48.0 ke versi LTS stabil Laravel 10.x.
- **Scope:**
  - Penyesuaian `composer.json` untuk Laravel 10 (`"laravel/framework": "^10.0"`, `"php": "^8.1"`).
  - Penyesuaian dev dependencies (`collision: ^7.0`, `ignition: ^2.0`, `phpunit: ^10.0`).
  - Update `yajra/laravel-datatables` ke v10.
  - Penyesuaian method/class deprecation pada Laravel 10.
  - Menjalankan `composer update` terarah.
- **Files Allowed:** `composer.json`, `composer.lock`.
- **Files Forbidden:** Seluruh controllers bisnis, database, views.
- **Expected Result:** Aplikasi boot dengan sukses pada Laravel 10.x (`php artisan --version` menampilkan Laravel 10).
- **Testing:**
  - [ ] `php artisan --version` -> Laravel 10.x.
  - [ ] `php artisan route:list` berjalan lancar tanpa exception.
  - [ ] Halaman login, dashboard, dan pendaftaran boot normal.
- **Risiko:** Ketidakcocokan paket pihak ketiga.
- **Rollback:** Kembalikan `composer.json` dan `composer.lock` via Git.

---

### SESSION 3 — Upgrade Hop 2: Laravel 10.x &rarr; Laravel 11.x
- **Objective:** Menyelesaikan upgrade ke target framework akhir (Laravel 11.x).
- **Scope:**
  - Migrasi `laravelcollective/html` (ganti dengan native Blade atau `spatie/laravel-html`).
  - Update `spatie/laravel-permission` ke v6.x.
  - Update `laravel/framework` ke `^11.0`, PHP requirement `^8.2`.
  - Penyesuaian `yajra/laravel-datatables` ke v11.
  - Penyesuaian service provider dan boot loaders.
- **Files Allowed:** `composer.json`, `composer.lock`, Blade views yang memuat form helper Collective.
- **Files Forbidden:** Skema database.
- **Expected Result:** `php artisan --version` menampilkan Laravel 11.x dan aplikasi berjalan stabil.
- **Testing:**
  - [ ] Boot test, route check, permission test.
  - [ ] Test form register/login.
- **Risiko:** Perubahan internal framework pada service provider.
- **Rollback:** `git checkout` branch sebelumnya.

---

### SESSION 4 — Setup Tailwind CSS, Vite & Design System Tokens
- **Objective:** Mengintegrasikan Tailwind CSS 3.x ke dalam asset pipeline Vite dan membangun fondasi design system.
- **Scope:**
  - Instalasi Tailwind CSS, PostCSS, Autoprefixer, `@tailwindcss/forms`, `@tailwindcss/typography`.
  - Konfigurasi `tailwind.config.js` (warna tema ASKLIN, dark mode `class`, font family).
  - Konfigurasi `vite.config.js` dan entry CSS `resources/css/app.css`.
  - Pembuatan script dark mode loader tanpa FOUC.
- **Files Allowed:** `package.json`, `package-lock.json`, `tailwind.config.js`, `postcss.config.js`, `vite.config.js`, `resources/css/`.
- **Files Forbidden:** Controller, database, existing Blade backend (agar Stisla tetap berjalan paralel).
- **Expected Result:** `npm run build` sukses menghasilkan CSS Tailwind terkompilasi.
- **Testing:**
  - [ ] Asset build sukses tanpa error.
  - [ ] Uji coba class Tailwind pada view dummy.
- **Risiko:** Konflik style CSS jika Tailwind dimasukkan langsung ke layout Stisla.
- **Rollback:** Revert berkas konfigurasi frontend.

---

### SESSION 5 — Fondasi Blade Components & Master Layout Backend Baru
- **Objective:** Membangun master layout baru (`layouts.app`) dan library komponen Blade inti.
- **Scope:**
  - Pembuatan master layout `resources/views/layouts/app.blade.php`.
  - Pembuatan komponen: `<x-card>`, `<x-button>`, `<x-badge>`, `<x-alert>`, `<x-modal>`, `<x-table>`, `<x-form.*>`.
  - Pembuatan partials: `<x-sidebar>` dan `<x-navbar>` dengan dark mode toggle dan user profile dropdown.
- **Files Allowed:** `resources/views/layouts/app.blade.php`, `resources/views/layouts/partials/`, `resources/views/components/`.
- **Files Forbidden:** View Stisla existing (jangan ditimpa terlebih dahulu).
- **Expected Result:** Layout utama Tailwind siap digunakan dengan struktur modular responsif.
- **Testing:**
  - [ ] Rendering komponen visual pada browser.
  - [ ] Responsivitas sidebar (collapsible di desktop, slide-over drawer di mobile).
  - [ ] Dark mode toggle berfungsi sempurna dan persist di `localStorage`.
- **Risiko:** Masalah rendering pada viewport ponsel kecil.
- **Rollback:** Hapus folder komponen baru.

---

### SESSION 6 — Redesign Halaman Autentikasi (Login & Password Reset)
- **Objective:** Memperbarui antarmuka halaman login, register, dan lupa password dengan desain modern Tailwind.
- **Scope:**
  - Redesign `resources/views/auth/login.blade.php`.
  - Redesign `resources/views/auth/register.blade.php`.
  - Redesign `resources/views/auth/passwords/*.blade.php`.
- **Files Allowed:** `resources/views/auth/`.
- **Files Forbidden:** Logic controller auth, database.
- **Expected Result:** Form login bersih, responsif, elegan, mendukung dark mode, dengan UX modern.
- **Testing:**
  - [ ] Uji login sukses dan login gagal (tampilan alert error).
  - [ ] Uji remember me checkbox.
  - [ ] Uji logout flow.
- **Risiko:** Error redirect form action.
- **Rollback:** `git checkout resources/views/auth/`.

---

### SESSION 7 — Redesign & Optimalisasi Dashboard Utama
- **Objective:** Modernisasi tampilan dashboard dan refaktor query agregat untuk kecepatan loading maksimal.
- **Scope:**
  - Refaktor `DashboardController::index` (konsolidasi query status).
  - Redesign `resources/views/backend/dashboard.blade.php` menggunakan layout Tailwind.
  - Integrasi Chart.js modern yang responsif dan mendukung skema warna dark mode.
  - Filter interaktif Provinsi dan Kota yang terintegrasi dengan chart sebaran.
- **Files Allowed:** `app/Http/Controllers/Backend/DashboardController.php`, `resources/views/backend/dashboard.blade.php`.
- **Files Forbidden:** Database schema.
- **Expected Result:** Dashboard tampil modern, bersih, dark-mode ready, dengan performa loading instan (<500ms).
- **Testing:**
  - [ ] Verifikasi keakuratan angka pada ke-8 kartu status dengan data existing.
  - [ ] Uji filter AJAX provinsi & kota.
  - [ ] Uji tampilan di mobile, tablet, dan desktop.
- **Risiko:** Salah hitung statistik jika filter query role admin cabang/daerah terlewat.
- **Rollback:** Kembalikan controller dan view dashboard.

---

### SESSION 8 — Redesign Modul Verifikasi Anggota
- **Objective:** Modernisasi alur verifikasi keanggotaan klinik (Cabang &rarr; Daerah &rarr; Sekjen &rarr; Ketum &rarr; Bendahara).
- **Scope:**
  - Redesign `resources/views/backend/verifikasi_anggota/index.blade.php` dan `verify.blade.php`.
  - Penggantian tabel DataTables Stisla ke DataTables Tailwind dengan filter chip (filter status, usia data, jenis klinik).
  - Pengetatan otorisasi role pada tombol approval dan checkbox bendahara.
- **Files Allowed:** `resources/views/backend/verifikasi_anggota/`, `app/Http/Controllers/Backend/VerifikasiAnggotaController.php`.
- **Files Forbidden:** Database schema.
- **Expected Result:** Antarmuka verifikasi yang intuitif, cepat, dan aman dari manipulasi otorisasi.
- **Testing:**
  - [ ] Uji coba peran: Admin Cabang hanya melihat kotanya, Admin Daerah provinsinya, Sekjen/Ketum melihat status terkait.
  - [ ] Approval workflow berjalan normal tanpa error.
- **Risiko:** Alur approval berjenjang terputus.
- **Rollback:** `git checkout` controller dan view verifikasi anggota.

---

### SESSION 9 — Redesign Modul Data Anggota Terverifikasi
- **Objective:** Modernisasi direktori klinik terverifikasi dan pengelolaan data anggota.
- **Scope:**
  - Redesign `resources/views/backend/anggota/index.blade.php` dan `detail_anggota.blade.php`.
  - Integrasi fitur print sertifikat dan print SK berorientasi print stylesheet Tailwind.
  - Pencegahan IDOR pada pencetakan dokumen.
- **Files Allowed:** `resources/views/backend/anggota/`, `app/Http/Controllers/Backend/AnggotaController.php`.
- **Files Forbidden:** Database schema.
- **Expected Result:** Tampilan direktori klinik profesional dengan pencarian cepat dan navigasi rapi.
- **Testing:**
  - [ ] Filter dan sorting DataTables.
  - [ ] Cetak SK dan sertifikat menghasilkan output rapi.
- **Risiko:** Format cetak sertifikat/SK tergeser.
- **Rollback:** Kembalikan template cetak dan controller anggota.

---

### SESSION 10 — Redesign Modul Iuran & Pembayaran Anggota
- **Objective:** Modernisasi verifikasi uang pangkal, verifikasi iuran, dan bukti pembayaran pusat/daerah.
- **Scope:**
  - Redesign views di `resources/views/backend/pembayaran/` dan `pembayaran_pusat/`.
  - Tampilan bukti transfer pop-up/lightbox yang aman.
  - Pengetatan hak akses eksklusif Bendahara dan Superadmin.
- **Files Allowed:** `resources/views/backend/pembayaran/`, `resources/views/backend/pembayaran_pusat/`, `app/Http/Controllers/Backend/PembayaranController.php`, `PembayaranPusatController.php`.
- **Files Forbidden:** Database schema.
- **Expected Result:** Alur verifikasi keuangan klinik jelas, akurat, dan aman.
- **Testing:**
  - [ ] Uji verifikasi iuran dan uang pangkal.
  - [ ] Uji validasi bukti upload pembayaran.
- **Risiko:** Kesalahan update status pembayaran.
- **Rollback:** Revert controller pembayaran.

---

### SESSION 11 — Redesign Modul Pelaporan (Pusat, Daerah, Cabang)
- **Objective:** Memperbaiki bug crash view, optimasi konsumsi memory pada ekspor data, dan modernisasi UI laporan.
- **Scope:**
  - Redesign `resources/views/backend/laporan/` (`pusat.blade.php`, `daerah.blade.php`, `cabang.blade.php`).
  - Perbaikan view action yang hilang pada `LaporanCabangController` & `LaporanDaerahController`.
  - Optimalisasi ekspor Excel & PDF via streaming chunking pada `LaporanPusatController`.
  - Interactive column selector berbasis Tailwind multiselect pill.
- **Files Allowed:** `resources/views/backend/laporan/`, `app/Http/Controllers/Backend/laporan/`.
- **Files Forbidden:** Database schema.
- **Expected Result:** Halaman laporan cabang/daerah tidak lagi crash; ekspor ribuan data anggota lancar tanpa memory timeout.
- **Testing:**
  - [ ] Akses laporan cabang, daerah, pusat secara berurutan.
  - [ ] Ekspor Excel dengan kombinasi kolom kustom.
  - [ ] Ekspor PDF.
- **Risiko:** Format PDF tabel melebihi batas lebar kertas.
- **Rollback:** Revert controller laporan.

---

### SESSION 12 — Redesign Master Data Organisasi
- **Objective:** Modernisasi pengelolaan master fasilitas, fasilitas klinik, layanan kesehatan, dan kategori pembayaran.
- **Scope:**
  - Redesign views di `resources/views/backend/masterdata/` (`fasilitas`, `fasilitas_klinik`, `layanan`, `kategori_pembayaran`).
  - Penggantian modal CRUD lama dengan form slide-over drawer yang elegan.
- **Files Allowed:** `resources/views/backend/masterdata/`, `app/Http/Controllers/Backend/masterdata/`.
- **Files Forbidden:** Database schema.
- **Expected Result:** Antarmuka master data responsif dan nyaman digunakan.
- **Testing:**
  - [ ] Tambah, edit, dan hapus master data.
- **Risiko:** Kerusakan relasi jika ID fasilitas terhapus (sudah ada constraint).
- **Rollback:** Revert folder masterdata.

---

### SESSION 13 — Redesign Modul KONAS (Kongres Nasional)
- **Objective:** Modernisasi modul administrasi Konas (hotel, tipe hotel, peserta, booking, manifest penerbangan, partner).
- **Scope:**
  - Redesign seluruh views di `resources/views/backend/konas/`.
  - Pengamanan webhook callback dan proteksi data peserta.
- **Files Allowed:** `resources/views/backend/konas/`, `app/Http/Controllers/Backend/konas/`.
- **Files Forbidden:** Database schema.
- **Expected Result:** Modul Konas modern dengan pencarian manifest penerbangan dan akomodasi hotel yang cepat.
- **Testing:**
  - [ ] Filter peserta, hotel, dan invoice.
- **Risiko:** Perubahan flow booking hotel.
- **Rollback:** Revert modul konas.

---

### SESSION 14 — Redesign Ruang Pengurus & Administrasi Surat
- **Objective:** Modernisasi struktur organisasi, jenis surat, penomoran surat, surat keluar, surat tugas, dan surat undangan.
- **Scope:**
  - Redesign views di `resources/views/backend/ruang_pengurus/` dan `agenda/`.
  - Visualisasi bagan struktur organisasi yang rapi.
  - Form pembuatan surat tugas dan surat keluar dengan preview cetak otomatis.
- **Files Allowed:** `resources/views/backend/ruang_pengurus/`, `app/Http/Controllers/Backend/ruang_pengurus/`, `app/Http/Controllers/Backend/AgendaController.php`.
- **Files Forbidden:** Database schema.
- **Expected Result:** Sistem administrasi persuratan efisien dengan generator nomor surat yang andal.
- **Testing:**
  - [ ] Generate nomor surat otomatis sesuai jenis surat.
  - [ ] Preview cetak PDF surat tugas & undangan.
- **Risiko:** Penomoran ganda jika race condition (pertahankan logic counter existing).
- **Rollback:** Revert ruang pengurus.

---

### SESSION 15 — Redesign Modul Monitoring Masa Berlaku (Expired SIO & Sertifikat)
- **Objective:** Modernisasi dashboard monitoring masa berlaku SIO klinik dan Sertifikat anggota.
- **Scope:**
  - Redesign `resources/views/backend/expired_sio/` dan `expired_sertifikat/`.
  - Indikator visual kartu urgensi (Merah: sudah lewat, Kuning: <30 hari, Hijau: aman).
- **Files Allowed:** `resources/views/backend/expired_sio/`, `resources/views/backend/expired_sertifikat/`, `app/Http/Controllers/Backend/ExpiredSIOController.php`, `ExpiredSertifikatController.php`.
- **Files Forbidden:** Database schema.
- **Expected Result:** Pengurus dapat memantau klinik yang izinnya akan habis dengan akurat dan cepat.
- **Testing:**
  - [ ] Uji filter tanggal expired.
- **Risiko:** Kesalahan kalkulasi selisih hari.
- **Rollback:** Revert expired SIO & sertifikat.

---

### SESSION 16 — Redesign Konten Portal Web & Pengaturan Sistem (Secret)
- **Objective:** Modernisasi manajemen slider, berita, event, galeri, kontak, manajemen user, role, permission, dan setting aplikasi.
- **Scope:**
  - Redesign views di `resources/views/backend/web/` dan `resources/views/backend/secret/`.
  - Pengamanan strictly role `Superadmin`.
  - Uploader media berita dengan proteksi ekstensi aman.
- **Files Allowed:** `resources/views/backend/web/`, `resources/views/backend/secret/`, controllers terkait di `Backend/web/` dan `Backend/secret/`.
- **Files Forbidden:** Database schema.
- **Expected Result:** Halaman CMS portal berita dan manajemen pengguna superadmin yang modern dan aman.
- **Testing:**
  - [ ] Uji CRUD user, penetapan role Spatie.
  - [ ] Uji setting logo/aplikasi.
- **Risiko:** Terkunci dari hak akses jika permission keliru diubah.
- **Rollback:** Revert modul secret.

---

### SESSION 17 — Redesign Alur Portal Anggota (Frontend Dashboard & SDM Klinik)
- **Objective:** Memperbarui antarmuka pengguna anggota klinik saat mengisi profil, upload berkas, dan pendaftaran SDM.
- **Scope:**
  - Redesign alur bertahap (stepper wizard) pendaftaran klinik, SDM dokter, perawat, asuransi, dan foto klinik.
  - Pencegahan IDOR secara tuntas pada formulir pendaftaran klinik.
- **Files Allowed:** `resources/views/frontend/home/` (views pendaftaran anggota), `app/Http/Controllers/Frontend/HomeController.php`.
- **Files Forbidden:** Database schema.
- **Expected Result:** Pengalaman pengisian data klinik oleh dokter/pengelola klinik menjadi sangat mudah, jelas, dan mobile-friendly.
- **Testing:**
  - [ ] Simulasi pendaftaran klinik dari user baru hingga submit akhir.
  - [ ] Cek status 'create_dokter' dan 'proses'.
- **Risiko:** Perubahan nama input name yang tidak sesuai model.
- **Rollback:** Revert view pendaftaran anggota.

---

### SESSION 18 — Pembersihan Technical Debt & Decommissioning Template Stisla
- **Objective:** Menghapus seluruh asset lama Stisla, membersihkan script mati, dan menghapus duplikasi direktori `views/` di root.
- **Scope:**
  - Hapus direktori duplikat `views/` dari Git tracking (`git rm -r views/`).
  - Hapus berkas CSS/JS Stisla yang tidak lagi digunakan di `public/assets/backend/`.
  - Bersihkan rute dan controller yang di-comment out.
- **Files Allowed:** `views/` (root duplicate), `public/assets/backend/`, `routes/web.php` (hapus commented routes).
- **Files Forbidden:** `resources/views/` aktif, database.
- **Expected Result:** Repositori bersih, ukuran assets ramping, tidak ada duplikasi kode.
- **Testing:**
  - [ ] Pastikan tidak ada halaman yang kehilangan styling atau script.
- **Risiko:** Aset gambar/icon yang masih dirujuk ikut terhapus.
- **Rollback:** `git checkout` asset terkait.

---

### SESSION 19 — Audit & Optimasi Performa Akhir (Cache, Query & Assets)
- **Objective:** Finalisasi optimasi kecepatan load, caching query wilayah, minifikasi asset, dan hardening konfigurasi server.
- **Scope:**
  - Caching data master wilayah (`Province`, `City`, `District`) menggunakan Laravel Cache.
  - Optimasi build Vite production (`npm run build`).
  - Hardening konfigurasi `php.ini` lokal/produksi (set limit aman: `memory_limit = 512M`, `max_execution_time = 120`).
- **Files Allowed:** `config/`, `app/Http/Controllers/WilayahController.php`, `vite.config.js`.
- **Files Forbidden:** Database schema.
- **Expected Result:** Page load < 300ms, query count per request turun drastis, rating performa tinggi.
- **Testing:**
  - [ ] Pengujian waktu respon endpoint wilayah dan dashboard.
  - [ ] Verifikasi browser console bebas dari error JS/CSS.
- **Risiko:** Cache stale jika data wilayah dimutasi.
- **Rollback:** `php artisan cache:clear` dan revert config.

---

### SESSION 20 — Pengujian Regresi Menyeluruh (Full Regression Test) & Sign-Off
- **Objective:** Validasi menyeluruh dari awal hingga akhir (*end-to-end*) untuk memastikan seluruh alur bisnis berjalan 100% sempurna tanpa cela.
- **Scope:** Eksekusi checklist pengujian menyeluruh terhadap 100% fitur aplikasi.
- **Checklist Pengujian:**
  - [ ] Sintaks PHP bebas error di seluruh file (`php -l`).
  - [ ] Boot framework Laravel 11 stabil (`php artisan about`).
  - [ ] Seluruh rute terdaftar tanpa konflik (`php artisan route:list`).
  - [ ] Alur registrasi anggota baru -> pengisian data -> upload berkas -> draft -> submit.
  - [ ] Alur verifikasi cabang -> verifikasi daerah -> verifikasi sekjen -> verifikasi ketum -> verifikasi bendahara.
  - [ ] Alur penomoran anggota otomatis berjalan sesuai aturan bisnis.
  - [ ] Alur cetak SK dan sertifikat menghasilkan output akurat.
  - [ ] Seluruh modul pelaporan dapat diakses dan diekspor tanpa memory exhaustion.
  - [ ] Navigasi sidebar, navbar, dark mode, dan responsivitas mobile teruji sempurna di berbagai ukuran layar.
  - [ ] Tidak ada berkas sensitif terlacak di Git.
  - [ ] Struktur skema database production terkonfirmasi **100% TIDAK BERUBAH**.
- **Files Allowed:** Berkas dokumentasi serah terima `/docs/FINAL-REPORT.md`.
- **Files Forbidden:** Seluruh source code.
- **Expected Result:** Aplikasi ASKLIN bertransformasi menjadi modern, aman, cepat, dan siap digunakan dengan integritas data production terjamin.

---

## 10. Protokol Kerja, Git & Rollback Rules

1. **Aturan Git:**
   - Sebelum mulai sesi: Jalankan `git status`, periksa branch aktif dan commit terakhir.
   - Selesai sesi: Periksa `git diff`, verifikasi hanya file dalam scope yang berubah, lalu lakukan commit lokal:
     ```bash
     git add <file-terkait>
     git commit -m "feat(scope): deskripsi pekerjaan sesi"
     ```
   - **DILARANG KERAS MELAKUKAN `git push`**: Push hanya akan dilakukan oleh pengguna sendiri.
   - Dilarang menjalankan `git reset --hard` atau mengubah histori commit tanpa instruksi.

2. **Aturan Integritas Database:**
   - Setiap sesi backend wajib menyertakan verifikasi bahwa tidak ada perubahan DDL pada database.
   - Tidak boleh ada query `CREATE TABLE`, `ALTER TABLE`, `DROP TABLE`, atau manipulasi metadata skema.

3. **Checklist Standar Setiap Sesi:**
   - [ ] PHP syntax check lulus
   - [ ] Laravel boot check lulus
   - [ ] Route check lulus
   - [ ] Blade rendering check lulus
   - [ ] Authorization / permission check lulus
   - [ ] Business logic intact
   - [ ] Asset build sukses
   - [ ] Laravel log bersih dari exception baru
   - [ ] Git diff terverifikasi bersih
   - [ ] Database schema unchanged

