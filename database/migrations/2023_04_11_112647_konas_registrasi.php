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
        Schema::create('konas_peserta', function (Blueprint $table) {
            $table->id();
            $table->string('nama_peserta')->nullable();
            $table->string('nik')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('detail')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();
            $table->text('foto')->nullable();
            $table->enum('keanggotaan', ['1', '2', '3'])->nullable();
            $table->string('nama_klinik')->nullable();
            $table->text('alamat_klinik')->nullable();
            $table->enum('status', ['1', '2'])->nullable();
            $table->string('cabang')->nullable();
            $table->text('surat_mandat')->nullable();
            $table->string('komisi')->nullable();
            $table->enum('status_pembayaran', ['0', '1'])->default('0');
            $table->string('biaya_pendaftaran')->default('0');
            $table->enum('status_pembayaran_hotel', ['0', '1'])->default('0');
            $table->string('biaya_hotel')->default('0');
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
        Schema::dropIfExists('konas_peserta');
    }
};
