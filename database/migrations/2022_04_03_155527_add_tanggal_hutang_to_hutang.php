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
        Schema::table('hutang', function (Blueprint $table) {
             $table->timestamp('tanggal_hutang')->after('currency_id')->nullable();
             $table->timestamp('tanggal_hutang_dibayar')->after('status_hutang')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hutang', function (Blueprint $table) {
              $table->dropColumn('tanggal_hutang');
              $table->dropColumn('tanggal_hutang_dibayar');
        });
    }
};
