# PANDUAN INTEGRASI REST API ASKLIN MOBILE (V1)

**Versi API:** 1.0.0  
**Base URL Development:** `http://10.0.2.2:8000/api/v1` (Android Emulator) / `http://<IP-LAN>:8000/api/v1` (Physical Device)  
**Base URL Production:** `https://asklin.org/api/v1`  
**Otentikasi:** Laravel Sanctum Bearer Token (`Authorization: Bearer <token>`)  
**Header Wajib:**  
```http
Accept: application/json
Content-Type: application/json
```

---

## 1. Format Respons Standar

### Respons Sukses
```json
{
  "success": true,
  "message": "Pesan deskriptif sukses",
  "data": { ... }
}
```

### Respons Gagal / Validasi
```json
{
  "success": false,
  "message": "Validasi data gagal.",
  "errors": {
    "email": ["Alamat email wajib diisi."]
  }
}
```

---

## 2. Modul Autentikasi (`/auth`)

### 2.1 Login
- **Endpoint:** `POST /api/v1/auth/login`
- **Rate Limit:** 5 request / menit (Mencegah brute-force)
- **Request Body:**
```json
{
  "email": "klinik@asklin.org",
  "password": "password123",
  "device_name": "samsung_galaxy_s23"
}
```
- **Respons (200 OK):**
```json
{
  "success": true,
  "message": "Login berhasil.",
  "data": {
    "token": "1|qWeRtYuIoP...",
    "token_type": "Bearer",
    "user": {
      "id": 15,
      "name": "Klinik Sehat Utama",
      "email": "klinik@asklin.org",
      "primary_role": "Klinik",
      "roles": ["Klinik"],
      "wilayah": {
        "provinsi_code": "32",
        "provinsi_name": "JAWA BARAT",
        "kota_code": "3216",
        "kota_name": "KABUPATEN BEKASI",
        "kecamatan_code": "321601",
        "kelurahan_code": "3216010001"
      },
      "anggota": {
        "id": 8,
        "no_anggota": "3216010001-00012.1.1",
        "nama_klinik": "Klinik Sehat Utama",
        "status": "approved",
        "jenis_klinik": "Pratama",
        "status_pembayaran": "1",
        "is_approved": true,
        "is_lunas": true
      }
    }
  }
}
```

### 2.2 Registrasi Akun Mandiri Klinik
- **Endpoint:** `POST /api/v1/auth/register`
- **Rate Limit:** 5 request / menit
- **Request Body:**
```json
{
  "name": "dr. Budi Santoso",
  "email": "budi@kliniksehat.co.id",
  "password": "Password123",
  "password_confirmation": "Password123",
  "nama_klinik": "Klinik Pratama Budi Sehat",
  "phone": "081234567890",
  "provinsi": "32",
  "kota": "3216",
  "device_name": "samsung_galaxy_s23"
}
```
- **Respons (201 Created):**
```json
{
  "success": true,
  "message": "Registrasi berhasil. Akun Anda telah siap digunakan.",
  "data": {
    "token": "2|AbCdEf123...",
    "token_type": "Bearer",
    "user": {
      "id": 16,
      "name": "dr. Budi Santoso",
      "email": "budi@kliniksehat.co.id",
      "primary_role": "Klinik",
      "roles": ["Klinik"],
      "wilayah": { ... },
      "anggota": { ... }
    }
  }
}
```

### 2.3 Get Profil User Login (Me)
- **Endpoint:** `GET /api/v1/auth/me`
- **Header:** `Authorization: Bearer <token>`
- **Respons (200 OK):** Mengembalikan data user dan ringkasan keanggotaan klinik.

### 2.4 Logout
- **Endpoint:** `POST /api/v1/auth/logout`
- **Header:** `Authorization: Bearer <token>`
- **Respons (200 OK):**
```json
{
  "success": true,
  "message": "Logout berhasil dan sesi token telah dicabut.",
  "data": null
}
```

