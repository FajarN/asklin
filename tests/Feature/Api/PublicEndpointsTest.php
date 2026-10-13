<?php

namespace Tests\Feature\Api;

use App\Models\Berita;
use App\Models\BeritaKategori;
use App\Models\Event;
use App\Models\EventKategori;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PublicEndpointsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test validasi registrasi akun mobile jika field kosong.
     */
    public function test_register_validation_fails_when_fields_are_missing()
    {
        $response = $this->postJson('/api/v1/auth/register', []);

        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Validasi data gagal.',
                 ])
                 ->assertJsonStructure([
                     'errors' => ['name', 'email', 'password'],
                 ]);
    }

    /**
     * Test registrasi berhasil dan token diterbitkan.
     */
    public function test_register_succeeds_and_issues_token()
    {
        $uniqueEmail = 'klinik_test_' . time() . '_' . rand(100, 999) . '@asklin.org';

        $response = $this->postJson('/api/v1/auth/register', [
            'name'                  => 'dr. Budi Santoso',
            'email'                 => $uniqueEmail,
            'password'              => 'P@ssword123',
            'password_confirmation' => 'P@ssword123',
            'nama_klinik'           => 'Klinik Pratama Sehat Sejahtera',
            'phone'                 => '081234567890',
            'provinsi'              => '31',
            'kota'                  => '3175',
            'device_name'           => 'Flutter Android Emulator',
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Registrasi berhasil. Akun Anda telah siap digunakan.',
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
                             'anggota',
                         ],
                     ],
                 ]);

        // Verifikasi role dan record di DB
        $user = User::where('email', $uniqueEmail)->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('Klinik'));
    }

    /**
     * Test pengambilan daftar berita & artikel publik.
     */
    public function test_berita_index_returns_list_and_categories()
    {
        $kategori = BeritaKategori::firstOrCreate(
            ['nama' => 'Klinik Update Test'],
            ['created_by' => 1]
        );

        $berita = Berita::create([
            'id_kategori' => $kategori->id,
            'judul'       => 'Akreditasi Paripurna Klinik ASKLIN',
            'path'        => 'akreditasi-paripurna-klinik-asklin-' . time(),
            'tanggal'     => now()->toDateString(),
            'konten'      => '<p>Panduan akreditasi klinik ASKLIN terlengkap.</p>',
            'status'      => '1',
        ]);

        $response = $this->getJson('/api/v1/berita');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Daftar berita berhasil diambil.',
                 ])
                 ->assertJsonStructure([
                     'data',
                     'kategori',
                     'meta' => ['current_page', 'last_page', 'per_page', 'total'],
                 ]);
    }

    /**
     * Test detail berita jika ada dan jika tidak ditemukan (404).
     */
    public function test_berita_detail_and_not_found()
    {
        $slug = 'berita-test-' . time();
        $berita = Berita::create([
            'id_kategori' => 1,
            'judul'       => 'Seminar Kesehatan Digital ASKLIN',
            'path'        => $slug,
            'tanggal'     => now()->toDateString(),
            'konten'      => '<p>Pembahasan digitalisasi rekam medis klinik.</p>',
            'status'      => '1',
        ]);

        // Ditemukan berdasarkan slug
        $response = $this->getJson('/api/v1/berita/' . $slug);
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'id'    => $berita->id,
                         'judul' => 'Seminar Kesehatan Digital ASKLIN',
                         'slug'  => $slug,
                     ],
                 ]);

        // 404 jika tidak ditemukan
        $notFound = $this->getJson('/api/v1/berita/slug-pasti-tidak-ada-999');
        $notFound->assertStatus(404)
                 ->assertJson([
                     'success' => false,
                 ]);
    }

    /**
     * Test agenda index dengan filter tab upcoming & past.
     */
    public function test_agenda_index_with_tabs()
    {
        $responseUpcoming = $this->getJson('/api/v1/agenda?tab=upcoming');
        $responseUpcoming->assertStatus(200)
                         ->assertJson([
                             'success'    => true,
                             'active_tab' => 'upcoming',
                         ])
                         ->assertJsonStructure([
                             'data',
                             'kategori',
                             'meta',
                         ]);

        $responsePast = $this->getJson('/api/v1/agenda?tab=past');
        $responsePast->assertStatus(200)
                     ->assertJson([
                         'success'    => true,
                         'active_tab' => 'past',
                     ]);
    }

    /**
     * Test agenda detail dan respons 404 jika tidak ditemukan.
     */
    public function test_agenda_detail_and_not_found()
    {
        // Event yang sudah ada di DB (ID 2)
        $existingEvent = Event::where('status', '1')->first();
        if ($existingEvent) {
            $response = $this->getJson('/api/v1/agenda/' . $existingEvent->path);
            $response->assertStatus(200)
                     ->assertJson([
                         'success' => true,
                     ])
                     ->assertJsonStructure([
                         'data' => [
                             'id',
                             'judul',
                             'slug',
                             'status_pill',
                         ],
                     ]);
        }

        $notFound = $this->getJson('/api/v1/agenda/nonexistent-event-9999');
        $notFound->assertStatus(404)
                 ->assertJson([
                     'success' => false,
                 ]);
    }

    /**
     * Test direktori organisasi tingkat pusat (DPP).
     */
    public function test_organisasi_struktur_pusat()
    {
        $response = $this->getJson('/api/v1/organisasi/struktur?tingkat=pusat');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data'    => [
                         'tingkat' => 'pusat',
                     ],
                 ])
                 ->assertJsonStructure([
                     'data' => [
                         'ringkasan' => ['dpp_count', 'dpd_count', 'dpc_count'],
                         'tingkat',
                         'detail',
                         'pengurus',
                     ],
                 ]);
    }

    /**
     * Test direktori organisasi tingkat daerah (DPD).
     */
    public function test_organisasi_struktur_daerah()
    {
        $response = $this->getJson('/api/v1/organisasi/struktur?tingkat=daerah');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data'    => [
                         'tingkat' => 'daerah',
                     ],
                 ])
                 ->assertJsonStructure([
                     'data' => [
                         'ringkasan',
                         'tingkat',
                         'daftar_dpd',
                     ],
                 ]);
    }

    /**
     * Test direktori organisasi tingkat cabang (DPC).
     */
    public function test_organisasi_struktur_cabang()
    {
        $response = $this->getJson('/api/v1/organisasi/struktur?tingkat=cabang');

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data'    => [
                         'tingkat' => 'cabang',
                     ],
                 ])
                 ->assertJsonStructure([
                     'data' => [
                         'ringkasan',
                         'tingkat',
                         'daftar_dpc',
                     ],
                     'meta' => ['current_page', 'last_page', 'total'],
                 ]);
    }
}
