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
        return $apiKey === 'testing-api-key-123';
    }
}