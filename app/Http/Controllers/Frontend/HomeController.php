<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Berita, Anggota, Fasilitas, FasilitasKlinik, SDM, KategoriSDM, RumahSakit, Asuransi, FotoKlinik, Pembayaran, PembayaranKategori, Sertifikat, Event, Galery, PendaftaranEvent};
use Laravolt\Indonesia\Models\Province;
use Auth;
use DataTables;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $title = "Asosiasi Klinik Indonesia";
        $description = "Asklin adalah asosiasi klinik seluruh indonesia.";

        return view('frontend.home.index', compact('title', 'description'));
    }

    public function pendaftaran_event()
    {
        $title = "Pendaftaran Event";
        $description = "Pendaftaran Event.";
        $provinsi = Province::get();

        return view('frontend.home.pendaftaran_event', compact('title', 'description', 'provinsi'));
    }

    public function daftarEvent(Request $request)
    {
        PendaftaranEvent::create([
            'nama_peserta' => $request->nama_peserta,
            'keanggotaaan' => $request->keanggotaaan,
            'nama_klinik' => $request->nama_klinik,
            'alamat' => $request->alamat,
            'id_provinsi' => $request->id_provinsi,
            'id_kota' => $request->id_kota,
            'id_kecamatan' => $request->id_kecamatan,
            'id_kelurahan' => $request->id_kelurahan,
            'email' => $request->email,
            'tlf' => $request->tlf,
            'nama_hotel' => $request->nama_hotel,
            'jumlah_kamar' => $request->jumlah_kamar,
            'tgl_masuk' => $request->tgl_masuk,
            'tgl_keluar' => $request->tgl_keluar,
        ]);

        return redirect()->route('home');
    }

    public function sejarah()
    {
        $title = "Sejarah";
        $description = "Sejarah asklin.";

        return view('frontend.home.sejarah', compact('title', 'description'));
    }

    public function visimisi()
    {
        $title = "Visi Misi";
        $description = "Visi misi asklin.";

        return view('frontend.home.visimisi', compact('title', 'description'));
    }

    public function latarbelakang()
    {
        $title = "Latar Belakang";
        $description = "Latar belakang asklin.";

        return view('frontend.home.latarbelakang', compact('title', 'description'));
    }

    public function penguruspusat()
    {
        $title = "Pengurus Pusat";
        $description = "Pengurus pusat asklin.";

        return view('frontend.home.penguruspusat', compact('title', 'description'));
    }

    public function agendakegiatan()
    {
        $title = "Agenda & Kegiatan";
        $description = "Agenda kegiatan asklin.";

        return view('frontend.home.agendakegiatan', compact('title', 'description'));
    }

    public function berita(Request $request)
    {
        $title = "Berita";
        $description = "Berita terbaru seputar dunia kesehatan";

        $berita = Berita::select('berita.*', 'berita_kategori.nama')
            ->join('berita_kategori', 'berita_kategori.id', '=', 'berita.id_kategori')
            ->where('berita.status', '1')
            ->orderBy('id', 'DESC')->paginate(10);

        return view('frontend.home.berita', compact('title', 'description', 'berita'));
    }

    public function beritaDetail($slug)
    {
        $berita = Berita::where('path', $slug)->first();
        $title = $berita->judul;
        $desc = $berita->konten;

        return view('frontend.home.beritadetail', compact('title', 'desc', 'berita'));
    }

    public function event(Request $request)
    {
        $title = "Berita";
        $description = "Event terbaru asklin";

        $event = Event::select('events.*', 'event_kategori.nama')
            ->join('event_kategori', 'event_kategori.id', '=', 'events.id_kategori')
            ->where('events.status', '1')
            ->orderBy('id', 'DESC')->paginate(5);

        return view('frontend.home.event', compact('title', 'description', 'event'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function eventDetail($slug)
    {
        $event = Event::where('path', $slug)->first();
        $title = $event->judul;
        $desc = $event->konten;

        return view('frontend.home.eventdetail', compact('title', 'desc', 'event'));
    }

    public function galery(Request $request)
    {
        $title = "Galery";
        $description = "Galery terbaru asklin";

        $galery = Galery::select('galery.*', 'a.name')
            ->join('users as a', 'a.id', '=', 'galery.created_by')
            ->where('galery.status', '1')
            ->latest()->get();

        return view('frontend.home.galery', compact('title', 'description', 'galery'));
    }

    public function kontak(Request $request)
    {
        $title = "Kontak";
        $description = "Hubungi PP Asklin";

        return view('frontend.home.kontak', compact('title', 'description'));
    }

    public function UserProfile()
    {
        $title = "User Profile";
        $desc = "";

        $anggota = Anggota::select('anggota.*', 'indonesia_provinces.name as provinsi', 'indonesia_cities.name as kota', 'indonesia_districts.name as kecamatan', 'indonesia_villages.name as kelurahan')
            ->leftJoin('indonesia_provinces', 'indonesia_provinces.code', '=', 'anggota.id_provinsi')
            ->leftJoin('indonesia_cities', 'indonesia_cities.code', '=', 'anggota.id_kota')
            ->leftJoin('indonesia_districts', 'indonesia_districts.code', '=', 'anggota.id_kecamatan')
            ->leftJoin('indonesia_villages', 'indonesia_villages.code', '=', 'anggota.id_kelurahan')
            ->where('id_user', Auth::user()->id)->first();
        return view('frontend.user_profile', compact('title', 'desc', 'anggota'));
    }

    public function createAnggota()
    {
        Anggota::create([
            'id_user' => Auth::user()->id,
            'id_provinsi' => '31',
            'id_kota' => '3171',
            'id_kecamatan' => '317101',
            'id_kelurahan' => '3171011001'
        ]);

        return redirect()->route('syarat.ketentuan');
    }

    public function updateAnggota(Request $request, $id)
    {
        if (!empty($request->id_fasilitas)) {
            $fasilitas = implode(",", $request->id_fasilitas);
        } else {
            $fasilitas = NULL;
        }

        $destinationPath = 'images/file/';

        if ($request->hasFile('sio')) {
            $img_ext = $request->file('sio')->getClientOriginalExtension();
            $sio = 'sio-' . time() . '.' . $img_ext;
            $path = $request->file('sio')->move($destinationPath, $sio);
        } else {
            $sio = NULL;
        }

        Anggota::where('id', $id)->update([
            'nama_klinik' => $request->nama_klinik,
            'status' => $request->status,
            'id_provinsi' => $request->id_provinsi,
            'id_kota' => $request->id_kota,
            'id_kecamatan' => $request->id_kecamatan,
            'id_kelurahan' => $request->id_kelurahan,
            'nama_kontak' => $request->nama_kontak,
            'email' => $request->email,
            'tlf' => $request->tlf,
            'status_pendaftar' => $request->status_pendaftar,
            'status_kepemilikan' => $request->status_kepemilikan,
            'nama_pemilik_klinik' => $request->nama_pemilik_klinik,
            'bentuk_badan_hukum' => $request->nama_badan_hukum,
            'nama_badan_usaha' => $request->nama_badan_usaha,
            'bentuk_badan_usaha' => $request->bentuk_badan_usaha,
            'jenis_klinik' => $request->jenis_klinik,
            'alamat_klinik' => $request->alamat_klinik,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'kode_pos' => $request->kode_pos,
            'tlf_klinik' => $request->tlf_klinik,
            'id_fasilitas' => $fasilitas,
            'id_layanan' => implode(",", $request->id_layanan),
            'no_ijin' => $request->no_ijin,
            'tgl_ijin' => date('Y-m-d', strtotime($request->tgl_ijin)),
            'tgl_akhir_ijin' => date('Y-m-d', strtotime($request->tgl_akhir_ijin)),
            'fasilitas_klinik' => implode(",", $request->fasilitas_klinik),
            'id_user' => Auth::user()->id,
            'status' => 'create_dokter',
            'sio' => $sio
        ]);

        return redirect()->route('pendaftaran.sdm');
    }

    public function SyaratKetentuan()
    {
        $title = "Syarat & Ketentuan";
        $desc = "";

        return view('frontend.syarat_ketentuan', compact('title', 'desc'));
    }

    public function PendaftaranAnggota()
    {
        $title = "Pendaftaran Anggota";
        $desc = "";

        $anggota = Anggota::where('id_user', Auth::user()->id)->first();
        $provinsi = Province::get();
        $fasilitas = Fasilitas::where('status', '1')->get();
        $fasilitas_klinik = FasilitasKlinik::where('status', '1')->get();

        return view('frontend.pendaftaran_anggota', compact('title', 'desc', 'anggota', 'provinsi', 'fasilitas', 'fasilitas_klinik'));
    }

    public function PendaftaranSDM()
    {
        $title = "Pendaftaran SDM";
        $desc = "";

        $anggota = Anggota::where('id_user', Auth::user()->id)->first();

        return view('frontend.pendaftaran_sdm', compact('title', 'desc', 'anggota'));
    }

    //SDM PJ

    public function sdm_pjk(Request $request)
    {
        if ($request->ajax()) {
            $anggota = Anggota::where('id_user', Auth::user()->id)->first();
            $data = SDM::where(['id_klinik' => $anggota->id, 'id_kategori_sdm' => '1'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $actionBtn = '
                        <a href="javascript:void(0)" onclick="deletepj(' . $data["id"] . ')" ><i class="fa fa-trash-alt"></i>Hapus</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store_sdm_pj(Request $request)
    {
        $anggota = Anggota::where('id_user', Auth::user()->id)->first();
        $destinationPath = 'images/file/';
        if ($request->hasFile('file_sip_pj')) {
            $img_ext = $request->file('file_sip_pj')->getClientOriginalExtension();
            $file_sip = 'sip-' . time() . '.' . $img_ext;
            $path = $request->file('file_sip_pj')->move($destinationPath, $file_sip);
        } else {
            $file_sip = NULL;
        }
        if ($request->hasFile('file_str_pj')) {
            $img_ext1 = $request->file('file_str_pj')->getClientOriginalExtension();
            $file_str = 'str-' . time() . '.' . $img_ext1;
            $path = $request->file('file_str_pj')->move($destinationPath, $file_str);
        } else {
            $file_str = NULL;
        }

        $data = SDM::updateOrCreate(
            ['id' => $request->id_pj],
            [
                'nama_dokter' => $request->nama_dokter_pj,
                'nama' => $request->nama_dokter_pj,
                'id_kategori_sdm' => $request->id_kategori_sdm_pj,
                'peranan' => 'penanggung jawab',
                'npa_idi' => $request->npa_idi_pj,
                'no_str' => $request->no_str_pj,
                'no_sip' => $request->no_sip_pj,
                'email_dokter' => $request->email_dokter_pj,
                'tgl_akhir_sip' => date('Y-m-d', strtotime($request->tgl_akhir_sip_pj)),
                'no_tlf' => $request->no_tlf_pj,
                'file_sip' => $file_sip,
                'file_str' => $file_str,
                'id_klinik' => $anggota->id
            ]
        );

        return Response()->json($data);
    }

    public function delete_sdm_pj(Request $request)
    {
        $data = SDM::where('id', $request->id)->first();
        // if(file_exists(public_path() .  '/images/file/' . $data->file_str)) {
        //     unlink(public_path() .  '/images/file/' . $data->file_str);
        // }
        // if(file_exists(public_path() .  '/images/file/' . $data->file_sip)) {
        //     unlink(public_path() .  '/images/file/' . $data->file_sip);
        // }
        $data->delete();
        return Response()->json($data);
    }

    //SDM DP

    public function sdm_dp(Request $request)
    {
        if ($request->ajax()) {
            $anggota = Anggota::where('id_user', Auth::user()->id)->first();
            $data = SDM::where(['id_klinik' => $anggota->id, 'id_kategori_sdm' => '2'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $actionBtn = '
                        <a href="javascript:void(0)" onclick="deletepj(' . $data["id"] . ')" ><i class="fa fa-trash-alt"></i>Hapus</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store_sdm_dp(Request $request)
    {
        $anggota = Anggota::where('id_user', Auth::user()->id)->first();
        $destinationPath = 'images/file/';
        if ($request->hasFile('file_str_dp')) {
            $img_ext1 = $request->file('file_str_dp')->getClientOriginalExtension();
            $file_str = 'str-' . time() . '.' . $img_ext1;
            $path = $request->file('file_str_dp')->move($destinationPath, $file_str);
        } else {
            $file_str = NULL;
        }

        $data = SDM::updateOrCreate(
            ['id' => $request->id_dp],
            [
                'nama_dokter' => $request->nama_dokter_dp,
                'nama' => $request->nama_dokter_dp,
                'id_kategori_sdm' => $request->id_kategori_sdm_dp,
                'peranan' => 'dokter',
                'npa_idi' => $request->npa_idi_dp,
                'no_str' => $request->no_str_dp,
                'no_sip' => $request->no_sip_dp,
                'email_dokter' => $request->email_dokter_dp,
                'tgl_akhir_sip' => date('Y-m-d', strtotime($request->tgl_akhir_sip_dp)),
                'no_tlf' => $request->no_tlf_dp,
                'file_str' => $file_str,
                'id_klinik' => $anggota->id
            ]
        );

        return Response()->json($data);
    }

    //SDM TK

    public function sdm_tk(Request $request)
    {
        if ($request->ajax()) {
            $anggota = Anggota::where('id_user', Auth::user()->id)->first();
            $data = SDM::where(['id_klinik' => $anggota->id, 'id_kategori_sdm' => '3'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $actionBtn = '
                        <a href="javascript:void(0)" onclick="deletepj(' . $data["id"] . ')" ><i class="fa fa-trash-alt"></i>Hapus</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store_sdm_tk(Request $request)
    {
        $anggota = Anggota::where('id_user', Auth::user()->id)->first();
        $destinationPath = 'images/file/';
        if ($request->hasFile('file_str_tk')) {
            $img_ext1 = $request->file('file_str_tk')->getClientOriginalExtension();
            $file_str = 'str-' . time() . '.' . $img_ext1;
            $path = $request->file('file_str_tk')->move($destinationPath, $file_str);
        } else {
            $file_str = NULL;
        }

        $data = SDM::updateOrCreate(
            ['id' => $request->id_tk],
            [
                'nama' => $request->nama_tk,
                'id_kategori_sdm' => '3',
                'no_sib_sik' => $request->no_sib_sik_tk,
                'no_str' => $request->no_str_tk,
                'tgl_akhir_str' => date('Y-m-d', strtotime($request->tgl_akhir_str_tk)),
                'no_tlf' => $request->no_tlf_tk,
                'file_str' => $file_str,
                'id_klinik' => $anggota->id
            ]
        );

        return Response()->json($data);
    }

    //SDM TK

    public function sdm_tkl(Request $request)
    {
        if ($request->ajax()) {
            $anggota = Anggota::where('id_user', Auth::user()->id)->first();
            $data = SDM::where(['id_klinik' => $anggota->id, 'id_kategori_sdm' => '4'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $actionBtn = '
                        <a href="javascript:void(0)" onclick="deletepj(' . $data["id"] . ')" ><i class="fa fa-trash-alt"></i>Hapus</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store_sdm_tkl(Request $request)
    {
        $anggota = Anggota::where('id_user', Auth::user()->id)->first();
        $destinationPath = 'images/file/';
        if ($request->hasFile('file_str_tkl')) {
            $img_ext1 = $request->file('file_str_tkl')->getClientOriginalExtension();
            $file_str = 'str-' . time() . '.' . $img_ext1;
            $path = $request->file('file_str_tkl')->move($destinationPath, $file_str);
        } else {
            $file_str = NULL;
        }

        $data = SDM::updateOrCreate(
            ['id' => $request->id_tkl],
            [
                'nama' => $request->nama_tkl,
                'farmasi_apoteker' => $request->farmasi_apoteker_tkl,
                'id_kategori_sdm' => '4',
                'no_str' => $request->no_str_tkl,
                'tgl_akhir_str' => date('Y-m-d', strtotime($request->tgl_akhir_str_tkl)),
                'no_sip' => $request->no_sip_tkl,
                'no_sib_sik' => $request->no_sip_tkl,
                'ket_sib_sik' => $request->ket_sib_sik_tkl,
                'no_tlf' => $request->no_tlf_tk,
                'file_str' => $file_str,
                'id_klinik' => $anggota->id
            ]
        );

        return Response()->json($data);
    }

    //SDM Lain

    public function sdm_lain(Request $request)
    {
        if ($request->ajax()) {
            $anggota = Anggota::where('id_user', Auth::user()->id)->first();
            $data = SDM::where(['id_klinik' => $anggota->id, 'id_kategori_sdm' => '5'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $actionBtn = '
                        <a href="javascript:void(0)" onclick="deletelain(' . $data["id"] . ')" ><i class="fa fa-trash-alt"></i>Hapus</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store_sdm_lain(Request $request)
    {
        $anggota = Anggota::where('id_user', Auth::user()->id)->first();
        $destinationPath = 'images/file/';
        if ($request->hasFile('file_str_lain')) {
            $img_ext = $request->file('file_str_lain')->getClientOriginalExtension();
            $file_ijazah = 'ijazah-' . time() . '.' . $img_ext;
            $path = $request->file('file_str_lain')->move($destinationPath, $file_ijazah);
        } else {
            $file_str = NULL;
        }

        $data = SDM::updateOrCreate(
            ['id' => $request->id_lain],
            [
                'nama' => $request->nama_lain,
                'ijazah_terakhir' => $request->ijazah_terakhir_lain,
                'id_kategori_sdm' => '5',
                'jabatan' => $request->jabatan_lain,
                'file_str' => $file_ijazah,
                'id_klinik' => $anggota->id
            ]
        );

        return Response()->json($data);
    }

    public function delete_sdm_lain(Request $request)
    {
        $data = SDM::where('id', $request->id)->first();
        if (file_exists(public_path() .  '/images/file/' . $data->file_str)) {
            unlink(public_path() .  '/images/file/' . $data->file_str);
        }
        $data->delete();
        return Response()->json($data);
    }

    //Rumah Sakit Terdekat

    public function sdm_rumah_sakit(Request $request)
    {
        if ($request->ajax()) {
            $anggota = Anggota::where('id_user', Auth::user()->id)->first();
            $data = RumahSakit::where('id_klinik', $anggota->id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $actionBtn = '
                        <a href="javascript:void(0)" onclick="deleters(' . $data["id"] . ')" ><i class="fa fa-trash-alt"></i>Hapus</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store_sdm_rumah_sakit(Request $request)
    {
        $anggota = Anggota::where('id_user', Auth::user()->id)->first();
        $data = RumahSakit::updateOrCreate(
            ['id' => $request->id_rs],
            [
                'nama' => $request->nama_rs,
                'alamat' => $request->alamat_rs,
                'jarak' => $request->jarak_rs,
                'telepon' => $request->telepon_rs,
                'id_klinik' => $anggota->id
            ]
        );

        return Response()->json($data);
    }

    public function delete_sdm_rumah_sakit(Request $request)
    {
        $data = RumahSakit::where('id', $request->id)->delete();
        return Response()->json($data);
    }

    //Provider Asuransi

    public function sdm_asuransi(Request $request)
    {
        if ($request->ajax()) {
            $anggota = Anggota::where('id_user', Auth::user()->id)->first();
            $data = Asuransi::where('id_klinik', $anggota->id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $actionBtn = '
                        <a href="javascript:void(0)" onclick="deleteas(' . $data["id"] . ')" ><i class="fa fa-trash-alt"></i>Hapus</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store_sdm_asuransi(Request $request)
    {
        $anggota = Anggota::where('id_user', Auth::user()->id)->first();
        $destinationPath = 'images/file/';
        if ($request->hasFile('bukti_as')) {
            $img_ext = $request->file('bukti_as')->getClientOriginalExtension();
            $bukti = 'bukti-' . time() . '.' . $img_ext;
            $path = $request->file('bukti_as')->move($destinationPath, $bukti);
        }

        $data = Asuransi::updateOrCreate(
            ['id' => $request->id_as],
            [
                'nama' => $request->nama_as,
                'nama_kontak' => $request->nama_kontak_as,
                'alamat' => $request->alamat_as,
                'telepon' => $request->telepon_as,
                'bukti' => $bukti,
                'id_klinik' => $anggota->id
            ]
        );

        return Response()->json($data);
    }

    public function delete_sdm_asuransi(Request $request)
    {
        $data = Asuransi::where('id', $request->id)->first();
        if (file_exists(public_path() .  '/images/file/' . $data->bukti)) {
            unlink(public_path() .  '/images/file/' . $data->bukti);
        } else {
        }
        $data->delete();
        return Response()->json($data);
    }

    //Foto Klinik

    public function sdm_foto(Request $request)
    {
        if ($request->ajax()) {
            $anggota = Anggota::where('id_user', Auth::user()->id)->first();
            $data = FotoKlinik::where('id_klinik', $anggota->id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $actionBtn = '
                        <a href="javascript:void(0)" onclick="deletefoto(' . $data["id"] . ')" ><i class="fa fa-trash-alt"></i>Hapus</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store_sdm_foto(Request $request)
    {
        $anggota = Anggota::where('id_user', Auth::user()->id)->first();
        $destinationPath = 'images/file/';
        if ($request->hasFile('foto_foto')) {
            $img_ext = $request->file('foto_foto')->getClientOriginalExtension();
            $foto = 'ruang-' . time() . '.' . $img_ext;
            $path = $request->file('foto_foto')->move($destinationPath, $foto);
        }

        $data = FotoKlinik::updateOrCreate(
            ['id' => $request->id_foto],
            [
                'nama' => $request->nama_foto,
                'foto' => $foto,
                'status' => 'waiting',
                'id_klinik' => $anggota->id
            ]
        );

        return Response()->json($data);
    }

    public function delete_sdm_foto(Request $request)
    {
        $data = FotoKlinik::where('id', $request->id)->first();
        if (file_exists(public_path() .  '/images/file/' . $data->foto)) {
            unlink(public_path() .  '/images/file/' . $data->foto);
        }
        $data->delete();
        return Response()->json($data);
    }

    public function draft()
    {
        Anggota::where('id_user', Auth::user()->id)->update([
            'status' => 'create_dokter'
        ]);

        return redirect()->route('pendaftaran.sdm');
    }

    public function submitAkhir()
    {
        Anggota::where('id_user', Auth::user()->id)->update([
            'status' => 'waiting'
        ]);

        return redirect()->route('user.profile');
    }

    public function uploadFotoKlinik(Request $request)
    {
        $destinationPath = 'images/file/';
        if ($request->hasFile('logo')) {
            $img_ext = $request->file('logo')->getClientOriginalExtension();
            $foto = 'logo-' . time() . '.' . $img_ext;
            $path = $request->file('logo')->move($destinationPath, $foto);
        } else {
            $cek = Anggota::where('id_user', Auth::user()->id)->first();
            $foto = $cek->logo;
        }
        Anggota::where('id_user', Auth::user()->id)->update([
            'logo' => $foto
        ]);

        return redirect()->route('user.profile');
    }

    public function tagihan()
    {
        $title = "Tagihan";
        $description = "Asklin adalah asosiasi klinik seluruh indonesia.";

        $kategori = PembayaranKategori::where('status', '1')->get();
        $anggota = Anggota::where('id_user', Auth::user()->id)->first();

        return view('frontend.pembayaran', compact('title', 'description', 'anggota', 'kategori'));
    }

    public function getTagihan(Request $request)
    {
        if ($request->ajax()) {
            $anggota = Anggota::where('id_user', Auth::user()->id)->first();
            $data = Pembayaran::select('pembayaran.*', 'a.nama')->join('pembayaran_kategori as a', 'a.id', '=', 'pembayaran.id_kategori')->where('id_anggota', $anggota->id)->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function storeTagihan(Request $request)
    {
        $destinationPath = 'images/file/';
        if ($request->hasFile('bukti')) {
            $img_ext = $request->file('bukti')->getClientOriginalExtension();
            $bukti = 'bukti-pembayaran-' . time() . '.' . $img_ext;
            $path = $request->file('bukti')->move($destinationPath, $bukti);
        }

        $data = Pembayaran::updateOrCreate(
            ['id' => $request->id],
            [
                'id_kategori' => $request->id_kategori,
                'status' => '0',
                'id_anggota' => $request->id_anggota,
                'keterangan' => $request->keterangan,
                'bukti' => $bukti,
                'tanggal_bayar' => date('Y-m-d'),
                'total' => $request->total
            ]
        );

        return Response()->json($data);
    }

    public function sertifikat()
    {
        $title = "Sertifikat";
        $description = "Asklin adalah asosiasi klinik seluruh indonesia.";

        $anggota = Anggota::where('id_user', Auth::user()->id)->first();

        return view('frontend.sertifikat', compact('title', 'description', 'anggota'));
    }

    public function getSertifikat(Request $request)
    {
        if ($request->ajax()) {
            $anggota = Anggota::where('id_user', Auth::user()->id)->first();
            $data = Sertifikat::select('sertifikat.*', 'a.nama_klinik', 'a.no_anggota', 'a.updated_at')
                ->join('anggota as a', 'a.id', '=', 'sertifikat.id_anggota')
                ->where('sertifikat.id_anggota', $anggota->id)
                ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $actionBtn = '
                        <a href="' . route("cetak_sertifikat", $data->id) . '" class="btn btn-info btn-sm" >Cetak</a>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function downloadSK($id)
    {
        $anggota = Anggota::select(
            'anggota.*',
            \DB::raw('GROUP_CONCAT(DISTINCT a.nama SEPARATOR ", ") as nama_fasilitas_klinik'),
            'b.name as kota',
            'c.name as provinsi'
        )
            ->leftJoin('fasilitas_klinik as a', \DB::raw("FIND_IN_SET(a.id, anggota.fasilitas_klinik)"), ">", \DB::raw("'0'"))
            ->join('indonesia_cities as b', 'b.code', '=', 'anggota.id_kota')
            ->join('indonesia_provinces as c', 'c.code', '=', 'anggota.id_provinsi')
            ->where('id_user', Auth::user()->id)->first();
        $id = $anggota->id;

        try {
            ob_start();
            $content = view('frontend.download_sk', compact('anggota'));

            $html2pdf = new Html2Pdf('P', 'F4', 'fr', true, 'UTF-8', 1);
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->pdf->SetTitle('SK Anggota ASKLIN');
            $html2pdf->writeHTML($content);
            $html2pdf->output('sk_anggota.pdf');
        } catch (Html2PdfException $e) {
            $html2pdf->clean();

            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
        exit();
    }

    public function cetakSertifikat($id)
    {
        $anggota = Sertifikat::select(
            'sertifikat.*',
            'a.nama_klinik',
            'a.no_anggota',
            'a.updated_at',
            'a.nama_kontak',
            'a.alamat_klinik',
            'a.rt',
            'a.rw',
            'b.name as kelurahan',
            'c.name as kecamatan',
            'd.name as kota',
            'e.name as provinsi',
            'a.id_kota',
            'a.id_provinsi'
        )
            ->join('anggota as a', 'a.id', '=', 'sertifikat.id_anggota')
            ->leftJoin('indonesia_villages as b', 'b.code', '=', 'a.id_kelurahan')
            ->leftJoin('indonesia_districts as c', 'c.code', '=', 'a.id_kecamatan')
            ->leftJoin('indonesia_cities as d', 'd.code', '=', 'a.id_kota')
            ->leftJoin('indonesia_provinces as e', 'e.code', '=', 'a.id_provinsi')
            ->where('sertifikat.id', $id)
            ->first();

        $id = $anggota->id;

        try {
            ob_start();
            $content = view('frontend.print_sertifikat', compact('anggota'));

            $html2pdf = new Html2Pdf('L', 'A4', 'fr', true, 'UTF-8', 1);
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->pdf->SetTitle('Sertifikat Anggota ASKLIN');
            $html2pdf->writeHTML($content);
            $html2pdf->output('sk_anggota.pdf');
        } catch (Html2PdfException $e) {
            $html2pdf->clean();

            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }
        exit();
    }
}
