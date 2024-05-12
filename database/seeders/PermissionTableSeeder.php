<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
  
class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
           'secret',
           'berita',
           'kategori-berita',
           'events-list',
           'events-create',
           'events-edit',
           'events-delete',
           'agenda-list',
           'agenda-create',
           'agenda-edit',
           'agenda-delete',
           'fasilitas',
           'fasilitas-klinik',
           'layanan',
           'kategori-pembayaran',
           'verifikasi-anggota',
           'pembayaran',
           'kerjasama-asuransi',
           'verifikasi',
           'sertifikat',
           'pembayaran-pusat'
        ];
      
        foreach ($permissions as $permission) {
             Permission::create(['name' => $permission]);
        }
    }
}