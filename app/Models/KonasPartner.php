<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonasPartner extends Model
{
    use HasFactory;

    protected $table = 'konas_partner';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'photo',
        'link'
    ];
}
