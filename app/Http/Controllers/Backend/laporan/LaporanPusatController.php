<?php

namespace App\Http\Controllers\Backend\laporan;

use App\Http\Controllers\Controller;
use App\Services\LaporanService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;
use Yajra\DataTables\Facades\DataTables;

class LaporanPusatController extends Controller
{
    protected LaporanService $laporanService;

    public function __construct(LaporanService $laporanService)
    {
        $this->middleware('permission:laporan-pusat');
        $this->laporanService = $laporanService;
    }

    /**
     * Dashboard & Pusat Komando Eksekutif Pelaporan Nasional ASKLIN.
     */
    public function index(Request $request)
    {
        $scope = $this->laporanService->resolveScope('pusat', Auth::user(), $request);
        $years = $this->laporanService->getAvailableYears();
        $provinces = Province::orderBy('name', 'asc')->get(['code', 'name']);
        $cities = $scope['provinsi_id'] 
            ? City::where('province_code', $scope['provinsi_id'])->orderBy('name', 'asc')->get(['code', 'name']) 
            : collect();

        $stats = $this->laporanService->getDashboardStats($scope, $request);
        $growthChart = $this->laporanService->getGrowthChartData($scope, $request);
        $statusChart = $this->laporanService->getStatusChartData($scope, $request);
        $regionalChart = $this->laporanService->getRegionalDistributionChartData($scope, $request);
        $breakdownChart = $this->laporanService->getBreakdownChartData($scope, $request);
        $rekapitulasi = $this->laporanService->getRekapitulasiData($scope, $request);
        $matriks = $this->laporanService->getMatrixData($scope, $request);

        return view('backend.laporan.pusat', compact(
            'scope',
            'provinces',
            'cities',
            'years',
            'stats',
            'growthChart',
            'statusChart',
            'regionalChart',
            'breakdownChart',
            'rekapitulasi',
            'matriks'
        ));
    }

    /**
     * Server-side DataTables: Data Anggota Nasional.
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $scope = $this->laporanService->resolveScope('pusat', Auth::user(), $request);
            $query = $this->laporanService->getAnggotaQuery($scope, $request);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('tgl_daftar', function ($row) {
                    if (!$row->tgl_daftar_raw) return '-';
                    return Carbon::parse($row->tgl_daftar_raw)->format('d-m-Y');
                })
                ->addColumn('status_badge', function ($row) {
                    $hasNoAnggota = !empty(trim($row->no_anggota ?? ''));
                    if ($hasNoAnggota) {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800"><i class="fas fa-check-circle mr-1"></i> Disetujui</span>';
                    } else {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-300 dark:border-amber-800"><i class="fas fa-clock mr-1"></i> Belum Disetujui</span>';
                    }
                })
                ->addColumn('status_pembayaran_badge', function ($row) {
                    if ($row->status_pembayaran == '1') {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800"><i class="fas fa-check mr-1"></i> Lunas</span>';
                    } else {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-300 dark:border-amber-800"><i class="fas fa-hourglass-half mr-1"></i> Belum Lunas</span>';
                    }
                })
                ->addColumn('tgl_akhir_ijin_formatted', function ($row) {
                    return $row->tgl_akhir_ijin ? Carbon::parse($row->tgl_akhir_ijin)->format('d-m-Y') : '-';
                })
                ->addColumn('action', function ($row) {
                    $detailUrl = route('anggota.detail_anggota', $row->id);
                    return '<a href="' . $detailUrl . '" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 transition" target="_blank">
                        <i class="fas fa-eye text-red-600"></i> Detail
                    </a>';
                })
                ->rawColumns(['status_badge', 'status_pembayaran_badge', 'action'])
                ->make(true);
        }
    }

    /**
     * Server-side DataTables: Data Sertifikat Nasional.
     */
    public function sertifikatList(Request $request)
    {
        if ($request->ajax()) {
            $scope = $this->laporanService->resolveScope('pusat', Auth::user(), $request);
            $query = $this->laporanService->getSertifikatQuery($scope, $request);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('tgl_terbit', function ($row) {
                    return $row->dari && $row->dari != '0000-00-00' ? Carbon::parse($row->dari)->format('d-m-Y') : '-';
                })
                ->addColumn('tgl_berlaku', function ($row) {
                    return $row->sampai && $row->sampai != '0000-00-00' ? Carbon::parse($row->sampai)->format('d-m-Y') : '-';
                })
                ->addColumn('status_badge', function ($row) {
                    if ($row->status_masa_berlaku === 'Aktif') {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800"><i class="fas fa-shield-alt mr-1"></i> Aktif</span>';
                    } else {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 dark:bg-rose-950/40 dark:text-rose-400 border border-rose-300 dark:border-rose-800"><i class="fas fa-exclamation-circle mr-1"></i> Kedaluwarsa</span>';
                    }
                })
                ->addColumn('status_bayar_badge', function ($row) {
                    if ($row->status_pembayaran == '1') {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800">Lunas</span>';
                    } else {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-300 dark:border-amber-800">Menunggu</span>';
                    }
                })
                ->rawColumns(['status_badge', 'status_bayar_badge'])
                ->make(true);
        }
    }

