{{-- resources/views/laporan/create.blade.php --}}
@extends('layouts.app')
@section('title', 'Buat Laporan')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
.type-toggle{display:flex;gap:8px;margin-bottom:1.5rem;flex-wrap:wrap;}
.type-btn{padding:9px 18px;border-radius:var(--radius-sm,8px);border:1.5px solid var(--border-md,rgba(0,0,0,.12));background:none;font-family:'Hanken Grotesk','DM Sans',sans-serif;font-size:.875rem;cursor:pointer;color:var(--text-muted,#6B7280);transition:all .18s;font-weight:500;}
.type-btn.active{border-color:var(--primary,#005F73);color:var(--primary,#005F73);background:var(--primary-light,#E0F0F4);}
.type-btn i{margin-right:5px;}

.kondisi-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:6px;}
.kondisi-btn{padding:12px 8px;border-radius:var(--radius-sm,8px);border:1.5px solid var(--border-md,rgba(0,0,0,.12));background:none;font-family:'Hanken Grotesk','DM Sans',sans-serif;font-size:.78rem;cursor:pointer;color:var(--text-muted);transition:all .18s;font-weight:500;text-align:center;line-height:1.4;display:flex;flex-direction:column;align-items:center;gap:5px;}
.kondisi-btn i{font-size:1.2rem;}
.kondisi-btn.active-hidup{border-color:#2196F3;color:#1565C0;background:#E3F2FD;}
.kondisi-btn.active-terdampar{border-color:#424242;color:#212121;background:#F5F5F5;}
.kondisi-btn.active-tertangkap{border-color:#E65100;color:#BF360C;background:#FBE9E7;}

.map-picker-wrap{border-radius:var(--radius-sm,8px);overflow:hidden;border:1px solid var(--border-md);}
.map-picker-header{display:flex;align-items:center;justify-content:space-between;background:var(--primary,#005F73);color:#fff;padding:8px 14px;font-size:.8rem;font-weight:500;}
.map-picker-header .hint{font-size:.72rem;opacity:.75;font-weight:400;}
#map-picker{width:100%;height:300px;cursor:crosshair;position:relative;z-index:0;}
.leaflet-pane,.leaflet-tile-pane,.leaflet-overlay-pane{z-index:1!important;}
.leaflet-marker-pane{z-index:4!important;}
.leaflet-popup-pane{z-index:6!important;}
.leaflet-top,.leaflet-bottom{z-index:7!important;}
.coord-result{display:flex;align-items:center;gap:10px;background:var(--surface,#F8F9FA);padding:9px 14px;border-top:1px solid var(--border);flex-wrap:wrap;}
.coord-chip{display:flex;align-items:center;gap:6px;background:var(--white,#fff);border:1px solid var(--border-md);border-radius:var(--radius-xs,6px);padding:6px 12px;flex:1;min-width:140px;}
.coord-chip label{font-size:.68rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;font-weight:600;flex-shrink:0;}
.coord-chip .val{font-family:monospace;font-size:.82rem;color:var(--primary,#005F73);font-weight:600;}
.coord-placeholder{color:var(--text-muted);font-size:.8rem;font-style:italic;}
.btn-reset-coord{background:var(--coral-light,#FAECE7);color:var(--coral,#D85A30);border:1px solid rgba(216,90,48,.2);border-radius:var(--radius-xs,6px);padding:6px 12px;font-size:.76rem;cursor:pointer;font-family:'Hanken Grotesk','DM Sans',sans-serif;font-weight:500;transition:all .18s;white-space:nowrap;}
.btn-reset-coord:hover{background:var(--coral,#D85A30);color:#fff;}
.btn-reset-coord:disabled{opacity:.4;cursor:not-allowed;}

.dropzone{border:2px dashed #93c5fd;border-radius:12px;background:#f0f7ff;padding:2.5rem 1.5rem;text-align:center;cursor:pointer;transition:all .2s;position:relative;}
.dropzone:hover,.dropzone.dragover{border-color:var(--blue,#185FA5);background:#e0f0ff;}
.dropzone input[type="file"]{position:absolute;inset:0;width:100%;height:100%;opacity:0;cursor:pointer;z-index:2;}
.dropzone-icon{width:64px;height:64px;margin:0 auto .8rem;background:#dbeafe;border-radius:12px;display:flex;align-items:center;justify-content:center;border:2px dashed #93c5fd;}
.dropzone-icon i{font-size:1.8rem;color:#60a5fa;}
.dropzone-text{font-size:.875rem;color:var(--text-muted);margin-bottom:.7rem;}
.dropzone-text strong{color:var(--text);}
.dropzone-choose{display:inline-block;padding:7px 20px;background:#fff;border:1.5px solid #93c5fd;border-radius:8px;color:var(--blue,#185FA5);font-size:.875rem;font-weight:500;pointer-events:none;}
.dropzone:hover .dropzone-choose{background:var(--blue,#185FA5);border-color:var(--blue,#185FA5);color:#fff;}
.dropzone-hint{font-size:.72rem;color:var(--text-muted);margin-top:.5rem;}
.preview-wrap{display:none;margin-top:10px;border-radius:10px;overflow:hidden;border:1px solid var(--border-md);}
.preview-wrap.show{display:block;}
.preview-img{width:100%;max-height:200px;object-fit:contain;display:block;background:#fff;}
.preview-bar{display:flex;align-items:center;justify-content:space-between;padding:7px 12px;background:#fff;border-top:1px solid var(--border);font-size:.8rem;}
.preview-remove{background:var(--coral-light);color:var(--coral);border:none;border-radius:6px;padding:4px 10px;font-size:.75rem;cursor:pointer;font-weight:600;transition:background .2s;}
.preview-remove:hover{background:var(--coral);color:#fff;}
.required{color:var(--coral,#D85A30);margin-left:2px;}
</style>
@endpush

@section('content')
<div class="container">

    @include('partials.breadcrumb', ['items' => [
        ['label' => 'Beranda', 'route' => route('beranda')],
        ['label' => 'Laporan', 'route' => route('laporan.index')],
        ['label' => 'Buat Laporan'],
    ]])

    <div class="page-header">
        <h2>Buat Laporan Baru</h2>
        <p>Cukup 1 formulir singkat, pilih kondisi, tandai lokasi di peta, dan kirim.</p>
    </div>

    <div class="card">
        {{-- TYPE TOGGLE --}}
        <div class="type-toggle">
            <button type="button" class="type-btn active" data-value="dugong">
                <i class="fa-solid fa-fish"></i> Penampakan Dugong
            </button>
            <button type="button" class="type-btn" data-value="habitat">
                <i class="fa-solid fa-leaf"></i> Kondisi Habitat
            </button>
        </div>

        <form method="POST" action="{{ route('laporan.store') }}" enctype="multipart/form-data" id="laporanForm">
            @csrf
            {{-- Field nama kondisi (string) — diproses di LaporanService jadi FK --}}
            <input type="hidden" name="jenis"          id="jenis_laporan" value="dugong">
            <input type="hidden" name="kondisi_dugong" id="kondisi_input"  value="hidup">
            <input type="hidden" name="lat_coord"      id="lat_input"      value="{{ old('lat_coord') }}">
            <input type="hidden" name="lng_coord"      id="lng_input"      value="{{ old('lng_coord') }}">

            <div class="form-grid">

                <div class="form-group">
                    <label class="form-label">Tanggal <span class="required">*</span></label>
                    <input type="date" name="tanggal"
                           class="form-control @error('tanggal') is-invalid @enderror"
                           value="{{ old('tanggal', date('Y-m-d')) }}"
                           max="{{ date('Y-m-d') }}">
                    @error('tanggal')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Waktu</label>
                    <input type="time" name="waktu"
                           class="form-control @error('waktu') is-invalid @enderror"
                           value="{{ old('waktu') }}">
                    @error('waktu')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Lokasi <span class="required">*</span></label>
                    <select name="lokasi" id="lokasi_select" class="form-control @error('lokasi') is-invalid @enderror" onchange="pusatkanPetaKeLokasi(this.value)">
                        <option value="">-- Pilih Lokasi --</option>
                        @foreach($lokasiOptions as $opt)
                        <option value="{{ $opt }}" {{ old('lokasi') === $opt ? 'selected':'' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                    @error('lokasi')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group" id="jumlah-wrap">
                    <label class="form-label">Jumlah Dugong</label>
                    <input type="number" name="jumlah_dugong" min="1"
                           class="form-control @error('jumlah_dugong') is-invalid @enderror"
                           value="{{ old('jumlah_dugong') }}" placeholder="cth: 2">
                    @error('jumlah_dugong')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                {{-- KONDISI DUGONG --}}
                <div class="form-group col-2" id="kondisi-wrap">
                    <label class="form-label">
                        <i class="fa-solid fa-circle-info"></i> Kondisi Dugong <span class="required">*</span>
                    </label>
                    <div class="kondisi-grid">
                        <button type="button" class="kondisi-btn active-hidup" data-value="hidup" onclick="setKondisi(this)">
                            <i class="fa-solid fa-fish"></i> Hidup
                        </button>
                        <button type="button" class="kondisi-btn" data-value="mati_terdampar" onclick="setKondisi(this)">
                            <i class="fa-solid fa-triangle-exclamation"></i> Mati Terdampar
                        </button>
                        <button type="button" class="kondisi-btn" data-value="mati_tertangkap" onclick="setKondisi(this)">
                            <i class="fa-solid fa-skull-crossbones"></i> Mati Tertangkap
                        </button>
                    </div>
                    @error('kondisi_dugong')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                {{-- MAP PICKER --}}
                <div class="form-group col-2">
                    <label class="form-label">
                        <i class="fa-solid fa-map-pin"></i> Titik Koordinat
                        <span style="font-weight:400;color:var(--text-muted);">(opsional, klik peta untuk menandai)</span>
                    </label>
                    <div class="map-picker-wrap" style="margin-top:8px;">
                        <div class="map-picker-header">
                            <span><i class="fa-solid fa-location-crosshairs"></i> Klik peta untuk menentukan lokasi persis</span>
                            <span class="hint">Geser marker untuk menyesuaikan</span>
                        </div>
                        <div id="map-picker"></div>
                        <div class="coord-result">
                            <button type="button" class="btn-reset-coord" id="btn-gps"
                                    style="background:var(--primary-light,#E0F0F4);color:var(--primary,#005F73);"
                                    onclick="gunakanLokasiSaya()">
                                <i class="fa-solid fa-location-crosshairs"></i> Gunakan Lokasi Saya
                            </button>
                            <span class="coord-placeholder" id="coord-placeholder">Belum ada titik, klik pada peta atau pakai GPS</span>
                            <div class="coord-chip" id="chip-lat" style="display:none;">
                                <label>Lat</label>
                                <span class="val" id="lat-display">-</span>
                            </div>
                            <div class="coord-chip" id="chip-lng" style="display:none;">
                                <label>Lng</label>
                                <span class="val" id="lng-display">-</span>
                            </div>
                            <button type="button" class="btn-reset-coord" id="btn-reset-coord"
                                    disabled onclick="resetKoordinat()">
                                <i class="fa-solid fa-xmark"></i> Hapus
                            </button>
                        </div>
                    </div>
                    <div class="field-warning" id="warningJarakTitik">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span id="warningJarakTeks"></span>
                    </div>
                </div>

                {{-- DESKRIPSI --}}
                <div class="form-group col-2">
                    <label class="form-label">Deskripsi <span class="required">*</span></label>
                    <textarea name="deskripsi" rows="4"
                              class="form-control @error('deskripsi') is-invalid @enderror"
                              placeholder="Jelaskan kondisi yang Anda temukan...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                {{-- PELAPOR --}}
                <div class="form-group">
                    <label class="form-label">Nama Pelapor <span class="required">*</span></label>
                    <input type="text" name="nama_pelapor"
                           class="form-control @error('nama_pelapor') is-invalid @enderror"
                           value="{{ old('nama_pelapor', auth()->user()->name ?? '') }}"
                           placeholder="Nama lengkap">
                    @error('nama_pelapor')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">No. HP <span class="required">*</span></label>
                    <input type="text" name="no_hp"
                           class="form-control @error('no_hp') is-invalid @enderror"
                           value="{{ old('no_hp') }}" placeholder="cth: 0812xxxxxxx">
                    @error('no_hp')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Email <span style="font-weight:400;color:var(--text-muted);">(opsional)</span>
                    </label>
                    <input type="email" name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" placeholder="email@example.com">
                    @error('email')<span class="form-error">{{ $message }}</span>@enderror
                </div>

                {{-- FOTO / VIDEO --}}
                <div class="form-group col-2">
                    <label class="form-label">
                        Foto / Video Bukti <span class="required">*</span>
                        <span style="font-weight:400;color:var(--text-muted);">(maks 20 MB)</span>
                    </label>
                    <div style="margin-top:6px;">
                        <div class="dropzone" id="dropzone">
                            <input type="file" name="foto" id="fotoInput"
                                   accept="image/jpeg,image/png,image/webp,video/mp4,video/quicktime,video/webm"
                                   class="@error('foto') is-invalid @enderror">
                            <div class="dropzone-icon"><i class="fa-regular fa-image"></i></div>
                            <div class="dropzone-text"><strong>Drop foto atau video</strong> atau</div>
                            <div class="dropzone-choose">Pilih file</div>
                            <div class="dropzone-hint">JPG, PNG, WEBP, MP4, MOV, WEBM · Maksimal 20 MB</div>
                        </div>
                        <div class="preview-wrap" id="previewWrap">
                            <img src="" alt="Preview" class="preview-img" id="previewImg">
                            <video class="preview-img" id="previewVideo" controls style="display:none;"></video>
                            <div class="preview-bar">
                                <div style="display:flex;align-items:center;gap:8px;min-width:0;">
                                    <i class="fa-regular fa-image" style="color:var(--text-muted);" id="previewIcon"></i>
                                    <span style="font-size:.8rem;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:180px;" id="previewName"></span>
                                    <span style="font-size:.75rem;color:var(--text-muted);" id="previewSize"></span>
                                </div>
                                <button type="button" class="preview-remove" id="previewRemove">
                                    <i class="fa-solid fa-xmark"></i> Hapus
                                </button>
                            </div>
                        </div>
                        @error('foto')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-teal">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Laporan
                </button>
                <a href="{{ route('laporan.index') }}" class="btn btn-gray">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
/* ══ TYPE TOGGLE ══ */
document.querySelectorAll('.type-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('jenis_laporan').value = btn.dataset.value;
        const isDugong = btn.dataset.value === 'dugong';
        document.getElementById('jumlah-wrap').style.display  = isDugong ? '' : 'none';
        document.getElementById('kondisi-wrap').style.display = isDugong ? '' : 'none';
    });
});

/* ══ KONDISI DUGONG ══ */
const kondisiClasses = {
    hidup:           'active-hidup',
    mati_terdampar:  'active-terdampar',
    mati_tertangkap: 'active-tertangkap',
};
function setKondisi(el) {
    document.querySelectorAll('.kondisi-btn').forEach(b =>
        b.classList.remove('active-hidup','active-terdampar','active-tertangkap'));
    el.classList.add(kondisiClasses[el.dataset.value]);
    document.getElementById('kondisi_input').value = el.dataset.value;
}

/* ══ MAP PICKER ══ */
const lokasiKoordinat = @json($lokasiKoordinat);

function jarakKm(lat1, lng1, lat2, lng2) {
    const R = 6371;
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLng = (lng2 - lng1) * Math.PI / 180;
    const a = Math.sin(dLat/2) ** 2 + Math.cos(lat1 * Math.PI/180) * Math.cos(lat2 * Math.PI/180) * Math.sin(dLng/2) ** 2;
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}

function pusatkanPetaKeLokasi(nama) {
    const koord = lokasiKoordinat[nama];
    if (koord) map.setView(koord, 13);
}

function cekJarakTitik(lat, lng) {
    const namaLokasi = document.getElementById('lokasi_select').value;
    const koord = lokasiKoordinat[namaLokasi];
    const warningBox  = document.getElementById('warningJarakTitik');
    const warningTeks = document.getElementById('warningJarakTeks');
    if (!koord) { warningBox.classList.remove('show'); return; }
    const jarak = jarakKm(lat, lng, koord[0], koord[1]);
    if (jarak > 15) {
        warningTeks.textContent = `Titik yang Anda tandai berjarak sekitar ${jarak.toFixed(1)} km dari wilayah "${namaLokasi}" yang dipilih. Pastikan titiknya sudah benar sebelum mengirim, laporan tidak bisa diedit lagi setelah dikirim.`;
        warningBox.classList.add('show');
    } else {
        warningBox.classList.remove('show');
    }
}

const map = L.map('map-picker', {
    center: [1.0800, 104.5300], zoom: 11,
    minZoom: 8, maxZoom: 18,
    maxBounds: [[-5,95],[8,120]], maxBoundsViscosity: 1.0,
});
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution:'© OpenStreetMap', maxZoom:19
}).addTo(map);

if (document.getElementById('lokasi_select').value) {
    pusatkanPetaKeLokasi(document.getElementById('lokasi_select').value);
}

const markerIcon = L.divIcon({
    html:`<svg xmlns="http://www.w3.org/2000/svg" width="30" height="38" viewBox="0 0 30 38">
        <path d="M15 0C6.72 0 0 6.72 0 15C0 26.25 15 38 15 38S30 26.25 30 15C30 6.72 23.28 0 15 0Z"
              fill="#005F73" stroke="#fff" stroke-width="2"/>
        <circle cx="15" cy="15" r="5" fill="rgba(255,255,255,0.9)"/>
    </svg>`,
    className:'', iconSize:[30,38], iconAnchor:[15,38], popupAnchor:[0,-40],
});

let marker = null;
function fmt(n) { return parseFloat(n).toFixed(6); }
function setKoordinat(lat, lng) {
    document.getElementById('lat_input').value  = fmt(lat);
    document.getElementById('lng_input').value  = fmt(lng);
    document.getElementById('lat-display').textContent = fmt(lat);
    document.getElementById('lng-display').textContent = fmt(lng);
    document.getElementById('coord-placeholder').style.display = 'none';
    document.getElementById('chip-lat').style.display = '';
    document.getElementById('chip-lng').style.display = '';
    document.getElementById('btn-reset-coord').disabled = false;
    cekJarakTitik(lat, lng);
}
function resetKoordinat() {
    document.getElementById('lat_input').value = '';
    document.getElementById('lng_input').value = '';
    document.getElementById('coord-placeholder').style.display = '';
    document.getElementById('chip-lat').style.display = 'none';
    document.getElementById('chip-lng').style.display = 'none';
    document.getElementById('btn-reset-coord').disabled = true;
    document.getElementById('warningJarakTitik').classList.remove('show');
    if (marker) { map.removeLayer(marker); marker = null; }
}
function taruhMarker(lat, lng) {
    if (marker) map.removeLayer(marker);
    marker = L.marker([lat, lng], { icon: markerIcon, draggable: true }).addTo(map);
    setKoordinat(lat, lng);
    marker.on('dragend', ev => {
        const p = ev.target.getLatLng();
        setKoordinat(p.lat, p.lng);
    });
}
map.on('click', function(e) {
    taruhMarker(e.latlng.lat, e.latlng.lng);
});

function gunakanLokasiSaya() {
    if (!navigator.geolocation) {
        alert('Perangkat/browser Anda tidak mendukung GPS.');
        return;
    }
    const btn = document.getElementById('btn-gps');
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mencari lokasi...';

    navigator.geolocation.getCurrentPosition(
        pos => {
            const { latitude, longitude } = pos.coords;
            map.setView([latitude, longitude], 15);
            taruhMarker(latitude, longitude);
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        },
        () => {
            alert('Tidak bisa mengambil lokasi Anda. Pastikan izin GPS diaktifkan, atau tandai lokasi secara manual di peta.');
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        },
        { enableHighAccuracy: true, timeout: 10000 }
    );
}

/* ══ UPLOAD FOTO / VIDEO ══ */
const dropzone     = document.getElementById('dropzone');
const fotoInput    = document.getElementById('fotoInput');
const previewWrap  = document.getElementById('previewWrap');
const previewImg   = document.getElementById('previewImg');
const previewVideo = document.getElementById('previewVideo');
const previewIcon  = document.getElementById('previewIcon');
function formatSize(b) { return b < 1048576 ? (b/1024).toFixed(1)+' KB' : (b/1048576).toFixed(1)+' MB'; }
function showPreview(file) {
    if (!file) return;
    const isVideo = file.type.startsWith('video/');
    const isImage = file.type.startsWith('image/');
    if (!isVideo && !isImage) return;
    const url = URL.createObjectURL(file);
    if (isVideo) {
        previewVideo.src = url;
        previewVideo.style.display = '';
        previewImg.style.display = 'none';
        previewIcon.className = 'fa-solid fa-video';
    } else {
        previewImg.src = url;
        previewImg.style.display = '';
        previewVideo.style.display = 'none';
        previewIcon.className = 'fa-regular fa-image';
    }
    document.getElementById('previewName').textContent = file.name;
    document.getElementById('previewSize').textContent = formatSize(file.size);
    previewWrap.classList.add('show');
    dropzone.style.display = 'none';
}
function resetDropzone() {
    fotoInput.value = ''; previewImg.src = ''; previewVideo.src = '';
    previewWrap.classList.remove('show');
    dropzone.style.display = '';
}
fotoInput.addEventListener('change', () => { if (fotoInput.files[0]) showPreview(fotoInput.files[0]); });
document.getElementById('previewRemove').addEventListener('click', resetDropzone);
dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('dragover'); });
dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
dropzone.addEventListener('drop', e => {
    e.preventDefault(); dropzone.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if (file) { const dt = new DataTransfer(); dt.items.add(file); fotoInput.files = dt.files; showPreview(file); }
});
</script>
@endpush
