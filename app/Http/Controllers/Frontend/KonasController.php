<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\{User, KonasPeserta, KonasMasterHotel, KonasMasterTipeHotel, KonasBookingHotel, KonasPenerbangan, KonasPartner};
use Spatie\Permission\Models\Role;
use Auth;
use Laravolt\Indonesia\Models\Province;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Carbon\Carbon;
use Mail;
use App\Mail\{Konas, Hotel};

class KonasController extends Controller
{
    public function index()
    {
        $title = "Kongres II Asosiasi Klinik Indonesia";
        $description = "Asklin adalah asosiasi klinik seluruh indonesia.";
        $partner = KonasPartner::all();

        return view('frontend.konas.index', compact('title', 'description', 'partner'));
    }
    
    public function profil()
    {
        $title = "Profil Acara Kongres II Asosiasi Klinik Indonesia";
        $description = "Asklin adalah asosiasi klinik seluruh indonesia.";

        return view('frontend.konas.profil_acara', compact('title', 'description'));
    }
    
    public function pendahuluan()
    {
        $title = "Pendahuluan Kongres II Asosiasi Klinik Indonesia";
        $description = "Asklin adalah asosiasi klinik seluruh indonesia.";

        return view('frontend.konas.pendahuluan', compact('title', 'description'));
    }
    
    public function sambutan_gubernur_aceh()
    {
        $title = "Sambutan Gubernur Aceh";
        $description = "Asklin adalah asosiasi klinik seluruh indonesia.";

        return view('frontend.konas.sambutan_gubernur_aceh', compact('title', 'description'));
    }
    
    public function sambutan_ketum()
    {
        $title = "Sambutan Ketua Umum";
        $description = "Asklin adalah asosiasi klinik seluruh indonesia.";

        return view('frontend.konas.sambutan_ketum', compact('title', 'description'));
    }

    public function sambutan_ketua_aceh()
    {
        $title = "Sambutan Ketua Aceh";
        $description = "Asklin adalah asosiasi klinik seluruh indonesia.";

        return view('frontend.konas.sambutan_ketua_aceh', compact('title', 'description'));
    }
    
    public function sambutan_ketua_panitia()
    {
        $title = "Sambutan Ketua Panitian";
        $description = "Asklin adalah asosiasi klinik seluruh indonesia.";

        return view('frontend.konas.sambutan_ketua_panitia', compact('title', 'description'));
    }

     public function konas_agenda()
    {
        $title = "Agenda Konas";
        $description = "Asklin adalah asosiasi klinik seluruh indonesia.";

        return view('frontend.konas.konas_agenda', compact('title', 'description'));
    }

     public function tour()
    {
        $title = "Hotel, Tour & Travel Konas";
        $description = "Asklin adalah asosiasi klinik seluruh indonesia.";

        return view('frontend.konas.tour', compact('title', 'description'));
    }
    
    
    
    public function registrasi()
    {
        $title = "Registrasi KONAS";
        $description = "Asklin adalah asosiasi klinik seluruh indonesia.";
        $provinsi = Province::get();

        return view('frontend.konas.registrasi', compact('title', 'description', 'provinsi'));
    }
    
    public function store(Request $request)
    {
        // cek apakah email sudah terdaftar sebelumnya
        $existingUser = KonasPeserta::where('email', $request->email)->first();
        if ($existingUser) {
            return redirect()->route('konas.akun');
        }

        $secret_key = 'Basic '.config('xendit.key_auth');
        $external_id = Str::random(10);

        $data_request = Http::withHeaders([
            'Authorization' => $secret_key
        ])->post('https://api.xendit.co/v2/invoices', [
            'external_id' => $external_id,
            'amount' => '1500000'
        ]);
        
        $response = $data_request->object();
        
        $destinationPath = 'images/konas/';
        
        $cekdata = KonasPeserta::find($request->id);
        $cd = KonasPeserta::selectRaw('MAX(RIGHT(no_anggota,4)) AS kd_max')->get();
        $kd = "";
        if($cd->count()>0){
            foreach($cd as $k){
                $tmp = ((int)$k->kd_max)+1;
                $kd = sprintf("%04s", $tmp);
            }
        }else{
            $kd = "0001";
        }
    
        $noanggota = 'konas-'.$kd;
    
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
        $konas = KonasPeserta::create([
            'no_anggota' => $noanggota,
            'nama_peserta' => $request->nama_peserta,
            'nik' => $request->nik,
            'tgl_lahir' => $request->tgl_lahir,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'foto' => $foto,
            'nama_klinik' => $request->nama_klinik,
            'alamat_klinik' => $request->alamat_klinik,
            'status' => $request->status,
            'status_pembayaran' => $response->status,
            'link_pembayaran' => $response->invoice_url,
            'doc_no' => $external_id,
            'biaya_pendaftaran' => '1500000',
            'cabang' => $request->id_kota,
            'id_provinsi' => $request->id_provinsi,
            'surat_mandat' => $surat_mandat,
            'komisi' => $request->komisi,
            'nama_sertifikat' => $request->nama_sertifikat
        ]);
        
        if($konas->id){
            // Send Email
            $mailData = [
                'title' => 'Pendaftaran KONAS 2023 Berhasil',
                'body' => 'Pendaftaran KONAS 2023 berhasil.'
            ];
            Mail::to($konas->email)->send(new Konas($mailData));
            
            return redirect()->away($response->invoice_url);
        }else{
            return redirect()->route('konas.registrasi');
        }
    }
    
