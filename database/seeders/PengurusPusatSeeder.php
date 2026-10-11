<?php

namespace Database\Seeders;

use App\Models\MasterKetuaUmum;
use App\Models\SkLama;
use App\Models\StrukturKelompokPengurus;
use App\Models\StrukturOrganisasi;
use App\Models\StrukturPengurus;
use App\Models\TingkatanPengurus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PengurusPusatSeeder extends Seeder
{
    /**
     * Run the database seeds for Pengurus Pusat ASKLIN Periode 2023 - 2028.
     * Sumber data: /docs/contoh sk pengurus pusat/SK PP ASKLIN 2023-2028 FINAL[1].docx
     * SK No: 129/SK-PP/PP-ASKLIN/XI/2023 (18 November 2023)
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {
            // 1. Pastikan Master Tingkatan Pengurus Pusat ada
            $tingkatanPusat = TingkatanPengurus::where('nama_tingkatan', 'LIKE', '%Pusat%')->first();
            if (!$tingkatanPusat) {
                $tingkatanPusat = TingkatanPengurus::firstOrCreate(
                    ['id' => 1],
                    ['nama_tingkatan' => 'Pengurus Pusat']
                );
            }

            // 2. Pastikan 10 Kelompok Bidang terdaftar
            $kelompokData = [
                1 => 'Bidang Organisasi',
                2 => 'Bidang Pengendalian Mutu',
                3 => 'Bidang Pengembangan Sumber Daya Manusia',
                4 => 'Bidang Penelitian dan Pengembangan (Litbang)',
                5 => 'Bidang Kajian Pembiayaan',
                6 => 'Bidang Penanggulangan Bencana',
                7 => 'Bidang Pengabdian Masyarakat',
                8 => 'Bidang Advokasi dan Bantuan Hukum',
                9 => 'Bidang Kerjasama Antar Lembaga',
                10 => 'Bidang Informasi dan Komunikasi',
            ];

            foreach ($kelompokData as $id => $nama) {
                StrukturKelompokPengurus::updateOrCreate(
                    ['id' => $id],
                    [
                        'nama_kelompok' => $nama,
                        'urutan' => $id,
                        'keterangan' => 'Bidang Pengurus Pusat ASKLIN',
                    ]
                );
            }

            // 3. Ambil atau Buat Struktur Organisasi Pengurus Pusat (Terbaru 2023 - 2028)
            $strukturPusat = StrukturOrganisasi::where('id_tingkatan_pengurus', 1)->first();
            if (!$strukturPusat) {
                $strukturPusat = new StrukturOrganisasi();
                $strukturPusat->id_tingkatan_pengurus = 1;
            }

            $strukturPusat->nama_struktur = 'Pengurus Pusat';
            $strukturPusat->id_provinsi = '31'; // DKI Jakarta
            $strukturPusat->id_kota = '3175'; // Jakarta Timur
            $strukturPusat->periode = '2023 - 2028';
            $strukturPusat->tgl_muscab = '2023-06-17'; // Kongres Kedua ASKLIN Takengon 14 - 17 Juni 2023
            $strukturPusat->alamat_sekretariat = 'Jl. Pinang Ranti II no.1A, Makasar, Jakarta Timur, DKI Jakarta';
            $strukturPusat->email_sekretariat = 'pp_asklin@asklin.org';
            $strukturPusat->telp_sekretariat = '0218098928';
            $strukturPusat->status = 'disetujui';
            $strukturPusat->is_locked = false;
            $strukturPusat->save();

            $idStruktur = $strukturPusat->id;

            // 4. HAPUS SEMUA DATA PENGURUS PUSAT LAMA DI DATABASE
            StrukturPengurus::where('id_struktur_organisasi', $idStruktur)->delete();

            // 5. SEED DATA SUSUNAN PENGURUS PUSAT TERBARU (SK 2023 - 2028 FINAL)

            // --- DEWAN PEMBINA ---
            $dewanPembina = [
                'dr. Djamal Abdul Muis, SH.MH.',
                'dr. Kurnia Putra',
            ];
            foreach ($dewanPembina as $idx => $nama) {
                StrukturPengurus::create([
                    'id_struktur_organisasi' => $idStruktur,
                    'jabatan' => 'dewan pembina',
                    'nama_lengkap' => $nama,
                    'urutan' => $idx + 1, // 1 .. 2
                    'status' => 'aktif',
                    'parent_id' => null,
                    'id_kelompok' => null,
                ]);
            }

            // --- DEWAN PENASEHAT ---
            $dewanPenasehat = [
                'Prof. Dr. drg. Habe Hanafi, MBA',
                'Prof. Dr. dr. Guslihan Dasa Tjipta, SpA (K)',
                'Prof. Dr. dr. Starry Rampengan, SpJP (K)',
                'Prof. dr Ascobat Gani, MPH, Dr. PH',
                'Prof. Dr. dr. Maimun Syukri, SpPD (K)',
                'Dr. dr. Julianto Witjaksono, SpOG.MARS',
                'Dr. dr. Prijo Sidipratomo, SpRad (K)',
                'Dr. Emi Nurjasmi, M.Kes',
                'Drs. Nurul Falah Eddy Pariang, Apt',
            ];
            foreach ($dewanPenasehat as $idx => $nama) {
                StrukturPengurus::create([
                    'id_struktur_organisasi' => $idStruktur,
                    'jabatan' => 'dewan penasehat',
                    'nama_lengkap' => $nama,
                    'urutan' => $idx + 3, // 3 .. 11
                    'status' => 'aktif',
                    'parent_id' => null,
                    'id_kelompok' => null,
                ]);
            }

            // --- KETUA UMUM ---
            $ketum = StrukturPengurus::create([
                'id_struktur_organisasi' => $idStruktur,
                'jabatan' => 'ketua umum',
                'nama_lengkap' => 'dr. Eddi Junaidi, SpOG., SH., MKes',
                'urutan' => 12, // setelah Dewan Pembina & Dewan Penasehat
                'status' => 'aktif',
                'parent_id' => null,
                'id_kelompok' => null,
            ]);

            // --- WAKIL KETUA UMUM & KETUA ---
            $wakilKetum = [
                ['jabatan' => 'wakil ketua umum 1', 'nama' => 'dr. Dodi Suardi, SpOG (K), FISQua', 'urutan' => 1],
                ['jabatan' => 'wakil ketua umum 2', 'nama' => 'dr. Mulyana Syarip Panija MARS', 'urutan' => 2],
                ['jabatan' => 'ketua I', 'nama' => "dr. Mas’ud Muhammad, MM, AAK", 'urutan' => 3],
                ['jabatan' => 'ketua II', 'nama' => 'dr. Teuku Yusriadi, Sp.BA', 'urutan' => 4],
                ['jabatan' => 'ketua III', 'nama' => 'Aus Triyono, SE', 'urutan' => 5],
            ];
            foreach ($wakilKetum as $item) {
                StrukturPengurus::create([
                    'id_struktur_organisasi' => $idStruktur,
                    'jabatan' => $item['jabatan'],
                    'nama_lengkap' => $item['nama'],
                    'urutan' => $item['urutan'],
                    'status' => 'aktif',
                    'parent_id' => $ketum->id,
                    'id_kelompok' => null,
                ]);
            }

            // --- SEKRETARIAT JENDERAL ---
            $sekjen = StrukturPengurus::create([
                'id_struktur_organisasi' => $idStruktur,
                'jabatan' => 'sekretaris jenderal',
                'nama_lengkap' => 'dr. Abdul Rasyid Salam',
                'urutan' => 6,
                'status' => 'aktif',
                'parent_id' => $ketum->id,
                'id_kelompok' => null,
            ]);

            $sekretarisSub = [
                ['jabatan' => 'sekretaris I', 'nama' => 'dr. Arif Rizki Taradita', 'urutan' => 1],
                ['jabatan' => 'sekretaris II', 'nama' => 'dr. Muyasir Saifullah', 'urutan' => 2],
                ['jabatan' => 'sekretaris III', 'nama' => 'Oktri Mohammad Firdaus', 'urutan' => 3],
            ];
            foreach ($sekretarisSub as $item) {
                StrukturPengurus::create([
                    'id_struktur_organisasi' => $idStruktur,
                    'jabatan' => $item['jabatan'],
                    'nama_lengkap' => $item['nama'],
                    'urutan' => $item['urutan'],
                    'status' => 'aktif',
                    'parent_id' => $sekjen->id,
                    'id_kelompok' => null,
                ]);
            }

            // --- KEBENDAHARAAN UMUM ---
            $bendum = StrukturPengurus::create([
                'id_struktur_organisasi' => $idStruktur,
                'jabatan' => 'bendahara umum',
                'nama_lengkap' => 'Lena. SE, MARS',
                'urutan' => 7,
                'status' => 'aktif',
                'parent_id' => $ketum->id,
                'id_kelompok' => null,
            ]);

            $bendaharaSub = [
                ['jabatan' => 'wakil bendahara I', 'nama' => 'dr. Wulandari Taradita', 'urutan' => 1],
                ['jabatan' => 'wakil bendahara II', 'nama' => 'Ernawati Cahya, S.K.M., M.M., M.Kes', 'urutan' => 2],
            ];
            foreach ($bendaharaSub as $item) {
                StrukturPengurus::create([
                    'id_struktur_organisasi' => $idStruktur,
                    'jabatan' => $item['jabatan'],
                    'nama_lengkap' => $item['nama'],
                    'urutan' => $item['urutan'],
                    'status' => 'aktif',
                    'parent_id' => $bendum->id,
                    'id_kelompok' => null,
                ]);
            }

            // --- KOORDINATOR DAERAH (9 Wilayah) ---
            $koorDaerah = [
                [
                    'wilayah' => 'SUMATERA UTARA, SUMATERA BARAT',
                    'nama' => 'Dr. dr.Khairani Sukatendel, M.Ked.(OG).,Sp.OG (K).,SH.,M.H.(Kes.)',
                    'urutan' => 1,
                ],
                [
                    'wilayah' => 'RIAU, JAMBI',
                    'nama' => 'dr. Nuzelly Husnedi, MARS',
                    'urutan' => 2,
                ],
                [
                    'wilayah' => 'KEPULAUAN RIAU, BANGKA BELITUNG',
                    'nama' => 'dr. Djamaris Munte',
                    'urutan' => 3,
                ],
                [
                    'wilayah' => 'LAMPUNG, PALEMBANG, BENGKULU',
                    'nama' => 'dr. Hernowo AW, MKes, SpKKLP',
                    'urutan' => 4,
                ],
                [
                    'wilayah' => 'DAERAH ISTIMEWA YOGYAKARTA',
                    'nama' => 'drg. Madi Saputra, Sp.Pros',
                    'urutan' => 5,
                ],
                [
                    'wilayah' => 'JAWA TENGAH, KALIMANTAN BARAT, KALIMANTAN TENGAH',
                    'nama' => 'dr. Fuad Alhamidy.MKes',
                    'urutan' => 6,
                ],
                [
                    'wilayah' => 'JAWA TIMUR, KALIMANTAN SELATAN, KALIMANTAN TIMUR, KALIMANTAN UTARA',
                    'nama' => 'dr. Eddy Santoso, S.Si',
                    'urutan' => 7,
                ],
                [
                    'wilayah' => 'SULAWESI, MALUKU UTARA, MALUKU, PAPUA, PAPUA BARAT',
                    'nama' => 'dr. Kasmawati T.Z. Basalamah, MHA',
                    'urutan' => 8,
                ],
                [
                    'wilayah' => 'NUSA TENGGARA BARAT,NUSA TENGGARA TIMUR, BALI',
                    'nama' => 'dr. H. Hasan Amin, SpRad',
                    'urutan' => 9,
                ],
            ];
            foreach ($koorDaerah as $item) {
                StrukturPengurus::create([
                    'id_struktur_organisasi' => $idStruktur,
                    'jabatan' => 'koordinator daerah',
                    'nama_lengkap' => $item['nama'],
                    'keterangan' => $item['wilayah'],
                    'urutan' => $item['urutan'],
                    'status' => 'aktif',
                    'parent_id' => $ketum->id,
                    'id_kelompok' => null,
                ]);
            }

            // --- 10 BIDANG-BIDANG ---
            $bidangList = [
                [
                    'kelompok_id' => 1,
                    'nama_bidang' => 'Bidang Organisasi',
                    'koordinator' => 'Apt. Drs Adlis Rahman, MM',
                    'anggota' => [
                        'dr. Kote Noordhianta, Sp.T.H.T.K.L,M.Kes',
                        'Asep Sardi, SKM',
                        'dr. Pitje Dwijana',
                        'drg. Sonny Hermawati Margo., M.Kes',
                    ],
                ],
                [
                    'kelompok_id' => 2,
                    'nama_bidang' => 'Bidang Pengendalian Mutu',
                    'koordinator' => 'dr. Ari Waluyo, SpOG,(K), MARS',
                    'anggota' => [
                        'dr. Entis Sutisna',
                        'drg. Shinta Kesuma Dewi, MARS',
                        'dr. Sri Wahjuni',
                    ],
                ],
                [
                    'kelompok_id' => 3,
                    'nama_bidang' => 'Bidang Pengembangan Sumber Daya Manusia',
                    'koordinator' => 'dr. Rahmat Fitriadi, M.Kes. MH',
                    'anggota' => [
                        'dr. Jimmy Permana HS',
                        'dr. Aditia , SpOG',
                        'Mutiara Rizka Ahmad, S.ST.,MARS',
                    ],
                ],
                [
                    'kelompok_id' => 4,
                    'nama_bidang' => 'Bidang Penelitian dan Pengembangan (Litbang)',
                    'koordinator' => 'dr. Ede Surya Darmawan, SKM.,M.DM',
                    'anggota' => [
                        'dr. Nia Reviana, MAPS',
                        'dr. Andi Ardjuna Sakti, SH, MPH',
                        'dr. Yut  Savithri, MARS',
                    ],
                ],
                [
                    'kelompok_id' => 5,
                    'nama_bidang' => 'Bidang Kajian Pembiayaan',
                    'koordinator' => 'dr. Sri Raharjo, SH, MH, MARS',
                    'anggota' => [
                        'dr. Yose Waluyo, SpKFR',
                        'Erni Asfiati Hasibuan, S.Keb, M.Kes',
                        'Kosasih, SE, M.AK',
                    ],
                ],
                [
                    'kelompok_id' => 6,
                    'nama_bidang' => 'Bidang Penanggulangan Bencana',
                    'koordinator' => 'Budiman, SKM. M.Kes',
                    'anggota' => [
                        'dr. Wisnu, Sp.An',
                        'dr. David Idrial, SpOT, FICS',
                        'dr. Guntur Utama Putera, SpOT',
                        'Wayan Pancayasa, S.Kp. Ns, MKM',
                    ],
                ],
                [
                    'kelompok_id' => 7,
                    'nama_bidang' => 'Bidang Pengabdian Masyarakat',
                    'koordinator' => 'dr. Cain Sugiyanto',
                    'anggota' => [
                        'Samsul Bahri Talib',
                        'dr. Sumiati',
                        'Nunung Surwatini, SE, S.Keb',
                        'Novianti, S.Ked, MM',
                        'dr. Ahmad Syahlani, MARS',
                    ],
                ],
                [
                    'kelompok_id' => 8,
                    'nama_bidang' => 'Bidang Advokasi dan Bantuan Hukum',
                    'koordinator' => 'Ahyar Gayo, SH, MH',
                    'anggota' => [
                        'Fayadh, SH',
                        'Lulu Fotuana, SH',
                        'dr. Ida Hariyanti M.Epid',
                        'Zulfadhly, A.Md. Kep',
                    ],
                ],
                [
                    'kelompok_id' => 9,
                    'nama_bidang' => 'Bidang Kerjasama Antar Lembaga',
                    'koordinator' => 'Muhajir, Skep, NERS, MMR',
                    'anggota' => [
                        'dr. Noor Arida Sofiana, MBA, MKes',
                        'dr. Alamsyah, M.Kes',
                        'dr. Sugeng Sumardjo',
                    ],
                ],
                [
                    'kelompok_id' => 10,
                    'nama_bidang' => 'Bidang Informasi dan Komunikasi',
                    'koordinator' => 'Muhammad Hasyim, S.Kom',
                    'anggota' => [
                        'dr. Ani Yustiani',
                        'dr. Intan Primarrytha Agmalia',
                        'dr. Ammar Zayn',
                    ],
                ],
            ];

            foreach ($bidangList as $bIndex => $bidang) {
                // Koordinator Bidang (anak langsung Ketua Umum)
                $koorBidang = StrukturPengurus::create([
                    'id_struktur_organisasi' => $idStruktur,
                    'id_kelompok' => $bidang['kelompok_id'],
                    'jabatan' => 'koordinator bidang',
                    'nama_lengkap' => $bidang['koordinator'],
                    'keterangan' => $bidang['nama_bidang'],
                    'urutan' => $bIndex + 1,
                    'status' => 'aktif',
                    'parent_id' => $ketum->id,
                ]);

                // Anggota-anggota Bidang (anak dari Koordinator Bidang)
                foreach ($bidang['anggota'] as $aIndex => $namaAnggota) {
                    StrukturPengurus::create([
                        'id_struktur_organisasi' => $idStruktur,
                        'id_kelompok' => $bidang['kelompok_id'],
                        'jabatan' => 'anggota bidang',
                        'nama_lengkap' => $namaAnggota,
                        'keterangan' => $bidang['nama_bidang'],
                        'urutan' => $aIndex + 1,
                        'status' => 'aktif',
                        'parent_id' => $koorBidang->id,
                    ]);
                }
            }

            // 6. Update Master Ketua Umum Aktif
            MasterKetuaUmum::updateOrCreate(
                ['nama_lengkap' => 'dr. Eddi Junaidi, SpOG., SH., MKes'],
                [
                    'periode_mulai' => '2023',
                    'periode_selesai' => '2028',
                    'status' => 'aktif',
                    'keterangan' => 'Ketua Umum Pengurus Pusat ASKLIN Periode 2023 - 2028',
                ]
            );

            // 7. Simpan Dokumen SK PP 2023-2028 ke History SK (sk_lama)
            $sourceFile = base_path('docs/contoh sk pengurus pusat/SK PP ASKLIN 2023-2028 FINAL[1].docx');
            if (File::exists($sourceFile)) {
                Storage::disk('public')->makeDirectory('sk_lama');
                $targetFileName = 'SK_PP_ASKLIN_2023_2028_FINAL.docx';
                $destPath = storage_path('app/public/sk_lama/' . $targetFileName);
                File::copy($sourceFile, $destPath);

                SkLama::updateOrCreate(
                    [
                        'no_sk' => '129/SK-PP/PP-ASKLIN/XI/2023',
                    ],
                    [
                        'judul' => 'Pengesahan dan Pengangkatan Pengurus Pusat ASKLIN Periode 2023 - 2028',
                        'tingkatan' => 'pusat',
                        'periode' => '2023 - 2028',
                        'tahun_mulai' => 2023,
                        'tahun_selesai' => 2028,
                        'tgl_penetapan' => '2023-11-18',
                        'file_sk' => $targetFileName,
                        'file_nama_asli' => 'SK PP ASKLIN 2023-2028 FINAL.docx',
                        'file_ekstensi' => 'docx',
                        'file_size' => File::size($sourceFile),
                        'keterangan' => 'SK Resmi Pengurus Pusat ASKLIN Periode 2023 - 2028 hasil Kongres Kedua ASKLIN Takengon, Aceh Tengah.',
                    ]
                );
            }

            DB::commit();
            $this->command->info('BERHASIL: Seluruh data Pengurus Pusat ASKLIN Periode 2023 - 2028 berhasil di-seed ke database!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('GAGAL seed Pengurus Pusat: ' . $e->getMessage());
            throw $e;
        }
    }
}
