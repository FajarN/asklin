<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Sertifikat, Anggota};
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;
use Carbon\Carbon;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

class ExpiredSertifikatController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:expired-sertifikat', ['only' => ['index', 'list', 'exportExcel', 'exportPdf']]);
    }

    public function index(Request $request)
    {
        $anggota = Anggota::when(Auth::user()->hasRole('Admin Cabang'), function ($query) use ($request) {
            $query->where('id_kota', Auth::user()->kota);
        })
        ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) use ($request) {
            $query->where('id_provinsi', Auth::user()->provinsi);
        })
        ->where('status', 'approved')->latest()->get();
        
        return view('backend.expired_sertifikat.index', compact('anggota'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->getDataQuery($request)->get();

            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            $search = Str::lower($request->get('search'));
                            return Str::contains(Str::lower($data['nama_klinik']), $search)
                                || Str::contains(Str::lower($data['kota']), $search)
                                || Str::contains(Str::lower($data['no_anggota']), $search);
                        });
                    }
                })
                ->addIndexColumn()
                ->addColumn('status_expired', function ($row) {
                    $today = Carbon::today();
                    $expiredDate = Carbon::parse($row->expired_date);
                    
                    if ($expiredDate->isPast()) {
                        return '<span class="badge badge-danger">EXPIRED</span>';
                    } elseif ($expiredDate->isToday()) {
                        return '<span class="badge badge-warning">HARI INI</span>';
                    } elseif ($expiredDate->isTomorrow()) {
                        return '<span class="badge badge-warning">BESOK</span>';
                    } elseif ($today->diffInDays($expiredDate) <= 7) {
                        return '<span class="badge badge-warning">SEMINGGU</span>';
                    } elseif ($today->diffInDays($expiredDate) <= 30) {
                        return '<span class="badge badge-info">SEBULAN</span>';
                    } else {
                        return '<span class="badge badge-success">AMAN</span>';
                    }
                })
                ->addColumn('sisa_hari', function ($row) {
                    $today = Carbon::today();
                    $expiredDate = Carbon::parse($row->expired_date);
                    $diffDays = $today->diffInDays($expiredDate, false);
                    
                    if ($expiredDate->isPast()) {
                        return '<span class="text-danger font-weight-bold">' . abs($diffDays) . ' hari lalu</span>';
                    } elseif ($expiredDate->isToday()) {
                        return '<span class="text-warning font-weight-bold">Hari ini</span>';
                    } else {
                        return '<span class="text-primary">' . $diffDays . ' hari</span>';
                    }
                })
                ->editColumn('expired_date', function ($row) {
                    return Carbon::parse($row->expired_date)->format('d-m-Y');
                })
                ->editColumn('dari', function ($row) {
                    return Carbon::parse($row->dari)->format('d-m-Y');
                })
                ->rawColumns(['status_expired', 'sisa_hari'])
                ->make(true);
        }
    }

    /**
     * Method untuk mengambil query data yang sama untuk list dan export
     */
    private function getDataQuery(Request $request)
    {
        $today = now()->startOfDay();
        $besok = now()->addDay()->startOfDay();
        $seminggu = now()->addDays(7)->endOfDay();
        $sebulan = now()->addDays(30)->endOfDay();

        $subquery = DB::table('sertifikat as s1')
            ->select('id_anggota', DB::raw('MAX(dari) as max_dari'))
            ->groupBy('id_anggota');

        return Sertifikat::select(
                'sertifikat.*',
                'a.nama_klinik',
                'a.no_anggota',
                'a.updated_at',
                'a.nama_kontak',
                'a.alamat_klinik',
                'a.rt',
                'a.rw',
                'a.email',
                'a.tlf',
                'a.jenis_klinik',
                'b.name as kelurahan',
                'c.name as kecamatan',
                'd.name as kota',
                'e.name as provinsi',
                'a.id_kota',
                'a.id_provinsi',
                DB::raw('DATE_ADD(sertifikat.dari, INTERVAL 1 YEAR) as expired_date')
            )
            ->joinSub($subquery, 'latest_sertifikat', function ($join) {
                $join->on('sertifikat.id_anggota', '=', 'latest_sertifikat.id_anggota')
                    ->on('sertifikat.dari', '=', 'latest_sertifikat.max_dari');
            })
            ->leftJoin('anggota as a', 'a.id', '=', 'sertifikat.id_anggota')
            ->leftJoin('indonesia_villages as b', 'b.code', '=', 'a.id_kelurahan')
            ->leftJoin('indonesia_districts as c', 'c.code', '=', 'a.id_kecamatan')
            ->leftJoin('indonesia_cities as d', 'd.code', '=', 'a.id_kota')
            ->leftJoin('indonesia_provinces as e', 'e.code', '=', 'a.id_provinsi')
            ->when(Auth::user()->hasRole('Admin Cabang'), function ($query) {
                $query->where('a.id_kota', Auth::user()->kota);
            })
            ->when(Auth::user()->hasRole('Admin Daerah'), function ($query) {
                $query->where('a.id_provinsi', Auth::user()->provinsi);
            })
            ->when($request->has('filter_expired') && $request->filter_expired !== '', function ($query) use ($request, $today, $besok, $seminggu, $sebulan) {
                switch ($request->filter_expired) {
                    case 'expired':
                        $query->where(DB::raw('DATE_ADD(sertifikat.dari, INTERVAL 1 YEAR)'), '<', now());
                        break;
                    case 'besok':
                        $query->whereDate(DB::raw('DATE_ADD(sertifikat.dari, INTERVAL 1 YEAR)'), '=', now()->addDay()->toDateString());
                        break;
                    case 'seminggu':
                        $query->whereBetween(DB::raw('DATE_ADD(sertifikat.dari, INTERVAL 1 YEAR)'), [now(), now()->addDays(7)]);
                        break;
                    case 'sebulan':
                        $query->whereBetween(DB::raw('DATE_ADD(sertifikat.dari, INTERVAL 1 YEAR)'), [now(), now()->addDays(30)]);
                        break;
                }
            })
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('a.nama_klinik', 'like', "%{$search}%")
                      ->orWhere('a.no_anggota', 'like', "%{$search}%")
                      ->orWhere('d.name', 'like', "%{$search}%");
                });
            });
    }

    /**
     * Export ke Excel menggunakan Spatie Simple Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            $data = $this->getDataQuery($request)->get();
            $filterName = $this->getFilterName($request->filter_expired);
            $fileName = 'expired_sertifikat_' . $filterName . '_' . date('Y-m-d_H-i-s') . '.xlsx';
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
                'Jenis Klinik',
                'Nama Kontak',
                'Alamat Klinik',
                'No Sertifikat',
                'Tanggal Terbit',
                'Tanggal Expired',
                'Status Sertifikat',
                'Sisa Hari',
                'Berlaku Sampai'
            ]);

            // Tambah data
            foreach ($data as $index => $item) {
                $today = Carbon::today();
                $expiredDate = Carbon::parse($item->expired_date);
                
                // Tentukan status berdasarkan tanggal
                $status = '-';
                $sisaHari = '-';
                
                $diffDays = $today->diffInDays($expiredDate, false);
                
                if ($expiredDate->isPast()) {
                    $status = 'EXPIRED';
                    $sisaHari = abs($diffDays) . ' hari lalu';
                } elseif ($expiredDate->isToday()) {
                    $status = 'HARI INI';
                    $sisaHari = 'Hari ini';
                } elseif ($expiredDate->isTomorrow()) {
                    $status = 'BESOK EXPIRED';
                    $sisaHari = '1 hari';
                } elseif ($today->diffInDays($expiredDate) <= 7) {
                    $status = 'DALAM SEMINGGU';
                    $sisaHari = $diffDays . ' hari';
                } elseif ($today->diffInDays($expiredDate) <= 30) {
                    $status = 'DALAM SEBULAN';
                    $sisaHari = $diffDays . ' hari';
                } else {
                    $status = 'AMAN';
                    $sisaHari = $diffDays . ' hari';
                }

                $writer->addRow([
                    $index + 1,
                    $item->no_anggota ?: '-',
                    $item->nama_klinik ?: '-',
                    $item->kota ?: '-',
                    $item->kecamatan ?: '-',
                    $item->kelurahan ?: '-',
                    $item->provinsi ?: '-',
                    $item->email ?: '-',
                    $item->tlf ?: '-',
                    $item->jenis_klinik ?: '-',
                    $item->nama_kontak ?: '-',
                    $item->alamat_klinik ?: '-',
                    $item->no_sertifikat ?: '-',
                    $item->dari ? Carbon::parse($item->dari)->format('d-m-Y') : '-',
                    $expiredDate->format('d-m-Y'),
                    $status,
                    $sisaHari,
                    $item->sampai ? Carbon::parse($item->sampai)->format('d-m-Y') : '-'
                ]);
            }

            // Tambah summary info
            $stats = $this->calculateStats($data);
            
            $writer->addRow([]); // Empty row
            $writer->addRow(['RINGKASAN EXPORT:']);
            $writer->addRow(['Total Data:', $stats['total']]);
            $writer->addRow(['Sudah Expired:', $stats['expired']]);
            $writer->addRow(['Hari Ini:', $stats['hari_ini']]);
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
     * Export ke PDF
     */
    public function exportPdf(Request $request)
    {
        $html2pdf = null;
        
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
            
            // Generate HTML dari template
            $html = view('backend.expired_sertifikat.export_pdf', compact('data', 'filterName', 'today', 'stats'))->render();
            
            // Log HTML length untuk debugging
            \Log::info('HTML generated', [
                'html_length' => strlen($html),
                'user' => Auth::id()
            ]);
            
            // Create HTML2PDF instance dengan language code yang didukung
            $html2pdf = new Html2Pdf('L', 'A4', 'en');
            $html2pdf->setDefaultFont('Arial');
            
            // Set test mode untuk debugging
            $html2pdf->setTestTdInOnePage(false);
            $html2pdf->setTestIsImage(false);
            
            // Write HTML ke PDF
            $html2pdf->writeHTML($html);
            
            $fileName = 'expired_sertifikat_' . $filterName . '_' . date('Y-m-d_H-i-s') . '.pdf';
            
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
        $hari_ini = 0;
        $besok = 0;
        $seminggu = 0;
        $sebulan = 0;
        $aman = 0;

        foreach ($data as $item) {
            $expiredDate = Carbon::parse($item->expired_date);
            
            if ($expiredDate->isPast()) {
                $expired++;
            } elseif ($expiredDate->isToday()) {
                $hari_ini++;
            } elseif ($expiredDate->isTomorrow()) {
                $besok++;
            } elseif ($today->diffInDays($expiredDate) <= 7) {
                $seminggu++;
            } elseif ($today->diffInDays($expiredDate) <= 30) {
                $sebulan++;
            } else {
                $aman++;
            }
        }

        return [
            'total' => $data->count(),
            'expired' => $expired,
            'hari_ini' => $hari_ini,
            'besok' => $besok,
            'seminggu' => $seminggu,
            'sebulan' => $sebulan,
            'aman' => $aman
        ];
    }
}