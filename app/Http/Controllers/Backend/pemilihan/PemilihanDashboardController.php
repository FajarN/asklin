<?php

namespace App\Http\Controllers\Backend\pemilihan;

use App\Http\Controllers\Controller;
use App\Models\Pemilihan;
use App\Models\PemilihanKandidat;
use App\Models\PemilihanSuara;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PemilihanDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:pemilihan-dashboard');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // Get available elections based on user role
        $allPemilihan = Pemilihan::with(['provinsi', 'kota'])->forUser($user)->latest()->get();

        $selectedId = $request->input('pemilihan_id');
        if ($selectedId) {
            $pemilihan = $allPemilihan->firstWhere('id', $selectedId);
        } else {
            // Default to latest active or latest created
            $pemilihan = $allPemilihan->firstWhere('status', 'aktif') ?? $allPemilihan->first();
        }

        if (!$pemilihan) {
            return view('backend.pemilihan.dashboard_empty', compact('allPemilihan'));
        }

        // Fetch DPT: Approved clinics in election scope
        $dptQuery = Anggota::whereIn(DB::raw('LOWER(status)'), ['approved', 'approve']);
        if ($pemilihan->tingkat === 'cabang') {
            $dptQuery->where('id_kota', $pemilihan->id_kota);
        } else {
            $dptQuery->where('id_provinsi', $pemilihan->id_provinsi);
        }

        $totalDpt = $dptQuery->count();
        $totalSuara = PemilihanSuara::where('pemilihan_id', $pemilihan->id)->count();
        $persentasePartisipasi = $totalDpt > 0 ? round(($totalSuara / $totalDpt) * 100, 1) : 0;
        $belumMemilih = max(0, $totalDpt - $totalSuara);

        // Candidates and their votes
        $kandidats = PemilihanKandidat::where('pemilihan_id', $pemilihan->id)
            ->withCount(['suara as total_suara_hitung'])
            ->orderBy('nomor_urut', 'asc')
            ->get();

        // Calculate leading candidate
        $pemenang = $kandidats->sortByDesc('total_suara_hitung')->first();
        if ($totalSuara === 0) {
            $pemenang = null;
        }

        // Suara per hari / jam (timeline)
        $suaraTimeline = PemilihanSuara::where('pemilihan_id', $pemilihan->id)
            ->select(DB::raw("DATE_FORMAT(waktu_vote, '%d %b %H:00') as label"), DB::raw('count(*) as count'))
            ->groupBy('label')
            ->orderBy('label', 'asc')
            ->get();

        return view('backend.pemilihan.dashboard', compact(
            'allPemilihan',
            'pemilihan',
            'totalDpt',
            'totalSuara',
            'persentasePartisipasi',
            'belumMemilih',
            'kandidats',
            'pemenang',
            'suaraTimeline'
        ));
    }
}
