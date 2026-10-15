<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use App\Models\EventKategori;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AgendaApiController extends BaseApiController
{
    /**
     * Mengambil daftar agenda & kegiatan berpaginasi dengan tab (upcoming/past), kategori, dan pencarian.
     * GET /api/v1/agenda
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Event::with(['kategori'])
                ->where('status', '1');

            $tab = $request->get('tab', 'all'); // upcoming, past, all
            $now = Carbon::now();

            if ($tab === 'upcoming') {
                $query->where(function ($q) use ($now) {
                    $q->where('selesai', '>=', $now)
                      ->orWhere('mulai', '>=', $now);
                })->orderBy('mulai', 'asc');
            } elseif ($tab === 'past') {
                $query->where('selesai', '<', $now)
                      ->orderBy('mulai', 'desc');
            } else {
                $query->orderBy('mulai', 'desc');
            }

            // Filter pencarian
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'LIKE', "%{$search}%")
                      ->orWhere('konten', 'LIKE', "%{$search}%");
                });
            }

            // Filter kategori
            if ($request->filled('kategori_id')) {
                $query->where('id_kategori', $request->get('kategori_id'));
            }

            $perPage = min(max((int) $request->get('per_page', 10), 1), 50);
            $paginator = $query->paginate($perPage);

            $items = collect($paginator->items())->map(function (Event $event) {
                return $this->formatEventItem($event);
            });

            // Ambil daftar kategori aktif untuk filter chips di UI mobile
            try {
                $kategoriList = EventKategori::select('id', 'nama')->get();
            } catch (\Throwable $e) {
                $kategoriList = [];
            }

            return response()->json([
                'success' => true,
                'message' => 'Daftar agenda kegiatan berhasil diambil.',
                'data'    => $items,
                'kategori'=> $kategoriList,
                'active_tab' => $tab,
                'meta'    => [
                    'current_page' => $paginator->currentPage(),
                    'last_page'    => $paginator->lastPage(),
                    'per_page'     => $paginator->perPage(),
                    'total'        => $paginator->total(),
                ],
            ], 200);
        } catch (\Throwable $e) {
            \Log::error('Agenda API Index Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat agenda: ' . $e->getMessage(),
                'data'    => [],
                'kategori'=> [],
                'active_tab' => $request->get('tab', 'all'),
                'meta'    => [
                    'current_page' => 1,
                    'last_page'    => 1,
                    'per_page'     => 10,
                    'total'        => 0,
                ],
            ], 200);
        }
    }

    /**
     * Mengambil detail agenda kegiatan berdasarkan slug (path) atau ID.
     * GET /api/v1/agenda/{identifier}
     *
     * @param string $identifier
     * @return JsonResponse
     */
    public function show(string $identifier): JsonResponse
    {
        try {
            $event = Event::with(['kategori', 'creator'])
                ->where('status', '1')
                ->where(function ($q) use ($identifier) {
                    $q->where('path', $identifier)
                      ->orWhere('id', $identifier);
                })
                ->first();

            if (!$event) {
                return $this->errorResponse('Agenda kegiatan tidak ditemukan atau belum dipublikasikan.', 404);
            }

            $related = Event::with(['kategori'])
                ->where('status', '1')
                ->where('id', '!=', $event->id)
                ->where('id_kategori', $event->id_kategori)
                ->orderBy('mulai', 'desc')
                ->limit(3)
                ->get()
                ->map(fn($item) => $this->formatEventItem($item));

            $data = $this->formatEventItem($event);
            $data['konten_html'] = $event->konten;
            $data['konten_bersih'] = strip_tags($event->konten);
            $data['agenda_terkait'] = $related;

            return $this->successResponse($data, 'Detail agenda kegiatan berhasil diambil.');
        } catch (\Throwable $e) {
            \Log::error('Agenda API Show Error: ' . $e->getMessage());
            return $this->errorResponse('Gagal mengambil detail agenda: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Format data event/agenda untuk API response.
     */
    private function formatEventItem(Event $event): array
    {
        $now = Carbon::now();
        $mulai = $event->mulai ? Carbon::parse($event->mulai) : null;
        $selesai = $event->selesai ? Carbon::parse($event->selesai) : null;

        $isExpired = $selesai ? $now->gt($selesai) : false;
        $isOngoing = ($mulai && $selesai) ? ($now->gte($mulai) && $now->lte($selesai)) : false;

        $statusPill = 'Pendaftaran Buka';
        $statusPillColor = 'green';
        if ($isExpired) {
            $statusPill = 'Selesai';
            $statusPillColor = 'gray';
        } elseif ($isOngoing) {
            $statusPill = 'Sedang Berlangsung';
            $statusPillColor = 'blue';
        }

        $cleanContent = strip_tags($event->konten ?? '');
        $excerpt = Str::limit(trim(preg_replace('/\s+/', ' ', $cleanContent)), 140);

        // Ambil data kategori secara aman tanpa bentrok dengan string column 'kategori'
        $kategoriData = null;
        if ($event->relationLoaded('kategori')) {
            $rel = $event->getRelation('kategori');
            if ($rel instanceof EventKategori) {
                $kategoriData = [
                    'id'   => $rel->id,
                    'nama' => $rel->nama,
                ];
            }
        }
        if (!$kategoriData) {
            $rawKategori = $event->attributes['kategori'] ?? null;
            if (is_string($rawKategori) && trim($rawKategori) !== '') {
                $kategoriData = [
                    'id'   => $event->id_kategori,
                    'nama' => trim($rawKategori),
                ];
            }
        }

        // Format tanggal secara aman
        $tanggalFormatted = null;
        if ($mulai) {
            try {
                $tanggalFormatted = $mulai->locale('id')->isoFormat('D MMMM Y');
            } catch (\Throwable $e) {
                $tanggalFormatted = $mulai->format('d M Y');
            }
        }

        return [
            'id'                 => $event->id,
            'judul'              => $event->judul,
            'slug'               => $event->path,
            'kategori'           => $kategoriData,
            'excerpt'            => $excerpt,
            'gambar_url'         => $event->gambar_url,
            'tanggal_mulai'      => $mulai ? $mulai->toIso8601String() : null,
            'tanggal_selesai'    => $selesai ? $selesai->toIso8601String() : null,
            'tanggal_formatted'  => $tanggalFormatted,
            'jam_formatted'      => ($mulai && $selesai) ? ($mulai->format('H:i') . ' - ' . $selesai->format('H:i') . ' WIB') : null,
            'is_expired'         => $isExpired,
            'is_ongoing'         => $isOngoing,
            'status_pill'        => $statusPill,
            'status_pill_color'  => $statusPillColor,
            'lokasi'             => $event->kategori_nama ?? 'ASKLIN Indonesia',
        ];
    }
}
