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
     Schema::create('surat_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_surat')->constrained('surat_keluar');
            $table->string('asal_surat', 255)->nullable();
            $table->string('nomor_asal_surat', 255)->nullable();
            $table->string('agenda', 255)->nullable();
            $table->string('hari', 20)->nullable();
            $table->date('tgl_agenda')->nullable();
            $table->string('waktu_agenda', 30)->nullable();
            $table->string('tempat_agenda', 255)->nullable();
            $table->string('created_by', 50);
            $table->string('updated_by', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_penomoran');
    }
};
