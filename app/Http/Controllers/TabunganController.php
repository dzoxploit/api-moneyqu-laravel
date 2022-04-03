<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tabungan;
use App\Models\Settings;
use Validator;
class TabunganController extends Controller
{
    
    public function index(Request $request){
        try{
            $term = $request->get('search');
            $settings = Settings::where('user_id',Auth::id())->first();
            $calculatetabungan = Tabungan::Where(
                                    [
                                        ['is_delete', '=', 0],
                                        ['user_id', '=', Auth::id()],
                                        ['currency_id', '=', $settings->currency_id]
                                    ])-sum('jumlah_tabungan');
            

            $tabungan = Tabungan::where([
                ['deskripsi', '!=', NULL],
                [function ($query) use ($request){
                    if (($term = $request->term)){
                        $query->orWhere('deskripsi', 'LIKE', '%' . $term .'%')->get();
                    }
                }]
            ])    
            ->orderBy('id','DESC')
            ->paginate(10);

            return response()->json([
                "status" => 201,
                "message" => "Tabungan Berhasil Ditampilkan",
                "data" => [
                    "total_tabungan" => $calculatetabungan,
                    "data_tabungan" => $tabungan
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
                'tujuan_tabungan_id' => 'required',
                'jumlah_tabungan' => 'required',
                'deskripsi' => 'required',
                'jenis_tabungan_id' => 'required',
                'currency_id' => 'required', 
            ]);

            if($validator->fails()){
                return response()->json([
                    "status" => 400,
                    "errors" => $validator->errors(),
                    "data" => null
                ]);          
            }
            $tabungan = new Tabungan;
            $tabungan->user_id = Auth::id();
            $tabungan->jumlah_tabungan = $input['jumlah_tabungan'];
            $tabungan->tujuan_tabungan_id = $input['tujuan_tabungan_id'];
            $tabungan->currency_id = $input['currency_id'];
            $tabungan->deskripsi = $input['deskripsi'];
            $tabungan->jenis_tabungan_id = $input['jenis_tabungan_id'];
            $tabungan->is_delete = 0;
            $tabungan->save();
            
            return response()->json([
                "status" => 201,
                "message" => "Tabungan created successfully.",
                "data" => $tabungan
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
                $tabungan = Tabungan::where('id',$id)->where('is_delete','=',0)->first();
                if($tabungan != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "Tabungan Berhasil Ditampilkan",
                        "data" => $pemasukan
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "Tabungan Tidak ada",
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
                        'tujuan_tabungan_id' => 'required',
                        'jumlah_tabungan' => 'required',
                        'deskripsi' => 'required',
                        'jenis_tabungan_id' => 'required',
                        'currency_id' => 'required', 
                    ]);

                    if($validator->fails()){
                        $tabungan = Tabungan::where('id','=',$id)->first();
                        $tabungan->user_id = Auth::id();
                        $tabungan->jumlah_tabungan = $input['jumlah_tabungan'];
                        $tabungan->tujuan_tabungan_id = $input['tujuan_tabungan_id'];
                        $tabungan->currency_id = $input['currency_id'];
                        $tabungan->deskripsi = $input['deskripsi'];
                        $tabungan->jenis_tabungan_id = $input['jenis_tabungan_id'];
                        $tabungan->is_delete = 0;
                        $tabungan->save();

                        return response()->json([
                            "status" => 201,
                            "message" => "Tabungan created successfully.",
                            "data" => $tabungan
                        ]);
                    }
                   $tabungan = Tabungan::where('id','=',$id)->first();
                        $tabungan->user_id = Auth::id();
                        $tabungan->jumlah_tabungan = $input['jumlah_tabungan'];
                        $tabungan->tujuan_tabungan_id = $input['tujuan_tabungan_id'];
                        $tabungan->currency_id = $input['currency_id'];
                        $tabungan->deskripsi = $input['deskripsi'];
                        $tabungan->jenis_tabungan_id = $input['jenis_tabungan_id'];
                        $tabungan->is_delete = 0;
                        $tabungan->save();

                    
                    return response()->json([
                        "status" => 201,
                        "message" => "Tabungan created successfully.",
                        "data" => $tabungan
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
         $tabungan = Tabungan::where('id',$id)->where('is_delete','=',0)->firstOrFail();
         $tabungan->is_delete = 1;
         $pemasukan->deleted_at = Carbon::now();
         $pemasukan->save();

          return response()->json([
                        "status" => 201,
                        "message" => 'delete tabungan succesfully',
                        "data" => $tabungan
          ]);
    }    
}
