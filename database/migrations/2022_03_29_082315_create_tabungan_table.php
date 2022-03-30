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
        Schema::create('tabungan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('tujuan_tabungan_id')->unsigned();
            $table->float('jumlah_tabungan');
            $table->string('deskripsi');
            $table->boolean('status_tabungan');
            $table->bigInteger('jenis_tabungan_id')->unsigned();
            $table->bigInteger('currency_id')->unsigned();
            $table->boolean('is_delete');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currency')->onDelete('cascade');
            $table->foreign('tujuan_tabungan_id')->references('id')->on('tujuan_tabungan')->onDelete('cascade');
            $table->foreign('jenis_tabungan_id')->references('id')->on('jenis_tabungan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tabungan');
    }
};
