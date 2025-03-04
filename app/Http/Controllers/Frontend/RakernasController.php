<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use App\Models\{User};
use Spatie\Permission\Models\Role;
use Auth;
use Laravolt\Indonesia\Models\Province;
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;
use Carbon\Carbon;
use Mail;
use App\Mail\{Konas, Hotel};

class RakernasController extends Controller
{
    public function index()
    {
        $title = "Rakernas Asosiasi Klinik Indonesia";
        $description = "Asklin adalah asosiasi klinik seluruh indonesia.";

        return view('frontend.rakernas.index', compact('title', 'description'));
    }
    
}