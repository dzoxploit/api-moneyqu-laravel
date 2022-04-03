<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TabunganController extends Controller
{
    
    public function index(Request $request){
        try{
            $term = $request->get('search');
            $settings = Settings::where('user_id',Auth::id())->first();
            $calculatepemasukan = Pemasukan::Where(
                                    [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                        ['currency_id', '=', $settings->currency_id]
                                    ])-sum('jumlah_pemasukan');
            

            $pemasukan = Pemasukan::where([
                ['nama_pemasukan', '!=', NULL],
                [function ($query) use ($request){
                    if (($term = $request->term)){
                        $query->orWhere('nama_pemasukan', 'LIKE', '%' . $term .'%')->get();
                    }
                }]
            ])    
            ->orderBy('id','DESC')
            ->paginate(10);

            return response()->json([
                "status" => 201,
                "message" => "Kategori Pemasukan Berhasil Ditampilkan",
                "data" => [
                    "total_pemasukan" => $calculatepemasukan,
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
                'nama_pemasukan' => 'required',
                'currency_id' => 'required',
                'jumlah_pemasukan' => 'required', 
                'tanggal_pemasukan' => 'required', 
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
            $pemasukan = new Pemasukan;
            $pemasukan->user_id = Auth::id();
            $pemasukan->kategori_pemasukan_id = $input['kategori_pemasukan_id'];
            $pemasukan->nama_pemasukan = $input['nama_pemasukan'];
            $pemasukan->currency_id = $input['currency_id'];
            $pemasukan->jumlah_pemasukan = $input['jumlah_pemasukan'];
            $pemasukan->tanggal_pemasukan = $input['tanggal_pemasukan'];
            $pemasukan->keterangan = $input['keterangan'];
            $pemasukan->status_transaksi_berulang = $input['status_transaksi_berulang'];
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
                $pemasukan = KategoriPemasukan::where('id',$id)->where('is_delete','=',0)->first();
                if($kategoripemasukan != null){
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
                        'keterangan' => "text",
                        'status_transaksi_berulang' => 'required',
                    ]);

                    if($validator->fails()){
                        $pemasukan = Pemasukan::where('id', $id)->where('is_delete',0)->first();
                        $pemasukan->user_id = Auth::id();
                        $pemasukan->kategori_pemasukan_id = $input['kategori_pemasukan_id'];
                        $pemasukan->nama_pemasukan = $input['nama_pemasukan'];
                        $pemasukan->currency_id = $input['currency_id'];
                        $pemasukan->jumlah_pemasukan = $input['jumlah_pemasukan'];
                        $pemasukan->tanggal_pemasukan = $input['tanggal_pemasukan'];
                        $pemasukan->keterangan = $input['keterangan'];
                        $pemasukan->status_transaksi_berulang = $input['status_transaksi_berulang'];
                        $pemasukan->is_delete = 0;
                        $pemasukan->save();

                        return response()->json([
                            "status" => 201,
                            "message" => "Pemasukan created successfully.",
                            "data" => $pemasukan
                        ]);
                    }
                    $pemasukan = Pemasukan::where('id', $id)->where('is_delete',0)->first();
                    $pemasukan->user_id = Auth::id();
                    $pemasukan->kategori_pemasukan_id = $input['kategori_pemasukan_id'];
                    $pemasukan->nama_pemasukan = $input['nama_pemasukan'];
                    $pemasukan->currency_id = $input['currency_id'];
                    $pemasukan->jumlah_pemasukan = $input['jumlah_pemasukan'];
                    $pemasukan->tanggal_pemasukan = $input['tanggal_pemasukan'];
                    $pemasukan->keterangan = $input['keterangan'];
                    $pemasukan->status_transaksi_berulang = $input['status_transaksi_berulang'];
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
    }

    public function destroy_pemasukan($id){
         $pemasukan = Pemasukan::where('id',$id)->where('is_delete','=',0)->firstOrFail();
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
