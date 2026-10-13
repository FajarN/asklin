<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_fails_with_invalid_credentials()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => 'nonexistent@asklin.org',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Kombinasi email dan password tidak sesuai.',
                 ]);
    }

    public function test_login_validation_fails_when_fields_are_missing()
    {
        $response = $this->postJson('/api/v1/auth/login', []);

        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Validasi data gagal.',
                 ])
                 ->assertJsonStructure([
                     'errors' => ['email', 'password'],
                 ]);
    }

    public function test_login_succeeds_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email'    => 'test_api_user_' . time() . '@asklin.org',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email'       => $user->email,
            'password'    => 'password123',
            'device_name' => 'test_android_device',
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Login berhasil.',
                 ])
                 ->assertJsonStructure([
                     'data' => [
                         'token',
                         'token_type',
                         'user' => [
                             'id',
                             'name',
                             'email',
                             'primary_role',
                             'roles',
                             'wilayah',
                         ],
                     ],
                 ]);
    }

    public function test_unauthenticated_request_to_me_returns_401()
    {
        $response = $this->getJson('/api/v1/auth/me');

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                 ]);
    }

    public function test_authenticated_user_can_access_me_and_logout()
    {
        $user = User::factory()->create([
            'email'    => 'test_auth_me_' . time() . '@asklin.org',
            'password' => Hash::make('password123'),
        ]);

        $token = $user->createToken('test_token')->plainTextToken;

        // Test GET /api/v1/auth/me
        $meResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                           ->getJson('/api/v1/auth/me');

        $meResponse->assertStatus(200)
                   ->assertJson([
                       'success' => true,
                       'data' => [
                           'id'    => $user->id,
                           'email' => $user->email,
                       ],
                   ]);

        // Test POST /api/v1/auth/logout
        $logoutResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                               ->postJson('/api/v1/auth/logout');

        $logoutResponse->assertStatus(200)
                       ->assertJson([
                           'success' => true,
                       ]);

        // Verify token is deleted
        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $user->id,
            'name'         => 'test_token',
        ]);
    }
}

