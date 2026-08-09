<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mobil extends Model
{
    use HasFactory;

    protected $table = 'mobil';

    protected $fillable = [
        'rute_id',
        'nama_mobil',
        'nomor_plat',
        'jam_keberangkatan',
        'harga',
        'total_kursi',
        'foto',
    ];

    public function rute()
    {
        return $this->belongsTo(Rute::class, 'rute_id');
    }

    public function kursi()
    {
        return $this->hasMany(Kursi::class, 'mobil_id');
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'mobil_id');
    }
}
