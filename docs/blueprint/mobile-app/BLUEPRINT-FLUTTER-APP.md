# BLUEPRINT PENGEMBANGAN MOBILE APP ANDROID (FLUTTER) UNTUK ASKLIN

**Tanggal Penyusunan:** 13 Oktober 2026  
**Status:** DRAFT BLUEPRINT (Tahap Perencanaan - Belum Ada Pembuatan Kode Flutter)  
**Aplikasi Client:** ASKLIN Mobile (Asosiasi Klinik Indonesia)  
**Platform Target:** Android (Publikasi Google Play Store)  
**Framework:** Flutter (Bahasa Dart)  
**Backend:** Laravel REST API (Mengacu pada `BLUEPRINT-LARAVEL-API.md`)  
**Lokasi File:** `/docs/blueprint/mobile-app/BLUEPRINT-FLUTTER-APP.md`

---

## 1. Tujuan & Ruang Lingkup Aplikasi Mobile

### 1.1 Tujuan Utama
Menyediakan aplikasi mobile Android resmi bagi organisasi ASKLIN untuk:
1. **Anggota Klinik:** Memudahkan pengecekan status keanggotaan, melihat profil fasilitas kesehatan, serta mengunduh dokumen legalitas resmi (SK Anggota dan Sertifikat Keanggotaan ASKLIN) secara langsung melalui smartphone.
2. **Pengurus Cabang, Daerah, dan Pusat:** Menyediakan pusat monitoring eksekutif (dashboard KPI) untuk memantau pertumbuhan jumlah klinik, sebaran wilayah, komposisi jenis klinik (Utama/Pratama), dan status iuran secara real-time.

### 1.2 Ruang Lingkup Fitur Berdasarkan Peran Pengguna
1. **Role Klinik (Anggota):**
   - Otentikasi (Login dan Logout aman).
   - Ringkasan status keanggotaan (Disetujui / Menunggu Verifikasi / Perbaikan) dan status pembayaran.
   - Profil lengkap klinik (data umum, izin operasional, penanggung jawab, fasilitas, SDM).
   - Akses dokumen digital: preview dan download Surat Keputusan (SK) Anggota dalam format PDF.
   - Akses dokumen digital: preview dan download Sertifikat Keanggotaan ASKLIN resmi.
2. **Role Pengurus Cabang (Tingkat Kabupaten/Kota):**
   - Dashboard monitoring terbatas pada kabupaten/kota kewenangannya.
   - Metrik: total klinik anggota di cabang, klinik disetujui, klinik menunggu verifikasi, status iuran.
   - Daftar klinik anggota cabang beserta status dan rincian kontak.
3. **Role Pengurus Daerah (Tingkat Provinsi):**
   - Dashboard monitoring tingkat provinsi.
   - Filter agregat per kabupaten/kota dalam wilayah provinsi yang dipimpin.
   - Grafik pertumbuhan anggota dan perbandingan sebaran antarcabang.
4. **Role Pengurus Pusat (Tingkat Nasional / Superadmin):**
   - Dashboard monitoring nasional se-Indonesia.
   - Filter lintas provinsi dan kabupaten/kota.
   - Ringkasan statistik sebaran badan hukum (PT, CV, Yayasan, Koperasi) dan jenis klinik nasional.

---

## 2. Rekomendasi Lokasi & Struktur Direktori Proyek

### 2.1 Lokasi Direktori Proyek
Proyek Flutter **TIDAK BOLEH** ditempatkan di dalam folder proyek Laravel (`c:\laragon\www\asklin`), melainkan di direktori terpisah sebagai sibling repository:
- **Lokasi Rekomendasi:** `c:\laragon\www\asklin-mobile`
- **Alasan:**
  1. Menjaga pemisahan dependensi (PHP/Composer/Node vs Dart/Flutter/Gradle).
  2. Mencegah git conflict dan mempermudah workflow CI/CD terpisah (misalnya deployment backend via cPanel/VPS vs build APK/AAB via GitHub Actions/Fastlane).
  3. Memastikan ukuran repository Laravel tetap ramping tanpa tercampur build cache Android (`build/`, `.gradle/`).

---

## 3. Arsitektur Flutter, State Management & Routing

### 3.1 Pilihan Arsitektur: *Feature-First with Clean Architecture Principles*
Struktur kode diorganisasi berdasarkan fitur modular (*feature-first*), dengan pembagian lapisan (*presentation, domain, data*) di setiap modul:

