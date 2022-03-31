<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
