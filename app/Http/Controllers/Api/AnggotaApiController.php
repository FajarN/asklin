<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anggota;
use Illuminate\Support\Facades\Validator;

class AnggotaApiController extends Controller
{
    /**
     * Get anggota data based on provinsi and kota
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getAnggotaByLocation(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'id_provinsi' => 'required|string',
            'id_kota' => 'required|string',
            'api_key' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify API key (implement your own validation logic)
        if (!$this->validateApiKey($request->api_key)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API Key'
            ], 401);
        }

        // Get data anggota based on provinsi and kota
        $anggota = Anggota::where('id_provinsi', $request->id_provinsi)
            ->where('id_kota', $request->id_kota)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $anggota,
            'count' => $anggota->count()
        ]);
    }

    /**
     * Get a specific anggota by ID
     * 
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getAnggotaById(Request $request, $id)
    {
        // Validate API key
        if (!$this->validateApiKey($request->api_key)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API Key'
            ], 401);
        }

        // Get anggota by ID
        $anggota = Anggota::find($id);

        if (!$anggota) {
            return response()->json([
                'success' => false,
                'message' => 'Anggota not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $anggota
        ]);
    }

    /**
     * Get last updated anggota based on location
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getLastUpdatedAnggota(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'id_provinsi' => 'required|string',
            'id_kota' => 'required|string',
            'api_key' => 'required|string',
            'last_sync' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify API key
        if (!$this->validateApiKey($request->api_key)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid API Key'
            ], 401);
        }

        // Build query
        $query = Anggota::where('id_provinsi', $request->id_provinsi)
            ->where('id_kota', $request->id_kota);

        // If last_sync is provided, only get records updated after that time
        if ($request->has('last_sync')) {
            $query->where('updated_at', '>', $request->last_sync);
        }

        $anggota = $query->get();

        return response()->json([
            'success' => true,
            'data' => $anggota,
            'count' => $anggota->count()
        ]);
    }

    /**
     * Validate API key
     * 
     * @param string $apiKey
     * @return bool
     */
    // private function validateApiKey($apiKey)
    // {
    //     // Query the database for valid API keys
    //     // You'll need to implement this based on where you store API keys
    //     $validApiKeys = \DB::table('api_keys')->pluck('key')->toArray();

    //     return in_array($apiKey, $validApiKeys);
    // }

    private function validateApiKey($apiKey)
    {
        // Untuk testing lokal, gunakan hardcoded key
        return $apiKey === '+HBmMyXqMLFPvhh4hd7EaYP/kFFQnas8t16vI74cErHScWG2YFzyoTIXyt+kxfmMn+PxxgO1WtAB5sxKSRVKhZhp2pHFFREzQ3v6t4DUfLylyzgz7b/csnVLD6JQ8Vd442YBVd0gH3hi2ERRsJfl65rYmX8PwH3O4S7pQFsH4Gq6VZUbu5/afNIJ+6BxFGGTpxK5kfq5FFzDPsrFjRFMdHq+oyh2U2Neez32D3OEbGd9qhYjrSl7PfKvxGTP/zrgKQkXAaDMiP2Gl8fX1Nhim7w+L/GWxRXPHJ8XiWUUocURKG3iunfjbIpZzAKiMBebwGIfy/YRAe/ztOtt0EVjddiCj+zm1CShELySjCkiVF85udTrPWzTPBiK9qCAD69MJSv7R1LnBDkFMhWzkxHbXLO+iLjB0tw7Sd/OXpGNn2QMft20AJnbe4gLEapFUxUg1JnyyRqRhbkX7MMPE4yGL0+PoK17MvEyxBMGF5IoZ8gwoQbm8d6cRbiXvEAa7LW9KUnHSsGO15vK4VARo2DmF7KwXdKGmU1IqFBYpm1JXA1HhU6oqN68PzN+pcpZZSsxI0W10XNAVarx0R6YlRJ8XvNRWvCW7XqFXErsgSbxSmkwJ1898aj/9FuD7FpmVL/r1jA461e5iFOYEt5D2TfW+8mw';
    }
}
