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

use Carbon\Carbon;
use Auth;
use Validator;
use DB;

class HutangController extends Controller
{
     public function index(Request $request){
        try{
            $term = $request->get('search');
            $datedata = $request->get('date');
            $settings = Settings::where('user_id',Auth::id())->first();

            $calculatehutangbelumdibayar = Hutang::Where(
                                            [
                                                ['is_delete', '=', 0],
                                                ['user_id', '=', Auth::id()],
                                                ['currency_id', '=', $settings->currency_id],
                                                ['status_hutang','=','0']
                                            ])->sum('jumlah_hutang');

            $calculatehutangsudahdibayar = Hutang::Where(
                                            [
                                                ['is_delete', '=', 0],
                                                ['user_id', '=', Auth::id()],
                                                ['currency_id', '=', $settings->currency_id],
                                                ['status_hutang','=','1']
                                            ])->sum('jumlah_hutang');
                    
            $hutang = Hutang::where([
                [function ($query) use ($request){
                    if (($term = $request->term)){
                        $query->orWhere('nama_hutang', 'LIKE', '%' . $term .'%')
                        ->orWhere('deskripsi', 'LIKE', '%' . $term .'%')
                        ->orWhere('tanggal_hutang','=',$datedata)
                        ->orWhere('tanggal_hutang_dibayar','=',$datedata)
                        ->get();
                    }
                }]
            ])    
            ->where('user_id',Auth::id())   
            ->where('is_delete','=',0)
            ->orderBy('id','DESC')
            ->paginate(10);

            return response()->json([
                "status" => 201,
                "message" => "Hutang Berhasil Ditampilkan",
                "data" => [
                    "total_hutang_sudah_dibayar" => $calculatehutangsudahdibayar,
                    "total_hutang_belum_dibayar" => $calculatehutangbelumdibayar,
                    "data_hutang" => $hutang
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
                'nama_hutang' => 'required',
                'no_telepon' => 'required',
                'jumlah_hutang' => 'required',
                'deksripsi' => 'nullable',
                'currency_id' => 'required', 
            ]);

            if($validator->fails()){
                return response()->json([
                    "status" => 400,
                    "errors" => $validator->errors(),
                    "data" => null
                ]);          
            }


            $hutang = new Hutang;
            $hutang->user_id = Auth::id();
            $hutang->nama_hutang = $input['nama_hutang'];
            $hutang->no_telepon = $input['no_telepon'];
            $hutang->jumlah_hutang = $input['jumlah_hutang'];
            $hutang->currency_id = $input['currency_id'];
            $hutang->deksripsi = $input['deksripsi'];
            $hutang->status_hutang = 0;
            $hutang->is_delete = 0;
            $hutang->save();
            
            return response()->json([
                "status" => 201,
                "message" => "hutang created successfully.",
                "data" => $hutang
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
                $hutang = hutang::where('id',$id)->where('user_id',Auth::id())->where('is_delete','=',0)->first();
                if($hutang != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "hutang Berhasil Ditampilkan",
                        "data" => $hutang
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "hutang Tidak ada",
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
                    'nama_hutang' => 'required',
                    'no_telepon' => 'required',
                    'deksripsi' => 'nullable',
                    'jumlah_hutang' => 'required',
                    'currency_id' => 'required', 
                ]);

                    if($validator->fails()){
                    $hutang = Hutang::where('id',$id)->where('user_id',Auth::id())->where('is_delete','=',0)->firstOrFail();
                    $hutang->user_id = Auth::id();
                    $hutang->nama_hutang = $input['nama_hutang'];
                    $hutang->no_telepon = $input['no_telepon'];
                    $hutang->deksripsi = $input['deksripsi'];
                    $hutang->jumlah_hutang = $input['jumlah_hutang'];
                    $hutang->status_hutang = 0;
                    $hutang->is_delete = 0;
                    $hutang->save();
                        return response()->json([
                            "status" => 201,
                            "message" => "Piutang created successfully.",
                            "data" => $pengeluaran
                        ]);
                    }
                    
                    $hutang = Hutang::where('id',$id)->where('user_id',Auth::id())->where('is_delete','=',0)->firstOrFail();
                    $hutang->user_id = Auth::id();
                    $hutang->nama_hutang = $input['nama_hutang'];
                    $hutang->no_telepon = $input['no_telepon'];
                    $hutang->deksripsi = $input['deksripsi'];
                    $hutang->jumlah_hutang = $input['jumlah_hutang'];
                    $hutang->status_hutang = 0;
                    $hutang->is_delete = 0;
                    $hutang->save();
                    
                    return response()->json([
                        "status" => 201,
                        "message" => "hutang created successfully.",
                        "data" => $hutang
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

    
    public function destroy_hutang($id){
         $hutang = Hutang::where('id',$id)->where('user_id',Auth::id())->where('is_delete','=',0)->firstOrFail();
         $hutang->is_delete = 1;
         $hutang->deleted_at = Carbon::now();
         $hutang->save();

          return response()->json([
                        "status" => 201,
                        "message" => 'delete hutang succesfully',
                        "data" => $hutang
          ]);
    }
}
