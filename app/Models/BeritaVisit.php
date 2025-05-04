<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaVisit extends Model
{
    use HasFactory;

    protected $table = 'berita_visits';

    protected $fillable = [
        'berita_id',
        'ip_address',
        'user_agent'
    ];

    public function berita()
    {
        return $this->belongsTo(Berita::class);
    }
}