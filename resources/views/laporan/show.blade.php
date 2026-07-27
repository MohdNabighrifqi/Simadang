{{-- resources/views/laporan/show.blade.php --}}
@extends('layouts.app')
@section('title','Detail Laporan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
#peta-detail{width:100%;height:240px;z-index:0;border-radius:0 0 8px 8px;}
.leaflet-pane{z-index:1!important;}
.leaflet-top,.leaflet-bottom{z-index:7!important;}
</style>
@endpush

@section('content')
<div class="container">

    @include('partials.breadcrumb', ['items' => [
        ['label' => 'Beranda', 'route' => route('beranda')],
        ['label' => 'Laporan', 'route' => route('laporan.index')],
        ['label' => $laporan->kode],
    ]])

    {{-- Header --}}
    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
        <div>
            <div class="table-mono" style="font-size:.85rem;margin-bottom:4px;">{{ $laporan->kode }}</div>
            <h2 style="font-size:1.3rem;">
                @if($laporan->jenis?->nama === 'dugong')
                    Penampakan Dugong
                @else
                    Kondisi Habitat
                @endif
            </h2>
        </div>
        @include('partials.badge-status', ['status' => $laporan->status])
    </div>

    <div class="detail-grid" style="--sidebar-w:340px;">

        {{-- Kolom Kiri --}}
        <div style="display:flex;flex-direction:column;gap:1rem;">

            {{-- Info Laporan --}}
            <div class="card">
                <div class="card-title">Informasi Laporan</div>
                <div class="detail-grid-cols2">
                    <div class="info-row">
                        <div class="info-label">Tanggal</div>
                        <div class="info-val">{{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d F Y') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Waktu</div>
                        <div class="info-val">
                            {{ $laporan->waktu ? \Carbon\Carbon::parse($laporan->waktu)->format('H:i').' WIB' : '—' }}
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Jenis Laporan</div>
                        <div class="info-val">{{ ucfirst($laporan->jenis?->nama ?? '—') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Kondisi Dugong</div>
                        <div class="info-val">
                            @php $k = $laporan->kondisi?->nama; @endphp
                            @if($k==='hidup') <span style="color:#0A9396;font-weight:600;">Hidup</span>
                            @elseif($k==='mati_terdampar') <span style="color:#374151;font-weight:600;">Mati Terdampar</span>
                            @elseif($k==='mati_tertangkap') <span style="color:#AE2012;font-weight:600;">Mati Tertangkap</span>
                            @else <span style="color:var(--text-muted);">—</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Jumlah Dugong</div>
                        <div class="info-val">{{ $laporan->jumlah_dugong ? $laporan->jumlah_dugong.' ekor' : '—' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Lokasi</div>
                        <div class="info-val">{{ $laporan->lokasi?->nama ?? '—' }}</div>
                    </div>
                    @if($laporan->sumber_data)
                    <div class="info-row info-row-full">
                        <div class="info-label">Sumber Data</div>
                        <div class="info-val">{{ $laporan->sumber_data }}</div>
                    </div>
                    @endif
                </div>

                <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid var(--border);">
                    <div class="info-label" style="margin-bottom:6px;">Deskripsi</div>
                    <p style="font-size:.875rem;line-height:1.8;color:var(--text);">{{ $laporan->deskripsi }}</p>
                </div>
            </div>

            {{-- Peta Lokasi --}}
            @php
                $lat = $laporan->latitude ?? $laporan->lokasi?->latitude;
                $lng = $laporan->longitude ?? $laporan->lokasi?->longitude;
            @endphp
            <div class="card card-flush">
                <div style="padding:14px 16px;border-bottom:1px solid var(--border);">
                    <div class="card-title" style="margin-bottom:.5rem;">Lokasi & Koordinat</div>
                    <div class="detail-grid-cols3">
                        <div class="info-row">
                            <div class="info-label">Nama Lokasi</div>
                            <div class="info-val">{{ $laporan->lokasi?->nama ?? '—' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Wilayah</div>
                            <div class="info-val">{{ $laporan->lokasi?->wilayah ?? '—' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Koordinat GPS</div>
                            <div class="info-val" style="font-family:monospace;font-size:.82rem;color:var(--primary);">
                                @if($lat && $lng)
                                    {{ number_format($lat,6) }}, {{ number_format($lng,6) }}
                                @else
                                    —
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($lat && $lng)
                    <a href="https://maps.google.com/?q={{ $lat }},{{ $lng }}" target="_blank"
                       class="btn btn-gray btn-sm" style="margin-top:8px;">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka di Google Maps
                    </a>
                    @endif
                </div>

                @if($lat && $lng)
                <div id="peta-detail"></div>
                @else
                <div style="height:120px;display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:.875rem;background:var(--bg);">
                    Koordinat tidak tersedia untuk laporan ini
                </div>
                @endif
            </div>

            {{-- Foto / Video --}}
            @if($laporan->fotos && $laporan->fotos->isNotEmpty())
            <div class="card">
                <div class="card-title">Foto / Video Bukti</div>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    @foreach($laporan->fotos as $foto)
                        @if($foto->is_video)
                        <video src="{{ $foto->url }}" controls
                               style="height:150px;border-radius:8px;border:1px solid var(--border);"></video>
                        @else
                        <img src="{{ $foto->url }}"
                             alt="Foto laporan"
                             style="height:150px;border-radius:8px;border:1px solid var(--border);object-fit:cover;cursor:pointer;"
                             onclick="window.open(this.src)">
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- Kolom Kanan --}}
        <div style="display:flex;flex-direction:column;gap:1rem;">

            {{-- Info Pelapor --}}
            <div class="card">
                <div class="card-title" style="font-size:.875rem;">Pelapor</div>
                <div class="info-row">
                    <div class="info-label">Nama</div>
                    <div class="info-val">{{ $laporan->nama_pelapor ?? $laporan->user?->name ?? '—' }}</div>
                </div>
                @if($laporan->no_hp)
                <div class="info-row">
                    <div class="info-label">No. HP</div>
                    <div class="info-val">{{ $laporan->no_hp }}</div>
                </div>
                @endif
                @if($laporan->email)
                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-val">{{ $laporan->email }}</div>
                </div>
                @endif
                <div class="info-row">
                    <div class="info-label">Dikirim</div>
                    <div class="info-val" style="font-size:.82rem;">
                        {{ \Carbon\Carbon::parse($laporan->created_at)->translatedFormat('d M Y, H:i') }}
                    </div>
                </div>
            </div>

            {{-- Catatan admin --}}
            @if($laporan->catatan)
            <div class="card" style="border-left:3px solid var(--amber);">
                <div class="card-title" style="font-size:.875rem;color:var(--amber);">Catatan Admin</div>
                <p style="font-size:.875rem;color:var(--text-muted);line-height:1.7;">{{ $laporan->catatan }}</p>
            </div>
            @endif

            {{-- Kembali --}}
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('laporan.index') }}"
               class="btn btn-gray" style="justify-content:center;">
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
@php
    $lat = $laporan->latitude ?? $laporan->lokasi?->latitude;
    $lng = $laporan->longitude ?? $laporan->lokasi?->longitude;
    $k   = $laporan->kondisi?->nama ?? 'hidup';
@endphp
@if($lat && $lng)
(function(){
    const lat = {{ $lat }};
    const lng = {{ $lng }};
    const kondisi = '{{ $k }}';
    const colors = {hidup:'#0A9396', mati_terdampar:'#374151', mati_tertangkap:'#AE2012'};
    const c = colors[kondisi] || '#005F73';

    const map = L.map('peta-detail',{
        center:[lat,lng], zoom:13,
        zoomControl:true, scrollWheelZoom:false
    });
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
        attribution:'© OpenStreetMap', maxZoom:19
    }).addTo(map);

    const icon = L.divIcon({
        html:`<svg xmlns="http://www.w3.org/2000/svg" width="28" height="36" viewBox="0 0 28 36">
            <path d="M14 0C6.27 0 0 6.27 0 14C0 24.5 14 36 14 36S28 24.5 28 14C28 6.27 21.73 0 14 0Z"
                  fill="${c}" stroke="#fff" stroke-width="2"/>
            <circle cx="14" cy="14" r="5" fill="rgba(255,255,255,.9)"/>
        </svg>`,
        className:'', iconSize:[28,36], iconAnchor:[14,36], popupAnchor:[0,-38]
    });
    L.marker([lat,lng],{icon})
     .addTo(map)
     .bindPopup('<b>{{ $laporan->kode }}</b><br>{{ $laporan->lokasi?->nama ?? "" }}')
     .openPopup();
})();
@endif
</script>
@endpush
