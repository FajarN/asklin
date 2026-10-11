<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Anggota;
use Carbon\Carbon;

class SyncUsersAnggotaCommand extends Command
{
    /**
     * The name and signature of the console command.
     * php artisan asklin:sync-users --cleanup --all-orphan
     *
     * @var string
     */
    protected $signature = 'asklin:sync-users
                            {--filter=all : Filter data: all, linked, unlinked, clinic-orphan}
                            {--sync : Jalankan sinkronisasi otomatis id_user anggota berdasarkan kecocokan email}
                            {--cleanup : Jalankan pembersihan aman akun role Klinik yang tidak berelasi}
                            {--never-logged-in : (Opsi cleanup) Hanya bersihkan akun yang belum pernah login}
                            {--all-orphan : (Opsi cleanup) Bersihkan seluruh akun role Klinik yang tidak berelasi}
                            {--dry-run : Simulasi tindakan tanpa mengubah atau menghapus data di database}
                            {--force : Lewati konfirmasi interaktif di terminal}
                            {--export : Ekspor daftar user yang tidak berelasi ke file CSV}
                            {--limit=25 : Batas baris tabel sampel di terminal (default 25)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit, filter, sync, and safe-cleanup relationship between users and anggota tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->newLine();
        $this->info("=========================================================================");
        $this->info("   ASKLIN - PUSAT AUDIT, SINKRONISASI & PEMBERSIHAN PENGGUNA (USERS)   ");
        $this->info("=========================================================================");
        $this->newLine();

        $totalUsers = DB::table('users')->count();
        $totalAnggota = DB::table('anggota')->count();

        // 1. Hitung user yang berelasi dan tidak berelasi
        $linkedUsersCount = DB::table('users')
            ->join('anggota', 'anggota.id_user', '=', 'users.id')
            ->count();

        $unlinkedUsersCount = DB::table('users')
            ->leftJoin('anggota', 'anggota.id_user', '=', 'users.id')
            ->whereNull('anggota.id')
            ->count();

        // Breakdown role untuk unlinked users
        $unlinkedRoleBreakdown = DB::table('users')
            ->leftJoin('anggota', 'anggota.id_user', '=', 'users.id')
            ->leftJoin('model_has_roles', function ($j) {
                $j->on('users.id', '=', 'model_has_roles.model_id')
                    ->where('model_has_roles.model_type', '=', 'App\\Models\\User');
            })
            ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->whereNull('anggota.id')
            ->select(DB::raw('COALESCE(roles.name, "Tanpa Role") as role_name'), DB::raw('count(*) as total'))
            ->groupBy('roles.name')
            ->orderByDesc('total')
            ->get();

        // Cek potensi sinkronisasi via email matching untuk user role Klinik
        $potentialSync = DB::table('users')
            ->leftJoin('anggota', 'anggota.id_user', '=', 'users.id')
            ->join('model_has_roles', function ($j) {
                $j->on('users.id', '=', 'model_has_roles.model_id')
                    ->where('model_has_roles.model_type', '=', 'App\\Models\\User');
            })
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->join('anggota as a2', 'a2.email', '=', 'users.email')
            ->where('roles.name', 'Klinik')
            ->whereNull('anggota.id')
            ->select(
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                'a2.id as anggota_id',
                'a2.nama_klinik',
                'a2.id_user as id_user_lama'
            )
            ->get();

        // Tampilkan Ringkasan
        $this->table(
            ['Metrik Data', 'Jumlah'],
            [
                ['Total Record di Tabel Users', number_format($totalUsers, 0, ',', '.')],
                ['Total Record di Tabel Anggota', number_format($totalAnggota, 0, ',', '.')],
                ['User BERELASI dengan Anggota (anggota.id_user = users.id)', number_format($linkedUsersCount, 0, ',', '.')],
                ['User TIDAK BERELASI dengan Anggota (Unlinked)', number_format($unlinkedUsersCount, 0, ',', '.')],
                [' - Akun Operasional Non-Klinik (Admin Cabang/Daerah, Ketum, Sekjen, Konas)', number_format($unlinkedUsersCount - ($unlinkedRoleBreakdown->firstWhere('role_name', 'Klinik')->total ?? 0), 0, ',', '.')],
                [' - Akun Role Klinik Tanpa Anggota (Orphan / Terbengkalai)', number_format($unlinkedRoleBreakdown->firstWhere('role_name', 'Klinik')->total ?? 0, 0, ',', '.')],
                [' - Akun Klinik yang BISA DISINKRONKAN via Email Matching', number_format($potentialSync->count(), 0, ',', '.')],
            ]
        );

        $this->newLine();
        $this->line("<comment>Rincian Role Pengguna yang Tidak Berelasi:</comment>");
        $roleRows = [];
        foreach ($unlinkedRoleBreakdown as $rb) {
            $catatan = ($rb->role_name === 'Klinik')
                ? 'Kandidat audit / sinkronisasi / pembersihan'
                : 'AMAL: Akun pengurus/admin sistem ASKLIN (DILINDUNGI DARI DELETE)';
            $roleRows[] = [$rb->role_name, $rb->total, $catatan];
        }
        $this->table(['Nama Role', 'Jumlah Akun', 'Status Perlindungan'], $roleRows);

        // Jika opsi --cleanup diaktifkan
        if ($this->option('cleanup')) {
            $this->handleCleanup();
            return 0;
        }

        // Jika opsi --sync diaktifkan
        if ($this->option('sync')) {
            $this->handleSync($potentialSync);
            return 0;
        }

        // Jika opsi --export diaktifkan
        if ($this->option('export')) {
            $this->handleExport();
        }

        // Tampilkan daftar data sesuai opsi filter
        $filter = strtolower($this->option('filter') ?? 'all');
        $limit = (int) $this->option('limit');
        if ($limit <= 0) $limit = 25;

        if ($filter === 'linked') {
            $this->showLinkedUsers($limit);
        } elseif ($filter === 'unlinked') {
            $this->showUnlinkedUsers($limit, false);
        } elseif ($filter === 'clinic-orphan') {
            $this->showUnlinkedUsers($limit, true);
        } else {
            $this->info("Petunjuk penggunaan opsi command:");
            $this->line("  1. <info>php artisan asklin:sync-users --filter=linked</info>             : Lihat sampel user yang berelasi");
            $this->line("  2. <info>php artisan asklin:sync-users --filter=unlinked</info>           : Lihat sampel user yang tidak berelasi");
            $this->line("  3. <info>php artisan asklin:sync-users --filter=clinic-orphan</info>      : Lihat user role Klinik yang terbengkalai");
            $this->line("  4. <info>php artisan asklin:sync-users --sync</info>                      : Hubungkan 37 user yang cocok emailnya");
            $this->line("  5. <info>php artisan asklin:sync-users --cleanup --never-logged-in</info> : Hapus aman 518 akun klinik yang BELUM pernah login");
            $this->line("  6. <info>php artisan asklin:sync-users --cleanup --all-orphan</info>      : Hapus aman seluruh 1.667 akun klinik terbengkalai");
            $this->line("  7. <info>php artisan asklin:sync-users --cleanup --dry-run</info>         : Simulasi pembersihan tanpa menghapus apa pun");
            $this->line("  8. <info>php artisan asklin:sync-users --export</info>                    : Ekspor seluruh unlinked users ke CSV");
        }

        $this->newLine();
        return 0;
    }

