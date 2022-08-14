<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriPemasukan;
use App\Models\Pemasukan;
use App\Models\CurrencyData;
use App\Models\Settings;
use AmrShawky\LaravelCurrency\Facade\Currency;
use Carbon\Carbon;
use Auth;
use Validator;
use DB;

class PemasukanController extends Controller
{

    public function index(Request $request){
        try{
            $term = $request->get('search');
            $id = $request->get('id');
            $settings = Settings::where('user_id',Auth::id())->first();
            $calculatepemasukan = Pemasukan::Where(
                                    [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                        ['currency_id', '=', $settings->currency_id]
                                    ])->sum('jumlah_pemasukan');
            $calculatepemasukanhariini = Pemasukan::Where(
                                    [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                        ['currency_id', '=', $settings->currency_id],
                                    ])->whereDay('created_at',date('d'))->sum('jumlah_pemasukan');
            
            if($id != null) {
                  $pemasukan = DB::table('pemasukan')->join('kategori_pemasukan','kategori_pemasukan.id','=','pemasukan.kategori_pemasukan_id')
                        ->select('pemasukan.id','pemasukan.nama_pemasukan as nama','kategori_pemasukan.nama_pemasukan as kategori','pemasukan.jumlah_pemasukan','pemasukan.tanggal_pemasukan','pemasukan.keterangan')
                        ->where('pemasukan.user_id',Auth::id())->where(function ($query) use ($term) {
                                $query->where('pemasukan.nama_pemasukan', "like", "%" . $term . "%");
                                $query->orWhere('kategori_pemasukan.nama_pemasukan', "like", "%" . $term . "%");
                                $query->orWhere('pemasukan.jumlah_pemasukan', "=", $term);
                        })
                        ->where('pemasukan.id','=', $id)
                        ->where('pemasukan.is_delete','=',0)
                        ->orderBy('pemasukan.id','DESC')
                        ->first();
            }else{
            $pemasukan = DB::table('pemasukan')->join('kategori_pemasukan','kategori_pemasukan.id','=','pemasukan.kategori_pemasukan_id')
                        ->select('pemasukan.id','pemasukan.nama_pemasukan as nama','kategori_pemasukan.nama_pemasukan as kategori','pemasukan.jumlah_pemasukan','pemasukan.tanggal_pemasukan','pemasukan.keterangan')
                        ->where('pemasukan.user_id',Auth::id())->where(function ($query) use ($term) {
                                $query->where('pemasukan.nama_pemasukan', "like", "%" . $term . "%");
                                $query->orWhere('kategori_pemasukan.nama_pemasukan', "like", "%" . $term . "%");
                                $query->orWhere('pemasukan.jumlah_pemasukan', "=", $term);
                        })
                        ->where('pemasukan.is_delete','=',0)
                        ->orderBy('pemasukan.id','DESC')
                        ->get();
            }
            return response()->json([
                "status" => 201,
                "message" => "Pemasukan Berhasil Ditampilkan",
                "data" => [
                    "pemasukan" => $calculatepemasukan,
                    "pemasukan_hari_ini" => $calculatepemasukanhariini,
                    "data_pemasukan" => $pemasukan
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
                'nama_pemasukan' => 'required',
                'kategori_pemasukan_id' => 'required',
                'currency_id' => 'required',
                'jumlah_pemasukan' => 'required', 
                'tanggal_pemasukan' => 'required', 
                'keterangan' => "nullable",
            ]);

            if($validator->fails()){
                return response()->json([
                    "status" => 400,
                    "errors" => $validator->errors(),
                    "data" => null
                ]);          
            }
            $pemasukan = new Pemasukan;
            $pemasukan->user_id = Auth::id();
            $pemasukan->kategori_pemasukan_id = $input['kategori_pemasukan_id'];
            $pemasukan->nama_pemasukan = $input['nama_pemasukan'];
            $pemasukan->currency_id = $input['currency_id'];
            $pemasukan->jumlah_pemasukan = $input['jumlah_pemasukan'];
            $pemasukan->tanggal_pemasukan = $input['tanggal_pemasukan'];
            $pemasukan->keterangan = $input['keterangan'];
            $pemasukan->status_transaksi_berulang = 0;
            $pemasukan->is_delete = 0;
            $pemasukan->save();
            
            return response()->json([
                "status" => 201,
                "message" => "Pemasukan created successfully.",
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


    public function update(Request $request, $id){
        if ($request->isMethod('get')){
            try{
                $pemasukan = Pemasukan::where('id',$id)->where('user_id',Auth::id())->where('is_delete','=',0)->first();
                if($pemasukan != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "Pemasukan Berhasil Ditampilkan",
                        "data" => $pemasukan
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "Pemasukan Tidak ada",
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
                        'nama_pemasukan' => 'required',
                        'kategori_pemasukan_id' => 'required',
                        'nama_pemasukan' => 'required',
                        'currency_id' => 'required',
                        'jumlah_pemasukan' => 'required', 
                        'tanggal_pemasukan' => 'required', 
                        'keterangan' => "nullable",
                    ]);

                    if($validator->fails()){
                        $pemasukan = Pemasukan::where('id', $id)->where('user_id',Auth::id())->where('is_delete',0)->first();
                        $pemasukan->user_id = Auth::id();
                        $pemasukan->kategori_pemasukan_id = $input['kategori_pemasukan_id'];
                        $pemasukan->nama_pemasukan = $input['nama_pemasukan'];
                        $pemasukan->currency_id = $input['currency_id'];
                        $pemasukan->jumlah_pemasukan = $input['jumlah_pemasukan'];
                        $pemasukan->tanggal_pemasukan = $input['tanggal_pemasukan'];
                        $pemasukan->keterangan = $input['keterangan'];
                        $pemasukan->status_transaksi_berulang = 0;
                        $pemasukan->is_delete = 0;
                        $pemasukan->save();

                        return response()->json([
                            "status" => 201,
                            "message" => "Pemasukan created successfully.",
                            "data" => $pemasukan
                        ]);
                    }
                    $pemasukan = Pemasukan::where('id', $id)->where('user_id',Auth::id())->where('is_delete',0)->first();
                    $pemasukan->user_id = Auth::id();
                    $pemasukan->kategori_pemasukan_id = $input['kategori_pemasukan_id'];
                    $pemasukan->nama_pemasukan = $input['nama_pemasukan'];
                    $pemasukan->currency_id = $input['currency_id'];
                    $pemasukan->jumlah_pemasukan = $input['jumlah_pemasukan'];
                    $pemasukan->tanggal_pemasukan = $input['tanggal_pemasukan'];
                    $pemasukan->keterangan = $input['keterangan'];
                    $pemasukan->status_transaksi_berulang = 0;
                    $pemasukan->is_delete = 0;
                    $pemasukan->save();
                    
                    return response()->json([
                        "status" => 201,
                        "message" => "Pemasukan Updated successfully.",
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

    public function destroy_pemasukan($id){
         $pemasukan = Pemasukan::where('id',$id)->where('user_id',Auth::id())->where('is_delete','=',0)->firstOrFail();
         $pemasukan->is_delete = 1;
         $pemasukan->deleted_at = Carbon::now();
         $pemasukan->save();
          return response()->json([
                        "status" => 201,
                        "message" => 'delete pemasukan succesfully',
                        "data" => $pemasukan
          ]);
    }

}
