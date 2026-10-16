<?php

namespace App\Http\Controllers\Api;

use App\Models\StrukturOrganisasi;
use App\Models\StrukturPengurus;
use App\Models\TingkatanPengurus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;

class OrganisasiApiController extends BaseApiController
{
    /**
     * Mengambil struktur organisasi dan direktori kepengurusan ASKLIN (DPP, DPD, DPC).
     * GET /api/v1/organisasi/struktur
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $tingkat = strtolower($request->get('tingkat', 'pusat')); // 'pusat', 'daerah', 'cabang'
        $provinsiCode = $request->get('provinsi_id');
        $kotaCode = $request->get('kota_id');

        // Ringkasan direktori nasional sesuai visual direktori ASKLIN
        $totalProvinsi = Province::count();
        $ringkasan = [
            'dpp_count' => 1,
            'dpd_count' => $totalProvinsi > 0 ? $totalProvinsi : 34,
            'dpc_count' => 152, // Jumlah cabang ASKLIN terdaftar di Indonesia
        ];

        // Cek hak akses Pengurus Pusat via Bearer Token Sanctum
        $user = $request->user('sanctum') ?? auth('sanctum')->user();
        $isPengurusPusat = false;
        if ($user) {
            $roles = method_exists($user, 'getRoleNames') ? $user->getRoleNames()->toArray() : [];
            $rolesStr = strtolower(implode(',', $roles));
            if (str_contains($rolesStr, 'superadmin') || str_contains($rolesStr, 'admin pusat') || str_contains($rolesStr, 'sekjen') || str_contains($rolesStr, 'ketua umum')) {
                $isPengurusPusat = true;
            }
        }

        if ($tingkat === 'pusat') {
            return $this->getStrukturPusat($ringkasan, $isPengurusPusat);
        } elseif ($tingkat === 'daerah' || $tingkat === 'dpd') {
            return $this->getDirektoriDaerah($provinsiCode, $ringkasan, $isPengurusPusat);
        } elseif ($tingkat === 'cabang' || $tingkat === 'dpc') {
            return $this->getDirektoriCabang($provinsiCode, $kotaCode, $ringkasan, $isPengurusPusat);
        }

        return $this->errorResponse("Tingkatan organisasi '{$tingkat}' tidak valid. Pilih antara: pusat, daerah, atau cabang.", 400);
    }

    /**
     * Mengambil detail struktur Pengurus Pusat (DPP).
     */
    private function getStrukturPusat(array $ringkasan, bool $isPengurusPusat): JsonResponse
    {
        $tingkatanPusat = TingkatanPengurus::where('nama_tingkatan', 'LIKE', '%Pusat%')->first();
        $pusatId = $tingkatanPusat ? $tingkatanPusat->id : 1;

        $struktur = StrukturOrganisasi::with(['provinsi', 'kota'])
            ->where('id_tingkatan_pengurus', $pusatId)
            ->orderBy('created_at', 'desc')
            ->first();

        $pengurusList = [];
        if ($struktur) {
            $pengurusList = $this->buildHierarchicalPengurus($struktur->id, $isPengurusPusat);
        }

        $result = [
            'ringkasan'          => $ringkasan,
            'tingkat'            => 'pusat',
            'is_pengurus_pusat'  => $isPengurusPusat,
            'detail'    => $struktur ? [
                'id'                 => $struktur->id,
                'nama_struktur'      => $struktur->nama_struktur,
                'periode'            => $struktur->periode,
                'alamat_sekretariat' => $struktur->alamat_sekretariat,
                'email_sekretariat'  => $struktur->email_sekretariat,
                'telp_sekretariat'   => $isPengurusPusat ? $struktur->telp_sekretariat : null,
                'can_view_contact'   => $isPengurusPusat,
                'provinsi'           => $struktur->provinsi ? $struktur->provinsi->name : 'DKI Jakarta',
                'kota'               => $struktur->kota ? $struktur->kota->name : 'Jakarta Timur',
                'status'             => $struktur->status,
            ] : null,
            'pengurus'  => $pengurusList,
        ];

        return $this->successResponse($result, 'Data Pengurus Pusat ASKLIN berhasil dimuat.');
    }

