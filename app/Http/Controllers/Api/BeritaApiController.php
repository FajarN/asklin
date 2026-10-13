<?php

namespace App\Http\Controllers\Api;

use App\Models\Berita;
use App\Models\BeritaImage;
use App\Models\BeritaKategori;
use App\Models\BeritaVisit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BeritaApiController extends BaseApiController
{
    /**
     * Mengambil daftar berita publik berpaginasi dengan filter kategori dan pencarian.
     * GET /api/v1/berita
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Berita::with(['kategori', 'images'])
            ->where('status', '1');

        // Filter pencarian berdasarkan judul atau konten
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                  ->orWhere('konten', 'LIKE', "%{$search}%");
            });
        }

        // Filter berdasarkan ID kategori
        if ($request->filled('kategori_id')) {
            $query->where('id_kategori', $request->get('kategori_id'));
        }

        // Default urutan terbaru
        $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc');

        $perPage = min(max((int) $request->get('per_page', 10), 1), 50);
        $paginator = $query->paginate($perPage);

        $items = collect($paginator->items())->map(function (Berita $berita) {
            return $this->formatBeritaListItem($berita);
        });

        // Ambil daftar kategori aktif untuk filter chips di mobile UI
        $kategoriList = BeritaKategori::select('id', 'nama')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar berita berhasil diambil.',
            'data'    => $items,
            'kategori'=> $kategoriList,
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ], 200);
    }

    /**
     * Mengambil detail artikel berita publik berdasarkan slug (path) atau ID.
     * GET /api/v1/berita/{identifier}
     *
     * @param string $identifier
     * @return JsonResponse
     */
    public function show(string $identifier): JsonResponse
    {
        $berita = Berita::with(['kategori', 'images'])
            ->where('status', '1')
            ->where(function ($q) use ($identifier) {
                $q->where('path', $identifier)
                  ->orWhere('id', $identifier);
            })
            ->first();

        if (!$berita) {
            return $this->errorResponse('Artikel berita tidak ditemukan atau belum dipublikasikan.', 404);
        }

        // Catat statistik visit jika belum berkunjung dalam 24 jam terakhir
        try {
            $ipAddress = request()->ip();
            $recentVisit = BeritaVisit::where('berita_id', $berita->id)
                ->where('ip_address', $ipAddress)
                ->where('created_at', '>', now()->subDay())
                ->exists();

            if (!$recentVisit) {
                BeritaVisit::create([
                    'berita_id'  => $berita->id,
                    'ip_address' => $ipAddress,
                    'user_agent' => request()->userAgent(),
                ]);
            }
        } catch (\Throwable $e) {
            // Silently ignore logging visit failure to prevent breaking API response
        }

        // Artikel Terkait
        $related = Berita::with(['kategori'])
            ->where('status', '1')
            ->where('id', '!=', $berita->id)
            ->where('id_kategori', $berita->id_kategori)
            ->orderBy('tanggal', 'desc')
            ->limit(3)
            ->get()
            ->map(fn($item) => $this->formatBeritaListItem($item));

        $galleryImages = $berita->images->map(function ($img) {
            return [
                'id'  => $img->id,
                'url' => asset('assets/images/berita/' . $img->nama_image),
            ];
        });

        $detail = [
            'id'             => $berita->id,
            'judul'          => $berita->judul,
            'slug'           => $berita->path,
            'tanggal'        => $berita->tanggal,
            'lokasi'         => $berita->lokasi,
            'kategori'       => $berita->kategori ? [
                'id'   => $berita->kategori->id,
                'nama' => $berita->kategori->nama,
            ] : null,
            'thumb_url'      => $berita->thumb ? asset('assets/images/berita/thumbnails/' . $berita->thumb) : null,
            'konten_html'    => $berita->konten,
            'konten_bersih'  => strip_tags($berita->konten),
            'total_visits'   => $berita->total_visits ?? 0,
            'galeri_images'  => $galleryImages,
            'artikel_terkait'=> $related,
        ];

        return $this->successResponse($detail, 'Detail berita berhasil diambil.');
    }

    /**
     * Format item berita untuk list.
     */
    private function formatBeritaListItem(Berita $berita): array
    {
        $cleanContent = strip_tags($berita->konten ?? '');
        $excerpt = Str::limit(trim(preg_replace('/\s+/', ' ', $cleanContent)), 140);

        return [
            'id'        => $berita->id,
            'judul'     => $berita->judul,
            'slug'      => $berita->path,
            'tanggal'   => $berita->tanggal,
            'kategori'  => $berita->kategori ? [
                'id'   => $berita->kategori->id,
                'nama' => $berita->kategori->nama,
            ] : null,
            'excerpt'   => $excerpt,
            'thumb_url' => $berita->thumb ? asset('assets/images/berita/thumbnails/' . $berita->thumb) : null,
            'lokasi'    => $berita->lokasi,
        ];
    }
}

