<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Piutang;
use App\Models\CurrencyData;
use App\Models\Settings;
use AmrShawky\LaravelCurrency\Facade\Currency;
use Auth;
use DB;
use Carbon\Carbon;


class PiutangController extends Controller
{
    public function index(Request $request){
        try{
            $term = $request->get('search');
            $settings = Piutang::where('user_id',Auth::id())->first();

            $calculatepiutangbelumdibayar = Piutang::Where(
                                            [
                                                ['is_delete', '=', 0],
                                                ['user_id', '=', Auth::id()],
                                                ['currency_id', '=', $settings->currency_id],
                                                ['status_piutang','=','0']
                                            ])-sum('jumlah_hutang');

            $calculatepiutangsudahibayar = Piutang::Where(
                                            [
                                                ['is_delete', '=', 0],
                                                ['user_id', '=', Auth::id()],
                                                ['currency_id', '=', $settings->currency_id],
                                                ['status_piutang','=','0']
                                            ])-sum('jumlah_hutang');
                    
            $piutang = Piutang::where([
                ['nama_piutang', '!=', NULL],
                [function ($query) use ($request){
                    if (($term = $request->term)){
                        $query->orWhere('nama_piutang', 'LIKE', '%' . $term .'%')->get();
                    }
                }]
            ])    
            ->orderBy('id','DESC')
            ->paginate(10);

            return response()->json([
                "status" => 201,
                "message" => "Piutang Berhasil Ditampilkan",
                "data" => [
                    "total_piutang_sudah_dibayar" => $calculatepiutangsudahibayar,
                    "total_piutang_belum_dibayar" => $calculatepiutangbelumdibayar,
                    "data_piutang" => $pengeluaran
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
                'deskripsi' => 'text',
                'jumlah_hutang' => 'required',
                'currency_id' => 'required', 
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
            $piutang->deskripsi = $input['deskripsi'];
            $piutang->jumlah_hutang = $input['jumlah_hutang'];
            $piutang->status_piutang = 0;
            $piutang->is_delete = 0;
            $piutang->save();
            
            return response()->json([
                "status" => 201,
                "message" => "Piutang created successfully.",
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


    public function update(Request $request, $id){
        if ($request->isMethod('get')){
            try{
                $piutang = Piutang::where('id',$id)->where('is_delete','=',0)->first();
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
                    'deskripsi' => 'text',
                    'jumlah_hutang' => 'required',
                    'currency_id' => 'required', 
                    'status_piutang' => 'required'
                ]);

                    if($validator->fails()){
                        $piutang = Piutang::where('id',$id)->where('is_delete','=',0)->firstOrFail();
                        $piutang->user_id = Auth::id();
                        $piutang->nama_piutang = $input['nama_piutang'];
                        $piutang->no_telepon = $input['no_telepon'];
                        $piutang->deskripsi = $input['deskripsi'];
                        $piutang->jumlah_hutang = $input['jumlah_hutang'];
                        $piutang->status_piutang = $input['status_piutang'] ;
                        $piutang->is_delete = 0;
                        $piutang->save();

                        return response()->json([
                            "status" => 201,
                            "message" => "Piutang created successfully.",
                            "data" => $pengeluaran
                        ]);
                    }
                    $piutang = Piutang::where('id',$id)->where('is_delete','=',0)->firstOrFail();
                    $piutang->user_id = Auth::id();
                    $piutang->nama_piutang = $input['nama_piutang'];
                    $piutang->no_telepon = $input['no_telepon'];
                    $piutang->deskripsi = $input['deskripsi'];
                    $piutang->jumlah_hutang = $input['jumlah_hutang'];
                    $piutang->status_piutang = $input['status_piutang'] ;
                    $piutang->is_delete = 0;
                    $piutang->save();

                    return response()->json([
                        "status" => 201,
                        "message" => "Piutang created successfully.",
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
    }

    
    public function update_status_piutang($id){

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
