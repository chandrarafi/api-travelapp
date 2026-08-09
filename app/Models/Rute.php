<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    use HasFactory;

    protected $table = 'rute';

    protected $fillable = [
        'kota_asal',
        'kota_tujuan',
        'jarak_km',
    ];

    public function mobil()
    {
        return $this->hasMany(Mobil::class, 'rute_id');
    }
}
