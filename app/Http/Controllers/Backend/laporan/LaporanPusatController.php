<?php

namespace App\Http\Controllers\Backend\laporan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Anggota};
use DataTables;
use Illuminate\Support\Str;
use Auth;
use Laravolt\Indonesia\Models\Province;
use Carbon\Carbon;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

class LaporanPusatController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:laporan-pusat', ['only' => ['index', 'list', 'exportExcel', 'exportPdf']]);
    }

    /**
     * Available columns untuk dynamic selection
     */
    private function getAvailableColumns()
    {
        return [
            'id' => ['label' => 'ID', 'type' => 'number'],
            'no_anggota' => ['label' => 'No. Anggota', 'type' => 'text'],
            'nama_klinik' => ['label' => 'Nama Klinik', 'type' => 'text'],
            'provinsi' => ['label' => 'Provinsi', 'type' => 'text'],
            'name' => ['label' => 'Kab/Kota', 'type' => 'text'],
            'kecamatan' => ['label' => 'Kecamatan', 'type' => 'text'],
            'kelurahan' => ['label' => 'Kelurahan', 'type' => 'text'],
            'jenis_klinik' => ['label' => 'Jenis Klinik', 'type' => 'text'],
            'kriteria' => ['label' => 'Kriteria Klinik', 'type' => 'text'],
            'status' => ['label' => 'Status Verifikasi', 'type' => 'text'],
            'status_pembayaran' => ['label' => 'Status Pembayaran', 'type' => 'text'],
            'no_ijin' => ['label' => 'No. Ijin', 'type' => 'text'],
            'tgl_ijin' => ['label' => 'Tanggal Ijin', 'type' => 'date'],
            'tgl_akhir_ijin' => ['label' => 'Tanggal Akhir Ijin', 'type' => 'date'],
            'nama_kontak' => ['label' => 'Nama Kontak', 'type' => 'text'],
            'email' => ['label' => 'Email', 'type' => 'text'],
            'tlf' => ['label' => 'Telepon', 'type' => 'text'],
            'status_pendaftar' => ['label' => 'Status Pendaftar', 'type' => 'text'],
            'status_kepemilikan' => ['label' => 'Status Kepemilikan', 'type' => 'text'],
            'nama_pemilik_klinik' => ['label' => 'Nama Pemilik', 'type' => 'text'],
            'alamat_klinik' => ['label' => 'Alamat Klinik', 'type' => 'text'],
            'rt' => ['label' => 'RT', 'type' => 'text'],
            'rw' => ['label' => 'RW', 'type' => 'text'],
            'kode_pos' => ['label' => 'Kode Pos', 'type' => 'text'],
            'tlf_klinik' => ['label' => 'Telepon Klinik', 'type' => 'text'],
            'created_on' => ['label' => 'Tanggal Dibuat', 'type' => 'date'],
            'verifikasi_cabang' => ['label' => 'Verifikasi Cabang', 'type' => 'datetime'],
            'verifikasi_pusat' => ['label' => 'Verifikasi Pusat', 'type' => 'datetime']
        ];
    }

    public function index()
    {
        $provinsi = Province::get();
        $availableColumns = $this->getAvailableColumns();
        
        return view('backend.laporan.pusat', compact('provinsi', 'availableColumns'));
    }

    /**
     * Method untuk mengambil data dengan column selection
     */
    private function getDataQuery(Request $request)
    {
        return Anggota::select(
            'anggota.*',
            'indonesia_cities.name',
            'indonesia_provinces.name as provinsi',
            'indonesia_districts.name as kecamatan',
            'indonesia_villages.name as kelurahan',
            \DB::raw('GROUP_CONCAT(DISTINCT fasilitas_klinik.nama SEPARATOR ", ") as kriteria')
        )
        ->leftjoin("fasilitas_klinik", \DB::raw("FIND_IN_SET(fasilitas_klinik.id, anggota.fasilitas_klinik)"), ">", \DB::raw("'0'"))
        ->leftjoin("indonesia_cities", 'indonesia_cities.code', '=', 'anggota.id_kota')
        ->leftjoin("indonesia_provinces", 'indonesia_provinces.code', '=', 'anggota.id_provinsi')
        ->leftjoin("indonesia_districts", 'indonesia_districts.code', '=', 'anggota.id_kecamatan')
        ->leftjoin("indonesia_villages", 'indonesia_villages.code', '=', 'anggota.id_kelurahan')
        ->when($request->provinsi, function ($query) use ($request) {
            $query->where('anggota.id_provinsi', $request->provinsi);
        })
        ->when($request->search, function ($query) use ($request) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('anggota.nama_klinik', 'like', "%{$search}%")
                  ->orWhere('anggota.no_anggota', 'like', "%{$search}%")
                  ->orWhere('indonesia_cities.name', 'like', "%{$search}%");
            });
        })
        ->groupBy('anggota.id');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            // Validasi selected columns
            $selectedColumns = $request->selected_columns ? explode(',', $request->selected_columns) : [];
            $availableColumns = array_keys($this->getAvailableColumns());
            
            // Filter hanya kolom yang valid
            $validColumns = array_intersect($selectedColumns, $availableColumns);
            
            if (empty($validColumns)) {
                return response()->json([
                    'draw' => $request->draw,
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => 'Pilih minimal satu kolom untuk ditampilkan'
                ]);
            }

            $data = $this->getDataQuery($request)->get();

            // Format data sesuai kebutuhan
            foreach ($data as $item) {
                $item->formatted_created_on = Carbon::createFromTimestamp($item->created_on)->format('d-m-Y');
                $item->formatted_tgl_ijin = $item->tgl_ijin ? Carbon::parse($item->tgl_ijin)->format('d-m-Y') : '-';
                $item->formatted_tgl_akhir_ijin = $item->tgl_akhir_ijin ? Carbon::parse($item->tgl_akhir_ijin)->format('d-m-Y') : '-';
                $item->formatted_verifikasi_cabang = $item->verifikasi_cabang ? Carbon::parse($item->verifikasi_cabang)->format('d-m-Y H:i') : '-';
                $item->formatted_verifikasi_pusat = $item->verifikasi_pusat ? Carbon::parse($item->verifikasi_pusat)->format('d-m-Y H:i') : '-';
                $item->formatted_status_pembayaran = $item->status_pembayaran == '1' ? 'Lunas' : 'Belum Lunas';
            }

            $datatables = Datatables::of($data)
                ->addIndexColumn();

            // Tambahkan kolom sesuai selection
            foreach ($validColumns as $column) {
                switch ($column) {
                    case 'created_on':
                        $datatables->addColumn($column, '{{$formatted_created_on}}');
                        break;
                    case 'tgl_ijin':
                        $datatables->addColumn($column, '{{$formatted_tgl_ijin}}');
                        break;
                    case 'tgl_akhir_ijin':
                        $datatables->addColumn($column, '{{$formatted_tgl_akhir_ijin}}');
                        break;
                    case 'verifikasi_cabang':
                        $datatables->addColumn($column, '{{$formatted_verifikasi_cabang}}');
                        break;
                    case 'verifikasi_pusat':
                        $datatables->addColumn($column, '{{$formatted_verifikasi_pusat}}');
                        break;
                    case 'status_pembayaran':
                        $datatables->addColumn($column, '{{$formatted_status_pembayaran}}');
                        break;
                    default:
                        $datatables->addColumn($column, '{{$' . $column . '}}');
                        break;
                }
            }

            return $datatables->make(true);
        }
    }

    /**
     * Export ke Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            $selectedColumns = $request->selected_columns ? explode(',', $request->selected_columns) : [];
            $availableColumnsData = $this->getAvailableColumns();
            
            if (empty($selectedColumns)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pilih minimal satu kolom untuk export'
                ], 400);
            }

            $data = $this->getDataQuery($request)->get();
            
            $fileName = 'laporan_anggota_' . date('Y-m-d_H-i-s') . '.xlsx';
            $filePath = storage_path('app/public/exports/' . $fileName);
            
            // Pastikan direktori exists
            if (!file_exists(storage_path('app/public/exports'))) {
                mkdir(storage_path('app/public/exports'), 0755, true);
            }

            $writer = SimpleExcelWriter::create($filePath);

            // Header sesuai kolom terpilih
            $headers = ['No'];
            foreach ($selectedColumns as $column) {
                $headers[] = $availableColumnsData[$column]['label'] ?? ucwords(str_replace('_', ' ', $column));
            }
            $writer->addHeader($headers);

            // Data rows
            foreach ($data as $index => $item) {
                $row = [$index + 1];
                
                foreach ($selectedColumns as $column) {
                    switch ($column) {
                        case 'created_on':
                            $row[] = Carbon::createFromTimestamp($item->created_on)->format('d-m-Y');
                            break;
                        case 'tgl_ijin':
                            $row[] = $item->tgl_ijin ? Carbon::parse($item->tgl_ijin)->format('d-m-Y') : '-';
                            break;
                        case 'tgl_akhir_ijin':
                            $row[] = $item->tgl_akhir_ijin ? Carbon::parse($item->tgl_akhir_ijin)->format('d-m-Y') : '-';
                            break;
                        case 'verifikasi_cabang':
                            $row[] = $item->verifikasi_cabang ? Carbon::parse($item->verifikasi_cabang)->format('d-m-Y H:i') : '-';
                            break;
                        case 'verifikasi_pusat':
                            $row[] = $item->verifikasi_pusat ? Carbon::parse($item->verifikasi_pusat)->format('d-m-Y H:i') : '-';
                            break;
                        case 'status_pembayaran':
                            $row[] = $item->status_pembayaran == '1' ? 'Lunas' : 'Belum Lunas';
                            break;
                        default:
                            $row[] = $item->{$column} ?: '-';
                            break;
                    }
                }
                
                $writer->addRow($row);
            }

            // Summary info
            $writer->addRow([]);
            $writer->addRow(['INFORMASI EXPORT:']);
            $writer->addRow(['Total Data:', $data->count()]);
            $writer->addRow(['Kolom Dipilih:', implode(', ', array_map(function($col) use ($availableColumnsData) {
                return $availableColumnsData[$col]['label'] ?? $col;
            }, $selectedColumns))]);
            $writer->addRow(['Diekspor pada:', Carbon::now()->format('d-m-Y H:i:s')]);
            $writer->addRow(['User:', Auth::user()->name]);

            $writer->close();

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
     * Export ke PDF
     */
    public function exportPdf(Request $request)
    {
        $html2pdf = null;
        
        try {
            $selectedColumns = $request->selected_columns ? explode(',', $request->selected_columns) : [];
            $availableColumnsData = $this->getAvailableColumns();
            
            if (empty($selectedColumns)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pilih minimal satu kolom untuk export'
                ], 400);
            }

            $data = $this->getDataQuery($request)->get();
            $today = Carbon::now();
            
            $html = view('backend.laporan.export_pdf', compact('data', 'selectedColumns', 'availableColumnsData', 'today'))->render();
            
            $html2pdf = new Html2Pdf('L', 'A4', 'en');
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML($html);
            
            $fileName = 'laporan_anggota_' . date('Y-m-d_H-i-s') . '.pdf';
            $pdfContent = $html2pdf->output('', 'S');
            
            return response($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Content-Length' => strlen($pdfContent),
                'Cache-Control' => 'max-age=0',
                'Pragma' => 'public'
            ]);
            
        } catch (Html2PdfException $e) {
            if ($html2pdf !== null) {
                $html2pdf->clean();
            }
            
            $formatter = new ExceptionFormatter($e);
            $errorMessage = $formatter->getHtmlMessage();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate PDF: ' . strip_tags($errorMessage)
            ], 500);
            
        } catch (\Exception $e) {
            if ($html2pdf !== null) {
                $html2pdf->clean();
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal export PDF: ' . $e->getMessage()
            ], 500);
        }
    }
}