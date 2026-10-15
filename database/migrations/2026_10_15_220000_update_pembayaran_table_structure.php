<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Gunakan direct DB statement agar tidak bergantung pada doctrine/dbal
        DB::statement("ALTER TABLE `pembayaran` MODIFY `status` ENUM('0', '1', '2') NOT NULL DEFAULT '0'");
        DB::statement("ALTER TABLE `pembayaran` MODIFY `total` DECIMAL(15, 2) NOT NULL DEFAULT 0.00");
        DB::statement("ALTER TABLE `pembayaran` MODIFY `keterangan` TEXT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `pembayaran` MODIFY `status` ENUM('0', '1') NOT NULL DEFAULT '0'");
        DB::statement("ALTER TABLE `pembayaran` MODIFY `total` DECIMAL(8, 2) NOT NULL DEFAULT 0.00");
    }
};
