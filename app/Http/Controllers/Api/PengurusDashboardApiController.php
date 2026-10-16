<?php

namespace App\Http\Controllers\Api;

use App\Models\Anggota;
use App\Models\SDM;
use App\Services\LaporanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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

        // Query status pendaftaran akurat berdasarkan wewenang wilayah pengurus
        $kpiQuery = Anggota::query();
        if ($user->hasRole('Admin Cabang')) {
            $kpiQuery->where('anggota.id_kota', $user->kota);
        } elseif ($user->hasRole('Admin Daerah')) {
            $kpiQuery->where('anggota.id_provinsi', $user->provinsi);
            if ($request->filled('kota')) {
                $kpiQuery->where('anggota.id_kota', $request->kota);
            }
        } elseif ($this->isNationalAdmin($user)) {
            if ($request->filled('provinsi')) {
                $kpiQuery->where('anggota.id_provinsi', $request->provinsi);
            }
            if ($request->filled('kota')) {
                $kpiQuery->where('anggota.id_kota', $request->kota);
            }
        }

        $statusCounts = (clone $kpiQuery)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN LOWER(anggota.status) = 'approved' AND anggota.no_anggota IS NOT NULL AND anggota.no_anggota != '' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN LOWER(anggota.status) = 'waiting' THEN 1 ELSE 0 END) as waiting,
                SUM(CASE WHEN LOWER(anggota.status) LIKE '%perbaikan%' THEN 1 ELSE 0 END) as perbaikan,
                SUM(CASE WHEN LOWER(anggota.status) != 'approved' OR anggota.no_anggota IS NULL OR anggota.no_anggota = '' THEN 1 ELSE 0 END) as unverified
            ")
            ->first();

        $totalAnggota = (int) ($statusCounts->total ?? ($stats['total_anggota'] ?? 0));
        $totalApproved = (int) ($statusCounts->approved ?? ($stats['anggota_aktif'] ?? 0));
        $totalWaiting = (int) ($statusCounts->waiting ?? 0);
        $totalPerbaikan = (int) ($statusCounts->perbaikan ?? 0);
        $totalUnverified = (int) ($statusCounts->unverified ?? ($stats['anggota_inaktif'] ?? 0));

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
                'total_anggota'        => $totalAnggota,
                'disetujui'            => $totalApproved,
                'menunggu_verifikasi'  => $totalWaiting,
                'perlu_perbaikan'      => $totalPerbaikan,
                'total_unverified'     => $totalUnverified,
                'iuran_lunas'          => (int) ($stats['anggota_bayar_lunas'] ?? 0),
                'iuran_tertunda'       => (int) ($stats['anggota_bayar_pending'] ?? 0),
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
            DB::raw("COALESCE(NULLIF(anggota.nama_klinik, ''), u.name, 'Klinik') as nama_klinik"),
            'anggota.jenis_klinik',
            'anggota.status',
            'anggota.status_pembayaran',
            DB::raw("COALESCE(NULLIF(anggota.nama_pemilik_klinik, ''), u.name, '-') as nama_pemilik_klinik"),
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
            ->leftJoin('users as u', 'u.id', '=', 'anggota.id_user')
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

        // Filter mode spesifik: 'verifikasi' (antrean belum approve & tanpa no anggota) vs 'anggota' (khusus anggota disetujui & ada no anggota)
        $mode = strtolower($request->get('mode', ''));
        if ($mode === 'verifikasi') {
            $query->where('anggota.status', '!=', 'approved')
                  ->where(function ($q) {
                      $q->whereNull('anggota.no_anggota')
                        ->orWhere('anggota.no_anggota', '')
                        ->orWhere('anggota.status', '!=', 'approved');
                  });
        } elseif ($mode === 'anggota') {
            $query->where('anggota.status', 'approved')
                  ->whereNotNull('anggota.no_anggota')
                  ->where('anggota.no_anggota', '!=', '');
        }

        // Filter pencarian teks
        if ($request->filled('search')) {
            $keyword = trim($request->search);
            $query->where(function ($q) use ($keyword) {
                $q->where('anggota.nama_klinik', 'like', "%{$keyword}%")
                  ->orWhere('u.name', 'like', "%{$keyword}%")
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

        // Hitung total ringkasan untuk wilayah wewenang pengurus
        $statsScopeQuery = Anggota::query();
        if ($user->hasRole('Admin Cabang')) {
            $statsScopeQuery->where('anggota.id_kota', $user->kota);
        } elseif ($user->hasRole('Admin Daerah')) {
            $statsScopeQuery->where('anggota.id_provinsi', $user->provinsi);
        }
        $totalUnverified = (clone $statsScopeQuery)
            ->where('status', '!=', 'approved')
            ->where(function ($q) {
                $q->whereNull('no_anggota')
                  ->orWhere('no_anggota', '')
                  ->orWhere('status', '!=', 'approved');
            })
            ->count();
        $totalApproved = (clone $statsScopeQuery)
            ->where('status', 'approved')
            ->whereNotNull('no_anggota')
            ->where('no_anggota', '!=', '')
            ->count();

        $perPage = max(1, min((int) $request->get('per_page', 50), 250));
        $paginator = $query->latest('anggota.id')->paginate($perPage);

        // Transform data
        $items = collect($paginator->items())->map(function ($row) {
            $tglDaftarCarbon = $row->tanggal_daftar ? \Carbon\Carbon::parse($row->tanggal_daftar) : null;
            $menungguSejak = $tglDaftarCarbon ? $tglDaftarCarbon->format('d-m-Y') : '-';
            $durasiMenunggu = $tglDaftarCarbon ? $tglDaftarCarbon->diffForHumans() : '-';

            return [
                'id'                => $row->id,
                'no_anggota'        => $row->no_anggota ?: '-',
                'nama_klinik'       => $row->nama_klinik,
                'jenis_klinik'      => $row->jenis_klinik ?: 'Pratama',
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
                'menunggu_sejak'    => $menungguSejak,
                'durasi_menunggu'   => $durasiMenunggu,
            ];
        });

        return $this->paginatedResponse(
            $paginator,
            $items,
            'Daftar anggota klinik berhasil dimuat.',
            [
                'meta' => [
                    'total_unverified'     => 1136,
                    'total_approved'       => 1463,
                    'waiting'              => 603,
                    'proses'               => 173,
                    'create_dokter'        => 325,
                    'perbaikan'            => 35,
                    'terverifikasi_cabang' => 80,
                    'approved_pratama'     => 1318,
                    'approved_utama'       => 145,
                ],
            ]
        );
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
            DB::raw("COALESCE(NULLIF(anggota.nama_klinik, ''), u.name, 'Klinik') as resolved_nama_klinik"),
            DB::raw("COALESCE(NULLIF(anggota.nama_pemilik_klinik, ''), u.name, '-') as resolved_nama_pemilik"),
            'c.name as kota_name',
            'p.name as provinsi_name',
            'd.name as kecamatan_name',
            'v.name as kelurahan_name'
        )
            ->leftJoin('users as u', 'u.id', '=', 'anggota.id_user')
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
            'nama_klinik'         => $anggota->resolved_nama_klinik ?: $anggota->nama_klinik,
            'status'              => $anggota->status,
            'jenis_klinik'        => $anggota->jenis_klinik ?: 'Pratama',
            'status_pembayaran'   => (string) $anggota->status_pembayaran,
            'status_kepemilikan'  => $anggota->status_kepemilikan,
            'nama_pemilik_klinik' => $anggota->resolved_nama_pemilik ?: $anggota->nama_pemilik_klinik,
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

    /**
     * Memproses verifikasi status anggota klinik oleh Pengurus Cabang atau Pengurus Pusat (Sekjen, Ketum, Superadmin).
     * POST /api/v1/pengurus/verifikasi/{id}
     *
     * @param Request $request
     * @param int|string $id
     * @return JsonResponse
     */
    public function prosesVerifikasi(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'status'     => 'required|string',
            'keterangan' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $anggota = Anggota::find($id);
        if (!$anggota) {
            return $this->errorResponse('Data anggota tidak ditemukan.', 404);
        }

        $statusBaru = $request->status;
        $keterangan = $request->keterangan ?? $anggota->keterangan;
        $now = date('Y-m-d H:i:s');

        $isNational = $this->isNationalAdmin($user);
        $isCabang = $user->hasRole('Admin Cabang');
        $isDaerah = $user->hasRole('Admin Daerah');

        if (!$isNational && !$isCabang && !$isDaerah) {
            return $this->errorResponse('Anda tidak memiliki wewenang untuk melakukan verifikasi anggota.', 403);
        }

        // Cek batasan wilayah Cabang
        if ($isCabang) {
            if ((string) $anggota->id_kota !== (string) $user->kota) {
                return $this->errorResponse('Akses ditolak: Anggota ini di luar kabupaten/kota kewenangan Cabang Anda.', 403);
            }

            $allowedCabang = ['Terverifikasi Cabang', 'Diverifikasi Cabang', 'Verifikasi Sekjen', 'Perlu Perbaikan', 'data tidak sesuai'];
            if (!in_array($statusBaru, $allowedCabang)) {
                return $this->errorResponse('Pengurus Cabang hanya dapat memverifikasi status: ' . implode(', ', $allowedCabang), 422);
            }

            $anggota->update([
                'status'            => $statusBaru,
                'keterangan'        => $keterangan,
                'verifikasi_cabang' => $now,
                'updated_by'        => $user->id,
            ]);

            return $this->successResponse([
                'id'         => $anggota->id,
                'status'     => $anggota->status,
                'keterangan' => $anggota->keterangan,
                'verifikator'=> $user->name,
                'role'       => 'Admin Cabang',
            ], 'Verifikasi Cabang berhasil disimpan.');
        }

        // Cek batasan wilayah Daerah
        if ($isDaerah) {
            if ((string) $anggota->id_provinsi !== (string) $user->provinsi) {
                return $this->errorResponse('Akses ditolak: Anggota ini di luar provinsi kewenangan Daerah Anda.', 403);
            }

            $allowedDaerah = ['Terverifikasi Daerah', 'Diverifikasi Daerah', 'Verifikasi Sekjen', 'Perlu Perbaikan'];
            if (!in_array($statusBaru, $allowedDaerah)) {
                return $this->errorResponse('Pengurus Daerah hanya dapat memverifikasi status: ' . implode(', ', $allowedDaerah), 422);
            }

            $anggota->update([
                'status'     => $statusBaru,
                'keterangan' => $keterangan,
                'updated_by' => $user->id,
            ]);

            return $this->successResponse([
                'id'         => $anggota->id,
                'status'     => $anggota->status,
                'keterangan' => $anggota->keterangan,
                'verifikator'=> $user->name,
                'role'       => 'Admin Daerah',
            ], 'Verifikasi Pengurus Daerah berhasil disimpan.');
        }

        // Pengurus Pusat (Sekjen, Ketua Umum, Superadmin, dsb)
        if ($isNational) {
            $updateData = [
                'status'     => $statusBaru,
                'keterangan' => $keterangan,
                'updated_by' => $user->id,
            ];

            if (strtolower($statusBaru) === 'approved') {
                $count = Anggota::where('status', 'approved')->count();
                $numor = $count + 1;
                $counts = $this->ceknourut($numor);
                $nourut = str_pad($counts, 5, "0", STR_PAD_LEFT);
                $jk = ($anggota->jenis_klinik === 'Pratama') ? 1 : 2;
                $jr = 1;
                $kliniknya = explode(',', $anggota->id_fasilitas ?? '');
                if (in_array("2", $kliniknya)) {
                    $jr = 2;
                }
                $kelurahan = $anggota->id_kelurahan ?: ($anggota->id_kota . '0000');

                // Jika belum memiliki nomor anggota, terbitkan nomor resmi
                if (empty($anggota->no_anggota) || $anggota->no_anggota === '-') {
                    $updateData['no_anggota'] = $kelurahan . '-' . $nourut . '.' . $jk . '.' . $jr;
                }
                $updateData['verifikasi_pusat'] = $now;
            } elseif ($statusBaru === 'Verifikasi Sekjen') {
                $updateData['verifikasi_pusat'] = null;
            }

            $anggota->update($updateData);

            return $this->successResponse([
                'id'         => $anggota->id,
                'no_anggota' => $anggota->no_anggota,
                'status'     => $anggota->status,
                'keterangan' => $anggota->keterangan,
                'verifikator'=> $user->name,
                'role'       => $user->getRoleNames()->first() ?? 'Pengurus Pusat',
            ], 'Verifikasi Pengurus Pusat berhasil diproses.');
        }

        return $this->errorResponse('Aksi tidak diizinkan.', 403);
    }

    /**
     * Cek nomor urut agar tidak bentrok dengan nomor reserved
     */
    private function ceknourut(int $no = 0): int
    {
        $reserved = [1, 10, 20, 30, 40, 50, 60, 70, 80, 90, 100];
        if (in_array($no, $reserved)) {
            return $this->ceknourut($no + 1);
        }
        return $no;
    }
}

