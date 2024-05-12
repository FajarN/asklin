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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->integer('id_kategori')->unsigned();
            $table->string('judul');
            $table->string('path');
            $table->text('konten');
            $table->text('gambar')->nullable();
            $table->datetime('mulai');
            $table->datetime('selesai');
            $table->enum('status', ['0', '1'])->default(0);
            $table->enum('kategori', ['Pusat', 'Daerah', 'Cabang']);
            $table->string('id_provinsi');
            $table->string('id_kota');
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
};
