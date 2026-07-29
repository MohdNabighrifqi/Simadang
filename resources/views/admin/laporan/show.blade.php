{{-- resources/views/admin/laporan/show.blade.php --}}
@extends('layouts.admin')
@section('title', 'Detail Laporan - Admin')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
#peta-detail{width:100%;height:260px;border-radius:0 0 8px 8px;position:relative;z-index:0;}
.leaflet-pane{z-index:1!important;}
.leaflet-top,.leaflet-bottom{z-index:7!important;}
.info-row{display:flex;flex-direction:column;gap:3px;padding:8px 0;border-bottom:0.5px solid var(--border);}
.info-row:last-child{border:none;}
.info-label{font-size:.72rem;font-weight:600;text-transform:uppercase;letter-spacing:.05em;color:var(--text-muted);}
.info-val{font-size:.875rem;color:var(--text);font-weight:500;}
</style>
@endpush

@section('content')

@include('partials.breadcrumb', ['items' => [
    ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
    ['label' => 'Kelola Laporan', 'route' => route('admin.laporan.index')],
    ['label' => $laporan->kode],
]])

    {{-- Header --}}
    <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
        <div>
            <div class="table-mono" style="font-size:.85rem;color:var(--text-muted);margin-bottom:2px;">{{ $laporan->kode }}</div>
            <h2 style="font-size:1.2rem;margin:0;">
                @if($laporan->jenis?->nama === 'dugong')
                    <i class="fa-solid fa-fish" style="color:var(--primary,#005F73)"></i> Penampakan Dugong
                @else
                    <i class="fa-solid fa-leaf" style="color:var(--amber,#CA6702)"></i> Kondisi Habitat
                @endif
            </h2>
        </div>
        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
            @include('partials.badge-status', ['status' => $laporan->status])
            <a href="{{ route('admin.laporan.edit', $laporan->id) }}"
               class="btn btn-sm"
               style="background:var(--primary-light,#E0F0F4);color:var(--primary,#005F73);border:none;">
                <i class="fa-solid fa-pen"></i> Edit
            </a>
            <form method="POST"
                  action="{{ route('admin.laporan.destroy', $laporan->id) }}"
                  style="margin:0;"
                  onsubmit="return confirm('Hapus laporan {{ $laporan->kode }}?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm"
                        style="background:var(--coral-light);color:var(--coral);border:none;">
                    <i class="fa-solid fa-trash"></i> Hapus
                </button>
            </form>
        </div>
    </div>

    <div class="admin-detail-grid" style="display:grid;grid-template-columns:1fr 340px;gap:1.2rem;align-items:start;">

        {{-- KOLOM KIRI --}}
        <div style="display:flex;flex-direction:column;gap:1rem;">

            {{-- Foto / Video — ditaruh paling atas karena paling membantu admin menilai keaslian laporan --}}
            <div class="card">
                <div class="card-title"><i class="fa-solid fa-image"></i> Foto / Video Bukti</div>
                @if($laporan->fotos && $laporan->fotos->isNotEmpty())
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    @foreach($laporan->fotos as $foto)
                        @if($foto->is_video)
                        <video src="{{ $foto->url }}" controls
                               style="height:280px;max-width:100%;border-radius:8px;border:1px solid var(--border);"></video>
                        @else
                        <img src="{{ $foto->url }}"
                             alt="Foto laporan"
                             style="height:280px;max-width:100%;border-radius:8px;border:1px solid var(--border);object-fit:cover;cursor:pointer;"
                             onclick="window.open(this.src)">
                        @endif
                    @endforeach
                </div>
                @else
                <div style="display:flex;align-items:center;gap:12px;padding:1rem 1.1rem;background:var(--amber-light,#FEF3E2);border:1px solid rgba(202,103,2,.2);border-radius:8px;color:#7A3D00;">
                    <i class="fa-solid fa-triangle-exclamation" style="font-size:1.3rem;flex-shrink:0;"></i>
                    <div style="font-size:.85rem;">
                        <strong>Tidak ada foto/video bukti.</strong> Laporan ini tidak bisa diverifikasi secara visual, pertimbangkan untuk menghubungi pelapor atau menolak jika deskripsi juga kurang meyakinkan.
                    </div>
                </div>
                @endif
            </div>

            {{-- Info Utama --}}
            <div class="card">
                <div class="card-title"><i class="fa-solid fa-circle-info"></i> Informasi Laporan</div>
                <div class="info-grid-2" style="display:grid;grid-template-columns:1fr 1fr;gap:0 1.5rem;">
                    <div class="info-row">
                        <div class="info-label">Tanggal</div>
                        <div class="info-val">{{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d F Y') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Waktu</div>
                        <div class="info-val">
                            {{ $laporan->waktu ? \Carbon\Carbon::parse($laporan->waktu)->format('H:i').' WIB' : '-' }}
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Jenis Laporan</div>
                        <div class="info-val">{{ ucfirst($laporan->jenis?->nama ?? '-') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Kondisi Dugong</div>
                        <div class="info-val">
                            @php $k = $laporan->kondisi?->nama; @endphp
                            @if($k==='hidup') <span style="color:#2196F3;"><i class="fa-solid fa-fish"></i> Hidup</span>
                            @elseif($k==='mati_terdampar') <span style="color:#424242;"><i class="fa-solid fa-triangle-exclamation"></i> Mati Terdampar</span>
                            @elseif($k==='mati_tertangkap') <span style="color:#E65100;"><i class="fa-solid fa-skull-crossbones"></i> Mati Tertangkap</span>
                            @else <span style="color:var(--text-muted);">-</span>
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Jumlah Dugong</div>
                        <div class="info-val">{{ $laporan->jumlah_dugong ? $laporan->jumlah_dugong.' ekor' : '-' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Sumber Data</div>
                        <div class="info-val">{{ $laporan->sumber_data ?? '-' }}</div>
                    </div>
                </div>

                <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid var(--border);">
                    <div class="info-label" style="margin-bottom:6px;">Deskripsi</div>
                    <p style="line-height:1.8;color:var(--text);font-size:.875rem;">{{ $laporan->deskripsi }}</p>
                </div>
            </div>

            {{-- Lokasi & Peta --}}
            <div class="card" style="padding:0;overflow:hidden;">
                <div style="padding:14px 16px;border-bottom:1px solid var(--border);">
                    <div class="card-title" style="margin-bottom:.8rem;">
                        <i class="fa-solid fa-map-pin"></i> Lokasi & Koordinat
                    </div>
                    <div class="info-grid-2" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:0 1.5rem;">
                        <div class="info-row">
                            <div class="info-label">Nama Lokasi</div>
                            <div class="info-val">{{ $laporan->lokasi?->nama ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Wilayah</div>
                            <div class="info-val">{{ $laporan->lokasi?->wilayah ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Keterangan Lokasi</div>
                            <div class="info-val" style="font-size:.78rem;">{{ $laporan->lokasi?->keterangan ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Latitude</div>
                            <div class="info-val" style="font-family:monospace;color:var(--primary,#005F73);">
                                {{ $laporan->latitude ?? $laporan->lokasi?->latitude ?? '-' }}
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Longitude</div>
                            <div class="info-val" style="font-family:monospace;color:var(--primary,#005F73);">
                                {{ $laporan->longitude ?? $laporan->lokasi?->longitude ?? '-' }}
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Koordinat Lengkap</div>
                            @php
                                $lat = $laporan->latitude ?? $laporan->lokasi?->latitude;
                                $lng = $laporan->longitude ?? $laporan->lokasi?->longitude;
                            @endphp
                            @if($lat && $lng)
                            <div class="info-val">
                                <a href="https://maps.google.com/?q={{ $lat }},{{ $lng }}"
                                   target="_blank"
                                   style="color:var(--primary,#005F73);font-size:.78rem;">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                    Buka di Google Maps
                                </a>
                            </div>
                            @else
                            <div class="info-val" style="color:var(--text-muted);">Tidak tersedia</div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Peta --}}
                @if($lat && $lng)
                <div id="peta-detail"></div>
                @else
                <div style="height:120px;display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:.875rem;background:var(--surface,#F8F9FA);">
                    <i class="fa-solid fa-map-location-dot" style="margin-right:8px;opacity:.4;"></i>
                    Koordinat tidak tersedia untuk laporan ini
                </div>
                @endif
            </div>

        </div>

        {{-- KOLOM KANAN --}}
        <div style="display:flex;flex-direction:column;gap:1rem;">

            {{-- Info Pelapor --}}
            <div class="card card-pelapor">
                <div class="card-title" style="margin-bottom:.8rem;"><i class="fa-solid fa-user"></i> Pelapor</div>
                <div class="info-row">
                    <div class="info-label">Nama</div>
                    <div class="info-val">{{ $laporan->nama_pelapor ?? $laporan->user?->name ?? '-' }}</div>
                </div>
                @if($laporan->no_hp)
                <div class="info-row">
                    <div class="info-label">No. HP</div>
                    <div class="info-val">
                        <a href="tel:{{ preg_replace('/[^0-9+]/','',$laporan->no_hp) }}" style="color:var(--primary);">
                            <i class="fa-solid fa-phone"></i> {{ $laporan->no_hp }}
                        </a>
                    </div>
                </div>
                @endif
                @if($laporan->email)
                <div class="info-row">
                    <div class="info-label">Email Pelapor</div>
                    <div class="info-val">
                        <a href="mailto:{{ $laporan->email }}" style="color:var(--primary);">
                            <i class="fa-solid fa-envelope"></i> {{ $laporan->email }}
                        </a>
                    </div>
                </div>
                @endif
                @if($laporan->user)
                <div class="info-row">
                    <div class="info-label">Email Akun</div>
                    <div class="info-val" style="font-size:.8rem;color:var(--text-muted);">{{ $laporan->user->email }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Asal Daerah</div>
                    <div class="info-val">{{ $laporan->user->daerah ?? '-' }}</div>
                </div>
                @endif
            </div>

            {{-- Verifikasi --}}
            <div class="card card-verifikasi" style="border-left:3px solid
                @if($laporan->status==='menunggu') var(--amber,#CA6702)
                @elseif($laporan->status==='terverifikasi') #1D9E75
                @else var(--coral,#D85A30) @endif;">
                <div class="card-title" style="margin-bottom:1rem;">
                    <i class="fa-solid fa-shield-halved"></i> Verifikasi
                </div>

                @if($laporan->status === 'menunggu')
                <div style="display:flex;flex-direction:column;gap:8px;">
                    <form method="POST" action="{{ route('admin.laporan.verify', $laporan->id) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="terverifikasi">
                        <button type="submit" class="btn btn-teal" style="width:100%;justify-content:center;">
                            <i class="fa-solid fa-circle-check"></i> Terima & Verifikasi
                        </button>
                    </form>
                    <div style="border-top:1px solid var(--border);padding-top:8px;">
                        <form method="POST" action="{{ route('admin.laporan.verify', $laporan->id) }}"
                              onsubmit="return confirm('Tolak laporan ini?')">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="ditolak">
                            <textarea name="catatan" class="form-control" rows="2"
                                      placeholder="Alasan penolakan (opsional)..."
                                      style="margin-bottom:6px;font-size:.8rem;resize:none;"></textarea>
                            <button type="submit" class="btn btn-sm"
                                    style="width:100%;justify-content:center;background:var(--coral-light);color:var(--coral);border:none;">
                                <i class="fa-solid fa-circle-xmark"></i> Tolak Laporan
                            </button>
                        </form>
                    </div>
                </div>

                @elseif($laporan->status === 'terverifikasi')
                <div style="text-align:center;padding:.5rem 0;">
                    <i class="fa-solid fa-circle-check" style="font-size:2rem;color:#1D9E75;display:block;margin-bottom:.5rem;"></i>
                    <div style="font-weight:500;color:#1D9E75;">Laporan Terverifikasi</div>
                    <div style="font-size:.75rem;color:var(--text-muted);margin-top:4px;">Laporan ini sudah tampil di peta</div>
                </div>
                <form method="POST" action="{{ route('admin.laporan.verify', $laporan->id) }}"
                      style="margin-top:10px;"
                      onsubmit="return confirm('Batalkan verifikasi laporan ini?')">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="menunggu">
                    <button type="submit" class="btn btn-sm btn-gray" style="width:100%;justify-content:center;">
                        <i class="fa-solid fa-rotate-left"></i> Batalkan Verifikasi
                    </button>
                </form>

                @else
                <div style="text-align:center;padding:.5rem 0;">
                    <i class="fa-solid fa-circle-xmark" style="font-size:2rem;color:var(--coral);display:block;margin-bottom:.5rem;"></i>
                    <div style="font-weight:500;color:var(--coral);">Laporan Ditolak</div>
                    @if($laporan->catatan)
                    <div style="font-size:.78rem;color:var(--text-muted);margin-top:6px;text-align:left;background:var(--surface);padding:8px 10px;border-radius:6px;">
                        {{ $laporan->catatan }}
                    </div>
                    @endif
                </div>
                <form method="POST" action="{{ route('admin.laporan.verify', $laporan->id) }}"
                      style="margin-top:10px;">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="menunggu">
                    <button type="submit" class="btn btn-sm btn-gray" style="width:100%;justify-content:center;">
                        <i class="fa-solid fa-rotate-left"></i> Kembalikan ke Menunggu
                    </button>
                </form>
                @endif
            </div>

            {{-- Meta --}}
            <div class="card card-meta" style="background:var(--surface,#F8F9FA);">
                <div class="info-row">
                    <div class="info-label">Dibuat</div>
                    <div class="info-val" style="font-size:.8rem;">
                        {{ \Carbon\Carbon::parse($laporan->created_at)->translatedFormat('d M Y, H:i') }}
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Terakhir Diperbarui</div>
                    <div class="info-val" style="font-size:.8rem;">
                        {{ \Carbon\Carbon::parse($laporan->updated_at)->translatedFormat('d M Y, H:i') }}
                    </div>
                </div>
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
    $kondisiNama = $laporan->kondisi?->nama ?? 'hidup';
@endphp
@if($lat && $lng)
(function() {
    const lat = {{ $lat }};
    const lng = {{ $lng }};
    const kondisi = '{{ $kondisiNama }}';

    const map = L.map('peta-detail', {
        center: [lat, lng],
        zoom: 13,
        zoomControl: true,
        scrollWheelZoom: false,
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap',
        maxZoom: 19,
    }).addTo(map);

    // Warna marker sesuai kondisi
    const warna = kondisi === 'hidup' ? '#2196F3'
                : kondisi === 'mati_terdampar' ? '#2C2C2C'
                : '#E65100';

    const icon = L.divIcon({
        html: `<svg xmlns="http://www.w3.org/2000/svg" width="32" height="40" viewBox="0 0 32 40">
            <path d="M16 0C7.16 0 0 7.16 0 16C0 28 16 40 16 40S32 28 32 16C32 7.16 24.84 0 16 0Z"
                  fill="${warna}" stroke="#fff" stroke-width="2"/>
            <circle cx="16" cy="16" r="6" fill="rgba(255,255,255,0.9)"/>
        </svg>`,
        className: '',
        iconSize: [32, 40],
        iconAnchor: [16, 40],
        popupAnchor: [0, -42],
    });

    L.marker([lat, lng], { icon })
     .addTo(map)
     .bindPopup(`<b>{{ $laporan->kode }}</b><br>{{ $laporan->lokasi?->nama ?? 'Lokasi' }}<br><small style="font-family:monospace">${lat.toFixed(6)}, ${lng.toFixed(6)}</small>`)
     .openPopup();
})();
@endif
</script>
@endpush