    /**
     * Eksekusi pembersihan aman (Safe Cleanup)
     */
    protected function handleCleanup()
    {
        $this->newLine();
        $this->warn("=========================================================================");
        $this->warn("                   PROSES PEMBERSIHAN AMAN (SAFE CLEANUP)                ");
        $this->warn("=========================================================================");

        $neverLoggedInOnly = $this->option('never-logged-in');
        $allOrphan = $this->option('all-orphan');
        $isDryRun = $this->option('dry-run');

        if (!$neverLoggedInOnly && !$allOrphan) {
            $choice = $this->choice(
                'Pilih kriteria akun klinik terbengkalai yang ingin dibersihkan:',
                [
                    '1' => 'Hanya akun yang BELUM PERNAH login sama sekali (Sangat Aman / 518 akun)',
                    '2' => 'Semua akun role Klinik yang tidak berelasi & tidak cocok email (1.667 akun)',
                    '3' => 'Batal'
                ],
                '1'
            );

            if ($choice === '1') {
                $neverLoggedInOnly = true;
            } elseif ($choice === '2') {
                $allOrphan = true;
            } else {
                $this->line("Operasi pembersihan dibatalkan.");
                return;
            }
        }

        // Filter kandidat yang 100% aman untuk dihapus:
        // 1. Role Klinik atau tanpa role
        // 2. TIDAK ADA di anggota.id_user
        // 3. Email TIDAK ADA di tabel anggota
        $query = DB::table('users')
            ->leftJoin('anggota', 'anggota.id_user', '=', 'users.id')
            ->join('model_has_roles', function ($j) {
                $j->on('users.id', '=', 'model_has_roles.model_id')
                    ->where('model_has_roles.model_type', '=', 'App\\Models\\User');
            })
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('roles.name', 'Klinik')
            ->whereNull('anggota.id')
            ->whereNotIn('users.email', function ($q) {
                $q->select('email')->from('anggota')->whereNotNull('email')->where('email', '!=', '');
            })
            ->select('users.id', 'users.name', 'users.email', 'users.last_login_at', 'users.created_at');

        if ($neverLoggedInOnly) {
            $query->where(function ($q) {
                $q->whereNull('users.last_login_at')
                    ->orWhere('users.last_login_at', '0000-00-00 00:00:00')
                    ->orWhere('users.last_login_at', '');
            });
        }

        $candidates = $query->get();

        if ($candidates->isEmpty()) {
            $this->info("Tidak ada akun yang memenuhi kriteria untuk dibersihkan.");
            return;
        }

        $totalCount = $candidates->count();
        $this->line("Ditemukan <comment>{$totalCount}</comment> akun yang siap dibersihkan.");

        // Tampilkan 10 sampel
        $sampleRows = [];
        foreach ($candidates->take(10) as $c) {
            $sampleRows[] = [
                $c->id,
                $c->name,
                $c->email,
                $c->last_login_at ?: 'Belum pernah',
                $c->created_at ?: '-'
            ];
        }
        $this->table(['ID User', 'Username', 'Email', 'Login Terakhir', 'Tgl Dibuat'], $sampleRows);

        if ($isDryRun) {
            $this->info("\n[SIMULASI DRY-RUN] Selesai. Sebanyak {$totalCount} data akun TIDAK dihapus karena dalam mode dry-run.");
            return;
        }

        // Konfirmasi 1
        if (!$this->option('force')) {
            $confirm1 = $this->confirm("Apakah Anda yakin ingin memproses pembersihan {$totalCount} akun ini? (Backup otomatis akan dibuat)", false);
            if (!$confirm1) {
                $this->line("Operasi dibatalkan.");
                return;
            }

            $confirm2 = $this->ask("Ketik 'HAPUS' (huruf besar) untuk melanjutkan proses penghapusan permanen:");
            if ($confirm2 !== 'HAPUS') {
                $this->line("Konfirmasi tidak cocok. Operasi dibatalkan dengan aman.");
                return;
            }
        }

        // 1. AUTO-BACKUP SEBELUM DELETE
        $backupDir = storage_path('app/backups');
        if (!file_exists($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $backupFile = $backupDir . '/deleted_users_backup_' . date('Ymd_His') . '.csv';
        $fp = fopen($backupFile, 'w');
        fputcsv($fp, ['ID User', 'Username', 'Email', 'Login Terakhir', 'Tgl Dibuat', 'Tgl Backup']);

        $candidateIds = [];
        foreach ($candidates as $c) {
            $candidateIds[] = $c->id;
            fputcsv($fp, [
                $c->id,
                $c->name,
                $c->email,
                $c->last_login_at ?: 'Belum pernah',
                $c->created_at ?: '-',
                Carbon::now()->toDateTimeString()
            ]);
        }
        fclose($fp);

        $this->info("✓ Backup otomatis berhasil disimpan di: {$backupFile}");

        // 2. EKSEKUSI PENGHAPUSAN DALAM DATABASE TRANSACTION
        $this->line("<comment>Menghapus data akun dan role...</comment>");
        $deleted = 0;

        DB::beginTransaction();
        try {
            // Hapus relasi role di model_has_roles dalam chunk
            foreach (array_chunk($candidateIds, 200) as $chunk) {
                DB::table('model_has_roles')
                    ->whereIn('model_id', $chunk)
                    ->where('model_type', 'App\\Models\\User')
                    ->delete();

                $affected = DB::table('users')->whereIn('id', $chunk)->delete();
                $deleted += $affected;
            }

            DB::commit();
            $this->newLine();
            $this->info("=========================================================================");
            $this->info("   BERHASIL! {$deleted} AKUN KLINIK TERBENGKALAI TELAH DIBERSIHKAN!   ");
            $this->info("=========================================================================");
            $this->line("File cadangan pemulihan tersimpan di:");
            $this->line("<comment>{$backupFile}</comment>");
            $this->newLine();

            $remainingUsers = DB::table('users')->count();
            $this->line("Total pengguna sekarang di tabel users: <info>" . number_format($remainingUsers, 0, ',', '.') . "</info>");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Gagal menghapus data: " . $e->getMessage());
        }
    }

    /**
     * Tampilkan sampel user yang berelasi
     */
    protected function showLinkedUsers(int $limit)
    {
        $this->newLine();
        $this->info("Menampilkan {$limit} sampel User yang BERELASI dengan Anggota:");

        $data = DB::table('users')
            ->join('anggota', 'anggota.id_user', '=', 'users.id')
            ->select(
                'users.id as user_id',
                'users.name as username',
                'users.email',
                'anggota.id as anggota_id',
                'anggota.no_anggota',
                'anggota.nama_klinik',
                'users.last_login_at'
            )
            ->limit($limit)
            ->get();

        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                $item->user_id,
                $item->username,
                $item->email,
                $item->no_anggota ?: '-',
                $item->nama_klinik,
                $item->last_login_at ?: 'Belum pernah'
            ];
        }

        $this->table(['ID User', 'Username', 'Email', 'No Anggota', 'Nama Klinik', 'Login Terakhir'], $rows);
    }

