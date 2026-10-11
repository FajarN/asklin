<?php

namespace App\Http\Controllers\Backend\konas;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{KonasPeserta, KonasPeriode, KonasMasterHotel, KonasMasterTipeHotel, KonasBookingHotel, KonasPenerbangan, KonasPartner, User};
use Laravolt\Indonesia\Models\Province;
use DataTables;
use Illuminate\Support\Str;
use Mail;
use App\Mail\{Approve};
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

class KonasPesertaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:konas-peserta-list', ['only' => ['index', 'list']]);
        $this->middleware('permission:konas-peserta-create', ['only' => ['store']]);
        $this->middleware('permission:konas-peserta-edit', ['only' => ['edit']]);
        $this->middleware('permission:konas-peserta-delete', ['only' => ['destroy']]);
    }


    public function index()
    {
        $provinsi = Province::get();
        return view('backend.konas.peserta', compact('provinsi'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = KonasPeserta::select('konas_peserta.*', 'a.id as userid', 'indonesia_cities.name', 'indonesia_provinces.name as provinsi')
                ->leftJoin('users as a', 'a.email', '=', 'konas_peserta.email')
                ->leftjoin("indonesia_cities", 'indonesia_cities.code', '=', 'konas_peserta.cabang')
                ->leftjoin("indonesia_provinces", 'indonesia_provinces.code', '=', 'konas_peserta.id_provinsi');

            if ($request->filled('id_periode')) {
                $query->where('konas_peserta.id_periode', $request->id_periode);
            }

            $data = $query->orderBy('konas_peserta.id', 'desc')->get();
            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    $search = is_array($request->get('search')) ? ($request->input('search.value') ?? '') : $request->get('search');
                    if (!empty($search)) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($search) {
                            if (Str::contains(Str::lower($data['nama_peserta'] ?? ''), Str::lower($search))){
                                return true;
                            }
                            if (Str::contains(Str::lower($data['nama_klinik'] ?? ''), Str::lower($search))){
                                return true;
                            }
                            if (Str::contains(Str::lower($data['status_pembayaran'] ?? ''), Str::lower($search))){
                                return true;
                            }
                            return false;
                        });
                    }
                    
                    if (!empty($request->get('provinsi'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            return Str::contains($data['id_provinsi'], $request->get('provinsi')) ? true : false;
                        });
                    }
                    
                    if (!empty($request->get('cabang'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            return Str::contains($data['cabang'], $request->get('cabang')) ? true : false;
                        });
                    }
                })
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    if($data->userid){
                        $approve = '
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle font-semibold px-2.5 py-1.5" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-cog mr-1"></i> Aksi
                                </button>
                                <div class="dropdown-menu shadow-lg rounded-xl p-1 text-xs border border-slate-200" style="min-width: 170px;">
                                    <a href="'.route('cetak_kartu', $data["id"]).'" target="_BLANK" class="dropdown-item py-1.5 px-3 rounded-lg flex items-center gap-2"><i class="fas fa-id-card text-emerald-600"></i> Cetak Kartu</a>
                                    <a href="'.route('konas.invoice', $data["id"]).'" target="_BLANK" class="dropdown-item py-1.5 px-3 rounded-lg flex items-center gap-2"><i class="fas fa-file-invoice-dollar text-blue-600"></i> Cetak Invoice</a>
                                    <div class="dropdown-divider my-1"></div>
                                    <a href="javascript:void(0)" onclick="updateStatus('.$data["id"].')" class="dropdown-item py-1.5 px-3 rounded-lg flex items-center gap-2"><i class="fas fa-credit-card text-amber-600"></i> Status Bayar</a>
                                    <a href="javascript:void(0)" onclick="edit('.$data["id"].')" class="dropdown-item py-1.5 px-3 rounded-lg flex items-center gap-2"><i class="fas fa-edit text-indigo-600"></i> Edit Data</a>
                                    <div class="dropdown-divider my-1"></div>
                                    <a href="javascript:void(0)" onclick="deleteu('.$data["id"].')" class="dropdown-item py-1.5 px-3 rounded-lg flex items-center gap-2 text-rose-600"><i class="fas fa-trash"></i> Hapus</a>
                                </div>
                            </div>
                        ';
                    }else{
                        $approve = '
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle font-semibold px-2.5 py-1.5" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-cog mr-1"></i> Aksi
                                </button>
                                <div class="dropdown-menu shadow-lg rounded-xl p-1 text-xs border border-slate-200" style="min-width: 170px;">
                                    <a href="'.route('konas.approve', $data->id).'" class="dropdown-item py-1.5 px-3 rounded-lg flex items-center gap-2 text-emerald-600 font-semibold"><i class="fas fa-check-circle"></i> Approve Registrasi</a>
                                    <div class="dropdown-divider my-1"></div>
                                    <a href="javascript:void(0)" onclick="updateStatus('.$data["id"].')" class="dropdown-item py-1.5 px-3 rounded-lg flex items-center gap-2"><i class="fas fa-credit-card text-amber-600"></i> Status Bayar</a>
                                    <a href="javascript:void(0)" onclick="edit('.$data["id"].')" class="dropdown-item py-1.5 px-3 rounded-lg flex items-center gap-2"><i class="fas fa-edit text-indigo-600"></i> Edit Data</a>
                                    <div class="dropdown-divider my-1"></div>
                                    <a href="javascript:void(0)" onclick="deleteu('.$data["id"].')" class="dropdown-item py-1.5 px-3 rounded-lg flex items-center gap-2 text-rose-600"><i class="fas fa-trash"></i> Hapus</a>
                                </div>
                            </div>
                        ';
                    }
                    $actionBtn = $approve;

                    

                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function edit(Request $request)
    {
        $data = KonasPeserta::find($request->id);

        return Response()->json($data);
    }

      public function updateStatus(Request $request)
    {
        $data = KonasPeserta::select('konas_peserta.*', 'a.id as id_user')
            ->join('users as a', 'a.email', '=', 'konas_peserta.email')
            ->where('konas_peserta.id', $request->id)
            ->first();
            
        $data2 = KonasBookingHotel::where('id_user', $data->id_user)->first();

        return Response()->json([$data, $data2]);
    }

     public function detail(Request $request)
    {
         $data = KonasPeserta::find($request->id);

        return view('backend.konas.detail');
    }
    
    public function store(Request $request)
    {
        $destinationPath = 'images/konas/';
        if($request->hasFile('foto')) {
            $img_ext1 = $request->file('foto')->getClientOriginalExtension();
            $foto = 'foto-' . time() . '.' . $img_ext1;
            $path = $request->file('foto')->move($destinationPath, $foto);
        }
        
        if($request->hasFile('surat_mandat')) {
            $img_ext2 = $request->file('surat_mandat')->getClientOriginalExtension();
            $surat_mandat = 'suratmandat-' . time() . '.' . $img_ext2;
            $path = $request->file('surat_mandat')->move($destinationPath, $surat_mandat);
        }else{
            $surat_mandat = NULL;
        }

        $periodeId = $request->id_periode ?: (KonasPeriode::where('status', '1')->value('id') ?? 1);
        $dataToSave = [
            'nama_peserta' => $request->nama_peserta,
            'nik' => $request->nik,
            'tgl_lahir' => $request->tgl_lahir,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'nama_klinik' => $request->nama_klinik,
            'alamat_klinik' => $request->alamat_klinik,
            'status' => $request->status,
            'biaya_pendaftaran' => $request->biaya_pendaftaran ?: '1500000',
            'cabang' => $request->id_kota,
            'id_provinsi' => $request->id_provinsi,
            'surat_mandat' => $surat_mandat,
            'komisi' => $request->komisi,
            'nama_sertifikat' => $request->nama_sertifikat
        ];

        if ($request->filled('id_periode') || empty($request->id)) {
            $dataToSave['id_periode'] = $periodeId;
        }

        $data = KonasPeserta::updateOrCreate(
            ['id' => $request->id],
            $dataToSave
        );

        return Response()->json($data);
    }
    
   
    
    public function approve(Request $request){
        $cek = KonasPeserta::find($request->id);
        $user = User::create([
            'name' => $cek->nama_peserta,
            'email' => $cek->email,
            'password' => bcrypt($cek->no_hp)
        ]);
        $user->assignRole(['Peserta']);
        $cek->update(['created_by' => $user->id]);
        
        return redirect()->route('konas.index');
    }
    
    public function status_pembayaran(Request $request){
        $data = KonasPeserta::updateOrCreate(
            ['id' => $request->id_peserta],
            ['status_pembayaran' => $request->status_pembayaran]
        );
        if($data)
        {
            $cek = KonasPeserta::find($data->id);
            // Send Email
            $content = "
                Terima kasih bapak/ibu '.$data->nama_peserta.'. Akun Anda sudah aktif. Silahkan login pada <a href='".route('login')."'>".route('login')."</a> Dengan Email dan No. HP yang didaftarkan sebagai password.
            ";
            $mailData = [
                'title' => 'Konas Approval',
                'body' => $content
            ];
            Mail::to($data->email)->send(new Approve($mailData));
            KonasBookingHotel::updateOrCreate(
                ['id' => $request->id_booking],
                ['status_pembayaran' => $request->status_pembayaran]
            );
        }
        
        return Response()->json($data);
    }
    
    public function destroy(Request $request)
    {
        $peserta = KonasPeserta::Find($request->id);
        $user = User::where('email', $peserta->email)->first();
        if($peserta){
            KonasPenerbangan::where('id_user', $user->id)->delete();
            KonasBookingHotel::where('id_user', $user->id)->delete();
        }
        $peserta->delete();
        return Response()->json($data);
    }
    
    public function invoice($id)
    {

        $peserta = KonasPeserta::select('konas_peserta.*')
                ->where('konas_peserta.id', $id)
                ->first();
        
        $id = $peserta->id;

      try {
            ob_start();
            $content = view('backend.konas.invoice', compact('peserta'));
        
            $html2pdf = new Html2Pdf('P', 'F4', 'fr', true, 'UTF-8', 1);
             $html2pdf->pdf->SetTitle('Cetak Invoice Peserta | PDF');
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->setTestTdInOnePage(false);
            $html2pdf->writeHTML($content, false);
            $html2pdf->output('invoice.pdf');
        } catch (Html2PdfException $e) {
            $html2pdf->clean();
        
            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
        exit();
    }
}