<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class AppKeySecurityTest extends TestCase
{
    public function test_api_request_without_app_key_is_blocked_with_403()
    {
        // Explicitly clear the X-App-Key header
        $response = $this->withHeaders(['X-App-Key' => ''])
                         ->getJson('/api/v1/wilayah/provinsi');

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Akses Ditolak: Kunci otorisasi aplikasi ASKLIN tidak sah atau belum disertakan.',
                 ]);
    }

    public function test_api_request_with_invalid_app_key_is_blocked_with_403()
    {
        $response = $this->withHeaders(['X-App-Key' => 'wrong_invalid_secret_key_123456'])
                         ->getJson('/api/v1/wilayah/provinsi');

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Akses Ditolak: Kunci otorisasi aplikasi ASKLIN tidak sah atau belum disertakan.',
                 ]);
    }

    public function test_api_request_with_valid_app_key_header_is_allowed()
    {
        $validKey = config('app.mobile_app_key');

        $response = $this->withHeaders(['X-App-Key' => $validKey])
                         ->getJson('/api/v1/wilayah/provinsi');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ]);
    }

    public function test_api_request_with_valid_app_key_query_param_is_allowed()
    {
        $validKey = config('app.mobile_app_key');

        $response = $this->withHeaders(['X-App-Key' => ''])
                         ->getJson('/api/v1/wilayah/provinsi?app_key=' . $validKey);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ]);
    }
}
