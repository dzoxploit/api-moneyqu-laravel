<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
use DB;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request){
        try{
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
                                        ['currency_id', '=', $settings->currency_id]
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
            
            return response()->json([
                "status" => 201,
                "message" => "Kategori Pemasukan Berhasil Ditampilkan",
                "data" => [
                    "calculation" => $calculation,
                    'pemasukan_total' => $pemasukan_total,
                    'pengeluaran_total' => $pengeluaran_total,
                    'pemasukan' => $pemasukan,
                    'pengeluaran' => $pengeluarandenganhutang,
                    'hutang' => $hutangbelumdibayar,
                    'piutang' => $piutangbelumdibayar,
                    'simpanan' => $simpanan,
                    'tagihan' => $tagihandata,
                    'tujuankeuangan' => $tujuankeuangan,
                ]
            
            ]);
        }catch(\Exception $e){
            return response()->json([
                "status" => 400,
                 "message" => 'Error'.$e->getMessage(),
                "data" => null
            ]);
        }
    }

    public function detail_laporan_keuangan(Request $request){
         try{
            $settings = Settings::where('user_id',Auth::id())->first();
            
            /** Harian */
                        $pemasukan_harian = Pemasukan::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                    ['currency_id', '=', $settings->currency_id]
                                                ])->where('created_at','>',Carbon::now()->subDays(1))->sum('jumlah_pemasukan');

                        $pengeluaran_harian = Pengeluaran::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                    ['currency_id', '=', $settings->currency_id],
                                                    ['hutang_id', '=', null]
                                                ])->where('created_at','>',Carbon::now()->subDays(1))->sum('jumlah_pengeluaran');

                        $simpanan_harian = Simpanan::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                    ['currency_id', '=', $settings->currency_id]
                                                ])->where('created_at','>',Carbon::now()->subDays(1))->sum('jumlah_simpanan');
                                                
                        $tujuankeuangan_harian = TujuanKeuangan::where(
                                                [
                                                    ['created_at','>',Carbon::now()->subDays(1)],
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                ])
                                                ->where('simpanan_id','=',null)
                                                ->where('hutang_id','=',null)
                                                ->sum('nominal_goals');
                                                

                        //piutang
                        
                        $calculatepiutangbelumdibayarsamsek_harian = Piutang::Where(
                                                                [
                                                                    ['is_delete', '=', 0],
                                                                    ['user_id', '=', Auth::id()],
                                                                    ['currency_id', '=', $settings->currency_id],
                                                                    ['status_piutang','=','0'],
                                                                    ['jumlah_piutang_dibayar','=',0],
                                                                    ['created_at','>',Carbon::now()->subDays(1)]
                                                                ])->sum('jumlah_hutang');

                        $calculatepiutangbelumdibayarsebagian_harian = Piutang::Where(
                                                                [
                                                                    ['is_delete', '=', 0],
                                                                    ['user_id', '=', Auth::id()],
                                                                    ['currency_id', '=', $settings->currency_id],
                                                                    ['status_piutang','=','0'],
                                                                    ['jumlah_piutang_dibayar','!=',0],
                                                                    ['created_at','>',Carbon::now()->subDays(1)]
                                                                ])->sum('jumlah_piutang_dibayar');
            
                        $calculatepiutangbelumdibayarsebagiansisa_harian = Piutang::Where(
                                                                [
                                                                    ['is_delete', '=', 0],
                                                                    ['user_id', '=', Auth::id()],
                                                                    ['currency_id', '=', $settings->currency_id],
                                                                    ['status_piutang','=','0'],
                                                                    ['jumlah_piutang_dibayar','!=',0],
                                                                    ['created_at','>',Carbon::now()->subDays(1)],
                                                                ])->sum('jumlah_hutang');


                        $calculatepiutangsudahibayar_harian = Piutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_piutang','=','1'],
                                                            ['created_at','>',Carbon::now()->subDays(1)]
                                                        ])->sum('jumlah_hutang');

                        $piutangdibayar_harian = $calculatepiutangsudahibayar_harian + $calculatepiutangbelumdibayarsebagian_harian;
                        $piutangbelumdibayar_harian = $calculatepiutangbelumdibayarsamsek_harian + $calculatepiutangbelumdibayarsebagiansisa_harian;


                        //endpiutang

                        //Hutang 
                        $calculatehutangbelumdibayarsamsek_harian = Hutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_hutang','=','0'],
                                                            ['jumlah_hutang_dibayar','=','0'],
                                                            ['created_at','>',Carbon::now()->subDays(1)],
                                                        ])->sum('jumlah_hutang');
                        
                        $calculatehutangbelumdibayarsebagian_harian = Hutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_hutang','=','0'],
                                                            ['jumlah_hutang_dibayar','!=','0'],
                                                            ['created_at','>',Carbon::now()->subDays(1)],
                                                        ])->sum('jumlah_hutang_dibayar');

                        $calculatehutangbelumdibayarsebagiansisa_harian = Hutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_hutang','=','0'],
                                                            ['jumlah_hutang_dibayar','!=','0'],
                                                            ['created_at','>',Carbon::now()->subDays(1)]
                                                        ])->sum('jumlah_hutang_dibayar');


                        $calculatehutangsudahdibayar_harian = Hutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_hutang','=','1'],
                                                            ['created_at','>',Carbon::now()->subDays(1)]
                                                        ])->sum('jumlah_hutang');
                        
                        $hutangdibayar_harian = $calculatehutangsudahdibayar_harian + $calculatehutangbelumdibayarsebagiansisa_harian;
                        $hutangbelumdibayar_harian =  $calculatehutangbelumdibayarsamsek_harian +  $calculatehutangbelumdibayarsebagiansisa_harian;
                        //end Hutang

                        $tagihan_harian = Tagihan::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                    ['status_tagihan_lunas','=',1],
                                                    ['created_at','>',Carbon::now()->subDays(1)],
                                                ])->sum('jumlah_tagihan');
                        
                        $calculation_pemasukan_harian = (int)$pemasukan_harian + (int)$piutangdibayar_harian + (int)$hutangbelumdibayar_harian ;
                        $calculation_pengeluaran_harian = (int)$piutangbelumdibayar_harian + (int)$hutangdibayar_harian + (int)$simpanan_harian + (int)$pengeluaran_harian + (int)$tujuankeuangan_harian + (int)$tagihan_harian;

            /** End Harian */


             /** Mingguan */
                        $pemasukan_mingguan = Pemasukan::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                    ['currency_id', '=', $settings->currency_id]
                                                ])->where('created_at','>',Carbon::now()->subDays(7))->sum('jumlah_pemasukan');
                
                        $pengeluaran_mingguan = Pengeluaran::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                    ['currency_id', '=', $settings->currency_id],
                                                    ['hutang_id', '=', null]
                                                ])->where('created_at','>',Carbon::now()->subDays(7))->sum('jumlah_pengeluaran');

                        $simpanan_mingguan = Simpanan::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                    ['currency_id', '=', $settings->currency_id]
                                                ])->where('created_at','>',Carbon::now()->subDays(7))->sum('jumlah_simpanan');
                                                
                        $tujuankeuangan_mingguan = TujuanKeuangan::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                ])->Where('simpanan_id','=',null)
                                                ->Where('hutang_id','=',null)
                                                ->where('created_at','>',Carbon::now()->subDays(7))->sum('nominal_goals');

                        //piutang
                        
                        $calculatepiutangbelumdibayarsamsek_mingguan = Piutang::Where(
                                                                [
                                                                    ['is_delete', '=', 0],
                                                                    ['user_id', '=', Auth::id()],
                                                                    ['currency_id', '=', $settings->currency_id],
                                                                    ['status_piutang','=','0'],
                                                                    ['jumlah_piutang_dibayar','=',0],
                                                                ])->where('created_at','>',Carbon::now()->subDays(7))->sum('jumlah_hutang');

                        $calculatepiutangbelumdibayarsebagian_mingguan = Piutang::Where(
                                                                [
                                                                    ['is_delete', '=', 0],
                                                                    ['user_id', '=', Auth::id()],
                                                                    ['currency_id', '=', $settings->currency_id],
                                                                    ['status_piutang','=','0'],
                                                                    ['jumlah_piutang_dibayar','!=',0]
                                                                ])->where('created_at','>',Carbon::now()->subDays(7))->sum('jumlah_piutang_dibayar');
            
                        $calculatepiutangbelumdibayarsebagiansisa_mingguan = Piutang::Where(
                                                                [
                                                                    ['is_delete', '=', 0],
                                                                    ['user_id', '=', Auth::id()],
                                                                    ['currency_id', '=', $settings->currency_id],
                                                                    ['status_piutang','=','0'],
                                                                    ['jumlah_piutang_dibayar','!=',0]
                                                                ])->where('created_at', [Carbon::now()->startOfWeek(Carbon::SUNDAY), Carbon::now()->endOfWeek(Carbon::SATURDAY)])->sum('jumlah_hutang');


                        $calculatepiutangsudahibayar_mingguan = Piutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_piutang','=','1']
                                                        ])->where('created_at','>',Carbon::now()->subDays(7))->sum('jumlah_hutang');

                        $piutangdibayar_mingguan = $calculatepiutangsudahibayar_mingguan + $calculatepiutangbelumdibayarsebagian_mingguan;
                        $piutangbelumdibayar_mingguan = $calculatepiutangbelumdibayarsamsek_mingguan + $calculatepiutangbelumdibayarsebagiansisa_mingguan;


                        //endpiutang

                        //Hutang 
                        $calculatehutangbelumdibayarsamsek_mingguan = Hutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_hutang','=','0'],
                                                            ['jumlah_hutang_dibayar','=','0']
                                                        ])->where('created_at','>',Carbon::now()->subDays(7))->sum('jumlah_hutang');
                        
                        $calculatehutangbelumdibayarsebagian_mingguan = Hutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_hutang','=','0'],
                                                            ['jumlah_hutang_dibayar','!=','0']
                                                        ])->where('created_at','>',Carbon::now()->subDays(7))->sum('jumlah_hutang_dibayar');

                        $calculatehutangbelumdibayarsebagiansisa_mingguan = Hutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_hutang','=','0'],
                                                            ['jumlah_hutang_dibayar','!=','0']
                                                        ])->where('created_at','>',Carbon::now()->subDays(7))->sum('jumlah_hutang_dibayar');


                        $calculatehutangsudahdibayar_mingguan = Hutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_hutang','=','1']
                                                        ])->where('created_at','>',Carbon::now()->subDays(7))->sum('jumlah_hutang');
                        
                        $hutangdibayar_mingguan = $calculatehutangsudahdibayar_mingguan + $calculatehutangbelumdibayarsebagiansisa_mingguan;
                        $hutangbelumdibayar_mingguan =  $calculatehutangbelumdibayarsamsek_mingguan +  $calculatehutangbelumdibayarsebagiansisa_mingguan;
                        //end Hutang

                        $tagihan_mingguan = Tagihan::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                    ['status_tagihan_lunas','=',1]
                                                ])->where('created_at','>',Carbon::now()->subDays(7))->sum('jumlah_tagihan');
                        
                        $calculation_pemasukan_mingguan = (int)$pemasukan_mingguan + (int)$piutangdibayar_mingguan + (int)$hutangbelumdibayar_mingguan ;
                        $calculation_pengeluaran_mingguan = (int)$piutangbelumdibayar_mingguan + (int)$hutangdibayar_mingguan + (int)$simpanan_mingguan + (int)$pengeluaran_mingguan + (int)$tujuankeuangan_mingguan + (int)$tagihan_mingguan;

            /** End Mingguan */



            /** Bulanan */
                        $pemasukan_bulanan = Pemasukan::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                    ['currency_id', '=', $settings->currency_id]
                                                ])->where("created_at", ">", Carbon::now()->subMonths(1))->sum('jumlah_pemasukan');

                        $pengeluaran_bulanan = Pengeluaran::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                    ['currency_id', '=', $settings->currency_id],
                                                    ['hutang_id', '=', null]
                                                ])->where("created_at", ">", Carbon::now()->subMonths(1))->sum('jumlah_pengeluaran');

                        $simpanan_bulanan = Simpanan::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                    ['currency_id', '=', $settings->currency_id]
                                                ])->where("created_at", ">", Carbon::now()->subMonths(1))->sum('jumlah_simpanan');
                                                
                        $tujuankeuangan_bulanan = TujuanKeuangan::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                ])->Where('simpanan_id','=',null)
                                                ->Where('hutang_id','=',null)
                                                ->where("created_at", ">", Carbon::now()->subMonths(1))
                                                ->sum('nominal_goals');

                        //piutang
                        
                        $calculatepiutangbelumdibayarsamsek_bulanan = Piutang::Where(
                                                                [
                                                                    ['is_delete', '=', 0],
                                                                    ['user_id', '=', Auth::id()],
                                                                    ['currency_id', '=', $settings->currency_id],
                                                                    ['status_piutang','=','0'],
                                                                    ['jumlah_piutang_dibayar','=',0],
                                                                ])->where("created_at", ">", Carbon::now()->subMonths(1))->sum('jumlah_hutang');
                        

                        $calculatepiutangbelumdibayarsebagian_bulanan = Piutang::Where(
                                                                [
                                                                    ['is_delete', '=', 0],
                                                                    ['user_id', '=', Auth::id()],
                                                                    ['currency_id', '=', $settings->currency_id],
                                                                    ['status_piutang','=','0'],
                                                                    ['jumlah_piutang_dibayar','!=',0]
                                                                ])->where("created_at", ">", Carbon::now()->subMonths(1))->sum('jumlah_piutang_dibayar');
            
                        $calculatepiutangbelumdibayarsebagiansisa_bulanan = Piutang::Where(
                                                                [
                                                                    ['is_delete', '=', 0],
                                                                    ['user_id', '=', Auth::id()],
                                                                    ['currency_id', '=', $settings->currency_id],
                                                                    ['status_piutang','=','0'],
                                                                    ['jumlah_piutang_dibayar','!=',0]
                                                                ])->where("created_at", ">", Carbon::now()->subMonths(1))->sum('jumlah_hutang');


                        $calculatepiutangsudahibayar_bulanan = Piutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_piutang','=','1']
                                                        ])->where("created_at", ">", Carbon::now()->subMonths(1))->sum('jumlah_hutang');

                        $piutangdibayar_bulanan = $calculatepiutangsudahibayar_bulanan + $calculatepiutangbelumdibayarsebagian_bulanan;
                        $piutangbelumdibayar_bulanan = $calculatepiutangbelumdibayarsamsek_bulanan + $calculatepiutangbelumdibayarsebagiansisa_bulanan;


                        //endpiutang

                        //Hutang 
                        $calculatehutangbelumdibayarsamsek_bulanan = Hutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_hutang','=','0'],
                                                            ['jumlah_hutang_dibayar','=','0']
                                                        ])->where("created_at", ">", Carbon::now()->subMonths(1))->sum('jumlah_hutang');
                        
                        $calculatehutangbelumdibayarsebagian_bulanan = Hutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_hutang','=','0'],
                                                            ['jumlah_hutang_dibayar','!=','0']
                                                        ])->where("created_at", ">", Carbon::now()->subMonths(1))->sum('jumlah_hutang_dibayar');

                        $calculatehutangbelumdibayarsebagiansisa_bulanan = Hutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_hutang','=','0'],
                                                            ['jumlah_hutang_dibayar','!=','0']
                                                        ])->where("created_at", ">", Carbon::now()->subMonths(1))->sum('jumlah_hutang_dibayar');


                        $calculatehutangsudahdibayar_bulanan = Hutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_hutang','=','1']
                                                        ])->where("created_at", ">", Carbon::now()->subMonths(1))->sum('jumlah_hutang');
                        
                        $hutangdibayar_bulanan = $calculatehutangsudahdibayar_bulanan + $calculatehutangbelumdibayarsebagiansisa_bulanan;
                        $hutangbelumdibayar_bulanan =  $calculatehutangbelumdibayarsamsek_bulanan +  $calculatehutangbelumdibayarsebagiansisa_bulanan;
                        //end Hutang

                        $tagihan_bulanan = Tagihan::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                    ['status_tagihan_lunas','=',1]
                                                ])->where("created_at", ">", Carbon::now()->subMonths(1))->sum('jumlah_tagihan');
                        
                        $calculation_pemasukan_bulanan = (int)$pemasukan_bulanan + (int)$piutangdibayar_bulanan + (int)$hutangbelumdibayar_bulanan ;
                        $calculation_pengeluaran_bulanan = (int)$piutangbelumdibayar_bulanan + (int)$hutangdibayar_bulanan + (int)$simpanan_bulanan + (int)$pengeluaran_bulanan + (int)$tujuankeuangan_bulanan + (int)$tagihan_bulanan;

            /** End Bulanan */


            //   /** Tahunan */
              $pemasukan_tahunan = Pemasukan::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                    ['currency_id', '=', $settings->currency_id]
                                                ])->where("created_at", ">", Carbon::now()->subMonths(12))->sum('jumlah_pemasukan');

                        $pengeluaran_tahunan = Pengeluaran::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                    ['currency_id', '=', $settings->currency_id],
                                                    ['hutang_id', '=', null]
                                                ])->where("created_at", ">", Carbon::now()->subMonths(12))->sum('jumlah_pengeluaran');

                        $simpanan_tahunan = Simpanan::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                    ['currency_id', '=', $settings->currency_id]
                                                ])->where("created_at", ">", Carbon::now()->subMonths(12))->sum('jumlah_simpanan');
                                                
                        $tujuankeuangan_tahunan = TujuanKeuangan::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                ])->Where('simpanan_id','=',null)
                                                ->Where('hutang_id','=',null)
                                                ->where("created_at", ">", Carbon::now()->subMonths(12))
                                                ->sum('nominal_goals');

                        //piutang
                        
                        $calculatepiutangbelumdibayarsamsek_tahunan = Piutang::Where(
                                                                [
                                                                    ['is_delete', '=', 0],
                                                                    ['user_id', '=', Auth::id()],
                                                                    ['currency_id', '=', $settings->currency_id],
                                                                    ['status_piutang','=','0'],
                                                                    ['jumlah_piutang_dibayar','=',0],
                                                                ])->where("created_at", ">", Carbon::now()->subMonths(12))->sum('jumlah_hutang');
                        

                        $calculatepiutangbelumdibayarsebagian_tahunan = Piutang::Where(
                                                                [
                                                                    ['is_delete', '=', 0],
                                                                    ['user_id', '=', Auth::id()],
                                                                    ['currency_id', '=', $settings->currency_id],
                                                                    ['status_piutang','=','0'],
                                                                    ['jumlah_piutang_dibayar','!=',0]
                                                                ])->where("created_at", ">", Carbon::now()->subMonths(12))->sum('jumlah_piutang_dibayar');
            
                        $calculatepiutangbelumdibayarsebagiansisa_tahunan = Piutang::Where(
                                                                [
                                                                    ['is_delete', '=', 0],
                                                                    ['user_id', '=', Auth::id()],
                                                                    ['currency_id', '=', $settings->currency_id],
                                                                    ['status_piutang','=','0'],
                                                                    ['jumlah_piutang_dibayar','!=',0]
                                                                ])->where("created_at", ">", Carbon::now()->subMonths(12))->sum('jumlah_hutang');


                        $calculatepiutangsudahibayar_tahunan = Piutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_piutang','=','1']
                                                        ])->where("created_at", ">", Carbon::now()->subMonths(12))->sum('jumlah_hutang');

                        $piutangdibayar_tahunan = $calculatepiutangsudahibayar_tahunan + $calculatepiutangbelumdibayarsebagian_tahunan;
                        $piutangbelumdibayar_tahunan = $calculatepiutangbelumdibayarsamsek_tahunan + $calculatepiutangbelumdibayarsebagiansisa_tahunan;


                        //endpiutang

                        //Hutang 
                        $calculatehutangbelumdibayarsamsek_tahunan = Hutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_hutang','=','0'],
                                                            ['jumlah_hutang_dibayar','=','0']
                                                        ])->where("created_at", ">", Carbon::now()->subMonths(12))->sum('jumlah_hutang');
                        
                        $calculatehutangbelumdibayarsebagian_tahunan = Hutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_hutang','=','0'],
                                                            ['jumlah_hutang_dibayar','!=','0']
                                                        ])->where("created_at", ">", Carbon::now()->subMonths(12))->sum('jumlah_hutang_dibayar');

                        $calculatehutangbelumdibayarsebagiansisa_tahunan = Hutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_hutang','=','0'],
                                                            ['jumlah_hutang_dibayar','!=','0']
                                                        ])->where("created_at", ">", Carbon::now()->subMonths(12))->sum('jumlah_hutang_dibayar');


                        $calculatehutangsudahdibayar_tahunan = Hutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_hutang','=','1']
                                                        ])->where("created_at", ">", Carbon::now()->subMonths(12))->sum('jumlah_hutang');
                        
                        $hutangdibayar_tahunan = $calculatehutangsudahdibayar_tahunan + $calculatehutangbelumdibayarsebagiansisa_tahunan;
                        $hutangbelumdibayar_tahunan =  $calculatehutangbelumdibayarsamsek_tahunan +  $calculatehutangbelumdibayarsebagiansisa_tahunan;
                        //end Hutang

                        $tagihan_tahunan = Tagihan::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                    ['status_tagihan_lunas','=',1]
                                                ])->where("created_at", ">", Carbon::now()->subMonths(12))->sum('jumlah_tagihan');
                        
                        $calculation_pemasukan_tahunan = (int)$pemasukan_tahunan + (int)$piutangdibayar_tahunan + (int)$hutangbelumdibayar_tahunan ;
                        $calculation_pengeluaran_tahunan = (int)$piutangbelumdibayar_tahunan + (int)$hutangdibayar_tahunan + (int)$simpanan_tahunan + (int)$pengeluaran_tahunan + (int)$tujuankeuangan_tahunan + (int)$tagihan_tahunan;


            // /** End Tahunan */
            
             $pengeluaran = DB::table('pengeluaran')->join('kategori_pengeluaran','kategori_pengeluaran.id','=','pengeluaran.kategori_pengeluaran_id')
                        ->select('pengeluaran.id','pengeluaran.nama_pengeluaran as nama','kategori_pengeluaran.nama_pengeluaran as kategori','pengeluaran.jumlah_pengeluaran','pengeluaran.tanggal_pengeluaran','pengeluaran.keterangan', DB::raw("'pengeluaran' as type"))
                        ->where('pengeluaran.is_delete','=',0)
                        ->orderBy('pengeluaran.id','DESC');

             $pemasukan = DB::table('pemasukan')->join('kategori_pemasukan','kategori_pemasukan.id','=','pemasukan.kategori_pemasukan_id')
                        ->select('pemasukan.id','pemasukan.nama_pemasukan as nama','kategori_pemasukan.nama_pemasukan as kategori','pemasukan.jumlah_pemasukan','pemasukan.tanggal_pemasukan','pemasukan.keterangan', DB::raw("'pemasukan' as type"))
                        ->where('pemasukan.is_delete','=',0)
                        ->orderBy('pemasukan.id','DESC')
                        ->union($pengeluaran)
                        ->get();
            
            

              return response()->json([
                "status" => 201,
                "message" => "Detail laporan keuangan Berhasil Ditampilkan",
                "data" => [
                    "harian" => [
                        "pemasukan" =>   $calculation_pemasukan_harian,
                        "pengeluaran" => $calculation_pengeluaran_harian
                    ],
                    'mingguan' => [
                        "pemasukan" =>  $calculation_pemasukan_mingguan,
                        "pengeluaran" =>  $calculation_pengeluaran_mingguan 
                    ],
                    'bulanan' => [
                         "pemasukan" =>  $calculation_pemasukan_bulanan,
                        "pengeluaran" =>  $calculation_pengeluaran_bulanan 
                    ],
                    'tahunan' => [
                         "pemasukan" =>  $calculation_pemasukan_tahunan,
                        "pengeluaran" =>  $calculation_pengeluaran_tahunan 
                    ],
                    'data_pemasukan_pengeluaran' => $pemasukan
                ]
            
            ]);
           


        }catch(\Exception $e){
            return response()->json([
                "status" => 400,
                 "message" => 'Error'.$e->getMessage(),
                "data" => null
            ]);
        }
    }

     public function currencydata(){
          try{
            $currency = CurrencyData::where('is_delete','=',0)->where('is_active','=',1)->get();
                if($currency != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "currency Berhasil Ditampilkan",
                        "data" => $currency
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "currency Tidak ada",
                        "data" => null
                    ]);
                }
            }catch(\Exception $e){
                return response()->json([
                    "status" => 400,
                    "message" => 'Error'.$e->getMessage(),
                    "data" => null
                ]);   
            }
    }   


    public function kategori_laporan_keuangan(){
          try{
            $kategorilaporankeuangan = KategoriLaporanKeuangan::where('is_delete','=',0)->where('is_active','=',1)->get();
                if($kategorilaporankeuangan != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "kategorilaporankeuangan Berhasil Ditampilkan",
                        "data" => $kategorilaporankeuangan
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "kategorilaporankeuangan Tidak ada",
                        "data" => null
                    ]);
                }
            }catch(\Exception $e){
                return response()->json([
                    "status" => 400,
                    "message" => 'Error'.$e->getMessage(),
                    "data" => null
                ]);   
            }
    }   

    public function kategori_pemasukan(){
          try{
            $kategoripemasukan = KategoriPemasukan::where('is_delete','=',0)->where('is_active','=',1)->get();
                if($kategoripemasukan != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "Kategori Pemasukan Berhasil Ditampilkan",
                        "data" => $kategoripemasukan
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "Kategori Pemasukan Tidak ada",
                        "data" => null
                    ]);
                }
            }catch(\Exception $e){
                return response()->json([
                    "status" => 400,
                    "message" => 'Error'.$e->getMessage(),
                    "data" => null
                ]);   
            }
    }   

    public function kategori_pengeluaran(){
           try{
            $kategoripengeluaran = KategoriPengeluaran::where('is_delete','=',0)->where('is_active','=',1)->get();
                if($kategoripengeluaran != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "Kategori Pengeluaran Berhasil Ditampilkan",
                        "data" => $kategoripengeluaran
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "Kategori Pengeluaran Tidak ada",
                        "data" => null
                    ]);
                }
            }catch(\Exception $e){
                return response()->json([
                    "status" => 400,
                    "message" => 'Error'.$e->getMessage(),
                    "data" => null
                ]);   
            }
    }

    public function kategori_tagihan(){
           try{
            $kategoritagihan = KategoriTagihan::where('is_delete','=',0)->where('is_active','=',1)->get();
                if($kategoritagihan != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "kategoritagihan Berhasil Ditampilkan",
                        "data" => $kategoritagihan
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "kategoritagihan Tidak ada",
                        "data" => null
                    ]);
                }
            }catch(\Exception $e){
                return response()->json([
                    "status" => 400,
                    "message" => 'Error'.$e->getMessage(),
                    "data" => null
                ]);   
            }
    }

    public function jenis_simpanan(){
        try{
            $jenissimpanan = JenisSimpanan::where('is_delete','=',0)->where('is_active','=',1)->get();
                if($jenissimpanan != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "Jenis Simpanan Berhasil Ditampilkan",
                        "data" => $jenissimpanan
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "Jenis Simpanan Tidak ada",
                        "data" => null
                    ]);
                }
            }catch(\Exception $e){
                return response()->json([
                    "status" => 400,
                    "message" => 'Error'.$e->getMessage(),
                    "data" => null
                ]);   
            }
    }

    public function tujuan_simpanan(){
        try{
            $tujuansimpanan = TujuanSimpanan::where('is_delete','=',0)->where('is_active','=',1)->get();
                if($tujuansimpanan != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "tujuan simpanan Berhasil Ditampilkan",
                        "data" => $tujuansimpanan
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "tujuan simpanan Tidak ada",
                        "data" => null
                    ]);
                }
            }catch(\Exception $e){
                return response()->json([
                    "status" => 400,
                    "message" => 'Error'.$e->getMessage(),
                    "data" => null
                ]);   
            }
    }

    public function kategori_tujuan_keuangan(){
          try{
            $kategoritujuankeuangan = KategoriTujuanKeuangan::where('is_delete','=',0)->where('is_active','=',1)->get();
                if($kategoritujuankeuangan != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "kategoritujuankeuangan Berhasil Ditampilkan",
                        "data" => $kategoritujuankeuangan
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "kategoritujuankeuangan Tidak ada",
                        "data" => null
                    ]);
                }
            }catch(\Exception $e){
                return response()->json([
                    "status" => 400,
                    "message" => 'Error'.$e->getMessage(),
                    "data" => null
                ]);   
            }
    }

    public function create(Request $request){
        $input = $request->all();
        
        try{
            $validator = Validator::make($input, [
                'nama_laporan_keuangan' => 'text',
                'deskripsi' => 'text',
                'total_pemasukan' => 'required',
                'total_pengeluaran' => 'required',
                'total_tabungan' => 'required', 
                'total_hutang' => 'required',
                'total_piutang' => 'required', 
                'total_balance' => "text",
                'currency_id' => 'required',
                'kategori_laporan_keuangan_id' => 'required',
            ]);

            if($validator->fails()){
                return response()->json([
                    "status" => 400,
                    "errors" => $validator->errors(),
                    "data" => null
                ]);          
            }
            $laporankeuangan = new Pemasukan;
            $laporankeuangan->user_id = Auth::id();
            $laporankeuangan->kategori_laporan_keuangan_id = $input['kategori_laporan_keuangan_id'];
            $laporankeuangan->nama_laporan_keuangan = $input['nama_laporan_keuangan'];
            $laporankeuangan->deskripsi = $input['deskripsi'];
            $laporankeuangan->currency_id = $input['currency_id'];
            $laporankeuangan->total_pemasukan = $input['total_pemasukan'];
            $laporankeuangan->total_pengeluaran = $input['total_pengeluaran'];
            $laporankeuangan->total_tabungan = $input['total_tabungan'];
            $laporankeuangan->total_balance = $input['total_balance'];
            $laporankeuangan->status_transaksi_berulang = $input['status_transaksi_berulang'];
            $laporankeuangan->is_deleted = 0;
            $laporankeuangan->save();
            
            return response()->json([
                "status" => 201,
                "message" => "Laporan Keuangan created successfully.",
                "data" => $pemasukan
            ]);
        }catch(\Exception $e){
            return response()->json([
                "status" => 401,
                "message" => 'Error'.$e->getMessage(),
                "data" => null
            ]);
        } 
    }

   
}
