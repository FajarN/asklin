<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->id();
            $table->string('no_anggota')->nullable();
            $table->string('nama_klinik')->nullable();
            $table->enum('status', ['waiting','process','approved','create_dokter','selesai input','data tidak sesuai','ditolak pusat','kirim konfirmasi pembayaran', 'Diverifikasi Cabang', 'Diproses Cabang', 'Terverifikasi Cabang','Diverifikasi Daerah', 'Diproses Daerah', 'Terverifikasi Daerah','Perlu Perbaikan', 'Verifikasi Sekjen'])->default('waiting');
            $table->string('id_provinsi')->nullable();
            $table->string('id_kota')->nullable();
            $table->string('id_kecamatan')->nullable();
            $table->string('id_kelurahan')->nullable();
            $table->string('nama_kontak')->nullable();
            $table->string('email')->nullable();
            $table->string('tlf')->nullable();
            $table->enum('status_pendaftar', ['Pemilik','Penanggunjawab','Pengelola','Pemilik dan Penanggungjawab','Pengelola dan penanggungjawab'])->nullable();
            $table->enum('status_kepemilikan', ['Perorangan', 'Badan Usaha', 'Badan Hukum'])->nullable();
            $table->string('nama_pemilik_klinik')->nullable();
            $table->enum('bentuk_badan_hukum', ['PT', 'Yayasan', 'Koperasi'])->nullable();
            $table->string('nama_badan_usaha')->nullable();
            $table->enum('bentuk_badan_usaha', ['CV'])->nullable();
            $table->enum('jenis_klinik', ['Utama', 'Pratama'])->nullable();
            $table->text('alamat_klinik')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('tlf_klinik')->nullable();
            $table->string('id_user')->nullable();
            $table->string('id_fasilitas')->nullable();
            $table->string('id_layanan')->nullable();
            $table->string('no_ijin')->nullable();
            $table->date('tgl_ijin')->nullable();
            $table->date('tgl_akhir_ijin')->nullable();
            $table->string('fasilitas_klinik')->nullable();
            $table->text('logo')->nullable();
            $table->enum('data_umum', ['0', '1']);
            $table->enum('sdm_klinik', ['0', '1']);
            $table->enum('provider_asuransi', ['0', '1']);
            $table->enum('foto_logo_klinik', ['0', '1']);
            $table->enum('status_pembayaran', ['0', '1'])->default('0');
            $table->text('keterangan')->nullable();
            $table->timestamps('verifikasi_cabang')->nullable();
            $table->timestamps('verifikasi_pusat')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anggota');
    }
};
