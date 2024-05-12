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
        Schema::create('pembayaran_pusat', function (Blueprint $table) {
            $table->id();
            $table->string('id_provinsi')->nullable();
            $table->string('id_kota')->nullable();
            $table->enum('status', ['Transfer PD', 'Transfer PP']);
            $table->string('jumlah_klinik');
            $table->text('bukti');
            $table->decimal('total')->default('0');
            $table->string('jenis_pembayaran');
            $table->date('dari');
            $table->date('sampai');
            $table->text('keterangan');
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
        Schema::dropIfExists('pembayaran_pusat');
    }
};
