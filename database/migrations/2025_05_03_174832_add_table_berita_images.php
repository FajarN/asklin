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
       Schema::create('berita_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('berita_id');
            $table->string('gambar');
            $table->timestamps();

            $table->foreign('berita_id')->references('id')->on('berita')->onDelete('cascade');
        });

            }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('berita_images');
    }
};