```
lib/
├── core/                  # Utilitas bersama, konfigurasi, tema, jaringan
│   ├── constants/         # App constants, assets, colors, strings
│   ├── network/           # Dio client, interceptors, error handler
│   ├── storage/           # FlutterSecureStorage wrapper
│   ├── theme/             # Material 3 light & dark theme
│   └── utils/             # Formatters, validator, helpers
├── features/              # Modul fungsional independen
│   ├── auth/              # Login, token session, splash
│   ├── anggota/           # Profil, status keanggotaan, detail klinik
│   ├── dokumen/           # SK, sertifikat, viewer & downloader
│   ├── pengurus/          # Dashboard monitoring, statistik, filter wilayah
│   └── settings/          # Pengaturan akun, ganti password, tentang aplikasi
├── app.dart               # MaterialApp konfigurasi & route runner
└── main.dart              # Entry point aplikasi
```

### 3.2 State Management: **flutter_bloc / Cubit**
- **Alasan Pemilihan:**
  - Standard industri teruji untuk aplikasi skala korporat dengan pemisahan business logic murni.
  - Alur data satu arah (*unidirectional data flow*) yang mudah di-debug dan diuji (*unit testing*).
  - State event yang terprediksi (`Initial`, `Loading`, `Loaded`, `Failure`) sangat ideal untuk menangani request API asinkron, retry, dan error state.

### 3.3 Routing & Deep Linking: **go_router**
- **Alasan Pemilihan:**
  - Pustaka routing deklaratif resmi dari tim Flutter.
  - Mendukung route guard / redirect otomatis berbasis status otentikasi (mengarahkan pengguna belum login ke `/login` dan pengguna ber-token ke `/dashboard`).
  - Memudahkan adaptasi navigasi berbeda berdasarkan role user (Anggota diarahkan ke dashboard anggota, Pengurus diarahkan ke dashboard monitoring).

---

## 4. Konfigurasi Environment & Komunikasi API

### 4.1 Mekanisme Base URL Multi-Environment
Aplikasi menggunakan pola compile-time environment configuration (`--dart-define`) atau class config:

```dart
class AppConfig {
  static const String devBaseUrl = 'http://10.0.2.2:8000/api/v1'; // Android Emulator ke localhost
  static const String stagingBaseUrl = 'https://dev.asklin.org/api/v1';
  static const String prodBaseUrl = 'https://asklin.org/api/v1';

  static String get baseUrl {
    const env = String.fromEnvironment('ENV', defaultValue: 'dev');
    switch (env) {
      case 'prod': return prodBaseUrl;
      case 'staging': return stagingBaseUrl;
      default: return devBaseUrl;
    }
  }
}
```

### 4.2 Network Client: **Dio**
Menggunakan package `dio` dengan konfigurasi interceptor:
1. **AuthInterceptor:** Secara otomatis menyematkan header `Authorization: Bearer <token>` pada setiap outgoing request.
2. **ErrorInterceptor:** Menangkap status HTTP:
   - `401 Unauthorized` → Menghapus token lokal dan mengarahkan navigasi kembali ke layar Login dengan pesan sesi telah berakhir.
   - `403 Forbidden` → Menampilkan dialog akses ditolak (misal: pelanggaran hak akses wilayah).
   - `Connection Timeout / No Internet` → Memicu state jaringan offline dengan tombol *Coba Lagi* (*Retry*).
3. **LoggingInterceptor:** Menampilkan log payload JSON saat mode debug aktif.

---

## 5. Mekanisme Keamanan & Penyimpanan Token

1. **Penyimpanan Token:**
   - Menggunakan pustaka `flutter_secure_storage`.
   - Pada platform Android, data token otomatis dienkripsi menggunakan Android Keystore System (`EncryptedSharedPreferences`).
   - Token **tidak pernah** disimpan dalam `SharedPreferences` biasa yang tidak terenkripsi.
2. **Penyimpanan Dokumen PDF yang Diunduh:**
   - Berkas PDF yang diunduh disimpan di dalam direktori internal aplikasi (`path_provider`: `getApplicationDocumentsDirectory()`) atau folder `Downloads` publik hanya jika pengguna secara eksplisit memilih tombol ekspor.
   - Cache dokumen dibersihkan secara berkala atau saat pengguna melakukan logout.

---

## 6. Alur Pengguna (User Flow) & Rancangan Layar

