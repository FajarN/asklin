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
        Schema::create('struktur_organisasi', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('id_tingkatan_pengurus');
        $table->string('id_provinsi')->nullable();
        $table->string('id_kota')->nullable();
        $table->string('nama_struktur'); 
        $table->string('periode', 20); 
        $table->date('tgl_muscab');
        $table->enum('status', ['draft', 'aktif', 'selesai'])->default('draft'); 
        $table->unsignedInteger('created_by')->nullable();
        $table->unsignedInteger('updated_by')->nullable();
        $table->unsignedBigInteger('deleted_by')->nullable();
        $table->timestamps();
        $table->softDeletes(); 

        $table->foreign('id_tingkatan_pengurus')->references('id')->on('tingkatan_pengurus')->onDelete('cascade');
        $table->foreign('id_provinsi')->references('code')->on('indonesia_provinces')->onDelete('set null');
        $table->foreign('id_kota')->references('code')->on('indonesia_cities')->onDelete('set null');
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('struktur_organisasi');
    }
};
