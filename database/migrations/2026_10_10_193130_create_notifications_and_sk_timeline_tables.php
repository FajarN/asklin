<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Tabel Notifikasi Sistem Terpusat
        if (!Schema::hasTable('system_notifications')) {
            Schema::create('system_notifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('role_target', 50)->nullable()->index(); // Superadmin, Sekjen, Ketum, Pengurus Daerah, Pengurus Cabang
                $table->string('title');
                $table->text('message');
                $table->string('type', 40)->default('general')->index(); // sk_pengurus, anggota, expired_sio, system
                $table->unsignedBigInteger('reference_id')->nullable()->index();
                $table->string('url')->nullable();
                $table->boolean('is_read')->default(false)->index();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }

        // 2. Tabel Riwayat Step-by-Step Approval SK Pengurus
        if (!Schema::hasTable('sk_approval_histories')) {
            Schema::create('sk_approval_histories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_sk_pengurus')->index();
                $table->unsignedBigInteger('id_struktur_organisasi')->nullable()->index();
                $table->string('step_key', 50)->index(); // draft, verifikasi_daerah, pemeriksaan_sekjen, pengesahan_ketum, terbit
                $table->string('step_title', 150);
                $table->string('status', 30)->default('pending')->index(); // pending, in_progress, approved, revised, rejected
                $table->unsignedBigInteger('actor_id')->nullable()->index();
                $table->string('actor_name', 150)->nullable();
                $table->string('actor_role', 50)->nullable();
                $table->text('notes')->nullable();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->integer('duration_seconds')->nullable(); // Menghitung lama tunggu
                $table->timestamps();
            });
        }

        // 3. Kolom Email Token 7 Hari pada tabel sk_pengurus
        if (Schema::hasTable('sk_pengurus')) {
            Schema::table('sk_pengurus', function (Blueprint $table) {
                if (!Schema::hasColumn('sk_pengurus', 'email_token')) {
                    $table->string('email_token', 100)->nullable()->unique()->after('status');
                }
                if (!Schema::hasColumn('sk_pengurus', 'email_token_role')) {
                    $table->string('email_token_role', 30)->nullable()->after('email_token');
                }
                if (!Schema::hasColumn('sk_pengurus', 'email_token_expires_at')) {
                    $table->timestamp('email_token_expires_at')->nullable()->after('email_token_role');
                }
                if (!Schema::hasColumn('sk_pengurus', 'email_token_used_at')) {
                    $table->timestamp('email_token_used_at')->nullable()->after('email_token_expires_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('sk_pengurus')) {
            Schema::table('sk_pengurus', function (Blueprint $table) {
                $columns = ['email_token', 'email_token_role', 'email_token_expires_at', 'email_token_used_at'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('sk_pengurus', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        Schema::dropIfExists('sk_approval_histories');
        Schema::dropIfExists('system_notifications');
    }
};
