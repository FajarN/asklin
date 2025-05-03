<?php

namespace App\Http\Controllers\Backend\ruang_pengurus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuratKeluar;
use App\Models\JenisSurat;
use App\Models\SuratPenomoran;
use App\Models\SuratTugas;
use App\Models\SuratTugasDetail;
use App\Models\SuratUndangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Str;
use Carbon\Carbon;
use DataTables;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratKeluarController extends Controller
{
    public function index()
    {
        $jenisSurat = JenisSurat::where('status', '1')->get();
        return view('backend.surat_keluar.index', compact('jenisSurat'));
    }

    public function create()
    {
        $jenisSurat = JenisSurat::where('status', '1')->get();
        return view('backend.surat_keluar.create', compact('jenisSurat'));
    }

    public function show($id)
    {
        $suratKeluar = SuratKeluar::with(['jenisSurat', 'suratTugas', 'suratUndangan', 'suratTugas.details'])->findOrFail($id);
        return view('backend.surat_keluar.show', compact('suratKeluar'));
    }

    public function edit($id)
    {
        $suratKeluar = SuratKeluar::with(['jenisSurat', 'suratTugas', 'suratUndangan', 'suratTugas.details'])->findOrFail($id);
        $jenisSurat = JenisSurat::where('status', '1')->get();
        return view('backend.surat_keluar.edit', compact('suratKeluar', 'jenisSurat'));
    }

    /**
     * Generate nomor surat berdasarkan jenis surat
     */

    private function formatTanggalBulanTahun($tanggal)
    {
        $bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $tanggal = \Carbon\Carbon::parse($tanggal);

        return $tanggal->format('d').' '.$bulan[(int) $tanggal->format('m')].' '.$tanggal->format('Y');
    }

    public function print($id)
    {
        $suratKeluar = SuratKeluar::with(['jenisSurat', 'suratTugas', 'suratUndangan', 'suratTugas.details'])->findOrFail($id);
        $suratKeluar->tgl_surat_formatted = $this->formatTanggalBulanTahun($suratKeluar->tgl_surat);
        
        if ($suratKeluar->suratTugas && $suratKeluar->suratTugas->tgl_agenda) {
            $suratKeluar->suratTugas->tgl_agenda_formatted = $this->formatTanggalBulanTahun($suratKeluar->suratTugas->tgl_agenda);
        }
        
        $pdf = Pdf::loadView('backend.surat_keluar.print', compact('suratKeluar'));
        return $pdf->stream('surat_keluar_'.$id.'.pdf');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = SuratKeluar::with('jenisSurat')->orderBy('created_at', 'desc')->get();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jenis_surat', function($row){
                    return $row->jenisSurat->nama_jenis;
                })
                ->addColumn('tgl_surat', function($row){
                    return Carbon::parse($row->tgl_surat)->format('d-m-Y');
                })
                ->addColumn('status_badge', function($row){
                    $badges = [
                        'draft' => 'badge-warning',
                        'disetujui' => 'badge-success',
                        'ditolak' => 'badge-danger',
                        'terkirim' => 'badge-info'
                    ];
                    return '<span class="badge '.$badges[$row->status].'">'.$row->status.'</span>';
                })
                ->addColumn('action', function($row){
                    $detailUrl = route('surat_keluar.show', $row->id);
                    $editUrl = route('surat_keluar.edit', $row->id);
                    $printUrl = route('surat_keluar.print', $row->id);
                    
                    $btn = '<a href="'.$detailUrl.'" class="btn btn-info btn-sm">Detail</a> ';
                    $btn .= '<a href="'.$editUrl.'" class="btn btn-primary btn-sm">Edit</a> ';
                    $btn .= '<a href="'.$printUrl.'" class="btn btn-success btn-sm" target="_blank">Print</a> ';
                    $btn .= '<button type="button" data-id="'.$row->id.'" class="btn btn-danger btn-sm btn-delete">Hapus</button>';
                    
                    return $btn;
                })
                ->rawColumns(['action', 'status_badge'])
                ->make(true);
        }
    }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'id_jenis_surat' => 'required',
                'tgl_surat' => 'required|date',
                'perihal' => 'required',
                'no_surat' => 'required|unique:surat_keluar,no_surat',
            ]);

            $suratKeluar = new SuratKeluar();
            $suratKeluar->id_jenis_surat = $request->id_jenis_surat;
            $suratKeluar->tgl_surat = Carbon::parse($request->tgl_surat);
            $suratKeluar->perihal = $request->perihal;
            $suratKeluar->no_surat = $request->no_surat;
            $suratKeluar->created_by = Auth::user()->name;
            $suratKeluar->updated_by = Auth::user()->name;
            $suratKeluar->status = 'draft';
            $suratKeluar->save();

            $hashSource = $suratKeluar->id . '|' . now()->timestamp . '|' . Str::random(10);
            $suratKeluar->kode_qr = hash('sha256', $hashSource);
            $suratKeluar->save();
            
            $this->updateNomorTerakhir($request->id_jenis_surat, $request->tgl_surat);
            
            $this->handleJenisSuratKhusus($request, $suratKeluar);

            DB::commit();
            return redirect()->route('surat_keluar.index')->with('success', 'Surat berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Gagal menyimpan Surat Keluar', [
                'user' => Auth::user()->name ?? 'guest',
                'error' => $e->getMessage(),
            ]);
            return back()->withInput()->with('error', 'Error: '.$e->getMessage());
        }
    }

    public function generateNomor(Request $request)
    {
        try {
            $jenisSuratId = $request->id_jenis_surat;
            
            if (!$jenisSuratId) {
                return response()->json([
                    'success' => false, 
                    'error' => 'Jenis surat belum dipilih'
                ]);
            }
            
            $jenisSurat = JenisSurat::findOrFail($jenisSuratId);
            $tglSurat = $request->tgl_surat ? Carbon::parse($request->tgl_surat) : Carbon::now();
            $tahun = $tglSurat->format('Y');
        
            $penomoran = SuratPenomoran::firstOrCreate(
                [
                    'id_jenis_surat' => $jenisSuratId,
                    'tahun' => $tahun
                ],
                ['nomor_terakhir' => 0]
            );
            
            $nomorSelanjutnya = $penomoran->nomor_terakhir + 1;
            
            $kode = $jenisSurat->kode_jenis ?? '';
            $tahunFormat = $tahun;
            $nomorPadded = str_pad($nomorSelanjutnya, 3, '0', STR_PAD_LEFT);
            
            $formatNomor = $jenisSurat->format_nomor;
            $formatNomor = str_replace('{kode}', $kode, $formatNomor);
            $formatNomor = str_replace('{tahun}', $tahunFormat, $formatNomor);
            $formatNomor = str_replace('{nomor}', $nomorPadded, $formatNomor);
            
            $penomoran->nomor_terakhir = $nomorSelanjutnya;
            $penomoran->save();
            
            return response()->json([
                'success' => true,
                'no_surat' => $formatNomor,
                'nomor_urut' => $nomorSelanjutnya,
                'debug' => [
                    'format_awal' => $jenisSurat->format_nomor,
                    'kode' => $kode,
                    'tahun' => $tahunFormat,
                    'nomor' => $nomorPadded
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Gagal generate nomor surat', [
                'user' => Auth::user()->name ?? 'guest',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Gagal generate nomor surat: ' . $e->getMessage()
            ]);
        }
    }

    private function updateNomorTerakhir($jenisSuratId, $tanggalSurat)
    {
        $tglSurat = Carbon::parse($tanggalSurat);
        $tahun = $tglSurat->format('Y');
        
        $noSurat = request()->no_surat;
        
        $penomoran = SuratPenomoran::where('id_jenis_surat', $jenisSuratId)
            ->where('tahun', $tahun)
            ->first();
            
        if (!$penomoran) {
            $penomoran = new SuratPenomoran();
            $penomoran->id_jenis_surat = $jenisSuratId;
            $penomoran->tahun = $tahun;
            preg_match('/(\d{3})(?!.*\d)/', $noSurat, $matches);
            if (isset($matches[1])) {
                $penomoran->nomor_terakhir = (int)$matches[1];
            } else {
                $penomoran->nomor_terakhir = 1;
            }
            $penomoran->save();
            return;
        }
        
        preg_match('/(\d{3})(?!.*\d)/', $noSurat, $matches);
        if (isset($matches[1])) {
            $nomorSekarang = (int)$matches[1];
            $penomoran->nomor_terakhir = $nomorSekarang;
        $penomoran->save();
    }
}

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'id_jenis_surat' => 'required',
                'tgl_surat' => 'required|date',
                'perihal' => 'required',
                'no_surat' => 'required|unique:surat_keluar,no_surat,'.$id,
            ]);

            $suratKeluar = SuratKeluar::findOrFail($id);
            $suratKeluar->id_jenis_surat = $request->id_jenis_surat;
            $suratKeluar->tgl_surat = Carbon::parse($request->tgl_surat);
            $suratKeluar->perihal = $request->perihal;
            $suratKeluar->no_surat = $request->no_surat;
            $suratKeluar->updated_by = Auth::user()->name;
            $suratKeluar->save();

            $this->handleJenisSuratKhusus($request, $suratKeluar);

            Log::info('Surat Keluar diperbarui', [
                'user' => Auth::user()->name,
                'id' => $suratKeluar->id,
                'data' => $suratKeluar->toArray(),
            ]);

            DB::commit();
            return redirect()->route('surat_keluar.index')->with('success', 'Surat berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Gagal memperbarui Surat Keluar', [
                'user' => Auth::user()->name ?? 'guest',
                'error' => $e->getMessage(),
            ]);
            return back()->withInput()->with('error', 'Error: '.$e->getMessage());
        }
    }


    private function handleJenisSuratKhusus(Request $request, SuratKeluar $suratKeluar)
    {
        $jenisSurat = JenisSurat::find($request->id_jenis_surat);
        
        if (stripos($jenisSurat->nama_jenis, 'tugas') !== false) {
            $suratTugas = SuratTugas::updateOrCreate(
                ['id_surat' => $suratKeluar->id],
                [
                    'asal_surat' => $request->asal_surat ?? '',
                    'nomor_asal_surat' => $request->nomor_asal_surat ?? '',
                    'agenda' => $request->agenda ?? '',
                    'hari' => $request->hari ?? '',
                    'tgl_agenda' => $request->tgl_agenda ?? null,
                    'waktu_agenda' => $request->waktu_agenda ?? '',
                    'tempat_agenda' => $request->tempat_agenda ?? ''
                ]
            );
            
            if (isset($request->nama_pengurus) && is_array($request->nama_pengurus)) {
                SuratTugasDetail::where('id_surat_tugas', $suratTugas->id)->delete();
                
                foreach ($request->nama_pengurus as $key => $nama) {
                    SuratTugasDetail::create([
                        'id_surat_tugas' => $suratTugas->id,
                        'nama_pengurus' => $nama,
                        'jabatan' => $request->jabatan[$key] ?? ''
                    ]);
                }
            }
        }

        else if (stripos($jenisSurat->nama_jenis, 'undangan') !== false) {
            SuratUndangan::updateOrCreate(
                ['id_surat' => $suratKeluar->id],
                [
                    'id_surat' => $suratKeluar->id,
                    'nama_penerima' => $request->nama_penerima ?? '',
                    'alamat_penerima' => $request->alamat_penerima ?? '',
                    'salam_pembuka' => $request->salam_pembuka ?? '',
                    'isi_surat' => $request->isi_surat ?? '',
                    'salam_penutup' => $request->salam_penutup ?? '',
                    'judul_acara' => $request->judul_acara ?? '',
                    'tujuan_acara' => $request->tujuan_acara ?? '',
                    'waktu_tgl_acara' => $request->waktu_tgl_acara ?? '',
                    'lokasi_acara' => $request->lokasi_acara ?? '',
                    'agenda_acara' => $request->agenda_acara ?? '',
                    'informasi_tambahan' => $request->informasi_tambahan ?? ''
                ]
            );
        }
    }

    public function destroy(Request $request)
    {
        try {
            $suratKeluar = SuratKeluar::find($request->id);
            
            if ($suratKeluar->suratTugas) {
                SuratTugasDetail::where('id_surat_tugas', $suratKeluar->suratTugas->id)->delete();
                $suratKeluar->suratTugas->delete();
            }
            
            if ($suratKeluar->suratUndangan) {
                $suratKeluar->suratUndangan->delete();
            }
            
            $suratKeluar->delete();
            
            return response()->json(['success' => true, 'message' => 'Surat berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: '.$e->getMessage()]);
        }
    }
}