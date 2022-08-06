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
use Validator;
use Carbon\Carbon;

class TujuanKeuanganController extends Controller
{
    public function index(Request $request){
           try{
            $term = $request->get('search');
            $id = $request->get('id');
            $settings = Settings::where('user_id',Auth::id())->first();

             $tercapai = TujuanKeuangan::where(
                                    [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                        ['status_tujuan_keuangan','=',1]
                                    ])->count('id');
                                        
           $belumtercapai = TujuanKeuangan::where(
                                    [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                        ['status_tujuan_keuangan','=',0]
                                    ])->count('id');
            if($id != null){
            $tujuankeuangan = DB::table('tujuan_keuangan')->join('kategori_tujuan_keuangan','kategori_tujuan_keuangan.id','=','tujuan_keuangan.kategori_tujuan_keuangan_id')
                                ->leftJoin('hutang','hutang.id','=','tujuan_keuangan.hutang_id')
                                ->leftJoin('simpanan','simpanan.id','=','tujuan_keuangan.simpanan_id')
                                ->select('tujuan_keuangan.id','.kategori_tujuan_keuangan.nama_tujuan_keuangan as kategori','tujuan_keuangan.nama_tujuan_keuangan as nama','hutang.nama_hutang','simpanan.deskripsi as nama_simpanan','tujuan_keuangan.nominal','tujuan_keuangan.nominal_goals',DB::raw('(tujuan_keuangan.nominal_goals / tujuan_keuangan.nominal) * 100 AS percentage_goals'),'tujuan_keuangan.tanggal','tujuan_keuangan.status_tujuan_keuangan')
                                ->where('tujuan_keuangan.user_id',Auth::id())
                                ->where(function ($query) use ($term) {
                                        $query->where('tujuan_keuangan.nama_tujuan_keuangan', "like", "%" . $term . "%");
                                        $query->orWhere('kategori_tujuan_keuangan.nama_tujuan_keuangan', "like", "%" . $term . "%");            
                                        $query->orWhere('hutang.nama_hutang', "like", "%" . $term . "%");
                                        $query->orWhere('simpanan.deskripsi', "like", "%" . $term . "%");
                                })
                                ->where('tujuan_keuangan.is_delete','=',0)
                                ->where('tujuan_keuangan.id','=',$id)
                                ->orderBy('tujuan_keuangan.id','DESC')
                                ->first();
            }else{
                    $tujuankeuangan = DB::table('tujuan_keuangan')->join('kategori_tujuan_keuangan','kategori_tujuan_keuangan.id','=','tujuan_keuangan.kategori_tujuan_keuangan_id')
                                ->leftJoin('hutang','hutang.id','=','tujuan_keuangan.hutang_id')
                                ->leftJoin('simpanan','simpanan.id','=','tujuan_keuangan.simpanan_id')
                                ->select('tujuan_keuangan.id','.kategori_tujuan_keuangan.nama_tujuan_keuangan as kategori','tujuan_keuangan.nama_tujuan_keuangan as nama','hutang.nama_hutang','simpanan.deskripsi as nama_simpanan','tujuan_keuangan.nominal','tujuan_keuangan.nominal_goals',DB::raw('(tujuan_keuangan.nominal_goals / tujuan_keuangan.nominal) * 100 AS percentage_goals'),'tujuan_keuangan.tanggal','tujuan_keuangan.status_tujuan_keuangan')
                                ->where('tujuan_keuangan.user_id',Auth::id())
                                ->where(function ($query) use ($term) {
                                        $query->where('tujuan_keuangan.nama_tujuan_keuangan', "like", "%" . $term . "%");
                                        $query->orWhere('kategori_tujuan_keuangan.nama_tujuan_keuangan', "like", "%" . $term . "%");            
                                        $query->orWhere('hutang.nama_hutang', "like", "%" . $term . "%");
                                        $query->orWhere('simpanan.deskripsi', "like", "%" . $term . "%");
                                })
                                ->where('tujuan_keuangan.is_delete','=',0)
                                ->orderBy('tujuan_keuangan.id','DESC')
                                ->get();
            }

            return response()->json([
                "status" => 201,
                "message" => "Tujuan Keuangan Berhasil Ditampilkan",
                "tercapai" => $tercapai,
                "belum_tercapai" => $belumtercapai,
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
                $goalstujuankeuangan = GoalsTujuanKeuangan::where('tujuan_keuangan_id',$id)->where('user_id',Auth::id())->where('is_delete','=',0)->get();
                if($goalstujuankeuangan != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "Goals Tujuan Keuangan Ditampilkan",
                        "data" => $goalstujuankeuangan
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
                'nominal' => 'required',
                'kategori_tujuan_keuangan_id' => 'required',
                'currency_id' => 'required',
                'tanggal' => 'required',
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
            $tujuankeuangan->status_fleksibel = 1;
            $tujuankeuangan->currency_id = $input['currency_id'];
            $tujuankeuangan->kategori_tujuan_keuangan_id = $input['kategori_tujuan_keuangan_id'];
            $tujuankeuangan->tanggal = $input['tanggal'];
            $tujuankeuangan->nominal = $input['nominal'];
            $tujuankeuangan->nominal_goals = 0;
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

                $tujuankeuangan = TujuanKeuangan::where('id',$id)
                                                       ->where('user_id',Auth::id())
                                                       ->where('simpanan_id','!=',null)
                                                       ->where('is_delete','=',0)
                                                       ->firstOrFail();

                $tujuankeuangan->nominal_goals = $tujuankeuangan->nominal_goals + $input['nominal'];
                

                $simpanan = Simpanan::where('user_id',Auth::id())->where('is_delete','=',0)->where('id','=',$tujuankeuanganvalidation->simpanan_id)->firstOrFail();
                
                 if($tujuankeuangan->nominal == $tujuankeuangan->nominal_goals){
                        $tujuankeuangan->status_tujuan_keuangan = 1;
                        $simpanan->status_simpanan = 1;
                }
                    $validation = balancedata($goalstujuankeuangan->nominal);
            
                    if($validation == true){
                        $goalstujuankeuangan->save();
                        $tujuankeuangan->save();
                        $simpanan->save();
                    
                        return response()->json([
                            "status" => 201,
                            "message" => "Goals Tujuan Keuangan created successfully.",
                            "data" => $goalstujuankeuangan
                        ]);
                    }else{

                        return response()->json([
                            "status" => 400,
                            "errors" => "Saldo Tidak Mencukupi",
                            "data" => null
                        ]);          
                    }
            
            } else {
                
                 $tujuankeuanganvalidationhutang  = TujuanKeuangan::where('id',$id)
                                                            ->where('user_id',Auth::id())
                                                            ->where('hutang_id','!=',null)
                                                            ->where('is_delete','=',0)
                                                            ->first();
                if($tujuankeuanganvalidationhutang != null){
                    $goalstujuankeuangan = new GoalsTujuanKeuangan;
                    $goalstujuankeuangan->user_id = Auth::id();
                    $goalstujuankeuangan->tujuan_keuangan_id = $id;
                    $goalstujuankeuangan->nama_goals_tujuan_keuangan = $input['nama_goals_tujuan_keuangan'];
                    $goalstujuankeuangan->nominal = $input['nominal'];
                    $goalstujuankeuangan->is_delete = 0;

                    $tujuankeuangan = TujuanKeuangan::where('id',$id)
                                                        ->where('user_id',Auth::id())
                                                        ->where('hutang_id','!=',null)
                                                        ->where('is_delete','=',0)
                                                        ->firstOrFail();

                    $tujuankeuangan->nominal_goals = $tujuankeuangan->nominal_goals + $input['nominal'];
                    
                    

                    $hutang = Hutang::where('id',$tujuankeuangan->hutang_id)->where('is_delete',0)
                                                       ->where('user_id',Auth::id())
                                                       ->where('is_delete','=',0)
                                                       ->firstOrFail();
                    $hutang->jumlah_hutang_dibayar = $hutang->jumlah_hutang_dibayar + $input['nominal'];
                    
                    if($tujuankeuangan->nominal == $tujuankeuangan->nominal_goals){
                        $tujuankeuangan->status_tujuan_keuangan = 1;
                        $hutang->status_hutang = 1;
                    }

                     $validation = balancedata($goalstujuankeuangan->nominal);
            
                    if($validation == true){
                        $goalstujuankeuangan->save();
                        $tujuankeuangan->save();
                        $simpanan->save();
                    
                        return response()->json([
                            "status" => 201,
                            "message" => "Goals Tujuan Keuangan created successfully.",
                            "data" => $goalstujuankeuangan
                        ]);
                    }else{

                        return response()->json([
                            "status" => 400,
                            "errors" => "Saldo Tidak Mencukupi",
                            "data" => null
                        ]);          
                    }
                    
                    
                }else{
                    $goalstujuankeuangan = new GoalsTujuanKeuangan;
                    $goalstujuankeuangan->user_id = Auth::id();
                    $goalstujuankeuangan->tujuan_keuangan_id = $id;
                    $goalstujuankeuangan->nama_goals_tujuan_keuangan = $input['nama_goals_tujuan_keuangan'];
                    $goalstujuankeuangan->nominal = $input['nominal'];
                    $goalstujuankeuangan->is_delete = 0;

                    $tujuankeuangan = TujuanKeuangan::where('id',$id)
                                                        ->where('user_id',Auth::id())
                                                        ->where('is_delete','=',0)
                                                        ->firstOrFail();
            
                    $tujuankeuangan->nominal_goals = $tujuankeuangan->nominal_goals + $input['nominal'];

                    if($tujuankeuangan->nominal == $tujuankeuangan->nominal_goals){
                        $tujuankeuangan->status_tujuan_keuangan = 1;
                    }
                    
                     $validation = balancedata($goalstujuankeuangan->nominal);
            
                    if($validation == true){
                        $goalstujuankeuangan->save();
                        $tujuankeuangan->save();
                        $simpanan->save();
                    
                        return response()->json([
                            "status" => 201,
                            "message" => "Goals Tujuan Keuangan created successfully.",
                            "data" => $goalstujuankeuangan
                        ]);
                    }else{

                        return response()->json([
                            "status" => 400,
                            "errors" => "Saldo Tidak Mencukupi",
                            "data" => null
                        ]);          
                    }
                }
            }
            
            return response()->json([
                "status" => 201,
                "message" => "goals tujuan keuangan created successfully.",
                "data" => $goalstujuankeuangan
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
                        'nominal' => 'required',
                        'kategori_tujuan_keuangan_id' => 'required',
                        'currency_id' => 'required',
                        'tanggal' => 'required',
                        'hutang_id' => 'nullable', 
                        'simpanan_id' => 'nullable', 
                    ]);
                    if($validator->fails()){
                        $tujuankeuangan = TujuanKeuangan::where('id',$id)->where('user_id', Auth::id())->where('is_delete', 0)->where('status_tujuan_keuangan',0)->firstOrFail();
                        $tujuankeuangan->user_id = Auth::id();
                        $tujuankeuangan->nama_tujuan_keuangan = $input['nama_tujuan_keuangan'];
                        $tujuankeuangan->status_fleksibel = 1;
                        $tujuankeuangan->currency_id = $input['currency_id'];
                        $tujuankeuangan->kategori_tujuan_keuangan_id = $input['kategori_tujuan_keuangan_id'];
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
                            "message" => "Tujuan Keuangan updated successfully.",
                            "data" => $tujuankeuangan
                        ]);
                    }
                        $tujuankeuangan = TujuanKeuangan::where('id',$id)->where('user_id', Auth::id())->where('is_delete', 0)->where('status_tujuan_keuangan',0)->firstOrFail();
                        $tujuankeuangan->user_id = Auth::id();
                        $tujuankeuangan->nama_tujuan_keuangan = $input['nama_tujuan_keuangan'];
                        $tujuankeuangan->status_fleksibel = 1;
                        $tujuankeuangan->currency_id = $input['currency_id'];
                        $tujuankeuangan->kategori_tujuan_keuangan_id = $input['kategori_tujuan_keuangan_id'];
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
        $goals = GoalsTujuanKeuangan::where('id',$id)->where('user_id',Auth::id())->where('is_delete','0')->firstOrFail();
        $goals->is_delete = 1;
        $goals->deleted_at = Carbon::now();
        $goals->save();

        $tujuankeuangan = TujuanKeuangan::where('id',$goals->tujuan_keuangan_id)->where('user_id',Auth::id())->where('is_delete','=',0)->firstOrFail();
        $tujuankeuangan->nominal_goals = $tujuankeuangan->nominal_goals - $goals->nominal;
        $tujuankeuangan->save();
        
        return response()->json([
                                    "status" => 201,
                                    "message" => 'delete tujuan keuangan succesfully',
                                    "data" => $goals
                    ]);
        
    } catch(\Exception $e){
        return response()->json([
                        "status" => 401,
                        "message" => 'Error'.$e->getMessage(),
                        "data" => null
                    ]);   
    }
}
}
