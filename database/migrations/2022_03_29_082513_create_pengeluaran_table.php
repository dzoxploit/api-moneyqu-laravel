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
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('kategori_pengeluaran_id')->unsigned();
            $table->bigInteger('hutang_id')->unsigned()->nullable();
            $table->string('nama_pengeluaran')->nullable();
            $table->bigInteger('currency_id')->unsigned();
            $table->float('jumlah_pengeluaran');
            $table->timestamp('tanggal_pengeluaran');
            $table->string('keterangan')->nullable();
            $table->boolean('is_delete');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('hutang_id')->references('id')->on('hutang')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currency')->onDelete('cascade');
            $table->foreign('kategori_pengeluaran_id')->references('id')->on('kategori_pengeluaran');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengeluaran');
    }
};
