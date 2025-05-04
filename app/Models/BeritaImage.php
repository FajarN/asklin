<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeritaImage extends Model
{
    use HasFactory;

    protected $table = 'berita_images';

    protected $fillable = ['berita_id', 'gambar'];

   public function berita()
{
    return $this->belongsTo(Berita::class, 'berita_id');
}

}
