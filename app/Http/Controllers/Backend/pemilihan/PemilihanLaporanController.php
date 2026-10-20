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
use Spatie\SimpleExcel\SimpleExcelWriter;

class PemilihanLaporanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:pemilihan-laporan', ['only' => ['index', 'exportExcel']]);
        $this->middleware('permission:pemilihan-audit-suara', ['only' => ['audit', 'auditExport']]);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $pemilihanList = Pemilihan::with(['provinsi', 'kota', 'kandidat', 'suara'])->forUser($user)->latest()->paginate(15);

        // Precompute stats for each
        foreach ($pemilihanList as $p) {
            $dptQuery = Anggota::whereIn(DB::raw('LOWER(status)'), ['approved', 'approve']);
            if ($p->tingkat === 'cabang') {
                $dptQuery->where('id_kota', $p->id_kota);
            } else {
                $dptQuery->where('id_provinsi', $p->id_provinsi);
            }
            $p->total_dpt = $dptQuery->count();
            $p->total_suara = $p->suara->count();
            $p->persen_partisipasi = $p->total_dpt > 0 ? round(($p->total_suara / $p->total_dpt) * 100, 1) : 0;
            $p->calon_terunggul = $p->kandidat->sortByDesc('total_suara')->first();
        }

        return view('backend.pemilihan.laporan', compact('pemilihanList'));
    }

    public function exportExcel($id)
    {
        $user = Auth::user();
        $pemilihan = Pemilihan::with(['provinsi', 'kota', 'kandidat'])->forUser($user)->findOrFail($id);

        $dptQuery = Anggota::whereIn(DB::raw('LOWER(status)'), ['approved', 'approve']);
        if ($pemilihan->tingkat === 'cabang') {
            $dptQuery->where('id_kota', $pemilihan->id_kota);
        } else {
            $dptQuery->where('id_provinsi', $pemilihan->id_provinsi);
        }
        $totalDpt = $dptQuery->count();
        $totalSuara = PemilihanSuara::where('pemilihan_id', $pemilihan->id)->count();
        $persenPartisipasi = $totalDpt > 0 ? round(($totalSuara / $totalDpt) * 100, 1) : 0;

        $fileName = 'Rekap_Pemilihan_' . str_replace(' ', '_', $pemilihan->judul) . '_' . date('Ymd_His') . '.xlsx';
        $exportDir = storage_path('app/public/exports');
        if (!file_exists($exportDir)) {
            mkdir($exportDir, 0755, true);
        }
        $filePath = $exportDir . '/' . $fileName;

        $writer = SimpleExcelWriter::create($filePath);

        // Add summary header rows
        $writer->addRow([
            'Nomor Urut' => 'INFORMASI UMUM',
            'Nama Calon Ketua' => $pemilihan->judul,
            'Gelar' => '',
            'Asal Klinik' => '',
            'Total Suara' => '',
            'Persentase (%)' => '',
            'Status' => '',
        ]);
        $writer->addRow([
            'Nomor Urut' => 'Wilayah',
            'Nama Calon Ketua' => $pemilihan->wilayah_nama,
            'Gelar' => '',
            'Asal Klinik' => '',
            'Total Suara' => '',
            'Persentase (%)' => '',
            'Status' => '',
        ]);
        $writer->addRow([
            'Nomor Urut' => 'Periode',
            'Nama Calon Ketua' => $pemilihan->periode_text,
            'Gelar' => '',
            'Asal Klinik' => '',
            'Total Suara' => '',
            'Persentase (%)' => '',
            'Status' => '',
        ]);
        $writer->addRow([
            'Nomor Urut' => 'Daftar Pemilih Tetap (DPT Sah)',
            'Nama Calon Ketua' => $totalDpt . ' Klinik',
            'Gelar' => '',
            'Asal Klinik' => '',
            'Total Suara' => '',
            'Persentase (%)' => '',
            'Status' => '',
        ]);
        $writer->addRow([
            'Nomor Urut' => 'Total Suara Masuk',
            'Nama Calon Ketua' => $totalSuara . ' Suara',
            'Gelar' => '',
            'Asal Klinik' => '',
            'Total Suara' => '',
            'Persentase (%)' => '',
            'Status' => '',
        ]);
        $writer->addRow([
            'Nomor Urut' => 'Tingkat Partisipasi',
            'Nama Calon Ketua' => $persenPartisipasi . '%',
            'Gelar' => '',
            'Asal Klinik' => '',
            'Total Suara' => '',
            'Persentase (%)' => '',
            'Status' => '',
        ]);
        $writer->addRow([
            'Nomor Urut' => '--------------------',
            'Nama Calon Ketua' => '--------------------',
            'Gelar' => '----------',
            'Asal Klinik' => '--------------------',
            'Total Suara' => '-----------',
            'Persentase (%)' => '--------------',
            'Status' => '-----------',
        ]);

        // Candidates data
        $kandidats = $pemilihan->kandidat()->orderBy('nomor_urut', 'asc')->get();
        $pemenang = $totalSuara > 0 ? $kandidats->sortByDesc('total_suara')->first() : null;

        foreach ($kandidats as $kand) {
            $suara = $kand->total_suara;
            $persen = $totalSuara > 0 ? round(($suara / $totalSuara) * 100, 1) : 0;
            $statusCalon = ($pemenang && $pemenang->id === $kand->id && $totalSuara > 0) ? 'UNGGUL / TERPILIH' : '-';

            $writer->addRow([
                'Nomor Urut' => 'No. ' . $kand->nomor_urut,
                'Nama Calon Ketua' => $kand->nama_calon,
                'Gelar' => $kand->gelar ?? '-',
                'Asal Klinik' => $kand->asal_klinik ?? '-',
                'Total Suara' => $suara,
                'Persentase (%)' => $persen . '%',
                'Status' => $statusCalon,
            ]);
        }

        $writer->addRow([
            'Nomor Urut' => 'CATATAN HUKUM',
            'Nama Calon Ketua' => 'Rekapitulasi Suara Bersifat Rahasia (LUBER JURDIL). Kerahasiaan pilihan anggota dilindungi AD/ART ASKLIN.',
            'Gelar' => '',
            'Asal Klinik' => '',
            'Total Suara' => '',
            'Persentase (%)' => '',
            'Status' => '',
        ]);

        return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
    }

    public function audit(Request $request, $id)
    {
        // STRICT GUARD: Superadmin only!
        if (!Auth::user()->can('pemilihan-audit-suara')) {
            abort(403, 'Akses Terlarang: Kerahasiaan suara pemilih dilindungi undang-undang.');
        }

        $pemilihan = Pemilihan::with(['provinsi', 'kota', 'kandidat'])->findOrFail($id);

        $suaraLogs = PemilihanSuara::with(['anggota', 'user', 'kandidat'])
            ->where('pemilihan_id', $pemilihan->id)
            ->latest('waktu_vote')
            ->paginate(30);

        return view('backend.pemilihan.audit', compact('pemilihan', 'suaraLogs'));
    }

    public function auditExport($id)
    {
        if (!Auth::user()->can('pemilihan-audit-suara')) {
            abort(403, 'Akses Terlarang: Kerahasiaan suara pemilih dilindungi undang-undang.');
        }

        $pemilihan = Pemilihan::with(['provinsi', 'kota'])->findOrFail($id);
        $suaraLogs = PemilihanSuara::with(['anggota', 'user', 'kandidat'])
            ->where('pemilihan_id', $pemilihan->id)
            ->oldest('waktu_vote')
            ->get();

        $fileName = 'AUDIT_RAHASIA_Pemilihan_' . str_replace(' ', '_', $pemilihan->judul) . '_' . date('Ymd_His') . '.xlsx';
        $exportDir = storage_path('app/public/exports');
        if (!file_exists($exportDir)) {
            mkdir($exportDir, 0755, true);
        }
        $filePath = $exportDir . '/' . $fileName;

        $writer = SimpleExcelWriter::create($filePath);

        $writer->addHeader([
            'No',
            'Waktu Memberikan Suara',
            'No Anggota Klinik',
            'Nama Klinik',
            'Email Akun',
            'Calon Dipilih',
            'Nomor Urut',
            'IP Address',
            'User Agent'
        ]);

        foreach ($suaraLogs as $idx => $s) {
            $writer->addRow([
                'No' => $idx + 1,
                'Waktu Memberikan Suara' => $s->waktu_vote ? $s->waktu_vote->format('Y-m-d H:i:s') : '-',
                'No Anggota Klinik' => $s->anggota ? $s->anggota->no_anggota : '-',
                'Nama Klinik' => $s->anggota ? $s->anggota->nama_klinik : '-',
                'Email Akun' => $s->user ? $s->user->email : '-',
                'Calon Dipilih' => $s->kandidat ? $s->kandidat->nama_calon : '-',
                'Nomor Urut' => $s->kandidat ? $s->kandidat->nomor_urut : '-',
                'IP Address' => $s->ip_address ?? '-',
                'User Agent' => $s->user_agent ?? '-',
            ]);
        }

        return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
    }
}
