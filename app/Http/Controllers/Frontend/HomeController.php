<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Berita, BeritaImage, Slider, BeritaVisit, Anggota, Fasilitas, FasilitasKlinik, SDM, KategoriSDM, RumahSakit, Asuransi, FotoKlinik, Pembayaran, PembayaranKategori, Sertifikat, Galery, PendaftaranEvent};
use App\Models\StrukturOrganisasi;
use App\Models\StrukturKelompokPengurus;
use App\Models\StrukturPengurus;
use App\Models\TingkatanPengurus;
use App\Models\Kontak;
use App\Models\Event;
use App\Models\EventKategori;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Auth;
use DataTables;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Services\TemplateService;


class HomeController extends Controller
{

    public function index(Request $request)
    {
        $title = "Asosiasi Klinik Indonesia";
        $description = "Asklin adalah asosiasi klinik seluruh indonesia.";

        $sliders = Slider::all();
        $data = Berita::latest()->limit(3)->get();
        $galery = Galery::select('galery.*', 'a.name')
            ->join('users as a', 'a.id', '=', 'galery.created_by')
            ->where('galery.status', '1')
            ->latest()->get();

        $tingkatan = TingkatanPengurus::all();
        $provinsi = DB::table('indonesia_provinces')->pluck('name', 'code');
        $kota = DB::table('indonesia_cities')->pluck('name', 'code');

        $query = DB::table('struktur_organisasi as a')
            ->select(
                'a.*',
                'd.name as kota',
                'e.name as provinsi'
            )
            ->leftJoin('indonesia_cities as d', 'd.code', '=', 'a.id_kota')
            ->leftJoin('indonesia_provinces as e', 'e.code', '=', 'a.id_provinsi');

        if ($request->filled('tingkatan')) {
            $query->where('a.id_tingkatan_pengurus', $request->tingkatan);
        }

        if ($request->filled('provinsi')) {
            $query->where('a.id_provinsi', $request->provinsi);
        }

        if ($request->filled('kota')) {
            $query->where('a.id_kota', $request->kota);
        }

        $struktur = $query->get();

        return TemplateService::view('index', compact(
            'title',
            'description',
            'sliders',
            'data',
            'galery',
            'tingkatan',
            'provinsi',
            'kota',
            'struktur'
        ));
    }


    public function pendaftaran_event()
    {
        $title = "Pendaftaran Event";
        $description = "Pendaftaran Event.";
        $provinsi = Province::get();

        return TemplateService::view('pendaftaran_event', compact('title', 'description', 'provinsi'));
    }

    public function daftarEvent(Request $request)
    {
        PendaftaranEvent::create([
            'nama_peserta' => $request->nama_peserta,
            'keanggotaaan' => $request->keanggotaaan,
            'nama_klinik' => $request->nama_klinik,
            'alamat' => $request->alamat,
            'id_provinsi' => $request->id_provinsi,
            'id_kota' => $request->id_kota,
            'id_kecamatan' => $request->id_kecamatan,
            'id_kelurahan' => $request->id_kelurahan,
            'email' => $request->email,
            'tlf' => $request->tlf,
            'nama_hotel' => $request->nama_hotel,
            'jumlah_kamar' => $request->jumlah_kamar,
            'tgl_masuk' => $request->tgl_masuk,
            'tgl_keluar' => $request->tgl_keluar,
        ]);

        return redirect()->route('home');
    }

    public function sejarah()
    {
        $title = "Sejarah";
        $description = "Sejarah asklin.";

        return TemplateService::view('sejarah', compact('title', 'description'));
    }

    public function visimisi()
    {
        $title = "Visi Misi";
        $description = "Visi misi asklin.";

        return TemplateService::view('visimisi', compact('title', 'description'));
    }

    public function latarbelakang()
    {
        $title = "Latar Belakang";
        $description = "Latar belakang asklin.";

        return TemplateService::view('latarbelakang', compact('title', 'description'));
    }

    public function penguruspusat()
    {
        $title = "Pengurus Pusat";
        $description = "Pengurus pusat asklin.";

        // Dapatkan ID tingkatan pengurus pusat terlebih dahulu
        $tingkatanPengurusPusat = TingkatanPengurus::where('nama_tingkatan', 'Pengurus Pusat')->first();

        if (!$tingkatanPengurusPusat) {
            abort(404, 'Tingkatan Pengurus Pusat tidak ditemukan');
        }

        $struktur = StrukturOrganisasi::query()
            ->select(
                'a.*',
                'd.name as kota_nama',
                'e.name as provinsi_nama'
            )
            ->from('struktur_organisasi as a')
            ->leftJoin('tingkatan_pengurus as b', 'b.id', '=', 'a.id_tingkatan_pengurus')
            ->leftJoin('indonesia_cities as d', 'd.code', '=', 'a.id_kota')
            ->leftJoin('indonesia_provinces as e', 'e.code', '=', 'a.id_provinsi')
            ->where('a.id_tingkatan_pengurus', $tingkatanPengurusPusat->id)
            ->orderBy('a.created_at', 'desc')
            ->firstOrFail();

        $pengurus = StrukturPengurus::where('id_struktur_organisasi', $struktur->id)
            ->whereNull('parent_id')
            ->orderBy('urutan', 'asc')
            ->get();

        return TemplateService::view('penguruspusat', compact('title', 'description', 'struktur', 'pengurus'));
    }

    public function agendakegiatan()
    {
        $title = "Agenda & Kegiatan";
        $description = "Agenda kegiatan asklin.";

        return TemplateService::view('agendakegiatan', compact('title', 'description'));
    }

    public function berita(Request $request)
    {
        $title = "Berita";
        $description = "Berita terbaru seputar dunia kesehatan";

        $data = Berita::select('berita.*', 'berita_kategori.nama')
            ->join('berita_kategori', 'berita_kategori.id', '=', 'berita.id_kategori')
            ->where('berita.status', '1')
            ->orderBy('id', 'DESC')->paginate(10);

        return TemplateService::view('berita', compact('title', 'description', 'data'));
    }

