<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriPengeluaran;
use App\Models\Pengeluaran;
use App\Models\CurrencyData;
use App\Models\Settings;
use AmrShawky\LaravelCurrency\Facade\Currency;
use Auth;
use DB;
use Carbon\Carbon;
use Validator;
use App\Models\Hutang;

class PengeluaranController extends Controller
{
    public function index(Request $request){
        try{
            $term = $request->get('search');
            $id = $request->get('id');
            $settings = Settings::where('user_id',Auth::id())->first();

            $calculatepengeluaran = Pengeluaran::Where(
                                    [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                        ['currency_id', '=', $settings->currency_id]
                                    ])->sum('jumlah_pengeluaran');

             $calculatepengeluaranhariini = Pengeluaran::Where(
                                    [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                        ['currency_id', '=', $settings->currency_id],
                                    ])->whereDay('created_at',date('d'))->sum('jumlah_pengeluaran');
            
            if($id != null) {
                  $pengeluaran = DB::table('pengeluaran')->join('kategori_pengeluaran','kategori_pengeluaran.id','=','pengeluaran.kategori_pengeluaran_id')
                        ->select('pengeluaran.id','pengeluaran.nama_pengeluaran as nama','kategori_pengeluaran.nama_pengeluaran as kategori','pengeluaran.jumlah_pengeluaran','pengeluaran.tanggal_pengeluaran','pengeluaran.keterangan')
                        ->where('pengeluaran.user_id',Auth::id())->where(function ($query) use ($term) {
                                $query->where('pengeluaran.nama_pengeluaran', "like", "%" . $term . "%");
                                $query->orWhere('kategori_pengeluaran.nama_pengeluaran', "like", "%" . $term . "%");
                                $query->orWhere('pengeluaran.jumlah_pengeluaran', "=", $term);
                        })
                        ->where('pengeluaran.id','=', $id)
                        ->where('pengeluaran.is_delete','=',0)
                        ->orderBy('pengeluaran.id','DESC')
                        ->first();
            }else{
            $pengeluaran = DB::table('pengeluaran')->join('kategori_pengeluaran','kategori_pengeluaran.id','=','pengeluaran.kategori_pengeluaran_id')
                        ->select('pengeluaran.id','pengeluaran.nama_pengeluaran as nama','kategori_pengeluaran.nama_pengeluaran as kategori','pengeluaran.jumlah_pengeluaran','pengeluaran.tanggal_pengeluaran','pengeluaran.keterangan')
                        ->where('pengeluaran.user_id',Auth::id())->where(function ($query) use ($term) {
                                $query->where('pengeluaran.nama_pengeluaran', "like", "%" . $term . "%");
                                $query->orWhere('kategori_pengeluaran.nama_pengeluaran', "like", "%" . $term . "%");
                                $query->orWhere('pengeluaran.jumlah_pengeluaran', "=", $term);
                        })
                        ->where('pengeluaran.is_delete','=',0)
                        ->orderBy('pengeluaran.id','DESC')
                        ->get();
            }
            return response()->json([
                "status" => 201,
                "message" => "Pengeluaran Berhasil Ditampilkan",
                "data" => [
                    "total_pengeluaran" => $calculatepengeluaran,
                    "total_pengeluaran_hari_ini" => $calculatepengeluaranhariini,
                    "data_pengeluaran" => $pengeluaran
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
                'nama_pengeluaran' => 'required',
                'kategori_pengeluaran_id' => 'required',
                'currency_id' => 'required',
                'jumlah_pengeluaran' => 'required', 
                'tanggal_pengeluaran' => 'required', 
                'keterangan' => "nullable",
            ]);

            if($validator->fails()){
                return response()->json([
                    "status" => 400,
                    "errors" => $validator->errors(),
                    "data" => null
                ]);          
            }
            $pengeluaran = new Pengeluaran;
            $pengeluaran->user_id = Auth::id();
            $pengeluaran->kategori_pengeluaran_id = $input['kategori_pengeluaran_id'];
            $pengeluaran->nama_pengeluaran = $input['nama_pengeluaran'];
            $pengeluaran->currency_id = $input['currency_id'];
            $pengeluaran->jumlah_pengeluaran = $input['jumlah_pengeluaran'];
            $pengeluaran->tanggal_pengeluaran = $input['tanggal_pengeluaran'];
            $pengeluaran->keterangan = $input['keterangan'];
            $pengeluaran->is_delete = 0;
            $pengeluaran->save();
            
            return response()->json([
                "status" => 201,
                "message" => "Pengeluaran created successfully.",
                "data" => $pengeluaran
            ]);
        }catch(\Exception $e){
            return response()->json([
                "status" => 401,
                "message" => 'Error'.$e->getMessage(),
                "data" => null
            ]);
        } 
    }

    public function create_bayar_hutang(Request $request, $id){
        $input = $request->all();
        
        try{
            $validator = Validator::make($input, [
                'nama_pengeluaran' => 'required',
                'kategori_pengeluaran_id' => 'required',
                'nama_pengeluaran' => 'required',
                'currency_id' => 'required',
                'jumlah_pengeluaran' => 'required', 
                'tanggal_pengeluaran' => 'required', 
                'keterangan' => "nullable",
            ]);

            if($validator->fails()){
                return response()->json([
                    "status" => 400,
                    "errors" => $validator->errors(),
                    "data" => null
                ]);          
            }
            $pengeluaran = new Pengeluaran;
            $pengeluaran->user_id = Auth::id();
            $pengeluaran->kategori_pengeluaran_id = $input['kategori_pengeluaran_id'];
            $pengeluaran->nama_pengeluaran = $input['nama_pengeluaran'];
            $pengeluaran->currency_id = $input['currency_id'];
            $pengeluaran->hutang_id = $id;
            $pengeluaran->jumlah_pengeluaran = $input['jumlah_pengeluaran'];
            $pengeluaran->tanggal_pengeluaran = $input['tanggal_pengeluaran'];
            $pengeluaran->keterangan = $input['keterangan'];
            $pengeluaran->is_delete = 0;
           

            $hutang = Hutang::where('id',$id)->where('user_id',Auth::id())->where('is_delete','=',0)->first();
            $hutang->jumlah_hutang_dibayar = $hutang->jumlah_hutang_dibayar + $input['jumlah_pengeluaran'];
            
            if($hutang->jumlah_hutang == $input['jumlah_pengeluaran']){

                    if($hutang->jumlah_hutang_dibayar == $hutang->jumlah_hutang){
                        $pengeluaran->save();
                        $hutang->tanggal_hutang_dibayar = Carbon::now();
                        $hutang->status_hutang = 1;
                        $hutang->save();
                    } 
                    else{
                        $pengeluaran->save();
                        $hutang->save();
                    }
                    
                    return response()->json([
                        "status" => 201,
                        "message" => "Pengeluaran hutang created successfully.",
                        "data" => $pengeluaran
                    ]);
            } else {
                 return response()->json([
                        "status" => 402,
                        "message" => 'Jumlah uang anda kurang atau kelebihan mohon membayar sebesar '.$hutang->jumlah_hutang,
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
                $pengeluaran = Pengeluaran::where('id',$id)->where('user_id',Auth::id())->where('is_delete','=',0)->first();
                if($pengeluaran != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "Pengeluaran Berhasil Ditampilkan",
                        "data" => $pengeluaran
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "Pengeluaran Tidak ada",
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
                    'nama_pengeluaran' => 'required',
                    'kategori_pengeluaran_id' => 'required',
                    'nama_pengeluaran' => 'required',
                    'currency_id' => 'required',
                    'jumlah_pengeluaran' => 'required', 
                    'tanggal_pengeluaran' => 'required', 
                    'keterangan' => "nullable",
                ]);

                    if($validator->fails()){
                        $pengeluaran = Pengeluaran::where('id', $id)->where('user_id',Auth::id())->where('is_delete',0)->first();
                        $pengeluaran->user_id = Auth::id();
                        $pengeluaran->kategori_pengeluaran_id = $input['kategori_pengeluaran_id'];
                        $pengeluaran->nama_pengeluaran = $input['nama_pengeluaran'];
                        $pengeluaran->currency_id = $input['currency_id'];
                        $pengeluaran->jumlah_pengeluaran = $input['jumlah_pengeluaran'];
                        $pengeluaran->tanggal_pengeluaran = $input['tanggal_pengeluaran'];
                        $pengeluaran->keterangan = $input['keterangan'];
                        $pengeluaran->is_delete = 0;
                        $pengeluaran->save();

                        return response()->json([
                            "status" => 201,
                            "message" => "Pengeluaran created successfully.",
                            "data" => $pengeluaran
                        ]);
                    }
                    $pengeluaran = Pengeluaran::where('id', $id)->where('user_id',Auth::id())->where('is_delete',0)->first();
                    $pengeluaran->user_id = Auth::id();
                    $pengeluaran->kategori_pengeluaran_id = $input['kategori_pengeluaran_id'];
                    $pengeluaran->nama_pengeluaran = $input['nama_pengeluaran'];
                    $pengeluaran->currency_id = $input['currency_id'];
                    $pengeluaran->jumlah_pengeluaran = $input['jumlah_pengeluaran'];
                    $pengeluaran->tanggal_pengeluaran = $input['tanggal_pengeluaran'];
                    $pengeluaran->keterangan = $input['keterangan'];
                    $pengeluaran->is_delete = 0;
                    $pengeluaran->save();

                    return response()->json([
                        "status" => 201,
                        "message" => "Pengeluarancreated successfully.",
                        "data" => $pengeluaran
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

    public function destroy_pengeluaran($id){
         $pengeluaran = Pengeluaran::where('id',$id)->where('user_id',Auth::id())->where('is_delete','=',0)->first();
         if($pengeluaran->hutang_id == null){
            $pengeluaran->is_delete = 1;
            $pengeluaran->deleted_at = Carbon::now();
            $pengeluaran->save();
         } else {
            $pengeluaran->is_delete = 1;
            $pengeluaran->deleted_at = Carbon::now();
            $pengeluaran->save();

            $hutang = Hutang::where('id',$pengeluaran->hutang_id)->where('is_delete','=',0)->where('status_hutang','=','0')->firstOrFail();
            $hutang->status_hutang = 0;
            $hutang->save();
        }

          return response()->json([
                        "status" => 201,
                        "message" => 'delete pengeluaran succesfully',
                        "data" => $pengeluaran
          ]);
    }

}
