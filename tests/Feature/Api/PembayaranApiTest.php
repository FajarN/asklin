<?php

namespace Tests\Feature\Api;

use App\Models\Anggota;
use App\Models\Pembayaran;
use App\Models\PembayaranKategori;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PembayaranApiTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'Bendahara', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Klinik', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Admin Cabang', 'guard_name' => 'web']);

        PembayaranKategori::firstOrCreate(
            ['id' => 1],
            ['nama' => 'Uang Iuran', 'status' => '1']
        );
    }

    public function test_unauthenticated_request_returns_401()
    {
        $response = $this->getJson('/api/v1/pembayaran/status');
        $response->assertStatus(401);
    }

    public function test_clinic_can_get_dues_status_and_bank_info()
    {
        $user = User::factory()->create([
            'email' => 'klinik_iuran_' . time() . '@asklin.org',
        ]);
        $user->assignRole('Klinik');

        $anggota = Anggota::create([
            'id_user' => $user->id,
            'nama_klinik' => 'Klinik Pratama Sehat Selalu',
            'no_anggota' => '32.73.01.0001',
            'status' => 'waiting',
            'status_pembayaran' => '0',
            'id_provinsi' => '32',
            'id_kota' => '3273',
        ]);

        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/pembayaran/status');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'is_klinik' => true,
                'klinik' => [
                    'nama_klinik' => 'Klinik Pratama Sehat Selalu',
                    'status_pembayaran' => '0',
                    'is_lunas' => false,
                ],
                'rekening' => [
                    'bank' => 'Bank Mandiri',
                    'atas_nama' => 'ASOSIASI KLINIK INDONESIA (ASKLIN)',
                ],
            ]);
    }

    public function test_clinic_can_submit_payment_confirmation()
    {
        $user = User::factory()->create([
            'email' => 'klinik_pay_' . time() . '@asklin.org',
        ]);
        $user->assignRole('Klinik');

        $anggota = Anggota::create([
            'id_user' => $user->id,
            'nama_klinik' => 'Klinik Sejahtera',
            'status' => 'waiting',
            'status_pembayaran' => '0',
            'id_provinsi' => '32',
            'id_kota' => '3273',
        ]);

        $token = $user->createToken('test_token')->plainTextToken;

        Storage::fake('public');
        $fakeFile = UploadedFile::fake()->image('bukti_transfer.jpg', 600, 600);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/pembayaran/konfirmasi', [
                'id_kategori' => 1,
                'total' => 1200000,
                'tanggal_bayar' => '2026-10-15',
                'keterangan' => 'Iuran tahunan 2026 Klinik Sejahtera',
                'bukti' => $fakeFile,
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id_kategori' => 1,
                    'total' => 1200000,
                    'status' => '0',
                    'status_label' => 'Menunggu Verifikasi',
                ],
            ]);

        $this->assertDatabaseHas('pembayaran', [
            'id_anggota' => $anggota->id,
            'id_kategori' => 1,
            'total' => 1200000,
            'status' => '0',
        ]);
    }

    public function test_clinic_can_view_payment_history()
    {
        $user = User::factory()->create([
            'email' => 'klinik_hist_' . time() . '@asklin.org',
        ]);
        $user->assignRole('Klinik');

        $anggota = Anggota::create([
            'id_user' => $user->id,
            'nama_klinik' => 'Klinik Medika Utama',
            'status' => 'waiting',
            'status_pembayaran' => '0',
        ]);

        Pembayaran::create([
            'id_anggota' => $anggota->id,
            'id_kategori' => 1,
            'total' => 500000,
            'status' => '1',
            'tanggal_bayar' => '2026-01-10',
            'bukti' => 'dummy_bukti.jpg',
        ]);

        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/pembayaran/riwayat');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonFragment([
                'total' => 500000,
                'status' => '1',
                'status_label' => 'Lunas',
            ]);
    }

    public function test_regular_user_cannot_access_bendahara_endpoints()
    {
        $user = User::factory()->create();
        $user->assignRole('Klinik');

        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/bendahara/pembayaran');

        $response->assertStatus(403);
    }

    public function test_bendahara_can_list_payments_and_see_summary()
    {
        $bendahara = User::factory()->create([
            'email' => 'bendahara_' . time() . '@asklin.org',
        ]);
        $bendahara->assignRole('Bendahara');

        $token = $bendahara->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/v1/bendahara/pembayaran');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'summary' => [
                    'total_pending',
                    'total_lunas',
                    'total_ditolak',
                    'nominal_lunas',
                ],
                'data' => [
                    'data',
                ],
            ]);
    }

    public function test_bendahara_can_approve_payment()
    {
        $bendahara = User::factory()->create([
            'email' => 'bendahara_appr_' . time() . '@asklin.org',
        ]);
        $bendahara->assignRole('Bendahara');

        $klinikUser = User::factory()->create();
        $anggota = Anggota::create([
            'id_user' => $klinikUser->id,
            'nama_klinik' => 'Klinik Amanah',
            'status' => 'kirim konfirmasi pembayaran',
            'status_pembayaran' => '0',
        ]);

        $pembayaran = Pembayaran::create([
            'id_anggota' => $anggota->id,
            'id_kategori' => 1,
            'total' => 1200000,
            'status' => '0',
            'tanggal_bayar' => '2026-10-15',
            'bukti' => 'bukti_test.jpg',
        ]);

        $token = $bendahara->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/bendahara/pembayaran/' . $pembayaran->id . '/verifikasi', [
                'aksi' => 'approve',
                'catatan' => 'Transfer terverifikasi di mutasi rekening Mandiri',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $pembayaran->id,
                    'status' => '1',
                    'status_label' => 'Lunas',
                ],
            ]);

        $this->assertEquals('1', $pembayaran->fresh()->status);
        $this->assertEquals('1', $anggota->fresh()->status_pembayaran);
    }

    public function test_bendahara_can_reject_payment()
    {
        $bendahara = User::factory()->create([
            'email' => 'bendahara_rej_' . time() . '@asklin.org',
        ]);
        $bendahara->assignRole('Bendahara');

        $klinikUser = User::factory()->create();
        $anggota = Anggota::create([
            'id_user' => $klinikUser->id,
            'nama_klinik' => 'Klinik Kurang Bayar',
            'status' => 'kirim konfirmasi pembayaran',
            'status_pembayaran' => '0',
        ]);

        $pembayaran = Pembayaran::create([
            'id_anggota' => $anggota->id,
            'id_kategori' => 1,
            'total' => 100000,
            'status' => '0',
            'tanggal_bayar' => '2026-10-15',
            'bukti' => 'bukti_kurang.jpg',
        ]);

        $token = $bendahara->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/v1/bendahara/pembayaran/' . $pembayaran->id . '/verifikasi', [
                'aksi' => 'reject',
                'catatan' => 'Nominal transfer kurang dari tagihan resmi tahunan',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $pembayaran->id,
                    'status' => '2',
                    'status_label' => 'Ditolak',
                ],
            ]);

        $this->assertEquals('2', $pembayaran->fresh()->status);
        $this->assertStringContainsString('Nominal transfer kurang', $pembayaran->fresh()->keterangan);
    }
}
