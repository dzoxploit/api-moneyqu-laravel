<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKeuangan extends Model
{
    use HasFactory;

     use HasFactory;
    protected $table = 'laporan_keuangan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'kategori_laporan_keuangan_id','nama_laporan_keuangan','deskripsi', 'total_pemasukan', 'total_pengeluaran','total_tabungan','total_hutang','total_piutang', 'total_balance','currency_id', 'id_deleted', 'deleted_at'
    ];

    public function CurrencyData()
    {
        return $this->hasOne(CurrencyData::class);
    }

    public function KategoriLaporanKeuangan()
    {
        return $this->hasOne(KategoriLaporanKeuangan::class);
    }

    public function User()
    {
        return $this->hasOne(User::class);
    }

}
