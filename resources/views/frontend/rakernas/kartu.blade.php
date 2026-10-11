<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Bukti Registrasi - {{ $peserta->no_registrasi }} - ASKLIN</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #047857;
            --primary-dark: #064e3b;
            --primary-light: #ecfdf5;
            --accent: #0284c7;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f1f5f9;
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .no-print-bar {
            width: 100%;
            max-width: 780px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-print {
            background: var(--primary);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(4, 120, 87, 0.25);
        }

        .btn-print:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-back {
            background: #ffffff;
            color: var(--text-dark);
            border: 1px solid var(--border);
        }

        .btn-back:hover {
            background: #f8fafc;
        }

        .pass-card {
            width: 100%;
            max-width: 780px;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.08), 0 0 1px 1px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
            position: relative;
        }

        .card-header {
            background: linear-gradient(135deg, #064e3b 0%, #047857 55%, #0d9488 100%);
            color: #ffffff;
            padding: 28px 32px;
            position: relative;
            overflow: hidden;
        }

        .card-header::after {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
        }

        .header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .brand-block {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-logo {
            width: 48px;
            height: 48px;
            object-fit: contain;
            background: #ffffff;
            padding: 6px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        .brand-text h2 {
            font-size: 17px;
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .brand-text p {
            font-size: 12px;
            color: #a7f3d0;
            font-weight: 500;
        }

        .badge-type {
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(8px);
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #ffffff;
        }

        .event-banner h1 {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 6px;
            letter-spacing: -0.01em;
            line-height: 1.3;
        }

        .event-banner p {
            font-size: 13px;
            color: #e2e8f0;
            line-height: 1.5;
            max-width: 600px;
        }

        .card-body {
            padding: 32px;
            display: grid;
            grid-template-columns: 1fr 220px;
            gap: 32px;
        }

        @media (max-width: 680px) {
            .card-body {
                grid-template-columns: 1fr;
            }
        }

        .data-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px 24px;
        }

        @media (max-width: 500px) {
            .data-grid {
                grid-template-columns: 1fr;
            }
        }

        .data-item.full {
            grid-column: span 2;
        }

        @media (max-width: 500px) {
            .data-item.full {
                grid-column: span 1;
            }
        }

        .data-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            font-weight: 700;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .data-value {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-dark);
            word-break: break-word;
        }

        .data-value-highlight {
            font-size: 18px;
            font-weight: 800;
            color: #047857;
            background: #ecfdf5;
            padding: 4px 10px;
            border-radius: 8px;
            display: inline-block;
            border: 1px solid #a7f3d0;
        }

        .data-value-clinic {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            border-left: 3px solid #047857;
            padding-left: 10px;
        }

        .qr-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 16px;
            padding: 20px;
        }

        .qr-box {
            width: 140px;
            height: 140px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            padding: 8px;
        }

        .qr-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .reg-number {
            font-family: 'JetBrains Mono', monospace;
            font-size: 14px;
            font-weight: 700;
            color: #047857;
            background: #ecfdf5;
            padding: 4px 10px;
            border-radius: 6px;
            border: 1px solid #a7f3d0;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }

        .qr-caption {
            font-size: 11px;
            color: var(--text-muted);
            line-height: 1.4;
        }

        .card-footer {
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            padding: 18px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            color: var(--text-muted);
            flex-wrap: gap;
        }

        .notes-list {
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px dashed #e2e8f0;
            font-size: 12px;
            color: #64748b;
            line-height: 1.6;
        }

        .notes-list strong {
            color: #0f172a;
        }

        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .no-print-bar {
                display: none !important;
            }
            .pass-card {
                box-shadow: none;
                border: 1px solid #cbd5e1;
                max-width: 100%;
                width: 100%;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>

    <div class="no-print-bar">
        <a href="{{ route('rakernas') }}" class="btn-action btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Website Rakernas
        </a>
        <button onclick="window.print()" class="btn-action btn-print">
            <i class="fas fa-print"></i> Cetak / Simpan PDF
        </button>
    </div>

    <div class="pass-card">
        <!-- Header -->
        <div class="card-header">
            <div class="header-top">
                <div class="brand-block">
                    <img src="{{ asset('assets/images/logo_asklin.png') }}" alt="ASKLIN Logo" class="brand-logo" onerror="this.src='/storage/{{ setting('logo') }}'">
                    <div class="brand-text">
                        <h2>ASOSIASI KLINIK INDONESIA</h2>
                        <p>PENGURUS PUSAT ASKLIN</p>
                    </div>
                </div>
                <div class="badge-type">
                    <i class="fas fa-id-badge" style="margin-right: 4px;"></i> BUKTI REGISTRASI
                </div>
            </div>

            <div class="event-banner">
                <h1>{{ $periode->nama ?? 'Rapat Kerja Nasional ASKLIN' }}</h1>
                <p>
                    @if($periode->tema)
                        "{{ $periode->tema }}"
                    @else
                        Forum Konsolidasi &amp; Koordinasi Pengurus Pusat, Daerah, Cabang, dan Anggota Klinik Se-Indonesia
                    @endif
                </p>
            </div>
        </div>

        <!-- Body -->
        <div class="card-body">
            <div class="data-block">
                <div class="data-grid">
                    <div class="data-item full">
                        <div class="data-label"><i class="fas fa-clinic-medical text-emerald-600"></i> NAMA KLINIK (DATA UTAMA)</div>
                        <div class="data-value data-value-clinic">{{ $peserta->nama_klinik }}</div>
                    </div>

                    <div class="data-item">
                        <div class="data-label"><i class="fas fa-user text-emerald-600"></i> NAMA PESERTA</div>
                        <div class="data-value">{{ $peserta->nama_peserta }}</div>
                    </div>

                    <div class="data-item">
                        <div class="data-label"><i class="fas fa-user-tag text-emerald-600"></i> JABATAN</div>
                        <div class="data-value">{{ $peserta->jabatan ?: 'Pimpinan / Delegasi Klinik' }}</div>
                    </div>

                    <div class="data-item">
                        <div class="data-label"><i class="fas fa-phone-alt text-emerald-600"></i> NO. WHATSAPP / HP</div>
                        <div class="data-value">{{ $peserta->no_hp }}</div>
                    </div>

                    <div class="data-item">
                        <div class="data-label"><i class="fas fa-envelope text-emerald-600"></i> EMAIL</div>
                        <div class="data-value">{{ $peserta->email }}</div>
                    </div>

                    <div class="data-item">
                        <div class="data-label"><i class="fas fa-map-marker-alt text-emerald-600"></i> ASAL DAERAH / PROVINSI</div>
                        <div class="data-value">{{ $peserta->provinsi->name ?? ($peserta->cabang ?: '-') }}</div>
                    </div>

                    <div class="data-item">
                        <div class="data-label"><i class="fas fa-check-circle text-emerald-600"></i> STATUS KEHADIRAN</div>
                        <div class="data-value">
                            <span class="data-value-highlight">
                                <i class="fas fa-{{ $peserta->status_kehadiran == 'Online' ? 'video' : 'building' }}"></i>
                                {{ $peserta->status_kehadiran ?: 'Hadir Langsung' }}
                            </span>
                        </div>
                    </div>

                    <div class="data-item full">
                        <div class="data-label"><i class="fas fa-calendar-alt text-emerald-600"></i> WAKTU &amp; TEMPAT ACARA</div>
                        <div class="data-value" style="font-size: 14px; font-weight: 600; color: #334155;">
                            {{ $periode->tanggal_formatted }} &bull; {{ $periode->tempat ?: 'Grand Ballroom' }}
                        </div>
                    </div>
                </div>

                <div class="notes-list">
                    <strong>Ketentuan Peserta:</strong>
                    <ul style="padding-left: 18px; margin-top: 4px;">
                        <li>Tunjukkan bukti registrasi ini (fisik atau digital di smartphone) saat proses registrasi ulang / check-in di meja panitia.</li>
                        <li>Peserta diharapkan hadir 30 menit sebelum pembukaan acara dimulai.</li>
                        <li>Informasi lebih lanjut dapat menghubungi Sekretariat Panitia RAKERNAS ASKLIN.</li>
                    </ul>
                </div>
            </div>

            <!-- QR Code Section -->
            <div class="qr-section">
                <div class="reg-number">{{ $peserta->no_registrasi }}</div>
                <div class="qr-box">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ urlencode($peserta->no_registrasi . '|' . $peserta->nama_peserta . '|' . $peserta->nama_klinik) }}" alt="QR Code">
                </div>
                <div class="qr-caption">
                    Scan QR Code untuk verifikasi kehadiran di lokasi acara.
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="card-footer">
            <div>
                <strong>Periode:</strong> {{ $periode->periode_kepengurusan ?? '2023-2028' }} (Rakernas ke-{{ $periode->rakernas_ke }})
            </div>
            <div>
                Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB
            </div>
        </div>
    </div>

</body>
</html>

