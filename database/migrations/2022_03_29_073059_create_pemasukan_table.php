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
        Schema::create('pemasukan', function (Blueprint $table) {
            $table->bigIncrements('id');
             $table->bigInteger('user_id')->unsigned();
             $table->bigInteger('kategori_pemasukan_id')->unsigned();
             $table->string('nama_pemasukan')->nullable();
             $table->bigInteger('currency_id')->unsigned();
             $table->float('jumlah_pemasukan');
             $table->timestamp('tanggal_pemasukan');
             $table->string('keterangan')->nullable();
             $table->boolean('status_transaksi_berulang');
             $table->boolean('is_delete');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currency')->onDelete('cascade');
            $table->foreign('kategori_pemasukan_id')->references('id')->on('kategori_pemasukan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemasukan');
    }
};