---

## 3. Modul Anggota / Klinik (`/anggota`)

### 3.1 Profil Lengkap Klinik
- **Endpoint:** `GET /api/v1/anggota/profil`
- **Header:** `Authorization: Bearer <token>`
- **Deskripsi:** Mengembalikan profil lengkap klinik, alamat, kontak, perizinan SIO, dan daftar dokter/SDM.

### 3.2 Status Keanggotaan & Alur Verifikasi
- **Endpoint:** `GET /api/v1/anggota/status-keanggotaan`
- **Header:** `Authorization: Bearer <token>`
- **Respons (200 OK):**
```json
{
  "success": true,
  "message": "Status keanggotaan berhasil diambil.",
  "data": {
    "id": 8,
    "no_anggota": "3216010001-00012.1.1",
    "status": "approved",
    "status_label": "Anggota Resmi ASKLIN",
    "status_pembayaran": "1",
    "is_approved": true,
    "is_lunas": true,
    "keterangan_perbaikan": null,
    "tgl_akhir_ijin": "2027-05-10",
    "timeline": [
      { "step": 1, "title": "Pendaftaran & Pengisian Data", "is_done": true, "date": "10-02-2023 10:15" },
      { "step": 2, "title": "Verifikasi Pengurus Cabang", "is_done": true, "date": "12-02-2023 14:20" },
      { "step": 3, "title": "Verifikasi & Iuran Pembayaran Pusat", "is_done": true, "date": null },
      { "step": 4, "title": "Persetujuan Final & Penerbitan SK/Sertifikat", "is_done": true, "date": "15-02-2023 09:30" }
    ]
  }
}
```

### 3.3 Daftar Dokumen Keanggotaan
- **Endpoint:** `GET /api/v1/anggota/dokumen-saya`
- **Header:** `Authorization: Bearer <token>`
- **Respons (200 OK):** Daftar metadata ketersediaan SK Anggota dan Sertifikat beserta URL unduhan.

---

## 4. Modul Dokumen Digital (`/dokumen`)

### 4.1 Unduh SK Anggota PDF
- **Endpoint:** `GET /api/v1/dokumen/sk-anggota/download`
- **Header:** `Authorization: Bearer <token>`
- **Respons (200 OK):** Stream binary PDF (`Content-Type: application/pdf`).
- **Keamanan:** Anti-IDOR. Jika pendaftaran belum disetujui, mengembalikan HTTP 403 Forbidden.

### 4.2 Unduh Sertifikat Anggota PDF
- **Endpoint:** `GET /api/v1/dokumen/sertifikat/download`
- **Header:** `Authorization: Bearer <token>`
- **Respons (200 OK):** Stream binary PDF (`Content-Type: application/pdf`).
- **Keamanan:** Anti-IDOR. Jika iuran belum lunas (`status_pembayaran != '1'`), mengembalikan HTTP 403 Forbidden.

---

## 5. Modul Pengurus (`/pengurus`)

### 5.1 Dashboard Monitoring Adaptif Role
- **Endpoint:** `GET /api/v1/pengurus/dashboard`
- **Header:** `Authorization: Bearer <token>`
- **Deskripsi:**
  - Role Admin Cabang: Otomatis dibatasi pada kota user login.
  - Role Admin Daerah: Otomatis dibatasi pada provinsi user login (bisa filter per kota).
  - Role Nasional: Bebas filter nasional, provinsi, atau kota.
- **Respons (200 OK):**
```json
{
  "success": true,
  "message": "Data dashboard monitoring berhasil diambil.",
  "data": {
    "scope": {
      "level": "cabang",
      "kota_name": "KABUPATEN BEKASI",
      "is_locked_kota": true
    },
    "kpi_stats": {
      "total_anggota": 142,
      "disetujui": 128,
      "menunggu_verifikasi": 10,
      "perlu_perbaikan": 4,
      "iuran_lunas": 120,
      "iuran_tertunda": 22
    },
    "charts": { ... }
  }
}
```

