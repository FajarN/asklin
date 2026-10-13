# ANALISIS REFERENSI DESAIN UI/UX MOBILE ASKLIN DAN INTEGRASI DENGAN BLUEPRINT

**Tanggal Analisis:** 13 Oktober 2026  
**Status Dokumen:** COMPLETED (Analisis & Pemetaan Referensi Desain)  
**Lokasi Berkas Gambar Referensi:** `/docs/blueprint/*.jpg` (8 Berkas Gambar Desain)  
**Dokumen Acuan:**  
- `/docs/blueprint/mobile-app/ANALISIS-AWAL.md`
- `/docs/blueprint/mobile-app/BLUEPRINT-LARAVEL-API.md`
- `/docs/blueprint/mobile-app/BLUEPRINT-FLUTTER-APP.md`
- `/docs/blueprint/mobile-app/API-DOCUMENTATION.md`
**Lokasi Dokumen Ini:** `/docs/blueprint/mobile-app/ANALISIS-DESAIN-UIUX.md`

---

## 1. Inventarisasi & Tinjauan Seluruh Gambar Referensi Desain

Ditemukan **8 berkas gambar desain UI/UX beresolusi tinggi** pada folder `/docs/blueprint/`:

1. **`Layar Masuk Aplikasi ASKLIN.jpg`** (571 KB) — Layar Login & Pemilihan Jenis Akun.
2. **`Formulir Pendaftaran Akun ASKLIN.jpg`** (612 KB) — Layar Registrasi Akun Pengguna Baru.
3. **`Beranda Aplikasi ASKLIN Modern.jpg`** (906 KB) — Beranda Utama Publik / Informasi Portal.
4. **`Dasbor Keanggotaan ASKLIN Modern.jpg`** (767 KB) — Beranda Khusus Anggota Klinik (Dasbor Keanggotaan).
5. **`Profil ASKLIN dengan Informasi Keanggotaan.jpg`** (616 KB) — Layar Profil Akun & Pengaturan Pengguna.
6. **`Agenda ASKLIN_ Kongres dan Kegiatan Klinik.jpg`** (889 KB) — Layar Daftar Agenda, Kongres & Kegiatan.
7. **`Berita dan Artikel ASKLIN Terkini.jpg`** (884 KB) — Layar Direktori Berita & Artikel Terkini.
8. **`Direktori Organisasi ASKLIN Indonesia.jpg`** (753 KB) — Layar Struktur Pengurus Organisasi (DPP, DPD, DPC).

---

## 2. Karakteristik Sistem Desain Visual ASKLIN (Design System)

Berdasarkan analisis terhadap ke-8 gambar referensi, sistem desain memiliki identitas visual yang seragam, konsisten, dan sangat profesional:

### 2.1 Palet Warna Utama (Color Palette)
- **Primary Brand Red:** `#D32F2F` / `#E50914` / `#DC2626` (Merah Resmi ASKLIN) — Digunakan pada CTA Button utama, active tab indicator, badge kategori penting, dan header banner.
- **Secondary / Soft Red Tint:** `#FEE2E2` / `#FFEBEE` / `#FFF1F2` — Digunakan pada latar belakang ikon menu, container role, dan alert notification.
- **Background Color:** `#F8FAFC` / `#FFFFFF` (Putih bersih dan abu-abu sangat muda / Slate 50) — Memberikan kontras tinggi dan kesan medis yang higienis.
- **Card Surface:** `#FFFFFF` murni dengan subtle box shadow (`0 2px 8px rgba(0,0,0,0.06)`), border radius 12px s.d. 16px.
- **Text Color Hierarchy:**
  - Primary Heading: `#0F172A` / `#1E293B` (Dark Navy / Slate 900)
  - Body Text: `#334155` / `#475569` (Slate 700 / Slate 600)
  - Secondary / Caption / Date: `#94A3B8` / `#64748B` (Slate 400 / Slate 500)
  - Success Green: `#16A34A` / `#22C55E` — Badge status keanggotaan aktif.

