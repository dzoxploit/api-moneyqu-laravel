<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriTujuanKeuangan;
use App\Models\TujuanKeuangan;
use App\Models\CurrencyData;
use App\Models\Settings;
use App\Models\GoalsTujuanKeuangan;
use AmrShawky\LaravelCurrency\Facade\Currency;
use Auth;
use DB;

class TujuanKeuanganController extends Controller
{
    public function index(Request $request){
           try{
            $term = $request->get('search');
            $settings = Settings::where('user_id',Auth::id())->first();

            $tujuankeuangan = TujuanKeuangan::where([
                ['nama_tujuan_keuangan', '!=', NULL],
                [function ($query) use ($request){
                    if (($term = $request->term)){
                        $query->orWhere('nama_tujuan_keuangan', 'LIKE', '%' . $term .'%')->get();
                    }
                }]
            ])    
            ->where('user_id',Auth::id())
            ->orderBy('id','DESC')
            ->paginate(10);

            return response()->json([
                "status" => 201,
                "message" => "Tujuan Keuangan Berhasil Ditampilkan",
                "data" =>  $tujuankeuangan
            
            ]);
        }catch(\Exception $e){
            return response()->json([
                "status" => 400,
                 "message" => 'Error'.$e->getMessage(),
                "data" => null
            ]);
        }
    }
    
