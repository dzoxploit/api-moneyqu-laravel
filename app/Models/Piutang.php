<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piutang extends Model
{
    use HasFactory;

    protected $table = 'piutang';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'nama_piutang','no_telepon','deskripsi', 'jumlah_hutang', 'currency_id','status_piutang','is_delete','deleted_at'
    ];

    public function CurrencyData()
    {
        return $this->hasOne(CurrencyData::class);
    }
}
