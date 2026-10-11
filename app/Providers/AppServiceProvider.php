<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap(); // untuk Bootstrap 4
        $this->bootDynamicMailConfig();
    }

    /**
     * Terapkan konfigurasi SMTP dinamis dari database (settings)
     * Menggantikan ketergantungan pada file .env secara global di seluruh aplikasi.
     */
    protected function bootDynamicMailConfig()
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                return;
            }

            $host = setting('mail_host');
            if (!empty($host)) {
                $port = (int) setting('mail_port', 587);
                $encryption = setting('mail_encryption');
                if ($encryption === 'none' || $encryption === 'null' || empty($encryption)) {
                    $encryption = null;
                }

                $username = setting('mail_username');
                $password = setting('mail_password');
                $fromAddress = setting('mail_from_address');
                $fromName = setting('mail_from_name');

                config([
                    'mail.default' => setting('mail_mailer', 'smtp') ?: 'smtp',
                    'mail.mailers.smtp.transport' => 'smtp',
                    'mail.mailers.smtp.host' => $host,
                    'mail.mailers.smtp.port' => $port,
                    'mail.mailers.smtp.encryption' => $encryption,
                    'mail.mailers.smtp.username' => $username,
                    'mail.mailers.smtp.password' => $password,
                ]);

                if (!empty($fromAddress)) {
                    config(['mail.from.address' => $fromAddress]);
                }
                if (!empty($fromName)) {
                    config(['mail.from.name' => $fromName]);
                }
            }
        } catch (\Throwable $e) {
            // Fail safely jika koneksi database belum tersedia saat console command/migration
        }
    }

    
}
