<?php

namespace Tests\Feature\Api;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AnggotaSecurityApiTest extends TestCase
{
    use DatabaseTransactions;

    public function test_unauthenticated_request_to_profil_returns_401()
    {
        $response = $this->getJson('/api/v1/anggota/profil');

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                 ]);
    }

    public function test_member_can_retrieve_own_profile()
    {
        $user = User::factory()->create([
            'email' => 'klinik_test_' . time() . '@asklin.org',
        ]);

        $anggota = Anggota::create([
            'id_user'            => $user->id,
            'nama_klinik'        => 'Klinik Pratama Sehat Uji Coba',
            'no_anggota'         => '3216010001-99999.1.1',
            'status'             => 'approved',
            'status_pembayaran'  => '1',
            'id_provinsi'        => '32',
            'id_kota'            => '3216',
        ]);

        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/v1/anggota/profil');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'id'          => $anggota->id,
                         'nama_klinik' => 'Klinik Pratama Sehat Uji Coba',
                         'no_anggota'  => '3216010001-99999.1.1',
                     ],
                 ]);
    }

    public function test_unapproved_member_cannot_download_sk()
    {
        $user = User::factory()->create([
            'email' => 'klinik_unapproved_' . time() . '@asklin.org',
        ]);

        Anggota::create([
            'id_user'           => $user->id,
            'nama_klinik'       => 'Klinik Belum Disetujui',
            'status'            => 'waiting',
            'status_pembayaran' => '0',
            'id_provinsi'       => '32',
            'id_kota'           => '3216',
        ]);

        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/v1/dokumen/sk-anggota/download');

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                 ]);
    }

    public function test_unpaid_member_cannot_download_sertifikat()
    {
        $user = User::factory()->create([
            'email' => 'klinik_unpaid_' . time() . '@asklin.org',
        ]);

        Anggota::create([
            'id_user'           => $user->id,
            'nama_klinik'       => 'Klinik Belum Bayar',
            'status'            => 'approved',
            'status_pembayaran' => '0',
            'id_provinsi'       => '32',
            'id_kota'           => '3216',
        ]);

        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/v1/dokumen/sertifikat/download');

        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                 ]);
    }
}

