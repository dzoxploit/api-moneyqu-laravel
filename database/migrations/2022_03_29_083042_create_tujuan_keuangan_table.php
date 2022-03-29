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
            $table->id();
            $table->string('nama_tujuan_keuangan');
            $table->boolean('status_fleksibel');
            $table->float('nominal');
            $table->integer('kategori_tujuan_keuangan')->unsigned();
            $table->timestamp('tanggal')->nullable();
            $table->boolean('status_tujuan_keuangan');
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
        Schema::dropIfExists('tujuan_keuangan');
    }
};
