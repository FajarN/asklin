<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sdm', function (Blueprint $table) {
            $table->id();
            $table->string('id_klinik');
            $table->string('nama');
            $table->enum('status', ['waiting', 'process', 'approved']);
            $table->string('id_kategori_sdm');
            $table->string('peranan')->nullable();
            $table->string('nama_dokter')->nullable();
            $table->string('npa_idi')->nullable();
            $table->string('no_str')->nullable();
            $table->string('no_sip')->nullable();
            $table->date('tgl_akhir_sip')->nullable();
            $table->string('no_tlf')->nullable();
            $table->string('no_sib_sik')->nullable();
            $table->date('tgl_akhir_str')->nullable();
            $table->string('ket_sib_sik')->nullable();
            $table->string('farmasi_apoteker')->nullable();
            $table->string('ijazah_terakhir')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('email_dokter')->nullable();
            $table->string('file_sip')->nullable();
            $table->string('file_str')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sdm');
    }
};
