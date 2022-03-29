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
            $table->id();
             $table->integer('user_id')->unsigned();
             $table->integer('kategori_pemasukan_id')->unsigned();
             $table->string('nama_pemasukan')->nullable();
             $table->integer('currency_id')->unsigned();
             $table->float('jumlah_pemasukan');
             $table->timestamp('tanggal_pemasukan');
             $table->string('keterangan')->nullable();
             $table->boolean('status_transaksi_berulang');
             $table->boolean('is_delete');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
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
