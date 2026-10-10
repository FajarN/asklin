<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait RegionalScoping
{
    /**
     * Scope query berdasarkan hak akses wilayah pengguna yang sedang login.
     * 
     * - Admin Cabang: Hanya melihat data kota miliknya (id_kota = user.kota)
     * - Admin Daerah: Hanya melihat data dalam provinsinya (id_provinsi = user.provinsi)
     * - Pengurus Pusat / Superadmin: Melihat data lintas wilayah nasional
     */
    public function scopeForCurrentUser(Builder $query, $columnKota = 'id_kota', $columnProvinsi = 'id_provinsi'): Builder
    {
        $user = Auth::user();

        if (!$user) {
            return $query;
        }

        if ($user->hasRole('Admin Cabang') && !empty($user->kota)) {
            return $query->where($columnKota, $user->kota);
        }

        if ($user->hasRole('Admin Daerah') && !empty($user->provinsi)) {
            return $query->where($columnProvinsi, $user->provinsi);
        }

        return $query;
    }

    /**
     * Helper untuk memverifikasi apakah user memiliki hak akses ke record ini
     */
    public function isAccessibleByCurrentUser($columnKota = 'id_kota', $columnProvinsi = 'id_provinsi'): bool
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        // Pengurus Pusat / Superadmin memiliki akses universal
        if ($user->hasRole(['Superadmin', 'Admin Pusat', 'Sekjen', 'Ketua Umum'])) {
            return true;
        }

        if ($user->hasRole('Admin Cabang')) {
            return (string) $this->{$columnKota} === (string) $user->kota;
        }

        if ($user->hasRole('Admin Daerah')) {
            return (string) $this->{$columnProvinsi} === (string) $user->provinsi;
        }

        return false;
    }
}

