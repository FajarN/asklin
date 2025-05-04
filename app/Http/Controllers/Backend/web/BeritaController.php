<?php

namespace App\Http\Controllers\Backend\web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{BeritaKategori, Berita, BeritaImage};
use DataTables;
use Illuminate\Support\Str;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class BeritaController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:berita-list|berita-create|berita-edit|berita-delete', ['only' => ['index','show']]);
        $this->middleware('permission:berita-create', ['only' => ['create','store']]);
        $this->middleware('permission:berita-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:berita-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('backend.berita.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Berita::select('berita.*', 'berita_kategori.nama')
                ->join('berita_kategori', 'berita_kategori.id', '=', 'berita.id_kategori')
                ->when(Auth::user()->hasRole('Admin Cabang'), function ($query) {
                    $query->where('berita.id_kota', Auth::user()->kota);
                })
                ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) {
                    $query->where('berita.id_provinsi', Auth::user()->provinsi);
                })
                ->latest()
                ->get();

            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            return Str::contains(Str::lower($data['judul']), Str::lower($request->get('search')));
                        });
                    }
                })
                ->addIndexColumn()
                ->addColumn('action', 'backend.berita.action')
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create()
    {
        $kategori = BeritaKategori::get();
        return view('backend.berita.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $rules = [
            'judul' => 'required',
            'id_kategori' => 'required',
            'konten' => 'required',
            'tanggal' => 'required|date',
            'status' => 'required',
            'thumb' => 'required|mimes:jpg,jpeg,png|max:2000',
            'gambar.*' => 'mimes:jpg,jpeg,png|max:2000',
        ];

        $messages = [
            'judul.required' => 'Judul berita tidak boleh kosong',
            'id_kategori.required' => 'Kategori berita harus dipilih',
            'konten.required' => 'Konten berita tidak boleh kosong',
            'tanggal.required' => 'Tanggal berita harus diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'status.required' => 'Status berita harus dipilih',
            'thumb.required' => 'Thumbnail berita harus diupload',
            'thumb.mimes' => 'Thumbnail harus berupa file gambar (jpg, jpeg, png)',
            'thumb.max' => 'Ukuran thumbnail maksimal 2MB',
            'gambar.*.mimes' => 'Gambar harus berupa file gambar (jpg, jpeg, png)',
            'gambar.*.max' => 'Ukuran gambar maksimal 2MB per file',
        ];

        $this->validate($request, $rules, $messages);
        
        try {

            // Handle thumbnail upload
            $destinationPath = 'assets/images/berita/thumbnails';
            $img_ext = $request->file('thumb')->getClientOriginalExtension();
            $filename = 'berita-' . time() . '.' . $img_ext;
            $path = $request->file('thumb')->move($destinationPath, $filename);
            Log::info('Thumbnail berhasil diunggah', ['filename' => $filename]);

            $kode_qr = $this->generateQR();

            $berita = Berita::create([
                'id_kategori' => $request->id_kategori,
                'judul' => $request->judul,
                'path' => Str::slug($request->judul),
                'tanggal' => $request->tanggal,
                'konten' => $request->konten,
                'lokasi' => $request->lokasi ?? null,
                'thumb' => $filename,
                'status' => $request->status,
                'kode_qr' => $kode_qr,
                'created_by' => Auth::id(),
            ]);

            // Handle multiple images upload
            if ($request->hasFile('gambar')) {
                foreach ($request->file('gambar') as $file) {
                    $destinationPath = 'assets/images/berita/';
                    $filename = 'berita-' . time() . '-' . $file->getClientOriginalName();
                    $file->move($destinationPath, $filename);

                    BeritaImage::create([
                        'berita_id' => $berita->id,
                        'gambar' => $filename,
                    ]);
                }
            }

          return redirect()->route('berita.index')->with('success', 'Berita berhasil disimpan.');


        } catch (\Exception $e) {
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public static function generateQR(): string
    {
        $uniqueData = uniqid('', true) . now()->timestamp . rand(1000, 9999); 
        $hash = hash('sha256', $uniqueData);

        return $hash;
    }

    public function edit($id)
    {
        $kategori = BeritaKategori::get();
        $data = Berita::with('images')->findOrFail($id);
        return view('backend.berita.edit', compact('data','kategori'));
    }
    

    public function update(Request $request, $id)
    {
        $rules = [
            'judul' => 'required',
            'id_kategori' => 'required',
            'konten' => 'required',
            'tanggal' => 'required|date',
            'status' => 'required',
            'thumb' => 'nullable|mimes:jpg,jpeg,png|max:2000',
            'gambar.*' => 'mimes:jpg,jpeg,png|max:2000',
        ];

        $messages = [
            'judul.required' => 'Judul berita tidak boleh kosong',
            'id_kategori.required' => 'Kategori berita harus dipilih',
            'konten.required' => 'Konten berita tidak boleh kosong',
            'tanggal.required' => 'Tanggal berita harus diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'status.required' => 'Status berita harus dipilih',
            'thumb.mimes' => 'Thumbnail harus berupa file gambar (jpg, jpeg, png)',
            'thumb.max' => 'Ukuran thumbnail maksimal 2MB',
            'gambar.*.mimes' => 'Gambar harus berupa file gambar (jpg, jpeg, png)',
            'gambar.*.max' => 'Ukuran gambar maksimal 2MB per file',
        ];

        $this->validate($request, $rules, $messages);

        try {
            $berita = Berita::findOrFail($id);

            // Handle thumbnail update
            if ($request->hasFile('thumb')) {
                $fileThumb = $request->file('thumb');
                $destinationPath = 'assets/images/berita/thumbnails/';
                $filename = 'thumb-' . time() . '-' . $fileThumb->getClientOriginalName();
                $fileThumb->move($destinationPath, $filename);
                
                // Delete old thumbnail if exists
                if ($berita->thumb && file_exists(public_path('assets/images/berita/thumbnails/' . $berita->thumb))) {
                    unlink(public_path('assets/images/berita/thumbnails/' . $berita->thumb));
                }
            } else {
                $filename = $berita->thumb;
            }

            $berita->update([
                'id_kategori' => $request->id_kategori,
                'judul' => $request->judul,
                'path' => Str::slug($request->judul),
                'tanggal' => $request->tanggal,
                'konten' => $request->konten,
                'lokasi' => $request->lokasi ?? null,
                'thumb' => $filename,
                'status' => $request->status,
                'updated_by' => Auth::id(),
            ]);

            // Handle new images upload
            if ($request->hasFile('gambar')) {
                foreach ($request->file('gambar') as $file) {
                    $destinationPath = 'assets/images/berita/';
                    $filename = 'berita-' . time() . '-' . $file->getClientOriginalName();
                    $file->move($destinationPath, $filename);

                    BeritaImage::create([
                        'berita_id' => $berita->id,
                        'gambar' => $filename,
                    ]);
                }
            }

            // Handle images deletion
            if ($request->has('hapus_gambar')) {
                foreach ($request->hapus_gambar as $imageId) {
                    $image = BeritaImage::findOrFail($imageId);
                    if (file_exists(public_path('assets/images/berita/' . $image->gambar))) {
                        unlink(public_path('assets/images/berita/' . $image->gambar));
                    }
                    $image->delete();
                }
            }

             return redirect()->route('berita.index')->with('success', 'Berita berhasil disimpan.');

        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat memperbarui berita', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $berita = Berita::findOrFail($request->id);

            // Delete thumbnail
            $thumbPath = public_path('assets/images/berita/thumbnails/' . $berita->thumb);
            if (!empty($berita->thumb) && file_exists($thumbPath)) {
                unlink($thumbPath);
            }

            // Delete all related images
            $images = BeritaImage::where('berita_id', $berita->id)->get();
            foreach ($images as $image) {
                $imagePath = public_path('assets/images/berita/' . $image->gambar);
                if (!empty($image->gambar) && file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $image->delete();
            }

            $berita->delete();

            return redirect()->route('berita.index')->with('success', 'Berita berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat menghapus berita', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}