### 2.2 Tipografi & Ikonografi
- **Font Family:** Modern Sans-Serif (`Inter`, `Plus Jakarta Sans`, atau `Poppins`).
- **Heading:** Semi-bold s.d. Bold, proporsi tegas dan mudah dibaca.
- **Icon Style:** Line/Outlined icon dengan aksen solid color di dalam rounded square container (diameter 40–48px).
- **Elemen Grafis Budaya / Nasional:** Terdapat ornamen siluet Gedung Nasional / DPR-MPR, bendera Merah Putih, dan gelombang merah di bagian bawah layar otentikasi.

### 2.3 Navigasi Bawah (Bottom Navigation Bar)
Terdapat 5 tab standar pada shell utama aplikasi:
1. **Beranda** (Icon Home)
2. **Organisasi** (Icon Building / Monument)
3. **Berita** (Icon Newspaper)
4. **Notifikasi / Agenda** (Icon Bell dengan badge merah / Icon Calendar)
5. **Profil** (Icon Person)

---

## 3. Analisis Mendalam Per Halaman Gambar Desain

---

### Gambar 1: `Layar Masuk Aplikasi ASKLIN.jpg`
- **Representasi:** Layar Login (Autentikasi).
- **Komponen UI:**
  - Logo resmi ASKLIN di bagian atas tengah.
  - Subtitle: *"Masuk untuk mengakses layanan keanggotaan"*.
  - Input field Email/Username dengan ikon amplop.
  - Input field Password dengan ikon gembok dan visibility eye toggle.
  - Checkbox "Ingat saya" (merah) & Tautan "Lupa kata sandi?".
  - Primary Button: "Masuk ->" (merah pekat, rounded 10px).
  - Divider teks: "--- Pilih Jenis Akun ---".
  - 4 Quick Card Seleksi Akun (2x2 grid): "Pengurus Pusat", "Pengurus Daerah", "Pengurus Cabang", "Anggota" (masing-masing dilengkapi ikon dan chevron).
  - Footer link: "Belum punya akun? Daftar di sini ->".
  - Security note: Shield icon *"Aplikasi resmi Asosiasi Klinik Indonesia. Aman, terpercaya, dan profesional"*.
- **Hubungan dengan Blueprint:** Sesuai dengan **Sesi FLUTTER-3 (Autentikasi & Login)**.
- **Kebutuhan API:** `POST /api/v1/auth/login`.

---

### Gambar 2: `Formulir Pendaftaran Akun ASKLIN.jpg`
- **Representasi:** Layar Registrasi Akun Pengguna Baru.
- **Komponen UI:**
  - Header logo ASKLIN rata kiri.
  - Judul: "Daftar Akun ASKLIN" dengan teks penjelas.
  - Form Fields:
    1. Nama Lengkap (icon user)
    2. Email (icon mail)
    3. Nomor HP (icon phone)
    4. Password (icon lock + info helper merah: *"Password minimal 8 karakter, kombinasi huruf dan angka"*)
    5. Konfirmasi Password (icon lock)
  - Checkbox Persetujuan: "Saya menyetujui Syarat dan Ketentuan dan Kebijakan Privasi ASKLIN."
  - Primary Button: "Daftar ->".
  - Footer link: "Sudah punya akun? Masuk ->".
- **Hubungan dengan Blueprint:** Melengkapi alur autentikasi Sesi FLUTTER-3.
- **Kebutuhan API:** Endpoint baru `POST /api/v1/auth/register` (memetakan ke `User::create` dan assign role `Klinik` seperti `RegisterController` di web).

---

### Gambar 3: `Beranda Aplikasi ASKLIN Modern.jpg`
- **Representasi:** Layar Beranda Utama (Portal Informasi & Publik/Anggota).
- **Komponen UI:**
  - Top Bar: Logo ASKLIN kiri, Bell notifikasi dengan badge "3", icon Search kanan.
  - Hero Slider Banner: Kartu gradient merah memuat event unggulan ("Kongres Nasional ASKLIN 2026"), tanggal, lokasi, tombol "Lihat Detail ->", dan dot indicators.
  - 5 Quick Action Icon Buttons: "Profil Organisasi", "Keanggotaan", "Berita", "Kegiatan / Event", "Direktori Cabang".
  - Section "Berita Terbaru": Slider horizontal kartu berita dengan thumbnail gambar, category pill, judul, dan tanggal. Tautan "Lihat Semua ->".
  - Section "Event Mendatang": Kartu horizontal event dengan thumbnail, tanggal, judul, lokasi, dan tombol merah "Lihat Detail ->".
  - Section "Pengumuman Penting": Kartu alert merah muda dengan ikon megaphone dan tanggal.
  - Bottom Navigation Bar (5 tabs).
