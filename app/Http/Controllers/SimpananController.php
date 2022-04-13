<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Simpanan;
use App\Models\Settings;
use Validator;
use Auth;
use DB;
use Carbon\Carbon;

class SimpananController extends Controller
{
    
    public function index(Request $request){
        try{
            $term = $request->get('search');
            $settings = Settings::where('user_id',Auth::id())->first();
            $calculatesimpanan = Simpanan::Where(
                                    [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                        ['currency_id', '=', $settings->currency_id]
                                    ])->sum('jumlah_simpanan');
            

            $simpanan = Simpanan::where([
                [function ($query) use ($request){
                    if (($term = $request->term)){
                        $query->orWhere('deskripsi', 'LIKE', '%' . $term .'%')->get();
                    }
                }]
            ])    
           ->where('user_id',Auth::id())   
            ->where('is_delete','=',0)
            ->orderBy('id','DESC')
            ->paginate(10);

            return response()->json([
                "status" => 201,
                "message" => "Simpanan Berhasil Ditampilkan",
                "data" => [
                    "total_simpanan" => $calculatesimpanan,
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
            $simpanan->status_simpanan = 0;
            $simpanan->save();
            
            return response()->json([
                "status" => 201,
                "message" => "Simpanan created successfully.",
                "data" => $simpanan
            ]);
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
                    ]);

                    if($validator->fails()){
                        $simpanan = Simpanan::where('id',$id)->where('is_delete','=',0)->where('user_id',Auth::id())->firstOrFail();
                        $simpanan->user_id = Auth::id();
                        $simpanan->jumlah_simpanan = $input['jumlah_simpanan'];
                        $simpanan->tujuan_simpanan_id = $input['tujuan_simpanan_id'];
                        $simpanan->currency_id = $input['currency_id'];
                        $simpanan->deskripsi = $input['deskripsi'];
                        $simpanan->jenis_simpanan_id = $input['jenis_simpanan_id'];
                        $simpanan->is_delete = 0;
                        $simpanan->save();

                        return response()->json([
                            "status" => 201,
                            "message" => "Simpanan created successfully.",
                            "data" => $simpanan
                        ]);
                    }
                     $simpanan = Simpanan::where('id',$id)->where('is_delete','=',0)->where('user_id',Auth::id())->firstOrFail();
                        $simpanan->user_id = Auth::id();
                        $simpanan->jumlah_simpanan = $input['jumlah_simpanan'];
                        $simpanan->tujuan_simpanan_id = $input['tujuan_simpanan_id'];
                        $simpanan->currency_id = $input['currency_id'];
                        $simpanan->deskripsi = $input['deskripsi'];
                        $simpanan->jenis_simpanan_id = $input['jenis_simpanan_id'];
                        $simpanan->is_delete = 0;
                        $simpanan->save();

                    
                    return response()->json([
                        "status" => 201,
                        "message" => "Simpanan created successfully.",
                        "data" => $simpanan
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
