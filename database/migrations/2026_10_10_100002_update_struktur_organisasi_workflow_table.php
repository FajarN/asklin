<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah tipe kolom status menjadi VARCHAR(50) agar fleksibel menampung alur persetujuan
        DB::statement("ALTER TABLE `struktur_organisasi` MODIFY COLUMN `status` VARCHAR(50) NOT NULL DEFAULT 'draft'");

        Schema::table('struktur_organisasi', function (Blueprint $table) {
            $table->boolean('is_locked')->default(false)->after('status');
            $table->text('catatan_revisi')->nullable()->after('is_locked');
            $table->string('file_berita_acara')->nullable()->after('catatan_revisi');
            $table->timestamp('diajukan_at')->nullable()->after('file_berita_acara');
            $table->timestamp('diverifikasi_daerah_at')->nullable()->after('diajukan_at');
            $table->unsignedBigInteger('diverifikasi_daerah_by')->nullable()->after('diverifikasi_daerah_at');
            $table->timestamp('disetujui_pusat_at')->nullable()->after('diverifikasi_daerah_by');
            $table->unsignedBigInteger('disetujui_pusat_by')->nullable()->after('disetujui_pusat_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('struktur_organisasi', function (Blueprint $table) {
            $table->dropColumn([
                'is_locked',
                'catatan_revisi',
                'file_berita_acara',
                'diajukan_at',
                'diverifikasi_daerah_at',
                'diverifikasi_daerah_by',
                'disetujui_pusat_at',
                'disetujui_pusat_by',
            ]);
        });

        DB::statement("ALTER TABLE `struktur_organisasi` MODIFY COLUMN `status` ENUM('draft', 'aktif', 'selesai') NOT NULL DEFAULT 'draft'");
    }
};