```
+-----------------------------------------------------------+
|                       Splash Screen                       |
|   (Cek validitas token di flutter_secure_storage)         |
+-----------------------------+-----------------------------+
                              |
            +-----------------+-----------------+
            |                                   |
      (Token Tidak Ada/Invalid)           (Token Valid)
            v                                   v
+-----------------------+           +-----------------------+
|      Login Screen     |           |     Role Dispatcher   |
+-----------+-----------+           +-----------+-----------+
            |                                   |
            | Sukses Login                      |
            +-----------------+                 |
                              |                 |
            +-----------------+-----------------+
            |                                   |
            v                                   v
   (Jika Role: Klinik)              (Jika Role: Pengurus Cabang/Daerah/Pusat)
            |                                   |
+-----------v-----------+           +-----------v-----------+
| Main Shell (Anggota)  |           | Main Shell (Pengurus) |
| - Tab 1: Beranda      |           | - Tab 1: Dashboard KPI|
| - Tab 2: Status & SK  |           | - Tab 2: Data Anggota |
| - Tab 3: Sertifikat   |           | - Tab 3: Filter Wilayah|
| - Tab 4: Profil Akun  |           | - Tab 4: Profil Akun  |
+-----------------------+           +-----------------------+
```

### 6.1 Rancangan Layar Anggota (Klinik)
1. **Layar Beranda Anggota:**
   - Header identitas klinik dan nomor anggota resmi.
   - Kartu status keanggotaan (Badge hijau untuk Aktif/Approved, kuning untuk Proses Verifikasi).
   - Pintasan cepat unduh SK dan Sertifikat.
   - Pengumuman penting atau agenda ASKLIN.
2. **Layar Detail Profil Klinik:**
   - Informasi Badan Usaha / Badan Hukum, Alamat lengkap, RT/RW, Kecamatan, Kota, Provinsi.
   - Informasi Surat Izin Operasional (SIO) dan tanggal masa berlaku.
   - Data Penanggung Jawab dan SDM terdaftar.
3. **Layar SK & Sertifikat (Dokumen Legalitas):**
   - Tampilan visual dokumen dengan watermark resmi.
   - Tombol *Lihat Dokumen* (membuka in-app PDF viewer via `flutter_pdfview`).
   - Tombol *Unduh PDF* dengan progress bar indikator unduhan.

### 6.2 Rancangan Layar Pengurus (Cabang, Daerah, Pusat)
1. **Layar Dashboard Monitoring Adaptif:**
   - Header dinamik menampilkan nama wilayah wewenang (misal: "Pengurus Cabang Kab. Bekasi" atau "Pengurus Daerah Jawa Barat").
   - Kartu Ringkasan Metrik (Total Klinik, Klinik Terverifikasi, Menunggu Proses, Iuran Lunas).
   - Diagram Pie: Proporsi Klinik Pratama vs Klinik Utama.
   - Diagram Bar: Sebaran anggota per cabang (khusus level Daerah dan Pusat).
2. **Layar Penelusuran Anggota:**
   - Search bar (pencarian real-time nama klinik / nomor anggota).
   - Filter chip (Status: Semua, Disetujui, Menunggu).
   - Infinite scroll list anggota dengan *pull-to-refresh*.
3. **Layar Detail Klinik Anggota (View-Only bagi Pengurus):**
   - Menampilkan ringkasan profil klinik yang dipilih pengurus dalam wilayah kewenangannya.

---

## 7. Penanganan Kondisi Khusus (Edge Cases & Error Handling)

1. **Koneksi Terputus / Jaringan Lambat:**
   - Komponen visual `NoInternetWidget` dengan ilustrasi ramah dan tombol *Muat Ulang*.
2. **Empty State:**
   - Jika belum ada data anggota pada cabang tertentu, tampilkan `EmptyDataWidget` ("Belum ada data anggota terdaftar pada wilayah ini").
3. **Session Expired:**
   - Notifikasi snackbar ("Sesi Anda telah berakhir, silakan login kembali") dan redirect mulus ke login screen.
4. **Dokumen Belum Terbit:**
   - Jika klinik belum approved, tombol unduh SK/Sertifikat dalam kondisi disabled (*dimmed*) disertai penjelasan status verifikasi yang sedang berlangsung.

---

## 8. Persiapan Build Android & Publikasi Google Play Store

1. **Android Package Identity:**
   - Application ID / Package Name: `org.asklin.mobile`
   - Target SDK: Android 14 (API level 34+) sesuai kebijakan Google Play Store terbaru.
   - Minimum SDK: Android 6.0 (API level 23) untuk memastikan kompatibilitas 98%+ perangkat Android di Indonesia.
2. **Keamanan Build:**
   - R8 Code Shrinking dan ProGuard obfuscation diaktifkan pada `build.gradle` release mode.
   - Konfigurasi `network_security_config.xml` untuk memblokir cleartext HTTP pada mode release (hanya mengizinkan HTTPS).