- **Hubungan dengan Blueprint:** Sesuai dengan **Sesi FLUTTER-4 (Shell Navigasi)** dan **Sesi FLUTTER-5**.

---

### Gambar 4: `Dasbor Keanggotaan ASKLIN Modern.jpg`
- **Representasi:** Beranda Khusus Anggota Klinik (Dasbor Keanggotaan).
- **Komponen UI:**
  - Greeting Banner: *"Selamat pagi, Budi Santoso / Semoga hari ini penuh dengan hal baik untuk kemajuan layanan kesehatan."* dengan siluet gedung nasional.
  - Kartu Identitas Anggota Utama:
    - Foto avatar dokter/klinik.
    - Nama klinik: "Klinik Sehat Bersama".
    - Nomor Anggota: "AKT-123456".
    - Status Badge Container: Checkmark hijau *"Status Keanggotaan: Aktif"* & Kalender *"Berlaku hingga: 31 Desember 2026"*.
  - 4 Tombol Akses Cepat (Quick Action):
    1. "Data Anggota"
    2. "Dokumen Keanggotaan" (SK & Sertifikat)
    3. "Informasi Iuran"
    4. "Pengumuman"
  - Feed Pengumuman Terbaru & Event Mendatang.
- **Hubungan dengan Blueprint:** Sesuai persis dengan **Sesi FLUTTER-5 (Beranda & Profil Anggota)** dan **Sesi FLUTTER-6 (Dokumen SK & Sertifikat)**.
- **Kebutuhan API:** `GET /api/v1/anggota/profil` dan `GET /api/v1/anggota/status-keanggotaan`.

---

### Gambar 5: `Profil ASKLIN dengan Informasi Keanggotaan.jpg`
- **Representasi:** Layar Profil Saya & Pengaturan Akun.
- **Komponen UI:**
  - Top Bar: Back arrow, Judul "Profil Saya", Bell icon.
  - Kartu Header Profil: Avatar, Nama Anggota, Nama Klinik, Badge "Anggota Aktif".
  - Card Group 1 — "Informasi Pribadi": Nama Lengkap, Tempat Tanggal Lahir, Jenis Kelamin.
  - Card Group 2 — "Informasi Kontak": Email, Nomor HP, Alamat lengkap.
  - Card Group 3 — "Informasi Keanggotaan": Nomor Anggota, Kategori Keanggotaan (Klinik Pratama/Utama), Berlaku Hingga.
  - Action Card: "Edit Profil" (icon pensil).
  - Card Group 4 — "Pengaturan Akun": Ubah Kata Sandi, Privasi, Pusat Bantuan.
  - Action Card: "Keluar" (merah, icon logout door).
- **Hubungan dengan Blueprint:** Sesuai dengan **Sesi FLUTTER-5** dan **Sesi FLUTTER-3 (Logout)**.
- **Kebutuhan API:** `GET /api/v1/anggota/profil` dan `POST /api/v1/auth/logout`.

---

### Gambar 6: `Agenda ASKLIN_ Kongres dan Kegiatan Klinik.jpg`
- **Representasi:** Layar Daftar Agenda & Kegiatan (Kongres, Seminar, Workshop, Pelatihan).
- **Komponen UI:**
  - Top Bar: Back arrow, Judul "Agenda & Kegiatan", teks intro.
  - Featured Event Card (Banner atas).
  - Segmented Tab Switcher: "Agenda Mendatang" (Active red pill) vs "Kegiatan Selesai".
  - Horizontal Category Filter Chips: "Semua", "Kongres", "Seminar", "Workshop", "Pelatihan".
  - Vertical Event Cards List:
    - Thumbnail foto.
    - Date Badge vertikal (misal: "NOV 12 2026").
    - Category Tag ("Kongres", "Seminar", "Workshop").
    - Detail: Tanggal, Lokasi/Venue, Target Peserta.
    - Tombol "Lihat Detail" (merah) & Status badge ("Pendaftaran Dibuka" / "Pendaftaran Belum Dibuka").
