<?php

namespace App\Http\Controllers\Backend\web;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:slider-list', ['only' => ['index', 'list']]);
        $this->middleware('permission:slider-create', ['only' => ['store']]);
        $this->middleware('permission:slider-edit', ['only' => ['edit']]);
        $this->middleware('permission:slider-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('backend.slider.Index');
    }

    public function list(Request $request)
    {
        $sliders = Slider::query();
        
        // Filtering by search query (if necessary)
        if ($request->has('search') && $request->search != '') {
            $sliders->where('judul', 'like', '%' . $request->search . '%');
        }
        
        return DataTables::of($sliders)
            ->addIndexColumn()
            ->addColumn('status', function($row) {
                return $row->status ? 'Active' : 'Inactive';
            })
          ->addColumn('foto_slider', function($row) {
                return '<img src="' . asset($row->foto_slider) . '" width="100" height="100">';
            })
            ->addColumn('action', function($row) {
                return '<button class="btn btn-info btn-sm" onclick="edit(' . $row->id . ')">Edit</button> 
                        <button class="btn btn-danger btn-sm" onclick="deleteu(' . $row->id . ')">Delete</button>';
            })
            ->rawColumns(['foto_slider', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'foto_slider' => $request->id ? 'nullable|image|mimes:jpeg,png,jpg,gif' : 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        $foto_slider_path = null;

        if ($request->hasFile('foto_slider')) {
            $file = $request->file('foto_slider');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('assets/images/sliders');

            // Pastikan folder tujuan ada
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Pindahkan file ke folder tujuan
            $file->move($destinationPath, $filename);

            $foto_slider_path = 'assets/images/sliders/' . $filename;
        }

        $slider = Slider::updateOrCreate(
            ['id' => $request->id],
            [
                'judul' => $request->judul,
                'status' => $request->status ?? 0,
            ]
        );

        // Update path foto jika upload baru
        if ($foto_slider_path) {
            $slider->foto_slider = $foto_slider_path;
            $slider->save();
        }

        return response()->json(['success' => 'Slider saved successfully']);
    }


    public function edit(Request $request)
    {
        $slider = Slider::find($request->id);
        return response()->json($slider);
    }

    public function destroy(Request $request)
    {
        $slider = Slider::find($request->id);
        $slider->delete();
        return response()->json(['success' => 'Slider deleted successfully']);
    }
}
