<?php

namespace App\Services;

use App\Models\Anggota;
use App\Models\Pembayaran;
use App\Models\PembayaranKategori;
use App\Models\Sertifikat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spipu\Html2Pdf\Html2Pdf;

class LaporanService
{
    /**
     * SQL snippet for calculating the unified registration date.
     * Older records use created_on (UNIX timestamp string),
     * newer records use created_at (MySQL timestamp).
     */
    public function getUnifiedDateSql(string $alias = 'anggota'): string
    {
        return "CASE 
            WHEN {$alias}.created_at IS NOT NULL AND {$alias}.created_at != '0000-00-00 00:00:00' THEN {$alias}.created_at 
            WHEN {$alias}.created_on IS NOT NULL AND {$alias}.created_on != '' THEN FROM_UNIXTIME({$alias}.created_on) 
            ELSE NULL 
        END";
    }

    /**
     * Resolve and strictly secure the regional scope based on user role and level ('daerah' or 'cabang').
     */
    public function resolveScope(string $level, User $user, Request $request): array
    {
        $isNationalAdmin = $user->hasAnyRole(['Superadmin', 'Admin Pusat', 'Ketua Umum', 'Sekjen', 'Bendahara']) 
            || $user->can('laporan-pusat');

        $scope = [
            'level' => $level,
            'is_national_admin' => $isNationalAdmin,
            'provinsi_id' => null,
            'provinsi_name' => '',
            'kota_id' => null,
            'kota_name' => '',
            'is_locked_provinsi' => true,
            'is_locked_kota' => true,
            'title' => '',
            'subtitle' => '',
        ];

        if ($level === 'daerah') {
            // Level: Daerah (Provinsi)
            if ($isNationalAdmin) {
                // National admin can choose any province
                $scope['provinsi_id'] = $request->filled('provinsi') 
                    ? (string) $request->provinsi 
                    : ((string) ($user->provinsi ?: '32'));
                $scope['is_locked_provinsi'] = false;
                $scope['is_locked_kota'] = false;
            } elseif ($user->hasRole('Admin Daerah')) {
                $scope['provinsi_id'] = (string) $user->provinsi;
                $scope['is_locked_provinsi'] = true;
                $scope['is_locked_kota'] = false; // Daerah can filter across cities within its province
            } else {
                // Fallback for authorized users with permission
                $scope['provinsi_id'] = (string) ($user->provinsi ?: '32');
                $scope['is_locked_provinsi'] = true;
                $scope['is_locked_kota'] = false;
            }

            $province = Province::where('code', $scope['provinsi_id'])->first();
            $scope['provinsi_name'] = $province ? $province->name : 'Provinsi';
            $scope['title'] = 'Laporan Wilayah (Pengurus Daerah ' . $scope['provinsi_name'] . ')';
            $scope['subtitle'] = 'Pusat data pelaporan, sertifikasi, dan iuran anggota tingkat provinsi ' . $scope['provinsi_name'];

        } else {
            // Level: Cabang (Kabupaten/Kota)
            if ($isNationalAdmin) {
                $scope['is_locked_provinsi'] = false;
                $scope['is_locked_kota'] = false;

                if ($request->filled('kota')) {
                    $scope['kota_id'] = (string) $request->kota;
                    $city = City::where('code', $scope['kota_id'])->first();
                    $scope['provinsi_id'] = $city ? (string) $city->province_code : ($request->filled('provinsi') ? (string) $request->provinsi : '32');
                } elseif ($request->filled('provinsi')) {
                    $scope['provinsi_id'] = (string) $request->provinsi;
                    $firstCity = City::where('province_code', $scope['provinsi_id'])->orderBy('name')->first();
                    $scope['kota_id'] = $firstCity ? (string) $firstCity->code : '3216';
                } else {
                    $scope['kota_id'] = (string) ($user->kota ?: '3216');
                    $city = City::where('code', $scope['kota_id'])->first();
                    $scope['provinsi_id'] = $city ? (string) $city->province_code : '32';
                }
            } elseif ($user->hasRole('Admin Daerah')) {
                $scope['provinsi_id'] = (string) $user->provinsi;
                $scope['is_locked_provinsi'] = true;
                $scope['is_locked_kota'] = false;

                if ($request->filled('kota')) {
                    $isValidCity = City::where('code', $request->kota)->where('province_code', $user->provinsi)->exists();
                    $scope['kota_id'] = $isValidCity ? (string) $request->kota : (City::where('province_code', $user->provinsi)->orderBy('name')->value('code') ?: '3216');
                } else {
                    $scope['kota_id'] = (string) (City::where('province_code', $user->provinsi)->orderBy('name')->value('code') ?: '3216');
                }
            } elseif ($user->hasRole('Admin Cabang')) {
                $scope['kota_id'] = (string) $user->kota;
                $city = City::where('code', $scope['kota_id'])->first();
                $scope['provinsi_id'] = $city ? (string) $city->province_code : (string) $user->provinsi;
                $scope['is_locked_provinsi'] = true;
                $scope['is_locked_kota'] = true;
            } else {
                $scope['kota_id'] = (string) ($user->kota ?: '3216');
                $city = City::where('code', $scope['kota_id'])->first();
                $scope['provinsi_id'] = $city ? (string) $city->province_code : '32';
                $scope['is_locked_provinsi'] = true;
                $scope['is_locked_kota'] = true;
            }

            $city = City::where('code', $scope['kota_id'])->first();
            $scope['kota_name'] = $city ? $city->name : 'Kabupaten/Kota';
            $province = Province::where('code', $scope['provinsi_id'])->first();
            $scope['provinsi_name'] = $province ? $province->name : '';
            $scope['title'] = 'Laporan Cabang (Pengurus Cabang ' . $scope['kota_name'] . ')';
            $scope['subtitle'] = 'Pusat data pelaporan, sertifikasi, dan iuran anggota tingkat ' . $scope['kota_name'];
        }

        return $scope;
    }

    /**
     * Get available registration years for filtering dropdown.
     */
    public function getAvailableYears(): array
    {
        $dateSql = $this->getUnifiedDateSql('anggota');
        $years = DB::table('anggota')
            ->selectRaw("DISTINCT YEAR({$dateSql}) as thn")
            ->whereRaw("{$dateSql} IS NOT NULL")
            ->orderBy('thn', 'desc')
            ->pluck('thn')
            ->filter()
            ->values()
            ->toArray();

        if (empty($years)) {
            $years = [date('Y')];
        }

        return $years;
    }