3. **Signing Key & App Bundle:**
   - Pembuatan Java Keystore (`upload-keystore.jks`) terisolasi di luar git repository.
   - Build format: Android App Bundle (`.aab`) via command `flutter build appbundle --release`.
4. **Aset Toko Aplikasi (Play Store Checklist):**
   - App Icon resolusi tinggi (512x512 PNG).
   - Feature Graphic banner (1024x500 PNG).
   - Screenshot antarmuka smartphone (minimal 4 tangkapan layar utama).
   - Dokumen Kebijakan Privasi (Privacy Policy) yang dapat diakses publik.

---

## 9. Rencana Pembagian Sesi Implementasi Flutter

Pekerjaan implementasi aplikasi Flutter dibagi menjadi **10 sesi terstruktur dan berurutan**:

---

### Sesi FLUTTER-1: Inisialisasi Proyek & Struktur Arsitektur
1. **Nomor & Nama Sesi:** FLUTTER-1 — Inisialisasi Proyek Flutter & Setup Arsitektur Clean/Feature-First
2. **Tujuan:** Membuat proyek baru Flutter pada direktori terpisah (`c:\laragon\www\asklin-mobile`), konfigurasi Git terpisah, dan pengaturan struktur folder standar.
3. **Prasyarat:** Sesi API-1 s.d. API-8 pada Laravel telah selesai dan Flutter SDK telah terpasang di komputer.
4. **File/Modul yang Diperiksa/Diubah:**
   - `pubspec.yaml`
   - `lib/` root folder
5. **Langkah Implementasi Spesifik:**
   - Buat proyek baru via `flutter create --org org.asklin asklin-mobile`.
   - Susun struktur folder `core/` dan `features/`.
   - Daftarkan dependensi inti di `pubspec.yaml`: `flutter_bloc`, `dio`, `flutter_secure_storage`, `go_router`, `equatable`, `google_fonts`.
6. **File Baru yang Direncanakan:**
   - `lib/core/constants/app_colors.dart`
   - `lib/core/constants/app_constants.dart`
   - `lib/core/theme/app_theme.dart`
7. **File Existing yang Mungkin Berubah:**
   - `pubspec.yaml`, `lib/main.dart`
8. **Hal yang Tidak Boleh Diubah:**
   - Direktori Laravel eksisting.
9. **Risiko & Dependensi:**
   - Versi Flutter SDK lokal; gunakan versi stabil 3.x+.
10. **Daftar Pengujian:**
    - Perintah `flutter run` sukses menampilkan halaman starter kosong tanpa error dependency.
11. **Kriteria Penerimaan Terverifikasi:**
    - Struktur folder rapi dan build berhasil.
12. **Output / Dokumentasi:**
    - Proyek Flutter terinisialisasi sempurna.
13. **Kondisi Sebelum Lanjut ke Sesi Berikutnya:**
    - Build Android debug berjalan lancar di emulator/device.

---

### Sesi FLUTTER-2: Core Network, Storage & Interceptor Dio
1. **Nomor & Nama Sesi:** FLUTTER-2 — Konfigurasi Dio HTTP Client, Secure Storage & Interceptor
2. **Tujuan:** Menyiapkan client komunikasi HTTP yang aman dengan penanganan token bearer dan penangkap status 401.
3. **Prasyarat:** Sesi FLUTTER-1 selesai.
4. **File/Modul yang Diperiksa/Diubah:**
   - `lib/core/network/*`
   - `lib/core/storage/*`
5. **Langkah Implementasi Spesifik:**
   - Buat service `SecureStorageService` membungkus `flutter_secure_storage`.
   - Buat `DioClient` dengan `BaseOptions` (timeout 15 detik).
   - Implementasikan `AuthInterceptor` untuk inject header bearer token.
   - Implementasikan error mapping mengubah respon JSON backend menjadi exception terstruktur (`ApiException`).
6. **File Baru yang Direncanakan:**
   - `lib/core/network/api_client.dart`
   - `lib/core/network/api_interceptor.dart`
   - `lib/core/network/api_exceptions.dart`
   - `lib/core/storage/secure_storage_service.dart`
7. **File Existing yang Mungkin Berubah:**
   - `lib/main.dart`
8. **Hal yang Tidak Boleh Diubah:**
   - Endpoint backend kontrak.
9. **Risiko & Dependensi:**
   - Android secure storage crash pada perangkat lawas; tambahkan konfigurasi Android Options yang kompatibel.
10. **Daftar Pengujian:**
    - Uji baca/tulis token ke Secure Storage.
    - Uji injection header token pada request dummy.
