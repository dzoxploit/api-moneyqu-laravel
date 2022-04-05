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
        Schema::table('tujuan_keuangan', function (Blueprint $table) {
            $table->bigInteger('currency_id')->unsigned()->after('kategori_tujuan_keuangan_id');
             $table->foreign('currency_id')->references('id')->on('currency')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tujuan_keuangan', function (Blueprint $table) {
               $table->dropColumn('currency_id');
        });
    }
};
