<?php

namespace App\Http\Controllers\Backend\rakernas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RakernasGaleri;
use DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RakernasGaleriController extends Controller
{
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = RakernasGaleri::query();
            if ($request->filled('id_periode')) {
                $query->where('id_periode', $request->id_periode);
            }
            if ($request->filled('kategori')) {
                $query->where('kategori', $request->kategori);
            }

            $data = $query->orderBy('urutan', 'asc')->orderBy('id', 'desc')->get();

            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    $search = is_array($request->get('search')) ? ($request->input('search.value') ?? '') : $request->get('search');
                    if (!empty($search)) {
                        $instance->collection = $instance->collection->filter(function ($row) use ($search) {
                            return Str::contains(Str::lower($row['judul'] ?? ''), Str::lower($search))
                                || Str::contains(Str::lower($row['deskripsi'] ?? ''), Str::lower($search));
                        });
                    }
                })
                ->addIndexColumn()
                ->addColumn('media_preview', function($row){
                    $foto = $row->foto ? asset('storage/' . $row->foto) : asset('assets/images/placeholder_venue.jpg');
                    return '<div class="flex items-center gap-3">
                                <a href="' . $foto . '" target="_blank">
                                    <img src="' . $foto . '" alt="' . e($row->judul) . '" class="w-20 h-14 object-cover rounded-xl border border-slate-200 dark:border-slate-700 hover:scale-105 transition-transform shadow-xs flex-shrink-0" onerror="this.src=\'/assets/images/transparent.png\'">
                                </a>
                                <div>
                                    <div class="font-bold text-sm text-slate-800 dark:text-white">' . e($row->judul) . '</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">' . e($row->deskripsi ?? '-') . '</div>
                                </div>
                            </div>';
                })
                ->addColumn('kategori_badge', function($row){
                    $labels = [
                        'venue' => '<span class="badge badge-info px-3 py-1.5 font-bold text-xs"><i class="fas fa-building mr-1.5"></i>Venue / Lokasi</span>',
                        'hotel' => '<span class="badge badge-primary px-3 py-1.5 font-bold text-xs"><i class="fas fa-hotel mr-1.5"></i>Hotel Rekanan</span>',
                        'slider' => '<span class="badge badge-success px-3 py-1.5 font-bold text-xs"><i class="fas fa-images mr-1.5"></i>Slider Acara</span>',
                        'kegiatan' => '<span class="badge badge-secondary px-3 py-1.5 font-bold text-xs"><i class="fas fa-camera mr-1.5"></i>Dokumentasi</span>',
                    ];
                    return $labels[$row->kategori] ?? '<span class="badge badge-light px-3 py-1.5 text-xs font-bold">' . e($row->kategori) . '</span>';
                })
                ->addColumn('action', function($row){
                    return '
                        <div class="inline-flex items-center gap-1.5">
                            <button type="button" class="btn btn-outline-info btn-sm px-2.5 py-1.5" onclick="editGaleri('.$row->id.')" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-sm px-2.5 py-1.5" onclick="deleteGaleri('.$row->id.')" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['media_preview', 'kategori_badge', 'action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_periode' => 'required',
            'judul' => 'required|string|max:255',
            'kategori' => 'required',
        ]);

        $data = [
            'id_periode' => $request->id_periode,
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'urutan' => $request->urutan ?? 0,
        ];

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('rakernas/galeri', 'public');
            $data['foto'] = $path;
        }

        if ($request->filled('id')) {
            $galeri = RakernasGaleri::findOrFail($request->id);
            $galeri->update($data);
            $msg = 'Galeri Rakernas berhasil diperbarui.';
        } else {
            if (!$request->hasFile('foto')) {
                return response()->json(['success' => false, 'message' => 'File foto wajib diunggah.'], 422);
            }
            RakernasGaleri::create($data);
            $msg = 'Foto/galeri Rakernas berhasil ditambahkan.';
        }

        return response()->json(['success' => true, 'message' => $msg]);
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $data = RakernasGaleri::findOrFail($id);
        return response()->json($data);
    }

    public function destroy(Request $request)
    {
        $galeri = RakernasGaleri::findOrFail($request->id);
        if ($galeri->foto) {
            Storage::disk('public')->delete($galeri->foto);
        }
        $galeri->delete();
        return response()->json(['success' => true, 'message' => 'Foto galeri berhasil dihapus.']);
    }
}