11. **Kriteria Penerimaan Terverifikasi:**
    - HTTP Client siap mengirim dan menerima payload JSON.
12. **Output / Dokumentasi:**
    - Unit test sederhana untuk Network layer.
13. **Kondisi Sebelum Lanjut ke Sesi Berikutnya:**
    - Network module siap menerima integrasi API Auth.

---

### Sesi FLUTTER-3: Autentikasi, Login & Sesi Pengguna
1. **Nomor & Nama Sesi:** FLUTTER-3 — Fitur Login, Validasi Form & State Manajemen Sesi
2. **Tujuan:** Mengimplementasikan antarmuka Login, validasi input, integrasi endpoint `POST /api/v1/auth/login`, dan penyimpanan token.
3. **Prasyarat:** Sesi FLUTTER-2 selesai dan backend Sesi API-2 aktif.
4. **File/Modul yang Diperiksa/Diubah:**
   - `lib/features/auth/*`
5. **Langkah Implementasi Spesifik:**
   - Desain UI form login (Logo ASKLIN, input email, input password, tombol login).
   - Buat `AuthCubit` atau `AuthBloc` (`AuthInitial`, `AuthLoading`, `AuthAuthenticated`, `AuthFailure`).
   - Implementasikan penyimpanan profil user dan role saat login berhasil.
   - Sediakan tombol logout di menu pengaturan.
6. **File Baru yang Direncanakan:**
   - `lib/features/auth/presentation/screens/login_screen.dart`
   - `lib/features/auth/presentation/cubit/auth_cubit.dart`
   - `lib/features/auth/data/auth_repository.dart`
   - `lib/features/auth/data/models/user_model.dart`
7. **File Existing yang Mungkin Berubah:**
   - `lib/app.dart`
8. **Hal yang Tidak Boleh Diubah:**
   - Format kredensial akun existing di database.
9. **Risiko & Dependensi:**
   - Password salah atau server mati; tampilkan snackbar error yang manusiawi.
10. **Daftar Pengujian:**
    - Input email salah format menampilkan pesan validasi form.
    - Login sukses mengarahkan pengguna ke dashboard sesuai rolenya.
11. **Kriteria Penerimaan Terverifikasi:**
    - Alur login dan penyimpanan token terverifikasi 100%.
12. **Output / Dokumentasi:**
    - Layar login responsif di berbagai resolusi layar.
13. **Kondisi Sebelum Lanjut ke Sesi Berikutnya:**
    - User berhasil login dan token tersimpan di storage perangkat.

---

### Sesi FLUTTER-4: Routing & Role-Based Navigation
1. **Nomor & Nama Sesi:** FLUTTER-4 — Navigasi Adaptif & Role Dispatcher (go_router)
2. **Tujuan:** Memisahkan alur navigasi dan menu antara Anggota (Klinik) dan Pengurus (Cabang/Daerah/Pusat).
3. **Prasyarat:** Sesi FLUTTER-3 selesai.
4. **File/Modul yang Diperiksa/Diubah:**
   - `lib/core/navigation/*`
5. **Langkah Implementasi Spesifik:**
   - Konfigurasi `go_router` dengan redirect guard berbasis state auth.
   - Buat `RoleDispatcherScreen`: jika role `Klinik` arahkan ke `AnggotaShell`, jika `Admin Cabang/Daerah/Pusat` arahkan ke `PengurusShell`.
   - Buat bottom navigation bar untuk masing-masing tipe shell.
6. **File Baru yang Direncanakan:**
   - `lib/core/navigation/app_router.dart`
   - `lib/features/home/presentation/screens/anggota_shell.dart`
   - `lib/features/home/presentation/screens/pengurus_shell.dart`
7. **File Existing yang Mungkin Berubah:**
   - `lib/app.dart`
8. **Hal yang Tidak Boleh Diubah:**
   - Batasan peran dari backend.
9. **Risiko & Dependensi:**
   - Role tidak dikenali; sediakan fallback error screen yang informatif.
10. **Daftar Pengujian:**
    - Login akun Klinik masuk ke tampilan anggota.
    - Login akun Pengurus Cabang masuk ke tampilan pengurus.
11. **Kriteria Penerimaan Terverifikasi:**
    - Navigasi berpindah mulus tanpa glitch visual.
12. **Output / Dokumentasi:**
    - Shell layout navigasi kedua role aktif.
13. **Kondisi Sebelum Lanjut ke Sesi Berikutnya:**
    - Shell navigasi teruji untuk akun Klinik dan Pengurus.

---

