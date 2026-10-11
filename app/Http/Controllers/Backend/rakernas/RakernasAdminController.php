<?php

namespace App\Http\Controllers\Backend\rakernas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{RakernasPeriode, RakernasPeserta, RakernasUndangan, RakernasGaleri};
use Laravolt\Indonesia\Models\Province;

class RakernasAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Unified RAKERNAS Administration Dashboard with multiple tabs
     */
    public function index(Request $request)
    {
        $allPeriodes = RakernasPeriode::orderBy('tahun', 'desc')->orderBy('rakernas_ke', 'asc')->get();
        $activePeriode = RakernasPeriode::where('status', '1')->first();

        // Default to selected periode or active periode, or the first one
        $selectedPeriodeId = $request->query('periode_id', $activePeriode->id ?? ($allPeriodes->first()->id ?? null));
        $currentPeriode = $allPeriodes->firstWhere('id', $selectedPeriodeId) ?? $activePeriode ?? $allPeriodes->first();

        // Statistics for current selected period
        $stats = [
            'total_peserta' => $currentPeriode ? RakernasPeserta::where('id_periode', $currentPeriode->id)->count() : RakernasPeserta::count(),
            'hadir_fisik' => $currentPeriode ? RakernasPeserta::where('id_periode', $currentPeriode->id)->where('status_kehadiran', 'Hadir')->count() : RakernasPeserta::where('status_kehadiran', 'Hadir')->count(),
            'hadir_online' => $currentPeriode ? RakernasPeserta::where('id_periode', $currentPeriode->id)->where('status_kehadiran', 'Online')->count() : RakernasPeserta::where('status_kehadiran', 'Online')->count(),
            'total_undangan' => $currentPeriode ? RakernasUndangan::where('id_periode', $currentPeriode->id)->count() : RakernasUndangan::count(),
            'total_galeri' => $currentPeriode ? RakernasGaleri::where('id_periode', $currentPeriode->id)->count() : RakernasGaleri::count(),
            'total_periode' => $allPeriodes->count(),
        ];

        $provinsi = Province::orderBy('name', 'asc')->get();
        $activeTab = $request->query('tab', 'periode');

        return view('backend.rakernas.admin_index', compact(
            'allPeriodes',
            'activePeriode',
            'currentPeriode',
            'selectedPeriodeId',
            'stats',
            'provinsi',
            'activeTab'
        ));
    }
}