    /**
     * Tampilkan sampel user yang tidak berelasi
     */
    protected function showUnlinkedUsers(int $limit, bool $clinicOnly = false)
    {
        $this->newLine();
        $title = $clinicOnly
            ? "Menampilkan {$limit} sampel User Role 'Klinik' yang TIDAK BERELASI:"
            : "Menampilkan {$limit} sampel Seluruh User yang TIDAK BERELASI:";
        $this->info($title);

        $query = DB::table('users')
            ->leftJoin('anggota', 'anggota.id_user', '=', 'users.id')
            ->leftJoin('model_has_roles', function ($j) {
                $j->on('users.id', '=', 'model_has_roles.model_id')
                    ->where('model_has_roles.model_type', '=', 'App\\Models\\User');
            })
            ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->whereNull('anggota.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('COALESCE(roles.name, "Tanpa Role") as role_name'),
                'users.created_at',
                'users.last_login_at'
            );

        if ($clinicOnly) {
            $query->where('roles.name', 'Klinik');
        }

        $data = $query->limit($limit)->get();

        $rows = [];
        foreach ($data as $item) {
            $rows[] = [
                $item->id,
                $item->name,
                $item->email,
                $item->role_name,
                $item->created_at ?: '-',
                $item->last_login_at ?: 'Belum pernah'
            ];
        }

        $this->table(['ID User', 'Username', 'Email', 'Role', 'Tgl Dibuat', 'Login Terakhir'], $rows);
    }

