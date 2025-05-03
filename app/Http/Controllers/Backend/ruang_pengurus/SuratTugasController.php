<?php 

namespace App\Http\Controllers\Backend\ruang_pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuratKeluar;
use App\Models\SuratTugas;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class SuratTugasController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = SuratTugas::with(['suratKeluar', 'details']);

        if ($startDate && $endDate) {
            $query->whereHas('suratKeluar', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('tgl_surat', [$startDate, $endDate]);
            });
        }

        $suratTugas = $query->paginate(10)->withQueryString();

        return view('backend.surat_tugas.index', compact('suratTugas', 'startDate', 'endDate'));
    }


    private function formatTanggalBulanTahun($tanggal)
    {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $tanggal = \Carbon\Carbon::parse($tanggal);
        return $tanggal->format('d') . ' ' . $bulan[(int) $tanggal->format('m')] . ' ' . $tanggal->format('Y');
    }

    public function print($id)
    {
        $suratKeluar = \App\Models\SuratKeluar::with(['jenisSurat', 'suratTugas.details'])->findOrFail($id);

        if (!$suratKeluar->suratTugas) {
            abort(404, 'Surat Tugas tidak ditemukan.');
        }

        $suratKeluar->tgl_surat_formatted = $this->formatTanggalBulanTahun($suratKeluar->tgl_surat);
        $suratKeluar->suratTugas->tgl_agenda_formatted = $this->formatTanggalBulanTahun($suratKeluar->suratTugas->tgl_agenda);

        $pdf = Pdf::loadView('backend.surat_tugas.print', compact('suratKeluar'));
        return $pdf->stream('surat_tugas_'.$id.'.pdf');
    }


}
