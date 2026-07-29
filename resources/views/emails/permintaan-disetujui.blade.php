<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Dugong Bintan - Simadang</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; background: #F3F4F6; color: #111827; }
        .wrapper { max-width: 600px; margin: 0 auto; padding: 24px 16px; }
        .card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .header { background: #005F73; padding: 28px 32px; text-align: center; }
        .header img { width: 48px; height: 48px; margin-bottom: 12px; }
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
        .attach-box { background: #E1F5EE; border-radius: 8px; padding: 14px 16px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; }
        .attach-icon { width: 40px; height: 40px; background: #1D9E75; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .attach-info h4 { font-size: 14px; font-weight: 600; color: #054030; margin-bottom: 2px; }
        .attach-info p { font-size: 12px; color: #1D9E75; }
        .note { font-size: 12px; color: #6B7280; line-height: 1.6; background: #F9FAFB; border-radius: 8px; padding: 12px 14px; margin-bottom: 20px; }
        .footer { padding: 20px 32px; border-top: 1px solid #E5E7EB; text-align: center; font-size: 12px; color: #9CA3AF; }
        .footer a { color: #005F73; text-decoration: none; }
        .badge { display: inline-block; background: #E1F5EE; color: #054030; padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="card">

        {{-- Header --}}
        <div class="header">
            <h1>Simadang</h1>
            <p>Sistem Informasi Konservasi Dugong Bintan</p>
        </div>

        {{-- Body --}}
        <div class="body">
            <div class="greeting">Yth. {{ $permintaan->nama_pemohon }},</div>
            <p class="text">
                Permintaan data dugong Anda telah <strong>disetujui</strong> oleh administrator Simadang.
                Data yang Anda minta telah kami lampirkan dalam email ini dalam format
                <strong>{{ $permintaan->format_file === 'pdf' ? 'PDF' : 'Excel (.xlsx)' }}</strong>.
            </p>

            {{-- Info Permintaan --}}
            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Nomor Permintaan</span>
                    <span class="info-val">#{{ $permintaan->id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jenis Data</span>
                    <span class="info-val">{{ $permintaan->jenis_data_label }}</span>
                </div>
                @if($permintaan->periode_dari || $permintaan->periode_sampai)
                <div class="info-row">
                    <span class="info-label">Periode</span>
                    <span class="info-val">{{ $permintaan->periode_dari ?? '-' }} s/d {{ $permintaan->periode_sampai ?? 'sekarang' }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Tujuan</span>
                    <span class="info-val">{{ $permintaan->tujuan }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="badge">Disetujui</span>
                </div>
            </div>

            {{-- File attachment info --}}
            <div class="attach-box">
                <div class="attach-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                </div>
                <div class="attach-info">
                    <h4>{{ $namaFile }}</h4>
                    <p>
                        @if($permintaan->format_file === 'pdf')
                            Terlampir di email ini, buka dengan aplikasi pembaca PDF
                        @else
                            Terlampir di email ini, buka dengan Excel atau Google Sheets
                        @endif
                    </p>
                </div>
            </div>

            {{-- Catatan admin --}}
            @if($permintaan->catatan_admin)
            <p class="text"><strong>Catatan dari Admin:</strong><br>{{ $permintaan->catatan_admin }}</p>
            @endif

            {{-- Note penggunaan --}}
            <div class="note">
                <strong>Ketentuan Penggunaan Data:</strong><br>
                Data ini diberikan untuk keperluan <em>{{ $permintaan->tujuan }}</em>. Mohon cantumkan sumber data sebagai
                <em>"Simadang, Sistem Informasi Konservasi Dugong Bintan, {{ date('Y') }}"</em> apabila digunakan dalam
                publikasi, laporan, atau karya ilmiah. Data tidak boleh disebarluaskan tanpa izin dari administrator Simadang.
            </div>

            <p class="text">
                Jika ada pertanyaan atau membutuhkan format data lain, silakan hubungi administrator melalui sistem Simadang.
            </p>
            <p class="text">Terima kasih atas partisipasi Anda dalam konservasi dugong di Kepulauan Riau.</p>
            <p class="text" style="margin-top:20px;">
                Salam,<br>
                <strong>Tim Simadang</strong><br>
                <span style="color:#6B7280;font-size:13px;">Sistem Informasi Konservasi Dugong Bintan</span>
            </p>
        </div>

        <div class="footer">
            <p>Email ini dikirim otomatis oleh sistem <a href="#">Simadang</a>.</p>
            <p style="margin-top:4px;">Jangan membalas email ini secara langsung.</p>
        </div>
    </div>
</div>
</body>
</html>
