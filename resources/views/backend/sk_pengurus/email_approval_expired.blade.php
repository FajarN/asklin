<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tautan Persetujuan Kadaluwarsa — ASKLIN</title>
    <!-- Google Fonts & Tailwind -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-200/80 overflow-hidden text-center p-8 space-y-5">
        <div class="w-16 h-16 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center mx-auto text-2xl">
            <i class="fas fa-history"></i>
        </div>

        <div class="space-y-2">
            <h2 class="text-base sm:text-lg font-bold text-slate-800">
                Tautan Persetujuan Tidak Berlaku
            </h2>
            <p class="text-xs text-slate-500 leading-relaxed">
                {{ $errorMessage ?? 'Tautan token persetujuan ini telah melewati batas masa aktif 7 (tujuh) hari atau telah digunakan sebelumnya.' }}
            </p>
        </div>

        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs text-slate-600 leading-relaxed text-left">
            <strong class="text-slate-800 block mb-1">Pemeriksaan Manual:</strong>
            Sesuai standar keamanan sistem ASKLIN, silakan masuk ke <strong>Dashboard Pengurus ASKLIN</strong> untuk meninjau dan memproses persetujuan dokumen SK ini secara langsung.
        </div>

        <div>
            <a href="{{ route('login') }}" class="inline-block w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition shadow-md shadow-emerald-600/20">
                <i class="fas fa-sign-in-alt mr-1"></i> Masuk ke Dashboard ASKLIN
            </a>
        </div>
    </div>
</body>
</html>

