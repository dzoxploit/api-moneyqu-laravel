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
        Schema::create('laporan_keuangan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('kategori_laporan_keuangan_id')->unsigned();
            $table->string('nama_laporan_keuangan');
            $table->string('deskripsi')->nullable();
            $table->float('total_pemasukan');
            $table->float('total_pengeluaran');
            $table->float('total_tabungan');
            $table->float('total_hutang');
            $table->float('total_piutang');
            $table->float('total_balance');
            $table->bigInteger('currency_id')->unsigned();
            $table->boolean('is_deleted');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currency')->onDelete('cascade');;
            $table->foreign('kategori_laporan_keuangan_id')->references('id')->on('kategori_laporan_keuangan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laporan_keuangan');
    }
};
