<?php

namespace App\Http\Controllers\Backend\konas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{KonasPeriode, KonasPeserta, KonasBookingHotel, KonasMasterHotel, KonasMasterTipeHotel, KonasPenerbangan, KonasPartner};
use Laravolt\Indonesia\Models\Province;

class KonasAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Unified KONAS Administration Dashboard with multiple tabs
     */
    public function index(Request $request)
    {
        $allPeriodes = KonasPeriode::orderBy('tahun_mulai', 'desc')->get();
        $activePeriode = KonasPeriode::where('status', '1')->first();

        // Default to selected periode or active periode, or the first one
        $selectedPeriodeId = $request->query('periode_id', $activePeriode->id ?? ($allPeriodes->first()->id ?? null));
        $currentPeriode = $allPeriodes->firstWhere('id', $selectedPeriodeId) ?? $activePeriode ?? $allPeriodes->first();

        // Statistics for current selected period
        $stats = [
            'total_peserta' => $currentPeriode ? KonasPeserta::where('id_periode', $currentPeriode->id)->count() : KonasPeserta::count(),
            'lunas_peserta' => $currentPeriode ? KonasPeserta::where('id_periode', $currentPeriode->id)->whereIn('status_pembayaran', ['PAID', 'SETTLED'])->count() : KonasPeserta::whereIn('status_pembayaran', ['PAID', 'SETTLED'])->count(),
            'pending_peserta' => $currentPeriode ? KonasPeserta::where('id_periode', $currentPeriode->id)->whereNotIn('status_pembayaran', ['PAID', 'SETTLED'])->count() : KonasPeserta::whereNotIn('status_pembayaran', ['PAID', 'SETTLED'])->count(),
            'total_booking' => $currentPeriode ? KonasBookingHotel::where('id_periode', $currentPeriode->id)->count() : KonasBookingHotel::count(),
            'total_hotel' => $currentPeriode ? KonasMasterHotel::where('id_periode', $currentPeriode->id)->count() : KonasMasterHotel::count(),
            'total_partner' => $currentPeriode ? KonasPartner::where('id_periode', $currentPeriode->id)->count() : KonasPartner::count(),
            'total_flight' => $currentPeriode ? KonasPenerbangan::where('id_periode', $currentPeriode->id)->count() : KonasPenerbangan::count(),
        ];

        $provinsi = Province::orderBy('name', 'asc')->get();
        $masterHotel = $currentPeriode ? KonasMasterHotel::where('id_periode', $currentPeriode->id)->get() : KonasMasterHotel::all();
        $activeTab = $request->query('tab', 'periode');

        return view('backend.konas.admin_index', compact(
            'allPeriodes',
            'activePeriode',
            'currentPeriode',
            'selectedPeriodeId',
            'stats',
            'provinsi',
            'masterHotel',
            'activeTab'
        ));
    }
}