- **Hubungan dengan Blueprint:** Memperluas fitur informasi anggota.
- **Kebutuhan API:** Endpoint baru `GET /api/v1/agenda` dan `GET /api/v1/agenda/{id}` (data dari tabel `agenda` & `events`).

---

### Gambar 7: `Berita dan Artikel ASKLIN Terkini.jpg`
- **Representasi:** Layar Direktori Berita & Artikel.
- **Komponen UI:**
  - Top Bar: Logo ASKLIN, Icon search, Icon bell badge 3.
  - Search Bar: "Cari berita atau artikel...".
  - Horizontal Category Filter Chips: "Semua", "Berita Organisasi", "Kegiatan", "Informasi Klinik", "Pengumuman".
  - "Berita Utama": Hero card besar dengan bookmark icon, badge kategori, judul tebal, tanggal, excerpt.
  - "Berita Terbaru": List vertikal kartu berita (thumbnail kiri, kategori, judul, tanggal, excerpt, bookmark icon kanan).
- **Hubungan dengan Blueprint:** Tab Berita pada Bottom Navigation Bar (Sesi FLUTTER-4).
- **Kebutuhan API:** Endpoint baru `GET /api/v1/berita` dan `GET /api/v1/berita/{slug}` (data dari tabel `berita` & `berita_kategori`).

---

### Gambar 8: `Direktori Organisasi ASKLIN Indonesia.jpg`
- **Representasi:** Layar Struktur Kepengurusan Organisasi (Direktori Organisasi).
- **Komponen UI:**
  - Top Bar: Back arrow, Judul "Direktori Organisasi", icon search & bell.
  - Subtitle penjelasan tingkatan organisasi (Pusat, Provinsi, Kabupaten/Kota).
  - Search input: "Cari nama organisasi atau lokasi...".
  - 3 Level Selector Buttons: "Pengurus Pusat", "Pengurus Daerah (Provinsi)", "Pengurus Cabang (Kabupaten/Kota)".
  - Card Highlight DPP ASKLIN (Jakarta Pusat, DKI Jakarta).
  - Section "Daftar Pengurus Pusat" (1 organisasi).
  - Section "Daftar Pengurus Daerah" (34 provinsi) dengan dropdown filter provinsi.
  - Section "Daftar Pengurus Cabang" (152 kabupaten/kota) dengan empty state edukatif: *"Pilih provinsi terlebih dahulu"*.
- **Hubungan dengan Blueprint:** Tab Organisasi pada Bottom Navigation Bar (Sesi FLUTTER-4 & Sesi FLUTTER-7/8).
- **Kebutuhan API:** Endpoint baru `GET /api/v1/organisasi/struktur` (data dari tabel `struktur_organisasi` & `tingkatan_pengurus`).

---

## 4. Matriks Pemetaan Desain Visual terhadap Blueprint & API

