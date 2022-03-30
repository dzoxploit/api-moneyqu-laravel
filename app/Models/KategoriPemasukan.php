<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPemasukan extends Model
{
    use HasFactory;

    protected $table = 'kategori_pemasukan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nama_pemasukan', 'deskripsi_pemasukan','is_active','is_delete'
    ];
}
