<?php

namespace App\Http\Controllers\Backend\laporan;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\Province;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;
use Yajra\DataTables\Facades\DataTables;

class LaporanExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:laporan-pusat', ['only' => ['index', 'list', 'exportExcel', 'exportPdf']]);
    }

    /**
     * Daftar kolom lengkap yang dapat dipilih (Custom Dynamic Column Picker).
     */
    private function getAvailableColumns(): array
    {
        return [
            'id' => ['label' => 'ID', 'type' => 'number'],
            'no_anggota' => ['label' => 'No. Anggota (NRA)', 'type' => 'text'],
            'nama_klinik' => ['label' => 'Nama Klinik', 'type' => 'text'],
            'status_approval' => ['label' => 'Status Persetujuan (NRA)', 'type' => 'text'],
            'status' => ['label' => 'Status Sistem', 'type' => 'text'],
            'status_pembayaran' => ['label' => 'Status Pembayaran', 'type' => 'text'],
            'provinsi' => ['label' => 'Provinsi', 'type' => 'text'],
            'name' => ['label' => 'Kabupaten/Kota', 'type' => 'text'],
            'kecamatan' => ['label' => 'Kecamatan', 'type' => 'text'],
            'kelurahan' => ['label' => 'Kelurahan/Desa', 'type' => 'text'],
            'jenis_klinik' => ['label' => 'Jenis Klinik', 'type' => 'text'],
            'kriteria' => ['label' => 'Kriteria / Fasilitas', 'type' => 'text'],
            'no_ijin' => ['label' => 'No. Ijin Operasional', 'type' => 'text'],
            'tgl_ijin' => ['label' => 'Tanggal Ijin', 'type' => 'date'],
            'tgl_akhir_ijin' => ['label' => 'Tanggal Akhir Ijin', 'type' => 'date'],
            'nama_pemilik_klinik' => ['label' => 'Nama Pemilik', 'type' => 'text'],
            'nama_kontak' => ['label' => 'Nama Kontak Person', 'type' => 'text'],
            'email' => ['label' => 'Email', 'type' => 'text'],
            'tlf' => ['label' => 'Telepon / WhatsApp', 'type' => 'text'],
            'tlf_klinik' => ['label' => 'Telepon Klinik', 'type' => 'text'],
            'status_pendaftar' => ['label' => 'Status Pendaftar', 'type' => 'text'],
            'status_kepemilikan' => ['label' => 'Status Kepemilikan', 'type' => 'text'],
            'alamat_klinik' => ['label' => 'Alamat Klinik', 'type' => 'text'],
            'rt' => ['label' => 'RT', 'type' => 'text'],
            'rw' => ['label' => 'RW', 'type' => 'text'],
            'kode_pos' => ['label' => 'Kode Pos', 'type' => 'text'],
            'tgl_daftar' => ['label' => 'Tanggal Pendaftaran', 'type' => 'date'],
            'verifikasi_cabang' => ['label' => 'Verifikasi Cabang', 'type' => 'datetime'],
            'verifikasi_pusat' => ['label' => 'Verifikasi Pusat', 'type' => 'datetime'],
        ];
    }

    /**
     * Tampilan utama Menu Laporan Keseluruhan (Export).
     */
    public function index()
    {
        $provinsi = Province::orderBy('name', 'asc')->get();
        $availableColumns = $this->getAvailableColumns();

        return view('backend.laporan.export', compact('provinsi', 'availableColumns'));
    }

    /**
     * Base Query dengan filter Provinsi, Kota, dan Status Approval berbasis nomor anggota.
     */
    private function getDataQuery(Request $request)
    {
        $unifiedDateSql = "CASE 
            WHEN anggota.created_at IS NOT NULL AND anggota.created_at != '0000-00-00 00:00:00' THEN anggota.created_at 
            WHEN anggota.created_on IS NOT NULL AND anggota.created_on != '' THEN FROM_UNIXTIME(anggota.created_on) 
            ELSE NULL 
        END";

        $query = Anggota::select(
            'anggota.*',
            'indonesia_cities.name',
            'indonesia_provinces.name as provinsi',
            'indonesia_districts.name as kecamatan',
            'indonesia_villages.name as kelurahan',
            DB::raw("{$unifiedDateSql} as tgl_daftar_raw"),
            DB::raw('GROUP_CONCAT(DISTINCT fasilitas_klinik.nama SEPARATOR ", ") as kriteria')
        )
        ->leftJoin('fasilitas_klinik', DB::raw('FIND_IN_SET(fasilitas_klinik.id, anggota.fasilitas_klinik)'), '>', DB::raw("'0'"))
        ->leftJoin('indonesia_cities', 'indonesia_cities.code', '=', 'anggota.id_kota')
        ->leftJoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'anggota.id_provinsi')
        ->leftJoin('indonesia_districts', 'indonesia_districts.code', '=', 'anggota.id_kecamatan')
        ->leftJoin('indonesia_villages', 'indonesia_villages.code', '=', 'anggota.id_kelurahan');

        // 1. Filter Provinsi
        if ($request->filled('provinsi')) {
            $query->where('anggota.id_provinsi', $request->provinsi);
        }

        // 2. Filter Kabupaten / Kota
        if ($request->filled('kota')) {
            $query->where('anggota.id_kota', $request->kota);
        }

        // 3. Filter Status Approval: Berdasarkan ADA/TIDAKNYA No. Anggota
        if ($request->filled('status_approval')) {
            if ($request->status_approval === 'approved') {
                // Disetujui jika no_anggota terisi
                $query->whereNotNull('anggota.no_anggota')
                      ->where('anggota.no_anggota', '!=', '');
            } elseif ($request->status_approval === 'belum_approved') {
                // Belum Disetujui jika no_anggota null atau kosong
                $query->where(function ($q) {
                    $q->whereNull('anggota.no_anggota')
                      ->orWhere('anggota.no_anggota', '=', '');
                });
            }
        }

        // 4. Filter Status Pembayaran
        if ($request->filled('status_pembayaran')) {
            $query->where('anggota.status_pembayaran', $request->status_pembayaran);
        }

        // 5. Filter Tahun Pendaftaran
        if ($request->filled('tahun')) {
            $query->whereRaw("YEAR({$unifiedDateSql}) = ?", [(int) $request->tahun]);
        }

        // 6. Pencarian Bebas
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('anggota.nama_klinik', 'like', "%{$search}%")
                  ->orWhere('anggota.no_anggota', 'like', "%{$search}%")
                  ->orWhere('anggota.nama_pemilik_klinik', 'like', "%{$search}%")
                  ->orWhere('anggota.nama_kontak', 'like', "%{$search}%")
                  ->orWhere('anggota.email', 'like', "%{$search}%")
                  ->orWhere('anggota.tlf', 'like', "%{$search}%")
                  ->orWhere('indonesia_cities.name', 'like', "%{$search}%")
                  ->orWhere('indonesia_provinces.name', 'like', "%{$search}%");
            });
        }

        return $query->groupBy('anggota.id');
    }

    /**
     * Server-side DataTables untuk preview data sesuai kolom terpilih.
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $selectedColumns = $request->selected_columns ? explode(',', $request->selected_columns) : [];
            $availableColumns = array_keys($this->getAvailableColumns());

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

            foreach ($data as $item) {
                // Tentukan approval status berdasarkan kehadiran no_anggota
                $hasNoAnggota = !empty(trim($item->no_anggota ?? ''));
                $item->status_approval_text = $hasNoAnggota ? 'Disetujui (Ber-NRA)' : 'Belum Disetujui';
                $item->status_approval_badge = $hasNoAnggota 
                    ? '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-300"><i class="fas fa-check-circle mr-1"></i> Disetujui</span>'
                    : '<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-300"><i class="fas fa-clock mr-1"></i> Belum Disetujui</span>';

                $item->formatted_tgl_daftar = $item->tgl_daftar_raw ? Carbon::parse($item->tgl_daftar_raw)->format('d-m-Y') : '-';
                $item->formatted_tgl_ijin = $item->tgl_ijin && $item->tgl_ijin != '0000-00-00' ? Carbon::parse($item->tgl_ijin)->format('d-m-Y') : '-';
                $item->formatted_tgl_akhir_ijin = $item->tgl_akhir_ijin && $item->tgl_akhir_ijin != '0000-00-00' ? Carbon::parse($item->tgl_akhir_ijin)->format('d-m-Y') : '-';
                $item->formatted_verifikasi_cabang = $item->verifikasi_cabang ? Carbon::parse($item->verifikasi_cabang)->format('d-m-Y H:i') : '-';
                $item->formatted_verifikasi_pusat = $item->verifikasi_pusat ? Carbon::parse($item->verifikasi_pusat)->format('d-m-Y H:i') : '-';
                $item->formatted_status_pembayaran = $item->status_pembayaran == '1' ? 'Lunas' : 'Belum Lunas';
            }

            $datatables = DataTables::of($data)
                ->addIndexColumn();

            foreach ($validColumns as $column) {
                switch ($column) {
                    case 'status_approval':
                        $datatables->addColumn($column, function ($row) {
                            return $row->status_approval_badge;
                        });
                        break;
                    case 'tgl_daftar':
                        $datatables->addColumn($column, function ($row) {
                            return $row->formatted_tgl_daftar;
                        });
                        break;
                    case 'tgl_ijin':
                        $datatables->addColumn($column, function ($row) {
                            return $row->formatted_tgl_ijin;
                        });
                        break;
                    case 'tgl_akhir_ijin':
                        $datatables->addColumn($column, function ($row) {
                            return $row->formatted_tgl_akhir_ijin;
                        });
                        break;
                    case 'verifikasi_cabang':
                        $datatables->addColumn($column, function ($row) {
                            return $row->formatted_verifikasi_cabang;
                        });
                        break;
                    case 'verifikasi_pusat':
                        $datatables->addColumn($column, function ($row) {
                            return $row->formatted_verifikasi_pusat;
                        });
                        break;
                    case 'status_pembayaran':
                        $datatables->addColumn($column, function ($row) {
                            return $row->formatted_status_pembayaran;
                        });
                        break;
                    default:
                        $datatables->addColumn($column, function ($row) use ($column) {
                            return $row->{$column} ?? '-';
                        });
                        break;
                }
            }

            return $datatables->rawColumns(array_merge($validColumns, ['DT_RowIndex', 'status_approval']))
                ->make(true);
        }
    }

    /**
     * Ekspor Data Keseluruhan ke Excel (.xlsx) dengan kolom dinamis.
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

            $fileName = 'laporan_keseluruhan_anggota_' . date('Y-m-d_H-i-s') . '.xlsx';
            $filePath = storage_path('app/temp/' . $fileName);

            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            $writer = SimpleExcelWriter::create($filePath);

            // 1. Header Baris
            $headers = ['No'];
            foreach ($selectedColumns as $column) {
                $headers[] = $availableColumnsData[$column]['label'] ?? ucwords(str_replace('_', ' ', $column));
            }
            $writer->addHeader($headers);

            // 2. Baris Data
            foreach ($data as $index => $item) {
                $row = [$index + 1];
                $hasNoAnggota = !empty(trim($item->no_anggota ?? ''));

                foreach ($selectedColumns as $column) {
                    switch ($column) {
                        case 'status_approval':
                            $row[] = $hasNoAnggota ? 'Disetujui (Ber-NRA)' : 'Belum Disetujui';
                            break;
                        case 'tgl_daftar':
                            $row[] = $item->tgl_daftar_raw ? Carbon::parse($item->tgl_daftar_raw)->format('d-m-Y') : '-';
                            break;
                        case 'tgl_ijin':
                            $row[] = $item->tgl_ijin && $item->tgl_ijin != '0000-00-00' ? Carbon::parse($item->tgl_ijin)->format('d-m-Y') : '-';
                            break;
                        case 'tgl_akhir_ijin':
                            $row[] = $item->tgl_akhir_ijin && $item->tgl_akhir_ijin != '0000-00-00' ? Carbon::parse($item->tgl_akhir_ijin)->format('d-m-Y') : '-';
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

            // 3. Ringkasan Dokumen
            $provName = 'Semua Provinsi';
            if ($request->filled('provinsi')) {
                $prov = Province::where('code', $request->provinsi)->first();
                $provName = $prov ? $prov->name : $request->provinsi;
            }

            $cityName = 'Semua Kab/Kota';
            if ($request->filled('kota')) {
                $city = City::where('code', $request->kota)->first();
                $cityName = $city ? $city->name : $request->kota;
            }

            $statusText = 'Semua Status';
            if ($request->status_approval === 'approved') {
                $statusText = 'Hanya Yang Disetujui (Memiliki No. Anggota)';
            } elseif ($request->status_approval === 'belum_approved') {
                $statusText = 'Hanya Yang Belum Disetujui (Belum Ada No. Anggota)';
            }

            $writer->addRow([]);
            $writer->addRow(['--- INFORMASI EXPORT DATA ASKLIN ---']);
            $writer->addRow(['Total Data Diekspor:', $data->count()]);
            $writer->addRow(['Filter Provinsi:', $provName]);
            $writer->addRow(['Filter Kab/Kota:', $cityName]);
            $writer->addRow(['Filter Status Persetujuan:', $statusText]);
            $writer->addRow(['Diekspor Oleh:', Auth::user()->name ?? 'System']);
            $writer->addRow(['Waktu Export:', Carbon::now()->format('d-m-Y H:i:s')]);

            $writer->close();

            return response()->download($filePath, $fileName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Cache-Control' => 'max-age=0',
                'Pragma' => 'public'
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Error exporting Excel Laporan Export: ' . $e->getMessage(), [
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
     * Ekspor Data Keseluruhan ke PDF.
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

            $fileName = 'laporan_keseluruhan_anggota_' . date('Y-m-d_H-i-s') . '.pdf';
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
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate PDF: ' . strip_tags($formatter->getHtmlMessage())
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

