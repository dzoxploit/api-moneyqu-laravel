<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriLaporanKeuangan extends Model
{
    use HasFactory;

    protected $table = 'kategori_laporan_keuangan';
    protected $primaryKey = 'id';
}