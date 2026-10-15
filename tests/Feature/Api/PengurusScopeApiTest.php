<?php

namespace Tests\Feature\Api;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PengurusScopeApiTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'Admin Cabang', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Admin Daerah', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Admin Pusat', 'guard_name' => 'web']);
    }

    public function test_unauthenticated_request_to_dashboard_returns_401()
    {
        $response = $this->getJson('/api/v1/pengurus/dashboard');

        $response->assertStatus(401)
                 ->assertJson([
                     'success' => false,
                 ]);
    }

    public function test_admin_cabang_can_access_dashboard_and_is_scoped_to_city()
    {
        $adminCabang = User::factory()->create([
            'email'    => 'admin_cabang_bekasi_' . time() . '@asklin.org',
            'provinsi' => '32',
            'kota'     => '3216', // Kab. Bekasi
        ]);
        $adminCabang->assignRole('Admin Cabang');

        $token = $adminCabang->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/v1/pengurus/dashboard');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'scope' => [
                             'level'              => 'cabang',
                             'kota_id'            => '3216',
                             'is_locked_kota'     => true,
                             'is_locked_provinsi' => true,
                         ],
                     ],
                 ]);
    }

    public function test_admin_cabang_cannot_access_anggota_detail_from_another_city()
    {
        // Admin Cabang Bekasi (3216)
        $adminBekasi = User::factory()->create([
            'email'    => 'admin_bekasi_' . time() . '@asklin.org',
            'provinsi' => '32',
            'kota'     => '3216',
        ]);
        $adminBekasi->assignRole('Admin Cabang');

        // Klinik di Kota Bandung (3273)
        $klinikBandung = Anggota::create([
            'nama_klinik'       => 'Klinik Kota Bandung',
            'status'            => 'approved',
            'status_pembayaran' => '1',
            'id_provinsi'       => '32',
            'id_kota'           => '3273', // Kota Bandung
        ]);

        $token = $adminBekasi->createToken('test_token')->plainTextToken;

        // Mencoba mengakses detail klinik di luar kotanya
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/v1/pengurus/anggota/' . $klinikBandung->id);

        // Harus dicegah dengan status 403 Forbidden!
        $response->assertStatus(403)
                 ->assertJson([
                     'success' => false,
                 ]);
    }

    public function test_admin_cabang_can_access_anggota_detail_within_own_city()
    {
        // Admin Cabang Bekasi (3216)
        $adminBekasi = User::factory()->create([
            'email'    => 'admin_bekasi_valid_' . time() . '@asklin.org',
            'provinsi' => '32',
            'kota'     => '3216',
        ]);
        $adminBekasi->assignRole('Admin Cabang');

        // Klinik di Kab Bekasi (3216)
        $klinikBekasi = Anggota::create([
            'nama_klinik'       => 'Klinik Sahabat Bekasi',
            'status'            => 'approved',
            'status_pembayaran' => '1',
            'id_provinsi'       => '32',
            'id_kota'           => '3216',
        ]);

        $token = $adminBekasi->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->getJson('/api/v1/pengurus/anggota/' . $klinikBekasi->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'id'          => $klinikBekasi->id,
                         'nama_klinik' => 'Klinik Sahabat Bekasi',
                     ],
                 ]);
    }

    public function test_admin_cabang_can_verify_clinic_in_own_city()
    {
        $adminBekasi = User::factory()->create([
            'email'    => 'admin_verify_' . time() . '@asklin.org',
            'provinsi' => '32',
            'kota'     => '3216',
        ]);
        $adminBekasi->assignRole('Admin Cabang');

        $klinik = Anggota::create([
            'nama_klinik'       => 'Klinik Baru Menunggu Cabang',
            'status'            => 'waiting',
            'id_provinsi'       => '32',
            'id_kota'           => '3216',
        ]);

        $token = $adminBekasi->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/v1/pengurus/verifikasi/' . $klinik->id, [
                             'status'     => 'Terverifikasi Cabang',
                             'keterangan' => 'Berkas klinik lengkap dan valid',
                         ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'status'     => 'Terverifikasi Cabang',
                         'keterangan' => 'Berkas klinik lengkap dan valid',
                     ],
                 ]);

        $this->assertDatabaseHas('anggota', [
            'id'     => $klinik->id,
            'status' => 'Terverifikasi Cabang',
        ]);
    }

    public function test_superadmin_can_approve_clinic_with_generated_no_anggota()
    {
        $superadmin = User::factory()->create([
            'email' => 'super_verify_' . time() . '@asklin.org',
        ]);
        $superadmin->assignRole('Superadmin');

        $klinik = Anggota::create([
            'nama_klinik'  => 'Klinik Final Approval',
            'status'       => 'Terverifikasi Cabang',
            'jenis_klinik' => 'Pratama',
            'id_provinsi'  => '32',
            'id_kota'      => '3216',
            'id_kelurahan' => '3216023002',
        ]);

        $token = $superadmin->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->postJson('/api/v1/pengurus/verifikasi/' . $klinik->id, [
                             'status'     => 'approved',
                             'keterangan' => 'Disetujui Pengurus Pusat',
                         ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'status' => 'approved',
                     ],
                 ]);

        $updated = Anggota::find($klinik->id);
        $this->assertEquals('approved', $updated->status);
        $this->assertNotEmpty($updated->no_anggota);
        $this->assertNotNull($updated->verifikasi_pusat);
    }
}

