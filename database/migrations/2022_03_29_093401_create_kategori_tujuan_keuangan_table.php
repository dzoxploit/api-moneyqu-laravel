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
        Schema::create('kategori_tujuan_keuangan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_tujuan_keuangan');
            $table->string('deskripsi_tujuan_keuangan');
            $table->boolean('is_active');
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
        Schema::dropIfExists('kategori_tujuan_keuangan');
    }
};
