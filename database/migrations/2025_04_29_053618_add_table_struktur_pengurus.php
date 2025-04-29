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
        Schema::create('struktur_pengurus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_struktur_organisasi');
            $table->unsignedBigInteger('id_kelompok')->nullable();
            $table->string('jabatan');
            $table->string('keterangan');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('nama_lengkap');
            $table->string('no_telp')->nullable();
            $table->string('email')->nullable();
            $table->string('foto_pengurus')->nullable();
            $table->integer('urutan')->default(0);
            $table->enum('status', ['aktif', 'nonaktif', 'mengundurkan_diri'])->default('aktif'); 
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes(); 

            $table->foreign('id_struktur_organisasi')->references('id')->on('struktur_organisasi')->onDelete('cascade');
            $table->foreign('id_kelompok')->references('id')->on('struktur_kelompok_pengurus')->onDelete('set null');
            $table->foreign('parent_id')->references('id')->on('struktur_pengurus')->onDelete('set null'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('struktur_pengurus');
    }
};
