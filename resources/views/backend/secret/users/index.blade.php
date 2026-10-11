@extends('layouts.backend.layout')

@section('title', 'Manajemen Pengguna')
@section('page_title', 'Manajemen Pengguna')

@section('content')
<div class="space-y-6">

    <!-- Responsive Standard Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Manajemen Sistem'],
        ['label' => 'Data Pengguna'],
    ]" />

    <!-- Page Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-base sm:text-lg font-bold tracking-tight text-slate-800 dark:text-white">
                Manajemen Pengguna Sistem
            </h2>
            <p class="text-[11px] sm:text-xs text-slate-400 dark:text-slate-400 mt-1">
                Kelola hak akses pengguna, wilayah penugasan, dan peran dalam organisasi ASKLIN.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i>
                <span>Tambah Pengguna Baru</span>
            </a>
            <a href="{{ route('dashboard.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i>
                <span>Dashboard</span>
            </a>
        </div>
    </div>

    <!-- Filter & Table Card -->
    <div class="card">
        <div class="card-header flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
            <h4 class="text-sm font-bold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                <i class="fas fa-users-cog text-emerald-600 mr-1.5"></i> Daftar Akun Pengguna
            </h4>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full sm:w-auto">
                <div class="w-full sm:w-44">
                    <select id="role" class="form-control w-full text-xs" data-placeholder="Pilih Role...">
                        <option value="">Semua Peran / Role</option>
                        @foreach ($role as $i)
                            <option value="{{ $i->name }}">{{ $i->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-48">
                    <select id="status_relasi" class="form-control w-full text-xs">
                        <option value="">Semua Status Relasi</option>
                        <option value="linked">✓ Berelasi Anggota</option>
                        <option value="unlinked">✗ Tidak Berelasi</option>
                    </select>
                </div>
                <div class="w-full sm:w-60">
                    <div class="relative">
                        <input type="text" class="form-control pl-9 w-full text-xs" placeholder="Cari nama, email, klinik..." id="search">
                        <i class="fas fa-search absolute left-3 top-3 text-xs text-slate-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0 border-t border-slate-100 dark:border-slate-800">
            <div class="table-responsive">
                <table class="table table-striped" id="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Group / Role</th>
                            <th>Relasi Anggota</th>
                            <th>Propinsi</th>
                            <th>Kab/Kota</th>
                            <th>Login Terakhir</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Reset Password (8 Digit) -->
    <div class="modal fade" id="modalResetPassword" tabindex="-1" role="dialog" aria-labelledby="modalResetPasswordLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-2xl rounded-2xl overflow-hidden bg-white dark:bg-slate-900">
                <div class="modal-header bg-gradient-to-r from-emerald-600 to-teal-700 px-5 py-4 border-0 text-white">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white text-base">
                            <i class="fas fa-key"></i>
                        </div>
                        <div>
                            <h5 class="modal-title text-sm font-bold leading-tight" id="modalResetPasswordLabel">Reset Password Pengguna</h5>
                            <p class="text-[11px] text-emerald-100 mt-0.5 font-normal">Generate kata sandi 8 digit & kirim notifikasi via email</p>
                        </div>
                    </div>
                    <button type="button" class="close text-white hover:text-white/80 opacity-90 transition focus:outline-none" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-2xl leading-none">&times;</span>
                    </button>
                </div>

                <form id="formResetPassword" onsubmit="submitResetPassword(event)">
                    <div class="modal-body p-5 space-y-4 text-xs">
                        <input type="hidden" id="reset_user_id">

                        <!-- Info Target Pengguna -->
                        <div class="p-3.5 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-200 dark:border-slate-700/60 space-y-2">
                            <div class="flex justify-between items-start">
                                <span class="text-slate-500 dark:text-slate-400 font-medium">Nama Pengguna:</span>
                                <span class="font-bold text-slate-800 dark:text-slate-100 text-right" id="reset_user_name">-</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span class="text-slate-500 dark:text-slate-400 font-medium">Email Tujuan:</span>
                                <span class="font-mono font-bold text-emerald-600 dark:text-emerald-400 text-right" id="reset_user_email">-</span>
                            </div>
                            <div class="flex justify-between items-start pt-1.5 border-t border-slate-200/80 dark:border-slate-700/60">
                                <span class="text-slate-500 dark:text-slate-400 font-medium">Klinik Terkait:</span>
                                <span class="font-bold text-slate-700 dark:text-slate-300 text-right" id="reset_user_klinik">
                                    <span class="italic text-slate-400 font-normal">Tidak Terhubung</span>
                                </span>
                            </div>
                        </div>

                        <!-- Input Password 8 Digit Acak -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="font-bold text-slate-700 dark:text-slate-200 m-0">Password Baru (8 Digit Acak)</label>
                                <button type="button" onclick="generateRandom8Password()" class="btn btn-xs btn-outline-success text-[11px] font-semibold flex items-center gap-1 px-2 py-0.5">
                                    <i class="fas fa-sync-alt text-[10px]"></i> Acak Ulang
                                </button>
                            </div>
                            <div class="relative">
                                <input type="text" id="reset_password_val" class="form-control font-mono font-extrabold text-lg tracking-widest text-center text-emerald-800 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 border-emerald-300 dark:border-emerald-700 rounded-xl py-2.5" maxlength="8" required>
                            </div>
                            <p class="text-[10px] text-slate-400 dark:text-slate-400 mt-1.5 flex items-center gap-1">
                                <i class="fas fa-shield-alt text-emerald-500"></i> Password 8 digit alfanumerik aman otomatis di-generate.
                            </p>
                        </div>

                        <!-- Alert Notice Email -->
                        <div class="p-3 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60 rounded-xl flex items-start gap-2.5 text-amber-800 dark:text-amber-300">
                            <i class="fas fa-paper-plane text-amber-500 mt-0.5 text-xs"></i>
                            <div class="text-[11px] leading-relaxed">
                                Password baru di atas akan <strong>otomatis terkirim via email resmi ASKLIN</strong> ke pengguna terkait saat tombol ditekan.
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-slate-50 dark:bg-slate-800/40 px-5 py-3 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-2">
                        <button type="button" class="btn btn-secondary btn-sm rounded-lg" data-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" id="btnSubmitReset" class="btn btn-success btn-sm rounded-lg bg-emerald-600 hover:bg-emerald-700 border-0 flex items-center gap-1.5 shadow-sm text-white font-semibold">
                            <i class="fas fa-paper-plane"></i>
                            <span id="btnSubmitResetText">Reset & Kirim Email</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/backend/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('assets/backend/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">
@endpush

@push('js')
    <script src="{{ asset('assets/backend/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/backend/modules/datatables/Select-1.2.4/js/dataTables.select.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function() {
            var table = $('#table').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                    url: "{{ route('users.list') }}",
                    data: function(d) {
                        d.search = $('#search').val();
                        d.role_names = $('#role').val();
                        d.status_relasi = $('#status_relasi').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email',
                        render: function(data, type, row) {
                            var safeEmail = $('<div>').text(data || '').html();
                            var html = '<div class="font-bold text-slate-800 dark:text-slate-100 text-xs">' + safeEmail + '</div>';
                            if (row.relasi_klinik) {
                                var safeKlinik = $('<div>').text(row.relasi_klinik).html();
                                html += '<div class="mt-1 inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 shadow-xs" title="Klinik: ' + safeKlinik + '">' +
                                    '<i class="fas fa-clinic-medical text-[10px] text-emerald-600"></i>' +
                                    '<span>' + safeKlinik + '</span>' +
                                    '</div>';
                            } else {
                                html += '<div class="mt-0.5 text-[10px] text-slate-400 italic flex items-center gap-1">' +
                                    '<i class="fas fa-info-circle text-[9px]"></i>' +
                                    '<span>Belum terhubung ke klinik</span>' +
                                    '</div>';
                            }
                            return html;
                        }
                    },
                    {
                        data: 'role_names',
                        name: 'roles'
                    },
                    {
                        data: 'status_relasi',
                        name: 'status_relasi',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'provinsi',
                        name: 'provinsi'
                    },
                    {
                        data: 'kota',
                        name: 'kota'
                    },
                    {
                        data: 'last_login_at',
                        "render": function(data, type, row, meta) {
                            var ip = row.last_login_ip || '-';
                            var tgl = row.last_login_at || 'Belum pernah';
                            return '<span class="text-xs text-slate-600 dark:text-slate-400">' + tgl + '</span><br/><small class="text-slate-400 font-mono text-[10px]">' + ip + '</small>';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            var searchTimer = null;
            $("#search").on('keyup input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(function() {
                    table.draw();
                }, 300);
            });

            $('#role, #status_relasi').on('change', function(e) {
                table.draw();
            });
        });

        function deleteu(id) {
            if (confirm("Hapus pengguna ini?") == true) {
                var id = id;
                $.ajax({
                    type: "POST",
                    url: "{{ route('users.delete') }}",
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(res) {
                        $('#table').dataTable().fnDraw(false);
                    }
                });
            }
        }

        // Helper Generate 8 Digit Password Acak
        function generateRandom8Password() {
            var chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz';
            var result = '';
            for (var i = 0; i < 8; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            $('#reset_password_val').val(result);
            return result;
        }

        // Buka Modal Reset Password
        function openResetPasswordModal(id, name, email, klinik) {
            $('#reset_user_id').val(id);
            $('#reset_user_name').text(name || '-');
            $('#reset_user_email').text(email || '-');
            if (klinik && klinik.trim() !== '') {
                $('#reset_user_klinik').html('<span class="text-emerald-700 dark:text-emerald-400 font-bold flex items-center justify-end gap-1"><i class="fas fa-clinic-medical text-[10px]"></i>' + $('<div>').text(klinik).html() + '</span>');
            } else {
                $('#reset_user_klinik').html('<span class="italic text-slate-400 font-normal">Tidak Terhubung</span>');
            }

            generateRandom8Password();
            $('#modalResetPassword').modal('show');
        }

        // Eksekusi Submit Reset Password & Kirim Email
        function submitResetPassword(e) {
            e.preventDefault();
            var id = $('#reset_user_id').val();
            var password = $('#reset_password_val').val();
            var email = $('#reset_user_email').text();

            if (!password || password.length !== 8) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Password Tidak Valid',
                    text: 'Password harus tepat 8 karakter/digit.',
                    confirmButtonColor: '#059669'
                });
                return;
            }

            var $btn = $('#btnSubmitReset');
            var $btnText = $('#btnSubmitResetText');
            $btn.prop('disabled', true);
            $btnText.text('Mereset & Mengirim...');

            $.ajax({
                url: "{{ url('backend/secret/users') }}/" + id + "/reset-password",
                type: "POST",
                data: {
                    password: password
                },
                dataType: 'json',
                success: function(res) {
                    $btn.prop('disabled', false);
                    $btnText.text('Reset & Kirim Email');
                    $('#modalResetPassword').modal('hide');

                    if (res.status === 'success') {
                        var emailNotice = res.email_sent 
                            ? '<p class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold mt-2.5 flex items-center justify-center gap-1.5"><i class="fas fa-check-circle"></i> Notifikasi & password baru telah terkirim ke <b>' + $('<div>').text(res.email).html() + '</b></p>'
                            : '<p class="text-xs text-amber-600 dark:text-amber-400 font-semibold mt-2.5 flex items-center justify-center gap-1.5"><i class="fas fa-exclamation-triangle"></i> Password tersimpan, namun email gagal terkirim (' + (res.email_error || 'cek mail server') + ')</p>';

                        Swal.fire({
                            icon: res.email_sent ? 'success' : 'warning',
                            title: 'Password Berhasil Di-Reset!',
                            html: `
                                <div class="text-left text-xs space-y-3 mt-2">
                                    <p class="text-slate-600">Password baru 8 digit untuk <b>${$('<div>').text(res.user.name).html()}</b> (${$('<div>').text(res.email).html()}):</p>
                                    <div class="flex items-center justify-between p-3 bg-slate-100 rounded-xl border border-slate-200">
                                        <span class="font-mono text-xl font-black tracking-widest text-emerald-700" id="swalPasswordText">${res.password}</span>
                                        <button type="button" class="btn btn-sm btn-outline-success text-xs font-semibold" onclick="copyPasswordToClipboard('${res.password}')">
                                            <i class="fas fa-copy mr-1"></i> Salin
                                        </button>
                                    </div>
                                    ${emailNotice}
                                </div>
                            `,
                            confirmButtonText: 'Tutup',
                            confirmButtonColor: '#059669'
                        });

                        $('#table').DataTable().ajax.reload(null, false);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: res.message || 'Terjadi kesalahan sistem.',
                            confirmButtonColor: '#dc2626'
                        });
                    }
                },
                error: function(xhr) {
                    $btn.prop('disabled', false);
                    $btnText.text('Reset & Kirim Email');
                    var errorMsg = 'Terjadi kendala pada server.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Reset Password',
                        text: errorMsg,
                        confirmButtonColor: '#dc2626'
                    });
                }
            });
        }

        // Copy Password to Clipboard helper
        function copyPasswordToClipboard(text) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(function() {
                    if (window.iziToast) {
                        iziToast.success({
                            title: 'Berhasil',
                            message: 'Password ' + text + ' berhasil disalin ke clipboard!',
                            position: 'topRight'
                        });
                    } else {
                        alert('Password ' + text + ' disalin!');
                    }
                });
            } else {
                var textArea = document.createElement("textarea");
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                if (window.iziToast) {
                    iziToast.success({
                        title: 'Berhasil',
                        message: 'Password ' + text + ' berhasil disalin ke clipboard!',
                        position: 'topRight'
                    });
                } else {
                    alert('Password ' + text + ' disalin!');
                }
            }
        }
    </script>
@endpush