### 5.2 Daftar Anggota dalam Wilayah
- **Endpoint:** `GET /api/v1/pengurus/anggota`
- **Parameter Query:**
  - `search` (string, optional: mencari nama klinik / nomor anggota)
  - `status` (string, optional: approved, waiting, dll)
  - `jenis_klinik` (string, optional: Utama / Pratama)
  - `page` (integer, default: 1)
  - `per_page` (integer, default: 15)
- **Respons (200 OK):** JSON data terpaginasi (`data`, `meta.current_page`, `meta.last_page`, `meta.total`).

### 5.3 Detail Anggota
- **Endpoint:** `GET /api/v1/pengurus/anggota/{id}`
- **Keamanan:** Jika ID klinik berada di luar kabupaten/kota kewenangan Admin Cabang, server otomatis menolak dengan **HTTP 403 Forbidden**.

---

## 6. Modul Referensi Wilayah (`/wilayah`)

- `GET /api/v1/wilayah/provinsi` → Daftar seluruh provinsi se-Indonesia.
- `GET /api/v1/wilayah/kota?provinsi_code={code}` → Daftar kabupaten/kota.
- `GET /api/v1/wilayah/kecamatan?city_code={code}` → Daftar kecamatan.
- `GET /api/v1/wilayah/kelurahan?district_code={code}` → Daftar kelurahan/desa.

---

## 7. Modul Berita & Artikel (`/berita`)

### 7.1 Daftar Berita Publik Berpaginasi
- **Endpoint:** `GET /api/v1/berita`
- **Parameter Query:**
  - `search` (string, optional: mencari di judul dan konten)
  - `kategori_id` (integer, optional: filter berdasarkan kategori)
  - `page` (integer, default: 1)
  - `per_page` (integer, default: 10, max: 50)
- **Respons (200 OK):**
```json
{
  "success": true,
  "message": "Daftar berita berhasil diambil.",
  "data": [
    {
      "id": 1,
      "judul": "Sosialisasi Standar Akreditasi Klinik Paripurna",
      "slug": "sosialisasi-standar-akreditasi-klinik-paripurna",
      "tanggal": "2026-10-13",
      "kategori": {
        "id": 1,
        "nama": "Akreditasi"
      },
      "excerpt": "ASKLIN menyelenggarakan panduan lengkap persiapan akreditasi...",
      "thumb_url": "http://asklin.test/assets/images/berita/thumbnails/berita-1.jpg",
      "lokasi": "Jakarta"
    }
  ],
  "kategori": [
    { "id": 1, "nama": "Akreditasi" },
    { "id": 2, "nama": "Regulasi" }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 10,
    "total": 28
  }
}
```

### 7.2 Detail Berita
- **Endpoint:** `GET /api/v1/berita/{identifier}`
- **Parameter:** `{identifier}` dapat berupa slug (`path`) atau ID berita.
- **Respons (200 OK):**
```json
{
  "success": true,
  "message": "Detail berita berhasil diambil.",
  "data": {
    "id": 1,
    "judul": "Sosialisasi Standar Akreditasi Klinik Paripurna",
    "slug": "sosialisasi-standar-akreditasi-klinik-paripurna",
    "tanggal": "2026-10-13",
    "lokasi": "Jakarta",
    "kategori": { "id": 1, "nama": "Akreditasi" },
    "thumb_url": "http://asklin.test/assets/images/berita/thumbnails/berita-1.jpg",
    "konten_html": "<p>Isi lengkap...</p>",
    "konten_bersih": "Isi lengkap...",
    "total_visits": 142,
    "galeri_images": [],
    "artikel_terkait": [ ... ]
  }
}
```

---

## 8. Modul Agenda & Kegiatan (`/agenda`)

