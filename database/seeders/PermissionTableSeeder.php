<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionTableSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk permissions.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'secret',
            'fasilitas-list', 'fasilitas-create', 'fasilitas-edit', 'fasilitas-delete',
            'fasilitas-klinik-list', 'fasilitas-klinik-create', 'fasilitas-klinik-edit', 'fasilitas-klinik-delete',
            'layanan-list', 'layanan-create', 'layanan-edit', 'layanan-delete',
            'kategori-berita-list', 'kategori-berita-create', 'kategori-berita-edit', 'kategori-berita-delete',
            'kategori-event-list', 'kategori-event-create', 'kategori-event-edit', 'kategori-event-delete',
            'kategori-pembayaran-list', 'kategori-pembayaran-create', 'kategori-event-edit', 'kategori-event-delete',
            'konas-peserta-list', 'konas-peserta-create', 'konas-peserta-edit', 'konas-peserta-delete',
            'konas-master-hotel-list', 'konas-master-hotel-create', 'konas-master-hotel-edit', 'konas-master-hotel-delete',
            'konas-tipe-hotel-list', 'konas-tipe-hotel-create', 'konas-tipe-hotel-edit', 'konas-tipe-hotel-delete',
            'konas-booking-hotel-list', 'konas-booking-hotel-create', 'konas-booking-hotel-edit', 'konas-booking-hotel-delete',
            'konas-data-penerbangan-list', 'konas-data-penerbangan-create', 'konas-data-penerbangan-edit', 'konas-data-penerbangan-delete',
            'konas-partner-list', 'konas-partner-create', 'konas-partner-edit', 'konas-partner-delete',
            'verifikasi-anggota',
            'data-anggota',
            'kerjasama-asuransi',
            'expired-sio',
            'agenda-list', 'agenda-create', 'agenda-edit', 'agenda-delete',
            'sertifikat-list', 'sertifikat-create', 'sertifikat-edit', 'sertifikat-delete',
            'expired-sertifikat',
            'pembayaran-list', 'pembayaran-create', 'pembayaran-edit', 'pembayaran-delete',
            'pembayaran-pusat-list', 'pembayaran-pusat-create', 'pembayaran-pusat-edit', 'pembayaran-pusat-delete',
            'struktur-organisasi-list', 'struktur-organisasi-create', 'struktur-organisasi-edit', 'struktur-organisasi-delete',
            'jenis-surat-list', 'jenis-surat-create', 'jenis-surat-edit', 'jenis-surat-delete',
            'penomoran-surat-list', 'penomoran-surat-edit',
            'surat-keluar-list', 'surat-keluar-create', 'surat-keluar-edit', 'surat-keluar-delete',
            'surat-tugas-list',
            'surat-undangan-list',
            'slider-list', 'slider-create', 'slider-edit', 'slider-delete',
            'berita-list', 'berita-create', 'berita-edit', 'berita-delete',
            'event-list', 'event-create', 'event-edit', 'event-delete',
            'gallery-list', 'gallery-create', 'gallery-edit', 'gallery-delete',
            'kontak-list',  'kontak-delete',
            'laporan-pusat',
            'laporan-daerah',
            'laporan-cabang'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}
