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
            $table->float('jumlah_piutang_dibayar')->after('jumlah_hutang');
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
            $table->dropColumn('jumlah_piutang_dibayar');
        });
    }
};
