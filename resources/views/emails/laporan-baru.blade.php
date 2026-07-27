<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Baru — SiKoDung</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; background: #F3F4F6; color: #111827; }
        .wrapper { max-width: 600px; margin: 0 auto; padding: 24px 16px; }
        .card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .header { background: #005F73; padding: 28px 32px; text-align: center; }
        .header h1 { color: #fff; font-size: 20px; font-weight: 700; margin-bottom: 4px; }
        .header p { color: rgba(255,255,255,.75); font-size: 13px; }
        .body { padding: 28px 32px; }
        .greeting { font-size: 16px; font-weight: 600; margin-bottom: 12px; color: #111827; }
        .text { font-size: 14px; color: #374151; line-height: 1.7; margin-bottom: 16px; }
        .info-box { background: #F0F8FA; border-left: 4px solid #005F73; border-radius: 0 8px 8px 0; padding: 14px 16px; margin-bottom: 20px; }
        .info-row { display: flex; justify-content: space-between; font-size: 13px; padding: 4px 0; border-bottom: 1px solid #E0EFF2; }
        .info-row:last-child { border: none; }
        .info-label { color: #6B7280; }
        .info-val { font-weight: 600; color: #111827; }
        .btn { display: inline-block; background: #005F73; color: #fff !important; text-decoration: none; font-size: 14px; font-weight: 600; padding: 12px 24px; border-radius: 8px; margin-bottom: 8px; }
        .footer { padding: 20px 32px; border-top: 1px solid #E5E7EB; text-align: center; font-size: 12px; color: #9CA3AF; }
        .footer a { color: #005F73; text-decoration: none; }
        .badge { display: inline-block; background: #FEF3C7; color: #7A3D00; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="card">

        <div class="header">
            <h1>SiKoDung</h1>
            <p>Sistem Informasi Konservasi Dugong Bintan</p>
        </div>

        <div class="body">
            <div class="greeting">Ada laporan baru masuk</div>
            <p class="text">
                Seorang pelapor baru saja mengirimkan laporan
                <strong>{{ $laporan->jenis?->nama === 'dugong' ? 'penampakan dugong' : 'kondisi habitat' }}</strong>.
                Laporan ini <span class="badge">Menunggu Verifikasi</span> dan perlu ditinjau oleh admin.
            </p>

            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Kode Laporan</span>
                    <span class="info-val">{{ $laporan->kode }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tanggal Kejadian</span>
                    <span class="info-val">{{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Lokasi</span>
                    <span class="info-val">{{ $laporan->lokasi?->nama ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Pelapor</span>
                    <span class="info-val">{{ $laporan->nama_pelapor ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">No. HP</span>
                    <span class="info-val">{{ $laporan->no_hp ?? '—' }}</span>
                </div>
            </div>

            <p class="text">Segera tinjau dan verifikasi laporan ini di dashboard admin:</p>
            <a href="{{ route('admin.laporan.show', $laporan->id) }}" class="btn">Tinjau Laporan</a>
        </div>

        <div class="footer">
            Email otomatis dari <a href="{{ route('beranda') }}">SiKoDung</a> — Sistem Informasi Konservasi Dugong Bintan
        </div>
    </div>
</div>
</body>
</html>
