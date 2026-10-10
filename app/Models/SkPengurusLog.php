<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkPengurusLog extends Model
{
    use HasFactory;

    protected $table = 'sk_pengurus_logs';

    public $timestamps = false; // Hanya menggunakan created_at

    protected $fillable = [
        'id_struktur_organisasi',
        'id_sk_pengurus',
        'user_id',
        'role_name',
        'action',
        'catatan',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function strukturOrganisasi(): BelongsTo
    {
        return $this->belongsTo(StrukturOrganisasi::class, 'id_struktur_organisasi');
    }

    public function skPengurus(): BelongsTo
    {
        return $this->belongsTo(SkPengurus::class, 'id_sk_pengurus');
    }

    /**
     * Helper cepat untuk merekam jejak riwayat aksi
     */
    public static function record($strukturId, $action, $roleName, $userId, $catatan = null, $skId = null)
    {
        return self::create([
            'id_struktur_organisasi' => $strukturId,
            'id_sk_pengurus' => $skId,
            'user_id' => $userId,
            'role_name' => $roleName,
            'action' => $action,
            'catatan' => $catatan,
            'created_at' => now(),
        ]);
    }
}