### Sesi FLUTTER-5: Beranda & Profil Anggota (Klinik)
1. **Nomor & Nama Sesi:** FLUTTER-5 — Layar Beranda, Status Keanggotaan & Profil Klinik
2. **Tujuan:** Menampilkan data identitas klinik, status verifikasi keanggotaan, izin SIO, dan informasi penanggung jawab.
3. **Prasyarat:** Sesi FLUTTER-4 selesai dan backend Sesi API-4 aktif.
4. **File/Modul yang Diperiksa/Diubah:**
   - `lib/features/anggota/*`
5. **Langkah Implementasi Spesifik:**
   - Integrasikan endpoint `GET /api/v1/anggota/profil` dan `GET /api/v1/anggota/status-keanggotaan`.
   - Tampilkan kartu ringkasan nomor anggota, nama klinik, jenis klinik, dan tanggal verifikasi.
   - Sediakan indikator tahapan pendaftaran (Timeline status verifikasi).
   - Implementasikan *pull-to-refresh*.
6. **File Baru yang Direncanakan:**
   - `lib/features/anggota/presentation/screens/beranda_anggota_screen.dart`
   - `lib/features/anggota/presentation/screens/profil_klinik_screen.dart`
   - `lib/features/anggota/presentation/cubit/anggota_cubit.dart`
   - `lib/features/anggota/data/models/anggota_model.dart`
7. **File Existing yang Mungkin Berubah:**
   - `lib/features/home/presentation/screens/anggota_shell.dart`
8. **Hal yang Tidak Boleh Diubah:**
   - Kontrak data anggota dari backend.
9. **Risiko & Dependensi:**
   - Data anggota null bagi user baru; tampilkan skeleton loading dan empty state yang informatif.
10. **Daftar Pengujian:**
    - Data nama klinik, alamat, dan nomor anggota tampil presisi.
11. **Kriteria Penerimaan Terverifikasi:**
    - Profil klinik termuat lengkap dengan format tanggal Indonesia yang rapi.
12. **Output / Dokumentasi:**
    - Layar beranda dan profil anggota selesai.
13. **Kondisi Sebelum Lanjut ke Sesi Berikutnya:**
    - Data anggota terverifikasi tampil sinkron dengan database web.

---

### Sesi FLUTTER-6: Modul Dokumen SK & Sertifikat (In-App PDF & Unduhan)
1. **Nomor & Nama Sesi:** FLUTTER-6 — Layar Dokumen Digital, In-App PDF Viewer & Pengunduhan SK/Sertifikat
2. **Tujuan:** Menampilkan daftar dokumen legalitas, preview PDF in-app, dan menyimpan berkas ke storage perangkat.
3. **Prasyarat:** Sesi FLUTTER-5 selesai dan backend Sesi API-5 aktif.
4. **File/Modul yang Diperiksa/Diubah:**
   - `lib/features/dokumen/*`
5. **Langkah Implementasi Spesifik:**
   - Tambahkan package `flutter_pdfview` dan `open_filex` pada `pubspec.yaml`.
   - Integrasikan endpoint `GET /api/v1/anggota/dokumen-saya`.
   - Buat tombol aksi unduh PDF dengan status progress download.
   - Buat layar viewer PDF in-app dengan fitur zoom dan share.
6. **File Baru yang Direncanakan:**
   - `lib/features/dokumen/presentation/screens/dokumen_screen.dart`
   - `lib/features/dokumen/presentation/screens/pdf_viewer_screen.dart`
   - `lib/features/dokumen/presentation/cubit/dokumen_cubit.dart`
   - `lib/features/dokumen/data/dokumen_repository.dart`
7. **File Existing yang Mungkin Berubah:**
   - `pubspec.yaml`
8. **Hal yang Tidak Boleh Diubah:**
   - Logika izin download di sisi backend.
9. **Risiko & Dependensi:**
   - Izin storage Android (Scoped Storage Android 10+); gunakan internal app storage agar tidak memerlukan izin berbahaya (`WRITE_EXTERNAL_STORAGE`).
10. **Daftar Pengujian:**
    - Preview PDF berjalan mulus tanpa layar hitam.
    - File tersimpan di cache dan dapat dibuka kembali secara offline.
11. **Kriteria Penerimaan Terverifikasi:**
    - SK dan sertifikat dapat dilihat dan diunduh sempurna di Android.
12. **Output / Dokumentasi:**
    - Modul dokumen legalitas terverifikasi.
13. **Kondisi Sebelum Lanjut ke Sesi Berikutnya:**
    - Fungsi download dokumen teruji stabil pada perangkat fisik Android.

---

