<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hutang extends Model
{
    use HasFactory;

    protected $table = 'hutang';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'nama_hutang','no_telepon','deskripsi', 'jumlah_hutang', 'currency_id','status_hutang','is_delete','deleted_at'
    ];

    public function CurrencyData()
    {
        return $this->hasOne(CurrencyData::class);
    }
}
