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
        Schema::create('tujuan_keuangan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('nama_tujuan_keuangan');
            $table->boolean('status_fleksibel');
            $table->float('nominal');
            $table->bigInteger('kategori_tujuan_keuangan_id')->unsigned();
            $table->timestamp('tanggal')->nullable();
            $table->boolean('status_tujuan_keuangan');
            $table->bigInteger('hutang_id')->unsigned()->nullable();
            $table->bigInteger('currency_id')->unsigned(); 
            $table->boolean('is_delete');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('kategori_tujuan_keuangan_id')->references('id')->on('kategori_tujuan_keuangan')->onDelete('cascade');
            $table->foreign('hutang_id')->references('id')->on('hutang')->onDelete('cascade');
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
        Schema::dropIfExists('tujuan_keuangan');
    }
};