### Sesi FLUTTER-7: Dashboard Monitoring Pengurus (Cabang & Daerah)
1. **Nomor & Nama Sesi:** FLUTTER-7 — Dashboard Monitoring Pengurus Cabang & Daerah
2. **Tujuan:** Menampilkan indikator KPI, kartu statistik, dan grafik jenis klinik untuk Pengurus Cabang dan Pengurus Daerah.
3. **Prasyarat:** Sesi FLUTTER-4 selesai dan backend Sesi API-6 aktif.
4. **File/Modul yang Diperiksa/Diubah:**
   - `lib/features/pengurus/*`
5. **Langkah Implementasi Spesifik:**
   - Tambahkan package grafik `fl_chart` pada `pubspec.yaml`.
   - Integrasikan endpoint `GET /api/v1/pengurus/dashboard`.
   - Tampilkan kartu metrik: Total Anggota, Disetujui, Menunggu, Iuran Lunas.
   - Render pie chart proporsi jenis klinik (Utama vs Pratama).
   - Untuk Pengurus Daerah: tambahkan filter dropdown kabupaten/kota di provinsinya.
6. **File Baru yang Direncanakan:**
   - `lib/features/pengurus/presentation/screens/dashboard_pengurus_screen.dart`
   - `lib/features/pengurus/presentation/widgets/metric_card_widget.dart`
   - `lib/features/pengurus/presentation/cubit/pengurus_dashboard_cubit.dart`
   - `lib/features/pengurus/data/models/dashboard_stats_model.dart`
7. **File Existing yang Mungkin Berubah:**
   - `lib/features/home/presentation/screens/pengurus_shell.dart`
8. **Hal yang Tidak Boleh Diubah:**
   - Validasi wilayah backend.
9. **Risiko & Dependensi:**
   - Grafik crash jika data bernilai 0; pastikan ada fallback tampilan empty data.
10. **Daftar Pengujian:**
    - Pengurus Cabang melihat metrik khusus kotanya.
    - Pengurus Daerah dapat mengganti filter kota dan data grafik ter-refresh.
11. **Kriteria Penerimaan Terverifikasi:**
    - Angka statistik di mobile app identik dengan dashboard web.
12. **Output / Dokumentasi:**
    - Layar dashboard monitoring pengurus aktif.
13. **Kondisi Sebelum Lanjut ke Sesi Berikutnya:**
    - Dashboard pengurus cabang dan daerah berjalan stabil.

---

### Sesi FLUTTER-8: Dashboard Nasional & Filter Lanjutan (Pengurus Pusat)
1. **Nomor & Nama Sesi:** FLUTTER-8 — Dashboard Eksekutif Pengurus Pusat & Penelusuran Anggota
2. **Tujuan:** Menyediakan dashboard skala nasional untuk Pengurus Pusat serta daftar anggota dengan pencarian dan pagination.
3. **Prasyarat:** Sesi FLUTTER-7 selesai dan backend Sesi API-7 aktif.
4. **File/Modul yang Diperiksa/Diubah:**
   - `lib/features/pengurus/presentation/screens/anggota_list_screen.dart`
5. **Langkah Implementasi Spesifik:**
   - Integrasikan filter multi-provinsi untuk Pengurus Pusat.
   - Implementasikan layar daftar anggota dengan `ListView.builder`, search bar, dan pagination infinite scrolling.
   - Buat layar detail anggota (ringkasan view-only) saat kartu klinik diklik.
6. **File Baru yang Direncanakan:**
   - `lib/features/pengurus/presentation/screens/anggota_list_screen.dart`
   - `lib/features/pengurus/presentation/screens/detail_klinik_pengurus_screen.dart`
   - `lib/features/pengurus/presentation/cubit/anggota_list_cubit.dart`
7. **File Existing yang Mungkin Berubah:**
   - `lib/features/pengurus/data/pengurus_repository.dart`
8. **Hal yang Tidak Boleh Diubah:**
   - Struktur database backend.
9. **Risiko & Dependensi:**
   - Overfetching data daftar anggota; batasi `per_page = 15`.
10. **Daftar Pengujian:**
    - Pencarian teks nama klinik memfilter data secara dinamis.
    - Scroll ke bawah memicu pemuatan halaman berikutnya (*next page*).
11. **Kriteria Penerimaan Terverifikasi:**
    - Penelusuran data anggota berjalan mulus tanpa lag.
12. **Output / Dokumentasi:**
    - Modul monitoring nasional dan daftar anggota selesai.
13. **Kondisi Sebelum Lanjut ke Sesi Berikutnya:**
    - Seluruh fitur pengurus terverifikasi berfungsi penuh.

---

