<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\StrukturOrganisasi;
use App\Models\StrukturKelompokPengurus;
use App\Models\StrukturPengurus;
use App\Models\TingkatanPengurus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;
use Yajra\DataTables\Facades\DataTables;

class StrukturOrganisasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tingkatanList = TingkatanPengurus::all();
        $provinces = Province::all();
        $kota = City::all();
        
        return view('backend.struktur_organisasi.index', compact('tingkatanList', 'provinces','kota'));
    }
    /**
     * Process datatables ajax request.
     */

     public function list(Request $request)
    {
        $query = StrukturOrganisasi::query()
            ->select(
                'a.*',
                'd.name as kota',
                'e.name as provinsi'
            )
            ->from('struktur_organisasi as a')
            ->leftJoin('indonesia_cities as d', 'd.code', '=', 'a.id_kota')
            ->leftJoin('indonesia_provinces as e', 'e.code', '=', 'a.id_provinsi');

        if ($request->has('tingkatan_id') && $request->tingkatan_id) {
            $query->where('a.id_tingkatan_pengurus', $request->tingkatan_id);
        }

        if ($request->has('provinsi_id') && $request->provinsi_id) {
            $query->where('a.id_provinsi', $request->provinsi_id);
        }

        if ($request->has('kota_id') && $request->kota_id) {
            $query->where('a.id_kota', $request->kota_id);
        }

        return DataTables::of($query)
            ->addColumn('tingkatan', function ($row) {
                return $row->tingkatanPengurus ? $row->tingkatanPengurus->nama_tingkatan : '-';
            })
            ->addColumn('action', function ($row) {
                $viewBtn = '<a href="' . route('struktur_organisasi.detail', $row->id) . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i> Detail</a>';
                $editBtn = '<button class="btn btn-sm btn-primary edit-btn" data-id="' . $row->id . '"><i class="fas fa-edit"></i> Edit</button>';
                $deleteBtn = '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '"><i class="fas fa-trash"></i> Delete</button>';

                return '<div class="btn-group" role="group">' . $viewBtn . $editBtn . $deleteBtn . '</div>';
            })
            ->editColumn('status', function ($row) {
                $statusClasses = [
                    'draft' => 'badge-warning',
                    'aktif' => 'badge-success',
                    'selesai' => 'badge-info'
                ];

                return '<span class="badge ' . ($statusClasses[$row->status] ?? 'badge-secondary') . '">' . ucfirst($row->status) . '</span>';
            })
            ->editColumn('tgl_muscab', function ($row) {
                return $row->tgl_muscab ? date('d-m-Y', strtotime($row->tgl_muscab)) : '-';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }



    /**
     * Get the specified resource for editing.
     */
    public function edit($id)
    {
        $struktur = StrukturOrganisasi::with(['tingkatanPengurus', 'provinsi', 'kota'])->findOrFail($id);
        
        return response()->json([
            'data' => $struktur,
            'tingkatanList' => TingkatanPengurus::all(),
            'provinsiList' => Province::all(),
            'kotaList' => $struktur->id_provinsi ? City::where('province_code', $struktur->id_provinsi)->get() : []
        ]);
    }

    /**
     * Store a newly created resource or update an existing one.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_struktur' => 'required|string|max:255',
            'tingkatan_pengurus' => 'required|exists:tingkatan_pengurus,id',
            'periode' => 'required|string|max:20',
            'tgl_muscab' => 'required|date',
            'status' => 'required|in:draft,aktif,selesai',
            'id_provinsi' => 'nullable|exists:indonesia_provinces,code', // Perhatikan field code
            'id_kota' => 'nullable|exists:indonesia_cities,code', // Perhatikan field code
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $data = [
                'id_tingkatan_pengurus' => $request->tingkatan_pengurus,
                'id_provinsi' => $request->id_provinsi, // Sesuaikan dengan name di form
                'id_kota' => $request->id_kota, // Sesuaikan dengan name di form
                'nama_struktur' => $request->nama_struktur,
                'periode' => $request->periode,
                'tgl_muscab' => $request->tgl_muscab,
                'status' => $request->status,
            ];

            if ($request->has('id') && $request->id) {
                $struktur = StrukturOrganisasi::findOrFail($request->id);
                $struktur->update($data);
                $message = 'Struktur organisasi berhasil diperbarui!';
            } else {
                StrukturOrganisasi::create($data);
                $message = 'Struktur organisasi berhasil ditambahkan!';
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error saving struktur organisasi: '.$e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function detail($id)
    {
        $struktur = StrukturOrganisasi::query()
            ->select(
                'a.*',
                'd.name as kota_nama',
                'e.name as provinsi_nama'
            )
            ->from('struktur_organisasi as a')
            ->leftJoin('indonesia_cities as d', 'd.code', '=', 'a.id_kota')
            ->leftJoin('indonesia_provinces as e', 'e.code', '=', 'a.id_provinsi')
            ->where('a.id', $id)
            ->firstOrFail();

        $pengurus = StrukturPengurus::where('id_struktur_organisasi', $id)
            ->whereNull('parent_id')
            ->orderBy('urutan', 'asc')
            ->get();

        $kelompokPengurusList = StrukturKelompokPengurus::orderBy('urutan')->get();

            
        return view('backend.struktur_organisasi.detail', compact('struktur', 'pengurus','kelompokPengurusList'));
    }

    
    /**
     * Add or edit structure members
     */
    public function storePengurus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_struktur_organisasi' => 'required|exists:struktur_organisasi,id',
            'jabatan' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'foto_pengurus' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'urutan' => 'required|integer',
            'parent_id' => 'nullable|exists:struktur_pengurus,id',
            'status' => 'required|in:aktif,nonaktif,mengundurkan_diri',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $data = [
                'id_struktur_organisasi' => $request->id_struktur_organisasi,
                'id_kelompok' => $request->id_kelompok,
                'jabatan' => $request->jabatan,
                'parent_id' => $request->parent_id,
                'nama_lengkap' => $request->nama_lengkap,
                'no_telp' => $request->no_telp,
                'email' => $request->email,
                'urutan' => $request->urutan,
                'status' => $request->status,
            ];

            // Handle file upload if present
            if ($request->hasFile('foto_pengurus')) {
                if ($request->has('id') && $request->id) {
                    $pengurus = StrukturPengurus::findOrFail($request->id);
                    if ($pengurus->foto_pengurus) {
                        Storage::delete('public/pengurus/' . $pengurus->foto_pengurus);
                    }
                }
                
                $file = $request->file('foto_pengurus');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/pengurus', $fileName);
                $data['foto_pengurus'] = $fileName;
            }

            if ($request->has('id') && $request->id) {
                $pengurus = StrukturPengurus::findOrFail($request->id);
                $pengurus->update($data);
                $message = 'Data pengurus berhasil diperbarui!';
            } else {
                StrukturPengurus::create($data);
                $message = 'Data pengurus berhasil ditambahkan!';
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Get structure member data for editing
     */
    public function editPengurus(Request $request)
    {
        $pengurus = StrukturPengurus::findOrFail($request->id);

        return response()->json([
            'id' => $pengurus->id,
            'id_struktur_organisasi' => $pengurus->id_struktur_organisasi,
            'id_kelompok' => $pengurus->id_kelompok, 
            'jabatan' => $pengurus->jabatan,
            'keterangan' => $pengurus->keterangan,
            'parent_id' => $pengurus->parent_id,
            'nama_lengkap' => $pengurus->nama_lengkap,
            'no_telp' => $pengurus->no_telp,
            'email' => $pengurus->email,
            'urutan' => $pengurus->urutan,
            'status' => $pengurus->status,
            'foto_pengurus' => $pengurus->foto_pengurus,
        ]);
    }

    
    /**
     * Delete structure member
     */
    public function destroyPengurus(Request $request)
    {
        try {
            $pengurus = StrukturPengurus::findOrFail($request->id);
            
            // Check if has children
            $hasChildren = StrukturPengurus::where('parent_id', $pengurus->id)->exists();
            if ($hasChildren) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Tidak dapat menghapus pengurus yang memiliki anggota bawahan!'
                ], 422);
            }
            
            // Delete photo if exists
            if ($pengurus->foto_pengurus) {
                Storage::delete('public/pengurus/' . $pengurus->foto_pengurus);
            }
            
            $pengurus->delete();
            
            return response()->json(['success' => true, 'message' => 'Data pengurus berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            $struktur = StrukturOrganisasi::findOrFail($request->id);
            
            // Check if structure has members
            $hasMembers = StrukturPengurus::where('id_struktur_organisasi', $struktur->id)->exists();
            if ($hasMembers) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Tidak dapat menghapus struktur yang memiliki pengurus! Hapus pengurus terlebih dahulu.'
                ], 422);
            }
            
            $struktur->delete();
            
            return response()->json(['success' => true, 'message' => 'Struktur organisasi berhasil dihapus!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
        

        public function printDetail($id)
        {
            $struktur = StrukturOrganisasi::query()
                ->select(
                    'a.*',
                    'd.name as kota_nama',
                    'e.name as provinsi_nama'
                )
                ->from('struktur_organisasi as a')
                ->leftJoin('indonesia_cities as d', 'd.code', '=', 'a.id_kota')
                ->leftJoin('indonesia_provinces as e', 'e.code', '=', 'a.id_provinsi')
                ->where('a.id', $id)
                ->firstOrFail();

            $pengurus = StrukturPengurus::where('id_struktur_organisasi', $id)
                ->whereNull('parent_id')
                ->orderBy('urutan', 'asc')
                ->get();

                
            return view('backend.struktur_organisasi.print', compact('struktur', 'pengurus'));
        }

}