<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Simpanan;
use App\Models\Settings;
use Validator;
use Auth;
use DB;
use Carbon\Carbon;
use App\Helpers\Helper;

class SimpananController extends Controller
{
    
    public function index(Request $request){
        try{
            $term = $request->get('search');
            $settings = Settings::where('user_id',Auth::id())->first();
            $id = $request->get('id');
            
            $calculatesimpanan = Simpanan::Where(
                                    [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                        ['currency_id', '=', $settings->currency_id]
                                    ])->sum('jumlah_simpanan');
            $calculatesimpananharian = Simpanan::Where(
                                    [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                        ['currency_id', '=', $settings->currency_id]
                                    ])->whereDay('created_at',date('d'))->sum('jumlah_simpanan');
            if($id != null){
            $simpanan = DB::table('simpanan')->join('tujuan_simpanan','tujuan_simpanan.id','=','simpanan.tujuan_simpanan_id')
                        ->join('jenis_simpanan','jenis_simpanan.id','=','simpanan.jenis_simpanan_id')
                        ->select('simpanan.id','simpanan.deskripsi','simpanan.jumlah_simpanan','tujuan_simpanan.nama_tujuan_simpanan','jenis_simpanan.nama_jenis_simpanan','simpanan.status_simpanan')
                        ->where('simpanan.user_id',Auth::id())->where(function ($query) use ($term) {
                                $query->where('simpanan.deskripsi', "like", "%" . $term . "%");
                                $query->orWhere('tujuan_simpanan.nama_tujuan_simpanan', "like", "%" . $term . "%");
                                $query->orWhere('jenis_simpanan.nama_jenis_simpanan', "=", $term);
                        })
                        ->where('simpanan.id','=', $id)
                        ->where('simpanan.is_delete','=',0)
                        ->orderBy('simpanan.id','DESC')
                        ->first();
            }else{
                  $simpanan = DB::table('simpanan')->join('tujuan_simpanan','tujuan_simpanan.id','=','simpanan.tujuan_simpanan_id')
                        ->join('jenis_simpanan','jenis_simpanan.id','=','simpanan.jenis_simpanan_id')
                        ->select('simpanan.id','simpanan.deskripsi','simpanan.jumlah_simpanan','tujuan_simpanan.nama_tujuan_simpanan','jenis_simpanan.nama_jenis_simpanan','simpanan.status_simpanan')
                        ->where('simpanan.user_id',Auth::id())->where(function ($query) use ($term) {
                                $query->where('simpanan.deskripsi', "like", "%" . $term . "%");
                                $query->orWhere('tujuan_simpanan.nama_tujuan_simpanan', "like", "%" . $term . "%");
                                $query->orWhere('jenis_simpanan.nama_jenis_simpanan', "=", $term);
                        })
                        ->where('simpanan.is_delete','=',0)
                        ->orderBy('simpanan.id','DESC')
                        ->get();
            }
        
            return response()->json([
                "status" => 201,
                "message" => "Simpanan Berhasil Ditampilkan",
                "data" => [
                    "total_simpanan" => $calculatesimpanan,
                    "total_simpanan_harian" => $calculatesimpananharian,
                    "data_simpanan" => $simpanan
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

    public function index_non_active(Request $request){
        try{
         
            $settings = Settings::where('user_id',Auth::id())->first();
            
                  $simpanan = DB::table('simpanan')->join('tujuan_simpanan','tujuan_simpanan.id','=','simpanan.tujuan_simpanan_id')
                        ->join('jenis_simpanan','jenis_simpanan.id','=','simpanan.jenis_simpanan_id')
                        ->select('simpanan.id','simpanan.deskripsi','simpanan.jumlah_simpanan','tujuan_simpanan.nama_tujuan_simpanan','jenis_simpanan.nama_jenis_simpanan','simpanan.status_simpanan')
                        ->where('simpanan.is_delete','=',0)
                        ->where('simpanan.status_simpanan','=',0)
                        ->orderBy('simpanan.id','DESC')
                        ->get();
        
            return response()->json([
                "status" => 201,
                "message" => "Simpanan Berhasil Ditampilkan",
                "data" => [
                    "data_simpanan" => $simpanan
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
                'tujuan_simpanan_id' => 'required',
                'jumlah_simpanan' => 'required',
                'deskripsi' => 'required',
                'jenis_simpanan_id' => 'required',
                'currency_id' => 'required', 
                'status_simpanan' => 'required',
            ]);

            if($validator->fails()){
                return response()->json([
                    "status" => 400,
                    "errors" => $validator->errors(),
                    "data" => null
                ]);          
            }
            $simpanan = new Simpanan;
            $simpanan->user_id = Auth::id();
            $simpanan->jumlah_simpanan = $input['jumlah_simpanan'];
            $simpanan->tujuan_simpanan_id = $input['tujuan_simpanan_id'];
            $simpanan->currency_id = $input['currency_id'];
            $simpanan->deskripsi = $input['deskripsi'];
            $simpanan->jenis_simpanan_id = $input['jenis_simpanan_id'];
            $simpanan->is_delete = 0;
            $simpanan->status_simpanan = $input['status_simpanan'];
            
            $validation = Helper::balancedata($simpanan->jumlah_simpanan);
            
                    if($validation == true && $simpanan->status_simpanan == 1){
                        $simpanan->save();
                    
                        return response()->json([
                            "status" => 201,
                            "message" => "Simpanan created successfully.",
                            "data" => $simpanan
                        ]);
                    }else if($simpanan->status_simpanan == 0){
                        $simpanan->save();
                    
                        return response()->json([
                            "status" => 201,
                            "message" => "Simpanan created successfully.",
                            "data" => $simpanan
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
                $simpanan = Simpanan::where('id',$id)->where('is_delete','=',0)->where('user_id',Auth::id())->first();
                if($simpanan != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "Simpanan Berhasil Ditampilkan",
                        "data" => $simpanan
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "simpanan Tidak ada",
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
                        'tujuan_simpanan_id' => 'required',
                        'jumlah_simpanan' => 'required',
                        'deskripsi' => 'required',
                        'jenis_simpanan_id' => 'required',
                        'currency_id' => 'required', 
                        'status_simpanan' => 'required',
                    ]);

                    if($validator->fails()){
                        $simpanan = Simpanan::where('id',$id)->where('is_delete','=',0)->where('user_id',Auth::id())->firstOrFail();
                        $simpanan->user_id = Auth::id();
                        $simpanan->jumlah_simpanan = $input['jumlah_simpanan'];
                        $simpanan->tujuan_simpanan_id = $input['tujuan_simpanan_id'];
                        $simpanan->currency_id = $input['currency_id'];
                        $simpanan->deskripsi = $input['deskripsi'];
                        $simpanan->jenis_simpanan_id = $input['jenis_simpanan_id'];
                        $simpanan->status_simpanan = $input['status_simpanan'];
                        $simpanan->is_delete = 0;
                       $validation = Helper::balancedata($simpanan->jumlah_simpanan);
            
                        if($validation == true && $simpanan->status_simpanan == 1){
                            $simpanan->save();
                        
                            return response()->json([
                                "status" => 201,
                                "message" => "Simpanan created successfully.",
                                "data" => $simpanan
                            ]);
                        }else if($simpanan->status_simpanan == 0){
                            $simpanan->save();
                        
                            return response()->json([
                                "status" => 201,
                                "message" => "Simpanan created successfully.",
                                "data" => $simpanan
                            ]);
                        }else{
                            return response()->json([
                                "status" => 400,
                                "errors" => "Saldo Tidak Mencukupi",
                                "data" => null
                            ]);          
                        }
                    }
                     $simpanan = Simpanan::where('id',$id)->where('is_delete','=',0)->where('user_id',Auth::id())->firstOrFail();
                        $simpanan->user_id = Auth::id();
                        $simpanan->jumlah_simpanan = $input['jumlah_simpanan'];
                        $simpanan->tujuan_simpanan_id = $input['tujuan_simpanan_id'];
                        $simpanan->currency_id = $input['currency_id'];
                        $simpanan->deskripsi = $input['deskripsi'];
                        $simpanan->jenis_simpanan_id = $input['jenis_simpanan_id'];
                        $simpanan->is_delete = 0;
                        $simpanan->status_simpanan = $input['status_simpanan'];
                        $simpanan->save();

                    
                   $validation = Helper::balancedata($simpanan->jumlah_simpanan);
            
                    if($validation == true){
                        $simpanan->save();
                    
                        return response()->json([
                            "status" => 201,
                            "message" => "Simpanan created successfully.",
                            "data" => $simpanan
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

    public function destroy_simpanan($id){
         $simpanan = Simpanan::where('id',$id)->where('user_id',Auth::id())->where('is_delete','=',0)->firstOrFail();
         $simpanan->is_delete = 1;
         $simpanan->deleted_at = Carbon::now();
         $simpanan->save();

          return response()->json([
                        "status" => 201,
                        "message" => 'delete simpanan succesfully',
                        "data" => $simpanan
          ]);
    }    
}
