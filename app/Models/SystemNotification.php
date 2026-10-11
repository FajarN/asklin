<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemNotification extends Model
{
    use HasFactory;

    protected $table = 'system_notifications';

    protected $fillable = [
        'user_id',
        'role_target',
        'title',
        'message',
        'type',
        'reference_id',
        'url',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeForCurrentUser($query, $user = null)
    {
        $user = $user ?: auth()->user();
        if (!$user) {
            return $query->whereRaw('1 = 0');
        }

        if ($user->hasRole('Superadmin')) {
            // Superadmin dapat melihat semua notifikasi sistem
            return $query;
        }

        $userRoles = $user->roles->pluck('name')->toArray();

        return $query->where(function ($q) use ($user, $userRoles) {
            $q->where('user_id', $user->id)
                ->orWhereIn('role_target', $userRoles)
                ->orWhereNull('role_target');
        });
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
