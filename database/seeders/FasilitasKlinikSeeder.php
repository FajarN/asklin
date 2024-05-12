<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class FasilitasKlinikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fasilitas_klinik')->insert(['id' => '1', 'nama' => 'Rawat Jalan', 'status' => '1', 'created_by' => '1']);
        DB::table('fasilitas_klinik')->insert(['id' => '2', 'nama' => 'Rawat Inap', 'status' => '1', 'created_by' => '1']);
    }
}