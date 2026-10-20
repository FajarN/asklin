<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PemilihanPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'pemilihan-list',
            'pemilihan-create',
            'pemilihan-edit',
            'pemilihan-delete',
            'pemilihan-vote',
            'pemilihan-dashboard',
            'pemilihan-laporan',
            'pemilihan-audit-suara',
        ];

        foreach ($permissions as $permName) {
            Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
        }

        // Roles assignment
        $superadmin = Role::firstOrCreate(['name' => 'Superadmin', 'guard_name' => 'web']);
        $adminPusat = Role::firstOrCreate(['name' => 'Admin Pusat', 'guard_name' => 'web']);
        $adminDaerah = Role::firstOrCreate(['name' => 'Admin Daerah', 'guard_name' => 'web']);
        $adminCabang = Role::firstOrCreate(['name' => 'Admin Cabang', 'guard_name' => 'web']);
        $klinik = Role::firstOrCreate(['name' => 'Klinik', 'guard_name' => 'web']);

        $superadmin->givePermissionTo($permissions);

        $adminPusat->givePermissionTo([
            'pemilihan-list',
            'pemilihan-create',
            'pemilihan-edit',
            'pemilihan-delete',
            'pemilihan-dashboard',
            'pemilihan-laporan',
        ]);

        $adminDaerah->givePermissionTo([
            'pemilihan-list',
            'pemilihan-create',
            'pemilihan-edit',
            'pemilihan-dashboard',
            'pemilihan-laporan',
        ]);

        $adminCabang->givePermissionTo([
            'pemilihan-list',
            'pemilihan-create',
            'pemilihan-edit',
            'pemilihan-dashboard',
            'pemilihan-laporan',
        ]);

        $klinik->givePermissionTo([
            'pemilihan-list',
            'pemilihan-vote',
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
