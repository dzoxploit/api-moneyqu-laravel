<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Settings;
use App\Models\CurrencyData;
use Illuminate\Support\Facades\Crypt;
use Auth;
use DB;
use Validator;
class TagihanController extends Controller
{
      public function index(Request $request){
        try{
            $term = $request->get('search');
            $date = $request->get('date');
            $id = $request->get('id');
            $status_tagihan = $request->get('status_tagihan_lunas');

            $settings = Settings::where('user_id',Auth::id())->first();
            $calculatetagihannonlunas = Tagihan::where(
                                    [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                        ['status_tagihan_lunas','=',0]
                                    ])->sum('jumlah_tagihan');
                                        
           $calculatetagihanlunas = Tagihan::Where(
                                    [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                        ['status_tagihan_lunas','=',1]
                                    ])->sum('jumlah_tagihan');
        
            if($id != null){
                  $tagihan = DB::table('tagihan')->join('kategori_tagihan','kategori_tagihan.id','=','tagihan.kategori_tagihan_id')
                        ->select('tagihan.id','tagihan.nama_tagihan','kategori_tagihan.nama_tagihan as kategori','tagihan.no_rekening','tagihan.no_tagihan', 'tagihan.kode_bank','tagihan.deksripsi','tagihan.jumlah_tagihan','tagihan.status_tagihan','tagihan.tanggal_tagihan','tagihan.status_tagihan_lunas','tagihan.tanggal_tagihan_lunas')
                        ->where('tagihan.user_id',Auth::id())->where(function ($query) use ($term) {
                                $query->where('tagihan.nama_tagihan', "like", "%" . $term . "%");
                                $query->orWhere('tagihan.no_rekening', "like", "%" . $term . "%");
                                $query->orWhere('tagihan.no_tagihan', "like", "%" . $term . "%");
                                $query->orWhere('tagihan.kode_bank', "like", "%" . $term . "%");
                        })
                        ->where('tagihan.id','=', $id)
                        ->where('tagihan.is_delete','=',0)
                        ->orderBy('tagihan.id','DESC')
                        ->first();
            }else{
                 $tagihan = DB::table('tagihan')->join('kategori_tagihan','kategori_tagihan.id','=','tagihan.kategori_tagihan_id')
                        ->select('tagihan.id','tagihan.nama_tagihan','kategori_tagihan.nama_tagihan as kategori','tagihan.no_rekening','tagihan.no_tagihan', 'tagihan.kode_bank','tagihan.deksripsi','tagihan.jumlah_tagihan','tagihan.status_tagihan','tagihan.tanggal_tagihan','tagihan.status_tagihan_lunas','tagihan.tanggal_tagihan_lunas')
                        ->where('tagihan.user_id',Auth::id())->where(function ($query) use ($term) {
                                $query->where('tagihan.nama_tagihan', "like", "%" . $term . "%");
                                $query->orWhere('tagihan.no_rekening', "like", "%" . $term . "%");
                                $query->orWhere('tagihan.no_tagihan', "like", "%" . $term . "%");
                                $query->orWhere('tagihan.kode_bank', "like", "%" . $term . "%");
                        })
                        ->where('tagihan.is_delete','=',0)
                        ->orderBy('tagihan.id','DESC')
                        ->get();
            }
            
            return response()->json([
                "status" => 201,
                "message" => "Tagihan Berhasil Ditampilkan",
                "data" => [
                    "total_tagihan_belum_lunas" => $calculatetagihannonlunas,
                    "total_tagihan_sudah_lunas" => $calculatetagihanlunas,
                    "data_tagihan" => $tagihan
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
                'nama_tagihan' => 'required',
                'kategori_tagihan_id' => 'required',
                'no_rekening' => 'nullable',
                'no_tagihan' => 'nullable',
                'kode_bank' => 'nullable',
                'deskripsi' => 'nullable',
                'jumlah_tagihan' => 'required', 
                'status_tagihan' => 'required',
                'tanggal_tagihan' => 'nullable'
            ]);

            if($validator->fails()){
                return response()->json([
                    "status" => 400,
                    "errors" => $validator->errors(),
                    "data" => null
                ]);          
            }
            $tagihan = new Tagihan;
            $tagihan->user_id = Auth::id();
            $tagihan->nama_tagihan = $input['nama_tagihan'];
            $tagihan->kategori_tagihan_id = $input['kategori_tagihan_id'];
            $tagihan->no_rekening = $input['no_rekening'];
            $tagihan->no_tagihan = $input['no_tagihan'];
            $tagihan->kode_bank = $input['kode_bank'];
            $tagihan->deksripsi = $input['deskripsi'];
            $tagihan->jumlah_tagihan = $input['jumlah_tagihan'];
            $tagihan->status_tagihan = $input['status_tagihan'];
            $tagihan->tanggal_tagihan = $input['tanggal_tagihan'];
            $tagihan->status_tagihan_lunas = 0;
            $tagihan->is_delete = 0;
            $validation = balancedata($tagihan->jumlah_tagihan);
            
                    if($validation == true){
                        $tagihan->save();
                    
                        return response()->json([
                            "status" => 201,
                            "message" => "Tagihan created successfully.",
                            "data" => $tagihan
                        ]);
                    }else{

                        return response()->json([
                            "status" => 400,
                            "errors" => "Saldo Tidak Mencukupi",
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
                $tagihan = Tagihan::where('id',$id)->where('is_delete','=',0)->where('user_id',Auth::id())->first();
                if($tagihan != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "Tagihan Berhasil Ditampilkan",
                        "data" => $tagihan
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "Tagihan Tidak ada",
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
                    'nama_tagihan' => 'required',
                    'kategori_tagihan_id' => 'required',
                    'no_rekening' => 'required',
                    'no_tagihan' => 'required',
                    'kode_bank' => 'required',
                    'deskripsi' => 'nullable',
                    'jumlah_tagihan' => 'required', 
                    'status_tagihan' => 'required',
                    'tanggal_tagihan' => 'nullable'
                 ]);

            if($validator->fails()){
                $tagihan = Tagihan::where('id',$id)->where('is_delete','=',0)->where('user_id',Auth::id())->first();
                $tagihan->user_id = Auth::id();
                $tagihan->nama_tagihan = $input['nama_tagihan'];
                $tagihan->kategori_tagihan_id = $input['kategori_tagihan_id'];
                $tagihan->no_rekening = Crypt::encryptString($input['no_rekening']);
                $tagihan->no_tagihan = $input['no_tagihan'];
                $tagihan->kode_bank = $input['kode_bank'];
                $tagihan->deskripsi = $input['deskripsi'];
                $tagihan->jumlah_tagihan = $input['jumlah_tagihan'];
                $tagihan->status_tagihan = $input['status_tagihan'];
                $tagihan->tanggal_tagihan = $input['tanggal_tagihan'];
                $tagihan->status_tagihan_lunas = 0;
                $tagihan->is_delete = 0;
                  $validation = balancedata($tagihan->jumlah_tagihan);
            
                    if($validation == true){
                        $tagihan->save();
                    
                        return response()->json([
                            "status" => 201,
                            "message" => "Tagihan created successfully.",
                            "data" => $tagihan
                        ]);
                    }else{

                        return response()->json([
                            "status" => 400,
                            "errors" => "Saldo Tidak Mencukupi",
                            "data" => null
                        ]);          
                    }
            }
            $tagihan = Tagihan::where('id',$id)->where('is_delete','=',0)->where('user_id',Auth::id())->firstOrFail();
            $tagihan->user_id = Auth::id();
            $tagihan->nama_tagihan = $input['nama_tagihan'];
            $tagihan->kategori_tagihan_id = $input['kategori_tagihan_id'];
            $tagihan->no_rekening = Crypt::encryptString($input['no_rekening']);
            $tagihan->no_tagihan = $input['no_tagihan'];
            $tagihan->kode_bank = $input['kode_bank'];
            $tagihan->deskripsi = $input['deskripsi'];
            $tagihan->jumlah_tagihan = $input['jumlah_tagihan'];
            $tagihan->status_tagihan = $input['status_tagihan'];
            $tagihan->tanggal_tagihan = $input['tanggal_tagihan'];
            $tagihan->status_tagihan_lunas = 0;
            $tagihan->is_delete = 0;
            $tagihan->save();
            
            return response()->json([
                "status" => 201,
                "message" => "Tagihan Updated successfully.",
                "data" => $tagihan
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
         $tagihan = Tagihan::where('id',$id)->where('is_delete','=',0)->where('user_id',Auth::id())->firstOrFail();
         $tagihan->is_delete = 1;
         $tagihan->deleted_at = Carbon::now();
         $tagihan->save();

          return response()->json([
                        "status" => 201,
                        "message" => 'delete tabungan succesfully',
                        "data" => $tagihan
          ]);
    }        
}
