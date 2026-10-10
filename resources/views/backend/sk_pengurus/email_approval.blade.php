<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan SK via Email — ASKLIN</title>
    <!-- Google Fonts & Tailwind -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/backend/css/modern-custom.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-lg w-full bg-white rounded-3xl shadow-xl border border-slate-200/80 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-700 p-6 text-white text-center">
            <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center mx-auto mb-3 shadow-inner">
                <i class="fas fa-stamp text-2xl text-white"></i>
            </div>
            <h1 class="text-lg font-bold">Pengesahan SK Pengurus ASKLIN</h1>
            <p class="text-xs text-emerald-100 mt-1">Verifikasi Persetujuan Aman Satu-Klik (Token 7 Hari)</p>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            @if(session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-center">
                    <i class="fas fa-check-circle text-2xl text-emerald-600 mb-2 block"></i>
                    <h3 class="font-bold text-sm">Persetujuan Berhasil Disimpan!</h3>
                    <p class="text-xs mt-1">{{ session('success') }}</p>
                    <div class="mt-4">
                        <a href="{{ route('sk_pengurus.timeline', $sk->id) }}" class="inline-block px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700">
                            Lihat Timeline SK di Dashboard &rarr;
                        </a>
                    </div>
                </div>
            @else
                <!-- Dokumen Overview -->
                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/80 space-y-2 text-xs">
                    <div class="flex justify-between border-b border-slate-200/60 pb-2">
                        <span class="text-slate-500">Nama Struktur:</span>
                        <strong class="text-slate-800 text-right">{{ $sk->strukturOrganisasi->nama_struktur }}</strong>
                    </div>
                    <div class="flex justify-between border-b border-slate-200/60 pb-2">
                        <span class="text-slate-500">Tingkatan:</span>
                        <strong class="text-emerald-700 uppercase">{{ $sk->tingkatan }}</strong>
                    </div>
                    <div class="flex justify-between border-b border-slate-200/60 pb-2">
                        <span class="text-slate-500">Periode:</span>
                        <strong class="text-slate-800">{{ $sk->strukturOrganisasi->periode }}</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Status Saat Ini:</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 uppercase">
                            {{ str_replace('_', ' ', $sk->status) }}
                        </span>
                    </div>
                </div>

                <!-- Form Konfirmasi -->
                <form action="{{ route('sk_pengurus.approve-email.process', $sk->email_token) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Pengesahan (Opsional):</label>
                        <textarea name="catatan" rows="3" class="w-full text-xs rounded-xl border border-slate-300 p-3 focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="Masukkan catatan atau instruksi jika ada..."></textarea>
                    </div>

                    <div class="p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-[11px] leading-relaxed">
                        <i class="fas fa-shield-alt mr-1 text-amber-600"></i>
                        Dengan mengklik tombol di bawah ini, Anda menyatakan persetujuan resmi selaku <strong>{{ strtoupper($sk->email_token_role) }}</strong> untuk memproses SK kepengurusan ini ke tahap berikutnya.
                    </div>

                    <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl transition shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-2">
                        <i class="fas fa-check"></i>
                        <span>Konfirmasi & Setujui SK Sekarang</span>
                    </button>
                </form>

                <div class="text-center pt-2">
                    <a href="{{ route('sk_pengurus.timeline', $sk->id) }}" class="text-xs text-slate-400 hover:text-emerald-600 hover:underline">
                        Buka Detail Lengkap di Dashboard Pengurus
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
