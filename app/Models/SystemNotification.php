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
        'id_provinsi',
        'id_kota',
        'title',
        'message',
        'type',
        'reference_id',
        'url',
        'data',
        'is_read',
        'read_at',
        'dismissed_from_navbar',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'dismissed_from_navbar' => 'boolean',
        'data' => 'array',
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
        $userKota = $user->kota;
        $userProvinsi = $user->provinsi;

        return $query->where(function ($q) use ($user, $userRoles, $userKota, $userProvinsi) {
            // 1. Notifikasi spesifik untuk user id
            $q->where('user_id', $user->id)
              // 2. Notifikasi broadcast tanpa role target
              ->orWhereNull('role_target')
              // 3. Notifikasi berdasarkan role target
              ->orWhere(function ($sub) use ($userRoles, $userKota, $userProvinsi) {
                  $sub->whereIn('role_target', $userRoles);

                  // Jika user adalah Admin Cabang dan notifikasi memiliki filter kota
                  if (!empty($userKota)) {
                      $sub->where(function ($kotaQ) use ($userKota) {
                          $kotaQ->whereNull('id_kota')->orWhere('id_kota', $userKota);
                      });
                  }

                  // Jika user adalah Admin Daerah dan notifikasi memiliki filter provinsi
                  if (!empty($userProvinsi)) {
                      $sub->where(function ($provQ) use ($userProvinsi) {
                          $provQ->whereNull('id_provinsi')->orWhere('id_provinsi', $userProvinsi);
                      });
                  }
              });
        });
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
