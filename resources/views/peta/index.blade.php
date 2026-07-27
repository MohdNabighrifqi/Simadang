{{-- resources/views/peta/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Peta Habitat')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
#peta-dugong{width:100%;height:580px;position:relative;z-index:0;}
.leaflet-pane,.leaflet-tile-pane,.leaflet-overlay-pane{z-index:1!important;}
.leaflet-marker-pane{z-index:4!important;}
.leaflet-tooltip-pane{z-index:5!important;}
.leaflet-popup-pane{z-index:6!important;}
.leaflet-top,.leaflet-bottom{z-index:7!important;}
.leaflet-control{z-index:8!important;}
.leaflet-popup-content-wrapper{border-radius:10px!important;box-shadow:0 4px 20px rgba(0,0,0,.18)!important;padding:0!important;overflow:hidden;}
.leaflet-popup-content{margin:0!important;min-width:210px;}
.popup-head{padding:9px 14px;color:#fff;font-size:13px;font-weight:700;}
.popup-body{padding:10px 14px;font-size:12px;}
.popup-row{display:flex;justify-content:space-between;padding:3px 0;border-bottom:.5px solid #f0efe8;font-size:12px;}
.popup-row:last-child{border:none;}
.popup-row .lbl{color:#888;}
.popup-row .val{font-weight:600;}
.peta-filter{display:flex;gap:8px;padding:10px 14px;background:var(--surface,#F8F9FA);border-bottom:1px solid var(--border);flex-wrap:wrap;align-items:center;}
.peta-filter-label{font-size:.78rem;color:var(--text-muted);font-weight:600;}
.filter-cat{display:inline-flex;align-items:center;gap:6px;padding:5px 13px;border-radius:20px;font-size:.76rem;font-weight:600;cursor:pointer;border:1.5px solid transparent;transition:all .18s;user-select:none;}
.filter-cat.hidup{border-color:#2196F3;color:#1565C0;background:#E3F2FD;}
.filter-cat.terdampar{border-color:#424242;color:#212121;background:#F5F5F5;}
.filter-cat.tertangkap{border-color:#E65100;color:#BF360C;background:#FBE9E7;}
.filter-cat.off{opacity:.4;}
.peta-legend{display:flex;gap:1.2rem;flex-wrap:wrap;padding:10px 14px;background:#fff;border-top:1px solid var(--border);font-size:.78rem;color:var(--text-muted);align-items:center;}
.legend-item{display:flex;align-items:center;gap:7px;}
.peta-statbar{display:grid;grid-template-columns:repeat(4,1fr);gap:1px;background:var(--border);border-bottom:1px solid var(--border);}
.peta-stat{background:#fff;padding:8px 14px;display:flex;align-items:center;gap:8px;font-size:.78rem;}
.peta-stat .num{font-size:1.2rem;font-weight:700;font-family:'Manrope','DM Sans',sans-serif;}
.peta-stat .lbl{color:var(--text-muted);font-size:.72rem;line-height:1.3;}
</style>
@endpush

@section('content')
<div class="container">
    <div class="page-header">
        <h2>Peta Sebaran Habitat Dugong</h2>
        <p>Setiap ikon mewakili satu titik pengamatan dugong di perairan Bintan, Kepulauan Riau.</p>
    </div>

    <div class="card" style="padding:0;overflow:hidden;margin-bottom:1.5rem;">

        {{-- Stat bar --}}
        <div class="peta-statbar">
            <div class="peta-stat">
                <div class="num" style="color:#2196F3;">{{ $stats['hidup'] }}</div>
                <div class="lbl">Dugong<br>Hidup</div>
            </div>
            <div class="peta-stat">
                <div class="num" style="color:#212121;">{{ $stats['terdampar'] }}</div>
                <div class="lbl">Mati<br>Terdampar</div>
            </div>
            <div class="peta-stat">
                <div class="num" style="color:#E65100;">{{ $stats['tertangkap'] }}</div>
                <div class="lbl">Mati<br>Tertangkap</div>
            </div>
            <div class="peta-stat">
                <div class="num" style="color:var(--primary,#005F73);">{{ $stats['total'] }}</div>
                <div class="lbl">Total<br>Data</div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="peta-filter">
            <span class="peta-filter-label">Tampilkan:</span>
            <span class="filter-cat hidup"      data-cat="hidup"      onclick="toggleFilter(this)">Dugong Hidup</span>
            <span class="filter-cat terdampar"  data-cat="terdampar"  onclick="toggleFilter(this)">Mati Terdampar</span>
            <span class="filter-cat tertangkap" data-cat="tertangkap" onclick="toggleFilter(this)">Mati Tertangkap</span>
        </div>

        {{-- Peta --}}
        <div id="peta-dugong"></div>

        {{-- Legend --}}
        <div class="peta-legend">
            <div class="legend-item">
                <div style="width:13px;height:13px;border-radius:50%;background:#5BB8F5;border:2px solid #fff;box-shadow:0 1px 3px rgba(0,0,0,.2);"></div>
                <span>Hidup</span>
            </div>
            <div class="legend-item">
                <div style="width:13px;height:13px;border-radius:50%;background:#2C2C2C;border:2px solid #fff;box-shadow:0 1px 3px rgba(0,0,0,.2);"></div>
                <span>Mati Terdampar</span>
            </div>
            <div class="legend-item">
                <div style="width:13px;height:13px;border-radius:50%;background:#D4956A;border:2px solid #fff;box-shadow:0 1px 3px rgba(0,0,0,.2);"></div>
                <span>Mati Tertangkap</span>
            </div>
            <span style="margin-left:auto;font-size:.72rem;opacity:.6;">Setiap ikon = 1 titik pengamatan · OpenStreetMap</span>
        </div>
    </div>

    {{-- Tabel rekap --}}
    <div class="card card-flush">
        <div class="card-header">
            <span class="card-title" style="margin-bottom:0;">Rekap per Lokasi</span>
        </div>
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Lokasi</th>
                        <th style="text-align:center;color:#2196F3;">Hidup</th>
                        <th style="text-align:center;color:#424242;">Mati Terdampar</th>
                        <th style="text-align:center;color:#E65100;">Mati Tertangkap</th>
                        <th style="text-align:center;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekapLokasi as $r)
                    <tr>
                        <td><strong>{{ $r['lokasi'] }}</strong></td>
                        <td style="text-align:center;font-weight:600;color:#2196F3;">{{ $r['hidup'] }}</td>
                        <td style="text-align:center;font-weight:600;color:#424242;">{{ $r['terdampar'] }}</td>
                        <td style="text-align:center;font-weight:600;color:#E65100;">{{ $r['tertangkap'] }}</td>
                        <td style="text-align:center;font-weight:700;">{{ $r['total'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const laporanData = @json($laporanPeta);

const map = L.map('peta-dugong', {
    center: [0.9800, 104.5000],
    zoom: 10, minZoom: 8, maxZoom: 18,
    maxBounds: [[-2, 100], [5, 110]],
    maxBoundsViscosity: 1.0,
});
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© <a href="https://openstreetmap.org">OpenStreetMap</a>',
    maxZoom: 19,
}).addTo(map);

/* ── SVG icon dugong per kondisi ── */
function buatIcon(kondisi) {
    let warnaTubuh, warnaGaris, warnaEkor, rotation;
    if (kondisi === 'hidup') {
        warnaTubuh = '#5BB8F5'; warnaGaris = '#1565C0'; warnaEkor = '#42A5F5'; rotation = '0';
    } else if (kondisi === 'mati_terdampar') {
        warnaTubuh = '#2C2C2C'; warnaGaris = '#000'; warnaEkor = '#1A1A1A'; rotation = '180';
    } else {
        warnaTubuh = '#D4956A'; warnaGaris = '#BF360C'; warnaEkor = '#C47A4A'; rotation = '15';
    }
    const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="44" height="30" viewBox="0 0 44 30"
        style="transform:rotate(${rotation}deg);filter:drop-shadow(1px 1px 2px rgba(0,0,0,.35))">
        <ellipse cx="20" cy="15" rx="17" ry="9" fill="${warnaTubuh}" stroke="${warnaGaris}" stroke-width="1.2"/>
        <ellipse cx="4" cy="15" rx="5" ry="6" fill="${warnaTubuh}" stroke="${warnaGaris}" stroke-width="1"/>
        <ellipse cx="7" cy="15" rx="7" ry="7.5" fill="${warnaTubuh}" stroke="${warnaGaris}" stroke-width="1"/>
        <path d="M22 6 Q26 1 30 6" fill="${warnaEkor}" stroke="${warnaGaris}" stroke-width="1"/>
        <path d="M37 11 Q44 6 43 15 Q44 24 37 19 Q40 15 37 11Z" fill="${warnaEkor}" stroke="${warnaGaris}" stroke-width="1"/>
        <circle cx="7" cy="12" r="1.5" fill="#fff"/>
        <circle cx="7" cy="12" r="0.8" fill="#333"/>
    </svg>`;
    return L.divIcon({ html: svg, className:'', iconSize:[44,30], iconAnchor:[22,15], popupAnchor:[0,-18] });
}

const WARNA = { hidup:'#1565C0', mati_terdampar:'#212121', mati_tertangkap:'#BF360C' };
const LABEL = { hidup:'Dugong Hidup', mati_terdampar:'Mati Terdampar', mati_tertangkap:'Mati Tertangkap' };

const layers = {
    hidup:      L.layerGroup().addTo(map),
    terdampar:  L.layerGroup().addTo(map),
    tertangkap: L.layerGroup().addTo(map),
};

laporanData.forEach(lap => {
    if (!lap.latitude || !lap.longitude) return;

    /* kondisi sekarang dari field 'kondisi' (nama dari tabel kondisi via FK) */
    const kondisiNama = lap.kondisi || 'hidup';
    const cat = kondisiNama === 'mati_terdampar'  ? 'terdampar'
              : kondisiNama === 'mati_tertangkap' ? 'tertangkap'
              : 'hidup';

    const marker = L.marker([lap.latitude, lap.longitude], { icon: buatIcon(kondisiNama) });
    marker.bindPopup(`
        <div class="popup-head" style="background:${WARNA[kondisiNama] ?? '#005F73'};">${LABEL[kondisiNama] ?? kondisiNama}</div>
        <div class="popup-body">
            <div class="popup-row"><span class="lbl">Kode</span><span class="val" style="font-family:monospace;">${lap.kode}</span></div>
            <div class="popup-row"><span class="lbl">Tahun</span><span class="val">${lap.tahun}</span></div>
            <div class="popup-row"><span class="lbl">Lokasi</span><span class="val">${lap.lokasi}</span></div>
            <div class="popup-row"><span class="lbl">Sumber</span><span class="val">${lap.sumber}</span></div>
            <div class="popup-row">
                <span class="lbl">Koordinat</span>
                <span class="val" style="font-family:monospace;font-size:10px;">
                    ${parseFloat(lap.latitude).toFixed(4)}, ${parseFloat(lap.longitude).toFixed(4)}
                </span>
            </div>
        </div>
    `, { maxWidth: 240 });
    layers[cat].addLayer(marker);
});

/* Auto fit */
const allCoords = laporanData.filter(l => l.latitude && l.longitude).map(l => [l.latitude, l.longitude]);
if (allCoords.length > 0) map.fitBounds(allCoords, { padding: [40, 40] });

/* Filter toggle */
const filterState = { hidup: true, terdampar: true, tertangkap: true };
function toggleFilter(el) {
    const cat = el.dataset.cat;
    filterState[cat] = !filterState[cat];
    el.classList.toggle('off', !filterState[cat]);
    filterState[cat] ? layers[cat].addTo(map) : map.removeLayer(layers[cat]);
}
</script>
@endpush