    /**
     * Eksekusi sinkronisasi email matching
     */
    protected function handleSync($potentialSync)
    {
        $this->newLine();
        $this->warn("=== PROSES SINKRONISASI RELASI USER -> ANGGOTA ===");

        if ($potentialSync->isEmpty()) {
            $this->info("Tidak ada user Klinik yang perlu disinkronkan via email matching.");
            return;
        }

        $this->line("Ditemukan <comment>{$potentialSync->count()}</comment> user Klinik yang emailnya cocok dengan klinik di tabel anggota.");

        $sampleTable = [];
        foreach ($potentialSync->take(10) as $s) {
            $sampleTable[] = [
                $s->user_id,
                $s->user_email,
                $s->anggota_id,
                $s->nama_klinik,
                $s->id_user_lama ?: '-'
            ];
        }
        $this->table(['ID User Baru', 'Email', 'ID Anggota', 'Nama Klinik', 'ID User Lama'], $sampleTable);

        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->info("[SIMULASI DRY-RUN] Tidak ada data yang diubah di database.");
            return;
        }

        if (!$this->option('force') && !$this->confirm('Apakah Anda yakin ingin menghubungkan ' . $potentialSync->count() . ' user tersebut ke tabel anggota?', false)) {
            $this->line("Sinkronisasi dibatalkan.");
            return;
        }

