<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Tabel Periode & Penyelenggaraan Rakernas
        if (!Schema::hasTable('rakernas_periode')) {
            Schema::create('rakernas_periode', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 255)->comment('Nama resmi acara, misal: Rapat Kerja Nasional I ASKLIN');
                $table->enum('rakernas_ke', ['1', '2'])->default('1')->comment('Penyelenggaraan ke-1 atau ke-2 dalam satu periode');
                $table->string('periode', 50)->default('2023-2028')->comment('Periode kepengurusan misal: 2023-2028');
                $table->integer('tahun')->default(2024);
                $table->string('tema', 255)->nullable()->comment('Tema utama Rakernas');
                $table->string('subtema', 255)->nullable()->comment('Subtema atau slogan');
                $table->string('tempat', 255)->nullable()->comment('Nama hotel / venue penyelenggaraan');
                $table->text('alamat_venue')->nullable()->comment('Alamat lengkap lokasi');
                $table->date('tanggal_mulai')->nullable();
                $table->date('tanggal_selesai')->nullable();
                $table->date('tgl_tutup_daftar')->nullable();
                $table->string('biaya_registrasi', 50)->default('0')->comment('0 jika gratis, atau nominal rupiah');
                $table->string('banner_img', 255)->nullable();
                $table->string('video_url', 255)->nullable();
                $table->string('rundown_file', 255)->nullable();
                $table->text('deskripsi')->nullable();
                $table->text('sambutan_ketum')->nullable();
                $table->enum('status', ['0', '1'])->default('1')->comment('0: Nonaktif, 1: Aktif');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();
            });
        }

        // 2. Tabel Peserta Rakernas (Nama klinik wajib diisi)
        if (!Schema::hasTable('rakernas_peserta')) {
            Schema::create('rakernas_peserta', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_periode')->index();
                $table->string('no_registrasi', 100)->nullable()->unique();
                $table->string('nama_peserta', 255);
                $table->string('nama_klinik', 255)->comment('Nama klinik wajib diisi sebagai data utama');
                $table->string('jabatan', 150)->nullable()->comment('Pimpinan, Dokter, Penanggung Jawab, Pengurus Daerah');
                $table->string('no_hp', 50);
                $table->string('email', 150);
                $table->string('id_provinsi', 50)->nullable();
                $table->string('cabang', 50)->nullable()->comment('Kota/Kabupaten domisili');
                $table->text('alamat_klinik')->nullable();
                $table->text('kebutuhan_khusus')->nullable();
                $table->enum('status_kehadiran', ['Hadir', 'Online', 'Batal'])->default('Hadir');
                $table->enum('status_pembayaran', ['Lunas', 'Pending', 'Gratis'])->default('Gratis');
                $table->string('bukti_bayar', 255)->nullable();
                $table->enum('status', ['0', '1'])->default('1')->comment('0: Dibatalkan, 1: Terverifikasi / Aktif');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();

                $table->foreign('id_periode')->references('id')->on('rakernas_periode')->onDelete('cascade');
            });
        }

        // 3. Tabel Pihak yang Diundang (Kemenkes, BPJS, Gubernur, Bupati, Sponsor)
        if (!Schema::hasTable('rakernas_undangan')) {
            Schema::create('rakernas_undangan', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_periode')->index();
                $table->string('nama', 255)->comment('Nama tokoh/instansi/sponsor');
                $table->string('instansi', 255)->comment('Institusi asal');
                $table->string('jabatan', 255)->nullable();
                $table->enum('kategori', ['kemenkes', 'bpjs', 'pemerintah', 'sponsor', 'narasumber', 'lainnya'])->default('kemenkes');
                $table->string('foto_logo', 255)->nullable();
                $table->enum('status_kehadiran', ['Terkonfirmasi', 'Dalam Konfirmasi', 'Berhalangan'])->default('Dalam Konfirmasi');
                $table->integer('urutan')->default(0);
                $table->timestamps();

                $table->foreign('id_periode')->references('id')->on('rakernas_periode')->onDelete('cascade');
            });
        }

        // 4. Tabel Galeri / Venue / Slider Rakernas
        if (!Schema::hasTable('rakernas_galeri')) {
            Schema::create('rakernas_galeri', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_periode')->index();
                $table->string('judul', 255);
                $table->string('foto', 255);
                $table->enum('kategori', ['slider', 'venue', 'hotel', 'kegiatan'])->default('venue');
                $table->text('deskripsi')->nullable();
                $table->integer('urutan')->default(0);
                $table->timestamps();

                $table->foreign('id_periode')->references('id')->on('rakernas_periode')->onDelete('cascade');
            });
        }

        // Seed data awal jika tabel rakernas_periode baru dibuat
        if (DB::table('rakernas_periode')->count() === 0) {
            $periodeId = DB::table('rakernas_periode')->insertGetId([
                'nama' => 'Rapat Kerja Nasional (RAKERNAS) I ASKLIN',
                'rakernas_ke' => '1',
                'periode' => '2023-2028',
                'tahun' => 2024,
                'tema' => 'Sinergi Penguatan Mutu dan Tata Kelola Klinik Menuju Transformasi Kesehatan Nasional',
                'subtema' => 'Akselerasi Akreditasi Klinik dan Penguatan Pelayanan Kesehatan Primer Bersama Kemenkes & BPJS',
                'tempat' => 'Hotel Grand Sahid Jaya, Jakarta',
                'alamat_venue' => 'Jl. Jenderal Sudirman No. 86, Karet Tengsin, Tanah Abang, Jakarta Pusat',
                'tanggal_mulai' => '2024-06-21',
                'tanggal_selesai' => '2024-06-23',
                'tgl_tutup_daftar' => '2024-06-15',
                'biaya_registrasi' => '0',
                'deskripsi' => 'Rapat Kerja Nasional (RAKERNAS) I ASKLIN merupakan forum strategis evaluasi program kerja dan konsolidasi Pengurus Pusat dengan Pengurus Daerah dan Cabang se-Indonesia untuk memperkuat eksistensi dan mutu klinik swasta.',
                'sambutan_ketum' => 'Selamat datang kepada seluruh pimpinan klinik dan pengurus ASKLIN se-Indonesia pada RAKERNAS I ASKLIN Periode 2023-2028. Mari bersama merumuskan langkah nyata kemajuan klinik Indonesia.',
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Seed sample pihak yang diundang
            DB::table('rakernas_undangan')->insert([
                [
                    'id_periode' => $periodeId,
                    'nama' => 'Kementerian Kesehatan Republik Indonesia',
                    'instansi' => 'Kemenkes RI',
                    'jabatan' => 'Menteri Kesehatan / Dirjen Pelayanan Kesehatan',
                    'kategori' => 'kemenkes',
                    'foto_logo' => null,
                    'status_kehadiran' => 'Terkonfirmasi',
                    'urutan' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id_periode' => $periodeId,
                    'nama' => 'BPJS Kesehatan',
                    'instansi' => 'Direktorat Jaminan Pelayanan Kesehatan BPJS',
                    'jabatan' => 'Direktur Utama BPJS Kesehatan',
                    'kategori' => 'bpjs',
                    'foto_logo' => null,
                    'status_kehadiran' => 'Terkonfirmasi',
                    'urutan' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id_periode' => $periodeId,
                    'nama' => 'Pemerintah Daerah Provinsi',
                    'instansi' => 'Pemerintah Provinsi DKI Jakarta',
                    'jabatan' => 'Gubernur / Dinas Kesehatan Provinsi',
                    'kategori' => 'pemerintah',
                    'foto_logo' => null,
                    'status_kehadiran' => 'Terkonfirmasi',
                    'urutan' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id_periode' => $periodeId,
                    'nama' => 'Mitra Farmasi & Alat Kesehatan',
                    'instansi' => 'Sponsor Platinum ASKLIN',
                    'jabatan' => 'Sponsor & Mitra Strategis',
                    'kategori' => 'sponsor',
                    'foto_logo' => null,
                    'status_kehadiran' => 'Terkonfirmasi',
                    'urutan' => 4,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rakernas_galeri');
        Schema::dropIfExists('rakernas_undangan');
        Schema::dropIfExists('rakernas_peserta');
        Schema::dropIfExists('rakernas_periode');
    }
};

