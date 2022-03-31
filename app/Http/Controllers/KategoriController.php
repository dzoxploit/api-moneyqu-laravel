<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriPemasukan;
use App\Models\KategoriPengeluaran;
use App\Models\KategoriLaporanKeuangan;
use Validator;
class KategoriController extends Controller
{

    /** Fitur kategori Pemasukan
     * 
     * index create read update delete
     */

    public function index_kategori_pemasukan(){
        try{
            $kategoripemasukan = KategoriPemasukan::where('is_delete','=',0)->get();
                if($kategoripemasukan != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "Kategori Pemasukan Berhasil Ditampilkan",
                        "data" => $kategoripemasukan
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "Kategori Pemasukan Tidak ada",
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
    }


    public function create_kategori_pemasukan(Request $request){
          
        $input = $request->all();
        
        try{
            $validator = Validator::make($input, [
                'nama_pemasukan' => 'required',
                'deskripsi_pemasukan' => 'required',
                'is_active' => 'required',
                'is_delete' => 'required'
            ]);

            if($validator->fails()){
                return response()->json([
                    "status" => 400,
                    "errors" => $validator->errors(),
                    "data" => null
                ]);          
            }
            $kategoripemasukan = KategoriPemasukan::create($input);
            return response()->json([
                "status" => 201,
                "message" => "Kategori Pemasukan created successfully.",
                "data" => $kategoripemasukan
            ]);
        }catch(\Exception $e){
            return response()->json([
                "status" => 401,
                "message" => 'Error'.$e->getMessage(),
                "data" => null
            ]);
        } 
    }

    public function update_kategori_pemasukan(Request $request, $id){
        if ($request->isMethod('get')){
            try{
                $kategoripemasukan = KategoriPemasukan::where('id',$id)->where('is_delete','=',0)->first();
                if($kategoripemasukan != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "Kategori Pemasukan Berhasil Ditampilkan",
                        "data" => $kategoripemasukan
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "Kategori Pemasukan Tidak ada",
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
                    'deskripsi_pemasukan' => 'required',
                    'is_active' => 'required',
                    'is_delete' => 'required'
                ]);

                if($validator->fails()){
                    $kategoripemasukan = KategoriPemasukan::where('id',$id)->where('is_delete','=',0)->firstOrFail();
                    $kategoripemasukan->nama_pemasukan = $input['nama_pemasukan'];
                    $kategoripemasukan->deskripsi_pemasukan = $input['deskripsi_pemasukan'];
                    $kategoripemasukan->is_active = $input['is_active'];
                    $kategoripemasukan->save();

                    return response()->json([
                        "status" => 201,
                        "message" => 'update data kategori pemasukan succesfully',
                        "data" => $kategoripemasukan
                    ]);      
                }
                $kategoripemasukan = KategoriPemasukan::where('id',$id)->where('is_delete','=',0)->firstOrFail();
                $kategoripemasukan->nama_pemasukan = $input['nama_pemasukan'];
                $kategoripemasukan->deskripsi_pemasukan = $input['deskripsi_pemasukan'];
                $kategoripemasukan->is_active = $input['is_active'];
                $kategoripemasukan->save();

                 return response()->json([
                        "status" => 201,
                        "message" => 'update data kategori pemasukan succesfully',
                        "data" => $kategoripemasukan
                 ]);

            }catch(\Exception $e){
                return response()->json([
                    "status" => 400,
                    "message" => 'Error'.$e->getMessage(),
                    "data" => null
                ]);
            } 

        }
    }


    public function destroy_kategori_pemasukan($id){
         $kategoripemasukan = KategoriPemasukan::where('id',$id)->where('is_delete','=',0)->firstOrFail();
         $kategoripemasukan->is_delete = 1;
         $kategoripemasukan->save();

          return response()->json([
                        "status" => 201,
                        "message" => 'delete data kategori pemasukan succesfully',
                        "data" => $kategoripemasukan
          ]);
    }



    /** Fitur kategori Pengeluaran
     * 
     * index create read update delete
     */
    
    public function index_kategori_pengeluaran(){
        try{
            $kategoripengeluaran = KategoriPengeluaran::where('is_delete','=',0)->get();
                if($kategoripengeluaran != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "Kategori Pengeluaran Berhasil Ditampilkan",
                        "data" => $kategoripengeluaran
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "Kategori Pengeluaran Tidak ada",
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
    }


    public function create_kategori_pengeluaran(Request $request){
          
        $input = $request->all();
        
        try{
            $validator = Validator::make($input, [
                'nama_pengeluaran' => 'required',
                'deskripsi_pengeluaran' => 'required',
                'is_active' => 'required',
                'is_delete' => 'required'
            ]);

            if($validator->fails()){
                return response()->json([
                    "status" => 400,
                    "errors" => $validator->errors(),
                    "data" => null
                ]);          
            }
            $kategoripengeluaran = KategoriPengeluaran::create($input);
            return response()->json([
                "status" => 201,
                "message" => "Kategori Pengeluaran created successfully.",
                "data" => $kategoripengeluaran
            ]);
        }catch(\Exception $e){
            return response()->json([
                "status" => 401,
                "message" => 'Error'.$e->getMessage(),
                "data" => null
            ]);
        } 
    }

    public function update_kategori_pengeluaran(Request $request, $id){
        if ($request->isMethod('get')){
            try{
                $kategoripengeluaran = KategoriPengeluaran::where('id',$id)->where('is_delete','=',0)->first();
                if($kategoripengeluaran != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "Kategori Pengeluaran Berhasil Ditampilkan",
                        "data" => $kategoripemasukan
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "Kategori Pengeluaran Tidak ada",
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
                    'deskripsi_pengeluaran' => 'required',
                    'is_active' => 'required',
                    'is_delete' => 'required'
                ]);

                if($validator->fails()){
                    $kategoripengeluaran = KategoriPengeluaran::where('id',$id)->where('is_delete','=',0)->firstOrFail();
                    $kategoripengeluaran->nama_pengeluaran = $input['nama_pengeluaran'];
                    $kategoripengeluaran->deskripsi_pengeluaran = $input['deskripsi_pengeluaran'];
                    $kategoripengeluaran->is_active = $input['is_active'];
                    $kategoripengeluaran->save();

                    return response()->json([
                        "status" => 201,
                        "message" => 'update data kategori pengeluaran succesfully',
                        "data" => $kategoripengeluaran
                    ]);      
                }
                $kategoripengeluaran = KategoriPengeluaran::where('id',$id)->where('is_delete','=',0)->firstOrFail();
                $kategoripengeluaran->nama_pengeluaran = $input['nama_pengeluaran'];
                $kategoripengeluaran->deskripsi_pengeluaran = $input['deskripsi_pengeluaran'];
                $kategoripengeluaran->is_active = $input['is_active'];
                $kategoripengeluaran->save();

                 return response()->json([
                        "status" => 201,
                        "message" => 'update data kategori pengeluaran succesfully',
                        "data" => $kategoripengeluaran
                 ]);

            }catch(\Exception $e){
                return response()->json([
                    "status" => 400,
                    "message" => 'Error'.$e->getMessage(),
                    "data" => null
                ]);
            } 

        }
    }


    public function destroy_kategori_pemngeluaran($id){
         $kategoripengeluaran = KategoriPengeluaran::where('id',$id)->where('is_delete','=',0)->firstOrFail();
         $kategoripengeluaran->is_delete = 1;
         $kategoripengeluaran->save();

          return response()->json([
                        "status" => 201,
                        "message" => 'delete data kategori pengeluaran succesfully',
                        "data" => $kategoripengeluaran
          ]);
    }

    
    
}