        $success = 0;
        $failed = 0;

        DB::beginTransaction();
        try {
            foreach ($potentialSync as $item) {
                $updated = DB::table('anggota')
                    ->where('id', $item->anggota_id)
                    ->update([
                        'id_user' => $item->user_id,
                        'updated_at' => Carbon::now()
                    ]);

                if ($updated) {
                    $success++;
                } else {
                    $failed++;
                }
            }

            DB::commit();
            $this->info("Berhasil mensinkronkan {$success} user ke tabel anggota!");
            if ($failed > 0) {
                $this->warn("Gagal memperbarui {$failed} data.");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Terjadi kesalahan saat sinkronisasi: " . $e->getMessage());
        }
    }

    /**
     * Ekspor daftar unlinked users ke CSV
     */
    protected function handleExport()
    {
        $this->newLine();
        $this->line("<comment>Mengekspor daftar user yang tidak berelasi ke CSV...</comment>");

        $dir = storage_path('app/exports');
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $fileName = 'unlinked_users_' . date('Ymd_His') . '.csv';
        $filePath = $dir . '/' . $fileName;

        $unlinkedUsers = DB::table('users')
            ->leftJoin('anggota', 'anggota.id_user', '=', 'users.id')
            ->leftJoin('model_has_roles', function ($j) {
                $j->on('users.id', '=', 'model_has_roles.model_id')
                    ->where('model_has_roles.model_type', '=', 'App\\Models\\User');
            })
            ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->whereNull('anggota.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('COALESCE(roles.name, "Tanpa Role") as role_name'),
                'users.created_at',
                'users.last_login_at',
                'users.last_login_ip'
            )
            ->get();

        $fp = fopen($filePath, 'w');
        fputcsv($fp, ['ID User', 'Nama / Username', 'Email', 'Role', 'Tanggal Dibuat', 'Login Terakhir', 'IP Login Terakhir']);

        foreach ($unlinkedUsers as $u) {
            fputcsv($fp, [
                $u->id,
                $u->name,
                $u->email,
                $u->role_name,
                $u->created_at ?: '-',
                $u->last_login_at ?: 'Belum pernah',
                $u->last_login_ip ?: '-'
            ]);
        }

        fclose($fp);

        $this->info("File CSV berhasil disimpan di: " . $filePath);
        $this->info("Total data diekspor: " . number_format($unlinkedUsers->count(), 0, ',', '.') . " baris.");
    }
}
