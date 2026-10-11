@extends('layouts.backend.layout')

@section('title', 'Pengaturan Email SMTP')
@section('page_title', 'Pengaturan Email SMTP')

@section('content')
<div class="space-y-6">

    <!-- Responsive Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Pengaturan Sistem'],
        ['label' => 'Pengaturan Email SMTP'],
    ]" />

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-mail-bulk text-emerald-600"></i>
                <span>Pengaturan Server Email SMTP</span>
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Kelola konfigurasi server SMTP langsung dari database tanpa perlu mengubah file <code>.env</code>. Dilengkapi fitur live test kirim email.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('settings.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-cog"></i>
                <span>Konfigurasi Aplikasi</span>
            </a>
            <a href="{{ route('dashboard.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Alert Flash Messages -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs flex items-center gap-2.5">
            <i class="fas fa-check-circle text-emerald-600 text-base"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-xs flex items-center gap-2.5">
            <i class="fas fa-exclamation-triangle text-rose-600 text-base"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Kolom Kiri (8 Col): Form Konfigurasi Utama SMTP -->
        <div class="lg:col-span-7 space-y-6">
            <div class="card shadow-sm border border-slate-200 dark:border-slate-800">
                <div class="card-header flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 flex items-center gap-2">
                        <i class="fas fa-server text-emerald-600"></i>
                        <span>Parameter Server SMTP</span>
                    </h4>

                    <!-- Tombol Preset Cepat -->
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-[10px] text-slate-400 font-medium">Preset Cepat:</span>
                        <button type="button" onclick="applyPreset('gmail')" class="px-2 py-1 rounded text-[10px] font-semibold bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 transition">
                            <i class="fab fa-google mr-0.5"></i> Gmail
                        </button>
                        <button type="button" onclick="applyPreset('cloudflare')" class="px-2 py-1 rounded text-[10px] font-semibold bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200 transition">
                            <i class="fas fa-cloud mr-0.5"></i> Cloudflare / Custom
                        </button>
                        <button type="button" onclick="applyPreset('mailtrap')" class="px-2 py-1 rounded text-[10px] font-semibold bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-300 transition">
                            Mailtrap
                        </button>
                    </div>
                </div>

                <div class="card-body p-6">
                    <form action="{{ route('smtp_settings.update') }}" method="POST" id="formSmtpConfig">
                        @csrf

                        <div class="space-y-4">
                            <!-- Host & Port Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                <div class="md:col-span-8">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                        SMTP Host <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="mail_host" id="mail_host" value="{{ old('mail_host', $smtp['mail_host']) }}" class="form-control text-xs pl-8 font-mono" placeholder="contoh: smtp.gmail.com" required>
                                        <i class="fas fa-network-wired absolute left-2.5 top-3 text-xs text-slate-400"></i>
                                    </div>
                                    <small class="text-[10px] text-slate-400">Alamat server SMTP (contoh: smtp.gmail.com, mail.domain.com)</small>
                                </div>
                                <div class="md:col-span-4">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                        SMTP Port <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="number" name="mail_port" id="mail_port" value="{{ old('mail_port', $smtp['mail_port']) }}" class="form-control text-xs font-mono" placeholder="587" required>
                                    <small class="text-[10px] text-slate-400">587 (TLS) atau 465 (SSL)</small>
                                </div>
                            </div>

                            <!-- Encryption -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                    Protokol Enkripsi Keamanan
                                </label>
                                <div class="grid grid-cols-3 gap-3">
                                    <label class="cursor-pointer border rounded-xl p-2.5 flex items-center justify-center gap-2 text-xs font-semibold hover:border-emerald-500 transition {{ ($smtp['mail_encryption'] == 'tls' || empty($smtp['mail_encryption'])) ? 'border-emerald-600 bg-emerald-50/50 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-300' : 'border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300' }}">
                                        <input type="radio" name="mail_encryption" value="tls" class="hidden" {{ ($smtp['mail_encryption'] == 'tls' || empty($smtp['mail_encryption'])) ? 'checked' : '' }} onchange="updateRadioStyles()">
                                        <i class="fas fa-shield-alt text-emerald-600"></i>
                                        <span>TLS (Port 587)</span>
                                    </label>
                                    <label class="cursor-pointer border rounded-xl p-2.5 flex items-center justify-center gap-2 text-xs font-semibold hover:border-emerald-500 transition {{ $smtp['mail_encryption'] == 'ssl' ? 'border-emerald-600 bg-emerald-50/50 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-300' : 'border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300' }}">
                                        <input type="radio" name="mail_encryption" value="ssl" class="hidden" {{ $smtp['mail_encryption'] == 'ssl' ? 'checked' : '' }} onchange="updateRadioStyles()">
                                        <i class="fas fa-lock text-emerald-600"></i>
                                        <span>SSL (Port 465)</span>
                                    </label>
                                    <label class="cursor-pointer border rounded-xl p-2.5 flex items-center justify-center gap-2 text-xs font-semibold hover:border-emerald-500 transition {{ in_array($smtp['mail_encryption'], ['none', 'null', '']) ? 'border-emerald-600 bg-emerald-50/50 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-300' : 'border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300' }}">
                                        <input type="radio" name="mail_encryption" value="none" class="hidden" {{ in_array($smtp['mail_encryption'], ['none', 'null', '']) ? 'checked' : '' }} onchange="updateRadioStyles()">
                                        <i class="fas fa-unlock text-slate-400"></i>
                                        <span>None / Tanpa SSL</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Username & Password Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                        SMTP Username
                                    </label>
                                    <div class="relative">
                                        <input type="text" name="mail_username" id="mail_username" value="{{ old('mail_username', $smtp['mail_username']) }}" class="form-control text-xs pl-8 font-mono" placeholder="email@gmail.com">
                                        <i class="fas fa-user absolute left-2.5 top-3 text-xs text-slate-400"></i>
                                    </div>
                                    <small class="text-[10px] text-slate-400">Alamat email akun SMTP untuk login server</small>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1 flex items-center justify-between">
                                        <span>SMTP Password / App Password</span>
                                        <button type="button" onclick="togglePasswordVisibility()" class="text-[10px] text-emerald-600 hover:text-emerald-700 font-semibold flex items-center gap-1">
                                            <i class="fas fa-eye" id="togglePasswordIcon"></i> <span id="togglePasswordText">Lihat</span>
                                        </button>
                                    </label>
                                    <div class="relative">
                                        <input type="password" name="mail_password" id="mail_password" value="{{ old('mail_password', $smtp['mail_password']) }}" class="form-control text-xs pl-8 font-mono" placeholder="••••••••••••••••">
                                        <i class="fas fa-key absolute left-2.5 top-3 text-xs text-slate-400"></i>
                                    </div>
                                    <small class="text-[10px] text-slate-400">Biarkan tetap jika tidak ingin mengubah password yang tersimpan.</small>
                                </div>
                            </div>

                            <!-- Sender / From Grid -->
                            <div class="pt-2 border-t border-slate-100 dark:border-slate-800">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                            Email Pengirim (From Address) <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="email" name="mail_from_address" id="mail_from_address" value="{{ old('mail_from_address', $smtp['mail_from_address']) }}" class="form-control text-xs pl-8 font-mono" placeholder="admin@asklin.org" required>
                                            <i class="fas fa-envelope absolute left-2.5 top-3 text-xs text-slate-400"></i>
                                        </div>
                                        <small class="text-[10px] text-slate-400">Bisa menggunakan email domain forwarder Cloudflare</small>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                            Nama Pengirim (From Name) <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input type="text" name="mail_from_name" id="mail_from_name" value="{{ old('mail_from_name', $smtp['mail_from_name']) }}" class="form-control text-xs pl-8" placeholder="Pengurus Pusat ASKLIN" required>
                                            <i class="fas fa-building absolute left-2.5 top-3 text-xs text-slate-400"></i>
                                        </div>
                                        <small class="text-[10px] text-slate-400">Nama resmi organisasi yang tampil di kotak masuk penerima</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-3 flex justify-end">
                                <button type="submit" class="btn btn-primary btn-sm flex items-center gap-2 px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold shadow-sm transition">
                                    <i class="fas fa-save"></i>
                                    <span>Simpan Pengaturan SMTP</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan (5 Col): Live Test Connection & Panduan -->
        <div class="lg:col-span-5 space-y-6">

            <!-- Card 2: Live Test Kirim Email -->
            <div class="card shadow-sm border border-slate-200 dark:border-slate-800">
                <div class="card-header bg-slate-50 dark:bg-slate-800/50 pb-3">
                    <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 flex items-center gap-2">
                        <i class="fas fa-vial text-emerald-600"></i>
                        <span>Uji Coba Pengiriman Email (Live Test)</span>
                    </h4>
                </div>

                <div class="card-body p-5 space-y-4">
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                        Kirim email uji coba instan untuk memverifikasi apakah server SMTP berhasil terhubung, login autentikasi valid, dan dapat meneruskan email keluar.
                    </p>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Alamat Email Penerima Uji Coba <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="email" id="test_recipient_email" value="{{ auth()->user()->email ?? '' }}" class="form-control text-xs pl-8 font-mono" placeholder="email-anda@domain.com" required>
                            <i class="fas fa-paper-plane absolute left-2.5 top-3 text-xs text-slate-400"></i>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" id="btnTestSend" onclick="executeTestSend()" class="btn btn-success btn-sm w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold flex items-center justify-center gap-2 shadow-sm transition">
                            <i class="fas fa-paper-plane" id="iconTestSend"></i>
                            <span id="textTestSend">Kirim Email Uji Coba Sekarang</span>
                        </button>
                    </div>

                    <!-- Live Result Box Container -->
                    <div id="testResultContainer" class="hidden mt-3">
                        <div id="testResultAlert" class="p-4 rounded-xl text-xs space-y-2"></div>
                    </div>
                </div>
            </div>

            <!-- Card 3: Panduan Gmail & Cloudflare Forwarder -->
            <div class="card shadow-sm border border-slate-200 dark:border-slate-800">
                <div class="card-header bg-gradient-to-r from-slate-50 to-emerald-50/40 dark:bg-slate-800/50 pb-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                        <i class="fas fa-lightbulb text-amber-500"></i>
                        <span>Panduan Gmail & Cloudflare Email Forwarder</span>
                    </h4>
                </div>
                <div class="card-body p-4 text-xs text-slate-600 dark:text-slate-400 space-y-3 leading-relaxed">
                    <div class="p-3 bg-red-50 dark:bg-red-950/30 rounded-xl border border-red-200 dark:border-red-900/60 text-red-900 dark:text-red-200">
                        <strong class="font-bold flex items-center gap-1 text-red-700 dark:text-red-300 mb-1">
                            <i class="fab fa-google"></i> Syarat Wajib SMTP Gmail:
                        </strong>
                        <ul class="list-disc pl-4 space-y-1 text-[11px]">
                            <li>Google mewajibkan <strong>Verifikasi 2 Langkah (2-Step Verification)</strong> aktif pada akun Google Anda.</li>
                            <li>Gunakan <strong>Google App Password (Sandi Aplikasi 16 Huruf)</strong> sebagai <em>SMTP Password</em>, <u>bukan</u> password akun login Google Anda.</li>
                            <li>Dapat dibuat di: <a href="https://myaccount.google.com/apppasswords" target="_blank" class="text-red-700 underline font-semibold">myaccount.google.com/apppasswords</a> (Pilih app: <em>Lainnya/Custom</em>, beri nama <em>SIMAK ASKLIN</em>).</li>
                        </ul>
                    </div>

                    <div class="p-3 bg-amber-50 dark:bg-amber-950/30 rounded-xl border border-amber-200 dark:border-amber-900/60 text-amber-900 dark:text-amber-200">
                        <strong class="font-bold flex items-center gap-1 text-amber-700 dark:text-amber-300 mb-1">
                            <i class="fas fa-cloud"></i> Menggunakan Cloudflare Email Forwarder:
                        </strong>
                        <ul class="list-disc pl-4 space-y-1 text-[11px]">
                            <li>Di bagian <strong>From Address</strong>, Anda bebas mengisi alamat email custom domain (misal: <code>sekretariat@asklin.org</code>).</li>
                            <li>Pastikan di akun Gmail pengirim, Anda telah menambahkan alamat email domain tersebut pada menu <em>Settings &gt; Accounts &gt; "Send mail as"</em> agar Gmail mengizinkan pengiriman atas nama email tersebut.</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

@push('js')
<script>
    // Radio button styling toggle
    function updateRadioStyles() {
        $('input[name="mail_encryption"]').each(function() {
            var $label = $(this).closest('label');
            if ($(this).is(':checked')) {
                $label.removeClass('border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300')
                      .addClass('border-emerald-600 bg-emerald-50/50 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-300');
            } else {
                $label.removeClass('border-emerald-600 bg-emerald-50/50 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-300')
                      .addClass('border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300');
            }
        });
    }

    // Toggle show/hide password
    function togglePasswordVisibility() {
        var input = document.getElementById('mail_password');
        var icon = document.getElementById('togglePasswordIcon');
        var text = document.getElementById('togglePasswordText');

        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
            text.textContent = 'Sembunyikan';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye';
            text.textContent = 'Lihat';
        }
    }

    // Preset Cepat (Gmail, Cloudflare, Mailtrap)
    function applyPreset(type) {
        if (type === 'gmail') {
            $('#mail_host').val('smtp.gmail.com');
            $('#mail_port').val(587);
            $('input[name="mail_encryption"][value="tls"]').prop('checked', true);
            updateRadioStyles();
            if (!$('#mail_from_name').val()) {
                $('#mail_from_name').val('Pengurus Pusat ASKLIN');
            }
        } else if (type === 'cloudflare') {
            $('#mail_host').val('smtp.gmail.com');
            $('#mail_port').val(587);
            $('input[name="mail_encryption"][value="tls"]').prop('checked', true);
            updateRadioStyles();
        } else if (type === 'mailtrap') {
            $('#mail_host').val('sandbox.smtp.mailtrap.io');
            $('#mail_port').val(2525);
            $('input[name="mail_encryption"][value="tls"]').prop('checked', true);
            updateRadioStyles();
        }
    }

    // Eksekusi AJAX Live Test Kirim Email
    function executeTestSend() {
        var recipient = $('#test_recipient_email').val();
        if (!recipient) {
            alert('Silakan masukkan alamat email penerima uji coba terlebih dahulu.');
            $('#test_recipient_email').focus();
            return;
        }

        var $btn = $('#btnTestSend');
        var $icon = $('#iconTestSend');
        var $text = $('#textTestSend');
        var $container = $('#testResultContainer');
        var $alert = $('#testResultAlert');

        $btn.prop('disabled', true);
        $icon.removeClass('fa-paper-plane').addClass('fa-spinner fa-spin');
        $text.text('Menghubungkan ke Server SMTP...');

        $container.removeClass('hidden');
        $alert.attr('class', 'p-3.5 rounded-xl text-xs bg-slate-100 text-slate-700 flex items-center gap-2')
              .html('<i class="fas fa-spinner fa-spin text-emerald-600"></i> <span>Sedang menguji koneksi dan mengirim email ke <b>' + recipient + '</b>...</span>');

        // Kirim nilai form yang sedang aktif untuk diuji langsung
        var payload = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            test_email: recipient,
            mail_host: $('#mail_host').val(),
            mail_port: $('#mail_port').val(),
            mail_encryption: $('input[name="mail_encryption"]:checked').val(),
            mail_username: $('#mail_username').val(),
            mail_password: $('#mail_password').val(),
            mail_from_address: $('#mail_from_address').val(),
            mail_from_name: $('#mail_from_name').val()
        };

        $.ajax({
            url: "{{ route('smtp_settings.test_send') }}",
            type: "POST",
            data: payload,
            dataType: 'json',
            success: function(res) {
                $btn.prop('disabled', false);
                $icon.removeClass('fa-spinner fa-spin').addClass('fa-paper-plane');
                $text.text('Kirim Email Uji Coba Sekarang');

                if (res.status === 'success') {
                    $alert.attr('class', 'p-4 rounded-xl text-xs bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-900 dark:text-emerald-200 space-y-1.5')
                          .html(`
                              <div class="font-bold flex items-center gap-2 text-emerald-700 dark:text-emerald-300 text-sm">
                                  <i class="fas fa-check-circle"></i> Koneksi SMTP Berhasil!
                              </div>
                              <p>${res.message}</p>
                              <div class="text-[11px] text-emerald-700 dark:text-emerald-400 pt-1">
                                  Silakan periksa kotak masuk (Inbox / Spam) email <b>${recipient}</b> untuk melihat template email resmi ASKLIN.
                              </div>
                          `);
                } else {
                    $alert.attr('class', 'p-4 rounded-xl text-xs bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-900 dark:text-rose-200 space-y-1.5')
                          .html(`
                              <div class="font-bold flex items-center gap-2 text-rose-700 dark:text-rose-300 text-sm">
                                  <i class="fas fa-times-circle"></i> Pengujian Gagal
                              </div>
                              <p>${res.message || 'Terjadi kesalahan tidak diketahui.'}</p>
                          `);
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false);
                $icon.removeClass('fa-spinner fa-spin').addClass('fa-paper-plane');
                $text.text('Kirim Email Uji Coba Sekarang');

                var errorMsg = 'Terjadi kesalahan saat menghubungi server SMTP.';
                var hint = '';
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) errorMsg = xhr.responseJSON.message;
                    if (xhr.responseJSON.hint) hint = xhr.responseJSON.hint;
                }

                $alert.attr('class', 'p-4 rounded-xl text-xs bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-900 dark:text-rose-200 space-y-2')
                      .html(`
                          <div class="font-bold flex items-center gap-2 text-rose-700 dark:text-rose-300 text-sm">
                              <i class="fas fa-exclamation-triangle"></i> Gagal Terhubung ke SMTP
                          </div>
                          <div class="font-mono text-[11px] bg-rose-100/70 dark:bg-rose-900/40 p-2 rounded-lg break-all">
                              ${errorMsg}
                          </div>
                          ${hint ? `<div class="p-2.5 rounded-lg bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200 text-[11px]">
                              <b><i class="fas fa-info-circle mr-1"></i> ${hint}</b>
                          </div>` : ''}
                      `);
            }
        });
    }
</script>
@endpush

