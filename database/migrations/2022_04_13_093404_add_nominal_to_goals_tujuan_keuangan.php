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
        Schema::table('goals_tujuan_keuangan', function (Blueprint $table) {
            $table->float('nominal_goals')->after('nominal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('goals_tujuan_keuangan', function (Blueprint $table) {
             $table->dropColumn('nominal_goals');
        });
    }
};
