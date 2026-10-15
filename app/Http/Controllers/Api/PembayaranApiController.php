<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\PembayaranKategori;
use App\Models\Anggota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PembayaranApiController extends Controller
{
    /**
     * Konfigurasi Rekening Resmi ASKLIN
     */
    private function getRekeningResmi(): array
    {
        return [
            'bank' => 'Bank Mandiri',
            'no_rekening' => '123-00-0987654-1',
            'atas_nama' => 'ASOSIASI KLINIK INDONESIA (ASKLIN)',
            'cabang' => 'KCP Jakarta Salemba',
            'nominal_iuran_tahunan' => 1200000,
            'nominal_uang_pangkal' => 500000,
            'petunjuk' => 'Silakan transfer sesuai nominal ke rekening resmi ASKLIN di atas. Harap cantumkan Nama Klinik & No Anggota pada berita transfer, lalu unggah foto bukti transfer di form konfirmasi.',
        ];
    }

    /**
     * GET /api/v1/pembayaran/status
     * Informasi status tagihan, rekening resmi, dan pengajuan terakhir
     */
    public function getStatus(Request $request)
    {
        $user = Auth::user();
        $anggota = Anggota::where('id_user', $user->id)->first();

        $kategori = PembayaranKategori::all()->map(function ($k) {
            return [
                'id' => $k->id,
                'nama' => $k->nama,
            ];
        });

        if (!$anggota) {
            return response()->json([
                'success' => true,
                'is_klinik' => false,
                'message' => 'Akun ini bukan akun klinik terdaftar',
                'rekening' => $this->getRekeningResmi(),
                'kategori' => $kategori,
            ]);
        }

        $latest = Pembayaran::with('kategori')
            ->where('id_anggota', $anggota->id)
            ->latest()
            ->first();

        $latestData = null;
        if ($latest) {
            $latestData = [
                'id' => $latest->id,
                'id_kategori' => $latest->id_kategori,
                'kategori_nama' => $latest->kategori ? $latest->kategori->nama : 'Iuran Anggota',
                'status' => (string) $latest->status,
                'status_label' => $this->getStatusLabel($latest->status),
                'status_badge' => $this->getStatusBadge($latest->status),
                'total' => (float) $latest->total,
                'total_rupiah' => 'Rp ' . number_format($latest->total ?? 0, 0, ',', '.'),
                'tanggal_bayar' => $latest->tanggal_bayar,
                'keterangan' => $latest->keterangan,
                'bukti_url' => $latest->bukti ? asset('images/file/' . $latest->bukti) : null,
                'created_at' => $latest->created_at ? $latest->created_at->format('d-m-Y H:i') : null,
            ];
        }

        return response()->json([
            'success' => true,
            'is_klinik' => true,
            'klinik' => [
                'id' => $anggota->id,
                'nama_klinik' => $anggota->nama_klinik,
                'no_anggota' => $anggota->no_anggota,
                'status_keanggotaan' => $anggota->status,
                'status_pembayaran' => (string) $anggota->status_pembayaran,
                'is_lunas' => (string) $anggota->status_pembayaran === '1',
            ],
            'rekening' => $this->getRekeningResmi(),
            'kategori' => $kategori,
            'latest_payment' => $latestData,
        ]);
    }

    /**
     * GET /api/v1/pembayaran/riwayat
     * Riwayat pembayaran klinik
     */
    public function getRiwayat(Request $request)
    {
        $user = Auth::user();
        $anggota = Anggota::where('id_user', $user->id)->first();

        if (!$anggota) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak terikat dengan profil klinik anggota.',
                'data' => [],
            ], 404);
        }

        $list = Pembayaran::with('kategori')
            ->where('id_anggota', $anggota->id)
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'id_kategori' => $item->id_kategori,
                    'kategori_nama' => $item->kategori ? $item->kategori->nama : 'Iuran Anggota',
                    'status' => (string) $item->status,
                    'status_label' => $this->getStatusLabel($item->status),
                    'status_badge' => $this->getStatusBadge($item->status),
                    'total' => (float) $item->total,
                    'total_rupiah' => 'Rp ' . number_format($item->total ?? 0, 0, ',', '.'),
                    'tanggal_bayar' => $item->tanggal_bayar,
                    'keterangan' => $item->keterangan,
                    'bukti_url' => $item->bukti ? asset('images/file/' . $item->bukti) : null,
                    'created_at' => $item->created_at ? $item->created_at->format('d-m-Y H:i') : null,
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Riwayat pembayaran berhasil diambil.',
            'data' => $list,
        ]);
    }

    /**
     * POST /api/v1/pembayaran/konfirmasi
     * Kirim konfirmasi pembayaran & upload bukti transfer
     */
    public function konfirmasiPembayaran(Request $request)
    {
        $user = Auth::user();
        $anggota = Anggota::where('id_user', $user->id)->first();

        if (!$anggota) {
            return response()->json([
                'success' => false,
                'message' => 'Akun klinik tidak ditemukan.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_kategori'   => 'required|integer',
            'total'         => 'required|numeric|min:1000',
            'tanggal_bayar' => 'nullable|date',
            'keterangan'    => 'nullable|string|max:500',
            'bukti'         => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $destinationPath = public_path('images/file');
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $img_ext = strtolower($request->file('bukti')->getClientOriginalExtension());
        $buktiFilename = 'bukti-pembayaran-' . time() . '-' . uniqid() . '.' . $img_ext;
        $request->file('bukti')->move($destinationPath, $buktiFilename);

        $tanggalBayar = $request->tanggal_bayar ?: date('Y-m-d');

        $pembayaran = Pembayaran::create([
            'id_kategori'   => $request->id_kategori,
            'status'        => '0', // Menunggu Verifikasi Bendahara
            'id_anggota'    => $anggota->id,
            'keterangan'    => $request->keterangan,
            'bukti'         => $buktiFilename,
            'tanggal_bayar' => $tanggalBayar,
            'total'         => $request->total,
        ]);

        // Jika status anggota belum approved/lunas, tandai sudah kirim konfirmasi pembayaran
        if (in_array(strtolower($anggota->status ?? ''), ['waiting', 'create_dokter', 'data tidak sesuai', 'kirim konfirmasi pembayaran'])) {
            $anggota->update([
                'status' => 'kirim konfirmasi pembayaran',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Konfirmasi pembayaran berhasil dikirim dan menunggu validasi bendahara.',
            'data' => [
                'id' => $pembayaran->id,
                'id_kategori' => $pembayaran->id_kategori,
                'total' => (float) $pembayaran->total,
                'status' => '0',
                'status_label' => 'Menunggu Verifikasi',
                'tanggal_bayar' => $tanggalBayar,
                'bukti_url' => asset('images/file/' . $buktiFilename),
            ],
        ], 201);
    }

    /**
     * GET /api/v1/bendahara/pembayaran
     * Daftar pembayaran untuk diverifikasi oleh Bendahara / Pengurus
     */
    public function getBendaharaList(Request $request)
    {
        $user = Auth::user();

        // Otorisasi: Bendahara, Pengurus (Pusat, Cabang, Daerah), atau Superadmin
        $isAuthorized = $user->hasAnyRole(['Bendahara', 'Superadmin', 'Admin Pusat', 'Admin Cabang', 'Admin Daerah', 'Ketua Umum', 'Sekjen']);
        if (!$isAuthorized) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Endpoint ini khusus untuk Bendahara dan Pengurus ASKLIN.',
            ], 403);
        }

        $query = Pembayaran::select(
                'pembayaran.*',
                'pembayaran_kategori.nama as nama_kategori',
                'anggota.nama_klinik',
                'anggota.no_anggota',
                'anggota.nama_pemilik_klinik',
                'anggota.status as status_keanggotaan',
                'anggota.status_pembayaran as status_pembayaran_anggota',
                'indonesia_cities.name as nama_kota',
                'indonesia_provinces.name as nama_provinsi'
            )
            ->join('anggota', 'anggota.id', '=', 'pembayaran.id_anggota')
            ->leftJoin('pembayaran_kategori', 'pembayaran_kategori.id', '=', 'pembayaran.id_kategori')
            ->leftJoin('indonesia_cities', 'indonesia_cities.code', '=', 'anggota.id_kota')
            ->leftJoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'anggota.id_provinsi');

        // Scoping per Wilayah
        if ($user->hasRole('Admin Cabang') && !empty($user->kota)) {
            $query->where('anggota.id_kota', $user->kota);
        } elseif ($user->hasRole('Admin Daerah') && !empty($user->provinsi)) {
            $query->where('anggota.id_provinsi', $user->provinsi);
        }

        // Filter status ('0' = Menunggu, '1' = Lunas, '2' = Ditolak)
        if ($request->filled('status')) {
            $query->where('pembayaran.status', $request->status);
        }

        // Filter kategori
        if ($request->filled('id_kategori')) {
            $query->where('pembayaran.id_kategori', $request->id_kategori);
        }

        // Filter search (nama klinik, no anggota, nama pemilik)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('anggota.nama_klinik', 'LIKE', "%{$search}%")
                  ->orWhere('anggota.no_anggota', 'LIKE', "%{$search}%")
                  ->orWhere('anggota.nama_pemilik_klinik', 'LIKE', "%{$search}%");
            });
        }

        // KPI Ringkasan
        $kpiQuery = clone $query;
        $totalPending = (clone $query)->where('pembayaran.status', '0')->count();
        $totalLunas = (clone $query)->where('pembayaran.status', '1')->count();
        $totalDitolak = (clone $query)->where('pembayaran.status', '2')->count();
        $nominalLunas = (clone $query)->where('pembayaran.status', '1')->sum('pembayaran.total');

        $data = $query->orderBy('pembayaran.created_at', 'DESC')
            ->paginate($request->input('per_page', 20));

        $data->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'id_anggota' => $item->id_anggota,
                'nama_klinik' => $item->nama_klinik,
                'no_anggota' => $item->no_anggota ?: '-',
                'nama_pemilik' => $item->nama_pemilik_klinik ?: '-',
                'lokasi' => ($item->nama_kota ? $item->nama_kota . ', ' : '') . ($item->nama_provinsi ?: '-'),
                'id_kategori' => $item->id_kategori,
                'kategori_nama' => $item->nama_kategori ?: 'Iuran Anggota',
                'status' => (string) $item->status,
                'status_label' => $this->getStatusLabel($item->status),
                'status_badge' => $this->getStatusBadge($item->status),
                'total' => (float) $item->total,
                'total_rupiah' => 'Rp ' . number_format($item->total ?? 0, 0, ',', '.'),
                'tanggal_bayar' => $item->tanggal_bayar,
                'keterangan' => $item->keterangan,
                'bukti_url' => $item->bukti ? asset('images/file/' . $item->bukti) : null,
                'created_at' => $item->created_at ? $item->created_at->format('d-m-Y H:i') : null,
            ];
        });

        return response()->json([
            'success' => true,
            'summary' => [
                'total_pending' => $totalPending,
                'total_lunas' => $totalLunas,
                'total_ditolak' => $totalDitolak,
                'nominal_lunas' => (float) $nominalLunas,
                'nominal_lunas_rupiah' => 'Rp ' . number_format($nominalLunas, 0, ',', '.'),
            ],
            'data' => $data,
        ]);
    }

    /**
     * POST /api/v1/bendahara/pembayaran/{id}/verifikasi
     * Validasi pembayaran oleh Bendahara (Approve / Reject)
     */
    public function verifikasiPembayaran(Request $request, $id)
    {
        $user = Auth::user();

        $isAuthorized = $user->hasAnyRole(['Bendahara', 'Superadmin', 'Admin Pusat', 'Admin Cabang', 'Admin Daerah', 'Ketua Umum', 'Sekjen']);
        if (!$isAuthorized) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Anda tidak memiliki otoritas validasi pembayaran.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'aksi'    => 'required|in:approve,reject',
            'catatan' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter tidak valid',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $pembayaran = Pembayaran::with('anggota')->find($id);
        if (!$pembayaran) {
            return response()->json([
                'success' => false,
                'message' => 'Data pembayaran tidak ditemukan.',
            ], 404);
        }

        // Scope check jika Cabang atau Daerah
        if ($user->hasRole('Admin Cabang') && $user->kota) {
            if ($pembayaran->anggota && $pembayaran->anggota->id_kota != $user->kota) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki hak akses verifikasi faskes di luar cabang Anda.',
                ], 403);
            }
        } elseif ($user->hasRole('Admin Daerah') && $user->provinsi) {
            if ($pembayaran->anggota && $pembayaran->anggota->id_provinsi != $user->provinsi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki hak akses verifikasi faskes di luar daerah Anda.',
                ], 403);
            }
        }

        $aksi = $request->aksi;
        $catatan = trim($request->catatan ?? '');

        if ($aksi === 'approve') {
            $pembayaran->update([
                'status' => '1', // Lunas
                'updated_by' => $user->id,
            ]);

            if ($pembayaran->anggota) {
                $anggotaUpdates = [
                    'status_pembayaran' => '1',
                ];

                // Jika alur pendaftaran menunggu verifikasi bendahara atau pembayaran
                if (in_array(strtolower($pembayaran->anggota->status ?? ''), ['kirim konfirmasi pembayaran', 'verifikasi bendahara'])) {
                    $anggotaUpdates['status'] = 'Verifikasi Sekjen';
                }

                $pembayaran->anggota->update($anggotaUpdates);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil disetujui (Lunas). Status klinik telah diperbarui.',
                'data' => [
                    'id' => $pembayaran->id,
                    'status' => '1',
                    'status_label' => 'Lunas',
                ],
            ]);
        } else {
            // Reject
            $newKeterangan = $pembayaran->keterangan;
            if (!empty($catatan)) {
                $newKeterangan = '[DITOLAK]: ' . $catatan . ($pembayaran->keterangan ? ' | ' . $pembayaran->keterangan : '');
            }

            $pembayaran->update([
                'status' => '2', // Ditolak
                'keterangan' => $newKeterangan,
                'updated_by' => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran telah ditolak dengan catatan verifikator.',
                'data' => [
                    'id' => $pembayaran->id,
                    'status' => '2',
                    'status_label' => 'Ditolak',
                    'catatan' => $catatan,
                ],
            ]);
        }
    }

    private function getStatusLabel($status): string
    {
        switch ((string) $status) {
            case '1':
                return 'Lunas';
            case '2':
                return 'Ditolak';
            case '0':
            default:
                return 'Menunggu Verifikasi';
        }
    }

    private function getStatusBadge($status): array
    {
        switch ((string) $status) {
            case '1':
                return ['text' => 'Lunas', 'color' => 'success', 'bg' => '#10B981'];
            case '2':
                return ['text' => 'Ditolak', 'color' => 'danger', 'bg' => '#EF4444'];
            case '0':
            default:
                return ['text' => 'Menunggu Verifikasi', 'color' => 'warning', 'bg' => '#F59E0B'];
        }
    }
}
