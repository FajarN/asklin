<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Agenda};
use DataTables;
use Illuminate\Support\Str;

class AgendaController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:agenda-list|agenda-create|agenda-edit|agenda-delete', ['only' => ['index','show']]);
        $this->middleware('permission:agenda-create', ['only' => ['create','store']]);
        $this->middleware('permission:agenda-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:agenda-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('backend.agenda.index');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $data = Agenda::latest()->get();
            return Datatables::of($data)
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->collection = $instance->collection->filter(function ($data) use ($request) {
                            if (Str::contains(Str::lower($data['perihal']), Str::lower($request->get('search')))){
                                return true;
                            }
                            return false;
                        });
                    }
                })
                ->addIndexColumn()
                ->addColumn('action', 'backend.agenda.action')
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function create()
    {
        return view('backend.agenda.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'jenis' => 'required',
            'tanggal' => 'required',
            'perihal' => 'required',
            'tempat' => 'required',
            'pimpinan' => 'required',
            'notulen' => 'required',
            'keputusan' => 'required'
        ]);

        $data = Agenda::create([
            'jenis' => $request->jenis,
            'tanggal' => date('Y-m-d H:i:s', strtotime($request->tanggal)),
            'perihal' => $request->perihal,
            'tempat' => $request->tempat,
            'pimpinan' => $request->pimpinan,
            'notulen' => $request->notulen,
            'keputusan' => $request->keputusan,
        ]);

        return redirect()->route('agenda.edit', $data->id)->with('success','Agenda berhasil dibuat');
    }

    public function edit(Request $request)
    {
        $data = Agenda::find($request->id);

        return view('backend.agenda.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'jenis' => 'required',
            'tanggal' => 'required',
            'perihal' => 'required',
            'tempat' => 'required',
            'pimpinan' => 'required',
            'notulen' => 'required',
            'keputusan' => 'required'
        ]);

        $data = Agenda::where('id', $id)->update([
            'jenis' => $request->jenis,
            'tanggal' => date('Y-m-d H:i:s', strtotime($request->tanggal)),
            'perihal' => $request->perihal,
            'tempat' => $request->tempat,
            'pimpinan' => $request->pimpinan,
            'notulen' => $request->notulen,
            'keputusan' => $request->keputusan,
        ]);

        return redirect()->route('agenda.edit', $id)->with('success','Agenda berhasil dibuat');
    }

    public function destroy(Request $request)
    {
        $data = Agenda::where('id', $request->id)->delete();
        return Response()->json($data);
    }
}