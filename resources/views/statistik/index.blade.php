{{-- resources/views/statistik/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Statistik')

@section('content')
<div class="container">

    <div class="page-header">
        <h2>Statistik Konservasi Dugong</h2>
        <p>Dashboard pemantauan laporan penampakan dan kondisi dugong di Kepulauan Riau ({{ $rentangTahun['dari'] }}–{{ $rentangTahun['sampai'] }}) untuk mendukung pengambilan keputusan konservasi.</p>
    </div>

    {{-- Ringkasan --}}
    <div class="stat-grid stat-grid-5">
        <div class="stat-card stat-teal">
            <div class="stat-label"><i class="fa-solid fa-clipboard-list"></i> Total Laporan</div>
            <div class="stat-value">{{ $ringkasan['total'] }}</div>
        </div>
        <div class="stat-card stat-green">
            <div class="stat-label"><i class="fa-solid fa-circle-check"></i> Terverifikasi</div>
            <div class="stat-value">{{ $ringkasan['terverifikasi'] }}</div>
        </div>
        <div class="stat-card stat-amber">
            <div class="stat-label"><i class="fa-solid fa-hourglass-half"></i> Menunggu Verifikasi</div>
            <div class="stat-value">{{ $ringkasan['menunggu'] }}</div>
        </div>
        <div class="stat-card stat-coral">
            <div class="stat-label"><i class="fa-solid fa-circle-xmark"></i> Ditolak</div>
            <div class="stat-value">{{ $ringkasan['ditolak'] }}</div>
        </div>
        <div class="stat-card stat-blue">
            <div class="stat-label"><i class="fa-solid fa-fish"></i> Total Dugong Teramati</div>
            <div class="stat-value">{{ $ringkasan['totalDugong'] }}</div>
            <div class="stat-sub">dari laporan terverifikasi</div>
        </div>
    </div>

    {{-- Tab navigasi --}}
    <div class="tren-tabs">
        <button type="button" class="tren-tab active" data-tab="tren">
            <i class="fa-solid fa-chart-line"></i> Tren Laporan
        </button>
        <button type="button" class="tren-tab" data-tab="wilayah">
            <i class="fa-solid fa-map-location-dot"></i> Wilayah Prioritas
        </button>
        <button type="button" class="tren-tab" data-tab="kondisi">
            <i class="fa-solid fa-heart-pulse"></i> Kondisi Dugong
        </button>
    </div>

    {{-- Panel: Tren Laporan (Bulanan + Tahunan) --}}
    <div class="tren-panel" data-panel="tren">
        <div class="card">
            <div class="card-title"><i class="fa-solid fa-chart-column"></i> Statistik Bulanan</div>
            <p class="chart-desc">Jumlah laporan terverifikasi setiap bulan (akumulasi seluruh tahun).</p>
            <div class="chart-fixed-height">
                <canvas id="chartBulanan"></canvas>
            </div>
        </div>
        <div class="card">
            <div class="card-title"><i class="fa-solid fa-chart-line"></i> Tren Penampakan Dugong per Tahun</div>
            <p class="chart-desc">Jumlah penampakan terverifikasi per tahun, dipisah berdasarkan kondisi dugong.</p>
            <div class="chart-fixed-height">
                <canvas id="chartTahunan"></canvas>
            </div>
        </div>
    </div>

    {{-- Panel: Wilayah Prioritas Konservasi --}}
    <div class="tren-panel" data-panel="wilayah" style="display:none;">
        <div class="card" style="padding:0;overflow:hidden;">
            <div class="card-title" style="padding:1.25rem 1.25rem 0;">
                <i class="fa-solid fa-map-location-dot"></i> Wilayah Prioritas Konservasi
            </div>
            <p class="chart-desc" style="padding:0 1.25rem;">Wilayah dengan jumlah laporan terverifikasi terbanyak, semakin besar &amp; gelap titik, semakin tinggi prioritas konservasi.</p>
            <div id="peta-wilayah"></div>
        </div>

        <div class="card card-flush">
            <div class="card-header">
                <span class="card-title" style="margin-bottom:0;">Peringkat Wilayah</span>
            </div>
            <div style="overflow-x:auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:70px;">Peringkat</th>
                            <th>Lokasi</th>
                            <th style="text-align:center;">Laporan Terverifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wilayahPrioritas as $i => $w)
                        <tr>
                            <td>
                                <span class="rank-badge {{ $i < 3 ? 'rank-top' : '' }}">{{ $i + 1 }}</span>
                            </td>
                            <td style="font-weight:600;">{{ $w['lokasi'] }}</td>
                            <td style="text-align:center;font-weight:700;color:var(--primary,#005F73);">{{ $w['jumlah'] }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" style="text-align:center;color:var(--text-muted);">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Panel: Kondisi Dugong --}}
    <div class="tren-panel" data-panel="kondisi" style="display:none;">
        <div class="card">
            <div class="card-title"><i class="fa-solid fa-heart-pulse"></i> Kondisi Dugong</div>
            <p class="chart-desc">Distribusi jumlah laporan dugong terverifikasi berdasarkan kondisi saat ditemukan.</p>
            @php $totalKondisi = collect($kondisiDugong)->sum('jumlah'); @endphp
            <div class="kondisi-layout">
                <div class="kondisi-chart-wrap">
                    <canvas id="chartKondisi"></canvas>
                </div>
                <div class="kondisi-legend">
                    @foreach($kondisiDugong as $k)
                    <div class="kondisi-legend-item">
                        <span class="kondisi-dot" style="background:{{ ['Hidup'=>'#2196F3','Mati Terdampar'=>'#424242','Mati Tertangkap'=>'#E65100'][$k['kondisi']] ?? '#999' }};"></span>
                        <span class="kondisi-legend-label">{{ $k['kondisi'] }}</span>
                        <span class="kondisi-legend-persen">{{ $totalKondisi > 0 ? round($k['jumlah'] / $totalKondisi * 100, 1) : 0 }}%</span>
                        <span class="kondisi-legend-val">{{ $k['jumlah'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
.stat-grid-5 { grid-template-columns: repeat(5, 1fr); }
@media (max-width: 992px) { .stat-grid-5 { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 600px) { .stat-grid-5 { grid-template-columns: repeat(2, 1fr); } }

.stat-green  { border-top-color: #1D9E75; }
.stat-green  .stat-value { color: #1D9E75; }

.tren-tabs { display:flex; gap:6px; margin-bottom:1.25rem; flex-wrap:wrap; border-bottom:1px solid var(--border); }
.tren-tab {
    padding:10px 16px; border:none; background:none; cursor:pointer;
    font-family:'Hanken Grotesk','DM Sans',sans-serif; font-size:.85rem; font-weight:600;
    color:var(--text-muted); border-bottom:2.5px solid transparent; margin-bottom:-1px;
    display:flex; align-items:center; gap:7px; transition:all .15s;
}
.tren-tab:hover { color:var(--primary,#005F73); }
.tren-tab.active { color:var(--primary,#005F73); border-bottom-color:var(--primary,#005F73); }

.chart-desc { font-size:.8rem; color:var(--text-muted); margin:-6px 0 1rem; line-height:1.5; }

/* Wajib ada tinggi CSS yang pasti untuk canvas Chart.js dengan maintainAspectRatio:false —
   tanpa ini, canvas & parent-nya bisa saling membesar tanpa henti (loop resize) sampai
   memakan RAM berlebihan dan bikin halaman berat/lag. */
.chart-fixed-height { position:relative; height:260px; width:100%; }

#peta-wilayah { width:100%; height:380px; position:relative; z-index:0; }
.leaflet-pane,.leaflet-tile-pane,.leaflet-overlay-pane{z-index:1!important;}
.leaflet-marker-pane{z-index:4!important;}
.leaflet-tooltip-pane{z-index:5!important;}
.leaflet-popup-pane{z-index:6!important;}
.leaflet-top,.leaflet-bottom{z-index:7!important;}
.leaflet-control{z-index:8!important;}
.leaflet-popup-content-wrapper{border-radius:10px!important;box-shadow:0 4px 20px rgba(0,0,0,.18)!important;padding:0!important;overflow:hidden;}
.leaflet-popup-content{margin:0!important;min-width:180px;}
.wilayah-popup-head{padding:8px 13px;background:var(--primary,#005F73);color:#fff;font-size:13px;font-weight:700;}
.wilayah-popup-body{padding:9px 13px;font-size:12px;color:var(--text-muted);}
.wilayah-popup-body b{color:var(--primary,#005F73);font-size:16px;}

.rank-badge{display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:50%;background:var(--surface-2,#F1F5F6);color:var(--text-muted);font-weight:700;font-size:.78rem;}
.rank-badge.rank-top{background:var(--primary,#005F73);color:#fff;}

.kondisi-layout{display:flex;gap:2rem;align-items:center;flex-wrap:wrap;}
.kondisi-chart-wrap{width:220px;height:220px;flex-shrink:0;margin:0 auto;}
.kondisi-legend{display:flex;flex-direction:column;gap:10px;flex:1;min-width:220px;}
.kondisi-legend-item{display:flex;align-items:center;gap:10px;padding:8px 12px;background:var(--surface-2,#F8F9FA);border-radius:8px;}
.kondisi-dot{width:12px;height:12px;border-radius:50%;flex-shrink:0;}
.kondisi-legend-label{flex:1;font-size:.85rem;font-weight:600;}
.kondisi-legend-persen{font-size:.85rem;font-weight:700;color:var(--primary,#005F73);}
.kondisi-legend-val{font-size:1.1rem;font-weight:800;font-family:'Manrope',sans-serif;color:var(--text);min-width:28px;text-align:right;}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
/* Leaflet (peta) baru dimuat kalau tab "Wilayah Prioritas" benar-benar diklik —
   supaya kunjungan yang tidak pernah buka tab itu tidak ikut download library peta (~150KB). */
let leafletPromise = null;
function muatLeaflet() {
    if (leafletPromise) return leafletPromise;
    leafletPromise = new Promise((resolve, reject) => {
        const css = document.createElement('link');
        css.rel = 'stylesheet';
        css.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
        document.head.appendChild(css);

        const script = document.createElement('script');
        script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
        script.onload = () => resolve();
        script.onerror = () => reject(new Error('Gagal memuat Leaflet'));
        document.body.appendChild(script);
    });
    return leafletPromise;
}
</script>
<script>
const bulananData = @json($bulanan);
const tahunanKondisiData = @json($tahunanPerKondisi);
const kondisiData  = @json($kondisiDugong);
const petaWilayah  = @json($petaWilayah);

const sudahInit = { tren: false, wilayah: false, kondisi: false };
let mapWilayah = null;

function initTren() {
    new Chart(document.getElementById('chartBulanan').getContext('2d'), {
        type: 'bar',
        data: {
            labels: bulananData.map(b => b.label),
            datasets: [{
                label: 'Jumlah Laporan',
                data: bulananData.map(b => b.jumlah),
                backgroundColor: 'rgba(0,95,115,.85)',
                borderRadius: 5,
                maxBarThickness: 34,
            }]
        },
        options: {
            responsive:true, maintainAspectRatio:false,
            plugins:{ legend:{display:false} },
            scales:{ x:{grid:{display:false}}, y:{beginAtZero:true, ticks:{stepSize:1}} }
        }
    });

    new Chart(document.getElementById('chartTahunan').getContext('2d'), {
        type: 'line',
        data: {
            labels: tahunanKondisiData.map(t => t.tahun),
            datasets: [
                {
                    label: 'Hidup',
                    data: tahunanKondisiData.map(t => t.hidup),
                    borderColor: '#2196F3', backgroundColor: '#2196F3',
                    borderWidth: 2.5, pointRadius: 4, tension: .3, fill: false,
                },
                {
                    label: 'Mati (Terdampar)',
                    data: tahunanKondisiData.map(t => t.mati_terdampar),
                    borderColor: '#424242', backgroundColor: '#424242',
                    borderWidth: 2.5, pointRadius: 4, tension: .3, fill: false,
                },
                {
                    label: 'Mati (Tertangkap)',
                    data: tahunanKondisiData.map(t => t.mati_tertangkap),
                    borderColor: '#E65100', backgroundColor: '#E65100',
                    borderWidth: 2.5, pointRadius: 4, tension: .3, fill: false,
                },
            ]
        },
        options: {
            responsive:true, maintainAspectRatio:false,
            plugins:{ legend:{display:true, position:'bottom'} },
            scales:{
                x:{ grid:{display:false}, title:{display:true, text:'Tahun'} },
                y:{ beginAtZero:true, ticks:{stepSize:1}, title:{display:true, text:'Jumlah Penampakan'} }
            }
        }
    });
}

async function initWilayah() {
    await muatLeaflet();
    mapWilayah = L.map('peta-wilayah', {
        center: [0.9800, 104.5000],
        zoom: 10, minZoom: 8, maxZoom: 18,
        maxBounds: [[-2, 100], [5, 110]],
        maxBoundsViscosity: 1.0,
        scrollWheelZoom: false,
    });
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://openstreetmap.org">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(mapWilayah);

    const maxJumlah = Math.max(1, ...petaWilayah.map(w => w.jumlah));

    function warnaIntensitas(jumlah) {
        const rasio = jumlah / maxJumlah;
        if (rasio >= .75) return '#AE2012';
        if (rasio >= .5)  return '#CA6702';
        if (rasio >= .25) return '#0A9396';
        return '#94D2BD';
    }

    const koordinatWilayah = [];
    petaWilayah.forEach(w => {
        if (!w.lat || !w.lng) return;
        koordinatWilayah.push([w.lat, w.lng]);
        const radius = 8 + (w.jumlah / maxJumlah) * 18;
        const warna  = warnaIntensitas(w.jumlah);

        L.circleMarker([w.lat, w.lng], {
            radius, color: '#fff', weight: 2,
            fillColor: warna, fillOpacity: .85,
        }).addTo(mapWilayah).bindPopup(`
            <div class="wilayah-popup-head">${w.nama}</div>
            <div class="wilayah-popup-body">
                ${w.wilayah ?? ''}<br>
                <b>${w.jumlah}</b> laporan terverifikasi
            </div>
        `, { maxWidth: 220 });
    });

    if (koordinatWilayah.length > 0) mapWilayah.fitBounds(koordinatWilayah, { padding:[40,40] });
}

function initKondisi() {
    const WARNA_KONDISI = { 'Hidup':'#2196F3', 'Mati Terdampar':'#424242', 'Mati Tertangkap':'#E65100' };
    new Chart(document.getElementById('chartKondisi').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: kondisiData.map(k => k.kondisi),
            datasets: [{
                data: kondisiData.map(k => k.jumlah),
                backgroundColor: kondisiData.map(k => WARNA_KONDISI[k.kondisi] ?? '#999'),
                borderWidth: 3, borderColor: '#fff',
            }]
        },
        options: {
            responsive:true, maintainAspectRatio:false,
            cutout: '68%',
            plugins:{
                legend:{display:false},
                tooltip:{ callbacks:{ label: (ctx) => {
                    const total = ctx.dataset.data.reduce((a,b)=>a+b,0);
                    const pct = total ? Math.round(ctx.parsed / total * 1000) / 10 : 0;
                    return `${ctx.label}: ${ctx.parsed} (${pct}%)`;
                }}}
            }
        }
    });
}

/* Tab pertama (Tren) langsung di-init karena tampil duluan */
initTren();
sudahInit.tren = true;

document.querySelectorAll('.tren-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        const target = btn.dataset.tab;

        document.querySelectorAll('.tren-tab').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.tren-panel').forEach(p => {
            p.style.display = (p.dataset.panel === target) ? '' : 'none';
        });

        if (!sudahInit[target]) {
            if (target === 'wilayah') initWilayah();
            if (target === 'kondisi') initKondisi();
            sudahInit[target] = true;
        } else if (target === 'wilayah' && mapWilayah) {
            mapWilayah.invalidateSize();
        }
    });
});
</script>
@endpush