| No | Nama File Gambar | Nama Halaman | Elemen Visual Utama | Acuan Blueprint | Kebutuhan API Backend | Sesi Implementasi | Status Kelayakan | Catatan Teknis |
|---|---|---|---|---|---|---|---|---|
| 1 | `Layar Masuk Aplikasi ASKLIN.jpg` | Layar Login | Logo ASKLIN, input email & password, remember me, quick account type cards (4 role), link daftar | Sesi FLUTTER-3 (Login & Sesi) | `POST /api/v1/auth/login` | Sesi FLUTTER-3 | **Bisa Diterapkan Langsung** | Kartu 4 jenis akun di bawah form berfungsi sebagai quick info/filter role atau shortcut login role. |
| 2 | `Formulir Pendaftaran Akun ASKLIN.jpg` | Layar Registrasi Akun | Logo rata kiri, input Nama, Email, No HP, Password + helper text merah, Konfirmasi Password, Checkbox S&K | Sesi FLUTTER-3 (Pendaftaran) | `POST /api/v1/auth/register` | Sesi FLUTTER-3 | **Perlu Penyesuaian API** | Backend perlu menambahkan endpoint register user standar role `Klinik` (seperti `RegisterController`). |
| 3 | `Beranda Aplikasi ASKLIN Modern.jpg` | Beranda Portal / Publik | App bar logo & notif, Hero carousel event, 5 quick menu icons, feed Berita Terbaru horizontal, Event card, Alert Pengumuman, Bottom Nav 5 tab | Sesi FLUTTER-4 & FLUTTER-5 | `GET /api/v1/berita`, `GET /api/v1/agenda` | Sesi FLUTTER-4, FLUTTER-5 | **Perlu Penyesuaian API** | Memerlukan endpoint feed portal publik (`/berita` & `/agenda`). |
| 4 | `Dasbor Keanggotaan ASKLIN Modern.jpg` | Dasbor Anggota Klinik | Greeting header nama klinik & siluet monumen, Member Card (avatar, nama, no anggota, badge aktif hijau, masa berlaku), 4 Akses Cepat, Feed pengumuman & event | Sesi FLUTTER-5 (Beranda Anggota) | `GET /api/v1/anggota/profil`, `GET /api/v1/anggota/status-keanggotaan` | Sesi FLUTTER-5 | **Bisa Diterapkan Langsung** | Data profil & status dari API Sesi 4 cocok 100% dengan kartu keanggotaan. |
| 5 | `Profil ASKLIN dengan Informasi Keanggotaan.jpg` | Profil Saya & Pengaturan | Header profil anggota, 3 grup info (Pribadi, Kontak, Keanggotaan), tombol Edit Profil, 3 menu Pengaturan Akun, tombol Logout merah | Sesi FLUTTER-5 (Profil Klinik) | `GET /api/v1/anggota/profil`, `POST /api/v1/auth/logout` | Sesi FLUTTER-5 | **Bisa Diterapkan Langsung** | Kompatibel penuh dengan endpoint profil dan auth logout yang sudah ada. |
| 6 | `Agenda ASKLIN_ Kongres dan Kegiatan Klinik.jpg` | Agenda & Kegiatan | Hero featured event, tab segmented "Mendatang" vs "Selesai", filter chips (Kongres, Seminar, dll), event list card dengan date badge & status pendaftaran | Modul Informasi & Event Anggota | `GET /api/v1/agenda`, `GET /api/v1/agenda/{id}` | Sesi FLUTTER-5 / Tambahan Sesi Event | **Perlu Penyesuaian API** | Backend Laravel sudah memiliki tabel `agenda` & `events`, tinggal dipublikasikan via API. |
| 7 | `Berita dan Artikel ASKLIN Terkini.jpg` | Direktori Berita & Artikel | Search input, filter chips kategori, Featured article card besar + bookmark, vertical list berita terbaru | Tab Berita pada Sesi FLUTTER-4 | `GET /api/v1/berita`, `GET /api/v1/berita/{id}` | Sesi FLUTTER-4 / Tambahan Sesi Berita | **Perlu Penyesuaian API** | Backend Laravel sudah memiliki tabel `berita` & `berita_kategori`, tinggal dipublikasikan via API. |
| 8 | `Direktori Organisasi ASKLIN Indonesia.jpg` | Direktori Organisasi | Search bar lokasi, 3 tab level (Pusat, Daerah, Cabang), Card DPP highlight, list DPD (34 provinsi) dengan dropdown filter, DPC placeholder | Tab Organisasi pada Sesi FLUTTER-4 / Sesi FLUTTER-7 | `GET /api/v1/organisasi/struktur` | Sesi FLUTTER-4 / Sesi FLUTTER-8 | **Perlu Penyesuaian API** | Backend Laravel sudah memiliki tabel `struktur_organisasi` & `tingkatan_pengurus`, tinggal dipublikasikan via API. |

---

## 5. Analisis Kesesuaian & Kesenjangan (Gap Analysis)

