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
        Schema::create('sk_pengurus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_struktur_organisasi')->unique();
            $table->unsignedBigInteger('id_ketua_umum')->nullable();
            $table->enum('tingkatan', ['pusat', 'daerah', 'cabang']);
            $table->string('no_sk', 100)->nullable();
            $table->string('perihal', 255)->nullable();
            $table->string('no_rekomendasi_daerah', 100)->nullable();
            $table->date('tgl_rekomendasi_daerah')->nullable();
            $table->date('tgl_musyawarah')->nullable();
            $table->date('tgl_penetapan')->nullable();
            $table->date('tgl_pelantikan')->nullable();
            $table->string('kota_penetapan', 100)->default('Jakarta');
            $table->boolean('show_qr')->default(true);
            $table->string('qr_token', 64)->nullable()->unique();
            $table->string('file_pdf', 255)->nullable();
            $table->string('status', 50)->default('draft');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('id_struktur_organisasi')
                ->references('id')
                ->on('struktur_organisasi')
                ->onDelete('cascade');

            $table->foreign('id_ketua_umum')
                ->references('id')
                ->on('master_ketua_umum')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sk_pengurus');
    }
};