    public function detail_tujuan_keuangan($id){
         try{
                $goalstujuankeuangan = GoalsTujuanKeuangan::where('tujuan_keuangan_id',$id)->where('user_id',Auth::id())->where('is_delete','=',0)->first();
                if($goalstujuankeuangan != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "Goals Tujuan Keuangan Ditampilkan",
                        "data" => $tujuankeuangan
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "Tujuan keuangan Tidak ada",
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
                'nama_tujuan_keuangan' => 'required',
                'status_fleksibel' => 'required',
                'nominal' => 'required',
                'kategori_tujuan_keuangan_id' => 'required',
                'currency_id' => 'required',
                'tanggal' => 'required',
                'status_tujuan_keuangan' => 'required', 
                'hutang_id' => 'nullable', 
                'simpanan_id' => 'nullable'
            ]);

            if($validator->fails()){
                return response()->json([
                    "status" => 400,
                    "errors" => $validator->errors(),
                    "data" => null
                ]);          
            }
            $tujuankeuangan = new TujuanKeuangan;
            $tujuankeuangan->user_id = Auth::id();
            $tujuankeuangan->nama_tujuan_keuangan = $input['nama_tujuan_keuangan'];
            $tujuankeuangan->status_fleksibel = $input['status_fleksibel'];
            $tujuankeuangan->currency_id = $input['currency_id'];
            $tujuankeuangan->kategori_tujuan_keuangan = $input['kategori_tujuan_keuangan'];
            $tujuankeuangan->tanggal = $input['tanggal'];
            $tujuankeuangan->nominal = $input['nominal'];
            $tujuankeuangan->nominal_goals = 0;
            $tujuankeuangan->simpanan_id = $input['simpanan_id'];
            $tujuankeuangan->status_tujuan_keuangan = 0;
            if($request->get('id') != null){
                $tujuankeuangan->hutang_id = $request->get('id');
            }
            else if($request->get('simpanan_id') != null){
                $tujuankeuangan->simpanan_id = $request->get('simpanan_id');
            }

            $tujuankeuangan->is_delete = 0;
            $tujuankeuangan->save();
            
            return response()->json([
                "status" => 201,
                "message" => "tujuan keuangan created successfully.",
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

    public function add_goals_tujuan_keuangan(Request $request, $id){
          $input = $request->all();
        
        try{
            $validator = Validator::make($input, [
                'nama_goals_tujuan_keuangan' => 'required',
                'nominal' => 'required', 
            ]);

            if($validator->fails()){
                return response()->json([
                    "status" => 400,
                    "errors" => $validator->errors(),
                    "data" => null
                ]);          
            }

            $tujuankeuanganvalidation = TujuanKeuangan::where('id',$id)->where('is_delete',0)
                                                       ->where('user_id',Auth::id())
                                                       ->where('simpanan_id','!=',null)
                                                       ->where('is_delete','=',0)
                                                       ->first();
            
            if($tujuankeuanganvalidation != null){
                $goalstujuankeuangan = new GoalsTujuanKeuangan;
                $goalstujuankeuangan->user_id = Auth::id();
                $goalstujuankeuangan->tujuan_keuangan_id = $id;
                $goalstujuankeuangan->nama_goals_tujuan_keuangan = $input['nama_goals_tujuan_keuangan'];
                $goalstujuankeuangan->nominal = $input['nominal'];
                $goalstujuankeuangan->is_delete = 0;
                $goalstujuankeuangan->save();

                $tujuankeuangan = TujuanKeuangan::where('id',$id)->where('is_delete',0)
                                                       ->where('user_id',Auth::id())
                                                       ->where('simpanan_id','!=',null)
                                                       ->where('is_delete','=',0)
                                                       ->firstOrFail();

                $tujuankeuangan->nominal_goals = $tujuankeuangan->nominal_goals + $input['nominal'];
                $tujuankeuangan->save();

                $simpanan = Simpanan::where('user_id',Auth::id())->where('is_delete','=',0)->where('id','=',$tujuankeuanganvalidation->simpanan_id)->firstOrFail();
                $simpanan->jumlah_simpanan = $simpanan->jumlah_simpanan + $input['nominal'];
                $simpaanan->save();
            } else {
                
                 $tujuankeuanganvalidationhutang  = TujuanKeuangan::where('id',$id)->where('is_delete',0)
                                                            ->where('user_id',Auth::id())
                                                            ->where('hutang_id','!=',null)
                                                            ->where('is_delete','=',0)
                                                            ->first();
                if($tujuankeuanganvalidationhutang != null){
                    $goalstujuankeuangan = new GoalsTujuanKeuangan;
                    $goalstujuankeuangan->user_id = Auth::id();
                    $goalstujuankeuangan->tujuan_keuangan_id = $id;
                    $goalstujuankeuangan->nama_goals_tujuan_keuangan = $input['nama_goals_tujuan_keuangan'];
                    $goalstujuankeuangan->currency_id = $input['currency_id'];
                    $goalstujuankeuangan->is_delete = 0;
                    $goalstujuankeuangan->save();

                    $tujuankeuangan = TujuanKeuangan::where('id',$id)->where('is_delete',0)
                                                        ->where('user_id',Auth::id())
                                                        ->where('simpanan_id','!=',null)
                                                        ->where('is_delete','=',0)
                                                        ->firstOrFail();

                    $tujuankeuangan->nominal_goals = $tujuankeuangan->nominal_goals + $input['nominal'];
                    $tujuankeuangan->save();
                    

                    $hutang = Hutang::where('id',$request->get('id'))->where('is_delete',0)
                                                       ->where('user_id',Auth::id())
                                                       ->where('is_delete','=',0)
                                                       ->firstOrFail();
                }else{
                    $goalstujuankeuangan = new GoalsTujuanKeuangan;
                    $goalstujuankeuangan->user_id = Auth::id();
                    $goalstujuankeuangan->tujuan_keuangan_id = $id;
                    $goalstujuankeuangan->nama_goals_tujuan_keuangan = $input['nama_goals_tujuan_keuangan'];
                    $goalstujuankeuangan->currency_id = $input['currency_id'];
                    $goalstujuankeuangan->is_delete = 0;
                    $goalstujuankeuangan->save();

                    $tujuankeuangan = TujuanKeuangan::where('id',$id)->where('is_delete',0)
                                                        ->where('user_id',Auth::id())
                                                        ->where('simpanan_id','!=',null)
                                                        ->where('is_delete','=',0)
                                                        ->firstOrFail();

                    $tujuankeuangan->nominal_goals = $tujuankeuangan->nominal_goals + $input['nominal'];
                    $tujuankeuangan->save();
                }
            }
            
            return response()->json([
                "status" => 201,
                "message" => "goals tujuan keuangan created successfully.",
                "data" => $pemas
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
                $tujuankeuangan = TujuanKeuangan::where('id',$id)->where('user_id',Auth::id())->where('is_delete','=',0)->first();
                if($tujuankeuangan != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "Tujuan Keuangan Ditampilkan",
                        "data" => $tujuankeuangan
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "Tujuan keuangan Tidak ada",
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
                        'nama_tujuan_keuangan' => 'required',
                        'status_fleksibel' => 'required',
                        'nominal' => 'required',
                        'kategori_tujuan_keuangan_id' => 'required',
                        'currency_id' => 'required',
                        'tanggal' => 'required',
                        'status_tujuan_keuangan' => 'required', 
                        'hutang_id' => 'nullable', 
                        'simpanan_id' => 'nullable', 
                    ]);
                    if($validator->fails()){
                        $tujuankeuangan = TujuanKeuangan::where('id',$id)->where('user_id', Auth::id())->where('is_delete', 0)->where('status_tujuan_keuangan',0);
                        $tujuankeuangan->user_id = Auth::id();
                        $tujuankeuangan->nama_tujuan_keuangan = $input['nama_tujuan_keuangan'];
                        $tujuankeuangan->status_fleksibel = $input['status_fleksibel'];
                        $tujuankeuangan->currency_id = $input['currency_id'];
                        $tujuankeuangan->kategori_tujuan_keuangan = $input['kategori_tujuan_keuangan'];
                        $tujuankeuangan->tanggal = $input['tanggal'];

                        if($request->get('hutang_id') != null){
                            $tujuankeuangan->hutang_id = $request->get('hutang_id');
                        }
                        else if($request->get('simpanan_id') != null){
                            $tujuankeuangan->simpanan_id = $request->get('simpanan_id');
                        }

                        $tujuankeuangan->is_delete = 0;
                        $tujuankeuangan->save();

                        return response()->json([
                            "status" => 201,
                            "message" => "Tujuan Keuangan created successfully.",
                            "data" => $tujuankeuangan
                        ]);
                    }
                    $tujuankeuangan = TujuanKeuangan::where('id',$id)->where('user_id', Auth::id())->where('is_delete', 0)->where('status_tujuan_keuangan',0);
                        $tujuankeuangan->user_id = Auth::id();
                        $tujuankeuangan->nama_tujuan_keuangan = $input['nama_tujuan_keuangan'];
                        $tujuankeuangan->status_fleksibel = $input['status_fleksibel'];
                        $tujuankeuangan->currency_id = $input['currency_id'];
                        $tujuankeuangan->kategori_tujuan_keuangan = $input['kategori_tujuan_keuangan'];
                        $tujuankeuangan->tanggal = $input['tanggal'];
                        
                        if($request->get('hutang_id') != null){
                            $tujuankeuangan->hutang_id = $request->get('hutang_id');
                        }
                        
                        else if($request->get('simpanan_id') != null){
                            $tujuankeuangan->simpanan_id = $request->get('simpanan_id');
                        }
                        
                        $tujuankeuangan->is_delete = 0;
                        $tujuankeuangan->save();
                    
                    return response()->json([
                        "status" => 201,
                        "message" => "Tujuan Keuangan created successfully.",
                        "data" => $tujuankeuangan
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

    public function destroy($id){
          
        try{

            $goals = GoalsTujuanKeuangan::where('tujuan_keuangan_id',$id)->where('user_id',Auth::id())->where('is_delete','0')->get();
            
            if($goals != null){
                    $tujuankeuangan = TujuanKeuangan::where('id',$id)->where('user_id',Auth::id())->where('is_delete','=',0)->firstOrFail();
                    $tujuankeuangan->is_delete = 1;
                    $tujuankeuangan->deleted_at = Carbon::now();
                    $tujuankeuangan->save();

                    foreach($goals as $siuuu){
                        $siuuu->is_delete = 1;
                        $siuuu->deleted_at = Carbon::now();
                        $siuuu->save();
                    }

                    return response()->json([
                                    "status" => 201,
                                    "message" => 'delete tujuan keuangan succesfully',
                                    "data" => $tujuankeuangan
                    ]);
            }else{
                $tujuankeuangan = TujuanKeuangan::where('id',$id)->where('user_id',Auth::id())->where('is_delete','=',0)->firstOrFail();
                    $tujuankeuangan->is_delete = 1;
                    $tujuankeuangan->deleted_at = Carbon::now();
                    $tujuankeuangan->save();

                    return response()->json([
                                    "status" => 201,
                                    "message" => 'delete tujuan keuangan succesfully',
                                    "data" => $tujuankeuangan
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

    public function destroy_goals_tujuan_keuangan($id){
        try{
        $goals = GoalsTujuanKeuangan::where('tujuan_keuangan_id',$id)->where('user_id',Auth::id())->where('is_delete','0')->firstOrFail();
        $goals->is_delete = 1;
        $goals->deleted_at = Carbon::now();
        $goals->save();

        $tujuankeuangan = TujuanKeuangan::where('id',$id)->where('user_id',Auth::id())->where('is_delete','=',0)->firstOrFail();
        $tujuankeuangan->nominal_goals = $tujuankeuangan->nominal_goals - $goals->nominal;
        $tujuankeuangan->save();
        
    } catch(\Exception $e){
        return response()->json([
                        "status" => 401,
                        "message" => 'Error'.$e->getMessage(),
                        "data" => null
                    ]);   
    }
}
}
