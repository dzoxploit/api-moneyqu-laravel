<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KategoriPemasukan;
use App\Models\KategoriPengeluaran;
use App\Models\KategoriLaporanKeuangan;
use App\Models\KategoriTagihan;
use App\Models\KategoriTujuanKeuangan;
use Validator;
use Carbon\Carbon;
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
         $kategoripemasukan->deleted_at = Carbon::now();
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


    public function destroy_kategori_pengeluaran($id){
         $kategoripengeluaran = KategoriPengeluaran::where('id',$id)->where('is_delete','=',0)->firstOrFail();
         $kategoripengeluaran->is_delete = 1;
         $kategoripengeluaran->deleted_at = Carbon::now();
         $kategoripengeluaran->save();

          return response()->json([
                        "status" => 201,
                        "message" => 'delete data kategori pengeluaran succesfully',
                        "data" => $kategoripengeluaran
          ]);
    }



       /** Fitur kategori Tagihan
     * 
     * index create read update delete
     */
    
    public function index_kategori_tagihan(){
        try{
            $kategoritagihan = KategoriTagihan::where('is_delete','=',0)->get();
                if($kategoripengeluaran != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "Kategori Tagihan Berhasil Ditampilkan",
                        "data" => $kategoritagihan
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "Kategori Tagihan Tidak ada",
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


    public function create_kategori_tagihan(Request $request){
          
        $input = $request->all();
        
        try{
            $validator = Validator::make($input, [
                'nama_tagihan' => 'required',
                'deskripsi_tagihan' => 'required',
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
            $kategoritagihan = KategoriTagihan::create($input);
            return response()->json([
                "status" => 201,
                "message" => "Kategori Tagihan created successfully.",
                "data" => $kategoritagihan
            ]);
        }catch(\Exception $e){
            return response()->json([
                "status" => 401,
                "message" => 'Error'.$e->getMessage(),
                "data" => null
            ]);
        } 
    }

    public function update_kategori_tagihan(Request $request, $id){
        if ($request->isMethod('get')){
            try{
                $kategoritagihan = KategoriTagihan::where('id',$id)->where('is_delete','=',0)->first();
                if($kategoritagihan != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "Kategori Tagihan Berhasil Ditampilkan",
                        "data" => $kategoritagihan
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
                    'nama_tagihan' => 'required',
                    'deskripsi_tagihan' => 'required',
                    'is_active' => 'required',
                    'is_delete' => 'required'
                ]);

                if($validator->fails()){
                    $kategoritagihan = KategoriTagihan::where('id',$id)->where('is_delete','=',0)->firstOrFail();
                    $kategoritagihan->nama_tagihan = $input['nama_tagihan'];
                    $kategoritagihan->deskripsi_tagihan = $input['deskripsi_tagihan'];
                    $kategoritagihan->is_active = $input['is_active'];
                    $kategoritagihan->save();

                    return response()->json([
                        "status" => 201,
                        "message" => 'update data kategori tagihan succesfully',
                        "data" => $kategoritagihan
                    ]);      
                }
                $kategoritagihan = KategoriTagihan::where('id',$id)->where('is_delete','=',0)->firstOrFail();
                $kategoritagihan->nama_tagihan = $input['nama_tagihan'];
                $kategoritagihan->deskripsi_tagihan = $input['deskripsi_tagihan'];
                $kategoritagihan->is_active = $input['is_active'];
                $kategoritagihan->save();

                 return response()->json([
                        "status" => 201,
                        "message" => 'update data kategori tagihan succesfully',
                        "data" => $kategoritagihan
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


    public function destroy_kategori_tagihan($id){
         $kategoritagihan = KategoriTagihan::where('id',$id)->where('is_delete','=',0)->firstOrFail();
         $kategoritagihan->is_delete = 1;
         $kategoritagihan->deleted_at = Carbon::now();
         $kategoritagihan->save();

          return response()->json([
                        "status" => 201,
                        "message" => 'delete data kategori tagihan succesfully',
                        "data" => $kategoritagihan
          ]);
    }


           /** Fitur kategori Tujuan Keuangan
     * 
     * index create read update delete
     */
    
    public function index_kategori_tujuan_keuangan(){
        try{
            $kategoritujuankeuangan = KategoriTujuanKeuangan::where('is_delete','=',0)->get();
                if($kategoritujuankeuangan != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "Kategori Tagihan Berhasil Ditampilkan",
                        "data" => $kategoritujuankeuangan
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "Kategori Tagihan Tidak ada",
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


    public function create_tujuan_keuangan(Request $request){
          
        $input = $request->all();
        
        try{
            $validator = Validator::make($input, [
                'nama_tujuan_keuangan' => 'required',
                'deskripsi_tujuan_keuangan' => 'required',
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
            $kategoritujuankeuangan = KategoriTujuanKeuangan::create($input);
            return response()->json([
                "status" => 201,
                "message" => "Kategori Tujuan Keuangan created successfully.",
                "data" => $kategoritujuankeuangan
            ]);
        }catch(\Exception $e){
            return response()->json([
                "status" => 401,
                "message" => 'Error'.$e->getMessage(),
                "data" => null
            ]);
        } 
    }

    public function update_tujuan_keuangan(Request $request, $id){
        if ($request->isMethod('get')){
            try{
                $kategoritujuankeuangan = KategoriTujuanKeuangan::where('id',$id)->where('is_delete','=',0)->first();
                if($kategoritujuankeuangan != null){
                    return response()->json([
                        "status" => 201,
                        "message" => "Kategori Tujuan Keuangan Berhasil Ditampilkan",
                        "data" => $kategoritujuankeuangan
                    ]);
                }else{
                    return response()->json([
                        "status" => 404,
                        "message" => "Kategori tujuan keuangan Tidak ada",
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
                    'deskripsi_tagihan' => 'required',
                    'is_active' => 'required',
                    'is_delete' => 'required'
                ]);

                if($validator->fails()){
                    $kategoritujuankeuangan = KategoriTujuanKeuangan::where('id',$id)->where('is_delete','=',0)->firstOrFail();
                    $kategoritujuankeuangan->nama_tujuan_keuangan = $input['nama_tujuan_keuangan'];
                    $kategoritujuankeuangan->deskripsi_tujuan_keuangan = $input['deskripsi_tujuan_keuangan'];
                    $kategoritujuankeuangan->is_active = $input['is_active'];
                    $kategoritujuankeuangan->save();

                    return response()->json([
                        "status" => 201,
                        "message" => 'update data kategori tujuan keuangan succesfully',
                        "data" => $kategoritagihan
                    ]);      
                }
                $kategoritujuankeuangan = KategoriTujuanKeuangan::where('id',$id)->where('is_delete','=',0)->firstOrFail();
                    $kategoritujuankeuangan->nama_tujuan_keuangan = $input['nama_tujuan_keuangan'];
                    $kategoritujuankeuangan->deskripsi_tujuan_keuangan = $input['deskripsi_tujuan_keuangan'];
                    $kategoritujuankeuangan->is_active = $input['is_active'];
                    $kategoritujuankeuangan->save();

                 return response()->json([
                        "status" => 201,
                        "message" => 'update data kategori tujuan keuangan succesfully',
                        "data" => $kategoritujuankeuangan
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


    public function destroy_kategori_tujuan_keuangan($id){
         $kategoritujuankeuangan = KategoriTujuanKeuangan::where('id',$id)->where('is_delete','=',0)->firstOrFail();
         $kategoritujuankeuangan->is_delete = 1;
         $kategoritujuankeuangan->deleted_at = Carbon::now();
         $kategoritujuankeuangan->save();

          return response()->json([
                        "status" => 201,
                        "message" => 'delete data kategori tujuan keuangan succesfully',
                        "data" => $kategoritagihan
          ]);
    }

    
    
}