    public function beritaDetail($path)
    {
        $berita = Berita::where('path', $path)->firstOrFail();
        $title = $berita->judul;
        $desc = $berita->konten;

        $recentVisit = BeritaVisit::where('berita_id', $berita->id)
            ->where('ip_address', request()->ip())
            ->where('created_at', '>', now()->subDay())
            ->exists();

        if (!$recentVisit) {
            BeritaVisit::create([
                'berita_id' => $berita->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
        }

        $images = BeritaImage::where('berita_id', $berita->id)->get();

        return TemplateService::view('beritadetail', compact('title', 'desc', 'berita', 'images'));
    }



    public function event(Request $request)
    {
        $title = "Event ASKLIN";
        $description = "Event terbaru dari ASKLIN";

        // Query dengan relationship
        $query = Event::with(['kategori'])
            ->where('status', '1');

        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'LIKE', "%{$search}%")
                    ->orWhere('konten', 'LIKE', "%{$search}%");
            });
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $kategoriId = $request->get('kategori');
            $query->where('id_kategori', $kategoriId);
        }

        // Filter status berdasarkan waktu
        if ($request->filled('status')) {
            $status = $request->get('status');
            $now = now();

            switch ($status) {
                case 'upcoming':
                    $query->where('mulai', '>', $now);
                    break;
                case 'ongoing':
                    $query->where('mulai', '<=', $now)
                        ->where('selesai', '>=', $now);
                    break;
                case 'finished':
                    $query->where('selesai', '<', $now);
                    break;
            }
        }

        // Paginate results
        $event = $query->orderBy('created_at', 'DESC')->paginate(9);

        // Format konten untuk preview
        $event->getCollection()->transform(function ($item) {
            // Clean HTML untuk excerpt
            $cleanContent = strip_tags($item->konten);
            $cleanContent = html_entity_decode($cleanContent);
            $cleanContent = preg_replace('/\s+/', ' ', $cleanContent); // Remove extra spaces
            $item->clean_excerpt = Str::limit($cleanContent, 150);

            return $item;
        });

        // Ambil semua kategori untuk dropdown
        $categories = EventKategori::where('status', 1)->get();

        return TemplateService::view('event', compact('title', 'description', 'event', 'categories'));
    }

    // Controller - Adaptive approach
    public function eventDetail($slug)
    {
        $event = Event::with(['kategori', 'creator'])
            ->where('path', $slug)
            ->where('status', '1')
            ->firstOrFail();

        $title = $event->judul;
        $cleanContent = strip_tags($event->konten);
        $description = Str::limit($cleanContent, 160);

        // Process content dengan adaptive approach
        $event->processed_content = $this->adaptiveContentProcessor($event->konten);

        $relatedEvents = Event::where('id', '!=', $event->id)
            ->where('id_kategori', $event->id_kategori)
            ->where('status', '1')
            ->orderBy('created_at', 'DESC')
            ->limit(3)
            ->get();

        return TemplateService::view('eventdetail', compact('title', 'description', 'event', 'relatedEvents'));
    }

    /**
     * Adaptive Content Processor - Fleksibel untuk semua format input
     */
    private function adaptiveContentProcessor($content)
    {
        // 1. Decode HTML entities
        $content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');

        // 2. Remove website mention
        $content = preg_replace('/^<p>Website\s+[\w\.]+<\/p>/', '', $content);

        // 3. Clean up inline styles tapi preserve structure
        $content = preg_replace('/<span[^>]*style="font-size:[^"]*"[^>]*>(.*?)<\/span>/i', '$1', $content);
        $content = preg_replace('/<span[^>]*>(.*?)<\/span>/i', '$1', $content);

        // 4. Apply smart formatting based on patterns
        $content = $this->applySmartFormatting($content);

        // 5. Enhance readability
        $content = $this->enhanceReadability($content);

        return $content;
    }

    /**
     * Apply smart formatting berdasarkan pattern detection
     */
    private function applySmartFormatting($content)
    {
        // Format sections with emoji/keywords - wrap in cards
        $patterns = [
            // Schedule patterns
            '/(<p[^>]*>.*?)(⏰|🗓️|Tanggal|Waktu|jadwal)(.*?<\/p>)/is' =>
            '<div class="event-card schedule-card"><div class="card-icon">🗓️</div><div class="card-content">$1$2$3</div></div>',

            // Target patterns  
            '/(<p[^>]*>.*?)(👩‍⚕️|👥|TARGET|PESERTA|target peserta)(.*?<\/p>)/is' =>
            '<div class="event-card target-card"><div class="card-icon">👥</div><div class="card-content">$1$2$3</div></div>',

            // Price patterns
            '/(<p[^>]*>.*?)(💵|💰|FREE|GRATIS|Biaya|biaya)(.*?<\/p>)/is' =>
            '<div class="event-card price-card"><div class="card-icon">💰</div><div class="card-content">$1$2$3</div></div>',

            // Contact patterns
            '/(<p[^>]*>.*?)(☎️|📞|KONTAK|kontak|hubungi|CP|\d{10,})(.*?<\/p>)/is' =>
            '<div class="event-card contact-card"><div class="card-icon">📞</div><div class="card-content">$1$2$3</div></div>',

            // Location patterns
            '/(<p[^>]*>.*?)(📍|Tempat|TEMPAT|Hotel|hotel|Alamat)(.*?<\/p>)/is' =>
            '<div class="event-card location-card"><div class="card-icon">📍</div><div class="card-content">$1$2$3</div></div>',
        ];

        foreach ($patterns as $pattern => $replacement) {
            $content = preg_replace($pattern, $replacement, $content);
        }

        return $content;
    }

    /**
     * Enhance readability - make content more readable
     */
    private function enhanceReadability($content)
    {
        // Make phone numbers clickable
        $content = preg_replace(
            '/(\b0\d{2,3}[\s\-]?\d{4}[\s\-]?\d{4,6}\b)/',
            '<a href="tel:+62$1" class="phone-link">$1</a>',
            $content
        );

        // Make URLs into nice buttons
        $content = preg_replace(
            '/(https?:\/\/[^\s<>"\']+)/',
            '<a href="$1" target="_blank" class="url-button"><i class="fas fa-external-link-alt"></i> Buka Link</a>',
            $content
        );

        // Format hashtags
        $content = preg_replace('/(#\w+)/', '<span class="hashtag">$1</span>', $content);

        // Format speaker names (Dr., Prof., etc.)
        $content = preg_replace(
            '/(\b(?:Dr\.|Prof\.|dr\.|Ir\.)\s+[^<\n]+)/i',
            '<span class="speaker-name">$1</span>',
            $content
        );

        // Clean up extra spaces and line breaks
        $content = preg_replace('/(<br>\s*){3,}/', '<br><br>', $content);

        return $content;
    }


    public function galery(Request $request)
    {
        $title = "Galery";
        $description = "Galery terbaru asklin";

        $galery = Galery::select('galery.*', 'a.name')
            ->join('users as a', 'a.id', '=', 'galery.created_by')
            ->where('galery.status', '1')
            ->latest()->get();

        return TemplateService::view('galery', compact('title', 'description', 'galery'));
    }

    public function kontak(Request $request)
    {
        $title = "Kontak";
        $description = "Hubungi PP Asklin";

        return TemplateService::view('kontak', compact('title', 'description'));
    }

    public function storeKontak(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'email' => 'required|email',
            'pesan' => 'required|string|max:1000',
        ]);

        $today = now()->startOfDay();
        $jumlahPesan = Kontak::where('email', $request->email)
            ->where('created_at', '>=', $today)
            ->count();

        if ($jumlahPesan >= 2) {
            return response()->json([
                'success' => false,
                'message' => 'Mohon maaf anda sudah mengirim terlalu banyak pesan hari ini'
            ], 429);
        }

        Kontak::create([
            'nama'  => $request->nama,
            'email' => $request->email,
            'pesan' => $request->pesan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesan kamu berhasil dikirim.'
        ]);
    }


    public function UserProfile()
    {
        $title = "User Profile";
        $desc = "";

        $anggota = Anggota::select('anggota.*', 'indonesia_provinces.name as provinsi', 'indonesia_cities.name as kota', 'indonesia_districts.name as kecamatan', 'indonesia_villages.name as kelurahan')
            ->leftJoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'anggota.id_provinsi')
            ->leftJoin('indonesia_cities', 'indonesia_cities.code', '=', 'anggota.id_kota')
            ->leftJoin('indonesia_districts', 'indonesia_districts.code', '=', 'anggota.id_kecamatan')
            ->leftJoin('indonesia_villages', 'indonesia_villages.code', '=', 'anggota.id_kelurahan')
            ->where('id_user', Auth::user()->id)->first();
        return TemplateService::view('user_profile', compact('title', 'desc', 'anggota'));
    }

    public function createAnggota()
    {
        Anggota::create([
            'id_user' => Auth::user()->id,
            'id_provinsi' => '31',
            'id_kota' => '3171',
            'id_kecamatan' => '317101',
            'id_kelurahan' => '3171011001'
        ]);

        return redirect()->route('syarat.ketentuan');
    }

    public function updateAnggota(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'sio' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // max 5MB
        ]);
    
        // Ambil data anggota yang sudah ada dan pastikan milik user yang login
        $anggotaLama = Anggota::where('id', $id)->where('id_user', Auth::user()->id)->firstOrFail();
    
        // Handle fasilitas
        if (!empty($request->id_fasilitas)) {
            $fasilitas = implode(",", $request->id_fasilitas);
        } else {
            $fasilitas = NULL;
        }
    
        $destinationPath = 'images/file/';
        $sio = $anggotaLama->sio; // Default: gunakan SIO yang sudah ada
    
        // Handle upload SIO baru
        if ($request->hasFile('sio')) {
            $sioFile = $request->file('sio');
            $img_ext = strtolower($sioFile->getClientOriginalExtension());
            $newSioName = 'sio-' . time() . '-' . $anggotaLama->id . '.' . $img_ext;
            
            // Pastikan direktori ada
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            // Upload file baru
            if ($sioFile->move($destinationPath, $newSioName)) {
                // Hapus file SIO lama jika ada dan berbeda dengan yang baru
                if ($anggotaLama->sio && $anggotaLama->sio !== $newSioName && file_exists($destinationPath . $anggotaLama->sio)) {
                    unlink($destinationPath . $anggotaLama->sio);
                }
                $sio = $newSioName;
            } else {
                session()->flash('warning', 'Upload SIO gagal, data lainnya berhasil disimpan');
            }
        }
    
        // Prepare data untuk update
        $updateData = [
            'nama_klinik' => $request->nama_klinik,
            'status' => $request->status,
            'id_provinsi' => $request->id_provinsi,
            'id_kota' => $request->id_kota,
            'id_kecamatan' => $request->id_kecamatan,
            'id_kelurahan' => $request->id_kelurahan,
            'nama_kontak' => $request->nama_kontak,
            'email' => $request->email,
            'tlf' => $request->tlf,
            'status_pendaftar' => $request->status_pendaftar,
            'status_kepemilikan' => $request->status_kepemilikan,
            'nama_pemilik_klinik' => $request->nama_pemilik_klinik,
            'bentuk_badan_hukum' => $request->nama_badan_hukum,
            'nama_badan_usaha' => $request->nama_badan_usaha,
            'bentuk_badan_usaha' => $request->bentuk_badan_usaha,
            'jenis_klinik' => $request->jenis_klinik,
            'alamat_klinik' => $request->alamat_klinik,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'kode_pos' => $request->kode_pos,
            'tlf_klinik' => $request->tlf_klinik,
            'id_fasilitas' => $fasilitas,
            'id_layanan' => is_array($request->id_layanan) ? implode(",", $request->id_layanan) : $request->id_layanan,
            'no_ijin' => $request->no_ijin,
            'tgl_ijin' => $request->tgl_ijin ? date('Y-m-d', strtotime($request->tgl_ijin)) : null,
            'tgl_akhir_ijin' => $request->tgl_akhir_ijin ? date('Y-m-d', strtotime($request->tgl_akhir_ijin)) : null,
            'fasilitas_klinik' => is_array($request->fasilitas_klinik) ? implode(",", $request->fasilitas_klinik) : $request->fasilitas_klinik,
            'id_user' => Auth::user()->id,
            'status' => 'create_dokter',
            'sio' => $sio
        ];
    
        try {
            Anggota::where('id', $anggotaLama->id)->where('id_user', Auth::user()->id)->update($updateData);
            
            $message = 'Data anggota berhasil diperbarui';
            if ($request->hasFile('sio')) {
                $message .= ' dan file SIO berhasil diupload';
            }
            
            session()->flash('success', $message);
            return redirect()->route('pendaftaran.sdm');
            
        } catch (\Exception $e) {
            // Jika database update gagal dan ada file baru yang diupload, hapus file tersebut
            if ($request->hasFile('sio') && isset($newSioName) && file_exists($destinationPath . $newSioName)) {
                unlink($destinationPath . $newSioName);
            }
            
            session()->flash('error', 'Gagal memperbarui data: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function SyaratKetentuan()
    {
        $title = "Syarat & Ketentuan";
        $desc = "";

        return view('frontend.syarat_ketentuan', compact('title', 'desc'));
    }

    public function PendaftaranAnggota()
    {
        $title = "Pendaftaran Anggota";
        $desc = "";

        $anggota = Anggota::where('id_user', Auth::user()->id)->first();
        $provinsi = Province::get();
        $fasilitas = Fasilitas::where('status', '1')->get();
        $fasilitas_klinik = FasilitasKlinik::where('status', '1')->get();

        return view('frontend.pendaftaran_anggota', compact('title', 'desc', 'anggota', 'provinsi', 'fasilitas', 'fasilitas_klinik'));
    }

    public function PendaftaranSDM()
    {
        $title = "Pendaftaran SDM";
        $desc = "";

        $anggota = Anggota::where('id_user', Auth::user()->id)->first();

        return view('frontend.pendaftaran_sdm', compact('title', 'desc', 'anggota'));
    }

    //SDM PJ

    public function sdm_pjk(Request $request)
    {
        if ($request->ajax()) {
            $anggota = Anggota::where('id_user', Auth::user()->id)->first();
            $data = SDM::where(['id_klinik' => $anggota->id, 'id_kategori_sdm' => '1'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $actionBtn = '
                        <a href="javascript:void(0)" onclick="deletepj(' . $data["id"] . ')" ><i class="fa fa-trash-alt"></i>Hapus</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store_sdm_pj(Request $request)
    {
        $request->validate([
            'file_sip_pj' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_str_pj' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $anggota = Anggota::where('id_user', Auth::user()->id)->firstOrFail();
        $destinationPath = 'images/file/';
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $existing = $request->id_pj ? SDM::where('id', $request->id_pj)->where('id_klinik', $anggota->id)->first() : null;
        $file_sip = $existing ? $existing->file_sip : null;
        $file_str = $existing ? $existing->file_str : null;

        if ($request->hasFile('file_sip_pj')) {
            $img_ext = strtolower($request->file('file_sip_pj')->getClientOriginalExtension());
            $new_file_sip = 'sip-' . time() . '-' . uniqid() . '.' . $img_ext;
            if ($request->file('file_sip_pj')->move($destinationPath, $new_file_sip)) {
                if ($file_sip && file_exists($destinationPath . $file_sip)) {
                    unlink($destinationPath . $file_sip);
                }
                $file_sip = $new_file_sip;
            }
        }

        if ($request->hasFile('file_str_pj')) {
            $img_ext1 = strtolower($request->file('file_str_pj')->getClientOriginalExtension());
            $new_file_str = 'str-' . time() . '-' . uniqid() . '.' . $img_ext1;
            if ($request->file('file_str_pj')->move($destinationPath, $new_file_str)) {
                if ($file_str && file_exists($destinationPath . $file_str)) {
                    unlink($destinationPath . $file_str);
                }
                $file_str = $new_file_str;
            }
        }

        $data = SDM::updateOrCreate(
            ['id' => $request->id_pj, 'id_klinik' => $anggota->id],
            [
                'nama_dokter' => $request->nama_dokter_pj,
                'nama' => $request->nama_dokter_pj,
                'id_kategori_sdm' => $request->id_kategori_sdm_pj,
                'peranan' => 'penanggung jawab',
                'npa_idi' => $request->npa_idi_pj,
                'no_str' => $request->no_str_pj,
                'no_sip' => $request->no_sip_pj,
                'email_dokter' => $request->email_dokter_pj,
                'tgl_akhir_sip' => $request->tgl_akhir_sip_pj ? date('Y-m-d', strtotime($request->tgl_akhir_sip_pj)) : null,
                'no_tlf' => $request->no_tlf_pj,
                'file_sip' => $file_sip,
                'file_str' => $file_str,
                'id_klinik' => $anggota->id
            ]
        );

        return Response()->json($data);
    }

    public function delete_sdm_pj(Request $request)
    {
        $anggota = Anggota::where('id_user', Auth::user()->id)->firstOrFail();
        $data = SDM::where('id', $request->id)->where('id_klinik', $anggota->id)->first();
        if ($data) {
            if ($data->file_str && file_exists(public_path('images/file/' . $data->file_str))) {
                unlink(public_path('images/file/' . $data->file_str));
            }
            if ($data->file_sip && file_exists(public_path('images/file/' . $data->file_sip))) {
                unlink(public_path('images/file/' . $data->file_sip));
            }
            $data->delete();
        }
        return Response()->json($data);
    }

    //SDM DP

    public function sdm_dp(Request $request)
    {
        if ($request->ajax()) {
            $anggota = Anggota::where('id_user', Auth::user()->id)->first();
            $data = SDM::where(['id_klinik' => $anggota->id, 'id_kategori_sdm' => '2'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $actionBtn = '
                        <a href="javascript:void(0)" onclick="deletepj(' . $data["id"] . ')" ><i class="fa fa-trash-alt"></i>Hapus</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store_sdm_dp(Request $request)
    {
        $request->validate([
            'file_str_dp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $anggota = Anggota::where('id_user', Auth::user()->id)->firstOrFail();
        $destinationPath = 'images/file/';
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $existing = $request->id_dp ? SDM::where('id', $request->id_dp)->where('id_klinik', $anggota->id)->first() : null;
        $file_str = $existing ? $existing->file_str : null;

        if ($request->hasFile('file_str_dp')) {
            $img_ext1 = strtolower($request->file('file_str_dp')->getClientOriginalExtension());
            $new_file_str = 'str-' . time() . '-' . uniqid() . '.' . $img_ext1;
            if ($request->file('file_str_dp')->move($destinationPath, $new_file_str)) {
                if ($file_str && file_exists($destinationPath . $file_str)) {
                    unlink($destinationPath . $file_str);
                }
                $file_str = $new_file_str;
            }
        }

        $data = SDM::updateOrCreate(
            ['id' => $request->id_dp, 'id_klinik' => $anggota->id],
            [
                'nama_dokter' => $request->nama_dokter_dp,
                'nama' => $request->nama_dokter_dp,
                'id_kategori_sdm' => $request->id_kategori_sdm_dp,
                'peranan' => 'dokter',
                'npa_idi' => $request->npa_idi_dp,
                'no_str' => $request->no_str_dp,
                'no_sip' => $request->no_sip_dp,
                'email_dokter' => $request->email_dokter_dp,
                'tgl_akhir_sip' => $request->tgl_akhir_sip_dp ? date('Y-m-d', strtotime($request->tgl_akhir_sip_dp)) : null,
                'no_tlf' => $request->no_tlf_dp,
                'file_str' => $file_str,
                'id_klinik' => $anggota->id
            ]
        );

        return Response()->json($data);
    }

    //SDM TK

    public function sdm_tk(Request $request)
    {
        if ($request->ajax()) {
            $anggota = Anggota::where('id_user', Auth::user()->id)->first();
            $data = SDM::where(['id_klinik' => $anggota->id, 'id_kategori_sdm' => '3'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $actionBtn = '
                        <a href="javascript:void(0)" onclick="deletepj(' . $data["id"] . ')" ><i class="fa fa-trash-alt"></i>Hapus</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store_sdm_tk(Request $request)
    {
        $request->validate([
            'file_str_tk' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $anggota = Anggota::where('id_user', Auth::user()->id)->firstOrFail();
        $destinationPath = 'images/file/';
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $existing = $request->id_tk ? SDM::where('id', $request->id_tk)->where('id_klinik', $anggota->id)->first() : null;
        $file_str = $existing ? $existing->file_str : null;

        if ($request->hasFile('file_str_tk')) {
            $img_ext1 = strtolower($request->file('file_str_tk')->getClientOriginalExtension());
            $new_file_str = 'str-' . time() . '-' . uniqid() . '.' . $img_ext1;
            if ($request->file('file_str_tk')->move($destinationPath, $new_file_str)) {
                if ($file_str && file_exists($destinationPath . $file_str)) {
                    unlink($destinationPath . $file_str);
                }
                $file_str = $new_file_str;
            }
        }

        $data = SDM::updateOrCreate(
            ['id' => $request->id_tk, 'id_klinik' => $anggota->id],
            [
                'nama' => $request->nama_tk,
                'id_kategori_sdm' => '3',
                'no_sib_sik' => $request->no_sib_sik_tk,
                'no_str' => $request->no_str_tk,
                'tgl_akhir_str' => $request->tgl_akhir_str_tk ? date('Y-m-d', strtotime($request->tgl_akhir_str_tk)) : null,
                'no_tlf' => $request->no_tlf_tk,
                'file_str' => $file_str,
                'id_klinik' => $anggota->id
            ]
        );

        return Response()->json($data);
    }

    //SDM TK

    public function sdm_tkl(Request $request)
    {
        if ($request->ajax()) {
            $anggota = Anggota::where('id_user', Auth::user()->id)->first();
            $data = SDM::where(['id_klinik' => $anggota->id, 'id_kategori_sdm' => '4'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $actionBtn = '
                        <a href="javascript:void(0)" onclick="deletepj(' . $data["id"] . ')" ><i class="fa fa-trash-alt"></i>Hapus</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store_sdm_tkl(Request $request)
    {
        $request->validate([
            'file_str_tkl' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $anggota = Anggota::where('id_user', Auth::user()->id)->firstOrFail();
        $destinationPath = 'images/file/';
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $existing = $request->id_tkl ? SDM::where('id', $request->id_tkl)->where('id_klinik', $anggota->id)->first() : null;
        $file_str = $existing ? $existing->file_str : null;

        if ($request->hasFile('file_str_tkl')) {
            $img_ext1 = strtolower($request->file('file_str_tkl')->getClientOriginalExtension());
            $new_file_str = 'str-' . time() . '-' . uniqid() . '.' . $img_ext1;
            if ($request->file('file_str_tkl')->move($destinationPath, $new_file_str)) {
                if ($file_str && file_exists($destinationPath . $file_str)) {
                    unlink($destinationPath . $file_str);
                }
                $file_str = $new_file_str;
            }
        }

        $data = SDM::updateOrCreate(
            ['id' => $request->id_tkl, 'id_klinik' => $anggota->id],
            [
                'nama' => $request->nama_tkl,
                'farmasi_apoteker' => $request->farmasi_apoteker_tkl,
                'id_kategori_sdm' => '4',
                'no_str' => $request->no_str_tkl,
                'tgl_akhir_str' => $request->tgl_akhir_str_tkl ? date('Y-m-d', strtotime($request->tgl_akhir_str_tkl)) : null,
                'no_sip' => $request->no_sip_tkl,
                'no_sib_sik' => $request->no_sip_tkl,
                'ket_sib_sik' => $request->ket_sib_sik_tkl,
                'no_tlf' => $request->no_tlf_tk,
                'file_str' => $file_str,
                'id_klinik' => $anggota->id
            ]
        );

        return Response()->json($data);
    }

    //SDM Lain

    public function sdm_lain(Request $request)
    {
        if ($request->ajax()) {
            $anggota = Anggota::where('id_user', Auth::user()->id)->first();
            $data = SDM::where(['id_klinik' => $anggota->id, 'id_kategori_sdm' => '5'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $actionBtn = '
                        <a href="javascript:void(0)" onclick="deletelain(' . $data["id"] . ')" ><i class="fa fa-trash-alt"></i>Hapus</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store_sdm_lain(Request $request)
    {
        $request->validate([
            'file_str_lain' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $anggota = Anggota::where('id_user', Auth::user()->id)->firstOrFail();
        $destinationPath = 'images/file/';
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $existing = $request->id_lain ? SDM::where('id', $request->id_lain)->where('id_klinik', $anggota->id)->first() : null;
        $file_ijazah = $existing ? $existing->file_str : null;

        if ($request->hasFile('file_str_lain')) {
            $img_ext = strtolower($request->file('file_str_lain')->getClientOriginalExtension());
            $new_file_ijazah = 'ijazah-' . time() . '-' . uniqid() . '.' . $img_ext;
            if ($request->file('file_str_lain')->move($destinationPath, $new_file_ijazah)) {
                if ($file_ijazah && file_exists($destinationPath . $file_ijazah)) {
                    unlink($destinationPath . $file_ijazah);
                }
                $file_ijazah = $new_file_ijazah;
            }
        }

        $data = SDM::updateOrCreate(
            ['id' => $request->id_lain, 'id_klinik' => $anggota->id],
            [
                'nama' => $request->nama_lain,
                'ijazah_terakhir' => $request->ijazah_terakhir_lain,
                'id_kategori_sdm' => '5',
                'jabatan' => $request->jabatan_lain,
                'file_str' => $file_ijazah,
                'id_klinik' => $anggota->id
            ]
        );

        return Response()->json($data);
    }

    public function delete_sdm_lain(Request $request)
    {
        $anggota = Anggota::where('id_user', Auth::user()->id)->firstOrFail();
        $data = SDM::where('id', $request->id)->where('id_klinik', $anggota->id)->first();
        if ($data) {
            if ($data->file_str && file_exists(public_path('images/file/' . $data->file_str))) {
                unlink(public_path('images/file/' . $data->file_str));
            }
            $data->delete();
        }
        return Response()->json($data);
    }

    //Rumah Sakit Terdekat

    public function sdm_rumah_sakit(Request $request)
    {
        if ($request->ajax()) {
            $anggota = Anggota::where('id_user', Auth::user()->id)->first();
            $data = RumahSakit::where('id_klinik', $anggota->id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $actionBtn = '
                        <a href="javascript:void(0)" onclick="deleters(' . $data["id"] . ')" ><i class="fa fa-trash-alt"></i>Hapus</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store_sdm_rumah_sakit(Request $request)
    {
        $anggota = Anggota::where('id_user', Auth::user()->id)->firstOrFail();
        $data = RumahSakit::updateOrCreate(
            ['id' => $request->id_rs, 'id_klinik' => $anggota->id],
            [
                'nama' => $request->nama_rs,
                'alamat' => $request->alamat_rs,
                'jarak' => $request->jarak_rs,
                'telepon' => $request->telepon_rs,
                'id_klinik' => $anggota->id
            ]
        );

        return Response()->json($data);
    }

    public function delete_sdm_rumah_sakit(Request $request)
    {
        $anggota = Anggota::where('id_user', Auth::user()->id)->firstOrFail();
        $data = RumahSakit::where('id', $request->id)->where('id_klinik', $anggota->id)->delete();
        return Response()->json($data);
    }

    //Provider Asuransi

    public function sdm_asuransi(Request $request)
    {
        if ($request->ajax()) {
            $anggota = Anggota::where('id_user', Auth::user()->id)->first();
            $data = Asuransi::where('id_klinik', $anggota->id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $actionBtn = '
                        <a href="javascript:void(0)" onclick="deleteas(' . $data["id"] . ')" ><i class="fa fa-trash-alt"></i>Hapus</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store_sdm_asuransi(Request $request)
    {
        $request->validate([
            'bukti_as' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $anggota = Anggota::where('id_user', Auth::user()->id)->firstOrFail();
        $destinationPath = 'images/file/';
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $existing = $request->id_as ? Asuransi::where('id', $request->id_as)->where('id_klinik', $anggota->id)->first() : null;
        $bukti = $existing ? $existing->bukti : null;

        if ($request->hasFile('bukti_as')) {
            $img_ext = strtolower($request->file('bukti_as')->getClientOriginalExtension());
            $new_bukti = 'bukti-' . time() . '-' . uniqid() . '.' . $img_ext;
            if ($request->file('bukti_as')->move($destinationPath, $new_bukti)) {
                if ($bukti && file_exists($destinationPath . $bukti)) {
                    unlink($destinationPath . $bukti);
                }
                $bukti = $new_bukti;
            }
        }

        $data = Asuransi::updateOrCreate(
            ['id' => $request->id_as, 'id_klinik' => $anggota->id],
            [
                'nama' => $request->nama_as,
                'nama_kontak' => $request->nama_kontak_as,
                'alamat' => $request->alamat_as,
                'telepon' => $request->telepon_as,
                'bukti' => $bukti,
                'id_klinik' => $anggota->id
            ]
        );

        return Response()->json($data);
    }

    public function delete_sdm_asuransi(Request $request)
    {
        $anggota = Anggota::where('id_user', Auth::user()->id)->firstOrFail();
        $data = Asuransi::where('id', $request->id)->where('id_klinik', $anggota->id)->first();
        if ($data) {
            if ($data->bukti && file_exists(public_path('images/file/' . $data->bukti))) {
                unlink(public_path('images/file/' . $data->bukti));
            }
            $data->delete();
        }
        return Response()->json($data);
    }

    //Foto Klinik

    public function sdm_foto(Request $request)
    {
        if ($request->ajax()) {
            $anggota = Anggota::where('id_user', Auth::user()->id)->first();
            $data = FotoKlinik::where('id_klinik', $anggota->id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $actionBtn = '
                        <a href="javascript:void(0)" onclick="deletefoto(' . $data["id"] . ')" ><i class="fa fa-trash-alt"></i>Hapus</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store_sdm_foto(Request $request)
    {
        $request->validate([
            'foto_foto' => 'nullable|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        $anggota = Anggota::where('id_user', Auth::user()->id)->firstOrFail();
        $destinationPath = 'images/file/';
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $existing = $request->id_foto ? FotoKlinik::where('id', $request->id_foto)->where('id_klinik', $anggota->id)->first() : null;
        $foto = $existing ? $existing->foto : null;

        if ($request->hasFile('foto_foto')) {
            $img_ext = strtolower($request->file('foto_foto')->getClientOriginalExtension());
            $new_foto = 'ruang-' . time() . '-' . uniqid() . '.' . $img_ext;
            if ($request->file('foto_foto')->move($destinationPath, $new_foto)) {
                if ($foto && file_exists($destinationPath . $foto)) {
                    unlink($destinationPath . $foto);
                }
                $foto = $new_foto;
            }
        }

        $data = FotoKlinik::updateOrCreate(
            ['id' => $request->id_foto, 'id_klinik' => $anggota->id],
            [
                'nama' => $request->nama_foto,
                'foto' => $foto,
                'status' => 'waiting',
                'id_klinik' => $anggota->id
            ]
        );

        return Response()->json($data);
    }

    public function delete_sdm_foto(Request $request)
    {
        $anggota = Anggota::where('id_user', Auth::user()->id)->firstOrFail();
        $data = FotoKlinik::where('id', $request->id)->where('id_klinik', $anggota->id)->first();
        if ($data) {
            if ($data->foto && file_exists(public_path('images/file/' . $data->foto))) {
                unlink(public_path('images/file/' . $data->foto));
            }
            $data->delete();
        }
        return Response()->json($data);
    }

    public function draft()
    {
        Anggota::where('id_user', Auth::user()->id)->update([
            'status' => 'create_dokter'
        ]);

        return redirect()->route('pendaftaran.sdm');
    }

    public function submitAkhir()
    {
        Anggota::where('id_user', Auth::user()->id)->update([
            'status' => 'waiting'
        ]);

        return redirect()->route('user.profile');
    }

    public function uploadFotoKlinik(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|file|mimes:jpg,jpeg,png|max:3072',
        ]);

        $anggota = Anggota::where('id_user', Auth::user()->id)->firstOrFail();
        $destinationPath = 'images/file/';
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $foto = $anggota->logo;
        if ($request->hasFile('logo')) {
            $img_ext = strtolower($request->file('logo')->getClientOriginalExtension());
            $new_foto = 'logo-' . time() . '-' . uniqid() . '.' . $img_ext;
            if ($request->file('logo')->move($destinationPath, $new_foto)) {
                if ($foto && file_exists($destinationPath . $foto)) {
                    unlink($destinationPath . $foto);
                }
                $foto = $new_foto;
            }
        }

        $anggota->update([
            'logo' => $foto
        ]);

        return redirect()->route('user.profile');
    }

    public function tagihan()
    {
        $title = "Tagihan";
        $description = "Asklin adalah asosiasi klinik seluruh indonesia.";

        $kategori = PembayaranKategori::where('status', '1')->get();
        $anggota = Anggota::where('id_user', Auth::user()->id)->first();

        return view('frontend.pembayaran', compact('title', 'description', 'anggota', 'kategori'));
    }

    public function getTagihan(Request $request)
    {
        if ($request->ajax()) {
            $anggota = Anggota::where('id_user', Auth::user()->id)->first();
            $data = Pembayaran::select('pembayaran.*', 'a.nama')->join('pembayaran_kategori as a', 'a.id', '=', 'pembayaran.id_kategori')->where('id_anggota', $anggota->id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function storeTagihan(Request $request)
    {
        $request->validate([
            'bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'id_kategori' => 'required',
            'total' => 'required',
        ]);

        $anggota = Anggota::where('id_user', Auth::user()->id)->firstOrFail();
        $destinationPath = 'images/file/';
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $existing = $request->id ? Pembayaran::where('id', $request->id)->where('id_anggota', $anggota->id)->first() : null;
        $bukti = $existing ? $existing->bukti : null;

        if ($request->hasFile('bukti')) {
            $img_ext = strtolower($request->file('bukti')->getClientOriginalExtension());
            $new_bukti = 'bukti-pembayaran-' . time() . '-' . uniqid() . '.' . $img_ext;
            if ($request->file('bukti')->move($destinationPath, $new_bukti)) {
                if ($bukti && file_exists($destinationPath . $bukti)) {
                    unlink($destinationPath . $bukti);
                }
                $bukti = $new_bukti;
            }
        }

        $data = Pembayaran::updateOrCreate(
            ['id' => $request->id, 'id_anggota' => $anggota->id],
            [
                'id_kategori' => $request->id_kategori,
                'status' => '0',
                'id_anggota' => $anggota->id,
                'keterangan' => $request->keterangan,
                'bukti' => $bukti,
                'tanggal_bayar' => date('Y-m-d'),
                'total' => $request->total
            ]
        );

        return Response()->json($data);
    }

    public function sertifikat()
    {
        $title = "Sertifikat";
        $description = "Asklin adalah asosiasi klinik seluruh indonesia.";

        $anggota = Anggota::where('id_user', Auth::user()->id)->first();

        return view('frontend.sertifikat', compact('title', 'description', 'anggota'));
    }

    public function getSertifikat(Request $request)
    {
        if ($request->ajax()) {
            $anggota = Anggota::where('id_user', Auth::user()->id)->first();
            $data = Sertifikat::select('sertifikat.*', 'a.nama_klinik', 'a.no_anggota', 'a.updated_at')
                ->join('anggota as a', 'a.id', '=', 'sertifikat.id_anggota')
                ->where('sertifikat.id_anggota', $anggota->id)
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $actionBtn = '
                        <a href="' . route("cetak_sertifikat", $data->id) . '" class="btn btn-info btn-sm" >Cetak</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function downloadSK($id)
    {
        $anggota = Anggota::select(
            'anggota.*',
            \DB::raw('GROUP_CONCAT(DISTINCT a.nama SEPARATOR ", ") as nama_fasilitas_klinik'),
            'b.name as kota',
            'c.name as provinsi'
        )
            ->leftJoin('fasilitas_klinik as a', \DB::raw("FIND_IN_SET(a.id, anggota.fasilitas_klinik)"), ">", \DB::raw("'0'"))
            ->leftJoin('indonesia_cities as b', 'b.code', '=', 'anggota.id_kota')
            ->leftJoin('indonesia_provinces as c', 'c.code', '=', 'anggota.id_provinsi')
            ->groupBy('anggota.id')
            ->where('anggota.id', $id)
            ->first();

        if (!$anggota) {
            abort(404, 'Data anggota tidak ditemukan.');
        }

        $user = Auth::user();
        if ($user) {
            $isAdminOrStaff = $user->hasAnyRole(['Superadmin', 'Admin Pusat', 'Admin Cabang', 'Admin Daerah', 'Sekjen', 'Ketua Umum', 'Bendahara']);
            if (!$isAdminOrStaff && $anggota->id_user != $user->id) {
                abort(403, 'Anda tidak memiliki hak akses untuk dokumen ini.');
            }
        }

        $id = $anggota->id;

        // Periksa apakah created_on kosong, jika ya, gunakan created_at atau verifikasi_pusat
        $created_on = !empty($anggota->created_on)
            ? Carbon::createFromTimestamp($anggota->created_on)->format('d-m-Y H:i:s')
            : ($anggota->created_at
                ? Carbon::parse($anggota->created_at)->format('d-m-Y H:i:s')
                : Carbon::parse($anggota->verifikasi_pusat)->format('d-m-Y H:i:s'));

        try {
            ob_start();
            $content = view('backend.verifikasi.printsk', compact('anggota', 'created_on'));

            $html2pdf = new Html2Pdf('P', 'F4', 'fr', true, 'UTF-8', 1);
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->pdf->SetTitle('SK Anggota ASKLIN');
            $html2pdf->writeHTML($content);
            $html2pdf->output('sk_anggota.pdf');
        } catch (Html2PdfException $e) {
            $html2pdf->clean();

            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
        exit();
    }


    public function cetakSertifikat($id)
    {
        $anggota = Sertifikat::select(
            'sertifikat.*',
            'a.id_user',
            'a.nama_klinik',
            'a.no_anggota',
            'a.updated_at',
            'a.nama_kontak',
            'a.alamat_klinik',
            'a.rt',
            'a.rw',
            'b.name as kelurahan',
            'c.name as kecamatan',
            'd.name as kota',
            'e.name as provinsi',
            'a.id_kota',
            'a.id_provinsi'
        )
            ->join('anggota as a', 'a.id', '=', 'sertifikat.id_anggota')
            ->leftJoin('indonesia_villages as b', 'b.code', '=', 'a.id_kelurahan')
            ->leftJoin('indonesia_districts as c', 'c.code', '=', 'a.id_kecamatan')
            ->leftJoin('indonesia_cities as d', 'd.code', '=', 'a.id_kota')
            ->leftJoin('indonesia_provinces as e', 'e.code', '=', 'a.id_provinsi')
            ->where('sertifikat.id', $id)
            ->first();

        if (!$anggota) {
            abort(404, 'Sertifikat tidak ditemukan.');
        }

        $user = Auth::user();
        if ($user) {
            $isAdminOrStaff = $user->hasAnyRole(['Superadmin', 'Admin Pusat', 'Admin Cabang', 'Admin Daerah', 'Sekjen', 'Ketua Umum', 'Bendahara']);
            if (!$isAdminOrStaff && $anggota->id_user != $user->id) {
                abort(403, 'Anda tidak memiliki hak akses untuk dokumen ini.');
            }
        }

        $id = $anggota->id;

        try {
            ob_start();
            $content = view('frontend.print_sertifikat', compact('anggota'));

            $html2pdf = new Html2Pdf('L', 'A4', 'fr', true, 'UTF-8', 1);
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->pdf->SetTitle('Sertifikat Anggota ASKLIN');
            $html2pdf->writeHTML($content);
            $html2pdf->output('sk_anggota.pdf');
        } catch (Html2PdfException $e) {
            $html2pdf->clean();

            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
        exit();
    }
}
