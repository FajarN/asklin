<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{RakernasPeriode, RakernasPeserta, RakernasUndangan, RakernasGaleri};
use Laravolt\Indonesia\Models\Province;
use App\Services\TemplateService;
use Illuminate\Support\Str;

class RakernasController extends Controller
{
    /**
     * Halaman Utama / Landing Page Publik Event RAKERNAS ASKLIN
     */
    public function index(Request $request)
    {
        $allPeriodes = RakernasPeriode::orderBy('tahun', 'desc')->orderBy('rakernas_ke', 'asc')->get();
        $activePeriode = RakernasPeriode::where('status', '1')->first();

        // Fitur pilih riwayat Rakernas (bisa dipilih via query param 'periode_id')
        $selectedPeriode = null;
        if ($request->filled('periode_id')) {
            $selectedPeriode = RakernasPeriode::find($request->periode_id);
        }

        // Default ke periode aktif, atau yang pertama jika tidak ada yang aktif
        if (!$selectedPeriode) {
            $selectedPeriode = $activePeriode ?? $allPeriodes->first();
        }

        $isPeriodeActive = $selectedPeriode && $selectedPeriode->status == '1';

        // Pihak yang diundang (Kemenkes, BPJS, Gubernur/Bupati/Pemda, Sponsor)
        $undangan = [];
        $galeriVenue = [];
        $galeriSlider = [];

        if ($selectedPeriode) {
            $undangan = RakernasUndangan::where('id_periode', $selectedPeriode->id)
                ->orderBy('urutan', 'asc')
                ->get();

            $galeriVenue = RakernasGaleri::where('id_periode', $selectedPeriode->id)
                ->whereIn('kategori', ['venue', 'hotel', 'kegiatan'])
                ->orderBy('urutan', 'asc')
                ->get();

            $galeriSlider = RakernasGaleri::where('id_periode', $selectedPeriode->id)
                ->where('kategori', 'slider')
                ->orderBy('urutan', 'asc')
                ->get();
        }

        $provinsi = Province::orderBy('name', 'asc')->get();

        // Template metadata
        $title = $selectedPeriode ? $selectedPeriode->nama : 'Rapat Kerja Nasional (RAKERNAS) ASKLIN';
        $description = $selectedPeriode ? ($selectedPeriode->tema ?? 'Rapat Kerja Nasional Asosiasi Klinik Indonesia (ASKLIN)') : 'RAKERNAS ASKLIN';

        $dataView = compact(
            'allPeriodes',
            'activePeriode',
            'selectedPeriode',
            'isPeriodeActive',
            'undangan',
            'galeriVenue',
            'galeriSlider',
            'provinsi',
            'title',
            'description'
        );

        $activeTemplate = TemplateService::getActiveTemplate();
        if ($activeTemplate === TemplateService::TEMPLATE_CUSTOM_ORG) {
            return view('frontend.templates.custom_org.rakernas', $dataView);
        }
        if ($activeTemplate === TemplateService::TEMPLATE_MODERN_ORG) {
            return view('frontend.templates.modern_org.rakernas', $dataView);
        }

        return view('frontend.rakernas.index', $dataView);
    }

    /**
     * Proses Pendaftaran Peserta Rakernas Publik
     * Otomatis terhubung ke periode Rakernas yang sedang aktif
     */
    public function store(Request $request)
    {
        // 1. Ambil periode Rakernas yang aktif
        $activePeriode = RakernasPeriode::where('status', '1')->first();

        if (!$activePeriode) {
            return response()->json([
                'success' => false,
                'message' => 'Pendaftaran RAKERNAS saat ini belum dibuka atau tidak ada periode yang sedang aktif.'
            ], 422);
        }

        // 2. Cek apakah batas waktu pendaftaran sudah lewat
        if ($activePeriode->tgl_tutup_daftar && now()->startOfDay()->gt($activePeriode->tgl_tutup_daftar)) {
            return response()->json([
                'success' => false,
                'message' => 'Batas waktu pendaftaran untuk ' . $activePeriode->nama . ' telah ditutup pada ' . $activePeriode->tgl_tutup_daftar->translatedFormat('d F Y') . '.'
            ], 422);
        }

        // 3. Validasi: Nama klinik WAJIB diisi sesuai instruksi
        $request->validate([
            'nama_peserta' => 'required|string|max:255',
            'nama_klinik' => 'required|string|max:255',
            'no_hp' => 'required|string|max:50',
            'email' => 'required|email|max:150',
            'id_provinsi' => 'nullable|string',
            'cabang' => 'nullable|string',
            'jabatan' => 'nullable|string|max:150',
            'alamat_klinik' => 'nullable|string',
            'kebutuhan_khusus' => 'nullable|string',
            'status_kehadiran' => 'nullable|in:Hadir,Online',
        ], [
            'nama_peserta.required' => 'Nama lengkap peserta wajib diisi.',
            'nama_klinik.required' => 'Nama klinik wajib diisi sebagai data utama pendaftaran RAKERNAS.',
            'no_hp.required' => 'Nomor WhatsApp / HP aktif wajib diisi untuk konfirmasi pendaftaran.',
            'email.required' => 'Alamat email aktif wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
        ]);

        // Cek duplikasi pendaftaran di periode yang sama
        $existing = RakernasPeserta::where('id_periode', $activePeriode->id)
            ->where(function($q) use ($request) {
                $q->where('email', $request->email)
                  ->orWhere('no_hp', $request->no_hp);
            })
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Nomor HP ini sudah terdaftar pada ' . $activePeriode->nama . ' dengan No. Registrasi: ' . $existing->no_registrasi . '.'
            ], 422);
        }

        // Simpan pendaftaran baru
        $peserta = RakernasPeserta::create([
            'id_periode' => $activePeriode->id,
            'nama_peserta' => $request->nama_peserta,
            'nama_klinik' => $request->nama_klinik,
            'jabatan' => $request->jabatan ?? 'Perwakilan Klinik',
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'id_provinsi' => $request->id_provinsi,
            'cabang' => $request->cabang,
            'alamat_klinik' => $request->alamat_klinik,
            'kebutuhan_khusus' => $request->kebutuhan_khusus,
            'status_kehadiran' => $request->status_kehadiran ?? 'Hadir',
            'status_pembayaran' => ($activePeriode->biaya_registrasi && $activePeriode->biaya_registrasi != '0') ? 'Pending' : 'Gratis',
            'status' => '1',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran RAKERNAS Berhasil! Data Anda telah terdaftar.',
            'no_registrasi' => $peserta->no_registrasi,
            'nama_peserta' => $peserta->nama_peserta,
            'nama_klinik' => $peserta->nama_klinik,
            'periode' => $activePeriode->nama,
        ]);
    }

    /**
     * Cetak Kartu / Bukti Pendaftaran Peserta Rakernas
     */
    public function cetakKartu($id)
    {
        $peserta = RakernasPeserta::with('periode', 'provinsi', 'kota')->findOrFail($id);
        return view('frontend.rakernas.kartu', compact('peserta'));
    }
}