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
        // 1. Buat tabel konas_periode
        if (!Schema::hasTable('konas_periode')) {
            Schema::create('konas_periode', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 255)->comment('Nama resmi gelaran KONAS, misal: Kongres Nasional II ASKLIN');
                $table->integer('tahun_mulai')->default(2023);
                $table->integer('tahun_selesai')->default(2028);
                $table->string('periode', 50)->comment('Contoh: 2023-2028');
                $table->string('tema', 255)->nullable()->comment('Tema utama Kongres');
                $table->string('subtema', 255)->nullable()->comment('Subtema atau abstraksi tema');
                $table->string('tempat', 255)->nullable()->comment('Lokasi/Tempat pelaksanaan');
                $table->date('tanggal_mulai')->nullable();
                $table->date('tanggal_selesai')->nullable();
                $table->date('tgl_tutup_daftar')->nullable();
                $table->string('biaya_registrasi', 50)->default('1500000');
                $table->string('banner_img', 255)->nullable();
                $table->string('video_url', 255)->nullable();
                $table->string('tor_file', 255)->nullable();
                $table->string('sponsorship_file', 255)->nullable();
                $table->text('deskripsi')->nullable();
                $table->text('sambutan_ketum')->nullable();
                $table->text('sambutan_panitia')->nullable();
                $table->text('sambutan_gubernur')->nullable();
                $table->text('sambutan_ketua_wilayah')->nullable();
                $table->enum('status', ['0', '1'])->default('1')->comment('0: Nonaktif, 1: Aktif');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();
            });
        }

        // 2. Tambah kolom id_periode ke tabel-tabel konas yang sudah ada
        if (Schema::hasTable('konas_peserta') && !Schema::hasColumn('konas_peserta', 'id_periode')) {
            Schema::table('konas_peserta', function (Blueprint $table) {
                $table->unsignedBigInteger('id_periode')->nullable()->after('id')->index();
            });
        }

        if (Schema::hasTable('konas_master_hotel') && !Schema::hasColumn('konas_master_hotel', 'id_periode')) {
            Schema::table('konas_master_hotel', function (Blueprint $table) {
                $table->unsignedBigInteger('id_periode')->nullable()->after('id')->index();
            });
        }

        if (Schema::hasTable('konas_booking_hotel') && !Schema::hasColumn('konas_booking_hotel', 'id_periode')) {
            Schema::table('konas_booking_hotel', function (Blueprint $table) {
                $table->unsignedBigInteger('id_periode')->nullable()->after('id')->index();
            });
        }

        if (Schema::hasTable('konas_penerbangan') && !Schema::hasColumn('konas_penerbangan', 'id_periode')) {
            Schema::table('konas_penerbangan', function (Blueprint $table) {
                $table->unsignedBigInteger('id_periode')->nullable()->after('id')->index();
            });
        }

        if (Schema::hasTable('konas_partner') && !Schema::hasColumn('konas_partner', 'id_periode')) {
            Schema::table('konas_partner', function (Blueprint $table) {
                $table->unsignedBigInteger('id_periode')->nullable()->after('id')->index();
            });
        }

        // 3. Masukkan data periode default (KONAS II ASKLIN 2023-2028) dan relasikan data eksisting
        $existingPeriode = DB::table('konas_periode')->where('periode', '2023-2028')->first();
        if (!$existingPeriode) {
            $periodeId = DB::table('konas_periode')->insertGetId([
                'nama' => 'Kongres Nasional (KONAS) II ASKLIN',
                'tahun_mulai' => 2023,
                'tahun_selesai' => 2028,
                'periode' => '2023-2028',
                'tema' => 'Peran ASKLIN dalam Era Transformasi Industri Wisata Medis',
                'subtema' => 'Peran ASKLIN dalam Menghadapi Tantangan dan Meningkatkan Keunggulan Bersaing',
                'tempat' => 'Lut Tawar Grand Ballroom, Takengon - Aceh Tengah',
                'tanggal_mulai' => '2023-06-14',
                'tanggal_selesai' => '2023-06-17',
                'tgl_tutup_daftar' => '2023-06-07',
                'biaya_registrasi' => '1500000',
                'video_url' => 'https://www.youtube.com/embed/rieNdaumPAI',
                'tor_file' => 'assets/frontend/TERMS_OF_REFERENCE.pdf',
                'sponsorship_file' => 'assets/frontend/SPONSORSHIP_BOOK.pdf',
                'deskripsi' => 'Kongres Nasional ASKLIN merupakan forum tertinggi musyawarah perwakilan klinik se-Indonesia yang diselenggarakan lima tahun sekali untuk menetapkan arah kebijakan strategis organisasi, memilih Ketua Umum & Dewan Pengurus Pusat, serta memperkuat sinergi mutu pelayanan klinik di seluruh Indonesia.',
                'status' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $periodeId = $existingPeriode->id;
        }

        // Hubungkan semua data eksisting yang id_periode nya masih NULL ke periode ini
        if ($periodeId) {
            DB::table('konas_peserta')->whereNull('id_periode')->update(['id_periode' => $periodeId]);
            DB::table('konas_master_hotel')->whereNull('id_periode')->update(['id_periode' => $periodeId]);
            DB::table('konas_booking_hotel')->whereNull('id_periode')->update(['id_periode' => $periodeId]);
            DB::table('konas_penerbangan')->whereNull('id_periode')->update(['id_periode' => $periodeId]);
            DB::table('konas_partner')->whereNull('id_periode')->update(['id_periode' => $periodeId]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('konas_partner') && Schema::hasColumn('konas_partner', 'id_periode')) {
            Schema::table('konas_partner', function (Blueprint $table) {
                $table->dropColumn('id_periode');
            });
        }

        if (Schema::hasTable('konas_penerbangan') && Schema::hasColumn('konas_penerbangan', 'id_periode')) {
            Schema::table('konas_penerbangan', function (Blueprint $table) {
                $table->dropColumn('id_periode');
            });
        }

        if (Schema::hasTable('konas_booking_hotel') && Schema::hasColumn('konas_booking_hotel', 'id_periode')) {
            Schema::table('konas_booking_hotel', function (Blueprint $table) {
                $table->dropColumn('id_periode');
            });
        }

        if (Schema::hasTable('konas_master_hotel') && Schema::hasColumn('konas_master_hotel', 'id_periode')) {
            Schema::table('konas_master_hotel', function (Blueprint $table) {
                $table->dropColumn('id_periode');
            });
        }

        if (Schema::hasTable('konas_peserta') && Schema::hasColumn('konas_peserta', 'id_periode')) {
            Schema::table('konas_peserta', function (Blueprint $table) {
                $table->dropColumn('id_periode');
            });
        }

        Schema::dropIfExists('konas_periode');
    }
};