### 5.1 Desain yang Dapat Diterapkan Langsung (100% Match)
1. **Layar Masuk (`Layar Masuk Aplikasi ASKLIN.jpg`):**
   - Endpoint backend `POST /api/v1/auth/login` sudah selesai dan teruji.
   - Penanganan token Sanctum dan pembagian role sudah didukung penuh.
2. **Dasbor Keanggotaan (`Dasbor Keanggotaan ASKLIN Modern.jpg`):**
   - Endpoint `GET /api/v1/anggota/profil` dan `GET /api/v1/anggota/status-keanggotaan` menyediakan seluruh data yang dibutuhkan: nama klinik, no anggota, status keanggotaan aktif/tidak, tanggal masa berlaku, dan dokumen.
3. **Profil Saya (`Profil ASKLIN dengan Informasi Keanggotaan.jpg`):**
   - Data kontak, alamat, nama penanggung jawab, dan tombol logout cocok 100% dengan API dan model data `anggota` & `users`.

### 5.2 Desain yang Membutuhkan Penyesuaian Teknis & API Tambahan
Di dalam gambar referensi, terdapat **3 layar konten publik/organisasi** yang belum memiliki endpoint REST API khusus di `routes/api.php`, meskipun tabel dan datanya **sudah lengkap tersedia di database MySQL eksisting**:

1. **Layar Pendaftaran Akun (`Formulir Pendaftaran Akun ASKLIN.jpg`):**
   - *Status di Backend:* Web memiliki `RegisterController` yang membuat record di tabel `users` dan memberi role `Klinik`.
   - *Kebutuhan API:* Tambahkan endpoint `POST /api/v1/auth/register` (Nama, Email, No HP, Password).
2. **Layar Berita & Artikel (`Berita dan Artikel ASKLIN Terkini.jpg`):**
   - *Status di Backend:* Database memiliki tabel `berita`, `berita_kategori`, dan `berita_images`.
   - *Kebutuhan API:* Tambahkan endpoint publik `GET /api/v1/berita` (dengan pagination & search) dan `GET /api/v1/berita/{id}`.
3. **Layar Agenda & Kegiatan (`Agenda ASKLIN_ Kongres dan Kegiatan Klinik.jpg`):**
   - *Status di Backend:* Database memiliki tabel `agenda` dan `events`.
   - *Kebutuhan API:* Tambahkan endpoint publik `GET /api/v1/agenda` (filter mendatang vs selesai).
4. **Layar Direktori Organisasi (`Direktori Organisasi ASKLIN Indonesia.jpg`):**
   - *Status di Backend:* Database memiliki tabel `struktur_organisasi` dan `tingkatan_pengurus`.
   - *Kebutuhan API:* Tambahkan endpoint publik `GET /api/v1/organisasi/struktur?level={pusat|daerah|cabang}`.

### 5.3 Perbedaan Desain vs Blueprint yang Perlu Diperhatikan
1. **Layar Pengurus (Monitoring Khusus Cabang/Daerah/Pusat):**
   - Ke-8 gambar yang ada saat ini berfokus pada **antarmuka Anggota & Portal Informasi Umum ASKLIN**.
   - Di blueprint, terdapat kebutuhan monitoring khusus pengurus (statistik grafik KPI, daftar verifikasi klinik per wilayah).
   - *Rekomendasi:* Layar monitoring pengurus dapat mengadopsi pola visual dari `Dasbor Keanggotaan ASKLIN Modern.jpg` dan `Direktori Organisasi ASKLIN Indonesia.jpg` (menggunakan kartu ringkasan KPI, filter dropdown provinsi/kota, dan list view klinik dengan badge status yang konsisten).
2. **Tab Bar ke-4 (Notifikasi vs Agenda):**
   - Pada `Beranda Aplikasi ASKLIN Modern.jpg`, tab ke-4 adalah **Notifikasi** (ikon lonceng ber-badge).
   - Pada `Agenda ASKLIN_ Kongres dan Kegiatan Klinik.jpg`, tab ke-4 adalah **Agenda** (ikon kalender).
   - *Rekomendasi:* Gunakan tab **Agenda / Kegiatan** pada tab bar utama, sedangkan **Notifikasi** tetap diakses melalui ikon lonceng di Top AppBar (seperti yang ada di kanan atas seluruh layar). Ini menyelesaikan perbedaan dan memberikan pengalaman pengguna yang lebih baik.

