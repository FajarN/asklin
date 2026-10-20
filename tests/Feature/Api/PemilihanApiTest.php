<?php

namespace Tests\Feature\Api;

use App\Models\Anggota;
use App\Models\Pemilihan;
use App\Models\PemilihanKandidat;
use App\Models\PemilihanSuara;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PemilihanApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Ensure permissions & roles exist
        Role::firstOrCreate(['name' => 'Klinik', 'guard_name' => 'web']);
        $superadmin = Role::firstOrCreate(['name' => 'Superadmin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Admin Cabang', 'guard_name' => 'web']);
        
        $permVote = Permission::firstOrCreate(['name' => 'pemilihan-vote', 'guard_name' => 'web']);
        $permAudit = Permission::firstOrCreate(['name' => 'pemilihan-audit-suara', 'guard_name' => 'web']);
        $permLaporan = Permission::firstOrCreate(['name' => 'pemilihan-laporan', 'guard_name' => 'web']);

        $superadmin->givePermissionTo([$permVote, $permAudit, $permLaporan]);
    }

    private function getAppKeyHeaders(): array
    {
        $validKey = config('app.mobile_app_key');
        return [
            'X-App-Key' => $validKey,
            'Accept' => 'application/json',
        ];
    }

    public function test_klinik_not_approved_cannot_vote()
    {
        $user = User::factory()->create();
        $user->assignRole('Klinik');

        $anggota = Anggota::create([
            'id_user' => $user->id,
            'nama_klinik' => 'Klinik Belum Approve',
            'status' => 'waiting',
            'id_provinsi' => '31',
            'id_kota' => '3171',
        ]);

        $pemilihan = Pemilihan::create([
            'judul' => 'Pemilihan Cabang Test',
            'tingkat' => 'cabang',
            'id_provinsi' => '31',
            'id_kota' => '3171',
            'periode_mulai' => 2023,
            'periode_selesai' => 2028,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_selesai' => now()->addDays(2),
            'status' => 'aktif',
        ]);

        $kandidat = PemilihanKandidat::create([
            'pemilihan_id' => $pemilihan->id,
            'nomor_urut' => 1,
            'nama_calon' => 'Calon A',
            'total_suara' => 0,
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->withHeaders($this->getAppKeyHeaders())
            ->postJson("/api/v1/pemilihan/{$pemilihan->id}/vote", [
                'kandidat_id' => $kandidat->id,
            ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
            ]);

        $this->assertEquals(0, PemilihanSuara::where('pemilihan_id', $pemilihan->id)->count());
    }

    public function test_approved_klinik_can_vote_successfully()
    {
        $user = User::factory()->create();
        $user->assignRole('Klinik');

        $anggota = Anggota::create([
            'id_user' => $user->id,
            'nama_klinik' => 'Klinik Sah Approved',
            'status' => 'approved',
            'id_provinsi' => '31',
            'id_kota' => '3171',
        ]);

        $pemilihan = Pemilihan::create([
            'judul' => 'Pemilihan Cabang Test 2',
            'tingkat' => 'cabang',
            'id_provinsi' => '31',
            'id_kota' => '3171',
            'periode_mulai' => 2023,
            'periode_selesai' => 2028,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_selesai' => now()->addDays(2),
            'status' => 'aktif',
        ]);

        $kandidat = PemilihanKandidat::create([
            'pemilihan_id' => $pemilihan->id,
            'nomor_urut' => 1,
            'nama_calon' => 'Calon B',
            'total_suara' => 0,
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->withHeaders($this->getAppKeyHeaders())
            ->postJson("/api/v1/pemilihan/{$pemilihan->id}/vote", [
                'kandidat_id' => $kandidat->id,
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertEquals(1, PemilihanSuara::where('pemilihan_id', $pemilihan->id)->count());
        $this->assertEquals(1, $kandidat->fresh()->total_suara);
    }

    public function test_klinik_cannot_vote_twice_one_time_vote()
    {
        $user = User::factory()->create();
        $user->assignRole('Klinik');

        $anggota = Anggota::create([
            'id_user' => $user->id,
            'nama_klinik' => 'Klinik Sah Anti Double Vote',
            'status' => 'approved',
            'id_provinsi' => '31',
            'id_kota' => '3171',
        ]);

        $pemilihan = Pemilihan::create([
            'judul' => 'Pemilihan Cabang Test 3',
            'tingkat' => 'cabang',
            'id_provinsi' => '31',
            'id_kota' => '3171',
            'periode_mulai' => 2023,
            'periode_selesai' => 2028,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_selesai' => now()->addDays(2),
            'status' => 'aktif',
        ]);

        $kandidat1 = PemilihanKandidat::create([
            'pemilihan_id' => $pemilihan->id,
            'nomor_urut' => 1,
            'nama_calon' => 'Calon 1',
            'total_suara' => 0,
        ]);

        $kandidat2 = PemilihanKandidat::create([
            'pemilihan_id' => $pemilihan->id,
            'nomor_urut' => 2,
            'nama_calon' => 'Calon 2',
            'total_suara' => 0,
        ]);

        Sanctum::actingAs($user, ['*']);

        // Vote 1st time
        $res1 = $this->withHeaders($this->getAppKeyHeaders())
            ->postJson("/api/v1/pemilihan/{$pemilihan->id}/vote", [
                'kandidat_id' => $kandidat1->id,
            ]);
        $res1->assertStatus(200);

        // Attempt Vote 2nd time
        $res2 = $this->withHeaders($this->getAppKeyHeaders())
            ->postJson("/api/v1/pemilihan/{$pemilihan->id}/vote", [
                'kandidat_id' => $kandidat2->id,
            ]);
        $res2->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);

        $this->assertEquals(1, PemilihanSuara::where('pemilihan_id', $pemilihan->id)->count());
        $this->assertEquals(1, $kandidat1->fresh()->total_suara);
        $this->assertEquals(0, $kandidat2->fresh()->total_suara);
    }

    public function test_get_pemilihan_aktif_returns_eligibility_and_status()
    {
        $user = User::factory()->create();
        $user->assignRole('Klinik');

        $anggota = Anggota::create([
            'id_user' => $user->id,
            'nama_klinik' => 'Klinik Cek Status',
            'status' => 'approved',
            'id_provinsi' => '32',
            'id_kota' => '3271',
        ]);

        $pemilihan = Pemilihan::create([
            'judul' => 'Pemilihan Cabang Bogor',
            'tingkat' => 'cabang',
            'id_provinsi' => '32',
            'id_kota' => '3271',
            'periode_mulai' => 2023,
            'periode_selesai' => 2028,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_selesai' => now()->addDays(5),
            'status' => 'aktif',
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->withHeaders($this->getAppKeyHeaders())
            ->getJson('/api/v1/pemilihan/aktif');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonFragment([
                'id' => $pemilihan->id,
                'is_eligible' => true,
                'has_voted' => false,
            ]);
    }

    public function test_secret_audit_endpoint_only_accessible_by_superadmin()
    {
        $regularUser = User::factory()->create();
        $regularUser->assignRole('Admin Cabang');

        $superadminUser = User::factory()->create();
        $superadminUser->assignRole('Superadmin');

        $pemilihan = Pemilihan::create([
            'judul' => 'Pemilihan Cabang Audit Test',
            'tingkat' => 'cabang',
            'id_provinsi' => '31',
            'id_kota' => '3171',
            'periode_mulai' => 2023,
            'periode_selesai' => 2028,
            'tanggal_mulai' => now()->subDay(),
            'tanggal_selesai' => now()->addDays(2),
            'status' => 'aktif',
        ]);

        // Regular user access to audit web view -> 403 Forbidden
        $this->actingAs($regularUser)
            ->get(route('pemilihan.audit', $pemilihan->id))
            ->assertStatus(403);

        // Superadmin access to audit web view -> 200 OK
        $this->actingAs($superadminUser)
            ->get(route('pemilihan.audit', $pemilihan->id))
            ->assertStatus(200);
    }
}
