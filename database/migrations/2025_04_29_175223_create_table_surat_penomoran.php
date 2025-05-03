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
      Schema::create('surat_penomoran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_jenis_surat')->constrained('jenis_surat');
            $table->integer('tahun');
            $table->integer('nomor_terakhir')->default(0);
            $table->timestamps();
            
            $table->unique(['id_jenis_surat', 'tahun']);
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
