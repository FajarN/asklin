<?php

namespace App\Http\Controllers\Backend\pemilihan;

use App\Http\Controllers\Controller;
use App\Models\Pemilihan;
use App\Models\PemilihanKandidat;
use App\Models\PemilihanSuara;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;
use Yajra\DataTables\Facades\DataTables;

class PemilihanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:pemilihan-list', ['only' => ['index', 'list', 'show']]);
        $this->middleware('permission:pemilihan-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:pemilihan-edit', ['only' => ['edit', 'update', 'toggleStatus']]);
        $this->middleware('permission:pemilihan-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('backend.pemilihan.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            $query = Pemilihan::with(['provinsi', 'kota', 'kandidat', 'suara'])->forUser($user)->latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('wilayah', function ($row) {
                    if ($row->tingkat === 'cabang') {
                        $kotaName = $row->kota ? $row->kota->name : ($row->id_kota ?? '-');
                        return '<span class="badge badge-info bg-cyan-600 text-white px-2 py-0.5 rounded text-[11px]">Cabang</span><br><span class="font-medium">' . e($kotaName) . '</span>';
                    } else {
                        $provName = $row->provinsi ? $row->provinsi->name : ($row->id_provinsi ?? '-');
                        return '<span class="badge badge-primary bg-indigo-600 text-white px-2 py-0.5 rounded text-[11px]">Daerah</span><br><span class="font-medium">' . e($provName) . '</span>';
                    }
                })
                ->addColumn('periode', function ($row) {
                    return e($row->periode_text);
                })
                ->addColumn('jadwal', function ($row) {
                    return '<div class="text-[11px] leading-tight">' .
                        '<div><i class="far fa-clock text-emerald-500 mr-1"></i>' . $row->tanggal_mulai->format('d M Y H:i') . '</div>' .
                        '<div><i class="far fa-clock text-rose-500 mr-1"></i>' . $row->tanggal_selesai->format('d M Y H:i') . '</div>' .
                        '</div>';
                })
                ->addColumn('kandidat_count', function ($row) {
                    return '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">' .
                        $row->kandidat->count() . ' Calon</span>';
                })
                ->addColumn('status_badge', function ($row) {
                    $badges = [
                        'draft' => 'bg-slate-200 text-slate-700',
                        'aktif' => 'bg-emerald-100 text-emerald-800 border border-emerald-300',
                        'selesai' => 'bg-blue-100 text-blue-800',
                        'ditutup' => 'bg-rose-100 text-rose-800',
                    ];
                    $cls = $badges[$row->status] ?? 'bg-slate-100 text-slate-600';
                    return '<span class="px-2 py-1 rounded-md text-xs font-bold uppercase ' . $cls . '">' . e($row->status) . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="flex items-center gap-1.5">';
                    $btn .= '<a href="' . route('pemilihan.show', $row->id) . '" class="btn btn-xs btn-outline-info text-cyan-600 hover:text-cyan-700" title="Detail"><i class="fas fa-eye"></i></a>';

                    if (Auth::user()->can('pemilihan-edit')) {
                        $btn .= '<a href="' . route('pemilihan.edit', $row->id) . '" class="btn btn-xs btn-outline-warning text-amber-600 hover:text-amber-700" title="Edit"><i class="fas fa-edit"></i></a>';
                    }

                    if (Auth::user()->can('pemilihan-delete')) {
                        $btn .= '<button type="button" onclick="deletePemilihan(' . $row->id . ')" class="btn btn-xs btn-outline-danger text-rose-600 hover:text-rose-700" title="Hapus"><i class="fas fa-trash"></i></button>';
                    }
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['wilayah', 'jadwal', 'kandidat_count', 'status_badge', 'action'])
                ->make(true);
        }
    }

    public function create()
    {
        $user = Auth::user();
        $isCabang = $user->hasRole('Admin Cabang');
        $isDaerah = $user->hasRole('Admin Daerah');

        $provinces = Province::orderBy('name', 'asc')->get();
        $userCity = null;
        $userProvince = null;

        if ($isCabang && $user->kota) {
            $userCity = City::where('code', $user->kota)->first();
            if ($userCity) {
                $userProvince = Province::where('code', $userCity->province_code)->first();
            }
        } elseif ($isDaerah && $user->provinsi) {
            $userProvince = Province::where('code', $user->provinsi)->first();
        }

        return view('backend.pemilihan.create', compact('provinces', 'isCabang', 'isDaerah', 'userCity', 'userProvince'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'judul' => 'required|string|max:255',
            'periode_mulai' => 'required|integer|min:2000|max:2100',
            'periode_selesai' => 'required|integer|gte:periode_mulai|max:2100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status' => 'required|in:draft,aktif,selesai,ditutup',
            'deskripsi' => 'nullable|string',
            'kandidat' => 'required|array|min:1',
            'kandidat.*.nama_calon' => 'required|string|max:255',
            'kandidat.*.nomor_urut' => 'required|integer|min:1',
            'kandidat.*.gelar' => 'nullable|string|max:100',
            'kandidat.*.asal_klinik' => 'nullable|string|max:255',
            'kandidat.*.visi' => 'nullable|string',
            'kandidat.*.misi' => 'nullable|string',
            'kandidat.*.program_kerja' => 'nullable|string',
            'kandidat.*.foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        // Scope validation
        if ($user->hasRole('Admin Cabang')) {
            $tingkat = 'cabang';
            $idKota = $user->kota;
            $city = City::where('code', $idKota)->first();
            $idProvinsi = $city ? $city->province_code : $user->provinsi;
        } elseif ($user->hasRole('Admin Daerah')) {
            $tingkat = 'daerah';
            $idProvinsi = $user->provinsi;
            $idKota = null;
        } else {
            $rules['tingkat'] = 'required|in:cabang,daerah';
            $rules['id_provinsi'] = 'required|string';
            if ($request->input('tingkat') === 'cabang') {
                $rules['id_kota'] = 'required|string';
            }
            $tingkat = $request->input('tingkat');
            $idProvinsi = $request->input('id_provinsi');
            $idKota = $tingkat === 'cabang' ? $request->input('id_kota') : null;
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            $pemilihan = Pemilihan::create([
                'judul' => $validated['judul'],
                'tingkat' => $tingkat,
                'id_provinsi' => $idProvinsi,
                'id_kota' => $idKota,
                'periode_mulai' => $validated['periode_mulai'],
                'periode_selesai' => $validated['periode_selesai'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'status' => $validated['status'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            // Ensure destination upload folder exists
            $uploadPath = public_path('images/pemilihan');
            if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true, true);
            }

            foreach ($request->input('kandidat') as $index => $kandData) {
                $fotoName = null;
                if ($request->hasFile("kandidat.{$index}.foto")) {
                    $file = $request->file("kandidat.{$index}.foto");
                    $fotoName = 'kandidat_' . $pemilihan->id . '_' . $kandData['nomor_urut'] . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move($uploadPath, $fotoName);
                }

                PemilihanKandidat::create([
                    'pemilihan_id' => $pemilihan->id,
                    'nomor_urut' => $kandData['nomor_urut'],
                    'nama_calon' => $kandData['nama_calon'],
                    'gelar' => $kandData['gelar'] ?? null,
                    'asal_klinik' => $kandData['asal_klinik'] ?? null,
                    'foto' => $fotoName,
                    'visi' => $kandData['visi'] ?? null,
                    'misi' => $kandData['misi'] ?? null,
                    'program_kerja' => $kandData['program_kerja'] ?? null,
                    'total_suara' => 0,
                ]);
            }

            DB::commit();
            return redirect()->route('pemilihan.index')->with('success', 'Agenda Pemilihan berhasil dibuat!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal membuat pemilihan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        $pemilihan = Pemilihan::with(['provinsi', 'kota', 'kandidat', 'suara'])->forUser($user)->findOrFail($id);

        // Calculate DPT (Daftar Pemilih Tetap klinik approve)
        $dptQuery = Anggota::whereIn(DB::raw('LOWER(status)'), ['approved', 'approve']);
        if ($pemilihan->tingkat === 'cabang') {
            $dptQuery->where('id_kota', $pemilihan->id_kota);
        } else {
            $dptQuery->where('id_provinsi', $pemilihan->id_provinsi);
        }
        $totalDpt = $dptQuery->count();
        $totalSuaraMasuk = $pemilihan->suara()->count();
        $persentasePartisipasi = $totalDpt > 0 ? round(($totalSuaraMasuk / $totalDpt) * 100, 1) : 0;

        return view('backend.pemilihan.show', compact('pemilihan', 'totalDpt', 'totalSuaraMasuk', 'persentasePartisipasi'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $pemilihan = Pemilihan::with(['provinsi', 'kota', 'kandidat'])->forUser($user)->findOrFail($id);

        $isCabang = $user->hasRole('Admin Cabang');
        $isDaerah = $user->hasRole('Admin Daerah');

        $provinces = Province::orderBy('name', 'asc')->get();
        $cities = $pemilihan->id_provinsi ? City::where('province_code', $pemilihan->id_provinsi)->orderBy('name', 'asc')->get() : collect();

        return view('backend.pemilihan.edit', compact('pemilihan', 'provinces', 'cities', 'isCabang', 'isDaerah'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $pemilihan = Pemilihan::forUser($user)->findOrFail($id);

        $rules = [
            'judul' => 'required|string|max:255',
            'periode_mulai' => 'required|integer|min:2000|max:2100',
            'periode_selesai' => 'required|integer|gte:periode_mulai|max:2100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status' => 'required|in:draft,aktif,selesai,ditutup',
            'deskripsi' => 'nullable|string',
            'kandidat' => 'required|array|min:1',
            'kandidat.*.nama_calon' => 'required|string|max:255',
            'kandidat.*.nomor_urut' => 'required|integer|min:1',
            'kandidat.*.gelar' => 'nullable|string|max:100',
            'kandidat.*.asal_klinik' => 'nullable|string|max:255',
            'kandidat.*.visi' => 'nullable|string',
            'kandidat.*.misi' => 'nullable|string',
            'kandidat.*.program_kerja' => 'nullable|string',
            'kandidat.*.foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];

        if ($user->hasRole('Admin Cabang') || $user->hasRole('Admin Daerah')) {
            // Cannot change territory
        } else {
            $rules['tingkat'] = 'required|in:cabang,daerah';
            $rules['id_provinsi'] = 'required|string';
            if ($request->input('tingkat') === 'cabang') {
                $rules['id_kota'] = 'required|string';
            }
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            $updateData = [
                'judul' => $validated['judul'],
                'periode_mulai' => $validated['periode_mulai'],
                'periode_selesai' => $validated['periode_selesai'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'status' => $validated['status'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'updated_by' => $user->id,
            ];

            if (!$user->hasRole('Admin Cabang') && !$user->hasRole('Admin Daerah')) {
                $updateData['tingkat'] = $validated['tingkat'];
                $updateData['id_provinsi'] = $validated['id_provinsi'];
                $updateData['id_kota'] = $validated['tingkat'] === 'cabang' ? $validated['id_kota'] : null;
            }

            $pemilihan->update($updateData);

            $uploadPath = public_path('images/pemilihan');
            if (!File::isDirectory($uploadPath)) {
                File::makeDirectory($uploadPath, 0777, true, true);
            }

            // Keep track of updated kandidat IDs
            $processedKandidatIds = [];

            foreach ($request->input('kandidat') as $index => $kandData) {
                $kandidatId = $kandData['id'] ?? null;
                $kandidat = $kandidatId ? PemilihanKandidat::where('pemilihan_id', $pemilihan->id)->find($kandidatId) : null;

                $fotoName = $kandidat ? $kandidat->foto : null;
                if ($request->hasFile("kandidat.{$index}.foto")) {
                    $file = $request->file("kandidat.{$index}.foto");
                    $fotoName = 'kandidat_' . $pemilihan->id . '_' . $kandData['nomor_urut'] . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move($uploadPath, $fotoName);
                }

                if ($kandidat) {
                    $kandidat->update([
                        'nomor_urut' => $kandData['nomor_urut'],
                        'nama_calon' => $kandData['nama_calon'],
                        'gelar' => $kandData['gelar'] ?? null,
                        'asal_klinik' => $kandData['asal_klinik'] ?? null,
                        'foto' => $fotoName,
                        'visi' => $kandData['visi'] ?? null,
                        'misi' => $kandData['misi'] ?? null,
                        'program_kerja' => $kandData['program_kerja'] ?? null,
                    ]);
                    $processedKandidatIds[] = $kandidat->id;
                } else {
                    $newKandidat = PemilihanKandidat::create([
                        'pemilihan_id' => $pemilihan->id,
                        'nomor_urut' => $kandData['nomor_urut'],
                        'nama_calon' => $kandData['nama_calon'],
                        'gelar' => $kandData['gelar'] ?? null,
                        'asal_klinik' => $kandData['asal_klinik'] ?? null,
                        'foto' => $fotoName,
                        'visi' => $kandData['visi'] ?? null,
                        'misi' => $kandData['misi'] ?? null,
                        'program_kerja' => $kandData['program_kerja'] ?? null,
                        'total_suara' => 0,
                    ]);
                    $processedKandidatIds[] = $newKandidat->id;
                }
            }

            // Remove candidates that were removed by user (only if no suara linked yet)
            $toDelete = PemilihanKandidat::where('pemilihan_id', $pemilihan->id)
                ->whereNotIn('id', $processedKandidatIds)
                ->get();

            foreach ($toDelete as $delKand) {
                if ($delKand->suara()->count() == 0) {
                    $delKand->delete();
                }
            }

            DB::commit();
            return redirect()->route('pemilihan.index')->with('success', 'Agenda Pemilihan berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui pemilihan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $pemilihan = Pemilihan::forUser($user)->findOrFail($id);

        // Delete candidate images
        foreach ($pemilihan->kandidat as $kand) {
            if ($kand->foto && File::exists(public_path('images/pemilihan/' . $kand->foto))) {
                File::delete(public_path('images/pemilihan/' . $kand->foto));
            }
        }

        $pemilihan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Agenda pemilihan berhasil dihapus.'
        ]);
    }

    public function toggleStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:draft,aktif,selesai,ditutup'
        ]);

        $user = Auth::user();
        $pemilihan = Pemilihan::forUser($user)->findOrFail($id);
        $pemilihan->update([
            'status' => $request->input('status'),
            'updated_by' => $user->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Status pemilihan berhasil diubah menjadi ' . $request->input('status')
        ]);
    }

    public function getCities(Request $request)
    {
        $provinceCode = $request->input('province_code');
        $cities = City::where('province_code', $provinceCode)->orderBy('name', 'asc')->get(['code', 'name']);
        return response()->json($cities);
    }
}
