{{-- resources/views/beranda/index.blade.php --}}
@extends('layouts.app')
@section('title','Beranda')

@push('styles')
<style>
.beranda-hero {
    background: linear-gradient(135deg, #003D4A 0%, #005F73 50%, #0A9396 100%);
    padding: 0;
    position: relative;
    overflow: hidden;
    min-height: 380px;
    display: flex;
    align-items: center;
}
.beranda-hero::before {
    content: '';
    position: absolute;
    top: -100px; right: -100px;
    width: 400px; height: 400px;
    border-radius: 50%;
    background: rgba(255,255,255,.04);
}
.beranda-hero::after {
    content: '';
    position: absolute;
    bottom: -60px; left: -60px;
    width: 250px; height: 250px;
    border-radius: 50%;
    background: rgba(148,210,189,.08);
}
.beranda-hero-inner {
    max-width: 1100px;
    margin: 0 auto;
    padding: 3.5rem 1.5rem;
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 3rem;
    align-items: center;
    position: relative;
    z-index: 2;
    width: 100%;
}
.beranda-hero-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    color: rgba(255,255,255,.9);
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    padding: 5px 14px;
    border-radius: 20px;
    margin-bottom: 1.2rem;
}
.beranda-hero h1 {
    font-size: clamp(1.6rem,3.5vw,2.4rem);
    font-weight: 800;
    color: #fff;
    line-height: 1.2;
    margin-bottom: .8rem;
    font-family: 'Manrope',sans-serif;
}
.beranda-hero p {
    color: rgba(255,255,255,.8);
    font-size: .95rem;
    line-height: 1.8;
    max-width: 480px;
    margin-bottom: 1.75rem;
}
.hero-btns { display: flex; gap: 10px; flex-wrap: wrap; }
.hero-btn-primary {
    display: inline-flex; align-items: center; gap: 7px;
    background: #fff; color: #005F73;
    padding: 10px 22px; border-radius: 8px;
    font-weight: 700; font-size: .875rem;
    text-decoration: none; transition: all .18s;
}
.hero-btn-primary:hover { background: #E0F0F4; }
.hero-btn-ghost {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(255,255,255,.1);
    border: 1.5px solid rgba(255,255,255,.3);
    color: #fff;
    padding: 10px 22px; border-radius: 8px;
    font-weight: 600; font-size: .875rem;
    text-decoration: none; transition: all .18s;
}
.hero-btn-ghost:hover { background: rgba(255,255,255,.2); }

/* Visual kanan */
.hero-visual {
    display: flex;
    flex-direction: column;
    gap: 10px;
    flex-shrink: 0;
}
.hero-stat-pill {
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.15);
    border-radius: 12px;
    padding: 12px 18px;
    display: flex;
    align-items: center;
    gap: 12px;
    backdrop-filter: blur(8px);
    min-width: 200px;
}
.hero-stat-pill .num {
    font-size: 1.5rem;
    font-weight: 800;
    color: #fff;
    font-family: 'Manrope',sans-serif;
    line-height: 1;
}
.hero-stat-pill .lbl {
    font-size: .72rem;
    color: rgba(255,255,255,.75);
    line-height: 1.4;
}
.hero-stat-pill .dot {
    width: 10px; height: 10px;
    border-radius: 50%;
    flex-shrink: 0;
}

@media (max-width: 768px) {
    .beranda-hero-inner { grid-template-columns: 1fr; }
    .hero-visual { display: none; }
    .beranda-hero { min-height: auto; }
}

