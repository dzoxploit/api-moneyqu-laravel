<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

     use HasFactory;
     protected $table = 'pemasukan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'kategori_pemasukan_id','nama_pemasukan','currency_id', 'jumlah_pemasukan', 'tanggal_pemasukan','keterangan','status_transaksi_berulang','is_delete', 'deleted_at'
    ];

    public function CurrencyData()
    {
        return $this->hasOne(CurrencyData::class);
    }

    public function KategoriPemasukan()
    {
        return $this->hasOne(KategoriPemasukan::class);
    }

    public function User()
    {
        return $this->hasOne(User::class);
    }

}