    /**
     * Get sub-region options for filter dropdown:
     * For Daerah: list of cities in this province.
     * For Cabang: list of districts in this city.
     */
    public function getSubRegionOptions(array $scope): array
    {
        if ($scope['level'] === 'daerah') {
            return City::where('province_code', $scope['provinsi_id'])
                ->orderBy('name', 'asc')
                ->get(['code', 'name'])
                ->toArray();
        } else {
            return District::where('city_code', $scope['kota_id'])
                ->orderBy('name', 'asc')
                ->get(['code', 'name'])
                ->toArray();
        }
    }

    /**
     * Apply all security boundaries and user filters to an Eloquent or DB query builder on 'anggota'.
     */
    public function applyFilters($query, array $scope, Request $request, string $alias = 'anggota')
    {
        // 1. Mandatory Regional Bounds (Security Isolation)
        if ($scope['level'] === 'daerah') {
            $query->where("{$alias}.id_provinsi", $scope['provinsi_id']);

            // Optional city filter within this authorized province
            if ($request->filled('kota')) {
                // Ensure the requested city belongs to the province
                $isCityValid = City::where('code', $request->kota)
                    ->where('province_code', $scope['provinsi_id'])
                    ->exists();
                if ($isCityValid) {
                    $query->where("{$alias}.id_kota", $request->kota);
                }
            }
        } else {
            // Level: Cabang -> Locked strictly to the authorized city
            $query->where("{$alias}.id_kota", $scope['kota_id']);

            // Optional district filter within this authorized city
            if ($request->filled('kecamatan')) {
                $isDistrictValid = District::where('code', $request->kecamatan)
                    ->where('city_code', $scope['kota_id'])
                    ->exists();
                if ($isDistrictValid) {
                    $query->where("{$alias}.id_kecamatan", $request->kecamatan);
                }
            }
        }

        // 2. Year filter
        $dateSql = $this->getUnifiedDateSql($alias);
        if ($request->filled('tahun')) {
            $query->whereRaw("YEAR({$dateSql}) = ?", [(int) $request->tahun]);
        }

        // 3. Date Range filter
        if ($request->filled('start_date')) {
            try {
                $startDate = Carbon::parse($request->start_date)->startOfDay();
                $query->whereRaw("{$dateSql} >= ?", [$startDate->toDateTimeString()]);
            } catch (\Exception $e) {}
        }
        if ($request->filled('end_date')) {
            try {
                $endDate = Carbon::parse($request->end_date)->endOfDay();
                $query->whereRaw("{$dateSql} <= ?", [$endDate->toDateTimeString()]);
            } catch (\Exception $e) {}
        }

        // 4. Status Keanggotaan filter
        if ($request->filled('status')) {
            $query->where("{$alias}.status", $request->status);
        }

        // 5. Status Pembayaran filter
        if ($request->filled('status_pembayaran')) {
            $query->where("{$alias}.status_pembayaran", $request->status_pembayaran);
        }

        // 6. Status Sertifikat filter
        if ($request->filled('status_sertifikat')) {
            $today = Carbon::today()->toDateString();
            if ($request->status_sertifikat === 'aktif') {
                $query->whereExists(function ($sub) use ($alias, $today) {
                    $sub->select(DB::raw(1))
                        ->from('sertifikat')
                        ->whereColumn('sertifikat.id_anggota', "{$alias}.id")
                        ->where('sertifikat.sampai', '>=', $today);
                });
            } elseif ($request->status_sertifikat === 'expired') {
                $query->whereExists(function ($sub) use ($alias, $today) {
                    $sub->select(DB::raw(1))
                        ->from('sertifikat')
                        ->whereColumn('sertifikat.id_anggota', "{$alias}.id")
                        ->where('sertifikat.sampai', '<', $today);
                })->whereNotExists(function ($sub) use ($alias, $today) {
                    $sub->select(DB::raw(1))
                        ->from('sertifikat')
                        ->whereColumn('sertifikat.id_anggota', "{$alias}.id")
                        ->where('sertifikat.sampai', '>=', $today);
                });
            } elseif ($request->status_sertifikat === 'belum') {
                $query->whereNotExists(function ($sub) use ($alias) {
                    $sub->select(DB::raw(1))
                        ->from('sertifikat')
                        ->whereColumn('sertifikat.id_anggota', "{$alias}.id");
                });
            }
        }

        // 7. General search term (safe for string or DataTables search array)
        $searchKeyword = null;
        if ($request->filled('search')) {
            $searchParam = $request->input('search');
            if (is_array($searchParam)) {
                $searchKeyword = isset($searchParam['value']) && is_string($searchParam['value']) ? trim($searchParam['value']) : null;
            } elseif (is_string($searchParam)) {
                $searchKeyword = trim($searchParam);
            }
        }

        if (!empty($searchKeyword)) {
            $search = '%' . $searchKeyword . '%';
            $query->where(function ($q) use ($alias, $search) {
                $q->where("{$alias}.nama_klinik", 'like', $search)
                    ->orWhere("{$alias}.no_anggota", 'like', $search)
                    ->orWhere("{$alias}.nama_pemilik_klinik", 'like', $search)
                    ->orWhere("{$alias}.nama_kontak", 'like', $search)
                    ->orWhere("{$alias}.email", 'like', $search)
                    ->orWhere("{$alias}.tlf", 'like', $search);
            });
        }

        return $query;
    }

