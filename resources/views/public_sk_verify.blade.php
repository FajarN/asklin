<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $isValid && $sk ? 'Verifikasi SK - ' . ($sk->no_sk ?: 'ASKLIN') : 'Verifikasi Dokumen ASKLIN' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            500: '#ef4444',
                            600: '#dc2626',
                            700: '#b91c1c',
                            800: '#991b1b',
                        },
                        asklin: {
                            green: '#059669',
                            darkgreen: '#065f46',
                            red: '#dc2626',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }
        .watermark-pattern {
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="watermark-pattern text-slate-800 antialiased p-3 sm:p-6 lg:p-10 flex flex-col items-center justify-center">

    <div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl border border-slate-200/80 overflow-hidden my-4">
        
        <!-- Header Bar -->
        <div class="bg-gradient-to-r from-red-700 via-red-600 to-emerald-700 p-4 sm:p-6 text-white text-center relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col items-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white rounded-2xl p-2 shadow-lg mb-3 flex items-center justify-center">
                    <img src="{{ asset('assets/images/logo.png') }}" onerror="this.src='{{ asset('assets/images/kop_asklin.jpg') }}'; this.className='max-h-full object-contain';" alt="Logo ASKLIN" class="max-h-full max-w-full object-contain">
                </div>
                <h1 class="text-base sm:text-xl font-extrabold uppercase tracking-wide">Asosiasi Klinik Indonesia (ASKLIN)</h1>
                <p class="text-xs sm:text-sm text-red-100 font-medium mt-0.5">Sistem Verifikasi Keabsahan Dokumen Digital Resmi</p>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/15 backdrop-blur-md rounded-full text-[11px] font-semibold mt-2.5">
                    <i class="fas fa-shield-halved"></i>
                    <span>Tervalidasi Digital Signature & Database Server</span>
                </div>
            </div>
        </div>

        @if(!$isValid || !$sk)
            <!-- Invalid / Not Found State -->
            <div class="p-8 text-center space-y-4">
                <div class="w-20 h-20 mx-auto rounded-full bg-red-100 text-red-600 flex items-center justify-center text-3xl">
                    <i class="fas fa-triangle-exclamation"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-900">Dokumen Tidak Ditemukan</h2>
                <p class="text-sm text-slate-600 max-w-md mx-auto">
                    {{ $message ?? 'Kode QR atau token verifikasi tidak cocok dengan arsip Surat Keputusan resmi di database ASKLIN.' }}
                </p>
                <div class="pt-4">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl transition">
                        <i class="fas fa-home"></i> Kembali ke Beranda ASKLIN
                    </a>
                </div>
            </div>
        @else
            <!-- Valid Document State -->
            <div class="p-5 sm:p-8 space-y-6">

                <!-- Status Banner -->
                @if($sk->status === 'terbit')
                    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex items-start sm:items-center gap-3.5">
                        <div class="w-11 h-11 rounded-full bg-emerald-600 text-white flex-shrink-0 flex items-center justify-center text-xl shadow-md shadow-emerald-600/20">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="flex-1">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-600 text-white mb-0.5">Dokumen Resmi Sah & Aktif</span>
                            <h3 class="text-sm sm:text-base font-bold text-emerald-950">Surat Keputusan (SK) Terverifikasi Otentik</h3>
                            <p class="text-[11px] sm:text-xs text-emerald-800">Dokumen ini sah diterbitkan oleh Pengurus Pusat Asosiasi Klinik Indonesia sesuai AD/ART dan tercatat dalam lembaran keputusan resmi organisasi.</p>
                        </div>
                    </div>
                @elseif($sk->status === 'dicabut')
                    <div class="p-4 rounded-xl bg-red-50 border border-red-200 flex items-start sm:items-center gap-3.5">
                        <div class="w-11 h-11 rounded-full bg-red-600 text-white flex-shrink-0 flex items-center justify-center text-xl">
                            <i class="fas fa-ban"></i>
                        </div>
                        <div class="flex-1">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-red-600 text-white mb-0.5">Status: Dicabut</span>
                            <h3 class="text-sm sm:text-base font-bold text-red-950">Surat Keputusan Tidak Berlaku Lagi</h3>
                            <p class="text-[11px] sm:text-xs text-red-800">SK ini telah dicabut atau digantikan oleh Surat Keputusan kepengurusan periode selanjutnya.</p>
                        </div>
                    </div>
                @else
                    <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 flex items-start sm:items-center gap-3.5">
                        <div class="w-11 h-11 rounded-full bg-amber-500 text-white flex-shrink-0 flex items-center justify-center text-xl">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="flex-1">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-amber-500 text-white mb-0.5">Status: {{ strtoupper($sk->status) }}</span>
                            <h3 class="text-sm sm:text-base font-bold text-amber-950">Dokumen Dalam Tahap Pemeriksaan</h3>
                            <p class="text-[11px] sm:text-xs text-amber-800">Surat keputusan ini sedang dalam alur verifikasi dan belum disahkan secara final oleh Ketua Umum PP ASKLIN.</p>
                        </div>
                    </div>
                @endif

                <!-- Document Main Details -->
                <div class="bg-slate-50/70 rounded-xl p-4 sm:p-6 border border-slate-200 space-y-4">
                    <div class="border-b border-slate-200/80 pb-3">
                        <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Nomor Surat Keputusan</span>
                        <h2 class="text-base sm:text-lg font-extrabold text-slate-900 font-mono tracking-tight mt-0.5">
                            {{ $sk->no_sk ?: '(Nomor SK Belum Diterbitkan)' }}
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Tentang / Perihal</span>
                            <span class="font-semibold text-slate-800 block mt-0.5">{{ $sk->perihal }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Tingkatan Organisasi</span>
                            <span class="font-semibold text-slate-800 block mt-0.5 uppercase">
                                Pengurus {{ $sk->tingkatan }} ({{ strtoupper($sk->tingkatan === 'cabang' ? 'PC' : ($sk->tingkatan === 'daerah' ? 'PD' : 'PP')) }})
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Wilayah Cakupan</span>
                            <span class="font-semibold text-slate-800 block mt-0.5">
                                {{ $struktur->kota ? $struktur->kota->name : '' }}
                                {{ $struktur->provinsi ? ($struktur->kota ? ', ' : '') . $struktur->provinsi->name : '' }}
                                @if(!$struktur->kota && !$struktur->provinsi) Seluruh Indonesia @endif
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Masa Bakti Kepengurusan</span>
                            <span class="font-semibold text-slate-800 block mt-0.5">{{ $struktur->periode }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Tanggal Ditetapkan</span>
                            <span class="font-semibold text-slate-800 block mt-0.5">
                                {{ $sk->tgl_penetapan ? $sk->tgl_penetapan->format('d F Y') : '-' }} (Ditetapkan di Jakarta)
                            </span>
                        </div>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-bold">Disahkan Oleh</span>
                            <span class="font-semibold text-slate-800 block mt-0.5">
                                {{ $ketum ? $ketum->nama_lengkap : 'dr. Eddi Junaidi, SpOG., SH., MKes' }}
                                <span class="text-slate-500 font-normal block text-[11px]">Ketua Umum Pengurus Pusat ASKLIN</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Personnel Preview Accordion/Table -->
                <div class="border border-slate-200 rounded-xl overflow-hidden">
                    <div class="bg-slate-100/80 px-4 py-3 flex items-center justify-between border-b border-slate-200">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-users text-slate-600"></i>
                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800">Daftar Susunan Personil Pengurus</h4>
                        </div>
                        <span class="text-[11px] font-semibold text-slate-500">
                            Total: {{ $pengurusSemua->count() }} Pengurus
                        </span>
                    </div>

                    <div class="p-3 sm:p-4 max-h-96 overflow-y-auto space-y-4">
                        @forelse($kelompokList as $kelompok)
                            @php
                                $pengurusKelompok = $kelompok->strukturPengurus ?: collect([]);
                            @endphp
                            @if($pengurusKelompok->count() > 0)
                                <div>
                                    <h5 class="text-[11px] font-extrabold uppercase tracking-wider text-red-700 bg-red-50/70 px-2.5 py-1 rounded mb-2 inline-block">
                                        {{ $kelompok->nama_kelompok }}
                                    </h5>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left text-xs">
                                            <thead>
                                                <tr class="border-b border-slate-200 text-slate-400 text-[10px] uppercase font-bold">
                                                    <th class="py-1 px-2 w-10">No</th>
                                                    <th class="py-1 px-2 w-44">Jabatan</th>
                                                    <th class="py-1 px-2">Nama Lengkap & Gelar</th>
                                                    <th class="py-1 px-2">Klinik / Instansi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100">
                                                @foreach($pengurusKelompok as $idx => $person)
                                                    <tr class="hover:bg-slate-50">
                                                        <td class="py-1.5 px-2 text-slate-400 font-mono">{{ $idx + 1 }}</td>
                                                        <td class="py-1.5 px-2 font-semibold text-slate-700">{{ $person->jabatan }}</td>
                                                        <td class="py-1.5 px-2 font-bold text-slate-900">{{ $person->nama_lengkap }}</td>
                                                        <td class="py-1.5 px-2 text-slate-500">{{ $person->keterangan ?: '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <p class="text-xs text-slate-400 italic text-center py-4">Data personalia pengurus belum diinput.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Security Verification Footer -->
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-[11px] text-slate-500">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-lock text-emerald-600 text-sm"></i>
                        <span>Token Enkripsi Keabsahan: <strong class="font-mono text-slate-700">{{ $sk->qr_token }}</strong></span>
                    </div>
                    <div>
                        <span>Diperiksa: {{ now()->format('d M Y, H:i:s') }} WIB</span>
                    </div>
                </div>

            </div>
        @endif

        <!-- Global Footer -->
        <div class="bg-slate-100/80 px-6 py-4 text-center border-t border-slate-200 text-xs text-slate-500">
            <p>&copy; {{ date('Y') }} <strong>Asosiasi Klinik Indonesia (ASKLIN)</strong>. Seluruh hak cipta dilindungi undang-undang.</p>
            <p class="text-[10px] text-slate-400 mt-0.5">Dokumen ini diterbitkan secara elektronik melalui Aplikasi Manajemen Terpadu ASKLIN.</p>
        </div>

    </div>

</body>
</html>
