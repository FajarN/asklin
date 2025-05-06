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
        Schema::table('struktur_organisasi', function (Blueprint $table) {
            $table->text('alamat_sekretariat')->nullable()->after('tgl_muscab');
            $table->string('email_sekretariat')->nullable()->after('alamat_sekretariat');
            $table->string('telp_sekretariat', 20)->nullable()->after('email_sekretariat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('struktur_organisasi', function (Blueprint $table) {
            $table->dropColumn(['alamat_sekretariat', 'email_sekretariat', 'telp_sekretariat']);
        });
    }
};
