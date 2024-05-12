<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class FasilitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fasilitas')->insert(['id' => '2', 'nama' => 'Ruang Nifas', 'status' => '1', 'created_by' => '1']);
        DB::table('fasilitas')->insert(['id' => '3', 'nama' => 'Tersedia Ruang Operasi', 'status' => '1', 'created_by' => '1']);
        DB::table('fasilitas')->insert(['id' => '4', 'nama' => 'Kamar Bersalin', 'status' => '1', 'created_by' => '1']);
    }
}