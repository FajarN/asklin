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
        Schema::table('berita', function (Blueprint $table) {
            $table->dateTime('tanggal')->after('path');
            $table->string('lokasi')->after('konten');
        });

        Schema::table('berita', function (Blueprint $table) {
            if (Schema::hasColumn('berita', 'gambar')) {
                $table->renameColumn('gambar', 'thumb');
            }
        });

        Schema::table('berita', function (Blueprint $table) {
            $table->text('kode_qr')->after('thumb');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('berita', function (Blueprint $table) {
            $table->dropColumn(['tanggal', 'lokasi', 'kode_qr']);
        });

        Schema::table('berita', function (Blueprint $table) {
            if (Schema::hasColumn('berita', 'thumb')) {
                $table->renameColumn('thumb', 'gambar');
            }
        });
    }
};
