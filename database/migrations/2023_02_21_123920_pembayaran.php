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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('id_kategori');
            $table->enum('status', ['0', '1']);
            $table->string('id_anggota');
            $table->text('keterangan');
            $table->text('bukti');
            $table->date('tanggal_bayar');
            $table->date('dari')->nullable();
            $table->date('sampai')->nullable();
            $table->decimal('total')->default('0');
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
        Schema::dropIfExists('pembayaran');
    }
};
