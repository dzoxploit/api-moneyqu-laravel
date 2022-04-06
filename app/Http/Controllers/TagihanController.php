<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tagihan;
use App\Models\Settings;
use App\Models\CurrencyData;
use Illuminate\Support\Facades\Crypt;
use Auth;
class TagihanController extends Controller
{
      public function index(Request $request){
        try{
            $term = $request->get('search');
            $date = $request->get('date');
            $status_tagihan = $request->get('status_tagihan_lunas');

            $settings = Settings::where('user_id',Auth::id())->first();
            $calculategihannonlunas = Tagihan::Where(
                                    [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                        ['currency_id', '=', $settings->currency_id].
                                        ['status_tagihan_lunas','=',0]
                                    ])-sum('jumlah_tagihan');
            
           $calculategihanlunas = Tagihan::Where(
                                    [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                        ['currency_id', '=', $settings->currency_id].
                                        ['status_tagihan_lunas','=',1]
                                    ])-sum('jumlah_tagihan');
            

            $tagihan = Tagihan::where([
                [function ($query) use ($request){
                    if (($term = $request->term)){
                        $query->orWhere('nama_tagihan', 'LIKE', '%' . $term .'%')
                        ->orWhere('no_rekening', 'LIKE', '%' . $term .'%')
                        ->orWhere('status_tagihan_lunas','=',$status_tagihan)
                        ->orWhere('tanggal_tagihan_lunas', '=', $date)
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
                'no_rekening' => 'required',
                'no_tagihan' => 'required',
                'kode_bank' => 'required',
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
                "message" => "Tagihan created successfully.",
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
            }
            $tagihan = Tagihan::where('id',$id)->where('is_delete','=',0)->where('user_id',Auth::id())->firstOrFail();
            $tagihan->user_id = Auth::id();
            $tagihan->nama_tagihan = $input['nama_tagihan'];
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
