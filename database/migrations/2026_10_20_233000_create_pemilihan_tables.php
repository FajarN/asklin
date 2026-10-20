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
        Schema::create('pemilihan', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->enum('tingkat', ['cabang', 'daerah']);
            $table->string('id_provinsi', 10)->nullable();
            $table->string('id_kota', 10)->nullable();
            $table->integer('periode_mulai');
            $table->integer('periode_selesai');
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->enum('status', ['draft', 'aktif', 'selesai', 'ditutup'])->default('draft');
            $table->text('deskripsi')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index(['tingkat', 'id_provinsi', 'id_kota']);
            $table->index('status');
        });

        Schema::create('pemilihan_kandidat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pemilihan_id');
            $table->integer('nomor_urut');
            $table->string('nama_calon');
            $table->string('gelar')->nullable();
            $table->string('asal_klinik')->nullable();
            $table->string('foto')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('program_kerja')->nullable();
            $table->integer('total_suara')->default(0);
            $table->timestamps();

            $table->foreign('pemilihan_id')->references('id')->on('pemilihan')->onDelete('cascade');
            $table->unique(['pemilihan_id', 'nomor_urut']);
        });

        Schema::create('pemilihan_suara', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pemilihan_id');
            $table->unsignedBigInteger('kandidat_id');
            $table->unsignedBigInteger('anggota_id');
            $table->unsignedBigInteger('user_id');
            $table->dateTime('waktu_vote');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->foreign('pemilihan_id')->references('id')->on('pemilihan')->onDelete('cascade');
            $table->foreign('kandidat_id')->references('id')->on('pemilihan_kandidat')->onDelete('cascade');
            $table->foreign('anggota_id')->references('id')->on('anggota')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique(['pemilihan_id', 'anggota_id'], 'pemilihan_anggota_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemilihan_suara');
        Schema::dropIfExists('pemilihan_kandidat');
        Schema::dropIfExists('pemilihan');
    }
};
