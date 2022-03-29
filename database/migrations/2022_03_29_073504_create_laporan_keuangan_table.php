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
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->integer('kategori_laporan_keuangan_id')->unsigned();
            $table->string('nama_laporan_keuangan');
            $table->string('deskripsi')->nullable();
            $table->float('total_pemasukan');
            $table->float('total_pengeluaran');
            $table->float('total_tabungan');
            $table->float('total_hutang');
            $table->float('total_piutang');
            $table->float('total_balance');
            $table->integer('currency_id')->unsigned();
            $table->boolean('is_deleted');
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
        Schema::dropIfExists('laporan_keuangan');
    }
};
