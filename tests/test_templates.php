<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\TemplateService;
use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Http\Request;

echo "=== START TEMPLATE ENGINE TEST ===" . PHP_EOL;

$controller = new HomeController();
$request = Request::create('/', 'GET');
\Illuminate\Support\Facades\View::share('errors', new \Illuminate\Support\ViewErrorBag());

$templates = ['canvas', 'custom_org', 'modern_org'];

foreach ($templates as $t) {
    TemplateService::setActiveTemplate($t);
    $active = TemplateService::getActiveTemplate();
    echo "\n>>> Testing Active Template: [{$active}] <<<\n";
    
    // Test index
    $vIndex = $controller->index($request);
    echo "1. Index View: " . $vIndex->name() . " -> Content rendered: " . (strlen($vIndex->render()) > 0 ? "YES (" . strlen($vIndex->render()) . " bytes)" : "NO") . "\n";

    // Test sejarah
    $vSejarah = $controller->sejarah();
    echo "2. Sejarah View: " . $vSejarah->name() . " -> Content rendered: " . (strlen($vSejarah->render()) > 0 ? "YES" : "NO") . "\n";

    // Test visimisi
    $vVisi = $controller->visimisi();
    echo "3. VisiMisi View: " . $vVisi->name() . " -> Content rendered: " . (strlen($vVisi->render()) > 0 ? "YES" : "NO") . "\n";

    // Test latarbelakang
    $vLatar = $controller->latarbelakang();
    echo "4. LatarBelakang View: " . $vLatar->name() . " -> Content rendered: " . (strlen($vLatar->render()) > 0 ? "YES" : "NO") . "\n";

    // Test penguruspusat
    $vPengurus = $controller->penguruspusat();
    echo "5. PengurusPusat View: " . $vPengurus->name() . " -> Content rendered: " . (strlen($vPengurus->render()) > 0 ? "YES" : "NO") . "\n";

    // Test berita
    $vBerita = $controller->berita($request);
    echo "6. Berita View: " . $vBerita->name() . " -> Content rendered: " . (strlen($vBerita->render()) > 0 ? "YES" : "NO") . "\n";

    // Test event
    $vEvent = $controller->event($request);
    echo "7. Event View: " . $vEvent->name() . " -> Content rendered: " . (strlen($vEvent->render()) > 0 ? "YES" : "NO") . "\n";

    // Test galery
    $vGalery = $controller->galery($request);
    echo "8. Galery View: " . $vGalery->name() . " -> Content rendered: " . (strlen($vGalery->render()) > 0 ? "YES" : "NO") . "\n";

    // Test kontak
    $vKontak = $controller->kontak($request);
    echo "9. Kontak View: " . $vKontak->name() . " -> Content rendered: " . (strlen($vKontak->render()) > 0 ? "YES" : "NO") . "\n";

    // Test pendaftaran_event
    $vDaftarEvent = $controller->pendaftaran_event();
    echo "10. Pendaftaran Event View: " . $vDaftarEvent->name() . " -> Content rendered: " . (strlen($vDaftarEvent->render()) > 0 ? "YES" : "NO") . "\n";

    // Test Berita Detail
    $mockBerita = (object)[
        'judul' => 'Uji Coba Judul Berita',
        'konten' => '<p>Konten uji coba berita resmi ASKLIN.</p>',
        'thumbnail' => null,
        'thumb' => null,
        'tanggal' => now(),
        'lokasi' => 'Jakarta',
        'created_at' => now(),
        'path' => 'uji-coba-berita',
        'creator' => (object)['name' => 'Admin'],
        'visits' => collect([(object)['created_at' => now()]]),
        'total_visits' => 10,
        'unique_visits' => 5
    ];
    $vBeritaDet = TemplateService::view('beritadetail', ['title' => 'Test', 'desc' => 'Test', 'berita' => $mockBerita, 'images' => collect([])]);
    echo "11. Berita Detail View: " . $vBeritaDet->name() . " -> Content rendered: " . (strlen($vBeritaDet->render()) > 0 ? "YES (" . strlen($vBeritaDet->render()) . " bytes)" : "NO") . "\n";

    // Test Event Detail
    $sampleEvent = \App\Models\Event::where('status', '1')->first();
    if ($sampleEvent) {
        $vEventDet = $controller->eventDetail($sampleEvent->path);
        echo "12. Event Detail View: " . $vEventDet->name() . " -> Content rendered: " . (strlen($vEventDet->render()) > 0 ? "YES (" . strlen($vEventDet->render()) . " bytes)" : "NO") . "\n";
    }

    // Test Login View
    $vLogin = TemplateService::view('login');
    echo "13. Login View: " . $vLogin->name() . " -> Content rendered: " . (strlen($vLogin->render()) > 0 ? "YES (" . strlen($vLogin->render()) . " bytes)" : "NO") . "\n";

    // Test Register View
    $vRegister = TemplateService::view('register');
    echo "14. Register View: " . $vRegister->name() . " -> Content rendered: " . (strlen($vRegister->render()) > 0 ? "YES (" . strlen($vRegister->render()) . " bytes)" : "NO") . "\n";

    // Test User Profile View
    $sampleAnggota = \App\Models\Anggota::first();
    if (!$sampleAnggota) {
        $sampleAnggota = new \App\Models\Anggota([
            'id' => 999,
            'id_user' => 1,
            'nama_klinik' => 'Klinik Pratama Sehat Uji Coba',
            'no_anggota' => 'ASKLIN-12345',
            'status' => 'approved',
            'verifikasi_cabang' => 'Diverifikasi Cabang',
            'verifikasi_pusat' => 'Diverifikasi Pusat',
            'rt' => '01',
            'rw' => '02',
        ]);
    }
    $vProfile = TemplateService::view('user_profile', ['title' => 'Profile', 'desc' => '', 'anggota' => $sampleAnggota]);
    echo "15. User Profile View: " . $vProfile->name() . " -> Content rendered: " . (strlen($vProfile->render()) > 0 ? "YES (" . strlen($vProfile->render()) . " bytes)" : "NO") . "\n";
}

// Reset back to canvas
TemplateService::setActiveTemplate('canvas');
echo "\n=== RESET BACK TO CANVAS: " . TemplateService::getActiveTemplate() . " ===\n";
echo "=== TEST COMPLETED SUCCESSFULLY ===\n";
