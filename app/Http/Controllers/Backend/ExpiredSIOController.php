<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\{Anggota, SDM, RumahSakit, Asuransi, FotoKlinik, Sertifikat};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;
use Auth;
use DataTables;
// Import Spatie Simple Excel
use Spatie\SimpleExcel\SimpleExcelWriter;

class ExpiredSIOController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:expired-sio', ['only' => ['index', 'list', 'exportExcel', 'exportPdf']]);
    }

    public function index()
    {
        return view('backend.expired_sio.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->getDataQuery($request)->get();

            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $searchQuery = Str::lower($request->get('search'));
                        $instance->collection = $instance->collection->filter(function ($data) use ($searchQuery) {
                            if (Str::contains(Str::lower($data['nama_klinik']), $searchQuery)) {
                                return true;
                            }
                            if (Str::contains(Str::lower($data['no_anggota']), $searchQuery)) {
                                return true;
                            }
                            if (Str::contains(Str::lower($data['name']), $searchQuery)) {
                                return true;
                            }
                            return false;
                        });
                    }
                })
                ->addIndexColumn()
                ->editColumn('tgl_akhir_ijin', function ($row) {
                    $today = Carbon::today();
                    $tglAkhir = $row->tgl_akhir_ijin ? Carbon::parse($row->tgl_akhir_ijin) : null;

                    if (!$tglAkhir) {
                        return '<span class="badge badge-secondary">-</span>';
                    }

                    $diff = $today->diffInDays($tglAkhir, false);

                    $warna = 'badge badge-secondary';
                    if ($tglAkhir->isPast()) {
                        $warna = 'badge badge-danger';
                    } elseif ($tglAkhir->isTomorrow()) {
                        $warna = 'badge badge-warning';
                    } elseif ($today->diffInDays($tglAkhir) <= 7) {
                        $warna = 'badge badge-warning';
                    } elseif ($today->diffInDays($tglAkhir) <= 30) {
                        $warna = 'badge badge-success';
                    }

                    return '<span class="' . $warna . '">' . $tglAkhir->format('d-m-Y') . '</span>';
                })
                ->rawColumns(['tgl_akhir_ijin', 'action'])
                ->make(true);
        }
    }

    /**
     * Method untuk mengambil query data yang sama untuk list dan export
     */
    private function getDataQuery(Request $request)
    {
        return Anggota::select(
            'anggota.*',
            'indonesia_cities.name',
            'indonesia_villages.name as kelurahan',
            'indonesia_districts.name as kecamatan',
            'indonesia_provinces.name as provinsi',
            \DB::raw('GROUP_CONCAT(DISTINCT fasilitas_klinik.nama SEPARATOR ", ") as kriteria')
        )
            ->leftjoin('fasilitas_klinik', \DB::raw('FIND_IN_SET(fasilitas_klinik.id, anggota.fasilitas_klinik)'), '>', \DB::raw("'0'"))
            ->leftjoin('indonesia_cities', 'indonesia_cities.code', '=', 'anggota.id_kota')
            ->leftjoin('indonesia_villages', 'indonesia_villages.code', '=', 'anggota.id_kelurahan')
            ->leftjoin('indonesia_districts', 'indonesia_districts.code', '=', 'anggota.id_kecamatan')
            ->leftjoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'anggota.id_provinsi')
            ->groupBy('anggota.id')
            ->where('anggota.status', 'approved')
            ->when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
                $query->where('id_kota', Auth::user()->kota);
            })
            ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
                $query->where('id_provinsi', Auth::user()->provinsi);
            })
            ->when(Auth::user()->hasRole('Sekjen'), function ($query) use ($request) {
                $query->where('anggota.status', 'Verifikasi Sekjen');
            })
            ->when(Auth::user()->hasRole('Ketua Umum'), function ($query) use ($request) {
                $query->where('anggota.status_pembayaran', '1');
            })
            ->when(Auth::user()->hasRole('Bendahara'), function ($query) use ($request) {
                $query->where('anggota.status', 'Verifikasi Bendahara');
            })
            ->when($request->filter_expired, function ($query) use ($request) {
                $today = Carbon::today();
                $besok = Carbon::tomorrow();
                $seminggu = Carbon::today()->addDays(7);
                $sebulan = Carbon::today()->addDays(30);

                if ($request->filter_expired == 'expired') {
                    $query->whereDate('tgl_akhir_ijin', '<', $today);
                } elseif ($request->filter_expired == 'besok') {
                    $query->whereDate('tgl_akhir_ijin', '=', $besok);
                } elseif ($request->filter_expired == 'seminggu') {
                    $query
                        ->whereDate('tgl_akhir_ijin', '>', $today)
                        ->whereDate('tgl_akhir_ijin', '<=', $seminggu);
                } elseif ($request->filter_expired == 'sebulan') {
                    $query
                        ->whereDate('tgl_akhir_ijin', '>', $today)
                        ->whereDate('tgl_akhir_ijin', '<=', $sebulan);
                }
            })
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q
                        ->where('anggota.nama_klinik', 'like', "%{$search}%")
                        ->orWhere('anggota.no_anggota', 'like', "%{$search}%")
                        ->orWhere('indonesia_cities.name', 'like', "%{$search}%");
                });
            });
    }

    /**
     * Export ke Excel dengan Force Download
     */
    public function exportExcel(Request $request)
    {
        try {
            $data = $this->getDataQuery($request)->get();
            $filterName = $this->getFilterName($request->filter_expired);
            $fileName = 'expired_sio_' . $filterName . '_' . date('Y-m-d_H-i-s') . '.xlsx';
            $filePath = storage_path('app/public/exports/' . $fileName);

            // Pastikan direktori exists
            if (!file_exists(storage_path('app/public/exports'))) {
                mkdir(storage_path('app/public/exports'), 0755, true);
            }

            // Buat Excel Writer
            $writer = SimpleExcelWriter::create($filePath);

            // Tambah header dengan styling
            $writer->addHeader([
                'No',
                'No Anggota',
                'Nama Klinik',
                'Kab/Kota',
                'Kecamatan',
                'Kelurahan',
                'Provinsi',
                'Email',
                'Telepon',
                'No Ijin',
                'Tgl Ijin',
                'Tgl Akhir Ijin',
                'Status SIO',
                'Sisa Hari',
                'Nama Kontak',
                'Alamat Klinik',
                'Jenis Klinik',
                'Fasilitas Klinik'
            ]);

            // Tambah data
            foreach ($data as $index => $item) {
                $today = Carbon::today();
                $tglAkhir = $item->tgl_akhir_ijin ? Carbon::parse($item->tgl_akhir_ijin) : null;

                // Tentukan status berdasarkan tanggal
                $status = '-';
                $sisaHari = '-';

                if ($tglAkhir) {
                    $diffDays = $today->diffInDays($tglAkhir, false);

                    if ($tglAkhir->isPast()) {
                        $status = 'EXPIRED';
                        $sisaHari = abs($diffDays) . ' hari lalu';
                    } elseif ($tglAkhir->isTomorrow()) {
                        $status = 'BESOK EXPIRED';
                        $sisaHari = '1 hari';
                    } elseif ($today->diffInDays($tglAkhir) <= 7) {
                        $status = 'DALAM SEMINGGU';
                        $sisaHari = $diffDays . ' hari';
                    } elseif ($today->diffInDays($tglAkhir) <= 30) {
                        $status = 'DALAM SEBULAN';
                        $sisaHari = $diffDays . ' hari';
                    } else {
                        $status = 'AMAN';
                        $sisaHari = $diffDays . ' hari';
                    }
                }

                $writer->addRow([
                    $index + 1,
                    $item->no_anggota ?: '-',
                    $item->nama_klinik ?: '-',
                    $item->name ?: '-',
                    $item->kecamatan ?: '-',
                    $item->kelurahan ?: '-',
                    $item->provinsi ?: '-',
                    $item->email ?: '-',
                    $item->tlf ?: '-',
                    $item->no_ijin ?: '-',
                    $item->tgl_ijin ? Carbon::parse($item->tgl_ijin)->format('d-m-Y') : '-',
                    $tglAkhir ? $tglAkhir->format('d-m-Y') : '-',
                    $status,
                    $sisaHari,
                    $item->nama_kontak ?: '-',
                    $item->alamat_klinik ?: '-',
                    $item->jenis_klinik ?: '-',
                    $item->kriteria ?: '-'
                ]);
            }

            // Tambah summary info
            $stats = $this->calculateStats($data);

            $writer->addRow([]);  // Empty row
            $writer->addRow(['RINGKASAN EXPORT:']);
            $writer->addRow(['Total Data:', $stats['total']]);
            $writer->addRow(['Sudah Expired:', $stats['expired']]);
            $writer->addRow(['Besok Expired:', $stats['besok']]);
            $writer->addRow(['Dalam Seminggu:', $stats['seminggu']]);
            $writer->addRow(['Dalam Sebulan:', $stats['sebulan']]);
            $writer->addRow(['Aman:', $stats['aman']]);
            $writer->addRow(['Diekspor pada:', Carbon::now()->format('d-m-Y H:i:s')]);
            $writer->addRow(['Filter:', ucwords(str_replace('_', ' ', $filterName))]);
            $writer->addRow(['User:', Auth::user()->name . ' (' . Auth::user()->getRoleNames()->first() . ')']);

            // Close writer
            $writer->close();

            // Log untuk debugging
            \Log::info('Excel export created', [
                'file_path' => $filePath,
                'file_exists' => file_exists($filePath),
                'file_size' => file_exists($filePath) ? filesize($filePath) : 0,
                'user' => Auth::id()
            ]);

            // Force download dengan header yang tepat
            return response()->download($filePath, $fileName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Cache-Control' => 'max-age=0',
                'Pragma' => 'public'
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            \Log::error('Error exporting Excel: ' . $e->getMessage(), [
                'user' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal export Excel: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export ke PDF dengan Force Download
     */
   /**
 * Export ke PDF dengan Language Code yang Benar
 */
public function exportPdf(Request $request)
{
    $html2pdf = null; // Initialize variable
    
    try {
        $data = $this->getDataQuery($request)->get();
        $filterName = $this->getFilterName($request->filter_expired);
        $today = Carbon::now();
        
        // Hitung statistik
        $stats = $this->calculateStats($data);
        
        // Log untuk debugging
        \Log::info('PDF export started', [
            'data_count' => $data->count(),
            'filter' => $filterName,
            'user' => Auth::id()
        ]);
        
        // Cek apakah template ada
        if (!view()->exists('backend.expired_sio.export_pdf')) {
            throw new \Exception('Template PDF tidak ditemukan: backend.expired_sio.export_pdf');
        }
        
        // Generate HTML dari template
        $html = view('backend.expired_sio.export_pdf', compact('data', 'filterName', 'today', 'stats'))->render();
        
        // Validasi HTML tidak kosong
        if (empty($html)) {
            throw new \Exception('HTML template kosong');
        }
        
        // Log HTML length untuk debugging
        \Log::info('HTML generated', [
            'html_length' => strlen($html),
            'user' => Auth::id()
        ]);
        
        // Create HTML2PDF instance dengan language code yang didukung
        // Gunakan 'en' (English) karena 'id' (Indonesia) tidak didukung
        $html2pdf = new Html2Pdf('L', 'A4', 'en'); // Ganti 'id' dengan 'en'
        $html2pdf->setDefaultFont('Arial');
        
        // Set test mode untuk debugging
        $html2pdf->setTestTdInOnePage(false);
        $html2pdf->setTestIsImage(false);
        
        // Write HTML ke PDF
        $html2pdf->writeHTML($html);
        
        $fileName = 'expired_sio_' . $filterName . '_' . date('Y-m-d_H-i-s') . '.pdf';
        
        // Output PDF dengan force download
        $pdfContent = $html2pdf->output('', 'S'); // S = String output
        
        // Log success
        \Log::info('PDF generated successfully', [
            'file_name' => $fileName,
            'pdf_size' => strlen($pdfContent),
            'user' => Auth::id()
        ]);
        
        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Content-Length' => strlen($pdfContent),
            'Cache-Control' => 'max-age=0',
            'Pragma' => 'public'
        ]);
        
    } catch (Html2PdfException $e) {
        // Clean up HTML2PDF jika ada
        if ($html2pdf !== null) {
            $html2pdf->clean();
        }
        
        $formatter = new ExceptionFormatter($e);
        $errorMessage = $formatter->getHtmlMessage();
        
        \Log::error('HTML2PDF Error', [
            'error' => $errorMessage,
            'user' => Auth::id(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal generate PDF: ' . strip_tags($errorMessage)
        ], 500);
        
    } catch (\Exception $e) {
        // Clean up HTML2PDF jika ada
        if ($html2pdf !== null) {
            $html2pdf->clean();
        }
        
        \Log::error('Error exporting PDF', [
            'error' => $e->getMessage(),
            'user' => Auth::id(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal export PDF: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Helper method untuk mendapatkan nama filter
     */
    private function getFilterName($filter)
    {
        switch ($filter) {
            case 'expired':
                return 'sudah_expired';
            case 'besok':
                return 'besok';
            case 'seminggu':
                return 'dalam_seminggu';
            case 'sebulan':
                return 'dalam_sebulan';
            default:
                return 'semua';
        }
    }

    /**
     * Helper method untuk menghitung statistik
     */
    private function calculateStats($data)
    {
        $today = Carbon::today();
        $expired = 0;
        $besok = 0;
        $seminggu = 0;
        $sebulan = 0;
        $aman = 0;

        foreach ($data as $item) {
            if (!$item->tgl_akhir_ijin)
                continue;

            $tglAkhir = Carbon::parse($item->tgl_akhir_ijin);

            if ($tglAkhir->isPast()) {
                $expired++;
            } elseif ($tglAkhir->isTomorrow()) {
                $besok++;
            } elseif ($today->diffInDays($tglAkhir) <= 7) {
                $seminggu++;
            } elseif ($today->diffInDays($tglAkhir) <= 30) {
                $sebulan++;
            } else {
                $aman++;
            }
        }

        return [
            'total' => $data->count(),
            'expired' => $expired,
            'besok' => $besok,
            'seminggu' => $seminggu,
            'sebulan' => $sebulan,
            'aman' => $aman
        ];
    }
}