### 8.1 Daftar Agenda Kegiatan (Tab Upcoming vs Past)
- **Endpoint:** `GET /api/v1/agenda`
- **Parameter Query:**
  - `tab` (string, default: `all`, opsi: `upcoming`, `past`, `all`)
  - `kategori_id` (integer, optional)
  - `search` (string, optional)
  - `page` (integer, default: 1)
  - `per_page` (integer, default: 10, max: 50)
- **Respons (200 OK):**
```json
{
  "success": true,
  "message": "Daftar agenda kegiatan berhasil diambil.",
  "data": [
    {
      "id": 2,
      "judul": "Seminar Kesehatan dalam Rangka Pelantikan",
      "slug": "seminar-kesehatan-dalam-rangka-pelantikan",
      "kategori": { "id": 1, "nama": "Seminar" },
      "excerpt": "Undangan Kepada Seluruh Klinik Anggota ASKLIN...",
      "gambar_url": "http://asklin.test/assets/images/events/events-1.jpg",
      "tanggal_mulai": "2025-06-30T22:57:00.000000Z",
      "tanggal_selesai": "2025-06-30T22:57:00.000000Z",
      "tanggal_formatted": "30 Juni 2025",
      "jam_formatted": "22:57 - 22:57 WIB",
      "is_expired": false,
      "is_ongoing": false,
      "status_pill": "Pendaftaran Buka",
      "status_pill_color": "green",
      "lokasi": "ASKLIN Indonesia"
    }
  ],
  "kategori": [
    { "id": 1, "nama": "Seminar" },
    { "id": 2, "nama": "Kongres" }
  ],
  "active_tab": "upcoming",
  "meta": { ... }
}
```

### 8.2 Detail Agenda Kegiatan
- **Endpoint:** `GET /api/v1/agenda/{identifier}`
- **Parameter:** `{identifier}` dapat berupa slug (`path`) atau ID event.
- **Respons (200 OK):** Mengembalikan data event lengkap beserta `konten_html`, `konten_bersih`, dan `agenda_terkait`.

---

## 9. Modul Direktori Struktur Organisasi (`/organisasi`)

### 9.1 Direktori Kepengurusan Nasional (DPP, DPD, DPC)
- **Endpoint:** `GET /api/v1/organisasi/struktur`
- **Parameter Query:**
  - `tingkat` (string, default: `pusat`, opsi: `pusat`, `daerah` / `dpd`, `cabang` / `dpc`)
  - `provinsi_id` (string, optional: filter DPD/DPC berdasarkan kode provinsi)
  - `kota_id` (string, optional: filter DPC berdasarkan kode kota)
- **Respons (200 OK - tingkat=pusat):**
```json
{
  "success": true,
  "message": "Data Pengurus Pusat ASKLIN berhasil dimuat.",
  "data": {
    "ringkasan": {
      "dpp_count": 1,
      "dpd_count": 34,
      "dpc_count": 152
    },
    "tingkat": "pusat",
    "detail": {
      "id": 1,
      "nama_struktur": "Pengurus Pusat",
      "periode": "2023 - 2028",
      "alamat_sekretariat": "Jl. Pinang Ranti II no.1A, Makasar, Jakarta Timur, DKI Jakarta",
      "email_sekretariat": "pp_asklin@asklin.org",
      "telp_sekretariat": "0218098928",
      "provinsi": "DKI JAKARTA",
      "kota": "KOTA JAKARTA TIMUR",
      "status": "disetujui"
    },
    "pengurus": [
      {
        "id": 238,
        "jabatan": "Dewan Pembina",
        "nama_lengkap": "dr. Djamal Abdul Muis, SH.MH.",
        "foto_url": null,
        "urutan": 1,
        "status": "aktif"
      }
    ]
  }
}
```
- **Respons (200 OK - tingkat=daerah):** Mengembalikan ringkasan statistik dan `daftar_dpd` per provinsi.
- **Respons (200 OK - tingkat=cabang):** Mengembalikan ringkasan statistik dan `daftar_dpc` berpaginasi.


