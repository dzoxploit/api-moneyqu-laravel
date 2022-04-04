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
            $table->boolean('status_tagihan')->after('jumlah_tagihan')->nullable();
             $table->timestamp('tanggal_tagihan')->after('status_tagihan')->nullable();
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
            $table->dropColumn('status_tagihan');
            $table->dropColumn('tanggal_tagihan');
        });
    }
};
