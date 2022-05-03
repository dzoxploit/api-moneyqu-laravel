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
use Validator;

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
              
            $calculation = (int)$pemasukan + (int)$piutangdibayar - (int)$piutangbelumdibayar - (int)$hutangdibayar + (int)$hutangbelumdibayar - (int)$simpanan - (int)$pengeluaran - (int)$tujuankeuangan - (int)$tagihan;
            
            $pemasukan = (int)$pemasukan + (int)$piutangdibayar + (int)$hutangbelumdibayar;

            $pengeluaran =  (int)$piutangbelumdibayar + (int)$hutangdibayar + (int)$simpanan + (int)$pengeluaran + (int)$tujuankeuangan + (int)$tagihan;
            
            return response()->json([
                "status" => 201,
                "message" => "Kategori Pemasukan Berhasil Ditampilkan",
                "data" => [
                    "calculation" => $calculation,
                    'pemasukan' => $pemasukan,
                    'pengeluaran' => $pengeluaran
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
                                                ])->where('created_at','=',Carbon::now())->sum('jumlah_pemasukan');

                        $pengeluaran_harian = Pengeluaran::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                    ['currency_id', '=', $settings->currency_id],
                                                    ['hutang_id', '=', null]
                                                ])->where('created_at','=',Carbon::now())->sum('jumlah_pengeluaran');

                        $simpanan_harian = Simpanan::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                    ['currency_id', '=', $settings->currency_id]
                                                ])->where('created_at','=',Carbon::now())->sum('jumlah_simpanan');
                                                
                        $tujuankeuangan_harian = TujuanKeuangan::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                ])->orWhere('simpanan_id','=',null)
                                                ->orWhere('hutang_id','=',null)
                                                ->where('created_at','=',Carbon::now())
                                                ->sum('nominal_goals');

                        //piutang
                        
                        $calculatepiutangbelumdibayarsamsek_harian = Piutang::Where(
                                                                [
                                                                    ['is_delete', '=', 0],
                                                                    ['user_id', '=', Auth::id()],
                                                                    ['currency_id', '=', $settings->currency_id],
                                                                    ['status_piutang','=','0'],
                                                                    ['jumlah_piutang_dibayar','=',0],
                                                                    ['created_at','=',Carbon::now()]
                                                                ])->sum('jumlah_hutang');

                        $calculatepiutangbelumdibayarsebagian_harian = Piutang::Where(
                                                                [
                                                                    ['is_delete', '=', 0],
                                                                    ['user_id', '=', Auth::id()],
                                                                    ['currency_id', '=', $settings->currency_id],
                                                                    ['status_piutang','=','0'],
                                                                    ['jumlah_piutang_dibayar','!=',0],
                                                                    ['created_at','=',Carbon::now()]
                                                                ])->sum('jumlah_piutang_dibayar');
            
                        $calculatepiutangbelumdibayarsebagiansisa_harian = Piutang::Where(
                                                                [
                                                                    ['is_delete', '=', 0],
                                                                    ['user_id', '=', Auth::id()],
                                                                    ['currency_id', '=', $settings->currency_id],
                                                                    ['status_piutang','=','0'],
                                                                    ['jumlah_piutang_dibayar','!=',0],
                                                                    ['created_at','=',Carbon::now()]
                                                                ])->sum('jumlah_hutang');


                        $calculatepiutangsudahibayar_harian = Piutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_piutang','=','1'],
                                                            ['created_at','=',Carbon::now()]
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
                                                            ['created_at','=',Carbon::now()]
                                                        ])->sum('jumlah_hutang');
                        
                        $calculatehutangbelumdibayarsebagian_harian = Hutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_hutang','=','0'],
                                                            ['jumlah_hutang_dibayar','!=','0'],
                                                            ['created_at','=',Carbon::now()]
                                                        ])->sum('jumlah_hutang_dibayar');

                        $calculatehutangbelumdibayarsebagiansisa_harian = Hutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_hutang','=','0'],
                                                            ['jumlah_hutang_dibayar','!=','0'],
                                                            ['created_at','=',Carbon::now()]
                                                        ])->sum('jumlah_hutang_dibayar');


                        $calculatehutangsudahdibayar_harian = Hutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_hutang','=','1'],
                                                            ['created_at','=',Carbon::now()]
                                                        ])->sum('jumlah_hutang');
                        
                        $hutangdibayar_harian = $calculatehutangsudahdibaya_harian + $calculatehutangbelumdibayarsebagiansisa_harian;
                        $hutangbelumdibayar_harian =  $calculatehutangbelumdibayarsamsek_harian +  $calculatehutangbelumdibayarsebagiansisa_harian;
                        //end Hutang

                        $tagihan_harian = Tagihan::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                    ['status_tagihan_lunas','=',1],
                                                    ['created_at','=',Carbon::now()]
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
                                                ])->sum('jumlah_pemasukan')->groupBy(DB::raw('WEEK(created_at)'));

                        $pengeluaran_mingguan = Pengeluaran::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                    ['currency_id', '=', $settings->currency_id],
                                                    ['hutang_id', '=', null]
                                                ])->sum('jumlah_pengeluaran')->groupBy(DB::raw('WEEK(created_at)'));

                        $simpanan_mingguan = Simpanan::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                    ['currency_id', '=', $settings->currency_id]
                                                ])->sum('jumlah_simpanan')->groupBy(DB::raw('WEEK(created_at)'));;
                                                
                        $tujuankeuangan_mingguan = TujuanKeuangan::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                ])->orWhere('simpanan_id','=',null)
                                                ->orWhere('hutang_id','=',null)
                                                ->sum('nominal_goals')->groupBy(DB::raw('WEEK(created_at)'));

                        //piutang
                        
                        $calculatepiutangbelumdibayarsamsek_mingguan = Piutang::Where(
                                                                [
                                                                    ['is_delete', '=', 0],
                                                                    ['user_id', '=', Auth::id()],
                                                                    ['currency_id', '=', $settings->currency_id],
                                                                    ['status_piutang','=','0'],
                                                                    ['jumlah_piutang_dibayar','=',0],
                                                                ])->sum('jumlah_hutang')->groupBy(DB::raw('WEEK(created_at)'));;

                        $calculatepiutangbelumdibayarsebagian_mingguan = Piutang::Where(
                                                                [
                                                                    ['is_delete', '=', 0],
                                                                    ['user_id', '=', Auth::id()],
                                                                    ['currency_id', '=', $settings->currency_id],
                                                                    ['status_piutang','=','0'],
                                                                    ['jumlah_piutang_dibayar','!=',0]
                                                                ])->sum('jumlah_piutang_dibayar')->groupBy(DB::raw('WEEK(created_at)'));;
            
                        $calculatepiutangbelumdibayarsebagiansisa_mingguan = Piutang::Where(
                                                                [
                                                                    ['is_delete', '=', 0],
                                                                    ['user_id', '=', Auth::id()],
                                                                    ['currency_id', '=', $settings->currency_id],
                                                                    ['status_piutang','=','0'],
                                                                    ['jumlah_piutang_dibayar','!=',0]
                                                                ])->sum('jumlah_hutang')->groupBy(DB::raw('WEEK(created_at)'));;


                        $calculatepiutangsudahibayar_mingguan = Piutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_piutang','=','1']
                                                        ])->sum('jumlah_hutang')->groupBy(DB::raw('WEEK(created_at)'));;

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
                                                        ])->sum('jumlah_hutang')->groupBy(DB::raw('WEEK(created_at)'));;
                        
                        $calculatehutangbelumdibayarsebagian_mingguan = Hutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_hutang','=','0'],
                                                            ['jumlah_hutang_dibayar','!=','0']
                                                        ])->sum('jumlah_hutang_dibayar')->groupBy(DB::raw('WEEK(created_at)'));;

                        $calculatehutangbelumdibayarsebagiansisa_mingguan = Hutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_hutang','=','0'],
                                                            ['jumlah_hutang_dibayar','!=','0']
                                                        ])->sum('jumlah_hutang_dibayar')->groupBy(DB::raw('WEEK(created_at)'));;


                        $calculatehutangsudahdibayar_mingguan = Hutang::Where(
                                                        [
                                                            ['is_delete', '=', 0],
                                                            ['user_id', '=', Auth::id()],
                                                            ['currency_id', '=', $settings->currency_id],
                                                            ['status_hutang','=','1']
                                                        ])->sum('jumlah_hutang')->groupBy(DB::raw('WEEK(created_at)'));;
                        
                        $hutangdibayar_mingguan = $calculatehutangsudahdibaya_mingguan + $calculatehutangbelumdibayarsebagiansisa_mingguan;
                        $hutangbelumdibayar_mingguan =  $calculatehutangbelumdibayarsamsek_mingguan +  $calculatehutangbelumdibayarsebagiansisa_mingguan;
                        //end Hutang

                        $tagihan_mingguan = Tagihan::Where(
                                                [
                                                    ['is_delete', '=', 0],
                                                    ['user_id', '=', Auth::id()],
                                                    ['status_tagihan_lunas','=',1]
                                                ])->sum('jumlah_tagihan')->groupBy(DB::raw('WEEK(created_at)'));;
                        
                        $calculation_pemasukan_mingguan = (int)$pemasukan_mingguan + (int)$piutangdibayar_mingguan + (int)$hutangbelumdibayar_mingguan ;
                        $calculation_pengeluaran_mingguan = (int)$piutangbelumdibayar_mingguan + (int)$hutangdibayar_mingguan + (int)$simpanan_mingguan + (int)$pengeluaran_mingguan + (int)$tujuankeuangan_mingguan + (int)$tagihan_mingguan;

            /** End Mingguan */





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
