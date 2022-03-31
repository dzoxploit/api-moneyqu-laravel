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

class PengeluaranController extends Controller
{
    public function index(Request $request){
        try{
            $term = $request->get('search');
            $settings = Settings::where('user_id',Auth::id())->first();

            $calculatepengeluaran = Pengeluaran::Where(
                                    [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                        ['currency_id', '=', $settings->currency_id]
                                    ])-sum('jumlah_pengeluaran');
            
            $pengeluaran = Pengeluaran::where([
                ['nama_pengeluaran', '!=', NULL],
                [function ($query) use ($request){
                    if (($term = $request->term)){
                        $query->orWhere('nama_pengeluaran', 'LIKE', '%' . $term .'%')->get();
                    }
                }]
            ])    
            ->orderBy('id','DESC')
            ->paginate(10);

            return response()->json([
                "status" => 201,
                "message" => "Pengeluaran Berhasil Ditampilkan",
                "data" => [
                    "total_pengeluaran" => $calculatepengeluaran,
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
                'nama_penegluaran' => 'required',
                'kategori_pengeluaran_id' => 'required',
                'nama_pengeluaran' => 'required',
                'currency_id' => 'required',
                'jumlah_pengeluaran' => 'required', 
                'tanggal_pengeluaran' => 'required', 
                'keterangan' => "text",
                'status_transaksi_berulang' => 'required',
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
            $pengeluaran->status_transaksi_berulang = $input['status_transaksi_berulang'];
            $pengeluaran->is_delete = 0;
            $pengeluaran->save();
            
            return response()->json([
                "status" => 201,
                "message" => "Pengeluaran created successfully.",
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

    public function create_bayar_hutang(Request $request, $hutang_id){
        $input = $request->all();
        
        try{
            $validator = Validator::make($input, [
                'nama_pengeluaran' => 'required',
                'kategori_pengeluaran_id' => 'required',
                'nama_pengeluaran' => 'required',
                'currency_id' => 'required',
                'jumlah_pengeluaran' => 'required', 
                'tanggal_pengeluaran' => 'required', 
                'keterangan' => "text",
                'status_transaksi_berulang' => 'required',
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
            $pengeluaran->hutang_id = $hutang_id;
            $pengeluaran->jumlah_pengeluaran = $input['jumlah_pengeluaran'];
            $pengeluaran->tanggal_pengeluaran = $input['tanggal_pengeluaran'];
            $pengeluaran->keterangan = $input['keterangan'];
            $pengeluaran->status_transaksi_berulang = $input['status_transaksi_berulang'];
            $pengeluaran->is_delete = 0;
            $pengeluaran->save();
            
            return response()->json([
                "status" => 201,
                "message" => "Pengeluaran created successfully.",
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
                $pengeluaran = Pengeluaran::where('id',$id)->where('is_delete','=',0)->first();
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
                    'keterangan' => "text",
                    'status_transaksi_berulang' => 'required',
                ]);

                    if($validator->fails()){
                        $pengeluaran = Pengeluaran::where('id', $id)->where('is_delete',0)->first();
                        $pengeluaran->user_id = Auth::id();
                        $pengeluaran->kategori_pengeluaran_id = $input['kategori_pengeluaran_id'];
                        $pengeluaran->nama_pengeluaran = $input['nama_pengeluaran'];
                        $pengeluaran->currency_id = $input['currency_id'];
                        $pengeluaran->jumlah_pengeluaran = $input['jumlah_pengeluaran'];
                        $pengeluaran->tanggal_pengeluaran = $input['tanggal_pengeluaran'];
                        $pengeluaran->keterangan = $input['keterangan'];
                        $pengeluaran->status_transaksi_berulang = $input['status_transaksi_berulang'];
                        $pengeluaran->is_delete = 0;
                        $pengeluaran->save();

                        return response()->json([
                            "status" => 201,
                            "message" => "Pemasukan created successfully.",
                            "data" => $pengeluaran
                        ]);
                    }
                    $pengeluaran = Pengeluaran::where('id', $id)->where('is_delete',0)->first();
                    $pengeluaran->user_id = Auth::id();
                    $pengeluaran->kategori_pengeluaran_id = $input['kategori_pengeluaran_id'];
                    $pengeluaran->nama_pengeluaran = $input['nama_pengeluaran'];
                    $pengeluaran->currency_id = $input['currency_id'];
                    $pengeluaran->jumlah_pengeluaran = $input['jumlah_pengeluaran'];
                    $pengeluaran->tanggal_pengeluaran = $input['tanggal_pengeluaran'];
                    $pengeluaran->keterangan = $input['keterangan'];
                    $pengeluaran->status_transaksi_berulang = $input['status_transaksi_berulang'];
                    $pengeluaran->is_delete = 0;
                    $pengeluaran->save();

                    return response()->json([
                        "status" => 201,
                        "message" => "Pemasukan created successfully.",
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
         $pengeluaran = Pengeluaran::where('id',$id)->where('is_delete','=',0)->firstOrFail();
         $pengeluaran->is_delete = 1;
         $pengeluaran->save();

          return response()->json([
                        "status" => 201,
                        "message" => 'delete pengeluaran succesfully',
                        "data" => $pengeluaran
          ]);
    }

}
