<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
     Schema::create('surat_undangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_surat')->constrained('surat_keluar');
            $table->string('nama_penerima', 100);
            $table->text('alamat_penerima')->nullable();
            $table->string('salam_pembuka', 100)->nullable();
            $table->text('isi_surat')->nullable();
            $table->string('salam_penutup', 100)->nullable();
            $table->string('judul_acara', 255)->nullable();
            $table->text('tujuan_acara')->nullable();
            $table->string('hari', 20)->nullable();
            $table->date('tgl_acara')->nullable();
            $table->string('waktu_acara', 40)->nullable();
            $table->text('lokasi_acara')->nullable();
            $table->text('agenda_acara')->nullable();
            $table->text('informasi_tambahan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_undangan');
    }
};