/* ── KONTAK ── */
.kontak-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.2rem; margin-bottom: 2.5rem;
}
.kontak-card {
    background: var(--white);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1.5rem;
    display: flex; gap: 1rem; align-items: flex-start;
    transition: box-shadow .2s;
}
.kontak-card:hover { box-shadow: var(--shadow); }
.kontak-icon {
    width: 44px; height: 44px;
    border-radius: 10px;
    background: var(--primary-light,#E0F0F4);
    display: flex; align-items: center; justify-content: center;
    color: var(--primary,#005F73); font-size: 1.1rem;
    flex-shrink: 0;
}
.kontak-card h4 { font-size: .9rem; font-weight: 700; color: var(--text); margin-bottom: .3rem; }
.kontak-card p  { font-size: .8rem; color: var(--text-muted); line-height: 1.6; margin-bottom: .4rem; }
.kontak-card a  { color: var(--primary,#005F73); font-size: .8rem; font-weight: 500; }
</style>
@endpush

@section('content')

{{-- HERO --}}
<div class="beranda-hero">
    <div class="beranda-hero-inner">
        <div>
            <div class="beranda-hero-tag">
                <i class="fa-solid fa-shield-halved"></i>
                IUCN Status: Vulnerable (Rentan)
            </div>
            <h1>Sistem Informasi<br>Konservasi Dugong Bintan</h1>
            <p>Platform pemantauan dan pelaporan dugong berbasis partisipasi masyarakat di perairan Kepulauan Riau. Setiap laporan berkontribusi langsung pada upaya pelestarian.</p>
            <div class="hero-btns">
                <a href="{{ route('peta.index') }}" class="hero-btn-primary">
                    <i class="fa-solid fa-map"></i> Lihat Peta Sebaran
                </a>
                @guest
                <a href="{{ route('register') }}" class="hero-btn-ghost">
                    Bergabung sebagai Ranger
                </a>
                @endguest
                @auth
                @if(auth()->user()->role !== 'admin')
                <a href="{{ route('laporan.create') }}" class="hero-btn-ghost">
                    <i class="fa-solid fa-plus"></i> Buat Laporan
                </a>
                @endif
                @endauth
            </div>
        </div>
    </div>
</div>

<div class="container">

    {{-- STAT GRID --}}
    <div class="stat-grid">
        <div class="stat-card stat-teal">
            <div class="stat-label">Total Pengamatan</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-sub">{{ $stats['historis'] }} historis + {{ $stats['total'] - $stats['historis'] }} laporan</div>
        </div>
        <div class="stat-card stat-blue">
            <div class="stat-label">Dugong Hidup</div>
            <div class="stat-value">{{ $stats['hidup'] }}</div>
            <div class="stat-sub">titik pengamatan</div>
        </div>
        <div class="stat-card" style="border-top-color:#374151;">
            <div class="stat-label">Mati Terdampar</div>
            <div class="stat-value" style="color:#374151;">{{ $stats['mati_terdampar'] }}</div>
            <div class="stat-sub">kasus tercatat</div>
        </div>
        <div class="stat-card stat-coral">
            <div class="stat-label">Mati Tertangkap</div>
            <div class="stat-value">{{ $stats['mati_tertangkap'] }}</div>
            <div class="stat-sub">kasus bycatch</div>
        </div>
    </div>

    {{-- 2 KOLOM --}}
    <div class="detail-grid-cols2" style="margin-bottom:1.5rem;gap:1.2rem;">

       {{-- Informasi Dugong Singkat --}}
<div class="card" style="margin:0;">
    <div class="card-title" style="font-size:.875rem;margin-bottom:1rem;">Tentang Dugong</div>
    <div style="display:flex;flex-direction:column;gap:12px;">
        <div style="display:flex;gap:12px;align-items:flex-start;">
            <div style="width:36px;height:36px;border-radius:8px;background:var(--primary-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fa-solid fa-fish" style="color:var(--primary);"></i>
            </div>
            <div>
                <div style="font-weight:600;font-size:.875rem;margin-bottom:2px;">Mamalia Laut Dilindungi</div>
                <p style="font-size:.8rem;color:var(--text-muted);line-height:1.65;">Dugong adalah satu-satunya mamalia laut dalam keluarga Dugongidae yang masih hidup. Dilindungi UU No. 5 Tahun 1990.</p>
            </div>
        </div>
        <div style="display:flex;gap:12px;align-items:flex-start;">
            <div style="width:36px;height:36px;border-radius:8px;background:#E1F5EE;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fa-solid fa-leaf" style="color:#1D9E75;"></i>
            </div>
            <div>
                <div style="font-weight:600;font-size:.875rem;margin-bottom:2px;">Bergantung pada Padang Lamun</div>
                <p style="font-size:.8rem;color:var(--text-muted);line-height:1.65;">Dugong mengonsumsi hingga 40 kg lamun per hari. Kerusakan padang lamun berdampak langsung pada kelangsungan hidupnya.</p>
            </div>
        </div>
        <div style="display:flex;gap:12px;align-items:flex-start;">
            <div style="width:36px;height:36px;border-radius:8px;background:#FEF3E2;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fa-solid fa-triangle-exclamation" style="color:#CA6702;"></i>
            </div>
            <div>
                <div style="font-weight:600;font-size:.875rem;margin-bottom:2px;">Ancaman Utama di Bintan</div>
                <p style="font-size:.8rem;color:var(--text-muted);line-height:1.65;">Tangkapan sampingan (bycatch), sedimentasi, dan degradasi habitat menjadi ancaman terbesar bagi dugong di perairan Kepulauan Riau.</p>
            </div>
        </div>
        <div style="display:flex;gap:12px;align-items:flex-start;">
            <div style="width:36px;height:36px;border-radius:8px;background:#E0F2FE;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fa-solid fa-users" style="color:#0077B6;"></i>
            </div>
            <div>
                <div style="font-weight:600;font-size:.875rem;margin-bottom:2px;">Peran Masyarakat</div>
                <p style="font-size:.8rem;color:var(--text-muted);line-height:1.65;">Laporan dari nelayan dan warga pesisir menjadi data penting yang tidak bisa diperoleh dari penelitian laboratorium saja.</p>
            </div>
        </div>
    </div>
    <div style="margin-top:1rem;padding-top:.75rem;border-top:1px solid var(--border);">
        <a href="{{ route('informasi.index') }}" class="btn btn-gray btn-sm" style="width:100%;justify-content:center;">
            Pelajari Lebih Lanjut
        </a>
    </div>
</div>

        {{-- Sebaran + Tren --}}
        <div style="display:flex;flex-direction:column;gap:1rem;">
            <div class="card" style="margin:0;">
                <div class="card-title" style="font-size:.875rem;margin-bottom:.75rem;">Sebaran Kondisi Dugong</div>
                @php
                    $tot = max($stats['hidup']+$stats['mati_terdampar']+$stats['mati_tertangkap'],1);
                @endphp
                <div style="display:flex;flex-direction:column;gap:10px;">
                    @foreach([['Hidup','#0A9396',$stats['hidup']],['Mati Terdampar','#374151',$stats['mati_terdampar']],['Mati Tertangkap','#AE2012',$stats['mati_tertangkap']]] as [$l,$c,$v])
                    @php $p=round(($v/$tot)*100); @endphp
                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:.78rem;margin-bottom:4px;">
                            <span style="font-weight:600;color:{{ $c }};">{{ $l }}</span>
                            <span style="font-weight:700;">{{ $v }} ({{ $p }}%)</span>
                        </div>
                        <div style="background:#F0F0F0;border-radius:4px;height:8px;overflow:hidden;">
                            <div style="width:{{ $p }}%;height:100%;background:{{ $c }};border-radius:4px;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div style="margin-top:1rem;padding-top:.75rem;border-top:1px solid var(--border);">
                    <a href="{{ route('peta.index') }}" class="btn btn-teal btn-sm" style="width:100%;justify-content:center;">
                        Lihat Peta Sebaran Lengkap
                    </a>
                </div>
            </div>

            <div class="card" style="margin:0;">
                <div class="card-title" style="font-size:.875rem;margin-bottom:.5rem;">Tren Laporan — 12 Bulan Terakhir</div>
                <div class="bar-chart" id="bar-chart" style="height:80px;"></div>
                <p style="font-size:.68rem;color:var(--text-muted);margin-top:.5rem;">Laporan baru dari masyarakat, tidak termasuk data historis.</p>
            </div>
        </div>
    </div>

    {{-- ALUR PELAPORAN --}}
    <div style="text-align:center;margin:2.5rem 0 1.5rem;">
        <div class="sec-eyebrow"><i class="fa-solid fa-route"></i> Panduan Singkat</div>
        <h2 class="sec-title" style="margin-bottom:.4rem;">Alur Pelaporan — Cuma 3 Langkah</h2>
        <p class="sec-sub" style="margin:0 auto;">Baru pertama kali mau lapor? Ikuti langkah sederhana berikut ini.</p>
    </div>
    <div class="steps-grid">
        <div class="step-card">
            <div class="step-num">1</div>
            <h4>Buka Halaman Laporan</h4>
            <p>Klik tombol "Laporkan!" di navbar, lalu masuk/daftar akun jika belum punya.</p>
        </div>
        <div class="step-card">
            <div class="step-num">2</div>
            <h4>Isi & Tandai Lokasi</h4>
            <p>Pilih kondisi dugong, tandai titik lokasi di peta atau pakai tombol GPS, lalu lampirkan foto/video.</p>
        </div>
        <div class="step-card">
            <div class="step-num">3</div>
            <h4>Kirim & Pantau Status</h4>
            <p>Kirim laporan Anda, lalu pantau status verifikasinya kapan saja lewat halaman Riwayat Laporan.</p>
        </div>
    </div>

    {{-- ══ KONTAK ══ --}}
    <div style="text-align:center;margin:2.5rem 0 1.5rem;">
        <div class="sec-eyebrow"><i class="fa-solid fa-address-book"></i> Hubungi Kami</div>
        <h2 class="sec-title" style="margin-bottom:.4rem;">Lembaga Konservasi Terkait</h2>
        <p class="sec-sub" style="margin:0 auto;">Untuk pelaporan darurat, informasi lebih lanjut, atau kerjasama program konservasi.</p>
    </div>
    <div class="kontak-grid">
        <div class="kontak-card">
            <div class="kontak-icon"><i class="fa-solid fa-building-columns"></i></div>
            <div>
                <h4>BPSPL Padang (Wil. Kepri)</h4>
                <p>Balai Pengelolaan Sumber Daya Pesisir dan Laut, Kementerian Kelautan dan Perikanan</p>
                <a href="mailto:bpspl.padang@kkp.go.id">
                    <i class="fa-solid fa-envelope"></i> bpspl.padang@kkp.go.id
                </a>
            </div>
        </div>
        <div class="kontak-card">
            <div class="kontak-icon"><i class="fa-solid fa-leaf"></i></div>
            <div>
                <h4>WWF Indonesia</h4>
                <p>World Wide Fund for Nature — Program Kelautan dan Pesisir Indonesia</p>
                <a href="mailto:info@wwf.id">
                    <i class="fa-solid fa-envelope"></i> info@wwf.id
                </a>
            </div>
        </div>
        <div class="kontak-card">
            <div class="kontak-icon"><i class="fa-solid fa-fish"></i></div>
            <div>
                <h4>SiKoDung — Laporan Online</h4>
                <p>Laporkan penampakan dugong atau kondisi habitat langsung melalui sistem ini</p>
                <a href="{{ route('laporan.create') }}">
                    <i class="fa-solid fa-arrow-right"></i> Buat Laporan Sekarang
                </a>
            </div>
        </div>
    </div>

    {{-- CTA --}}
    @guest
    <div class="cta-band">
        <h3>Bergabung sebagai Dugong Ranger</h3>
        <p>Daftarkan diri untuk ikut memantau dan melaporkan kondisi dugong di perairan Kepulauan Riau.</p>
        <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('register') }}" class="btn btn-teal">Daftar Sekarang</a>
            <a href="{{ route('informasi.index') }}" class="btn btn-gray">Pelajari Tentang Dugong</a>
        </div>
    </div>
    @endguest

</div>
@endsection

@push('scripts')
<script>
const labels = @json($chartLabels);
const data   = @json($chartData);
const chart  = document.getElementById('bar-chart');
if (chart && labels.length) {
    const max = Math.max(...data, 1);
    chart.innerHTML = labels.map((l,i) => `
        <div class="bar-col">
            <div class="bar-val">${data[i] > 0 ? data[i] : ''}</div>
            <div class="bar-rect" style="height:${Math.max((data[i]/max)*60,2)}px;"></div>
            <div class="bar-lbl">${l}</div>
        </div>`).join('');
}
</script>
@endpush