### Sesi FLUTTER-9: Penguatan UX, Offline Handling & Automated Testing
1. **Nomor & Nama Sesi:** FLUTTER-9 — Polishing Antarmuka, Penanganan Offline & Pengujian Otomatis
2. **Tujuan:** Memastikan aplikasi tahan banting terhadap kondisi jaringan buruk, penanganan error ramah pengguna, dan pengujian unit/widget.
3. **Prasyarat:** Sesi FLUTTER-1 s.d. FLUTTER-8 selesai.
4. **File/Modul yang Diperiksa/Diubah:**
   - `test/*`
   - `lib/core/widgets/*`
5. **Langkah Implementasi Spesifik:**
   - Tambahkan animasi transisi dan Shimmer loading effect pada setiap kartu.
   - Buat widget global penanganan error (`NoConnectionWidget`, `ServerErrorWidget`).
   - Tulis unit test untuk BLoC/Cubit (`auth_cubit_test.dart`, `anggota_cubit_test.dart`).
   - Tulis widget test untuk layar login dan dashboard.
6. **File Baru yang Direncanakan:**
   - `test/features/auth/auth_cubit_test.dart`
   - `test/features/anggota/anggota_cubit_test.dart`
   - `lib/core/widgets/shimmer_loading.dart`
7. **File Existing yang Mungkin Berubah:**
   - Seluruh layar presentasi
8. **Hal yang Tidak Boleh Diubah:**
   - Alur bisnis utama yang telah disetujui.
9. **Risiko & Dependensi:**
   - Memory leak saat stream/cubit tidak di-close; lakukan audit memory.
10. **Daftar Pengujian:**
    - Uji mode pesawat (offline): aplikasi menampilkan peringatan tanpa crash.
    - Seluruh unit test lolos (*green*).
11. **Kriteria Penerimaan Terverifikasi:**
    - Bebas dari error *unhandled exception* atau *Red Screen of Death*.
12. **Output / Dokumentasi:**
    - Laporan hasil pengujian aplikasi Flutter.
13. **Kondisi Sebelum Lanjut ke Sesi Berikutnya:**
    - Aplikasi stabil dan siap untuk proses build release Android.

---

### Sesi FLUTTER-10: Build Android Release, Keystore Signing & Persiapan Google Play Store
1. **Nomor & Nama Sesi:** FLUTTER-10 — Konfigurasi Release Android, Signing Keystore & Persiapan Play Store
2. **Tujuan:** Menyiapkan konfigurasi build production, icon aplikasi, splash screen, ProGuard rules, dan menghasilkan file Android App Bundle (`.aab`).
3. **Prasyarat:** Sesi FLUTTER-9 selesai.
4. **File/Modul yang Diperiksa/Diubah:**
   - `android/app/build.gradle`
   - `android/app/src/main/AndroidManifest.xml`
   - `android/key.properties`
5. **Langkah Implementasi Spesifik:**
   - Generate production upload keystore (`upload-keystore.jks`).
   - Konfigurasi `signingConfigs` pada `android/app/build.gradle`.
   - Konfigurasi launcher icon resmi ASKLIN via `flutter_launcher_icons`.
   - Update `versionCode` dan `versionName` di `pubspec.yaml` (misal `1.0.0+1`).
   - Jalankan kompilasi release: `flutter build appbundle --release`.
   - Uji instalasi release bundle ke perangkat Android fisik via bundletool.
6. **File Baru yang Direncanakan:**
   - `android/key.properties` (masuk ke `.gitignore`)
   - `android/app/proguard-rules.pro`
7. **File Existing yang Mungkin Berubah:**
   - `android/app/build.gradle`, `pubspec.yaml`
8. **Hal yang Tidak Boleh Diubah:**
   - Rahasia keystore tidak boleh dicommit ke git.
9. **Risiko & Dependensi:**
   - Crash akibat obfuscation R8; pastikan model JSON di-keep di ProGuard rules jika diperlukan.
10. **Daftar Pengujian:**
    - Verifikasi file `.aab` berhasil ter-generate tanpa error kompilasi.
    - Uji coba buka aplikasi hasil build release pada HP Android fisik (Android 10 - 14).
11. **Kriteria Penerimaan Terverifikasi:**
    - File `.aab` siap diunggah ke Google Play Console Internal Testing track.
12. **Output / Dokumentasi:**
    - Berkas `app-release.aab` dan panduan rilis Play Store.
13. **Kondisi Sebelum Lanjut ke Sesi Berikutnya:**
    - Blueprint dan seluruh tahapan pengembangan selesai dan siap dipresentasikan.

