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
        Schema::table('system_notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('system_notifications', 'dismissed_from_navbar')) {
                $table->boolean('dismissed_from_navbar')->default(false)->index()->after('read_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_notifications', function (Blueprint $table) {
            if (Schema::hasColumn('system_notifications', 'dismissed_from_navbar')) {
                $table->dropColumn('dismissed_from_navbar');
            }
        });
    }
};
