<?php

namespace App\Http\Controllers\Backend\ruang_pengurus;

use App\Http\Controllers\Controller;
use App\Models\MasterKetuaUmum;
use App\Models\SkPengurus;
use App\Models\SkPengurusLog;
use App\Models\StrukturOrganisasi;
use App\Models\StrukturPengurus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SkApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:sk-pengurus-list', ['only' => ['index', 'list', 'show']]);
    }

    /**
     * Halaman Utama Pengelolaan & Approval Surat Keputusan (SK)
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Hitung statistik sesuai kewenangan user
        $queryBase = SkPengurus::query()->forCurrentUser();

        $stats = [
            'total' => (clone $queryBase)->count(),
            'terbit' => (clone $queryBase)->where('sk_pengurus.status', 'terbit')->count(),
            'menunggu_daerah' => (clone $queryBase)->where('sk_pengurus.status', 'menunggu_daerah')->count(),
            'menunggu_sekjen' => (clone $queryBase)->where('sk_pengurus.status', 'menunggu_sekjen')->count(),
            'menunggu_ketum' => (clone $queryBase)->where('sk_pengurus.status', 'menunggu_ketum')->count(),
        ];

        // Tentukan jumlah pengajuan yang memerlukan tindakan pengguna saat ini
        $pendingActionCount = 0;
        if ($user->hasRole('Admin Daerah')) {
            $pendingActionCount = $stats['menunggu_daerah'];
        } elseif ($user->hasRole('Sekjen')) {
            $pendingActionCount = $stats['menunggu_sekjen'];
        } elseif ($user->hasRole('Ketua Umum')) {
            $pendingActionCount = $stats['menunggu_ketum'];
        } elseif ($user->hasRole(['Superadmin', 'Admin Pusat'])) {
            $pendingActionCount = $stats['menunggu_sekjen'] + $stats['menunggu_ketum'] + $stats['menunggu_daerah'];
        }

        return view('backend.sk_pengurus.index', compact('stats', 'pendingActionCount'));
    }

    /**
     * AJAX DataTables list SK Pengurus
     */
    public function list(Request $request)
    {
        $user = Auth::user();

        $query = SkPengurus::query()
            ->select('sk_pengurus.*')
            ->with(['strukturOrganisasi.provinsi', 'strukturOrganisasi.kota', 'ketuaUmum'])
            ->forCurrentUser();

        // Filter tab status
        if ($request->has('tab') && !empty($request->tab)) {
            if ($request->tab === 'menunggu_saya') {
                if ($user->hasRole('Admin Daerah')) {
                    $query->where('sk_pengurus.status', 'menunggu_daerah');
                } elseif ($user->hasRole('Sekjen')) {
                    $query->where('sk_pengurus.status', 'menunggu_sekjen');
                } elseif ($user->hasRole('Ketua Umum')) {
                    $query->where('sk_pengurus.status', 'menunggu_ketum');
                } else {
                    $query->whereIn('sk_pengurus.status', ['menunggu_daerah', 'menunggu_sekjen', 'menunggu_ketum']);
                }
            } elseif ($request->tab === 'terbit') {
                $query->where('sk_pengurus.status', 'terbit');
            }
        }

        if ($request->has('tingkatan') && !empty($request->tingkatan)) {
            $query->where('sk_pengurus.tingkatan', $request->tingkatan);
        }

        return DataTables::of($query)
            ->addColumn('no_sk_display', function ($row) {
                return $row->no_sk ?: '<span class="text-muted italic">(Belum Dinomori)</span>';
            })
            ->addColumn('nama_struktur', function ($row) {
                return $row->strukturOrganisasi ? $row->strukturOrganisasi->nama_struktur : '-';
            })
            ->addColumn('wilayah', function ($row) {
                if (!$row->strukturOrganisasi) return '-';
                $prov = $row->strukturOrganisasi->provinsi->name ?? '';
                $kota = $row->strukturOrganisasi->kota->name ?? '';
                return $kota ? $kota . ' (' . $prov . ')' : $prov;
            })
            ->addColumn('tingkatan_badge', function ($row) {
                $badges = [
                    'pusat' => 'badge-danger',
                    'daerah' => 'badge-primary',
                    'cabang' => 'badge-info',
                ];
                return '<span class="badge ' . ($badges[$row->tingkatan] ?? 'badge-secondary') . ' uppercase">' . $row->tingkatan . '</span>';
            })
            ->editColumn('status', function ($row) {
                $statusMap = [
                    'draft' => ['badge-secondary', 'Draft Pengajuan'],
                    'menunggu_daerah' => ['badge-info', 'Menunggu Daerah'],
                    'menunggu_sekjen' => ['badge-primary', 'Menunggu Sekjen'],
                    'menunggu_ketum' => ['badge-warning', 'Menunggu Ketum'],
                    'terbit' => ['badge-success', 'SK Terbit & Sah'],
                    'dicabut' => ['badge-danger', 'Dicabut / Ditolak'],
                ];

                $info = $statusMap[$row->status] ?? ['badge-secondary', ucfirst($row->status)];
                return '<span class="badge ' . $info[0] . '">' . $info[1] . '</span>';
            })
            ->addColumn('action', function ($row) {
                $showUrl = route('sk_pengurus.show', $row->id);
                $btn = '<a href="' . $showUrl . '" class="btn btn-sm btn-info" title="Periksa / Detail"><i class="fas fa-eye"></i> Periksa</a>';

                if ($row->status === 'terbit') {
                    $printUrl = route('sk_pengurus.print', $row->id);
                    $btn .= ' <a href="' . $printUrl . '" target="_blank" class="btn btn-sm btn-success" title="Cetak Dokumen Resmi PDF"><i class="fas fa-file-pdf"></i> Cetak SK</a>';
                }

                return '<div class="btn-group" role="group">' . $btn . '</div>';
            })
            ->rawColumns(['no_sk_display', 'tingkatan_badge', 'status', 'action'])
            ->make(true);
    }

    /**
     * Halaman Detail & Ruang Kerja Persetujuan SK
     */
    public function show($id)
    {
        $sk = SkPengurus::with([
            'strukturOrganisasi.provinsi',
            'strukturOrganisasi.kota',
            'strukturOrganisasi.tingkatanPengurus',
            'strukturOrganisasi.kelompokPengurus',
            'ketuaUmum',
            'logs.user'
        ])->findOrFail($id);

        if (!$sk->strukturOrganisasi || !$sk->strukturOrganisasi->isAccessibleByCurrentUser()) {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang pada SK wilayah ini.');
        }

        $struktur = $sk->strukturOrganisasi;
        $pengurus = StrukturPengurus::where('id_struktur_organisasi', $struktur->id)
            ->whereNull('parent_id')
            ->orderBy('urutan', 'asc')
            ->get();

        $semuaPengurus = StrukturPengurus::where('id_struktur_organisasi', $struktur->id)
            ->orderBy('urutan', 'asc')
            ->get();

        $ketumAktif = MasterKetuaUmum::getAktif();

        // Usulkan nomor SK baru jika belum dinomori
        $suggestedNoSk = $sk->no_sk ?: $this->generateNomorSk($sk->tingkatan, $sk->tgl_penetapan ?: now());

        return view('backend.sk_pengurus.show', compact('sk', 'struktur', 'pengurus', 'semuaPengurus', 'ketumAktif', 'suggestedNoSk'));
    }

    /**
     * Aksi Persetujuan Tingkat Pengurus Daerah (Rekomendasikan / Revisi / Tolak)
     */
    public function verifikasiDaerah(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->hasRole(['Admin Daerah', 'Superadmin'])) {
            return response()->json(['success' => false, 'message' => 'Hanya Pengurus Daerah yang berhak memproses tahap ini!'], 403);
        }

        $sk = SkPengurus::with('strukturOrganisasi')->findOrFail($id);
        $struktur = $sk->strukturOrganisasi;

        if (!$struktur->isAccessibleByCurrentUser()) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki wewenang pada cabang ini!'], 403);
        }

        $request->validate([
            'action' => 'required|in:rekomendasi,revisi,tolak',
            'no_rekomendasi_daerah' => 'nullable|string|max:100',
            'tgl_rekomendasi_daerah' => 'nullable|date',
            'catatan' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            if ($request->action === 'rekomendasi') {
                $sk->status = 'menunggu_sekjen';
                $sk->no_rekomendasi_daerah = $request->no_rekomendasi_daerah ?: $sk->no_rekomendasi_daerah;
                $sk->tgl_rekomendasi_daerah = $request->tgl_rekomendasi_daerah ?: now();
                $sk->save();

                $struktur->status = 'diajukan_pusat';
                $struktur->diverifikasi_daerah_at = now();
                $struktur->diverifikasi_daerah_by = $user->id;
                $struktur->save();

                SkPengurusLog::record(
                    $struktur->id,
                    'rekomendasi_daerah',
                    'Admin Daerah',
                    $user->id,
                    $request->catatan ?: 'Rekomendasi disetujui oleh Pengurus Daerah dan diteruskan ke Pengurus Pusat.',
                    $sk->id
                );

                $msg = 'Pengajuan cabang berhasil diverifikasi dan diteruskan ke Pengurus Pusat!';
            } elseif ($request->action === 'revisi') {
                $sk->status = 'draft';
                $sk->save();

                $struktur->status = 'revisi';
                $struktur->is_locked = false; // Buka kunci pengeditan
                $struktur->catatan_revisi = $request->catatan ?: 'Perlu perbaikan susunan pengurus oleh cabang.';
                $struktur->save();

                SkPengurusLog::record(
                    $struktur->id,
                    'revisi_daerah',
                    'Admin Daerah',
                    $user->id,
                    $request->catatan,
                    $sk->id
                );

                $msg = 'Pengajuan dikembalikan ke Cabang untuk perbaikan.';
            } else {
                $sk->status = 'dicabut';
                $sk->save();

                $struktur->status = 'ditolak';
                $struktur->catatan_revisi = $request->catatan ?: 'Pengajuan ditolak oleh Pengurus Daerah.';
                $struktur->save();

                SkPengurusLog::record(
                    $struktur->id,
                    'tolak_daerah',
                    'Admin Daerah',
                    $user->id,
                    $request->catatan,
                    $sk->id
                );

                $msg = 'Pengajuan cabang telah ditolak.';
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => $msg]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Aksi Persiapan Draf & Penomoran oleh Sekjen PP
     */
    public function draftSekjen(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->hasRole(['Sekjen', 'Admin Pusat', 'Superadmin'])) {
            return response()->json(['success' => false, 'message' => 'Hanya Sekretaris Jenderal / Admin Pusat yang berhak memproses tahap ini!'], 403);
        }

        $sk = SkPengurus::with('strukturOrganisasi')->findOrFail($id);
        $struktur = $sk->strukturOrganisasi;

        $request->validate([
            'action' => 'required|in:teruskan_ketum,minta_revisi',
            'no_sk' => 'required_if:action,teruskan_ketum|nullable|string|max:100',
            'tgl_penetapan' => 'required_if:action,teruskan_ketum|nullable|date',
            'tgl_pelantikan' => 'nullable|date',
            'kota_penetapan' => 'nullable|string|max:100',
            'show_qr' => 'nullable|in:0,1',
            'catatan' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            if ($request->action === 'teruskan_ketum') {
                $sk->no_sk = $request->no_sk;
                $sk->tgl_penetapan = $request->tgl_penetapan ?: now();
                $sk->tgl_pelantikan = $request->tgl_pelantikan;
                $sk->kota_penetapan = $request->kota_penetapan ?: 'Jakarta';
                $sk->show_qr = (bool) $request->input('show_qr', 1);
                $sk->status = 'menunggu_ketum';
                $sk->save();

                $struktur->status = 'menunggu_ketum';
                $struktur->save();

                SkPengurusLog::record(
                    $struktur->id,
                    'draft_sekjen',
                    'Sekjen',
                    $user->id,
                    $request->catatan ?: 'Draft SK telah dinomori (' . $sk->no_sk . ') dan diteruskan ke Ketua Umum untuk pengesahan.',
                    $sk->id
                );

                $msg = 'Draft SK berhasil disiapkan dan diteruskan ke Ketua Umum!';
            } else {
                $sk->status = 'draft';
                $sk->save();

                $struktur->status = 'revisi';
                $struktur->is_locked = false; // Buka kunci pengeditan
                $struktur->catatan_revisi = $request->catatan ?: 'Perlu perbaikan administrasi oleh pengusul.';
                $struktur->save();

                SkPengurusLog::record(
                    $struktur->id,
                    'revisi_sekjen',
                    'Sekjen',
                    $user->id,
                    $request->catatan,
                    $sk->id
                );

                $msg = 'Pengajuan dikembalikan untuk perbaikan.';
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => $msg]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Aksi Pengesahan Final oleh Ketua Umum PP
     */
    public function approveKetum(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->hasRole(['Ketua Umum', 'Superadmin'])) {
            return response()->json(['success' => false, 'message' => 'Hanya Ketua Umum yang berhak mengesahkan SK ini!'], 403);
        }

        $sk = SkPengurus::with('strukturOrganisasi')->findOrFail($id);
        $struktur = $sk->strukturOrganisasi;

        $request->validate([
            'action' => 'required|in:approve,tolak',
            'catatan' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            if ($request->action === 'approve') {
                $ketumAktif = MasterKetuaUmum::getAktif();
                if (!$ketumAktif) {
                    return response()->json(['success' => false, 'message' => 'Belum ada data Ketua Umum Aktif yang ditetapkan di sistem!'], 422);
                }

                $sk->id_ketua_umum = $ketumAktif->id; // Kunci snapshot Ketum yang menjabat saat ini
                $sk->status = 'terbit';
                $sk->save();

                $struktur->status = 'disetujui';
                $struktur->disetujui_pusat_at = now();
                $struktur->disetujui_pusat_by = $user->id;
                $struktur->save();

                SkPengurusLog::record(
                    $struktur->id,
                    'approve_ketum',
                    'Ketua Umum',
                    $user->id,
                    $request->catatan ?: 'Surat Keputusan resmi telah disetujui dan disahkan oleh Ketua Umum PP ASKLIN.',
                    $sk->id
                );

                $msg = 'Surat Keputusan berhasil disahkan secara resmi oleh Ketua Umum!';
            } else {
                $sk->status = 'draft';
                $sk->save();

                $struktur->status = 'revisi';
                $struktur->is_locked = false;
                $struktur->catatan_revisi = $request->catatan ?: 'Pengajuan dikembalikan oleh Ketua Umum.';
                $struktur->save();

                SkPengurusLog::record(
                    $struktur->id,
                    'tolak_ketum',
                    'Ketua Umum',
                    $user->id,
                    $request->catatan,
                    $sk->id
                );

                $msg = 'Pengajuan dikembalikan untuk perbaikan.';
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => $msg]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Helper pembuatan nomor SK resmi otomatis
     */
    private function generateNomorSk($tingkatan, $date = null)
    {
        $date = $date ? Carbon::parse($date) : now();
        $tahun = $date->format('Y');
        $bulanAngka = (int) $date->format('m');

        $romawiList = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        $bulanRomawi = $romawiList[$bulanAngka] ?? 'I';

        $kodeTingkat = 'PC';
        if ($tingkatan === 'pusat') {
            $kodeTingkat = 'PP';
        } elseif ($tingkatan === 'daerah') {
            $kodeTingkat = 'PD';
        }

        // Hitung nomor urut SK di tahun berjalan
        $count = SkPengurus::whereYear('created_at', $tahun)->whereNotNull('no_sk')->count() + 1;
        $nomorUrut = str_pad($count, 2, '0', STR_PAD_LEFT);

        return "{$nomorUrut}/SK-{$kodeTingkat}/PP-ASKLIN/{$bulanRomawi}/{$tahun}";
    }

    /**
     * Cetak dokumen SK resmi (dilengkapi pada Sesi 5)
     */
    public function print($id)
    {
        $sk = SkPengurus::findOrFail($id);
        return redirect()->route('sk_pengurus.show', $id);
    }
}
