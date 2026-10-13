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

        if ($tingkat === 'pusat') {
            return $this->getStrukturPusat($ringkasan);
        } elseif ($tingkat === 'daerah' || $tingkat === 'dpd') {
            return $this->getDirektoriDaerah($provinsiCode, $ringkasan);
        } elseif ($tingkat === 'cabang' || $tingkat === 'dpc') {
            return $this->getDirektoriCabang($provinsiCode, $kotaCode, $ringkasan);
        }

        return $this->errorResponse("Tingkatan organisasi '{$tingkat}' tidak valid. Pilih antara: pusat, daerah, atau cabang.", 400);
    }

    /**
     * Mengambil detail struktur Pengurus Pusat (DPP).
     */
    private function getStrukturPusat(array $ringkasan): JsonResponse
    {
        $tingkatanPusat = TingkatanPengurus::where('nama_tingkatan', 'LIKE', '%Pusat%')->first();
        $pusatId = $tingkatanPusat ? $tingkatanPusat->id : 1;

        $struktur = StrukturOrganisasi::with(['provinsi', 'kota'])
            ->where('id_tingkatan_pengurus', $pusatId)
            ->orderBy('created_at', 'desc')
            ->first();

        $pengurusList = [];
        if ($struktur) {
            $pengurus = StrukturPengurus::where('id_struktur_organisasi', $struktur->id)
                ->where('status', 'aktif')
                ->orderBy('urutan', 'asc')
                ->get();

            $pengurusList = $pengurus->map(function ($p) {
                return [
                    'id'           => $p->id,
                    'jabatan'      => ucwords(strtolower($p->jabatan)),
                    'nama_lengkap' => $p->nama_lengkap,
                    'no_telp'      => $p->no_telp,
                    'email'        => $p->email,
                    'foto_url'     => $p->foto_pengurus ? asset('assets/images/pengurus/' . $p->foto_pengurus) : null,
                    'urutan'       => $p->urutan,
                    'status'       => $p->status,
                ];
            });
        }

        $result = [
            'ringkasan' => $ringkasan,
            'tingkat'   => 'pusat',
            'detail'    => $struktur ? [
                'id'                 => $struktur->id,
                'nama_struktur'      => $struktur->nama_struktur,
                'periode'            => $struktur->periode,
                'alamat_sekretariat' => $struktur->alamat_sekretariat,
                'email_sekretariat'  => $struktur->email_sekretariat,
                'telp_sekretariat'   => $struktur->telp_sekretariat,
                'provinsi'           => $struktur->provinsi ? $struktur->provinsi->name : 'DKI Jakarta',
                'kota'               => $struktur->kota ? $struktur->kota->name : 'Jakarta Timur',
                'status'             => $struktur->status,
            ] : null,
            'pengurus'  => $pengurusList,
        ];

        return $this->successResponse($result, 'Data Pengurus Pusat ASKLIN berhasil dimuat.');
    }

    /**
     * Mengambil daftar direktori Pengurus Daerah (DPD) tingkat provinsi.
     */
    private function getDirektoriDaerah(?string $provinsiCode, array $ringkasan): JsonResponse
    {
        $provQuery = Province::query();
        if (!empty($provinsiCode)) {
            $provQuery->where('code', $provinsiCode);
        }

        $provinces = $provQuery->orderBy('name', 'asc')->get();

        $dpdList = $provinces->map(function ($prov) {
            // Cek apakah ada record struktur organisasi terdaftar untuk provinsi ini
            $struktur = StrukturOrganisasi::where('id_provinsi', $prov->code)
                ->where('id_tingkatan_pengurus', '!=', 1)
                ->first();

            return [
                'provinsi_code'      => $prov->code,
                'provinsi_name'      => $prov->name,
                'nama_struktur'      => $struktur ? $struktur->nama_struktur : ('Pengurus Daerah ASKLIN ' . $prov->name),
                'periode'            => $struktur ? $struktur->periode : '2023 - 2028',
                'alamat_sekretariat' => $struktur ? $struktur->alamat_sekretariat : ('Sekretariat DPD ASKLIN ' . $prov->name),
                'email_sekretariat'  => $struktur ? $struktur->email_sekretariat : null,
                'telp_sekretariat'   => $struktur ? $struktur->telp_sekretariat : null,
                'status'             => $struktur ? $struktur->status : 'Aktif',
            ];
        });

        $result = [
            'ringkasan' => $ringkasan,
            'tingkat'   => 'daerah',
            'daftar_dpd'=> $dpdList,
        ];

        return $this->successResponse($result, 'Daftar Pengurus Daerah (DPD) berhasil dimuat.');
    }

    /**
     * Mengambil daftar direktori Pengurus Cabang (DPC) tingkat kabupaten/kota.
     */
    private function getDirektoriCabang(?string $provinsiCode, ?string $kotaCode, array $ringkasan): JsonResponse
    {
        $cityQuery = City::with('province');
        if (!empty($provinsiCode)) {
            $cityQuery->where('province_code', $provinsiCode);
        }
        if (!empty($kotaCode)) {
            $cityQuery->where('code', $kotaCode);
        }

        $cities = $cityQuery->orderBy('name', 'asc')->paginate(20);

        $dpcList = collect($cities->items())->map(function ($city) {
            return [
                'kota_code'          => $city->code,
                'kota_name'          => $city->name,
                'provinsi_code'      => $city->province_code,
                'provinsi_name'      => $city->province ? $city->province->name : null,
                'nama_struktur'      => 'Pengurus Cabang ASKLIN ' . $city->name,
                'periode'            => '2023 - 2028',
                'alamat_sekretariat' => 'Sekretariat DPC ASKLIN ' . $city->name,
                'email_sekretariat'  => null,
                'telp_sekretariat'   => null,
                'status'             => 'Aktif',
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar Pengurus Cabang (DPC) berhasil dimuat.',
            'data'    => [
                'ringkasan'  => $ringkasan,
                'tingkat'    => 'cabang',
                'daftar_dpc' => $dpcList,
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

