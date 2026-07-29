{{-- resources/views/admin/laporan/edit.blade.php --}}
@extends('layouts.admin')
@section('title','Edit Laporan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
#map-edit{width:100%;height:260px;z-index:0;border-radius:0 0 8px 8px;}
.leaflet-pane{z-index:1!important;}
.leaflet-top,.leaflet-bottom{z-index:7!important;}
.map-head{background:var(--primary);color:#fff;padding:8px 14px;font-size:.8rem;font-weight:500;display:flex;justify-content:space-between;border-radius:8px 8px 0 0;}
.coord-bar{display:flex;gap:8px;padding:8px 12px;background:#f8fcfb;border-top:1px solid var(--border);flex-wrap:wrap;align-items:center;}
.coord-chip{display:flex;align-items:center;gap:5px;background:#fff;border:1px solid var(--border-md);border-radius:6px;padding:5px 10px;flex:1;min-width:120px;font-size:.78rem;}
.coord-chip label{font-size:.65rem;color:var(--text-muted);text-transform:uppercase;font-weight:700;flex-shrink:0;}
.coord-chip .val{font-family:monospace;color:var(--primary);font-weight:600;}
</style>
@endpush

@section('content')

@include('partials.breadcrumb', ['items' => [
    ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
    ['label' => 'Kelola Laporan', 'route' => route('admin.laporan.index')],
    ['label' => 'Edit ' . $laporan->kode],
]])

    <div class="card">
        <div class="card-title" style="margin-bottom:1.2rem;">Edit Laporan - {{ $laporan->kode }}</div>

        <form method="POST" action="{{ route('admin.laporan.update', $laporan->id) }}">
            @csrf @method('PUT')

            <div class="form-grid">

                <div class="form-group">
                    <label class="form-label">Jenis <span class="required">*</span></label>
                    <select name="jenis_id" class="form-control @error('jenis_id') is-invalid @enderror">
                        @foreach($jenisList as $j)
                        <option value="{{ $j->id }}" {{ old('jenis_id',$laporan->jenis_id)==$j->id?'selected':'' }}>
                            {{ ucfirst($j->nama) }}
                        </option>
                        @endforeach
                    </select>
                    @error('jenis_id')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Kondisi</label>
                    <select name="kondisi_id" class="form-control @error('kondisi_id') is-invalid @enderror">
                        <option value="">- Tidak ada kondisi (Habitat) -</option>
                        @foreach($kondisiList as $k)
                        <option value="{{ $k->id }}" {{ old('kondisi_id',$laporan->kondisi_id)==$k->id?'selected':'' }}>
                            {{ ucfirst(str_replace('_',' ',$k->nama)) }}
                        </option>
                        @endforeach
                    </select>
                    @error('kondisi_id')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Lokasi</label>
                    <select name="lokasi_id" id="lokasi_id_select" class="form-control @error('lokasi_id') is-invalid @enderror">
                        <option value="">- Pilih Lokasi -</option>
                        @foreach($lokasiList as $l)
                        <option value="{{ $l->id }}" {{ old('lokasi_id',$laporan->lokasi_id)==$l->id?'selected':'' }}>
                            {{ $l->nama }}
                        </option>
                        @endforeach
                    </select>
                    <div style="font-size:.72rem;color:var(--text-muted);margin-top:3px;">Otomatis menyesuaikan lokasi terdekat saat titik koordinat diubah, tetap bisa dipilih manual.</div>
                    @error('lokasi_id')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Status <span class="required">*</span></label>
                    <select name="status" class="form-control @error('status') is-invalid @enderror">
                        <option value="menunggu"      {{ old('status',$laporan->status)==='menunggu'      ?'selected':'' }}>Menunggu</option>
                        <option value="terverifikasi" {{ old('status',$laporan->status)==='terverifikasi' ?'selected':'' }}>Terverifikasi</option>
                        <option value="ditolak"       {{ old('status',$laporan->status)==='ditolak'       ?'selected':'' }}>Ditolak</option>
                    </select>
                    @error('status')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal <span class="required">*</span></label>
                    <input type="date" name="tanggal"
                           class="form-control @error('tanggal') is-invalid @enderror"
                           value="{{ old('tanggal',$laporan->tanggal?->format('Y-m-d')) }}">
                    @error('tanggal')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Waktu</label>
                    <input type="time" name="waktu"
                           class="form-control @error('waktu') is-invalid @enderror"
                           value="{{ old('waktu', $laporan->waktu ? \Carbon\Carbon::parse($laporan->waktu)->format('H:i') : '') }}">
                    @error('waktu')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Jumlah Dugong</label>
                    <input type="number" name="jumlah_dugong" min="1"
                           class="form-control @error('jumlah_dugong') is-invalid @enderror"
                           value="{{ old('jumlah_dugong',$laporan->jumlah_dugong) }}">
                    @error('jumlah_dugong')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Sumber Data</label>
                    <input type="text" name="sumber_data"
                           class="form-control @error('sumber_data') is-invalid @enderror"
                           value="{{ old('sumber_data',$laporan->sumber_data) }}"
                           placeholder="cth: Kuesioner / BPSPL">
                    @error('sumber_data')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Pelapor <span style="font-weight:400;color:var(--text-muted);">(opsional, kosong untuk data historis)</span></label>
                    <input type="text" name="nama_pelapor"
                           class="form-control @error('nama_pelapor') is-invalid @enderror"
                           value="{{ old('nama_pelapor',$laporan->nama_pelapor) }}">
                    @error('nama_pelapor')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">No. HP <span style="font-weight:400;color:var(--text-muted);">(opsional, kosong untuk data historis)</span></label>
                    <input type="text" name="no_hp"
                           class="form-control @error('no_hp') is-invalid @enderror"
                           value="{{ old('no_hp',$laporan->no_hp) }}" placeholder="cth: 0812xxxxxxx">
                    @error('no_hp')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email <span style="font-weight:400;color:var(--text-muted);">(opsional)</span></label>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email',$laporan->email) }}" placeholder="email@example.com">
                    @error('email')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group col-2">
                    <label class="form-label">Deskripsi <span class="required">*</span></label>
                    <textarea name="deskripsi" rows="4"
                              class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi',$laporan->deskripsi) }}</textarea>
                    @error('deskripsi')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group col-2">
                    <label class="form-label">Catatan Admin</label>
                    <textarea name="catatan" rows="2"
                              class="form-control @error('catatan') is-invalid @enderror"
                              placeholder="Catatan untuk pelapor...">{{ old('catatan',$laporan->catatan) }}</textarea>
                    @error('catatan')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                {{-- MAP PICKER KOORDINAT --}}
                <div class="form-group col-2">
                    <label class="form-label">
                        Titik Koordinat GPS
                        <span style="font-weight:400;color:var(--text-muted);">(klik peta untuk ubah posisi marker)</span>
                    </label>
                    <input type="hidden" name="latitude"  id="lat_input" value="{{ old('latitude',$laporan->latitude) }}">
                    <input type="hidden" name="longitude" id="lng_input" value="{{ old('longitude',$laporan->longitude) }}">

                    <div style="border:1px solid var(--border-md);border-radius:8px;overflow:hidden;">
                        <div class="map-head">
                            <span>Klik peta untuk pindahkan titik koordinat</span>
                            <span style="opacity:.75;font-size:.72rem;font-weight:400;">Geser marker untuk sesuaikan</span>
                        </div>
                        <div id="map-edit"></div>
                        <div class="coord-bar">
                            <button type="button" class="btn btn-sm" onclick="gunakanLokasiSayaEdit()" id="btn-gps-edit"
                                    style="background:var(--primary-light,#E0F0F4);color:var(--primary,#005F73);border:none;">
                                <i class="fa-solid fa-location-crosshairs"></i> Gunakan Lokasi Saya
                            </button>
                            <div class="coord-chip">
                                <label>Lat</label>
                                <span class="val" id="lat-disp">{{ $laporan->latitude ?? '-' }}</span>
                            </div>
                            <div class="coord-chip">
                                <label>Lng</label>
                                <span class="val" id="lng-disp">{{ $laporan->longitude ?? '-' }}</span>
                            </div>
                            <button type="button" class="btn btn-gray btn-sm" onclick="resetKoord()" id="btn-reset">
                                Hapus Koordinat
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-teal">Simpan Perubahan</button>
                <a href="{{ route('admin.laporan.index') }}" class="btn btn-gray">Batal</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const initLat = {{ $laporan->latitude ?? ($laporan->lokasi?->latitude ?? 0.98) }};
const initLng = {{ $laporan->longitude ?? ($laporan->lokasi?->longitude ?? 104.53) }};
const hasCoord = {{ ($laporan->latitude && $laporan->longitude) ? 'true' : 'false' }};

const lokasiKoordinatEdit = @json(
    $lokasiList->filter(fn($l) => $l->latitude && $l->longitude)
               ->mapWithKeys(fn($l) => [$l->id => [(float) $l->latitude, (float) $l->longitude]])
);

function jarakKmEdit(lat1, lng1, lat2, lng2) {
    const R = 6371;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a = Math.sin(dLat/2) ** 2 + Math.cos(lat1 * Math.PI/180) * Math.cos(lat2 * Math.PI/180) * Math.sin(dLng/2) ** 2;
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}

function pilihLokasiTerdekat(lat, lng) {
    let terdekatId = null, jarakTerpendek = Infinity;
    for (const id in lokasiKoordinatEdit) {
        const [lokLat, lokLng] = lokasiKoordinatEdit[id];
        const jarak = jarakKmEdit(lat, lng, lokLat, lokLng);
        if (jarak < jarakTerpendek) { jarakTerpendek = jarak; terdekatId = id; }
    }
    if (terdekatId !== null) {
        document.getElementById('lokasi_id_select').value = terdekatId;
    }
}

const map = L.map('map-edit',{
    center:[initLat, initLng], zoom: hasCoord ? 14 : 10,
    scrollWheelZoom:false
});
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
    attribution:'© OpenStreetMap', maxZoom:19
}).addTo(map);

const icon = L.divIcon({
    html:`<svg xmlns="http://www.w3.org/2000/svg" width="28" height="36" viewBox="0 0 28 36">
        <path d="M14 0C6.27 0 0 6.27 0 14C0 24.5 14 36 14 36S28 24.5 28 14C28 6.27 21.73 0 14 0Z"
              fill="#005F73" stroke="#fff" stroke-width="2"/>
        <circle cx="14" cy="14" r="5" fill="rgba(255,255,255,.9)"/>
    </svg>`,
    className:'', iconSize:[28,36], iconAnchor:[14,36]
});

let marker = null;

function fmt(n){ return parseFloat(n).toFixed(6); }
function setCoord(lat, lng){
    document.getElementById('lat_input').value = fmt(lat);
    document.getElementById('lng_input').value = fmt(lng);
    document.getElementById('lat-disp').textContent = fmt(lat);
    document.getElementById('lng-disp').textContent = fmt(lng);
    pilihLokasiTerdekat(lat, lng);
}

function gunakanLokasiSayaEdit(){
    if (!navigator.geolocation) {
        alert('Perangkat/browser Anda tidak mendukung GPS.');
        return;
    }
    const btn = document.getElementById('btn-gps-edit');
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mencari lokasi...';

    navigator.geolocation.getCurrentPosition(
        pos => {
            const { latitude, longitude } = pos.coords;
            map.setView([latitude, longitude], 14);
            if (marker) map.removeLayer(marker);
            marker = L.marker([latitude, longitude], { icon, draggable: true }).addTo(map);
            setCoord(latitude, longitude);
            marker.on('dragend', ev => {
                const p = ev.target.getLatLng();
                setCoord(p.lat, p.lng);
            });
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        },
        () => {
            alert('Tidak bisa mengambil lokasi Anda. Pastikan izin GPS diaktifkan.');
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        },
        { enableHighAccuracy: true, timeout: 10000 }
    );
}
function resetKoord(){
    document.getElementById('lat_input').value = '';
    document.getElementById('lng_input').value = '';
    document.getElementById('lat-disp').textContent = '-';
    document.getElementById('lng-disp').textContent = '-';
    if(marker){ map.removeLayer(marker); marker = null; }
}

// Pasang marker awal jika ada koordinat
if(hasCoord){
    marker = L.marker([initLat, initLng],{icon, draggable:true}).addTo(map);
    marker.on('dragend', ev=>{
        const p = ev.target.getLatLng();
        setCoord(p.lat, p.lng);
    });
}

// Klik peta untuk pindah marker
map.on('click', e=>{
    if(marker) map.removeLayer(marker);
    marker = L.marker([e.latlng.lat, e.latlng.lng],{icon, draggable:true}).addTo(map);
    setCoord(e.latlng.lat, e.latlng.lng);
    marker.on('dragend', ev=>{
        const p = ev.target.getLatLng();
        setCoord(p.lat, p.lng);
    });
});
</script>
@endpush
