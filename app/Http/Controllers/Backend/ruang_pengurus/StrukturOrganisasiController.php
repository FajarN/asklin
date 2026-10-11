<?php

namespace App\Http\Controllers\Backend\ruang_pengurus;

use App\Http\Controllers\Controller;
use App\Models\StrukturOrganisasi;
use App\Models\StrukturKelompokPengurus;
use App\Models\StrukturPengurus;
use App\Models\TingkatanPengurus;
use App\Models\SkPengurus;
use App\Models\SkPengurusLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\MasterKetuaUmum;
use App\Services\NotificationService;
use Illuminate\Support\Str;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class StrukturOrganisasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:struktur-organisasi-list', ['only' => ['index', 'list']]);
        $this->middleware('permission:struktur-organisasi-create', ['only' => ['store']]);
        $this->middleware('permission:struktur-organisasi-edit', ['only' => ['edit']]);
        $this->middleware('permission:struktur-organisasi-delete', ['only' => ['destroy']]);
    }

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
        $user = Auth::user();

        $query = StrukturOrganisasi::query()
            ->with(['tingkatanPengurus', 'skPengurus'])
            ->select(
                'a.*',
                'd.name as kota',
                'e.name as provinsi'
            )
            ->from('struktur_organisasi as a')
            ->leftJoin('indonesia_cities as d', 'd.code', '=', 'a.id_kota')
            ->leftJoin('indonesia_provinces as e', 'e.code', '=', 'a.id_provinsi');

        // Regional Data Scoping
        if ($user->hasRole('Admin Cabang') && !empty($user->kota)) {
            $query->where('a.id_kota', $user->kota);
        } elseif ($user->hasRole('Admin Daerah') && !empty($user->provinsi)) {
            $query->where('a.id_provinsi', $user->provinsi);
        }

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
            ->addColumn('action', function ($row) use ($user) {
                $viewBtn = '<a href="' . route('struktur_organisasi.detail', $row->id) . '" class="btn btn-sm btn-info" title="Lihat Detail"><i class="fas fa-eye"></i> Detail</a>';
                
                $skBtn = '';
                if ($row->skPengurus) {
                    $skBtn = ' <a href="' . route('sk_pengurus.show', $row->skPengurus->id) . '" class="btn btn-sm btn-success" title="Ruang Kerja Persetujuan SK"><i class="fas fa-stamp"></i> Periksa SK</a>';
                }

                // Jika data sedang terkunci, disable edit dan delete kecuali untuk Superadmin
                if ($row->is_locked && !$user->hasRole('Superadmin')) {
                    $editBtn = ' <button class="btn btn-sm btn-secondary" disabled title="Terkunci dalam proses persetujuan"><i class="fas fa-lock"></i> Terkunci</button>';
                    $deleteBtn = '';
                } else {
                    $editBtn = ' <button class="btn btn-sm btn-primary edit-btn" data-id="' . $row->id . '"><i class="fas fa-edit"></i> Edit</button>';
                    $deleteBtn = ' <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '"><i class="fas fa-trash"></i> Delete</button>';
                }

                return '<div class="btn-group" role="group">' . $viewBtn . $skBtn . $editBtn . $deleteBtn . '</div>';
            })
            ->editColumn('status', function ($row) {
                $statusClasses = [
                    'draft' => 'badge-secondary',
                    'diajukan_daerah' => 'badge-info',
                    'diajukan_pusat' => 'badge-primary',
                    'disetujui' => 'badge-success',
                    'aktif' => 'badge-success',
                    'selesai' => 'badge-dark',
                    'revisi' => 'badge-warning',
                    'ditolak' => 'badge-danger',
                ];

                $labels = [
                    'draft' => 'Draft',
                    'diajukan_daerah' => 'Diajukan ke Daerah',
                    'diajukan_pusat' => 'Diajukan ke Pusat',
                    'disetujui' => 'Disetujui',
                    'aktif' => 'Aktif',
                    'selesai' => 'Selesai',
                    'revisi' => 'Perlu Revisi',
                    'ditolak' => 'Ditolak',
                ];

                $label = $labels[$row->status] ?? ucfirst(str_replace('_', ' ', $row->status));
                $badgeClass = $statusClasses[$row->status] ?? 'badge-secondary';

                $html = '<span class="badge ' . $badgeClass . '">' . $label . '</span>';
                if ($row->is_locked) {
                    $html .= ' <span class="badge badge-light text-muted" title="Data terkunci"><i class="fas fa-lock"></i></span>';
                }

                return $html;
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
        $user = Auth::user();

        // Enforce wilayah untuk Admin Cabang & Admin Daerah
        if ($user->hasRole('Admin Cabang') && !empty($user->kota)) {
            $request->merge([
                'id_provinsi' => $user->provinsi,
                'id_kota' => $user->kota,
            ]);
        } elseif ($user->hasRole('Admin Daerah') && !empty($user->provinsi)) {
            $request->merge([
                'id_provinsi' => $user->provinsi,
            ]);
        }

        $validator = Validator::make($request->all(), [
            'nama_struktur' => 'required|string|max:255',
            'tingkatan_pengurus' => 'required|exists:tingkatan_pengurus,id',
            'periode' => 'required|string|max:20',
            'tgl_muscab' => 'required|date',
            'status' => 'required|string|max:50',
            'id_provinsi' => 'nullable|exists:indonesia_provinces,code',
            'id_kota' => 'nullable|exists:indonesia_cities,code',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('id') && $request->id) {
            $existing = StrukturOrganisasi::findOrFail($request->id);
            if ($existing->is_locked && !Auth::user()->hasRole('Superadmin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Struktur organisasi terkunci karena sedang dalam proses pengajuan SK!'
                ], 422);
            }
            if (!$existing->isAccessibleByCurrentUser()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki wewenang untuk mengubah struktur wilayah ini!'
                ], 403);
            }
        }

        DB::beginTransaction();
        try {
            $data = [
                'id_tingkatan_pengurus' => $request->tingkatan_pengurus,
                'id_provinsi' => $request->id_provinsi,
                'id_kota' => $request->id_kota,
                'nama_struktur' => $request->nama_struktur,
                'periode' => $request->periode,
                'tgl_muscab' => $request->tgl_muscab,
                'alamat_sekretariat' => $request->alamat_sekretariat,
                'email_sekretariat' => $request->email_sekretariat,
                'telp_sekretariat' => $request->telp_sekretariat,
                'status' => $request->status,
            ];

            if ($request->has('id') && $request->id) {
                $struktur = StrukturOrganisasi::findOrFail($request->id);
                $struktur->update($data);
                $message = 'Struktur organisasi berhasil diperbarui!';
            } else {
                $data['created_by'] = $user->id;
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

        if (!$struktur->isAccessibleByCurrentUser()) {
            abort(403, 'Akses ditolak: Anda tidak memiliki wewenang pada data struktur wilayah ini.');
        }

        $pengurus = StrukturPengurus::where('id_struktur_organisasi', $id)
            ->whereNull('parent_id')
            ->orderBy('urutan', 'asc')
            ->get();

        $kelompokPengurusList = StrukturKelompokPengurus::orderBy('urutan')->get();
        $sk = $struktur->skPengurus;
        $logs = $struktur->logs()->with('user')->get();
        $totalPengurus = StrukturPengurus::where('id_struktur_organisasi', $id)->count();

        return view('backend.struktur_organisasi.detail', compact('struktur', 'pengurus', 'kelompokPengurusList', 'sk', 'logs', 'totalPengurus'));
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

        $struktur = StrukturOrganisasi::findOrFail($request->id_struktur_organisasi);
        if ($struktur->is_locked && !Auth::user()->hasRole('Superadmin')) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengurus terkunci karena struktur organisasi sedang dalam proses pengajuan SK!'
            ], 422);
        }

        if (!$struktur->isAccessibleByCurrentUser()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki wewenang untuk mengelola pengurus pada struktur ini!'
            ], 403);
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
            $struktur = StrukturOrganisasi::findOrFail($pengurus->id_struktur_organisasi);

            if ($struktur->is_locked && !Auth::user()->hasRole('Superadmin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pengurus terkunci karena struktur organisasi sedang dalam proses pengajuan SK!'
                ], 422);
            }

            if (!$struktur->isAccessibleByCurrentUser()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki wewenang untuk menghapus pengurus pada struktur ini!'
                ], 403);
            }
            
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

            if ($struktur->is_locked && !Auth::user()->hasRole('Superadmin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus struktur yang sedang terkunci dalam proses pengajuan SK!'
                ], 422);
            }

            if (!$struktur->isAccessibleByCurrentUser()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki wewenang untuk menghapus struktur wilayah ini!'
                ], 403);
            }
            
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

    /**
     * Ajukan Struktur Organisasi untuk verifikasi dan pengesahan SK
     */
    public function ajukanSk(Request $request, $id)
    {
        $struktur = StrukturOrganisasi::findOrFail($id);

        if (!$struktur->isAccessibleByCurrentUser()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki wewenang untuk mengajukan struktur wilayah ini!'
            ], 403);
        }

        if ($struktur->is_locked && !in_array($struktur->status, ['draft', 'revisi']) && !Auth::user()->hasRole('Superadmin')) {
            return response()->json([
                'success' => false,
                'message' => 'Struktur ini sudah dalam proses pengajuan verifikasi!'
            ], 422);
        }

        $totalPengurus = StrukturPengurus::where('id_struktur_organisasi', $id)->count();
        if ($totalPengurus < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Susunan pengurus masih kosong! Silakan tambahkan minimal 1 pengurus sebelum mengajukan pengesahan.'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'file_berita_acara' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,jpeg|max:5120',
            'no_pengantar' => 'nullable|string|max:100',
            'catatan_pengantar' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            // Tentukan status dan tingkatan
            $isCabang = ($struktur->id_tingkatan_pengurus == 3 || stripos($struktur->nama_struktur, 'cabang') !== false);
            $isDaerah = ($struktur->id_tingkatan_pengurus == 2 || stripos($struktur->nama_struktur, 'daerah') !== false);

            if ($isCabang) {
                $newStatusStruktur = 'diajukan_daerah';
                $newStatusSk = 'menunggu_daerah';
                $tingkatSk = 'cabang';
                $actionLog = 'submit_cabang';
            } elseif ($isDaerah) {
                $newStatusStruktur = 'diajukan_pusat';
                $newStatusSk = 'menunggu_sekjen';
                $tingkatSk = 'daerah';
                $actionLog = 'submit_daerah';
            } else {
                $newStatusStruktur = 'diajukan_pusat';
                $newStatusSk = 'menunggu_sekjen';
                $tingkatSk = 'pusat';
                $actionLog = 'submit_pusat';
            }

            // Simpan file berita acara jika diunggah
            if ($request->hasFile('file_berita_acara')) {
                if ($struktur->file_berita_acara && Storage::exists('public/berita_acara/' . $struktur->file_berita_acara)) {
                    Storage::delete('public/berita_acara/' . $struktur->file_berita_acara);
                }
                $file = $request->file('file_berita_acara');
                $filename = 'berita_acara_' . $struktur->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/berita_acara', $filename);
                $struktur->file_berita_acara = $filename;
            }

            $struktur->status = $newStatusStruktur;
            $struktur->is_locked = true;
            $struktur->diajukan_at = now();
            $struktur->catatan_revisi = null;
            $struktur->save();

            // Create or update SkPengurus draft
            $sk = SkPengurus::firstOrNew(['id_struktur_organisasi' => $struktur->id]);
            $sk->tingkatan = $tingkatSk;
            $sk->status = $newStatusSk;
            $sk->tgl_musyawarah = $struktur->tgl_muscab;
            $sk->perihal = 'Pengesahan Susunan Pengurus ASKLIN ' . $struktur->nama_struktur . ' Periode ' . $struktur->periode;
            if ($request->no_pengantar) {
                $sk->no_rekomendasi_daerah = $request->no_pengantar;
            }
            $sk->created_by = Auth::id();
            $sk->save();

            // Record log
            $userRole = Auth::user()->roles->first()->name ?? 'Pengurus';
            SkPengurusLog::record(
                $struktur->id,
                $actionLog,
                $userRole,
                Auth::id(),
                $request->catatan_pengantar ?: 'Pengajuan pengesahan struktur kepengurusan oleh ' . Auth::user()->name,
                $sk->id
            );

            // Kirim notifikasi sistem terpusat dan email approval
            if ($isCabang) {
                \App\Services\NotificationService::notifySkActionRequired($sk, 'Verifikasi Daerah', 'Admin Daerah');
            } else {
                \App\Services\NotificationService::notifySkActionRequired($sk, 'Pemeriksaan Sekjen', 'Sekjen');
                \App\Services\EmailNotificationService::sendSkApprovalRequest($sk, 'sekjen');
            }
            \App\Services\NotificationService::notifySkActionRequired($sk, 'Pengajuan Baru', 'Superadmin');

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Pengajuan pengesahan SK berhasil dikirim! Susunan pengurus saat ini terkunci selama proses verifikasi.',
                'sk_id' => $sk->id,
                'sk_url' => route('sk_pengurus.show', $sk->id),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset status pengajuan untuk keperluan testing oleh Superadmin
     */
    public function resetTesting($id)
    {
        if (!Auth::user()->hasRole('Superadmin')) {
            return response()->json(['success' => false, 'message' => 'Hanya Superadmin yang dapat melakukan reset pengujian!'], 403);
        }

        $struktur = StrukturOrganisasi::findOrFail($id);
        $struktur->status = 'draft';
        $struktur->is_locked = false;
        $struktur->diajukan_at = null;
        $struktur->diverifikasi_daerah_at = null;
        $struktur->diverifikasi_daerah_by = null;
        $struktur->disetujui_pusat_at = null;
        $struktur->disetujui_pusat_by = null;
        $struktur->catatan_revisi = null;
        $struktur->save();

        if ($struktur->skPengurus) {
            $struktur->skPengurus->delete();
        }

        SkPengurusLog::record(
            $struktur->id,
            'reset_superadmin',
            'Superadmin',
            Auth::id(),
            'Pengajuan di-reset kembali ke status Draft oleh Superadmin untuk keperluan pengujian alur.'
        );

        return response()->json([
            'success' => true,
            'message' => 'Status struktur dan SK berhasil di-reset ke Draft. Anda dapat menguji proses pengajuan dari awal!'
        ]);
    }

    /**
     * Pengesahan Langsung SK Pengurus Pusat oleh Ketua Umum / Superadmin
     * (Khusus Pengurus Pusat: Jalur Mandiri Langsung Ketua Umum tanpa pengajuan daerah,
     *  tanpa perantara sekjen, dan tanpa upload berita acara)
     */
    public function approvePusatDirect(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->hasRole(['Ketua Umum', 'Superadmin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya Ketua Umum atau Superadmin yang berhak mengesahkan SK Pengurus Pusat!'
            ], 403);
        }

        $struktur = StrukturOrganisasi::findOrFail($id);

        if ((int) $struktur->id_tingkatan_pengurus !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Fitur pengesahan langsung hanya berlaku khusus untuk Pengurus Pusat.'
            ], 422);
        }

        $jumlahPengurus = StrukturPengurus::where('id_struktur_organisasi', $id)->count();
        if ($jumlahPengurus === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada data susunan pengurus yang diinputkan. Silakan tambahkan pengurus terlebih dahulu.'
            ], 422);
        }

        $ketumAktif = MasterKetuaUmum::getAktif();
        if (!$ketumAktif) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada data Ketua Umum aktif di sistem. Silakan tetapkan Ketua Umum aktif di menu Master Ketua Umum terlebih dahulu.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Update status struktur organisasi langsung disetujui & kunci data
            $struktur->status = 'disetujui';
            $struktur->is_locked = true;
            $struktur->diajukan_at = $struktur->diajukan_at ?: now();
            $struktur->disetujui_pusat_at = now();
            $struktur->disetujui_pusat_by = $user->id;
            $struktur->catatan_revisi = null;
            $struktur->save();

            // Generate nomor urut & format nomor SK baku Pengurus Pusat
            $tahun = now()->format('Y');
            $bulanAngka = (int) now()->format('m');
            $romawiList = [
                1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
                7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
            ];
            $bulanRomawi = $romawiList[$bulanAngka] ?? 'I';
            $count = SkPengurus::whereYear('created_at', $tahun)->whereNotNull('no_sk')->count() + 1;
            $nomorUrut = str_pad($count, 2, '0', STR_PAD_LEFT);
            $noSk = "{$nomorUrut}/SK-PP/PP-ASKLIN/{$bulanRomawi}/{$tahun}";

            // Find or create SK Pengurus
            $sk = SkPengurus::where('id_struktur_organisasi', $struktur->id)->first();
            if (!$sk) {
                $sk = new SkPengurus();
                $sk->id_struktur_organisasi = $struktur->id;
            }

            $sk->tingkatan = 'pusat';
            $sk->id_ketua_umum = $ketumAktif->id;
            $sk->no_sk = $sk->no_sk ?: $noSk;
            $sk->perihal = "Pengesahan Susunan Pengurus ASKLIN Pengurus Pusat Periode " . $struktur->periode;
            $sk->tgl_musyawarah = $struktur->tgl_muscab ?: now();
            $sk->tgl_penetapan = now();
            $sk->tgl_pelantikan = $sk->tgl_pelantikan ?: now();
            $sk->kota_penetapan = 'Jakarta';
            $sk->show_qr = true;
            if (!$sk->qr_token) {
                $sk->qr_token = (string) Str::uuid();
            }
            $sk->status = 'terbit';
            $sk->created_by = $sk->created_by ?: $user->id;
            $sk->updated_by = $user->id;
            $sk->save();

            // Record Log Audit Pengesahan Langsung
            $userRole = $user->hasRole('Ketua Umum') ? 'Ketua Umum' : 'Superadmin';
            SkPengurusLog::record(
                $struktur->id,
                'approve_ketum',
                $userRole,
                $user->id,
                $request->catatan ?: 'Pengesahan Langsung SK Pengurus Pusat oleh Ketua Umum PP ASKLIN (Jalur Mandiri Pusat).',
                $sk->id
            );

            // Kirim notifikasi sistem terpusat bahwa SK telah resmi terbit
            NotificationService::notifySkIssued($sk);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'SK Pengurus Pusat berhasil disahkan secara langsung oleh Ketua Umum!',
                'sk_id' => $sk->id,
                'print_url' => route('sk_pengurus.print', $sk->id),
                'sk_url' => route('sk_pengurus.show', $sk->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}