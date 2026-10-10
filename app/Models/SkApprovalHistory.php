<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SkApprovalHistory extends Model
{
    use HasFactory;

    protected $table = 'sk_approval_histories';

    protected $fillable = [
        'id_sk_pengurus',
        'id_struktur_organisasi',
        'step_key',
        'step_title',
        'status',
        'actor_id',
        'actor_name',
        'actor_role',
        'notes',
        'started_at',
        'completed_at',
        'duration_seconds',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'duration_seconds' => 'integer',
    ];

    public function skPengurus()
    {
        return $this->belongsTo(SkPengurus::class, 'id_sk_pengurus');
    }

    public function strukturOrganisasi()
    {
        return $this->belongsTo(StrukturOrganisasi::class, 'id_struktur_organisasi');
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    /**
     * Hitung durasi tunggu format teks Indonesia yang rapi (contoh: 2 Hari 5 Jam, atau 45 Menit).
     */
    public function getFormattedDurationAttribute()
    {
        $start = $this->started_at;
        $end = $this->completed_at ?: now();

        if (!$start) {
            return '-';
        }

        $diff = $start->diff($end);

        $parts = [];
        if ($diff->d > 0) {
            $parts[] = $diff->d . ' hari';
        }
        if ($diff->h > 0) {
            $parts[] = $diff->h . ' jam';
        }
        if ($diff->i > 0 && empty($parts)) {
            $parts[] = $diff->i . ' menit';
        } elseif ($diff->i > 0 && count($parts) < 2) {
            $parts[] = $diff->i . ' mnt';
        }

        if (empty($parts)) {
            return '< 1 menit';
        }

        return implode(' ', $parts);
    }
}
