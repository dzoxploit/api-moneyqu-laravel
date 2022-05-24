<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;
    protected $table = 'tagihan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'nama_tagihan','kategori_tagihan_id','no_rekening','no_tagihan', 'kode_bank','deksripsi','jumlah_tagihan','status_tagihan','tanggal_tagihan','status_tagihan_lunas','tanggal_tagihan_lunas','is_delete', 'deleted_at'
    ];
    
    public function User()
    {
        return $this->hasOne(User::class);
    }


}