    /**
     * Compute Executive Dashboard Statistics based on current scope and active filters.
     */
    public function getDashboardStats(array $scope, Request $request): array
    {
        $dateSql = $this->getUnifiedDateSql('anggota');
        $today = Carbon::today()->toDateString();

        // 1. Core Anggota stats
        $anggotaQuery = DB::table('anggota');
        $this->applyFilters($anggotaQuery, $scope, $request, 'anggota');

        $stats = $anggotaQuery->selectRaw("
            COUNT(*) as total_anggota,
            SUM(CASE WHEN anggota.status = 'approved' THEN 1 ELSE 0 END) as anggota_aktif,
            SUM(CASE WHEN anggota.status != 'approved' THEN 1 ELSE 0 END) as anggota_inaktif,
            SUM(CASE WHEN anggota.status_pembayaran = '1' THEN 1 ELSE 0 END) as anggota_bayar_lunas,
            SUM(CASE WHEN anggota.status_pembayaran != '1' OR anggota.status_pembayaran IS NULL THEN 1 ELSE 0 END) as anggota_bayar_pending,
            SUM(CASE WHEN {$dateSql} >= ? THEN 1 ELSE 0 END) as anggota_baru_30_hari
        ", [Carbon::now()->subDays(30)->toDateTimeString()])->first();

        // 2. Certificate stats (matching filtered anggota)
        $certActiveQuery = DB::table('sertifikat')
            ->join('anggota', 'anggota.id', '=', 'sertifikat.id_anggota');
        $this->applyFilters($certActiveQuery, $scope, $request, 'anggota');
        $totalSertifikatAktif = $certActiveQuery->where('sertifikat.sampai', '>=', $today)->count();

        $certExpiredQuery = DB::table('sertifikat')
            ->join('anggota', 'anggota.id', '=', 'sertifikat.id_anggota');
        $this->applyFilters($certExpiredQuery, $scope, $request, 'anggota');
        $totalSertifikatExpired = $certExpiredQuery->where('sertifikat.sampai', '<', $today)->count();

        $totalAnggota = (int) ($stats->total_anggota ?? 0);
        $totalBersertifikat = DB::table('sertifikat')
            ->join('anggota', 'anggota.id', '=', 'sertifikat.id_anggota');
        $this->applyFilters($totalBersertifikat, $scope, $request, 'anggota');
        $totalMemilikiSertifikat = $totalBersertifikat->distinct('sertifikat.id_anggota')->count('sertifikat.id_anggota');
        $totalBelumBersertifikat = max(0, $totalAnggota - $totalMemilikiSertifikat);

        // 3. Payment stats from tabel pembayaran
        $pembayaranQuery = DB::table('pembayaran')
            ->join('anggota', 'anggota.id', '=', 'pembayaran.id_anggota');
        $this->applyFilters($pembayaranQuery, $scope, $request, 'anggota');

        $payStats = $pembayaranQuery->selectRaw("
            COUNT(*) as total_transaksi,
            SUM(CASE WHEN pembayaran.status = '1' THEN 1 ELSE 0 END) as transaksi_lunas,
            SUM(CASE WHEN pembayaran.status = '0' OR pembayaran.status = '' THEN 1 ELSE 0 END) as transaksi_pending,
            SUM(CASE WHEN pembayaran.status = '1' THEN pembayaran.total ELSE 0 END) as nominal_lunas,
            SUM(CASE WHEN pembayaran.status = '0' OR pembayaran.status = '' THEN pembayaran.total ELSE 0 END) as nominal_pending
        ")->first();

        return [
            'total_anggota' => $totalAnggota,
            'anggota_aktif' => (int) ($stats->anggota_aktif ?? 0),
            'anggota_inaktif' => (int) ($stats->anggota_inaktif ?? 0),
            'anggota_baru_30_hari' => (int) ($stats->anggota_baru_30_hari ?? 0),
            'anggota_bayar_lunas' => (int) ($stats->anggota_bayar_lunas ?? 0),
            'anggota_bayar_pending' => (int) ($stats->anggota_bayar_pending ?? 0),
            'sertifikat_aktif' => (int) $totalSertifikatAktif,
            'sertifikat_expired' => (int) $totalSertifikatExpired,
            'sertifikat_belum' => (int) $totalBelumBersertifikat,
            'total_transaksi_bayar' => (int) ($payStats->total_transaksi ?? 0),
            'transaksi_bayar_lunas' => (int) ($payStats->transaksi_lunas ?? 0),
            'transaksi_bayar_pending' => (int) ($payStats->transaksi_pending ?? 0),
            'nominal_bayar_lunas' => (float) ($payStats->nominal_lunas ?? 0),
            'nominal_bayar_pending' => (float) ($payStats->nominal_pending ?? 0),
        ];
    }

    /**
     * Get chart data for Growth (Per Tahun / Per Bulan).
     */
    public function getGrowthChartData(array $scope, Request $request): array
    {
        $dateSql = $this->getUnifiedDateSql('anggota');
        $query = DB::table('anggota');
        $this->applyFilters($query, $scope, $request, 'anggota');

        if ($request->filled('tahun')) {
            // Group by Month for selected year
            $data = $query->selectRaw("
                MONTH({$dateSql}) as periode,
                COUNT(*) as total,
                SUM(CASE WHEN anggota.status = 'approved' THEN 1 ELSE 0 END) as aktif
            ")
            ->whereRaw("{$dateSql} IS NOT NULL")
            ->groupBy('periode')
            ->orderBy('periode')
            ->get();

            $monthNames = [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'];
            $labels = [];
            $totals = [];
            $aktifs = [];

            for ($m = 1; $m <= 12; $m++) {
                $row = $data->firstWhere('periode', $m);
                $labels[] = $monthNames[$m];
                $totals[] = $row ? (int) $row->total : 0;
                $aktifs[] = $row ? (int) $row->aktif : 0;
            }
        } else {
            // Group by Year
            $data = $query->selectRaw("
                YEAR({$dateSql}) as periode,
                COUNT(*) as total,
                SUM(CASE WHEN anggota.status = 'approved' THEN 1 ELSE 0 END) as aktif
            ")
            ->whereRaw("{$dateSql} IS NOT NULL")
            ->groupBy('periode')
            ->orderBy('periode')
            ->get();

            $labels = $data->pluck('periode')->map(fn($y) => (string) $y)->toArray();
            $totals = $data->pluck('total')->map(fn($v) => (int) $v)->toArray();
            $aktifs = $data->pluck('aktif')->map(fn($v) => (int) $v)->toArray();
        }

        return [
            'labels' => $labels,
            'totals' => $totals,
            'aktifs' => $aktifs,
        ];
    }

    /**
     * Get status distribution chart data.
     */
    public function getStatusChartData(array $scope, Request $request): array
    {
        $query = DB::table('anggota');
        $this->applyFilters($query, $scope, $request, 'anggota');

        $rows = $query->select('anggota.status', DB::raw('count(*) as count'))
            ->groupBy('anggota.status')
            ->orderByDesc('count')
            ->get();

        $labels = [];
        $data = [];
        $statusLabels = [
            'approved' => 'Disetujui (Aktif)',
            'waiting' => 'Menunggu Verifikasi',
            'proses' => 'Sedang Diproses',
            'create_dokter' => 'Pengisian Dokter',
            'Terverifikasi Cabang' => 'Terverifikasi Cabang',
            'Diproses Cabang' => 'Diproses Cabang',
            'Perlu Perbaikan' => 'Perlu Perbaikan',
            'ditolak' => 'Ditolak',
        ];

        foreach ($rows as $row) {
            $key = $row->status ?: 'Lainnya';
            $labels[] = $statusLabels[$key] ?? ucfirst(str_replace('_', ' ', $key));
            $data[] = (int) $row->count;
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * Get regional distribution chart data:
     * For Daerah: per Kab/Kota.
     * For Cabang: per Kecamatan.
     */
    public function getRegionalDistributionChartData(array $scope, Request $request): array
    {
        if ($scope['level'] === 'daerah') {
            $query = DB::table('anggota')
                ->join('indonesia_cities', 'indonesia_cities.code', '=', 'anggota.id_kota');
            $this->applyFilters($query, $scope, $request, 'anggota');

            $rows = $query->select('indonesia_cities.name as wilayah', DB::raw('count(*) as total'))
                ->groupBy('indonesia_cities.name')
                ->orderByDesc('total')
                ->limit(15)
                ->get();
        } else {
            $query = DB::table('anggota')
                ->join('indonesia_districts', 'indonesia_districts.code', '=', 'anggota.id_kecamatan');
            $this->applyFilters($query, $scope, $request, 'anggota');

            $rows = $query->select('indonesia_districts.name as wilayah', DB::raw('count(*) as total'))
                ->groupBy('indonesia_districts.name')
                ->orderByDesc('total')
                ->limit(15)
                ->get();
        }

        return [
            'labels' => $rows->pluck('wilayah')->toArray(),
            'data' => $rows->pluck('total')->map(fn($v) => (int) $v)->toArray(),
        ];
    }

    /**
     * Get certificate & payment status doughnut chart data.
     */
    public function getBreakdownChartData(array $scope, Request $request): array
    {
        $stats = $this->getDashboardStats($scope, $request);

        return [
            'certificate' => [
                'labels' => ['Sertifikat Aktif', 'Kedaluwarsa', 'Belum Memiliki Sertifikat'],
                'data' => [
                    $stats['sertifikat_aktif'],
                    $stats['sertifikat_expired'],
                    $stats['sertifikat_belum'],
                ],
            ],
            'payment' => [
                'labels' => ['Lunas (Berhasil)', 'Menunggu / Tertunda'],
                'data' => [
                    $stats['transaksi_bayar_lunas'],
                    $stats['transaksi_bayar_pending'],
                ],
            ],
        ];
    }

    /**
     * Build base query for Anggota report.
     */
    public function getAnggotaQuery(array $scope, Request $request)
    {
        $dateSql = $this->getUnifiedDateSql('anggota');

        $query = Anggota::select(
            'anggota.*',
            'indonesia_cities.name as kota_name',
            'indonesia_provinces.name as provinsi_name',
            'indonesia_districts.name as kecamatan_name',
            'indonesia_villages.name as kelurahan_name',
            DB::raw("{$dateSql} as tgl_daftar_raw"),
            DB::raw('GROUP_CONCAT(DISTINCT fasilitas_klinik.nama SEPARATOR ", ") as kriteria')
        )
        ->leftJoin('fasilitas_klinik', DB::raw('FIND_IN_SET(fasilitas_klinik.id, anggota.fasilitas_klinik)'), '>', DB::raw("'0'"))
        ->leftJoin('indonesia_cities', 'indonesia_cities.code', '=', 'anggota.id_kota')
        ->leftJoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'anggota.id_provinsi')
        ->leftJoin('indonesia_districts', 'indonesia_districts.code', '=', 'anggota.id_kecamatan')
        ->leftJoin('indonesia_villages', 'indonesia_villages.code', '=', 'anggota.id_kelurahan')
        ->groupBy('anggota.id');

        $this->applyFilters($query, $scope, $request, 'anggota');

        return $query;
    }

    /**
     * Build base query for Sertifikat report.
     */
    public function getSertifikatQuery(array $scope, Request $request)
    {
        $today = Carbon::today()->toDateString();

        $query = DB::table('sertifikat')
            ->select(
                'sertifikat.*',
                'anggota.no_anggota',
                'anggota.nama_klinik',
                'anggota.nama_pemilik_klinik',
                'anggota.tlf',
                'anggota.status as status_anggota',
                'indonesia_cities.name as kota_name',
                'indonesia_provinces.name as provinsi_name',
                'indonesia_districts.name as kecamatan_name',
                DB::raw("CASE WHEN sertifikat.sampai >= '{$today}' THEN 'Aktif' ELSE 'Kedaluwarsa' END as status_masa_berlaku")
            )
            ->join('anggota', 'anggota.id', '=', 'sertifikat.id_anggota')
            ->leftJoin('indonesia_cities', 'indonesia_cities.code', '=', 'anggota.id_kota')
            ->leftJoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'anggota.id_provinsi')
            ->leftJoin('indonesia_districts', 'indonesia_districts.code', '=', 'anggota.id_kecamatan');

        $this->applyFilters($query, $scope, $request, 'anggota');

        return $query;
    }

    /**
     * Build base query for Pembayaran report.
     */
    public function getPembayaranQuery(array $scope, Request $request)
    {
        $query = DB::table('pembayaran')
            ->select(
                'pembayaran.*',
                'pembayaran_kategori.nama as nama_kategori',
                'anggota.no_anggota',
                'anggota.nama_klinik',
                'anggota.nama_pemilik_klinik',
                'anggota.tlf',
                'indonesia_cities.name as kota_name',
                'indonesia_provinces.name as provinsi_name',
                'indonesia_districts.name as kecamatan_name'
            )
            ->join('anggota', 'anggota.id', '=', 'pembayaran.id_anggota')
            ->leftJoin('pembayaran_kategori', 'pembayaran_kategori.id', '=', 'pembayaran.id_kategori')
            ->leftJoin('indonesia_cities', 'indonesia_cities.code', '=', 'anggota.id_kota')
            ->leftJoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'anggota.id_provinsi')
            ->leftJoin('indonesia_districts', 'indonesia_districts.code', '=', 'anggota.id_kecamatan');

        $this->applyFilters($query, $scope, $request, 'anggota');

        // Optional filter specific to payment category
        if ($request->filled('id_kategori')) {
            $query->where('pembayaran.id_kategori', $request->id_kategori);
        }

        // Optional filter specific to payment status in pembayaran table
        if ($request->filled('transaksi_status')) {
            $query->where('pembayaran.status', $request->transaksi_status);
        }

        return $query;
    }

    /**
     * Compile structured Rekapitulasi Organisasi data.
     */
    public function getRekapitulasiData(array $scope, Request $request): array
    {
        $today = Carbon::today()->toDateString();
        $dateSql = $this->getUnifiedDateSql('anggota');

        // 1. Regional Breakdown Table
        if ($scope['level'] === 'daerah') {
            $regionalQuery = DB::table('indonesia_cities')
                ->where('province_code', $scope['provinsi_id'])
                ->leftJoin('anggota', function ($join) use ($scope, $request) {
                    $join->on('anggota.id_kota', '=', 'indonesia_cities.code');
                });

            $regionalData = $regionalQuery->select(
                'indonesia_cities.code',
                'indonesia_cities.name',
                DB::raw('COUNT(anggota.id) as total_anggota'),
                DB::raw("SUM(CASE WHEN anggota.status = 'approved' THEN 1 ELSE 0 END) as aktif"),
                DB::raw("SUM(CASE WHEN anggota.status != 'approved' AND anggota.id IS NOT NULL THEN 1 ELSE 0 END) as inaktif"),
                DB::raw("SUM(CASE WHEN anggota.status_pembayaran = '1' THEN 1 ELSE 0 END) as lunas"),
                DB::raw("SUM(CASE WHEN (anggota.status_pembayaran != '1' OR anggota.status_pembayaran IS NULL) AND anggota.id IS NOT NULL THEN 1 ELSE 0 END) as belum_lunas")
            )
            ->groupBy('indonesia_cities.code', 'indonesia_cities.name')
            ->orderBy('indonesia_cities.name')
            ->get();
        } else {
            $regionalQuery = DB::table('indonesia_districts')
                ->where('city_code', $scope['kota_id'])
                ->leftJoin('anggota', function ($join) use ($scope, $request) {
                    $join->on('anggota.id_kecamatan', '=', 'indonesia_districts.code');
                });

            $regionalData = $regionalQuery->select(
                'indonesia_districts.code',
                'indonesia_districts.name',
                DB::raw('COUNT(anggota.id) as total_anggota'),
                DB::raw("SUM(CASE WHEN anggota.status = 'approved' THEN 1 ELSE 0 END) as aktif"),
                DB::raw("SUM(CASE WHEN anggota.status != 'approved' AND anggota.id IS NOT NULL THEN 1 ELSE 0 END) as inaktif"),
                DB::raw("SUM(CASE WHEN anggota.status_pembayaran = '1' THEN 1 ELSE 0 END) as lunas"),
                DB::raw("SUM(CASE WHEN (anggota.status_pembayaran != '1' OR anggota.status_pembayaran IS NULL) AND anggota.id IS NOT NULL THEN 1 ELSE 0 END) as belum_lunas")
            )
            ->groupBy('indonesia_districts.code', 'indonesia_districts.name')
            ->orderBy('indonesia_districts.name')
            ->get();
        }

        // 2. Status Keanggotaan Table
        $statusQuery = DB::table('anggota');
        $this->applyFilters($statusQuery, $scope, $request, 'anggota');
        $statusBreakdown = $statusQuery->select('anggota.status', DB::raw('count(*) as total'))
            ->groupBy('anggota.status')
            ->orderByDesc('total')
            ->get();

        // 3. Pendaftaran per Tahun Table
        $tahunQuery = DB::table('anggota');
        $this->applyFilters($tahunQuery, $scope, $request, 'anggota');
        $tahunBreakdown = $tahunQuery->selectRaw("
            YEAR({$dateSql}) as tahun,
            COUNT(*) as total,
            SUM(CASE WHEN anggota.status = 'approved' THEN 1 ELSE 0 END) as disetujui,
            SUM(CASE WHEN anggota.status_pembayaran = '1' THEN 1 ELSE 0 END) as lunas
        ")
        ->whereRaw("{$dateSql} IS NOT NULL")
        ->groupBy('tahun')
        ->orderByDesc('tahun')
        ->get();

        // 4. Pembayaran per Kategori Table
        $payKatQuery = DB::table('pembayaran')
            ->join('anggota', 'anggota.id', '=', 'pembayaran.id_anggota')
            ->leftJoin('pembayaran_kategori', 'pembayaran_kategori.id', '=', 'pembayaran.id_kategori');
        $this->applyFilters($payKatQuery, $scope, $request, 'anggota');
        $pembayaranKategori = $payKatQuery->select(
            'pembayaran_kategori.nama',
            DB::raw('COUNT(*) as total_transaksi'),
            DB::raw("SUM(CASE WHEN pembayaran.status = '1' THEN 1 ELSE 0 END) as transaksi_lunas"),
            DB::raw("SUM(CASE WHEN pembayaran.status = '0' OR pembayaran.status = '' THEN 1 ELSE 0 END) as transaksi_pending"),
            DB::raw("SUM(CASE WHEN pembayaran.status = '1' THEN pembayaran.total ELSE 0 END) as nominal_lunas"),
            DB::raw("SUM(CASE WHEN pembayaran.status = '0' OR pembayaran.status = '' THEN pembayaran.total ELSE 0 END) as nominal_pending")
        )
        ->groupBy('pembayaran_kategori.nama')
        ->get();

        // 5. YoY Comparison (This Year vs Last Year)
        $currentYear = (int) date('Y');
        $prevYear = $currentYear - 1;

        $yoyQuery = DB::table('anggota');
        $this->applyFilters($yoyQuery, $scope, $request, 'anggota');
        $yoyData = $yoyQuery->selectRaw("
            SUM(CASE WHEN YEAR({$dateSql}) = {$currentYear} THEN 1 ELSE 0 END) as anggota_tahun_ini,
            SUM(CASE WHEN YEAR({$dateSql}) = {$prevYear} THEN 1 ELSE 0 END) as anggota_tahun_lalu,
            SUM(CASE WHEN YEAR({$dateSql}) = {$currentYear} AND status = 'approved' THEN 1 ELSE 0 END) as aktif_tahun_ini,
            SUM(CASE WHEN YEAR({$dateSql}) = {$prevYear} AND status = 'approved' THEN 1 ELSE 0 END) as aktif_tahun_lalu
        ")->first();

        return [
            'regional_data' => $regionalData,
            'status_breakdown' => $statusBreakdown,
            'tahun_breakdown' => $tahunBreakdown,
            'pembayaran_kategori' => $pembayaranKategori,
            'yoy' => [
                'current_year' => $currentYear,
                'prev_year' => $prevYear,
                'anggota_current' => (int) ($yoyData->anggota_tahun_ini ?? 0),
                'anggota_prev' => (int) ($yoyData->anggota_tahun_lalu ?? 0),
                'aktif_current' => (int) ($yoyData->aktif_tahun_ini ?? 0),
                'aktif_prev' => (int) ($yoyData->aktif_tahun_lalu ?? 0),
            ],
        ];
    }

    /**
     * Compile Matriks Laporan (Cross-Tabulation Pivots).
     */
    public function getMatrixData(array $scope, Request $request): array
    {
        $dateSql = $this->getUnifiedDateSql('anggota');
        $today = Carbon::today()->toDateString();

        // Matrix 1: Wilayah x Status Anggota
        if ($scope['level'] === 'daerah') {
            $m1Query = DB::table('indonesia_cities')
                ->where('province_code', $scope['provinsi_id'])
                ->leftJoin('anggota', function ($join) {
                    $join->on('anggota.id_kota', '=', 'indonesia_cities.code');
                });
            $m1Rows = $m1Query->select(
                'indonesia_cities.name as baris',
                DB::raw("SUM(CASE WHEN anggota.status = 'approved' THEN 1 ELSE 0 END) as col_approved"),
                DB::raw("SUM(CASE WHEN anggota.status = 'waiting' THEN 1 ELSE 0 END) as col_waiting"),
                DB::raw("SUM(CASE WHEN anggota.status = 'proses' THEN 1 ELSE 0 END) as col_proses"),
                DB::raw("SUM(CASE WHEN anggota.status NOT IN ('approved', 'waiting', 'proses') AND anggota.id IS NOT NULL THEN 1 ELSE 0 END) as col_lainnya"),
                DB::raw("COUNT(anggota.id) as col_total")
            )
            ->groupBy('indonesia_cities.name')
            ->orderBy('indonesia_cities.name')
            ->get();
        } else {
            $m1Query = DB::table('indonesia_districts')
                ->where('city_code', $scope['kota_id'])
                ->leftJoin('anggota', function ($join) {
                    $join->on('anggota.id_kecamatan', '=', 'indonesia_districts.code');
                });
            $m1Rows = $m1Query->select(
                'indonesia_districts.name as baris',
                DB::raw("SUM(CASE WHEN anggota.status = 'approved' THEN 1 ELSE 0 END) as col_approved"),
                DB::raw("SUM(CASE WHEN anggota.status = 'waiting' THEN 1 ELSE 0 END) as col_waiting"),
                DB::raw("SUM(CASE WHEN anggota.status = 'proses' THEN 1 ELSE 0 END) as col_proses"),
                DB::raw("SUM(CASE WHEN anggota.status NOT IN ('approved', 'waiting', 'proses') AND anggota.id IS NOT NULL THEN 1 ELSE 0 END) as col_lainnya"),
                DB::raw("COUNT(anggota.id) as col_total")
            )
            ->groupBy('indonesia_districts.name')
            ->orderBy('indonesia_districts.name')
            ->get();
        }

        // Matrix 2: Tahun Pendaftaran x Status Anggota
        $m2Query = DB::table('anggota');
        $this->applyFilters($m2Query, $scope, $request, 'anggota');
        $m2Rows = $m2Query->selectRaw("
            YEAR({$dateSql}) as baris,
            SUM(CASE WHEN anggota.status = 'approved' THEN 1 ELSE 0 END) as col_approved,
            SUM(CASE WHEN anggota.status = 'waiting' THEN 1 ELSE 0 END) as col_waiting,
            SUM(CASE WHEN anggota.status = 'proses' THEN 1 ELSE 0 END) as col_proses,
            SUM(CASE WHEN anggota.status NOT IN ('approved', 'waiting', 'proses') THEN 1 ELSE 0 END) as col_lainnya,
            COUNT(*) as col_total
        ")
        ->whereRaw("{$dateSql} IS NOT NULL")
        ->groupBy('baris')
        ->orderByDesc('baris')
        ->get();

        // Matrix 3: Tahun Pendaftaran x Status Sertifikat
        $m3Query = DB::table('anggota');
        $this->applyFilters($m3Query, $scope, $request, 'anggota');
        $m3Rows = $m3Query->selectRaw("
            YEAR({$dateSql}) as baris,
            SUM(CASE WHEN EXISTS (SELECT 1 FROM sertifikat s WHERE s.id_anggota = anggota.id AND s.sampai >= '{$today}') THEN 1 ELSE 0 END) as col_aktif,
            SUM(CASE WHEN EXISTS (SELECT 1 FROM sertifikat s WHERE s.id_anggota = anggota.id AND s.sampai < '{$today}') AND NOT EXISTS (SELECT 1 FROM sertifikat s2 WHERE s2.id_anggota = anggota.id AND s2.sampai >= '{$today}') THEN 1 ELSE 0 END) as col_expired,
            SUM(CASE WHEN NOT EXISTS (SELECT 1 FROM sertifikat s WHERE s.id_anggota = anggota.id) THEN 1 ELSE 0 END) as col_belum,
            COUNT(*) as col_total
        ")
        ->whereRaw("{$dateSql} IS NOT NULL")
        ->groupBy('baris')
        ->orderByDesc('baris')
        ->get();

        // Matrix 4: Kategori Pembayaran x Status Pembayaran
        $m4Query = DB::table('pembayaran')
            ->join('anggota', 'anggota.id', '=', 'pembayaran.id_anggota')
            ->leftJoin('pembayaran_kategori', 'pembayaran_kategori.id', '=', 'pembayaran.id_kategori');
        $this->applyFilters($m4Query, $scope, $request, 'anggota');
        $m4Rows = $m4Query->select(
            DB::raw("COALESCE(pembayaran_kategori.nama, 'Lainnya') as baris"),
            DB::raw("SUM(CASE WHEN pembayaran.status = '1' THEN 1 ELSE 0 END) as col_lunas_trx"),
            DB::raw("SUM(CASE WHEN pembayaran.status = '0' OR pembayaran.status = '' THEN 1 ELSE 0 END) as col_pending_trx"),
            DB::raw("COUNT(*) as col_total_trx"),
            DB::raw("SUM(CASE WHEN pembayaran.status = '1' THEN pembayaran.total ELSE 0 END) as col_lunas_nom"),
            DB::raw("SUM(CASE WHEN pembayaran.status = '0' OR pembayaran.status = '' THEN pembayaran.total ELSE 0 END) as col_pending_nom")
        )
        ->groupBy('baris')
        ->get();

        return [
            'matrix_wilayah_status' => $m1Rows,
            'matrix_tahun_status' => $m2Rows,
            'matrix_tahun_sertifikat' => $m3Rows,
            'matrix_pembayaran' => $m4Rows,
        ];
    }

    /**
     * Export data to Excel (.xlsx) using SimpleExcelWriter.
     */
    public function exportExcel(array $scope, Request $request, string $type = 'anggota')
    {
        $timestamp = date('Y-m-d_H-i-s');
        $fileName = "laporan_{$scope['level']}_{$type}_{$timestamp}.xlsx";
        $filePath = storage_path('app/temp/' . $fileName);

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $writer = SimpleExcelWriter::create($filePath);

        if ($type === 'anggota') {
            $writer->addHeader([
                'No',
                'No. Anggota',
                'Nama Klinik',
                'Jenis Klinik',
                'Nama Pemilik',
                'Nama Kontak',
                'Email',
                'Telepon',
                'Alamat Lengkap',
                'Provinsi',
                'Kab/Kota',
                'Kecamatan',
                'Kelurahan',
                'Status Verifikasi',
                'Status Pembayaran',
                'No. Ijin SIO',
                'Tgl Akhir Ijin',
                'Tgl Pendaftaran'
            ]);

            $query = $this->getAnggotaQuery($scope, $request);
            $no = 1;
            foreach ($query->cursor() as $item) {
                $tglDaftar = $item->tgl_daftar_raw ? Carbon::parse($item->tgl_daftar_raw)->format('d-m-Y') : '-';
                $tglAkhirIjin = $item->tgl_akhir_ijin ? Carbon::parse($item->tgl_akhir_ijin)->format('d-m-Y') : '-';
                $writer->addRow([
                    $no++,
                    $item->no_anggota ?: '-',
                    $item->nama_klinik,
                    $item->jenis_klinik ?: '-',
                    $item->nama_pemilik_klinik ?: '-',
                    $item->nama_kontak ?: '-',
                    $item->email ?: '-',
                    $item->tlf ?: $item->tlf_klinik ?: '-',
                    $item->alamat_klinik ?: '-',
                    $item->provinsi_name ?: '-',
                    $item->kota_name ?: '-',
                    $item->kecamatan_name ?: '-',
                    $item->kelurahan_name ?: '-',
                    ucfirst(str_replace('_', ' ', $item->status)),
                    $item->status_pembayaran == '1' ? 'Lunas' : 'Belum Lunas',
                    $item->no_ijin ?: '-',
                    $tglAkhirIjin,
                    $tglDaftar
                ]);
            }

        } elseif ($type === 'sertifikat') {
            $writer->addHeader([
                'No',
                'No. Anggota',
                'Nama Klinik',
                'Kab/Kota',
                'Nomor Sertifikat (Kode)',
                'Jenis Sertifikat',
                'Tanggal Terbit',
                'Masa Berlaku Sampai',
                'Status Masa Berlaku',
                'Status Pembayaran'
            ]);

            $query = $this->getSertifikatQuery($scope, $request);
            $no = 1;
            foreach ($query->cursor() as $item) {
                $writer->addRow([
                    $no++,
                    $item->no_anggota ?: '-',
                    $item->nama_klinik,
                    $item->kota_name ?: '-',
                    $item->kode ?: '-',
                    $item->sertifikat ? ucwords(str_replace('_', ' ', $item->sertifikat)) : 'Sertifikat Anggota',
                    $item->dari ? Carbon::parse($item->dari)->format('d-m-Y') : '-',
                    $item->sampai ? Carbon::parse($item->sampai)->format('d-m-Y') : '-',
                    $item->status_masa_berlaku,
                    $item->status_pembayaran == '1' ? 'Lunas' : 'Menunggu'
                ]);
            }

        } elseif ($type === 'pembayaran') {
            $writer->addHeader([
                'No',
                'No. Anggota',
                'Nama Klinik',
                'Kab/Kota',
                'Kategori Pembayaran',
                'Tanggal Bayar',
                'Periode Dari',
                'Periode Sampai',
                'Nominal (Rp)',
                'Status Transaksi',
                'Keterangan'
            ]);

            $query = $this->getPembayaranQuery($scope, $request);
            $no = 1;
            foreach ($query->cursor() as $item) {
                $writer->addRow([
                    $no++,
                    $item->no_anggota ?: '-',
                    $item->nama_klinik,
                    $item->kota_name ?: '-',
                    $item->nama_kategori ?: 'Pembayaran Iuran',
                    $item->tanggal_bayar && $item->tanggal_bayar != '0000-00-00' ? Carbon::parse($item->tanggal_bayar)->format('d-m-Y') : '-',
                    $item->dari && $item->dari != '0000-00-00' ? Carbon::parse($item->dari)->format('d-m-Y') : '-',
                    $item->sampai && $item->sampai != '0000-00-00' ? Carbon::parse($item->sampai)->format('d-m-Y') : '-',
                    (float) $item->total,
                    $item->status == '1' ? 'Berhasil / Lunas' : 'Menunggu / Tertunda',
                    $item->keterangan ?: '-'
                ]);
            }

        } elseif ($type === 'rekapitulasi') {
            $rekap = $this->getRekapitulasiData($scope, $request);
            $wilayahLabel = $scope['level'] === 'daerah' ? 'Kabupaten/Kota' : 'Kecamatan';

            $writer->addHeader(['REKAPITULASI WILAYAH: ' . strtoupper($scope['title'])]);
            $writer->addRow([]);
            $writer->addRow([$wilayahLabel, 'Total Anggota', 'Anggota Aktif', 'Belum Aktif', 'Status Bayar Lunas', 'Status Bayar Tertunda']);
            foreach ($rekap['regional_data'] as $r) {
                $writer->addRow([
                    $r->name,
                    (int) $r->total_anggota,
                    (int) $r->aktif,
                    (int) $r->inaktif,
                    (int) $r->lunas,
                    (int) $r->belum_lunas
                ]);
            }

            $writer->addRow([]);
            $writer->addRow(['REKAPITULASI PENDAFTARAN PER TAHUN']);
            $writer->addRow(['Tahun', 'Total Pendaftar', 'Disetujui Aktif', 'Lunas']);
            foreach ($rekap['tahun_breakdown'] as $t) {
                $writer->addRow([
                    $t->tahun,
                    (int) $t->total,
                    (int) $t->disetujui,
                    (int) $t->lunas
                ]);
            }

            $writer->addRow([]);
            $writer->addRow(['KOMPARASI ANTARPERIODE (YEAR-OVER-YEAR)']);
            $writer->addRow(['Periode', 'Total Pendaftar', 'Disetujui Aktif']);
            $writer->addRow(['Tahun Ini (' . $rekap['yoy']['current_year'] . ')', $rekap['yoy']['anggota_current'], $rekap['yoy']['aktif_current']]);
            $writer->addRow(['Tahun Lalu (' . $rekap['yoy']['prev_year'] . ')', $rekap['yoy']['anggota_prev'], $rekap['yoy']['aktif_prev']]);

        } elseif ($type === 'matriks') {
            $matriks = $this->getMatrixData($scope, $request);
            $wilayahLabel = $scope['level'] === 'daerah' ? 'Kabupaten/Kota' : 'Kecamatan';

            $writer->addHeader(['MATRIKS 1: ' . strtoupper($wilayahLabel) . ' X STATUS ANGGOTA']);
            $writer->addRow([]);
            $writer->addRow([$wilayahLabel, 'Disetujui (Aktif)', 'Menunggu (Waiting)', 'Sedang Proses', 'Lainnya / Revisi', 'Total Baris']);
            foreach ($matriks['matrix_wilayah_status'] as $m1) {
                $writer->addRow([
                    $m1->baris,
                    (int) $m1->col_approved,
                    (int) $m1->col_waiting,
                    (int) $m1->col_proses,
                    (int) $m1->col_lainnya,
                    (int) $m1->col_total
                ]);
            }

            $writer->addRow([]);
            $writer->addRow(['MATRIKS 2: TAHUN PENDAFTARAN X STATUS ANGGOTA']);
            $writer->addRow(['Tahun', 'Disetujui (Aktif)', 'Menunggu (Waiting)', 'Sedang Proses', 'Lainnya / Revisi', 'Total Baris']);
            foreach ($matriks['matrix_tahun_status'] as $m2) {
                $writer->addRow([
                    $m2->baris,
                    (int) $m2->col_approved,
                    (int) $m2->col_waiting,
                    (int) $m2->col_proses,
                    (int) $m2->col_lainnya,
                    (int) $m2->col_total
                ]);
            }

            $writer->addRow([]);
            $writer->addRow(['MATRIKS 3: TAHUN PENDAFTARAN X STATUS SERTIFIKAT']);
            $writer->addRow(['Tahun', 'Sertifikat Aktif', 'Kedaluwarsa', 'Belum Memiliki', 'Total Baris']);
            foreach ($matriks['matrix_tahun_sertifikat'] as $m3) {
                $writer->addRow([
                    $m3->baris,
                    (int) $m3->col_aktif,
                    (int) $m3->col_expired,
                    (int) $m3->col_belum,
                    (int) $m3->col_total
                ]);
            }

            $writer->addRow([]);
            $writer->addRow(['MATRIKS 4: KATEGORI PEMBAYARAN X STATUS PEMBAYARAN']);
            $writer->addRow(['Kategori Pembayaran', 'Lunas (Trx)', 'Pending (Trx)', 'Total Trx', 'Nominal Lunas (Rp)', 'Nominal Pending (Rp)']);
            foreach ($matriks['matrix_pembayaran'] as $m4) {
                $writer->addRow([
                    $m4->baris,
                    (int) $m4->col_lunas_trx,
                    (int) $m4->col_pending_trx,
                    (int) $m4->col_total_trx,
                    (float) $m4->col_lunas_nom,
                    (float) $m4->col_pending_nom
                ]);
            }

        } elseif ($type === 'dashboard') {
            $stats = $this->getDashboardStats($scope, $request);
            $growth = $this->getGrowthChartData($scope, $request);
            $status = $this->getStatusChartData($scope, $request);

            $writer->addHeader(['RINGKASAN EKSEKUTIF & KPI DASHBOARD: ' . strtoupper($scope['title'])]);
            $writer->addRow([]);
            $writer->addRow(['Indikator KPI', 'Nilai']);
            $writer->addRow(['Total Anggota Terdaftar', $stats['total_anggota']]);
            $writer->addRow(['Anggota Aktif (Disetujui)', $stats['anggota_aktif']]);
            $writer->addRow(['Anggota Menunggu / Inaktif', $stats['anggota_inaktif']]);
            $writer->addRow(['Anggota Baru (30 Hari)', $stats['anggota_baru_30_hari']]);
            $writer->addRow(['Sertifikat Aktif', $stats['sertifikat_aktif']]);
            $writer->addRow(['Sertifikat Kedaluwarsa', $stats['sertifikat_expired']]);
            $writer->addRow(['Belum Memiliki Sertifikat', $stats['sertifikat_belum']]);
            $writer->addRow(['Transaksi Iuran Lunas', $stats['transaksi_bayar_lunas']]);
            $writer->addRow(['Transaksi Iuran Tertunda', $stats['transaksi_bayar_pending']]);
            $writer->addRow(['Total Nominal Lunas (Rp)', (float) $stats['nominal_bayar_lunas']]);
            $writer->addRow(['Total Nominal Tertunda (Rp)', (float) $stats['nominal_bayar_pending']]);

            $writer->addRow([]);
            $writer->addRow(['TREN PERTUMBUHAN']);
            $writer->addRow(['Periode', 'Total Pendaftar', 'Disetujui Aktif']);
            foreach ($growth['labels'] as $idx => $lbl) {
                $writer->addRow([$lbl, $growth['totals'][$idx] ?? 0, $growth['aktifs'][$idx] ?? 0]);
            }
        }

        // Summary footer
        $writer->addRow([]);
        $writer->addRow(['--- INFORMASI DOKUMEN ---']);
        $writer->addRow(['Tingkat Organisasi:', strtoupper($scope['level'])]);
        $writer->addRow(['Wilayah:', $scope['level'] === 'daerah' ? $scope['provinsi_name'] : $scope['kota_name']]);
        $writer->addRow(['Dicetak Oleh:', Auth::user()->name ?? 'System']);
        $writer->addRow(['Waktu Export:', Carbon::now()->format('d-m-Y H:i:s')]);

        $writer->close();

        return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Export data to PDF using Html2Pdf.
     */
    public function exportPdf(array $scope, Request $request, string $type = 'anggota')
    {
        $timestamp = date('Y-m-d_H-i-s');
        $fileName = "laporan_{$scope['level']}_{$type}_{$timestamp}.pdf";

        $data = [];
        $title = '';

        if ($type === 'anggota') {
            $data = $this->getAnggotaQuery($scope, $request)->limit(300)->get();
            $title = 'Laporan Data Anggota';
        } elseif ($type === 'sertifikat') {
            $data = $this->getSertifikatQuery($scope, $request)->limit(300)->get();
            $title = 'Laporan Sertifikat Anggota';
        } elseif ($type === 'pembayaran') {
            $data = $this->getPembayaranQuery($scope, $request)->limit(300)->get();
            $title = 'Laporan Pembayaran dan Iuran';
        } elseif ($type === 'rekapitulasi' || $type === 'matriks') {
            $data = $this->getRekapitulasiData($scope, $request);
            $matrix = $this->getMatrixData($scope, $request);
            $title = 'Laporan Rekapitulasi & Matriks Organisasi';
        }

        $stats = $this->getDashboardStats($scope, $request);
        $today = Carbon::now();

        $html = view('backend.laporan.pdf_template', [
            'scope' => $scope,
            'type' => $type,
            'title' => $title,
            'data' => $data,
            'matrix' => $matrix ?? null,
            'stats' => $stats,
            'today' => $today,
            'filters' => $request->all(),
        ])->render();

        $html2pdf = new Html2Pdf('L', 'A4', 'en');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($html);

        $pdfContent = $html2pdf->output('', 'S');

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Content-Length' => strlen($pdfContent),
            'Cache-Control' => 'max-age=0',
            'Pragma' => 'public'
        ]);
    }
}

