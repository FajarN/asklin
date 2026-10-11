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
        Schema::create('sk_lama', function (Blueprint $table) {
            $table->id();
            $table->string('no_sk', 150)->nullable();
            $table->string('judul', 255);
            $table->enum('tingkatan', ['pusat', 'daerah', 'cabang'])->default('cabang');
            $table->char('id_provinsi', 2)->nullable();
            $table->char('id_kota', 4)->nullable();
            $table->string('periode', 50)->nullable(); // e.g. "2019 - 2024"
            $table->integer('tahun_mulai')->nullable();
            $table->integer('tahun_selesai')->nullable();
            $table->date('tgl_penetapan')->nullable();
            $table->string('file_sk', 255);
            $table->string('file_nama_asli', 255)->nullable();
            $table->string('file_ekstensi', 20)->default('pdf');
            $table->bigInteger('file_size')->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tingkatan', 'tahun_selesai']);
            $table->index(['id_provinsi', 'id_kota']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sk_lama');
    }
};
