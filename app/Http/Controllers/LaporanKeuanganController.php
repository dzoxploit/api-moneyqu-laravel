<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request){
        try{
            
            
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
                'nama_laporan_keuangan' => 'text',
                'deskripsi' => 'text',
                'total_pemasukan' => 'required',
                'total_pengeluaran' => 'required',
                'total_tabungan' => 'required', 
                'total_hutang' => 'required',
                'total_piutang' => 'required', 
                'total_balance' => "text",
                'currency_id' => 'required',
                'kategori_laporan_keuangan_id' => 'required',
            ]);

            if($validator->fails()){
                return response()->json([
                    "status" => 400,
                    "errors" => $validator->errors(),
                    "data" => null
                ]);          
            }
            $laporankeuangan = new Pemasukan;
            $laporankeuangan->user_id = Auth::id();
            $laporankeuangan->kategori_laporan_keuangan_id = $input['kategori_laporan_keuangan_id'];
            $laporankeuangan->nama_laporan_keuangan = $input['nama_laporan_keuangan'];
            $laporankeuangan->deskripsi = $input['deskripsi'];
            $laporankeuangan->currency_id = $input['currency_id'];
            $laporankeuangan->total_pemasukan = $input['total_pemasukan'];
            $laporankeuangan->total_pengeluaran = $input['total_pengeluaran'];
            $laporankeuangan->total_tabungan = $input['total_tabungan'];
            $laporankeuangan->total_balance = $input['total_balance'];
            $laporankeuangan->status_transaksi_berulang = $input['status_transaksi_berulang'];
            $laporankeuangan->is_deleted = 0;
            $laporankeuangan->save();
            
            return response()->json([
                "status" => 201,
                "message" => "Laporan Keuangan created successfully.",
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
