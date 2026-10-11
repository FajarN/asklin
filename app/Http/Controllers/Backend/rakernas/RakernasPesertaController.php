<?php

namespace App\Http\Controllers\Backend\rakernas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{RakernasPeserta, RakernasPeriode};
use DataTables;
use Illuminate\Support\Str;
use Auth;

class RakernasPesertaController extends Controller
{
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = RakernasPeserta::select('rakernas_peserta.*', 'indonesia_cities.name as kota_name', 'indonesia_provinces.name as provinsi_name')
                ->leftJoin('indonesia_cities', 'indonesia_cities.code', '=', 'rakernas_peserta.cabang')
                ->leftJoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'rakernas_peserta.id_provinsi');

            if ($request->filled('id_periode')) {
                $query->where('rakernas_peserta.id_periode', $request->id_periode);
            }

            if ($request->filled('provinsi')) {
                $query->where('rakernas_peserta.id_provinsi', $request->provinsi);
            }

            if ($request->filled('status_kehadiran')) {
                $query->where('rakernas_peserta.status_kehadiran', $request->status_kehadiran);
            }

            $data = $query->orderBy('rakernas_peserta.id', 'desc')->get();

            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    $search = is_array($request->get('search')) ? ($request->input('search.value') ?? '') : $request->get('search');
                    if (!empty($search)) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($search) {
                            return Str::contains(Str::lower($row['nama_peserta'] ?? ''), Str::lower($search))
                                || Str::contains(Str::lower($row['nama_klinik'] ?? ''), Str::lower($search))
                                || Str::contains(Str::lower($row['no_registrasi'] ?? ''), Str::lower($search))
                                || Str::contains(Str::lower($row['no_hp'] ?? ''), Str::lower($search))
                                || Str::contains(Str::lower($row['email'] ?? ''), Str::lower($search))
                                || Str::contains(Str::lower($row['jabatan'] ?? ''), Str::lower($search));
                        });
                    }
                })
                ->addIndexColumn()
                ->addColumn('peserta_info', function($row){
                    $noReg = $row->no_registrasi ? '<span class="badge badge-light border text-xs font-mono font-bold mr-1.5 px-2.5 py-1">' . e($row->no_registrasi) . '</span>' : '';
                    $jabatan = $row->jabatan ? '<span class="text-slate-500 text-xs font-normal">(' . e($row->jabatan) . ')</span>' : '';
                    return '<div class="space-y-1">
                                <div class="font-bold text-sm text-slate-800 dark:text-white flex items-center flex-wrap gap-1">' . $noReg . e($row->nama_peserta) . ' ' . $jabatan . '</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center flex-wrap gap-3 mt-1">
                                    <span><i class="fas fa-phone-alt mr-1 text-emerald-600"></i>' . e($row->no_hp) . '</span>
                                    <span><i class="fas fa-envelope mr-1 text-blue-500"></i>' . e($row->email) . '</span>
                                </div>
                            </div>';
                })
                ->addColumn('klinik_info', function($row){
                    $lokasi = ($row->kota_name ?? '') ? e($row->kota_name) . ', ' . e($row->provinsi_name ?? '') : ($row->provinsi_name ?? '-');
                    return '<div class="space-y-1">
                                <div class="font-bold text-sm text-emerald-700 dark:text-emerald-400 flex items-center gap-1.5">
                                    <i class="fas fa-clinic-medical text-sm"></i> ' . e($row->nama_klinik) . '
                                </div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1 mt-1">
                                    <i class="fas fa-map-marker-alt text-xs text-red-500"></i> ' . $lokasi . '
                                </div>
                            </div>';
                })
                ->addColumn('kehadiran_badge', function($row){
                    if ($row->status_kehadiran == 'Hadir') {
                        return '<span class="badge badge-success px-3 py-1.5 font-bold text-xs"><i class="fas fa-user-check mr-1.5"></i>Hadir Fisik</span>';
                    } elseif ($row->status_kehadiran == 'Online') {
                        return '<span class="badge badge-info px-3 py-1.5 font-bold text-xs"><i class="fas fa-video mr-1.5"></i>Hadir Online</span>';
                    }
                    return '<span class="badge badge-secondary px-3 py-1.5 font-bold text-xs">Batal</span>';
                })
                ->addColumn('action', function($row){
                    return '
                        <div class="inline-flex items-center gap-1.5">
                            <a href="' . route('rakernas.cetak_kartu', $row->id) . '" target="_blank" class="btn btn-outline-success btn-sm px-2.5 py-1.5" title="Cetak Kartu Tanda Registrasi">
                                <i class="fas fa-print"></i>
                            </a>
                            <button type="button" class="btn btn-outline-info btn-sm px-2.5 py-1.5" onclick="editPeserta('.$row->id.')" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm px-2.5 py-1.5" onclick="deletePeserta('.$row->id.')" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['peserta_info', 'klinik_info', 'kehadiran_badge', 'action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_periode' => 'required',
            'nama_peserta' => 'required|string|max:255',
            'nama_klinik' => 'required|string|max:255',
            'no_hp' => 'required|string|max:50',
            'email' => 'required|email|max:150',
        ]);

        $data = [
            'id_periode' => $request->id_periode,
            'nama_peserta' => $request->nama_peserta,
            'nama_klinik' => $request->nama_klinik,
            'jabatan' => $request->jabatan,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'id_provinsi' => $request->id_provinsi,
            'cabang' => $request->cabang,
            'alamat_klinik' => $request->alamat_klinik,
            'kebutuhan_khusus' => $request->kebutuhan_khusus,
            'status_kehadiran' => $request->status_kehadiran ?? 'Hadir',
            'status_pembayaran' => $request->status_pembayaran ?? 'Gratis',
            'updated_by' => Auth::id(),
        ];

        if ($request->filled('id')) {
            $peserta = RakernasPeserta::findOrFail($request->id);
            $peserta->update($data);
            $msg = 'Data peserta Rakernas berhasil diperbarui.';
        } else {
            $data['created_by'] = Auth::id();
            RakernasPeserta::create($data);
            $msg = 'Pendaftaran peserta Rakernas berhasil ditambahkan.';
        }

        return response()->json(['success' => true, 'message' => $msg]);
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $data = RakernasPeserta::findOrFail($id);
        return response()->json($data);
    }

    public function destroy(Request $request)
    {
        $peserta = RakernasPeserta::findOrFail($request->id);
        $peserta->delete();
        return response()->json(['success' => true, 'message' => 'Peserta Rakernas berhasil dihapus.']);
    }
}

