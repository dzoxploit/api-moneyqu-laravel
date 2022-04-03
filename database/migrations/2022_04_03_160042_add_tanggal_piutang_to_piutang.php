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
        Schema::table('piutang', function (Blueprint $table) {
             $table->timestamp('tanggal_piutang')->after('currency_id')->nullable();
             $table->timestamp('tanggal_piutang_dibayar')->after('status_piutang')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('piutang', function (Blueprint $table) {
              $table->dropColumn('tanggal_piutang');
              $table->dropColumn('tanggal_piutang_dibayar');
        });
    }
};
