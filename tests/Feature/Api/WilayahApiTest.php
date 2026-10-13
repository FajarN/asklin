<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class WilayahApiTest extends TestCase
{
    public function test_get_provinces_returns_valid_json()
    {
        $response = $this->getJson('/api/v1/wilayah/provinsi');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ])
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['code', 'name'],
                     ],
                 ]);
    }

    public function test_get_cities_returns_valid_json()
    {
        $response = $this->getJson('/api/v1/wilayah/kota?provinsi_code=32');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ])
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['code', 'name', 'province_code'],
                     ],
                 ]);
    }
}
