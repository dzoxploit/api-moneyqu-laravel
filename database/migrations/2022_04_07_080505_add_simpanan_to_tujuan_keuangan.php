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
            $table->bigInteger('simpanan_id')->unsigned()->after('kategori_tujuan_keuangan_id')->nullable();
             $table->foreign('simpanan_id')->references('id')->on('simpanan')->onDelete('set null');
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
            $table->dropColumn('simpanan_id');
        });
    }
};
