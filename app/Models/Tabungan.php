<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tabungan extends Model
{
    use HasFactory;

    
     use HasFactory;
    protected $table = 'tabungan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'tujuan_tabungan_id','jumlah_tabyngan','deskripsi', 'status_tabungan', 'jenis_tabungan_id','currency_id','is_delete', 'deleted_at'
    ];

    public function CurrencyData()
    {
        return $this->hasOne(CurrencyData::class);
    }

    public function TujuanTabungan()
    {
        return $this->hasOne(TujuanKeuangan::class);
    }

    public function JenisTabungan()
    {
        return $this->hasOne(JenisTabungan::class);
    }

    public function User()
    {
        return $this->hasOne(User::class);
    }

}
