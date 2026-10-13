<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;

class WilayahApiController extends BaseApiController
{
    /**
     * Mengambil daftar seluruh provinsi di Indonesia.
     *
     * @return JsonResponse
     */
    public function getProvinces(): JsonResponse
    {
        $provinces = Province::orderBy('name', 'asc')->get(['code', 'name']);

        return $this->successResponse($provinces, 'Daftar provinsi berhasil diambil.');
    }

    /**
     * Mengambil daftar kabupaten/kota (opsional difilter per provinsi).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getCities(Request $request): JsonResponse
    {
        $query = City::orderBy('name', 'asc');

        if ($request->filled('provinsi_code')) {
            $query->where('province_code', $request->provinsi_code);
        }

        $cities = $query->get(['code', 'name', 'province_code']);

        return $this->successResponse($cities, 'Daftar kabupaten/kota berhasil diambil.');
    }

    /**
     * Mengambil daftar kecamatan berdasarkan kode kabupaten/kota.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getDistricts(Request $request): JsonResponse
    {
        $request->validate([
            'city_code' => 'required|string',
        ], [
            'city_code.required' => 'Parameter city_code wajib disertakan.',
        ]);

        $districts = District::where('city_code', $request->city_code)
            ->orderBy('name', 'asc')
            ->get(['code', 'name', 'city_code']);

        return $this->successResponse($districts, 'Daftar kecamatan berhasil diambil.');
    }

    /**
     * Mengambil daftar kelurahan/desa berdasarkan kode kecamatan.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getVillages(Request $request): JsonResponse
    {
        $request->validate([
            'district_code' => 'required|string',
        ], [
            'district_code.required' => 'Parameter district_code wajib disertakan.',
        ]);

        $villages = Village::where('district_code', $request->district_code)
            ->orderBy('name', 'asc')
            ->get(['code', 'name', 'district_code']);

        return $this->successResponse($villages, 'Daftar kelurahan/desa berhasil diambil.');
    }
}

