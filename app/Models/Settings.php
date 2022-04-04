<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;
    protected $table = 'settings';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'currency_id','bahasa','settings_component_1'
    ];
    public function User()
    {
        return $this->hasOne(User::class);
    }

}
