<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simpanan extends Model
{
    use HasFactory;

    
     use HasFactory;
    protected $table = 'simpanan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'tujuan_simpanan_id','jumlah_simpanan','deskripsi', 'status_simpanan', 'jenis_simpanan_id','currency_id','is_delete', 'deleted_at'
    ];

    public function CurrencyData()
    {
        return $this->hasOne(CurrencyData::class);
    }

    public function TujuanSimpanan()
    {
        return $this->hasOne(TujuanSimpanan::class);
    }

    public function JenisSimpanan()
    {
        return $this->hasOne(JenisSimpanan::class);
    }

    public function User()
    {
        return $this->hasOne(User::class);
    }

}
