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
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_jenis_surat')->constrained('jenis_surat');
            $table->date('tgl_surat');
            $table->string('no_surat', 50)->unique();
            $table->string('perihal', 255);
            $table->string('kode_qr')->nullable();
            $table->string('created_by', 50);
            $table->string('updated_by', 50);
            $table->enum('status', ['draft', 'disetujui', 'ditolak', 'terkirim'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_keluar');
    }
};
