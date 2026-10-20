<?php

namespace App\Http\Controllers\Api;

use App\Models\Pemilihan;
use App\Models\PemilihanKandidat;
use App\Models\PemilihanSuara;
use App\Models\Anggota;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemilihanApiController extends BaseApiController
{
    /**
     * Mengambil daftar agenda pemilihan aktif/relevan untuk klinik yang sedang login.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getPemilihanAktif(Request $request): JsonResponse
    {
        $user = $request->user();
        $anggota = $user->anggota ?: Anggota::where('id_user', $user->id)->first();

        if (!$anggota) {
            return $this->errorResponse('Akun Anda tidak terhubung dengan data klinik / faskes.', 404);
        }

        $isApproved = in_array(strtolower((string) $anggota->status), ['approved', 'approve']);

        // Query active or recently completed elections for the clinic's region
        $pemilihanList = Pemilihan::with(['provinsi', 'kota'])
            ->where(function ($q) use ($anggota) {
                $q->where(function ($qc) use ($anggota) {
                    $qc->where('tingkat', 'cabang')->where('id_kota', $anggota->id_kota);
                })->orWhere(function ($qd) use ($anggota) {
                    $qd->where('tingkat', 'daerah')->where('id_provinsi', $anggota->id_provinsi);
                });
            })
            ->whereIn('status', ['aktif', 'selesai', 'draft'])
            ->orderByRaw("FIELD(status, 'aktif', 'draft', 'selesai')")
            ->latest('tanggal_mulai')
            ->get();

        $data = $pemilihanList->map(function ($p) use ($anggota, $isApproved) {
            $suara = PemilihanSuara::where('pemilihan_id', $p->id)
                ->where('anggota_id', $anggota->id)
                ->first();

            $hasVoted = $suara !== null;

            return [
                'id' => $p->id,
                'judul' => $p->judul,
                'tingkat' => $p->tingkat,
                'wilayah' => $p->wilayah_nama,
                'periode' => $p->periode_text,
                'tanggal_mulai' => $p->tanggal_mulai ? $p->tanggal_mulai->toIso8601String() : null,
                'tanggal_selesai' => $p->tanggal_selesai ? $p->tanggal_selesai->toIso8601String() : null,
                'status' => $p->status,
                'is_berlangsung' => $p->is_berlangsung,
                'hak_suara' => [
                    'is_eligible' => $isApproved,
                    'status_klinik' => $anggota->status,
                    'eligibility_message' => $isApproved
                        ? 'Klinik berhak memberikan suara.'
                        : "Klinik berstatus '{$anggota->status}'. Hanya klinik dengan status 'Approved' yang berhak memilih.",
                    'has_voted' => $hasVoted,
                    'waktu_vote' => $hasVoted ? $suara->waktu_vote->toIso8601String() : null,
                ],
                'total_kandidat' => $p->kandidat()->count(),
            ];
        });

        return $this->successResponse($data, 'Data pemilihan berhasil dimuat.');
    }

    /**
     * Mengambil detail pemilihan beserta daftar kandidat dan status hak suara klinik.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function getDetail(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $anggota = $user->anggota ?: Anggota::where('id_user', $user->id)->first();

        if (!$anggota) {
            return $this->errorResponse('Akun Anda tidak terhubung dengan profil klinik.', 404);
        }

        $pemilihan = Pemilihan::with(['provinsi', 'kota', 'kandidat'])->find($id);
        if (!$pemilihan) {
            return $this->errorResponse('Agenda pemilihan tidak ditemukan.', 404);
        }

        $isApproved = in_array(strtolower((string) $anggota->status), ['approved', 'approve']);

        $suara = PemilihanSuara::where('pemilihan_id', $pemilihan->id)
            ->where('anggota_id', $anggota->id)
            ->first();

        $hasVoted = $suara !== null;

        $kandidatList = $pemilihan->kandidat->map(function ($k) {
            return [
                'id' => $k->id,
                'nomor_urut' => $k->nomor_urut,
                'nama_calon' => $k->nama_calon,
                'gelar' => $k->gelar,
                'nama_lengkap' => $k->nama_lengkap,
                'asal_klinik' => $k->asal_klinik,
                'foto_url' => $k->foto_url,
                'visi' => $k->visi,
                'misi' => $k->misi,
                'program_kerja' => $k->program_kerja,
            ];
        });

        $response = [
            'id' => $pemilihan->id,
            'judul' => $pemilihan->judul,
            'tingkat' => $pemilihan->tingkat,
            'wilayah' => $pemilihan->wilayah_nama,
            'periode' => $pemilihan->periode_text,
            'tanggal_mulai' => $pemilihan->tanggal_mulai ? $pemilihan->tanggal_mulai->toIso8601String() : null,
            'tanggal_selesai' => $pemilihan->tanggal_selesai ? $pemilihan->tanggal_selesai->toIso8601String() : null,
            'status' => $pemilihan->status,
            'is_berlangsung' => $pemilihan->is_berlangsung,
            'deskripsi' => $pemilihan->deskripsi,
            'hak_suara' => [
                'is_eligible' => $isApproved,
                'status_klinik' => $anggota->status,
                'eligibility_message' => $isApproved
                    ? 'Hak suara aktif. Anda berhak memilih 1 calon ketua.'
                    : "Hak suara terkunci. Klinik berstatus '{$anggota->status}'. Syarat hak suara adalah status 'Approved'.",
                'has_voted' => $hasVoted,
                'waktu_vote' => $hasVoted ? $suara->waktu_vote->toIso8601String() : null,
            ],
            'kandidat' => $kandidatList,
        ];

        return $this->successResponse($response, 'Detail pemilihan berhasil dimuat.');
    }

    /**
     * Submit hak suara (One-time click & vote). Suara bersifat permanen dan tidak bisa dibatalkan / diubah.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function submitVote(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        $anggota = $user->anggota ?: Anggota::where('id_user', $user->id)->first();

        if (!$anggota) {
            return $this->errorResponse('Akun tidak terhubung dengan klinik faskes.', 403);
        }

        // 1. Cek status keanggotaan klinik: HANYA APPROVED
        $isApproved = in_array(strtolower((string) $anggota->status), ['approved', 'approve']);
        if (!$isApproved) {
            return $this->errorResponse(
                "Hak suara ditolak: Hanya klinik yang telah berstatus 'Approved' yang berhak memberikan suara. Status klinik Anda saat ini: '{$anggota->status}'.",
                403
            );
        }

        // 2. Ambil agenda pemilihan
        $pemilihan = Pemilihan::find($id);
        if (!$pemilihan) {
            return $this->errorResponse('Agenda pemilihan tidak ditemukan.', 404);
        }

        // 3. Cek kesesuaian wilayah
        if ($pemilihan->tingkat === 'cabang') {
            if ($pemilihan->id_kota != $anggota->id_kota) {
                return $this->errorResponse('Anda tidak terdaftar di wilayah cabang pemilihan ini.', 403);
            }
        } else {
            if ($pemilihan->id_provinsi != $anggota->id_provinsi) {
                return $this->errorResponse('Anda tidak terdaftar di wilayah daerah pemilihan ini.', 403);
            }
        }

        // 4. Cek masa aktif pemilihan
        if ($pemilihan->status !== 'aktif') {
            return $this->errorResponse("Sesi pemilihan tidak sedang aktif (Status: {$pemilihan->status}).", 400);
        }

        $now = now();
        if ($now->lt($pemilihan->tanggal_mulai)) {
            return $this->errorResponse('Sesi pemilihan belum dimulai.', 400);
        }
        if ($now->gt($pemilihan->tanggal_selesai)) {
            return $this->errorResponse('Sesi pemilihan telah berakhir.', 400);
        }

        // 5. Validasi input kandidat_id
        $request->validate([
            'kandidat_id' => 'required|integer',
        ]);

        $kandidatId = $request->input('kandidat_id');
        $kandidat = PemilihanKandidat::where('pemilihan_id', $pemilihan->id)->where('id', $kandidatId)->first();
        if (!$kandidat) {
            return $this->errorResponse('Calon kandidat tidak valid atau bukan peserta pemilihan ini.', 422);
        }

        // 6. Cek One-time Vote: Klinik belum pernah memilih sebelumnya
        $existingVote = PemilihanSuara::where('pemilihan_id', $pemilihan->id)
            ->where('anggota_id', $anggota->id)
            ->first();

        if ($existingVote) {
            return $this->errorResponse(
                'Anda sudah memberikan suara pada agenda pemilihan ini. Sesuai aturan pemilihan, suara bersifat permanen dan tidak dapat diubah.',
                422
            );
        }

        // 7. Atomic transaction penyimpanan suara
        DB::beginTransaction();
        try {
            $suara = PemilihanSuara::create([
                'pemilihan_id' => $pemilihan->id,
                'kandidat_id' => $kandidat->id,
                'anggota_id' => $anggota->id,
                'user_id' => $user->id,
                'waktu_vote' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Counter cache
            $kandidat->increment('total_suara');

            DB::commit();

            return $this->successResponse([
                'waktu_vote' => $suara->waktu_vote->toIso8601String(),
                'nomor_urut' => $kandidat->nomor_urut,
                'nama_calon' => $kandidat->nama_calon,
            ], 'Suara Anda berhasil disimpan secara aman dan permanen (One-time Vote).');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            // Handle unique constraint duplicate entry
            if ($e->getCode() == 23000) {
                return $this->errorResponse(
                    'Anda sudah memberikan suara pada agenda pemilihan ini.',
                    422
                );
            }
            return $this->errorResponse('Gagal menyimpan suara: ' . $e->getMessage(), 500);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Terjadi kesalahan sistem saat memproses suara: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Mengambil hasil rekapitulasi suara (Real count/quick count) secara anonim.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function getHasil(Request $request, $id): JsonResponse
    {
        $pemilihan = Pemilihan::with(['provinsi', 'kota', 'kandidat'])->find($id);
        if (!$pemilihan) {
            return $this->errorResponse('Agenda pemilihan tidak ditemukan.', 404);
        }

        $dptQuery = Anggota::whereIn(DB::raw('LOWER(status)'), ['approved', 'approve']);
        if ($pemilihan->tingkat === 'cabang') {
            $dptQuery->where('id_kota', $pemilihan->id_kota);
        } else {
            $dptQuery->where('id_provinsi', $pemilihan->id_provinsi);
        }

        $totalDpt = $dptQuery->count();
        $totalSuara = PemilihanSuara::where('pemilihan_id', $pemilihan->id)->count();
        $persentasePartisipasi = $totalDpt > 0 ? round(($totalSuara / $totalDpt) * 100, 1) : 0;

        $kandidatList = $pemilihan->kandidat->map(function ($k) use ($totalSuara) {
            $suara = $k->total_suara;
            $persen = $totalSuara > 0 ? round(($suara / $totalSuara) * 100, 1) : 0;
            return [
                'id' => $k->id,
                'nomor_urut' => $k->nomor_urut,
                'nama_calon' => $k->nama_calon,
                'gelar' => $k->gelar,
                'nama_lengkap' => $k->nama_lengkap,
                'asal_klinik' => $k->asal_klinik,
                'foto_url' => $k->foto_url,
                'total_suara' => $suara,
                'persentase' => $persen,
            ];
        });

        $pemenang = $totalSuara > 0 ? $kandidatList->sortByDesc('total_suara')->first() : null;

        return $this->successResponse([
            'pemilihan' => [
                'id' => $pemilihan->id,
                'judul' => $pemilihan->judul,
                'tingkat' => $pemilihan->tingkat,
                'wilayah' => $pemilihan->wilayah_nama,
                'periode' => $pemilihan->periode_text,
                'status' => $pemilihan->status,
            ],
            'statistik' => [
                'total_dpt' => $totalDpt,
                'total_suara' => $totalSuara,
                'belum_memilih' => max(0, $totalDpt - $totalSuara),
                'persentase_partisipasi' => $persentasePartisipasi,
            ],
            'pemenang_terunggul' => $pemenang,
            'kandidat' => $kandidatList->values(),
        ], 'Hasil pemilihan berhasil dimuat.');
    }
}
