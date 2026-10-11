<?php

namespace App\Http\Controllers\Backend\konas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KonasPeriode;
use DataTables;
use Illuminate\Support\Facades\Auth;

class KonasPeriodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:konas-peserta-list|secret', ['only' => ['list']]);
        $this->middleware('permission:konas-peserta-create|secret', ['only' => ['store']]);
        $this->middleware('permission:konas-peserta-edit|secret', ['only' => ['edit', 'toggleStatus']]);
        $this->middleware('permission:konas-peserta-delete|secret', ['only' => ['destroy']]);
    }

    /**
     * Datatables list of Konas Periode
     */
    public function list(Request $request)
    {
        $data = KonasPeriode::withCount(['peserta', 'hotels', 'bookings', 'partners'])
            ->orderBy('tahun_mulai', 'desc')
            ->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('status_badge', function ($row) {
                if ($row->status == '1') {
                    return '<span class="badge badge-success px-3 py-1.5 text-xs font-bold rounded-full"><i class="fas fa-check-circle mr-1"></i> Aktif</span>';
                }
                return '<span class="badge badge-secondary px-3 py-1.5 text-xs font-bold rounded-full"><i class="fas fa-times-circle mr-1"></i> Nonaktif</span>';
            })
            ->addColumn('waktu_pelaksanaan', function ($row) {
                $mulai = $row->tanggal_mulai ? $row->tanggal_mulai->format('d M Y') : '-';
                $selesai = $row->tanggal_selesai ? $row->tanggal_selesai->format('d M Y') : '-';
                return $mulai . ' s/d ' . $selesai;
            })
            ->addColumn('biaya_formatted', function ($row) {
                $val = (int) preg_replace('/[^0-9]/', '', $row->biaya_registrasi ?? '0');
                return 'Rp ' . number_format($val, 0, ',', '.');
            })
            ->addColumn('action', function ($row) {
                $btnToggle = $row->status == '1'
                    ? '<button type="button" class="btn btn-warning btn-sm font-semibold" title="Nonaktifkan Periode" onclick="toggleStatusPeriode(' . $row->id . ', 0)"><i class="fas fa-pause mr-1"></i> Nonaktifkan</button>'
                    : '<button type="button" class="btn btn-success btn-sm font-semibold" title="Aktifkan Periode" onclick="toggleStatusPeriode(' . $row->id . ', 1)"><i class="fas fa-play mr-1"></i> Aktifkan</button>';

                return '
                    <div class="inline-flex items-center gap-1.5" role="group">
                        <button type="button" class="btn btn-info btn-sm font-semibold" title="Edit Periode" onclick="editPeriode(' . $row->id . ')">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </button>
                        ' . $btnToggle . '
                        <button type="button" class="btn btn-danger btn-sm" title="Hapus Periode" onclick="deletePeriode(' . $row->id . ')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                ';
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    /**
     * Store or update a period
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'periode' => 'required|string|max:50',
            'tahun_mulai' => 'required|numeric',
            'tahun_selesai' => 'required|numeric',
            'biaya_registrasi' => 'nullable|string',
        ]);

        $id = $request->id;
        $data = [
            'nama' => $request->nama,
            'periode' => $request->periode,
            'tahun_mulai' => (int) $request->tahun_mulai,
            'tahun_selesai' => (int) $request->tahun_selesai,
            'tema' => $request->tema,
            'subtema' => $request->subtema,
            'tempat' => $request->tempat,
            'tanggal_mulai' => $request->tanggal_mulai ?: null,
            'tanggal_selesai' => $request->tanggal_selesai ?: null,
            'tgl_tutup_daftar' => $request->tgl_tutup_daftar ?: null,
            'biaya_registrasi' => $request->biaya_registrasi ?: '1500000',
            'video_url' => $request->video_url,
            'deskripsi' => $request->deskripsi,
            'sambutan_ketum' => $request->sambutan_ketum,
            'sambutan_panitia' => $request->sambutan_panitia,
            'status' => $request->has('status') ? (string) $request->status : '1',
            'updated_by' => Auth::check() ? Auth::id() : null,
        ];

        // Jika status diset '1' (aktif), kita bisa pastikan periode lain dinonaktifkan jika diinginkan hanya 1 aktif sekaligus
        if ($data['status'] === '1' && $request->boolean('set_single_active', true)) {
            KonasPeriode::where('status', '1')->update(['status' => '0']);
        }

        if (empty($id)) {
            $data['created_by'] = Auth::check() ? Auth::id() : null;
            $periode = KonasPeriode::create($data);
            return response()->json([
                'status' => true,
                'message' => 'Periode KONAS berhasil ditambahkan.',
                'data' => $periode
            ]);
        } else {
            $periode = KonasPeriode::findOrFail($id);
            $periode->update($data);
            return response()->json([
                'status' => true,
                'message' => 'Periode KONAS berhasil diperbarui.',
                'data' => $periode
            ]);
        }
    }

    /**
     * Get single period for editing
     */
    public function edit(Request $request)
    {
        $id = $request->id;
        $data = KonasPeriode::findOrFail($id);
        return response()->json($data);
    }

    /**
     * Toggle active/inactive status
     */
    public function toggleStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status ? '1' : '0';

        $periode = KonasPeriode::findOrFail($id);

        if ($status === '1') {
            // Ketika diaktifkan, jadikan periode ini aktif tunggal
            KonasPeriode::where('status', '1')->update(['status' => '0']);
            $periode->update(['status' => '1', 'updated_by' => Auth::check() ? Auth::id() : null]);
            $msg = "Periode {$periode->nama} ({$periode->periode}) berhasil diaktifkan. Data periode ini sekarang aktif di halaman publik.";
        } else {
            $periode->update(['status' => '0', 'updated_by' => Auth::check() ? Auth::id() : null]);
            $msg = "Periode {$periode->nama} ({$periode->periode}) berhasil dinonaktifkan. Data tidak akan ditampilkan di halaman publik.";
        }

        return response()->json([
            'status' => true,
            'message' => $msg,
            'current_status' => $periode->status
        ]);
    }

    /**
     * Delete period
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $periode = KonasPeriode::findOrFail($id);

        if ($periode->peserta()->count() > 0) {
            return response()->json([
                'status' => false,
                'message' => 'Periode ini memiliki riwayat data peserta dan tidak dapat dihapus demi keamanan arsip.'
            ], 422);
        }

        $periode->delete();
        return response()->json([
            'status' => true,
            'message' => 'Periode KONAS berhasil dihapus.'
        ]);
    }
}

