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
        Schema::table('tagihan', function (Blueprint $table) {
            $table->bigInteger('kategori_tagihan_id')->nullable()->unsigned()->after('nama_tagihan');
             $table->foreign('kategori_tagihan_id')->references('id')->on('kategori_tagihan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tagihan', function (Blueprint $table) {
             $table->dropColumn('kategori_tagihan_id');
        });
    }
};
