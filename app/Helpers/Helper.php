<?php
namespace App\Helpers;
use Illuminate\Support\Facades\DB;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\TujuanKeuangan;
use App\Models\GoalsTujuanKeuangan;
use App\Models\Hutang;
use App\Models\Piutang;
use App\Models\Tagihan;
use App\Models\Settings;
use App\Models\Simpanan;
use App\Models\KategoriPemasukan;
use App\Models\KategoriPengeluaran;
use App\Models\KategoriLaporanKeuangan;
use App\Models\KategoriTagihan;
use App\Models\KategoriTujuanKeuangan;
use App\Models\JenisSimpanan;
use App\Models\TujuanSimpanan;
use App\Models\CurrencyData;

use Auth;
use Carbon\Carbon;
use Validator;

class Helper {
     public static function balancedata($jumlah){

         $settings = Settings::where('user_id',Auth::id())->first();
            $pemasukan = Pemasukan::Where(
                                    [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                        ['currency_id', '=', $settings->currency_id]
                                    ])->sum('jumlah_pemasukan');
            
              $pengeluarandenganhutang = Pengeluaran::Where(
                                        [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                        ['currency_id', '=', $settings->currency_id],
                                        ])->sum('jumlah_pengeluaran');

             $pengeluaran = Pengeluaran::Where(
                                    [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                        ['currency_id', '=', $settings->currency_id],
                                        ['hutang_id', '=', null]
                                    ])->sum('jumlah_pengeluaran');
            

            $simpanan = Simpanan::Where(
                                    [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                        ['currency_id', '=', $settings->currency_id],
                                        ['status_simpanan','=',1]
                                    ])->sum('jumlah_simpanan');
                                    
            $tujuankeuangan = TujuanKeuangan::Where(
                                    [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                    ])->orWhere('simpanan_id','=',null)
                                    ->orWhere('hutang_id','=',null)
                                    ->sum('nominal_goals');

            //piutang
            
               $calculatepiutangbelumdibayarsamsek = Piutang::Where(
                                                    [
                                                        ['is_delete', '=', 0],
                                                        ['user_id', '=', Auth::id()],
                                                        ['currency_id', '=', $settings->currency_id],
                                                        ['status_piutang','=','0'],
                                                        ['jumlah_piutang_dibayar','=',0]
                                                    ])->sum('jumlah_hutang');

            $calculatepiutangbelumdibayarsebagian= Piutang::Where(
                                                    [
                                                        ['is_delete', '=', 0],
                                                        ['user_id', '=', Auth::id()],
                                                        ['currency_id', '=', $settings->currency_id],
                                                        ['status_piutang','=','0'],
                                                        ['jumlah_piutang_dibayar','!=',0]
                                                    ])->sum('jumlah_piutang_dibayar');
 
            $calculatepiutangbelumdibayarsebagiansisa = Piutang::Where(
                                                    [
                                                        ['is_delete', '=', 0],
                                                        ['user_id', '=', Auth::id()],
                                                        ['currency_id', '=', $settings->currency_id],
                                                        ['status_piutang','=','0'],
                                                        ['jumlah_piutang_dibayar','!=',0]
                                                    ])->sum('jumlah_hutang');


            $calculatepiutangsudahibayar = Piutang::Where(
                                            [
                                                ['is_delete', '=', 0],
                                                ['user_id', '=', Auth::id()],
                                                ['currency_id', '=', $settings->currency_id],
                                                ['status_piutang','=','1']
                                            ])->sum('jumlah_hutang');


            $piutangdibayar = $calculatepiutangsudahibayar + $calculatepiutangbelumdibayarsebagian;
            $piutangbelumdibayar = $calculatepiutangbelumdibayarsamsek + $calculatepiutangbelumdibayarsebagiansisa;


            //endpiutang

            //Hutang 
            $calculatehutangbelumdibayarsamsek = Hutang::Where(
                                            [
                                                ['is_delete', '=', 0],
                                                ['user_id', '=', Auth::id()],
                                                ['currency_id', '=', $settings->currency_id],
                                                ['status_hutang','=','0'],
                                                ['jumlah_hutang_dibayar','=','0']
                                            ])->sum('jumlah_hutang');
            
            $calculatehutangbelumdibayarsebagian = Hutang::Where(
                                            [
                                                ['is_delete', '=', 0],
                                                ['user_id', '=', Auth::id()],
                                                ['currency_id', '=', $settings->currency_id],
                                                ['status_hutang','=','0'],
                                                ['jumlah_hutang_dibayar','!=','0']
                                            ])->sum('jumlah_hutang_dibayar');

            $calculatehutangbelumdibayarsebagiansisa = Hutang::Where(
                                            [
                                                ['is_delete', '=', 0],
                                                ['user_id', '=', Auth::id()],
                                                ['currency_id', '=', $settings->currency_id],
                                                ['status_hutang','=','0'],
                                                ['jumlah_hutang_dibayar','!=','0']
                                            ])->sum('jumlah_hutang_dibayar');


            $calculatehutangsudahdibayar = Hutang::Where(
                                            [
                                                ['is_delete', '=', 0],
                                                ['user_id', '=', Auth::id()],
                                                ['currency_id', '=', $settings->currency_id],
                                                ['status_hutang','=','1']
                                            ])->sum('jumlah_hutang');
            
             $hutangdibayar = $calculatehutangsudahdibayar + $calculatehutangbelumdibayarsebagiansisa;
             $hutangbelumdibayar =  $calculatehutangbelumdibayarsamsek +  $calculatehutangbelumdibayarsebagiansisa;
            //end Hutang

            $tagihan = Tagihan::Where(
                                    [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                        ['status_tagihan_lunas','=',1]
                                    ])->sum('jumlah_tagihan');
            $tagihandata = Tagihan::Where(
                                    [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                    ])->sum('jumlah_tagihan');
              
            $calculation = (int)$pemasukan + (int)$piutangdibayar - (int)$piutangbelumdibayar - (int)$hutangdibayar + (int)$hutangbelumdibayar - (int)$simpanan - (int)$pengeluaran - (int)$tujuankeuangan - (int)$tagihan;
            
            $pemasukan_total = (int)$pemasukan + (int)$piutangdibayar + (int)$hutangbelumdibayar;

            $pengeluaran_total =  (int)$piutangbelumdibayar + (int)$hutangdibayar + (int)$simpanan + (int)$pengeluaran + (int)$tujuankeuangan + (int)$tagihan;
            
            $jumlah_temporary = $calculation - $jumlah;

            if($jumlah_temporary >= 0){
                return true;
            }else{
                return false;
            }

    }
}
?>