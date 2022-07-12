<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\HutangController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\SimpananController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\TujuanKeuanganController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function(){
        Route::post('login', [UsersController::class, 'login']);
        Route::post('register', [UsersController::class, 'register']);
        Route::get('logout', [UsersController::class, 'logout'])->middleware('auth:api');

        Route::get('index', [LaporanKeuanganController::class, 'index'])->middleware('auth:api');
        Route::get('detail', [LaporanKeuanganController::class, 'detail_laporan_keuangan'])->middleware('auth:api');
        Route::get('kategori-laporan-keuangan', [LaporanKeuanganController::class, 'kategori_laporan_keuangan'])->middleware('auth:api');
        Route::get('kategori-pemasukan', [LaporanKeuanganController::class, 'kategori_pemasukan'])->middleware('auth:api');
        Route::get('kategori-pengeluaran', [LaporanKeuanganController::class, 'kategori_pengeluaran'])->middleware('auth:api');
        Route::get('kategori-tagihan', [LaporanKeuanganController::class, 'kategori_tagihan'])->middleware('auth:api');
        Route::get('jenis-simpanan', [LaporanKeuanganController::class, 'jenis_simpanan'])->middleware('auth:api');
        Route::get('kategori-tujuan-keuangan', [LaporanKeuanganController::class, 'kategori_tujuan_keuangan'])->middleware('auth:api');
        Route::get('tujuan-simpanan', [LaporanKeuanganController::class, 'tujuan_simpanan'])->middleware('auth:api');
        Route::get('currency', [LaporanKeuanganController::class, 'currencydata'])->middleware('auth:api');

        Route::get('pemasukan', [PemasukanController::class, 'index'])->middleware('auth:api');
        Route::post('pemasukan/create', [PemasukanController::class, 'create'])->middleware('auth:api');
        Route::match(array('GET','POST'),'pemasukan/update/{id}', [PemasukanController::class, 'update'])->middleware('auth:api');
        Route::post('pemasukan/destroy/{id}', [PemasukanController::class, 'destroy_pemasukan'])->middleware('auth:api');

        Route::get('pengeluaran', [PengeluaranController::class, 'index'])->middleware('auth:api');
        Route::post('pengeluaran/create', [PengeluaranController::class, 'create'])->middleware('auth:api');
        Route::post('pengeluaran/create/bayar-hutang/{id}', [PengeluaranController::class, 'create_bayar_hutang'])->middleware('auth:api');
        Route::match(array('GET','POST'),'pengeluaran/update/{id}', [PengeluaranController::class, 'update'])->middleware('auth:api');
        Route::post('pengeluaran/destroy/{id}', [PengeluaranController::class, 'destroy_pengeluaran'])->middleware('auth:api');

        
        Route::get('hutang', [HutangController::class, 'index'])->middleware('auth:api');
        Route::get('hutang-belum-lunas', [HutangController::class, 'index_belum_lunas'])->middleware('auth:api');
        Route::post('hutang/create', [HutangController::class, 'create'])->middleware('auth:api');
        Route::match(array('GET','POST'),'hutang/update/{id}', [HutangController::class, 'update'])->middleware('auth:api');
        Route::post('hutang/destroy/{id}', [HutangController::class, 'destroy_hutang'])->middleware('auth:api');


        Route::get('piutang', [PiutangController::class, 'index'])->middleware('auth:api');
        Route::post('piutang/create', [PiutangController::class, 'create'])->middleware('auth:api');
        Route::match(array('GET','POST'),'piutang/update/{id}', [PiutangController::class, 'update'])->middleware('auth:api');
        Route::post('piutang/destroy/{id}', [PiutangController::class, 'destroy_piutang'])->middleware('auth:api');
        Route::post('piutang/create/bayar-piutang/{id}', [PiutangController::class, 'update_bayaran_piutang'])->middleware('auth:api');

        Route::get('simpanan', [SimpananController::class, 'index'])->middleware('auth:api');
        Route::get('simpanan-non-active', [SimpananController::class, 'index_non_active'])->middleware('auth:api');
        Route::post('simpanan/create', [SimpananController::class, 'create'])->middleware('auth:api');
        Route::match(array('GET','POST'),'simpanan/update/{id}', [SimpananController::class, 'update'])->middleware('auth:api');
        Route::post('simpanan/destroy/{id}', [SimpananController::class, 'destroy_simpanan'])->middleware('auth:api');


        Route::get('tujuan-keuangan', [TujuanKeuanganController::class, 'index'])->middleware('auth:api');
        Route::post('tujuan-keuangan/create', [TujuanKeuanganController::class, 'create'])->middleware('auth:api');
        Route::match(array('GET','POST'),'tujuan-keuangan/update/{id}', [TujuanKeuanganController::class, 'update'])->middleware('auth:api');
        Route::post('tujuan-keuangan/destroy/{id}', [TujuanKeuanganController::class, 'destroy'])->middleware('auth:api');

        
        Route::get('tagihan', [TagihanController::class, 'index'])->middleware('auth:api');
        Route::post('tagihan/create', [TagihanController::class, 'create'])->middleware('auth:api'); 
        Route::match(array('GET','POST'),'tagihan/update/{id}', [TagihanController::class, 'update'])->middleware('auth:api');
        Route::post('tagihan/destroy/{id}', [TagihanController::class, 'destroy'])->middleware('auth:api');

        //add goals tujuan keuangan
        Route::get('detail-tujuan-keuangan/{id}', [TujuanKeuanganController::class, 'detail_tujuan_keuangan'])->middleware('auth:api');
        Route::post('tujuan-keuangan/goals/create/{id}', [TujuanKeuanganController::class, 'add_goals_tujuan_keuangan'])->middleware('auth:api');
        Route::post('tujuan-keuangan/goals/destroy/{id}', [TujuanKeuanganController::class, 'destroy_goals_tujuan_keuangan'])->middleware('auth:api');

});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
