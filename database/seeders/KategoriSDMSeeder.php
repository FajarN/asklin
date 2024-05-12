<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class KategoriSDMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sdm_kategori')->insert(['id' => '1', 'nama' => 'Dokter Penanggung Jawab', 'status' => '1', 'created_by' => '1']);
        DB::table('sdm_kategori')->insert(['id' => '3', 'nama' => 'Tenaga Keperawatan', 'status' => '1', 'created_by' => '1']);
        DB::table('sdm_kategori')->insert(['id' => '2', 'nama' => 'Dokter', 'status' => '1', 'created_by' => '1']);
        DB::table('sdm_kategori')->insert(['id' => '4', 'nama' => 'Tenaga Kesehatan', 'status' => '1', 'created_by' => '1']);
        DB::table('sdm_kategori')->insert(['id' => '5', 'nama' => 'Tenaga SDM lainnya', 'status' => '1', 'created_by' => '1']);
    }
}