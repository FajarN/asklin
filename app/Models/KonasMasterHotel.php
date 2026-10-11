<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonasMasterHotel extends Model
{
    use HasFactory;

    protected $table = 'konas_master_hotel';

    protected $fillable = [
        'id_periode',
        'hotel',
        'status',
        'alamat',
    ];

    public function periode()
    {
        return $this->belongsTo(KonasPeriode::class, 'id_periode');
    }

    public function tipeHotel()
    {
        return $this->hasMany(KonasMasterTipeHotel::class, 'id_hotel');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            if (Auth()->check()) {
                $model->created_by = Auth()->user()->id;
                $model->updated_by = Auth()->user()->id;
            }
        });
        static::saving(function ($model) {
            if (Auth()->check()) {
                $model->updated_by = Auth()->user()->id;
            }
        });
    }
}