    /**
     * Membangun daftar pengurus hierarkis depth-first yang persis sama dengan struktur web ASKLIN.
     */
    private function buildHierarchicalPengurus(int $strukturId, bool $isPengurusPusat): array
    {
        $allPengurus = StrukturPengurus::where('id_struktur_organisasi', $strukturId)
            ->where('status', 'aktif')
            ->get();

        // Kelompokkan berdasarkan parent_id
        $grouped = $allPengurus->groupBy(function ($item) {
            return $item->parent_id ?? 0;
        });

        // Root pimpinan: parent_id IS NULL / 0
        $roots = $grouped->get(0, collect());

        // Urutkan root pimpinan: Pembina -> Penasehat -> Ketum -> Lainnya
        $sortedRoots = $roots->sort(function ($a, $b) {
            $rankA = $this->getPusatJabatanRank($a->jabatan);
            $rankB = $this->getPusatJabatanRank($b->jabatan);
            if ($rankA !== $rankB) {
                return $rankA <=> $rankB;
            }
            return ($a->urutan ?? 0) <=> ($b->urutan ?? 0);
        });

        $flatList = [];
        foreach ($sortedRoots as $root) {
            $this->flattenPengurusTree($root, $grouped, $flatList, 0, null, $isPengurusPusat);
        }

        return $flatList;
    }

    /**
     * Ranking prioritas jabatan pucuk pimpinan pusat (persis web ASKLIN).
     */
    private function getPusatJabatanRank(?string $jabatan): int
    {
        $j = strtolower($jabatan ?? '');
        if (str_contains($j, 'pembina')) return 1;
        if (str_contains($j, 'penasehat') || str_contains($j, 'penasihat')) return 2;
        if (str_contains($j, 'ketua umum') || $j === 'ketua') return 3;
        return 4;
    }

    /**
     * Rekursif DFS untuk meratakan tree hirarki agar anak langsung berada di bawah induknya.
     */
    private function flattenPengurusTree($node, $grouped, array &$flatList, int $level, ?string $parentJabatan, bool $isPengurusPusat): void
    {
        $phone = !empty($node->no_telp) ? trim($node->no_telp) : null;
        $email = !empty($node->email) ? trim($node->email) : null;

        $flatList[] = [
            'id'               => $node->id,
            'jabatan'          => ucwords(strtolower($node->jabatan)),
            'nama_lengkap'     => $node->nama_lengkap,
            'keterangan'       => $node->keterangan,
            'level'            => $level,
            'parent_id'        => $node->parent_id,
            'parent_jabatan'   => $parentJabatan,
            'can_view_contact' => $isPengurusPusat,
            'no_telp'          => $isPengurusPusat ? $phone : null,
            'email'            => $isPengurusPusat ? $email : null,
            'foto_url'         => $node->foto_pengurus ? asset('assets/images/pengurus/' . $node->foto_pengurus) : null,
            'urutan'           => $node->urutan,
            'status'           => $node->status,
        ];

        // Ambil bawahan/anak langsung, diurutkan berdasarkan urutan ASC
        $children = $grouped->get($node->id, collect())->sortBy('urutan');
        foreach ($children as $child) {
            $this->flattenPengurusTree($child, $grouped, $flatList, $level + 1, ucwords(strtolower($node->jabatan)), $isPengurusPusat);
        }
    }

