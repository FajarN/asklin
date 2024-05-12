<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class LayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('layanan')->insert(['id' => '3', 'nama' => 'Umum', 'status' => '1', 'created_by' => '1']);
        DB::table('layanan')->insert(['id' => '4', 'nama' => 'Gigi', 'status' => '1', 'created_by' => '1']);
        DB::table('layanan')->insert(['id' => '5', 'nama' => 'Kebidanan', 'status' => '1', 'created_by' => '1']);
        DB::table('layanan')->insert(['id' => '6', 'nama' => 'Estetika', 'status' => '1', 'created_by' => '1']);
        DB::table('layanan')->insert(['id' => '7', 'nama' => 'Mata', 'status' => '1', 'created_by' => '1']);
        DB::table('layanan')->insert(['id' => '8', 'nama' => 'THT', 'status' => '1', 'created_by' => '1']);
        DB::table('layanan')->insert(['id' => '9', 'nama' => 'Bedah', 'status' => '1', 'created_by' => '1']);
        DB::table('layanan')->insert(['id' => '10', 'nama' => 'Penyakit Dalam', 'status' => '1', 'created_by' => '1']);
        DB::table('layanan')->insert(['id' => '11', 'nama' => 'Hemodialisa', 'status' => '1', 'created_by' => '1']);
        DB::table('layanan')->insert(['id' => '12', 'nama' => 'Akupuntur', 'status' => '1', 'created_by' => '1']);
        DB::table('layanan')->insert(['id' => '14', 'nama' => 'Rehabilitasi Medik', 'status' => '1', 'created_by' => '1']);
        DB::table('layanan')->insert(['id' => '15', 'nama' => 'Anak', 'status' => '1', 'created_by' => '1']);
        DB::table('layanan')->insert(['id' => '16', 'nama' => 'Persalinan 24 Jam', 'status' => '1', 'created_by' => '1']);
    }
}