---

## 6. Komponen UI Bersama yang Wajib Dibuat (Reusable UI Components)

Untuk memastikan konsistensi visual 100% dengan gambar referensi di seluruh sesi Flutter:

1. **`AsklinAppBar`:** App bar kustom dengan Logo ASKLIN kiri, back button kondisional, judul layar navy bold, icon search, dan icon lonceng notifikasi dengan red counter badge.
2. **`AsklinPrimaryButton`:** Tombol rounded (radius 12px) dengan warna merah ASKLIN (`#D32F2F`), teks putih semi-bold, dan panah kanan (`->`).
3. **`AsklinTextField`:** Input field dengan border rounded lembut, background abu-abu muda (`#F8FAFC`), ikon prefix di kiri, dan eye toggle di kanan untuk password.
4. **`AsklinCategoryChip`:** Chip filter horizontal (status aktif: merah pekat dengan teks putih; status inaktif: abu-abu muda dengan teks navy).
5. **`AsklinCard`:** Kartu kontainer putih bersih dengan elevation halus, radius 12-16px, padding seragam 16px.
6. **`AsklinBottomNavBar`:** Navigasi 5 tab dengan active indicator merah pekat dan ikon proporsional.
7. **`AsklinBadge`:** Pill badge status (Hijau untuk "Aktif/Approved", Merah untuk kategori/kegiatan, Kuning/Orange untuk pending).
8. **`NationalLandmarkWave`:** Ornamen footer lengkungan merah dengan siluet grafis gedung nasional untuk layar autentikasi dan header dashboard.

---

## 7. Usulan Penyesuaian Tahapan Sesi Flutter Berdasarkan Gambar

Berdasarkan ke-8 gambar referensi, kita dapat menyelaraskan 10 Sesi Flutter secara presisi:

- **Sesi FLUTTER-1:** Setup Arsitektur, Assets (Logo ASKLIN, Icon pack, Siluet landmark), dan Theme (`AsklinTheme`: warna merah `#D32F2F`, font modern, card radius).
- **Sesi FLUTTER-2:** Core Network, Storage, dan Reusable Components (`AsklinButton`, `AsklinTextField`, `AsklinCard`, `AsklinAppBar`).
- **Sesi FLUTTER-3:** Layar Masuk (`Layar Masuk Aplikasi ASKLIN.jpg`) & Formulir Pendaftaran (`Formulir Pendaftaran Akun ASKLIN.jpg`).
- **Sesi FLUTTER-4:** Navigasi 5 Tab & Shell Utama (`AsklinBottomNavBar`), serta Layar Direktori Organisasi (`Direktori Organisasi ASKLIN Indonesia.jpg`).
- **Sesi FLUTTER-5:** Beranda Utama (`Beranda Aplikasi ASKLIN Modern.jpg`), Dasbor Keanggotaan (`Dasbor Keanggotaan ASKLIN Modern.jpg`), dan Profil Saya (`Profil ASKLIN dengan Informasi Keanggotaan.jpg`).
- **Sesi FLUTTER-6:** Dokumen Legalitas (Akses Cepat Dokumen: Viewer & Downloader SK & Sertifikat dengan proteksi anti-IDOR).
- **Sesi FLUTTER-7:** Modul Berita & Artikel (`Berita dan Artikel ASKLIN Terkini.jpg`) serta Modul Agenda & Kegiatan (`Agenda ASKLIN_ Kongres dan Kegiatan Klinik.jpg`).
- **Sesi FLUTTER-8:** Dashboard Monitoring Pengurus Cabang, Daerah, dan Pusat (Mengadopsi pola visual kartu KPI dan filter wilayah dari Direktori Organisasi).
- **Sesi FLUTTER-9:** Polishing UX, Offline State, Error Dialogs, dan Komparasi Visual 1:1 terhadap seluruh gambar referensi.
- **Sesi FLUTTER-10:** Build Android Release, Keystore Signing, dan Persiapan Rilis Google Play Store.

