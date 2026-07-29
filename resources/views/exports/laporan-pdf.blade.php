<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Dugong Bintan</title>
<style>
    * { box-sizing: border-box; }
    body { font-family: 'Helvetica', Arial, sans-serif; color: #1C1C1E; font-size: 11px; }
    .header { background: #005F73; color: #fff; padding: 18px 24px; margin-bottom: 18px; }
    .header h1 { margin: 0 0 4px; font-size: 18px; }
    .header p { margin: 0; font-size: 11px; opacity: .85; }
    .section-title {
        font-size: 14px; font-weight: bold; color: #005F73;
        margin: 22px 0 8px; padding-bottom: 4px; border-bottom: 2px solid #94D2BD;
    }
    table { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
    th, td { border: 1px solid #D0D7DE; padding: 5px 8px; text-align: left; font-size: 10px; }
    th { background: #005F73; color: #fff; }
    tr:nth-child(even) td { background: #F8F9FA; }
    .ringkasan-grid { width: 100%; }
    .ringkasan-grid td { border: none; padding: 6px 10px; }
    .ringkasan-label { color: #6B7280; }
    .ringkasan-val { font-weight: bold; color: #005F73; font-size: 13px; }
    .footer { margin-top: 24px; font-size: 9px; color: #9CA3AF; text-align: center; }
    .empty { color: #9CA3AF; font-style: italic; padding: 8px 0; }

    .chart-box { border: 1px solid #E5E7EB; border-radius: 4px; padding: 10px 12px; margin-bottom: 10px; }
    .bar-chart-table { width: 100%; border-collapse: collapse; }
    .bar-chart-table td { border: none; padding: 0 2px; text-align: center; vertical-align: bottom; }
    .bar-chart-val { font-size: 7px; color: #005F73; font-weight: bold; }
    .bar-chart-bar { background: #005F73; width: 60%; margin: 1px auto 0; }
    .bar-chart-label { font-size: 7px; color: #6B7280; padding-top: 3px !important; }
    .hbar-table { width: 100%; border-collapse: collapse; }
    .hbar-table td { border: none; padding: 2px 0; font-size: 9px; }
    .hbar-label { width: 32%; text-align: right; padding-right: 8px !important; white-space: nowrap; color: #1C1C1E; }
    .hbar-track { width: 68%; }
    .hbar-bar { background: #CA6702; height: 12px; border-radius: 2px; }
    .hbar-val { font-size: 8px; color: #6B7280; padding-left: 6px; }
    .donut-legend { width: 100%; border-collapse: collapse; margin-top: 4px; }
    .donut-legend td { border: none; padding: 3px 6px; font-size: 9px; }
    .donut-swatch { width: 10px; height: 10px; border-radius: 50%; display: inline-block; margin-right: 6px; }
    .donut-persen { font-weight: bold; }
</style>
</head>
<body>

<div class="header">
    <h1>Simadang - {{ $judulDokumen }}</h1>
    <p>Sistem Informasi Konservasi Dugong Bintan · Dihasilkan {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
</div>

@if(in_array($mode, ['statistik', 'lengkap']))
    @php
        $maxBulan = collect($statistik['bulanan'])->max('jumlah') ?: 1;
        $maxTinggiBar = 70; // px

        $tahunanList = $statistik['tahunan']->values();
        $maxTahun = $tahunanList->max('jumlah') ?: 1;
        $lebarSvg = 560; $tinggiSvg = 130; $paddingSvg = 16;
        $n = $tahunanList->count();
        $titikLine = $tahunanList->map(function ($t, $i) use ($n, $maxTahun, $lebarSvg, $tinggiSvg, $paddingSvg) {
            $x = $n <= 1 ? $lebarSvg / 2 : $paddingSvg + ($i / ($n - 1)) * ($lebarSvg - 2 * $paddingSvg);
            $y = $tinggiSvg - $paddingSvg - ($t['jumlah'] / $maxTahun) * ($tinggiSvg - 2 * $paddingSvg);
            return round($x, 1) . ',' . round($y, 1);
        });

        $maxWilayah = $statistik['wilayahPrioritas']->max('jumlah') ?: 1;

        $totalKondisi = collect($statistik['kondisiDugong'])->sum('jumlah') ?: 1;
        $warnaKondisi = ['Hidup' => '#2196F3', 'Mati Terdampar' => '#424242', 'Mati Tertangkap' => '#E65100'];
        $rKondisi = 34; $kelilingKondisi = 2 * M_PI * $rKondisi;
        $offsetKondisi = 0;
    @endphp

    <div class="section-title">Ringkasan</div>
    <table class="ringkasan-grid">
        @foreach($statistik['ringkasan'] as $label => $nilai)
        <tr>
            <td class="ringkasan-label" style="width:70%;">{{ $label }}</td>
            <td class="ringkasan-val">{{ $nilai }}</td>
        </tr>
        @endforeach
    </table>

    <div class="section-title">Statistik Bulanan</div>
    <div class="chart-box">
        <table class="bar-chart-table">
            <tr>
                @foreach($statistik['bulanan'] as $b)
                <td style="height:{{ $maxTinggiBar + 12 }}px;width:{{ 100/12 }}%;">
                    <div class="bar-chart-val">{{ $b['jumlah'] }}</div>
                    <div class="bar-chart-bar" style="height:{{ max(2, round($b['jumlah'] / $maxBulan * $maxTinggiBar)) }}px;"></div>
                </td>
                @endforeach
            </tr>
            <tr>
                @foreach($statistik['bulanan'] as $b)
                <td class="bar-chart-label">{{ $b['label'] }}</td>
                @endforeach
            </tr>
        </table>
    </div>

    <div class="section-title">Statistik Tahunan</div>
    @if($statistik['tahunan']->isEmpty())
        <div class="empty">Tidak ada data.</div>
    @else
    <div class="chart-box">
        <svg width="100%" height="{{ $tinggiSvg }}" viewBox="0 0 {{ $lebarSvg }} {{ $tinggiSvg }}" xmlns="http://www.w3.org/2000/svg">
            <polyline points="{{ $titikLine->implode(' ') }}" fill="none" stroke="#005F73" stroke-width="2.5"/>
            @foreach($titikLine as $tt)
                @php [$tx, $ty] = explode(',', $tt); @endphp
                <circle cx="{{ $tx }}" cy="{{ $ty }}" r="3.5" fill="#005F73"/>
            @endforeach
        </svg>
        <table class="bar-chart-table">
            <tr>
                @foreach($tahunanList as $t)
                <td class="bar-chart-label" style="width:{{ 100 / max($n,1) }}%;">{{ $t['tahun'] }}</td>
                @endforeach
            </tr>
        </table>
    </div>
    @endif

    <div class="section-title">Wilayah Prioritas (berdasarkan laporan terverifikasi)</div>
    @if($statistik['wilayahPrioritas']->isEmpty())
        <div class="empty">Tidak ada data.</div>
    @else
    <div class="chart-box">
        <table class="hbar-table">
            @foreach($statistik['wilayahPrioritas'] as $w)
            <tr>
                <td class="hbar-label">{{ $w['lokasi'] }}</td>
                <td class="hbar-track">
                    <div class="hbar-bar" style="width:{{ max(3, round($w['jumlah'] / $maxWilayah * 100)) }}%;"></div>
                </td>
                <td class="hbar-val">{{ $w['jumlah'] }}</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    <div class="section-title">Kondisi Dugong</div>
    <div class="chart-box">
        <table style="width:100%;border:none;">
            <tr>
                <td style="width:110px;border:none;padding:0;">
                    <svg width="90" height="90" viewBox="0 0 90 90" xmlns="http://www.w3.org/2000/svg">
                        @foreach($statistik['kondisiDugong'] as $k)
                            @php
                                $panjang = ($k['jumlah'] / $totalKondisi) * $kelilingKondisi;
                                $warna = $warnaKondisi[$k['kondisi']] ?? '#999';
                            @endphp
                            <circle cx="45" cy="45" r="{{ $rKondisi }}" fill="none" stroke="{{ $warna }}"
                                stroke-width="16" stroke-dasharray="{{ round($panjang,1) }} {{ round($kelilingKondisi - $panjang, 1) }}"
                                stroke-dashoffset="{{ round(-$offsetKondisi, 1) }}"/>
                            @php $offsetKondisi += $panjang; @endphp
                        @endforeach
                    </svg>
                </td>
                <td style="border:none;padding:0;vertical-align:middle;">
                    <table class="donut-legend">
                        @foreach($statistik['kondisiDugong'] as $k)
                        <tr>
                            <td style="width:16px;"><span class="donut-swatch" style="background:{{ $warnaKondisi[$k['kondisi']] ?? '#999' }};"></span></td>
                            <td>{{ $k['kondisi'] }}</td>
                            <td class="donut-persen">{{ $totalKondisi > 0 ? round($k['jumlah'] / $totalKondisi * 100, 1) : 0 }}%</td>
                            <td style="color:#6B7280;">({{ $k['jumlah'] }})</td>
                        </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <table>
        <thead><tr><th>Kondisi</th><th>Jumlah</th></tr></thead>
        <tbody>
        @foreach($statistik['kondisiDugong'] as $k)
            <tr><td style="color:{{ $warnaKondisi[$k['kondisi']] ?? '#1C1C1E' }};font-weight:bold;">{{ $k['kondisi'] }}</td><td>{{ $k['jumlah'] }}</td></tr>
        @endforeach
        </tbody>
    </table>
@endif

@if(in_array($mode, ['laporan', 'lengkap']))
    <div class="section-title">Data Laporan</div>
    @if($laporan->isEmpty())
        <div class="empty">Tidak ada laporan yang sesuai filter.</div>
    @else
    <table>
        <thead>
            <tr>
                <th>Kode</th><th>Tanggal</th><th>Jenis</th><th>Lokasi</th>
                <th>Latitude</th><th>Longitude</th><th>Jumlah</th><th>Kondisi</th>
                <th>Pelapor</th><th>Status</th>
            </tr>
        </thead>
        <tbody>
        @foreach($laporan as $l)
            <tr>
                <td>{{ $l->kode }}</td>
                <td>{{ $l->tanggal?->format('d-m-Y') ?? '-' }}</td>
                <td>{{ $l->jenis?->nama === 'dugong' ? 'Dugong' : 'Habitat' }}</td>
                <td>{{ $l->lokasi?->nama ?? '-' }}</td>
                <td>{{ $l->latitude ?? $l->lokasi?->latitude ?? '-' }}</td>
                <td>{{ $l->longitude ?? $l->lokasi?->longitude ?? '-' }}</td>
                <td>{{ $l->jumlah_dugong ?? '-' }}</td>
                <td>{{ match($l->kondisi?->nama) { 'hidup' => 'Hidup', 'mati_terdampar' => 'Mati Terdampar', 'mati_tertangkap' => 'Mati Tertangkap', default => '-' } }}</td>
                <td>{{ $l->nama_pelapor ?? $l->user?->name ?? '-' }}</td>
                <td>{{ ucfirst($l->status) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @endif
@endif

<div class="footer">
    Dokumen ini dihasilkan otomatis oleh Simadang untuk kebutuhan monitoring dan pengambilan keputusan konservasi dugong di Kepulauan Riau.
</div>

</body>
</html>
