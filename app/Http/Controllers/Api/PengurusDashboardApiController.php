<?php

namespace App\Http\Controllers\Api;

use App\Models\Anggota;
use App\Models\SDM;
use App\Services\LaporanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;

class PengurusDashboardApiController extends BaseApiController
{
    protected LaporanService $laporanService;

    public function __construct(LaporanService $laporanService)
    {
        $this->laporanService = $laporanService;
    }

    /**
     * Dashboard monitoring adaptif untuk Pengurus Cabang, Daerah, dan Pusat.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getDashboard(Request $request): JsonResponse
    {
        $user = $request->user();

        // Tentukan level wewenang berdasarkan role
        $level = $this->determineUserLevel($user, $request);

        $scope = $this->laporanService->resolveScope($level, $user, $request);
        $stats = $this->laporanService->getDashboardStats($scope, $request);
        $statusChart = $this->laporanService->getStatusChartData($scope, $request);
        $growthChart = $this->laporanService->getGrowthChartData($scope, $request);
        $regionalChart = $this->laporanService->getRegionalDistributionChartData($scope, $request);
        $breakdownChart = $this->laporanService->getBreakdownChartData($scope, $request);

        // Filter opsi wilayah yang tersedia untuk level user
        $filterOptions = [];
        if ($level === 'pusat') {
            $filterOptions['provinces'] = Province::orderBy('name')->get(['code', 'name']);
            if ($scope['provinsi_id']) {
                $filterOptions['cities'] = City::where('province_code', $scope['provinsi_id'])->orderBy('name')->get(['code', 'name', 'province_code']);
            }
        } elseif ($level === 'daerah') {
            $filterOptions['cities'] = City::where('province_code', $scope['provinsi_id'])->orderBy('name')->get(['code', 'name', 'province_code']);
        }

        return $this->successResponse([
            'scope'          => [
                'level'              => $scope['level'],
                'is_national_admin'  => $scope['is_national_admin'],
                'provinsi_id'        => $scope['provinsi_id'],
                'provinsi_name'      => $scope['provinsi_name'],
                'kota_id'            => $scope['kota_id'],
                'kota_name'          => $scope['kota_name'],
                'is_locked_provinsi' => $scope['is_locked_provinsi'],
                'is_locked_kota'     => $scope['is_locked_kota'],
                'title'              => $scope['title'],
            ],
            'kpi_stats'      => [
                'total_anggota'        => (int) ($stats['total'] ?? 0),
                'disetujui'            => (int) ($stats['approved'] ?? 0),
                'menunggu_verifikasi'  => (int) ($stats['waiting'] ?? 0),
                'perlu_perbaikan'      => (int) ($stats['perbaikan'] ?? 0),
                'iuran_lunas'          => (int) ($stats['lunas'] ?? 0),
                'iuran_tertunda'       => (int) ($stats['belum_lunas'] ?? 0),
                'sertifikat_aktif'     => (int) ($stats['sertifikat_aktif'] ?? 0),
                'sertifikat_expired'   => (int) ($stats['sertifikat_expired'] ?? 0),
            ],
            'charts'         => [
                'status_distribution'   => $statusChart,
                'growth_trend'          => $growthChart,
                'regional_distribution' => $regionalChart,
                'jenis_dan_badan_hukum' => $breakdownChart,
            ],
            'filter_options' => $filterOptions,
        ], 'Data dashboard monitoring berhasil diambil.');
    }

    /**
     * Daftar anggota klinik dalam wewenang pengurus dengan pagination REST.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAnggotaList(Request $request): JsonResponse
    {
        $user = $request->user();
        $query = Anggota::select(
            'anggota.id',
            'anggota.no_anggota',
            'anggota.nama_klinik',
            'anggota.jenis_klinik',
            'anggota.status',
            'anggota.status_pembayaran',
            'anggota.nama_pemilik_klinik',
            'anggota.nama_kontak',
            'anggota.tlf_klinik',
            'anggota.tgl_akhir_ijin',
            'anggota.id_kota',
            'anggota.id_provinsi',
            'c.name as kota_name',
            'p.name as provinsi_name',
            DB::raw("CASE 
                WHEN anggota.created_at IS NOT NULL AND anggota.created_at != '0000-00-00 00:00:00' THEN anggota.created_at 
                WHEN anggota.created_on IS NOT NULL AND anggota.created_on != '' THEN FROM_UNIXTIME(anggota.created_on) 
                ELSE NULL 
            END as tanggal_daftar")
        )
            ->leftJoin('indonesia_cities as c', 'c.code', '=', 'anggota.id_kota')
            ->leftJoin('indonesia_provinces as p', 'p.code', '=', 'anggota.id_provinsi');

        // Terapkan isolasi wilayah pengurus
        if ($user->hasRole('Admin Cabang')) {
            $query->where('anggota.id_kota', $user->kota);
        } elseif ($user->hasRole('Admin Daerah')) {
            $query->where('anggota.id_provinsi', $user->provinsi);
            if ($request->filled('kota')) {
                $query->where('anggota.id_kota', $request->kota);
            }
        } elseif ($this->isNationalAdmin($user)) {
            if ($request->filled('provinsi')) {
                $query->where('anggota.id_provinsi', $request->provinsi);
            }
            if ($request->filled('kota')) {
                $query->where('anggota.id_kota', $request->kota);
            }
        } else {
            return $this->errorResponse('Anda tidak memiliki hak akses data pengurus.', 403);
        }

        // Filter pencarian teks
        if ($request->filled('search')) {
            $keyword = trim($request->search);
            $query->where(function ($q) use ($keyword) {
                $q->where('anggota.nama_klinik', 'like', "%{$keyword}%")
                  ->orWhere('anggota.no_anggota', 'like', "%{$keyword}%")
                  ->orWhere('anggota.nama_pemilik_klinik', 'like', "%{$keyword}%")
                  ->orWhere('c.name', 'like', "%{$keyword}%");
            });
        }

        // Filter status verifikasi
        if ($request->filled('status')) {
            $query->where('anggota.status', $request->status);
        }

        // Filter jenis klinik
        if ($request->filled('jenis_klinik')) {
            $query->where('anggota.jenis_klinik', $request->jenis_klinik);
        }

        // Filter status pembayaran
        if ($request->filled('status_pembayaran')) {
            $query->where('anggota.status_pembayaran', $request->status_pembayaran);
        }

        $perPage = max(1, min((int) $request->get('per_page', 15), 100));
        $paginator = $query->latest('anggota.id')->paginate($perPage);

        // Transform data
        $items = collect($paginator->items())->map(function ($row) {
            return [
                'id'                => $row->id,
                'no_anggota'        => $row->no_anggota ?: '-',
                'nama_klinik'       => $row->nama_klinik,
                'jenis_klinik'      => $row->jenis_klinik,
                'status'            => $row->status,
                'status_pembayaran' => (string) $row->status_pembayaran,
                'is_lunas'          => (string) $row->status_pembayaran === '1',
                'is_approved'       => strtolower($row->status) === 'approved',
                'nama_pemilik'      => $row->nama_pemilik_klinik,
                'kontak'            => $row->nama_kontak,
                'telepon'           => $row->tlf_klinik,
                'kota'              => $row->kota_name,
                'provinsi'          => $row->provinsi_name,
                'tanggal_daftar'    => $row->tanggal_daftar,
            ];
        });

        return $this->paginatedResponse($paginator, $items, 'Daftar anggota klinik berhasil dimuat.');
    }

    /**
     * Detail klinik untuk kebutuhan pemeriksaan pengurus.
     *
     * @param Request $request
     * @param int|string $id
     * @return JsonResponse
     */
    public function getAnggotaDetail(Request $request, $id): JsonResponse
    {
        $user = $request->user();

        $anggota = Anggota::select(
            'anggota.*',
            'c.name as kota_name',
            'p.name as provinsi_name',
            'd.name as kecamatan_name',
            'v.name as kelurahan_name'
        )
            ->leftJoin('indonesia_cities as c', 'c.code', '=', 'anggota.id_kota')
            ->leftJoin('indonesia_provinces as p', 'p.code', '=', 'anggota.id_provinsi')
            ->leftJoin('indonesia_districts as d', 'd.code', '=', 'anggota.id_kecamatan')
            ->leftJoin('indonesia_villages as v', 'v.code', '=', 'anggota.id_kelurahan')
            ->where('anggota.id', $id)
            ->first();

        if (!$anggota) {
            return $this->errorResponse('Data anggota tidak ditemukan.', 404);
        }

        // Verifikasi isolasi wilayah pengurus
        if ($user->hasRole('Admin Cabang') && (string) $anggota->id_kota !== (string) $user->kota) {
            return $this->errorResponse('Akses ditolak: Anggota ini berada di luar kabupaten/kota kewenangan Anda.', 403);
        }

        if ($user->hasRole('Admin Daerah') && (string) $anggota->id_provinsi !== (string) $user->provinsi) {
            return $this->errorResponse('Akses ditolak: Anggota ini berada di luar provinsi kewenangan Anda.', 403);
        }

        if (!$this->isNationalAdmin($user) && !$user->hasRole(['Admin Cabang', 'Admin Daerah'])) {
            return $this->errorResponse('Anda tidak memiliki wewenang untuk melihat data ini.', 403);
        }

        // Ambil SDM terkait
        $sdmList = SDM::where('id_klinik', $anggota->id)
            ->get(['id', 'nama', 'nama_dokter', 'peranan', 'no_str', 'no_sip', 'tgl_akhir_sip', 'id_kategori_sdm']);

        return $this->successResponse([
            'id'                  => $anggota->id,
            'no_anggota'          => $anggota->no_anggota,
            'nama_klinik'         => $anggota->nama_klinik,
            'status'              => $anggota->status,
            'jenis_klinik'        => $anggota->jenis_klinik,
            'status_pembayaran'   => (string) $anggota->status_pembayaran,
            'status_kepemilikan'  => $anggota->status_kepemilikan,
            'nama_pemilik_klinik' => $anggota->nama_pemilik_klinik,
            'bentuk_badan_hukum'  => $anggota->bentuk_badan_hukum,
            'nama_badan_usaha'    => $anggota->nama_badan_usaha,
            'bentuk_badan_usaha'  => $anggota->bentuk_badan_usaha,
            'alamat_lengkap'      => [
                'alamat_klinik' => $anggota->alamat_klinik,
                'rt'            => $anggota->rt,
                'rw'            => $anggota->rw,
                'kode_pos'      => $anggota->kode_pos,
                'kelurahan'     => $anggota->kelurahan_name,
                'kecamatan'     => $anggota->kecamatan_name,
                'kota'          => $anggota->kota_name,
                'provinsi'      => $anggota->provinsi_name,
            ],
            'kontak'              => [
                'nama_kontak' => $anggota->nama_kontak,
                'email'       => $anggota->email,
                'tlf'         => $anggota->tlf,
                'tlf_klinik'  => $anggota->tlf_klinik,
            ],
            'perijinan'           => [
                'no_ijin'        => $anggota->no_ijin,
                'tgl_ijin'       => $anggota->tgl_ijin,
                'tgl_akhir_ijin' => $anggota->tgl_akhir_ijin,
            ],
            'sdm_list'            => $sdmList,
            'verifikasi'          => [
                'verifikasi_cabang' => $anggota->verifikasi_cabang,
                'verifikasi_pusat'  => $anggota->verifikasi_pusat,
            ],
            'keterangan'          => $anggota->keterangan,
        ], 'Detail anggota berhasil diambil.');
    }

    /**
     * Tentukan level wewenang pelaporan pengguna.
     */
    private function determineUserLevel($user, Request $request): string
    {
        if ($this->isNationalAdmin($user)) {
            // Pengurus pusat dapat meminta scope daerah/cabang tertentu jika ada di parameter
            return $request->get('level', 'pusat');
        }

        if ($user->hasRole('Admin Daerah')) {
            return 'daerah';
        }

        return 'cabang';
    }

    /**
     * Cek apakah user adalah pengurus tingkat nasional.
     */
    private function isNationalAdmin($user): bool
    {
        return $user->hasAnyRole(['Superadmin', 'Admin Pusat', 'Sekjen', 'Ketua Umum', 'Bendahara'])
            || $user->can('laporan-pusat');
    }
}