    /**
     * Server-side DataTables: Data Pembayaran & Iuran Nasional.
     */
    public function pembayaranList(Request $request)
    {
        if ($request->ajax()) {
            $scope = $this->laporanService->resolveScope('pusat', Auth::user(), $request);
            $query = $this->laporanService->getPembayaranQuery($scope, $request);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('tanggal_bayar_formatted', function ($row) {
                    return $row->tanggal_bayar && $row->tanggal_bayar != '0000-00-00' ? Carbon::parse($row->tanggal_bayar)->format('d-m-Y') : '-';
                })
                ->addColumn('periode', function ($row) {
                    $dari = $row->dari && $row->dari != '0000-00-00' ? Carbon::parse($row->dari)->format('d-m-Y') : '-';
                    $sampai = $row->sampai && $row->sampai != '0000-00-00' ? Carbon::parse($row->sampai)->format('d-m-Y') : '-';
                    return $dari . ' s/d ' . $sampai;
                })
                ->addColumn('nominal_formatted', function ($row) {
                    return 'Rp ' . number_format((float) $row->total, 0, ',', '.');
                })
                ->addColumn('status_badge', function ($row) {
                    if ($row->status == '1') {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-300 dark:border-emerald-800"><i class="fas fa-check-circle mr-1"></i> Berhasil / Lunas</span>';
                    } else {
                        return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-300 dark:border-amber-800"><i class="fas fa-clock mr-1"></i> Menunggu / Tertunda</span>';
                    }
                })
                ->addColumn('bukti_view', function ($row) {
                    if ($row->bukti) {
                        return '<a href="' . asset('storage/' . $row->bukti) . '" target="_blank" class="text-blue-600 hover:underline text-xs"><i class="fas fa-file-invoice mr-1"></i> Lihat Bukti</a>';
                    }
                    return '<span class="text-slate-400 text-xs">-</span>';
                })
                ->rawColumns(['status_badge', 'bukti_view'])
                ->make(true);
        }
    }

    /**
     * Endpoint AJAX untuk update dinamis kartu statistik, grafik, rekapitulasi, dan matriks nasional ketika filter diubah.
     */
    public function chartData(Request $request)
    {
        $scope = $this->laporanService->resolveScope('pusat', Auth::user(), $request);

        return response()->json([
            'scope' => $scope,
            'stats' => $this->laporanService->getDashboardStats($scope, $request),
            'growth' => $this->laporanService->getGrowthChartData($scope, $request),
            'status' => $this->laporanService->getStatusChartData($scope, $request),
            'regional' => $this->laporanService->getRegionalDistributionChartData($scope, $request),
            'breakdown' => $this->laporanService->getBreakdownChartData($scope, $request),
            'rekapitulasi' => $this->laporanService->getRekapitulasiData($scope, $request),
            'matriks' => $this->laporanService->getMatrixData($scope, $request),
        ]);
    }

    /**
     * Export data ke Excel (.xlsx).
     */
    public function exportExcel(Request $request)
    {
        $scope = $this->laporanService->resolveScope('pusat', Auth::user(), $request);
        $type = $request->get('type', 'anggota');

        return $this->laporanService->exportExcel($scope, $request, $type);
    }

    /**
     * Export data ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $scope = $this->laporanService->resolveScope('pusat', Auth::user(), $request);
        $type = $request->get('type', 'anggota');

        return $this->laporanService->exportPdf($scope, $request, $type);
    }

    /**
     * Tampilan cetak laporan (Print Friendly).
     */
    public function printView(Request $request)
    {
        $scope = $this->laporanService->resolveScope('pusat', Auth::user(), $request);
        $type = $request->get('type', 'anggota');

        $data = [];
        $title = '';

        if ($type === 'anggota') {
            $data = $this->laporanService->getAnggotaQuery($scope, $request)->get();
            $title = 'Laporan Data Anggota Nasional';
        } elseif ($type === 'sertifikat') {
            $data = $this->laporanService->getSertifikatQuery($scope, $request)->get();
            $title = 'Laporan Sertifikat Anggota Nasional';
        } elseif ($type === 'pembayaran') {
            $data = $this->laporanService->getPembayaranQuery($scope, $request)->get();
            $title = 'Laporan Pembayaran dan Iuran Nasional';
        } else {
            $data = $this->laporanService->getRekapitulasiData($scope, $request);
            $matrix = $this->laporanService->getMatrixData($scope, $request);
            $title = 'Laporan Rekapitulasi & Matriks Organisasi Nasional';
        }

        $stats = $this->laporanService->getDashboardStats($scope, $request);
        $today = Carbon::now();

        return view('backend.laporan.print_view', [
            'scope' => $scope,
            'type' => $type,
            'title' => $title,
            'data' => $data,
            'matrix' => $matrix ?? null,
            'stats' => $stats,
            'today' => $today,
            'filters' => $request->all(),
        ]);
    }
}