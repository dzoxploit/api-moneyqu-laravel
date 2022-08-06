<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Piutang;
use App\Models\CurrencyData;
use App\Models\Settings;
use AmrShawky\LaravelCurrency\Facade\Currency;
use Auth;
use DB;
use Validator;
use Carbon\Carbon;


class PiutangController extends Controller
{
    public function index(Request $request){
        try{
            $term = $request->get('search');
            $settings = Settings::where('user_id',Auth::id())->first();
            $id = $request->get('id');

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

            $calculatepiutangdibayar = $calculatepiutangsudahibayar + $calculatepiutangbelumdibayarsebagian;
            $calculatepiutangbelumdibayar =$calculatepiutangbelumdibayarsamsek + $calculatepiutangbelumdibayarsebagiansisa;
            
            if($id != null){
                $piutang = Piutang::where(function ($query) use ($term) {
                                $query->where('nama_piutang', "like", "%" . $term . "%");
                                $query->orWhere('deksripsi', "like", "%" . $term . "%");
                        })
            ->where('user_id',Auth::id())   
            ->where('is_delete','=',0)
            ->where('id',$id)
            ->orderBy('id','DESC')
            ->first();
            }else{
                $piutang = Piutang::where(function ($query) use ($term) {
                                $query->where('nama_piutang', "like", "%" . $term . "%");
                                $query->orWhere('deksripsi', "like", "%" . $term . "%");
                        })
            ->where('user_id',Auth::id())   
            ->where('is_delete','=',0)
            ->orderBy('id','DESC')
            ->get();
            }
            

            return response()->json([
                "status" => 201,
                "message" => "Piutang Berhasil Ditampilkan",
                "data" => [
                    "total_piutang_sudah_dibayar" => $calculatepiutangdibayar,
                    "total_piutang_belum_dibayar" => $calculatepiutangbelumdibayar ,
                    "data_piutang" => $piutang
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

    public function create(Request $request){
        $input = $request->all();
        
        try{
            $validator = Validator::make($input, [
                'nama_piutang' => 'required',
                'no_telepon' => 'required',
                'deksripsi' => 'nullable',
                'jumlah_hutang' => 'required',
                'currency_id' => 'required', 
                'tanggal_piutang' => 'required'
            ]);

            if($validator->fails()){
                return response()->json([
                    "status" => 400,
                    "errors" => $validator->errors(),
                    "data" => null
                ]);          
            }
            $piutang = new Piutang;
            $piutang->user_id = Auth::id();
            $piutang->nama_piutang = $input['nama_piutang'];
            $piutang->no_telepon = $input['no_telepon'];
            $piutang->deksripsi = $input['deksripsi'];
            $piutang->jumlah_hutang = $input['jumlah_hutang'];
            $piutang->currency_id = $input['currency_id'];
            $piutang->jumlah_piutang_dibayar = 0;
            $piutang->tanggal_piutang = $input['tanggal_piutang'];
            $piutang->status_piutang = 0;
            $piutang->is_delete = 0;
            $validation = balancedata($piutang->jumlah_hutang);
            
                    if($validation == true){
                        $piutang->save();
                    
                        return response()->json([
                            "status" => 201,
                            "message" => "Piutang created successfully.",
                            "data" => $piutang
                        ]);
                    }else{

                        return response()->json([
                            "status" => 400,
                            "errors" => "Saldo Tidak Mencukupi",
                            "data" => null
                        ]);          
                    }
        }catch(\Exception $e){
            return response()->json([
                "status" => 401,
                "message" => 'Error'.$e->getMessage(),
                "data" => null
            ]);
        } 
    }


    public function update(Request $request, $id){
        if ($request->isMethod('get')){
            try{
                $piutang = Piutang::where('id',$id)->where('is_delete','=',0)->where('user_id',Auth::id())->first();
                if($piutang != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "Piutang Berhasil Ditampilkan",
                        "data" => $piutang
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "Piutang Tidak ada",
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
        
        }else{
        
                $input = $request->all();
                try{
            
                $validator = Validator::make($input, [
                    'nama_piutang' => 'required',
                    'no_telepon' => 'required',
                    'deksripsi' => 'nullable',
                    'jumlah_hutang' => 'required',
                    'currency_id' => 'required', 
                    'tanggal_piutang' => 'required',
                ]);

                    if($validator->fails()){
                        $piutang = Piutang::where('id',$id)->where('is_delete','=',0)->firstOrFail();
                        $piutang->user_id = Auth::id();
                        $piutang->nama_piutang = $input['nama_piutang'];
                        $piutang->no_telepon = $input['no_telepon'];
                        $piutang->deksripsi = $input['deksripsi'];
                        $piutang->jumlah_hutang = $input['jumlah_hutang'];
                        $piutang->tanggal_piutang = $input['tanggal_piutang'];
                        $piutang->currency_id = $input['currency_id'];
                        $piutang->is_delete = 0;
                       $validation = balancedata($piutang->jumlah_hutang);
            
                    if($validation == true){
                        $piutang->save();
                    
                        return response()->json([
                            "status" => 201,
                            "message" => "Piutang created successfully.",
                            "data" => $piutang
                        ]);
                    }else{

                        return response()->json([
                            "status" => 400,
                            "errors" => "Saldo Tidak Mencukupi",
                            "data" => null
                        ]);          
                    }
                    }
                    $piutang = Piutang::where('id',$id)->where('is_delete','=',0)->firstOrFail();
                    $piutang->user_id = Auth::id();
                    $piutang->nama_piutang = $input['nama_piutang'];
                    $piutang->no_telepon = $input['no_telepon'];
                    $piutang->deksripsi = $input['deksripsi'];
                    $piutang->currency_id = $input['currency_id'];
                    $piutang->jumlah_hutang = $input['jumlah_hutang'];
                    $piutang->is_delete = 0;
                    
                    $validation = balancedata($piutang->jumlah_hutang);
            
                    if($validation == true){
                        $piutang->save();
                    
                        return response()->json([
                            "status" => 201,
                            "message" => "Piutang created successfully.",
                            "data" => $piutang
                        ]);
                    }else{

                        return response()->json([
                            "status" => 400,
                            "errors" => "Saldo Tidak Mencukupi",
                            "data" => null
                        ]);          
                    }

                }catch(\Exception $e){
                    return response()->json([
                        "status" => 401,
                        "message" => 'Error'.$e->getMessage(),
                        "data" => null
                    ]);
                }
        } 
    }

    
    public function update_bayaran_piutang($id){
         try{
            
                    $piutang = Piutang::where('id',$id)->where('is_delete','=',0)->firstOrFail();
                    $piutang->jumlah_piutang_dibayar = $piutang->jumlah_hutang;
                    $piutang->status_piutang = 1;
                    $piutang->tanggal_piutang_dibayar = Carbon::now();
                    $piutang->is_delete = 0;
                    $piutang->save();

                    return response()->json([
                        "status" => 201,
                        "message" => "Pembayaran Piutang created successfully.",
                        "data" => $piutang
                    ]);

                }catch(\Exception $e){
                    return response()->json([
                        "status" => 401,
                        "message" => 'Error'.$e->getMessage(),
                        "data" => null
                    ]);
                }
    }
    

    public function destroy_piutang($id){
         $piutang = Piutang::where('id',$id)->where('is_delete','=',0)->firstOrFail();
         $piutang->is_delete = 1;
         $piutang->deleted_at = Carbon::now();
         $piutang->save();

          return response()->json([
                        "status" => 201,
                        "message" => 'delete piutang succesfully',
                        "data" => $piutang 
          ]);
    }

}