    public function bayar(Request $request)
    {
        $konas = KonasPeserta::where('email', Auth()->user()->email)->first();
        $secret_key = 'Basic '.config('xendit.key_auth');
        $external_id = $konas->doc_no;

        $data_request = Http::withHeaders([
            'Authorization' => $secret_key
        ])->post('https://api.xendit.co/v2/invoices', [
            'external_id' => $external_id,
            'amount' => '1500000'
        ]);
        
        $response = $data_request->object();
    
        return redirect()->away($response->invoice_url);
    }
    
    public function callback()
    {
        $data = request()->all();
        $status = $data['status'];
        $external_id = $data['external_id'];
        
        KonasPeserta::where('doc_no', $external_id)->update([
            'status_pembayaran' => $status
        ]);
        
        KonasBookingHotel::where('doc_no', $external_id)->update([
            'status_pembayaran' => $status
        ]);

        return response()->json($data);
    }

    public function akun()
    {
        $title = "Akun KONAS";
        $description = "Asklin adalah asosiasi klinik seluruh indonesia.";
        $konas = KonasPeserta::where('email', Auth()->user()->email)->first();
        $hotel = KonasMasterHotel::where('status', '1')->get();
        $myhotel = KonasBookingHotel::where('id_user', Auth()->user()->id)->get();
        $myflight = KonasPenerbangan::where('id_user', Auth()->user()->id)->get();

        return view('frontend.konas.akun', compact('title', 'description', 'konas', 'hotel', 'myhotel', 'myflight'));
    }
    
    public function getTipe(request $request)
    {
        $id_hotel = $request->id_hotel;

        $data = KonasMasterTipeHotel::where('id_hotel', $id_hotel)->get();

        return response()->json($data);
    }
    
    public function getTipeHotel(request $request)
    {
        $id = $request->id;

        $data = KonasMasterTipeHotel::where('id', $id)->first();

        return response()->json($data);
    }
    
    public function storeHotel(Request $request)
    {
        $secret_key = 'Basic '.config('xendit.key_auth');
        $external_id = Str::random(10);
        $total = $request->total_harga_kamar + $request->total_harga_extrabed;

        $data_request = Http::withHeaders([
            'Authorization' => $secret_key
        ])->post('https://api.xendit.co/v2/invoices', [
            'external_id' => $external_id,
            'amount' => $total,
        ]);
        
        $response = $data_request->object();
        
        $getHotel = KonasMasterHotel::find($request->id_hotel);
        $getTipe = KonasMasterTipeHotel::find($request->hotel);

        $booking = KonasBookingHotel::create([
            'hotel' => $getHotel->hotel.' '.$getTipe->tipe,
            'nama_peserta' => $request->nama_peserta,
            'jumlah' => $request->jumlah,
            'extrabed' => $request->extrabed,
            'harga_kasur' => $request->harga_kasur,
            'harga_kamar' => $request->harga_kamar,
            'total_harga_extrabed' => $request->total_harga_extrabed,
            'total_harga_kamar' => $request->total_harga_kamar,
            'total' => $total,
            'status_pembayaran' => $response->status,
            'link_pembayaran' => $response->invoice_url,
            'doc_no' => $external_id,
            'tanggal' => date('Y-m-d H:i:s'),
            'id_user' => Auth()->user()->id
        ]);
        
        if($booking->id){
            $stok = $getTipe->stok - $booking->jumlah;
            $stok_extrabed = $getTipe->stok_extrabed - $booking->extrabed;
            $getTipe->update(['stok' => $stok, 'stok_extrabed' => $stok_extrabed]);
            $getUser = User::find($booking->id_user);
            // Send Email
            $content = "
                Halo '.$getUser->name.', silahkan lakukan pembayaran dengan total $booking->total melalui link dibawah ini <a href='".$booking->link_pembayaran."'>'.$booking->link_pembayaran.'</a>
            ";
            $mailData = [
                'title' => 'Booking Hotel',
                'body' => 'Booking Hotel'
            ];
            Mail::to($getUser->email)->send(new Hotel($mailData));
            return redirect()->away($response->invoice_url);
        }else{
            return redirect()->route('konas.akun');
        }
    }

    public function cetakKartu($id)
    {

        $peserta = KonasPeserta::select('konas_peserta.*')
                ->where('konas_peserta.id', $id)
                ->first();
        
        $id = $peserta->id;

      try {
            ob_start();
            $content = view('frontend.konas.cetak_kartu', compact('peserta'));
        
            $html2pdf = new Html2Pdf('P', 'A4', 'fr', true, 'UTF-8', 1);
             $html2pdf->pdf->SetTitle('Cetak Kartu konas | PDF');
            $html2pdf->SetDefaultFont('Arial');
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->setTestTdInOnePage(false);
            $html2pdf->writeHTML($content, false);
            $html2pdf->output('sk_anggota.pdf');
        } catch (Html2PdfException $e) {
            $html2pdf->clean();
        
            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
        exit();
    }
    
    public function storePenerbangan(Request $request)
    {
        $booking = KonasPenerbangan::create([
            'tanggal' => $request->tanggal,
            'dari' => $request->dari,
            'tujuan' => $request->tujuan,
            'pesawat' => $request->pesawat,
            'id_user' => Auth()->user()->id
        ]);
        
        return redirect()->route('konas.akun');
    }
}
