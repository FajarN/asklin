<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{KonasPeserta, KonasMasterHotel, KonasMasterTipeHotel, KonasBookingHotel, KonasPenerbangan, KonasPartner, User};
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
    function __construct()
    {
        $this->middleware('permission:konas-peserta', ['only' => ['index','store', 'create', 'edit', 'destroy']]);
    }


    public function index()
    {
        $provinsi = Province::get();
        return view('backend.konas.peserta', compact('provinsi'));
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = KonasPeserta::select('konas_peserta.*', 'a.id as userid', 'indonesia_cities.name', 'indonesia_provinces.name as provinsi')
                ->leftJoin('users as a', 'a.email', '=', 'konas_peserta.email')
                ->leftjoin("indonesia_cities", 'indonesia_cities.code', '=', 'konas_peserta.cabang')
                ->leftjoin("indonesia_provinces", 'indonesia_provinces.code', '=', 'konas_peserta.id_provinsi')
                ->orderBy('konas_peserta.created_by', 'desc')->get();
            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            if (Str::contains(Str::lower($data['nama_peserta']), Str::lower($request->get('search')))){
                                return true;
                            }
                            if (Str::contains(Str::lower($data['nama_klinik']), Str::lower($request->get('search')))){
                                return true;
                            }
                            if (Str::contains(Str::lower($data['status_pembayaran']), Str::lower($request->get('search')))){
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
                                <button class="btn btn-danger btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                            
                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                               
                                <a href="'.route('cetak_kartu', $data["id"]).'" target="_BLANK" class="dropdown-item has-icon"><i class="fas fa-print"></i> Cetak</a>
                                <div class="dropdown-divider"></div>
                                
                                <a href="'.route('konas.invoice', $data["id"]).'" target="_BLANK" class="dropdown-item has-icon"><i class="fas fa-print"></i> Invoice</a>
                                <div class="dropdown-divider"></div>
                                
                                <a href="javascript:void(0)" onclick="deleteu('.$data["id"].')" class="dropdown-item has-icon"><i class="fas fa-trash"></i> Hapus</a>
                                <div class="dropdown-divider"></div>
                                
                                <a href="javascript:void(0)" onclick="edit('.$data["id"].')" class="dropdown-item has-icon"><i class="fas fa-edit"></i> Edit</a>
                                <div class="dropdown-divider"></div>

                                  <a href="javascript:void(0)" onclick="updateStatus('.$data["id"].')" class="dropdown-item has-icon"><i class="fas fa-pen"></i>Status Pembayaran</a>
                                <div class="dropdown-divider"></div>

                                 </div>
                                           
                        ';
                    }else{
                        $approve = '
                                 <button class="btn btn-danger btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                            
                                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">
                             
                                <a href="'.route('konas.approve', $data->id).'" class="dropdown-item has-icon"><i class="fas  fa-check-circle"></i> Approve</a>
                                <div class="dropdown-divider"></div>

                                 <a href="javascript:void(0)" onclick="updateStatus('.$data["id"].')" class="dropdown-item has-icon"><i class="fas fa-pen"></i>Status Pembayaran</a>
                                <div class="dropdown-divider"></div>
                                
                                <a href="javascript:void(0)" onclick="edit('.$data["id"].')" class="dropdown-item has-icon"><i class="fas fa-edit"></i> Edit</a>
                                <div class="dropdown-divider"></div>

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

        $data = KonasPeserta::updateOrCreate(
            ['id' => $request->id],
            [
                'nama_peserta' => $request->nama_peserta,
                'nik' => $request->nik,
                'tgl_lahir' => $request->tgl_lahir,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                // 'foto' => $foto,
                'nama_klinik' => $request->nama_klinik,
                'alamat_klinik' => $request->alamat_klinik,
                'status' => $request->status,
                'biaya_pendaftaran' => '1500000',
                'cabang' => $request->id_kota,
                'id_provinsi' => $request->id_provinsi,
                'surat_mandat' => $surat_mandat,
                'komisi' => $request->komisi,
                'nama_sertifikat' => $request->nama_sertifikat
            ]
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