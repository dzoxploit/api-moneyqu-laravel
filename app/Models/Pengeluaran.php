<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

     use HasFactory;
     protected $table = 'pengeluaran';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'kategori_pengeluaran_id','', 'nama_pengeluaran','currency_id', 'jumlah_pengeluaran', 'tanggal_pengeluaran','keterangan','is_delete', 'deleted_at'
    ];

    public function CurrencyData()
    {
        return $this->hasOne(CurrencyData::class);
    }

    public function KategoriPengeluaran()
    {
        return $this->hasOne(KategoriPengeluaran::class);
    }

    public function User()
    {
        return $this->hasOne(User::class);
    }

}
