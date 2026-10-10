<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterKetuaUmum;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class SkAndMasterKetuaUmumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Tambah Master Data Ketua Umum awal (dr. Eddi Junaidi, SpOG., SH., MKes)
        MasterKetuaUmum::firstOrCreate(
            ['nama_lengkap' => 'dr. Eddi Junaidi, SpOG., SH., MKes'],
            [
                'periode_mulai' => '2023',
                'periode_selesai' => '2028',
                'file_ttd' => null,
                'status' => 'aktif',
                'keterangan' => 'Ketua Umum Pengurus Pusat ASKLIN Periode 2023 - 2028',
            ]
        );

        // 2. Daftar Permission Baru untuk SK dan Master Ketua Umum
        $newPermissions = [
            'master-ketua-umum-list',
            'master-ketua-umum-create',
            'master-ketua-umum-edit',
            'master-ketua-umum-delete',
            'sk-pengurus-approval-daerah',
            'sk-pengurus-approval-sekjen',
            'sk-pengurus-approval-ketum',
        ];

        foreach ($newPermissions as $permName) {
            Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
        }

        // 3. Pastikan permission diberikan ke Role yang sesuai
        $superadmin = Role::where('name', 'Superadmin')->first();
        if ($superadmin) {
            $superadmin->syncPermissions(Permission::all());
        }

        $adminPusat = Role::where('name', 'Admin Pusat')->first();
        if ($adminPusat) {
            $adminPusat->givePermissionTo([
                'master-ketua-umum-list',
                'master-ketua-umum-create',
                'master-ketua-umum-edit',
                'master-ketua-umum-delete',
                'sk-pengurus-list',
                'sk-pengurus-create',
                'sk-pengurus-edit',
                'sk-pengurus-delete',
                'struktur-organisasi-list',
                'struktur-organisasi-create',
                'struktur-organisasi-edit',
                'struktur-organisasi-delete',
            ]);
        }

        $adminDaerah = Role::where('name', 'Admin Daerah')->first();
        if ($adminDaerah) {
            $adminDaerah->givePermissionTo([
                'sk-pengurus-list',
                'sk-pengurus-approval-daerah',
                'struktur-organisasi-list',
            ]);
        }

        $adminCabang = Role::where('name', 'Admin Cabang')->first();
        if ($adminCabang) {
            $adminCabang->givePermissionTo([
                'sk-pengurus-list',
                'sk-pengurus-create',
                'struktur-organisasi-list',
                'struktur-organisasi-create',
                'struktur-organisasi-edit',
            ]);
        }

        $sekjen = Role::where('name', 'Sekjen')->first();
        if ($sekjen) {
            $sekjen->givePermissionTo([
                'sk-pengurus-list',
                'sk-pengurus-edit',
                'sk-pengurus-approval-sekjen',
                'master-ketua-umum-list',
                'struktur-organisasi-list',
            ]);
        }

        $ketum = Role::where('name', 'Ketua Umum')->first();
        if ($ketum) {
            $ketum->givePermissionTo([
                'sk-pengurus-list',
                'sk-pengurus-approval-ketum',
                'master-ketua-umum-list',
                'struktur-organisasi-list',
            ]);
        }
    }
}

