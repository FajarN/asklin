<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'foto',
        'password',
        'provinsi',
        'kota',
        'kecamatan',
        'kelurahan'
    ];

    /**
     * Relasi ke model Anggota (jika akun klinik)
     */
    public function anggota()
    {
        return $this->hasOne(Anggota::class, 'id_user');
    }

    /**
     * Accessor untuk foto URL profil
     */
    public function getFotoUrlAttribute()
    {
        if ($this->foto && file_exists(public_path('images/file/' . $this->foto))) {
            return asset('images/file/' . $this->foto);
        }
        if ($this->anggota && $this->anggota->logo && file_exists(public_path('images/file/' . $this->anggota->logo))) {
            return asset('images/file/' . $this->anggota->logo);
        }
        return null;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
