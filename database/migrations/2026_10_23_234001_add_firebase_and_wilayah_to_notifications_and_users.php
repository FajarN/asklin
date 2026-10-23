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
        // 1. Tambah kolom wilayah dan payload data ke system_notifications
        Schema::table('system_notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('system_notifications', 'id_provinsi')) {
                $table->string('id_provinsi', 20)->nullable()->after('role_target')->index();
            }
            if (!Schema::hasColumn('system_notifications', 'id_kota')) {
                $table->string('id_kota', 20)->nullable()->after('id_provinsi')->index();
            }
            if (!Schema::hasColumn('system_notifications', 'data')) {
                $table->text('data')->nullable()->after('url');
            }
        });

        // 2. Tambah kolom fcm_token ke tabel users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'fcm_token')) {
                $table->text('fcm_token')->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_notifications', function (Blueprint $table) {
            if (Schema::hasColumn('system_notifications', 'id_provinsi')) {
                $table->dropColumn('id_provinsi');
            }
            if (Schema::hasColumn('system_notifications', 'id_kota')) {
                $table->dropColumn('id_kota');
            }
            if (Schema::hasColumn('system_notifications', 'data')) {
                $table->dropColumn('data');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'fcm_token')) {
                $table->dropColumn('fcm_token');
            }
        });
    }
};
