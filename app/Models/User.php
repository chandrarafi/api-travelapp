<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'kata_sandi',
        'nomor_telepon',
        'peran',
    ];

    protected $hidden = [
        'kata_sandi',
        'remember_token',
    ];

    public function getAuthPasswordName()
    {
        return 'kata_sandi';
    }

    public function getAuthPassword()
    {
        return $this->kata_sandi;
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'user_id');
    }
}

