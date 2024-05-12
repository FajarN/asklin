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
        Schema::create('pendaftaran_event', function (Blueprint $table) {
            $table->id();
            $table->string('nama_peserta');
            $table->enum('keanggotaaan', ['0', '1', '2']);
            $table->string('nama_klinik');
            $table->text('alamat');
            $table->string('id_provinsi');
            $table->string('id_kota');
            $table->string('id_kecamatan');
            $table->string('id_kelurahan');
            $table->string('tlf');
            $table->string('email');
            $table->string('nama_hotel');
            $table->string('jumlah_kamar');
            $table->date('tgl_masuk');
            $table->date('tgl_keluar');
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
        Schema::dropIfExists('pendaftaran_event');
    }
};
