<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\KategoriController;
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
        
        Route::get('kategori-pemasukan', [KategoriController::class, 'index_kategori_pemasukan'])->middleware('auth:api');
        Route::post('kategori-pemasukan/create', [KategoriController::class, 'create_kategori_pemasukan'])->middleware('auth:api');
        Route::match(array('GET','POST'),'kategori-pemasukan/update/{id}', [KategoriController::class, 'update_kategori_pemasukan']);
        Route::post('kategori-pemasukan/destroy/{id}', [KategoriController::class, 'destroy_kategori_pemasukan']);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
