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

### 2.2 Get Profil User Login (Me)
- **Endpoint:** `GET /api/v1/auth/me`
- **Header:** `Authorization: Bearer <token>`
- **Respons (200 OK):** Mengembalikan data user dan ringkasan keanggotaan klinik.

### 2.3 Logout
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

