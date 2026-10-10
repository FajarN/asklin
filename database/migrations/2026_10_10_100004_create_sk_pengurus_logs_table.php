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
        Schema::create('sk_pengurus_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_struktur_organisasi')->index();
            $table->unsignedBigInteger('id_sk_pengurus')->nullable()->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('role_name', 50);
            $table->string('action', 50);
            $table->text('catatan')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('id_struktur_organisasi')
                ->references('id')
                ->on('struktur_organisasi')
                ->onDelete('cascade');

            $table->foreign('id_sk_pengurus')
                ->references('id')
                ->on('sk_pengurus')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sk_pengurus_logs');
    }
};