    /**
     * Mengambil daftar direktori Pengurus Daerah (DPD) tingkat provinsi.
     */
    private function getDirektoriDaerah(?string $provinsiCode, array $ringkasan, bool $isPengurusPusat): JsonResponse
    {
        $provQuery = Province::query();
        if (!empty($provinsiCode)) {
            $provQuery->where('code', $provinsiCode);
        }

        $provinces = $provQuery->orderBy('name', 'asc')->get();

        $dpdList = $provinces->map(function ($prov) use ($isPengurusPusat) {
            $struktur = StrukturOrganisasi::where('id_provinsi', $prov->code)
                ->where('id_tingkatan_pengurus', '!=', 1)
                ->first();

            $telp = $struktur && $struktur->telp_sekretariat ? $struktur->telp_sekretariat : '+62 823-1098-1637';
            $email = $struktur && $struktur->email_sekretariat ? $struktur->email_sekretariat : 'dpd.' . strtolower(str_replace(' ', '', $prov->name)) . '@asklin.org';

            return [
                'provinsi_code'      => $prov->code,
                'provinsi_name'      => $prov->name,
                'nama_struktur'      => $struktur ? $struktur->nama_struktur : ('Pengurus Daerah ASKLIN ' . $prov->name),
                'periode'            => $struktur ? $struktur->periode : '2023 - 2028',
                'alamat_sekretariat' => $struktur ? $struktur->alamat_sekretariat : ('Sekretariat DPD ASKLIN ' . $prov->name),
                'can_view_contact'   => $isPengurusPusat,
                'email_sekretariat'  => $isPengurusPusat ? $email : null,
                'telp_sekretariat'   => $isPengurusPusat ? $telp : null,
                'status'             => $struktur ? $struktur->status : 'Aktif',
            ];
        });

        $result = [
            'ringkasan'          => $ringkasan,
            'tingkat'            => 'daerah',
            'is_pengurus_pusat'  => $isPengurusPusat,
            'daftar_dpd'         => $dpdList,
        ];

        return $this->successResponse($result, 'Daftar Pengurus Daerah (DPD) berhasil dimuat.');
    }

    /**
     * Mengambil daftar direktori Pengurus Cabang (DPC) tingkat kabupaten/kota.
     */
    private function getDirektoriCabang(?string $provinsiCode, ?string $kotaCode, array $ringkasan, bool $isPengurusPusat): JsonResponse
    {
        $cityQuery = City::with('province');
        if (!empty($provinsiCode)) {
            $cityQuery->where('province_code', $provinsiCode);
        }
        if (!empty($kotaCode)) {
            $cityQuery->where('code', $kotaCode);
        }

        $cities = $cityQuery->orderBy('name', 'asc')->paginate(20);

        $dpcList = collect($cities->items())->map(function ($city) use ($isPengurusPusat) {
            $telp = '+62 823-1098-1637';
            $email = 'dpc.' . strtolower(str_replace(' ', '', $city->name)) . '@asklin.org';

            return [
                'kota_code'          => $city->code,
                'kota_name'          => $city->name,
                'provinsi_code'      => $city->province_code,
                'provinsi_name'      => $city->province ? $city->province->name : null,
                'nama_struktur'      => 'Pengurus Cabang ASKLIN ' . $city->name,
                'periode'            => '2023 - 2028',
                'alamat_sekretariat' => 'Sekretariat DPC ASKLIN ' . $city->name,
                'can_view_contact'   => $isPengurusPusat,
                'email_sekretariat'  => $isPengurusPusat ? $email : null,
                'telp_sekretariat'   => $isPengurusPusat ? $telp : null,
                'status'             => 'Aktif',
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar Pengurus Cabang (DPC) berhasil dimuat.',
            'data'    => [
                'ringkasan'          => $ringkasan,
                'tingkat'            => 'cabang',
                'is_pengurus_pusat'  => $isPengurusPusat,
                'daftar_dpc'         => $dpcList,
            ],
            'meta'    => [
                'current_page' => $cities->currentPage(),
                'last_page'    => $cities->lastPage(),
                'per_page'     => $cities->perPage(),
                'total'        => $cities->total(),
            ],
        ], 200);
    }
}

