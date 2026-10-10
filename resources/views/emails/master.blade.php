<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Pemberitahuan Resmi ASKLIN' }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #334155;
            -webkit-font-smoothing: antialiased;
        }
        table {
            border-collapse: collapse;
        }
        .wrapper {
            width: 100%;
            background-color: #f1f5f9;
            padding: 30px 15px;
        }
        .main-card {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .header-banner {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            padding: 28px 30px;
            text-align: center;
        }
        .header-logo {
            max-height: 52px;
            width: auto;
            margin-bottom: 12px;
            background: #ffffff;
            padding: 6px 14px;
            border-radius: 10px;
            display: inline-block;
        }
        .header-title {
            color: #ffffff;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin: 0;
            text-transform: uppercase;
        }
        .header-subtitle {
            color: #d1fae5;
            font-size: 12px;
            margin-top: 4px;
            margin-bottom: 0;
        }
        .content-body {
            padding: 32px 30px;
            font-size: 14px;
            line-height: 1.6;
            color: #334155;
        }
        .greeting {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
        }
        .badge-pill {
            display: inline-block;
            padding: 4px 12px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-warning {
            background-color: #fef3c7;
            color: #b45309;
        }
        .badge-success {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-info {
            background-color: #e0f2fe;
            color: #0369a1;
        }
        .info-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            margin: 20px 0;
        }
        .btn-cta {
            display: inline-block;
            background-color: #059669;
            color: #ffffff !important;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
            margin: 15px 0;
        }
        .btn-cta:hover {
            background-color: #047857;
        }
        .footer {
            background-color: #f8fafc;
            padding: 24px 30px;
            text-align: center;
            border-top: 1px solid #f1f5f9;
            font-size: 11px;
            color: #64748b;
            line-height: 1.5;
        }
        .footer a {
            color: #059669;
            text-decoration: none;
        }
        .token-warning {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 10px 14px;
            font-size: 11px;
            color: #92400e;
            border-radius: 6px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main-card">
            <!-- Header Banner -->
            <div class="header-banner">
                <div class="header-logo">
                    <img src="{{ url('assets/images/logo_asklin.png') }}" alt="Logo ASKLIN" style="height: 38px; vertical-align: middle;">
                </div>
                <h1 class="header-title">ASOSIASI KLINIK INDONESIA</h1>
                <p class="header-subtitle">Pengurus Pusat ASKLIN &bull; Sistem Layanan Digital Terpadu</p>
            </div>

            <!-- Content Area -->
            <div class="content-body">
                @yield('content')
            </div>

            <!-- Footer -->
            <div class="footer">
                <p style="margin: 0 0 6px 0; font-weight: 700; color: #334155;">PENGURUS PUSAT ASOSIASI KLINIK INDONESIA (ASKLIN)</p>
                <p style="margin: 0 0 8px 0;">Email Resmi: <a href="mailto:pp_asklin@asklin.org">pp_asklin@asklin.org</a> &bull; Portal: <a href="{{ url('/') }}" target="_blank">{{ url('/') }}</a></p>
                <p style="margin: 0; font-size: 10px; color: #94a3b8;">
                    Email ini dikirim secara otomatis oleh Sistem Organisasi Digital ASKLIN. Mohon tidak membalas langsung ke alamat ini.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
