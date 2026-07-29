{{-- resources/views/informasi/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Informasi Dugong')

@push('styles')
<style>
/* ── HERO ── */
.info-hero {
    background: linear-gradient(135deg, var(--primary-dark,#003D4A) 0%, var(--primary,#005F73) 60%, var(--secondary,#0A9396) 100%);
    padding: 4rem 0 5rem;
    position: relative;
    overflow: hidden;
}
.info-hero::after {
    content: '';
    position: absolute;
    bottom: -1px; left: 0; right: 0;
    height: 80px;
    background: var(--white,#fff);
    clip-path: ellipse(55% 100% at 50% 100%);
}
.info-hero::before {
    content: '';
    position: absolute;
    top: -80px; right: -80px;
    width: 300px; height: 300px;
    border-radius: 50%;
    background: rgba(255,255,255,.05);
}
.info-hero-inner {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 1.5rem;
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 3rem;
}
.info-hero-text { flex: 1; color: #fff; }
.info-hero-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.25);
    color: #fff;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    padding: 4px 14px;
    border-radius: 20px;
    margin-bottom: 1.2rem;
}
.info-hero-text h1 {
    font-size: clamp(1.8rem, 4vw, 2.6rem);
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: .8rem;
}
.info-hero-text p {
    font-size: 1rem;
    opacity: .85;
    line-height: 1.8;
    max-width: 500px;
    margin-bottom: 1.8rem;
}
.info-hero-actions { display: flex; gap: 10px; flex-wrap: wrap; }
.info-hero-visual {
    flex-shrink: 0;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: rgba(255,255,255,.1);
    border: 3px solid rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    overflow: hidden;
}
.info-hero-visual img {
    width: 120px; height: 120px;
    object-fit: contain;
    filter: brightness(0) invert(1);
    opacity: .8;
}

/* ── QUICK STATS ── */
.quick-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 1px;
    background: var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    margin: 0 auto 3rem;
    max-width: 1100px;
    box-shadow: var(--shadow);
}
.qs-item {
    background: var(--white);
    padding: 1.4rem 1rem;
    text-align: center;
    transition: background .2s;
}
.qs-item:hover { background: var(--primary-light,#E0F0F4); }
.qs-num {
    font-family: 'Manrope','DM Sans',sans-serif;
    font-size: 2rem; font-weight: 800;
    color: var(--primary,#005F73);
    line-height: 1; margin-bottom: .3rem;
}
.qs-unit {
    font-size: .68rem; color: var(--primary,#005F73);
    font-weight: 700; letter-spacing: .06em;
    text-transform: uppercase; margin-bottom: .2rem;
}
.qs-desc { font-size: .72rem; color: var(--text-muted); }

/* ── SECTION ── */
.info-section { max-width: 1100px; margin: 0 auto; padding: 0 1.5rem 4rem; }

/* ── SPLIT LAYOUT ── */
.split {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
    margin-bottom: 5rem;
}
.split.reverse { direction: rtl; }
.split.reverse > * { direction: ltr; }
.split-img {
    border-radius: var(--radius);
    overflow: hidden;
    position: relative;
    background: var(--primary-light,#E0F0F4);
    aspect-ratio: 4/3;
    display: flex; align-items: center; justify-content: center;
}
.split-img img {
    width: 100%; height: 100%;
    object-fit: cover; display: block;
    transition: transform .5s ease;
}
.split-img:hover img { transform: scale(1.04); }
.split-img-placeholder {
    font-size: 5rem;
    opacity: .3;
}
.split-img-caption {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    background: linear-gradient(to top, rgba(0,61,74,.8), transparent);
    color: #fff; padding: 1.5rem 1rem .8rem;
    font-size: .78rem; font-style: italic;
}
.split-body {}
.split-body p {
    color: var(--text-muted); line-height: 1.85;
    font-size: .92rem; margin-bottom: .9rem;
}
.fact-list { display: flex; flex-direction: column; gap: 10px; margin-top: 1.2rem; }
.fact-item {
    display: flex; align-items: flex-start; gap: 12px;
    font-size: .875rem; color: var(--text-muted); line-height: 1.65;
}
.fact-icon {
    width: 32px; height: 32px; border-radius: 8px;
    background: var(--primary-light,#E0F0F4);
    display: flex; align-items: center; justify-content: center;
    color: var(--primary,#005F73); font-size: .9rem;
    flex-shrink: 0; margin-top: 1px;
}

/* ── STATUS CARD ── */
.status-card {
    background: linear-gradient(135deg, #FFF8E8, #FFF0CC);
    border: 1px solid rgba(186,117,23,.2);
    border-radius: var(--radius);
    padding: 2rem;
    display: flex;
    gap: 1.5rem;
    align-items: flex-start;
    margin-bottom: 4rem;
}
.status-icon {
    width: 56px; height: 56px;
    border-radius: 14px;
    background: rgba(186,117,23,.12);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; color: #B45309;
    flex-shrink: 0;
}
.status-card h3 { font-size: 1.2rem; color: #5A3400; margin-bottom: .5rem; }
.status-card p  { color: #7A5010; font-size: .875rem; line-height: 1.8; margin-bottom: .8rem; }
.iucn-badge {
    display: inline-flex; align-items: center; gap: 7px;
    background: #F59E0B; color: #fff;
    padding: 5px 14px; border-radius: 8px;
    font-size: .8rem; font-weight: 700;
}
.threat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 8px; margin-top: 1rem;
}
.threat-item {
    background: rgba(255,255,255,.7);
    border: 1px solid rgba(186,117,23,.15);
    border-radius: 8px;
    padding: 10px 12px;
    display: flex; align-items: center; gap: 9px;
    font-size: .78rem; color: #7A5010; font-weight: 500;
}
.threat-item i { color: #B45309; width: 16px; text-align: center; }

/* ── GALERI (viewfinder / lightbox) ── */
.galeri-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem; margin-bottom: 4rem;
}
.galeri-card {
    position: relative;
    border-radius: var(--radius);
    overflow: hidden;
    aspect-ratio: 4/3;
    background: #0A2E36;
    cursor: pointer;
    transition: box-shadow .25s, transform .25s;
}
.galeri-card:hover { box-shadow: 0 12px 28px rgba(0,61,74,.28); transform: translateY(-3px); }
.galeri-card img {
    width: 100%; height: 100%;
    object-fit: cover; display: block;
    transition: transform .5s ease, filter .3s ease;
}
.galeri-card:hover img { transform: scale(1.08); }
.galeri-card::after {
    content: '';
    position: absolute; inset: 9px;
    pointer-events: none;
    opacity: 0;
    transition: opacity .25s;
    background-image:
        linear-gradient(to right, var(--tertiary,#94D2BD) 2px, transparent 2px),
        linear-gradient(to bottom, var(--tertiary,#94D2BD) 2px, transparent 2px),
        linear-gradient(to left,  var(--tertiary,#94D2BD) 2px, transparent 2px),
        linear-gradient(to bottom, var(--tertiary,#94D2BD) 2px, transparent 2px),
        linear-gradient(to right, var(--tertiary,#94D2BD) 2px, transparent 2px),
        linear-gradient(to top,   var(--tertiary,#94D2BD) 2px, transparent 2px),
        linear-gradient(to left,  var(--tertiary,#94D2BD) 2px, transparent 2px),
        linear-gradient(to top,   var(--tertiary,#94D2BD) 2px, transparent 2px);
    background-size: 16px 16px;
    background-repeat: no-repeat;
    background-position: top left, top left, top right, top right, bottom left, bottom left, bottom right, bottom right;
}
.galeri-card:hover::after { opacity: 1; }
.galeri-zoom-icon {
    position: absolute; inset: 0;
    display: flex; align-items: center; justify-content: center;
    background: rgba(0,61,74,0);
    opacity: 0;
    transition: all .25s;
}
.galeri-card:hover .galeri-zoom-icon { opacity: 1; background: rgba(0,20,25,.35); }
.galeri-zoom-icon i {
    width: 42px; height: 42px;
    border-radius: 50%;
    background: rgba(255,255,255,.12);
    backdrop-filter: blur(6px);
    border: 1px solid rgba(255,255,255,.4);
    color: #fff; font-size: 1.1rem;
    display: flex; align-items: center; justify-content: center;
    transform: scale(.7);
    transition: transform .25s;
}
.galeri-card:hover .galeri-zoom-icon i { transform: scale(1); }
.galeri-empty {
    text-align: center;
    padding: 3rem 1.5rem;
    color: var(--text-muted);
    background: var(--surface,#F8F9FA);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    margin-bottom: 4rem;
}

/* ── LIGHTBOX ── */
.lightbox-overlay {
    display: none;
    position: fixed; inset: 0; z-index: 2000;
    background: rgba(0,20,25,.93);
    backdrop-filter: blur(8px);
    align-items: center; justify-content: center;
    flex-direction: column;
    padding: 2rem;
}
.lightbox-overlay.open { display: flex; }
.lightbox-img-wrap { position: relative; max-width: 880px; width: 100%; }
.lightbox-img {
    width: 100%; max-height: 74vh; object-fit: contain;
    border-radius: 10px;
    box-shadow: 0 24px 70px rgba(0,0,0,.55);
    background: #04181C;
}
.lightbox-close, .lightbox-nav {
    position: absolute;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.25);
    color: #fff;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background .2s;
}
.lightbox-close:hover, .lightbox-nav:hover { background: rgba(255,255,255,.22); }
.lightbox-close { top: -50px; right: 0; width: 38px; height: 38px; border-radius: 50%; font-size: 1rem; }
.lightbox-nav { top: 50%; transform: translateY(-50%); width: 46px; height: 46px; border-radius: 50%; font-size: 1.1rem; }
.lightbox-prev { left: -62px; }
.lightbox-next { right: -62px; }
.lightbox-counter { margin-top: 1.1rem; color: rgba(255,255,255,.6); font-size: .78rem; letter-spacing: .05em; font-family: var(--mono); }
@media (max-width: 900px) {
    .lightbox-prev { left: 6px; }
    .lightbox-next { right: 6px; }
    .lightbox-close { top: 6px; right: 6px; background: rgba(0,0,0,.4); }
}

/* ── FAQ ── */
.faq-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0 3rem;
    margin-bottom: 4rem;
}
.faq-item { border-bottom: 1px solid var(--border); }
.faq-question {
    display: flex; align-items: center; justify-content: space-between;
    gap: 1rem; padding: 1.1rem 0; cursor: pointer;
    font-weight: 700; color: var(--primary,#005F73);
    font-size: .92rem; font-family: 'Manrope','DM Sans',sans-serif;
}
.faq-question i { flex-shrink: 0; transition: transform .2s; color: var(--primary,#005F73); }
.faq-item.open .faq-question i { transform: rotate(180deg); }
.faq-answer { max-height: 0; overflow: hidden; transition: max-height .25s ease; }
.faq-item.open .faq-answer { max-height: 300px; }
.faq-answer-inner { padding-bottom: 1.1rem; font-size: .85rem; color: var(--text-muted); line-height: 1.75; }
@media (max-width: 768px) {
    .faq-grid { grid-template-columns: 1fr; }
}

/* ── CTA ── */
.info-cta {
    background: linear-gradient(135deg, var(--primary-dark,#003D4A), var(--primary,#005F73));
    border-radius: var(--radius);
    padding: 3.5rem 2rem;
    text-align: center;
    color: #fff;
    position: relative;
    overflow: hidden;
    margin-bottom: 3rem;
}
.info-cta::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: rgba(255,255,255,.05);
}
.info-cta h2 {
    font-size: 1.8rem; margin-bottom: .6rem;
    font-family: 'Manrope','DM Sans',sans-serif;
    font-weight: 800;
}
.info-cta p  { opacity: .85; max-width: 500px; margin: 0 auto 1.8rem; font-size: .95rem; line-height: 1.75; }
.info-cta-btns { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }

@media (max-width: 768px) {
    .split, .split.reverse { grid-template-columns: 1fr; direction: ltr; gap: 2rem; }
    .info-hero-inner { flex-direction: column; gap: 1.5rem; }
    .info-hero-visual { width: 120px; height: 120px; }
    .info-hero-visual img { width: 80px; height: 80px; }
    .info-cta { padding: 2.5rem 1.5rem; }
    .status-card { flex-direction: column; }
    .quick-stats { grid-template-columns: repeat(3, 1fr); }
}
</style>
@endpush

@section('content')

{{-- ══ HERO ══ --}}
<section class="info-hero">
    <div class="info-hero-inner">
        <div class="info-hero-text">
            <div class="info-hero-tag">
                <i class="fa-solid fa-water"></i>
                Konservasi Laut · Kepulauan Riau
            </div>
            <h1>Mengenal Dugong<br>Sang Penjaga Lamun</h1>
            <p>Dugong adalah mamalia laut yang menjadi indikator kesehatan ekosistem padang lamun di perairan Bintan, Kepulauan Riau.</p>
            <div class="info-hero-actions">
                <a href="{{ route('laporan.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-pen-to-square"></i> Buat Laporan
                </a>
                <a href="#partisipasi" class="btn btn-outline">
                    <i class="fa-solid fa-people-group"></i> Cara Berpartisipasi
                </a>
            </div>
        </div>
        <div class="info-hero-visual">
            <img src="{{ asset('images/Logo Sidugong.png') }}" alt="Dugong">
        </div>
    </div>
</section>

{{-- ══ QUICK STATS ══ --}}
<div style="max-width:1100px;margin:0 auto;padding:0 1.5rem;">
    <div class="quick-stats" style="margin-top:2rem;">
        <div class="qs-item">
            <div class="qs-num">40</div>
            <div class="qs-unit">kg/hari</div>
            <div class="qs-desc">Konsumsi lamun</div>
        </div>
        <div class="qs-item">
            <div class="qs-num">70</div>
            <div class="qs-unit">tahun</div>
            <div class="qs-desc">Usia hidup</div>
        </div>
        <div class="qs-item">
            <div class="qs-num">3 m</div>
            <div class="qs-unit">panjang</div>
            <div class="qs-desc">Tubuh dewasa</div>
        </div>
        <div class="qs-item">
            <div class="qs-num">10 m</div>
            <div class="qs-unit">kedalaman</div>
            <div class="qs-desc">Habitat lamun</div>
        </div>
        <div class="qs-item">
            <div class="qs-num">350+</div>
            <div class="qs-unit">kg</div>
            <div class="qs-desc">Berat dewasa</div>
        </div>
    </div>
</div>

{{-- ══ APA ITU DUGONG ══ --}}
<div class="info-section" style="padding-top:3rem;">
    <div class="split">
        <div class="split-img">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6b/Dugong_at_Marsa_Alam.jpg/800px-Dugong_at_Marsa_Alam.jpg"
                 alt="Dugong berenang"
                 onerror="this.parentElement.innerHTML='<div class=\'split-img-placeholder\'><i class=\'fa-solid fa-fish\'></i></div>'">
            <div class="split-img-caption">Dugong dugon, mamalia laut dilindungi</div>
        </div>
        <div class="split-body">
            <div class="sec-eyebrow"><i class="fa-solid fa-circle-info"></i> Apa itu Dugong?</div>
            <h2 class="sec-title">Mamalia Laut Penjaga Ekosistem Pesisir</h2>
            <p>Dugong (<em>Dugong dugon</em>) adalah mamalia laut dalam ordo Sirenia, satu-satunya anggota keluarga Dugongidae yang masih hidup hingga saat ini.</p>
            <p>Berbeda dengan ikan, dugong bernapas menggunakan paru-paru dan harus naik ke permukaan secara berkala. Mereka sepenuhnya bergantung pada ekosistem padang lamun untuk bertahan hidup.</p>
            <div class="fact-list">
                <div class="fact-item">
                    <div class="fact-icon"><i class="fa-solid fa-globe"></i></div>
                    <span>Tersebar di kawasan Indo-Pasifik, dari Afrika Timur hingga Australia</span>
                </div>
                <div class="fact-item">
                    <div class="fact-icon"><i class="fa-solid fa-lungs"></i></div>
                    <span>Bernapas dengan paru-paru, harus naik ke permukaan setiap beberapa menit</span>
                </div>
                <div class="fact-item">
                    <div class="fact-icon"><i class="fa-solid fa-baby"></i></div>
                    <span>Reproduksi sangat lambat, betina melahirkan 1 anak setiap 3 hingga 7 tahun</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ HABITAT ══ --}}
    <div class="split reverse">
        <div class="split-img">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/Seagrass_meadow.jpg/800px-Seagrass_meadow.jpg"
                 alt="Padang lamun"
                 onerror="this.parentElement.innerHTML='<div class=\'split-img-placeholder\'><i class=\'fa-solid fa-leaf\'></i></div>'">
            <div class="split-img-caption">Padang lamun, sumber makanan utama dugong</div>
        </div>
        <div class="split-body">
            <div class="sec-eyebrow"><i class="fa-solid fa-seedling"></i> Habitat & Makanan</div>
            <h2 class="sec-title">Hidup di Padang Lamun yang Jernih</h2>
            <p>Dugong sangat bergantung pada padang lamun (<em>seagrass</em>) sebagai makanan utama. Mereka merumput di dasar laut, mengonsumsi lamun beserta akar-akarnya.</p>
            <p>Di perairan Bintan, padang lamun tersebar di lokasi-lokasi seperti Busung, Pengudang, Pangkil, dan Penaga, semua menjadi habitat penting dugong.</p>
            <div class="fact-list">
                <div class="fact-item">
                    <div class="fact-icon"><i class="fa-solid fa-water"></i></div>
                    <span>Hidup di perairan dangkal hingga kedalaman <strong>10 meter</strong></span>
                </div>
                <div class="fact-item">
                    <div class="fact-icon"><i class="fa-solid fa-bowl-food"></i></div>
                    <span>Mengonsumsi hingga <strong>40 kg lamun per hari</strong> untuk memenuhi kebutuhan energi</span>
                </div>
                <div class="fact-item">
                    <div class="fact-icon"><i class="fa-solid fa-droplet"></i></div>
                    <span>Sangat sensitif terhadap polusi, membutuhkan air yang jernih dan bersih</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ STATUS KONSERVASI ══ --}}
    <div class="status-card">
        <div class="status-icon">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div style="flex:1;">
            <h3>Status Konservasi: Vulnerable (Rentan)</h3>
            <p>IUCN Red List menetapkan dugong berstatus <strong>Vulnerable</strong> secara global. Di Indonesia, dugong dilindungi penuh oleh <strong>UU No. 5 Tahun 1990</strong> dan PP No. 7 Tahun 1999 tentang Pengawetan Jenis Tumbuhan dan Satwa.</p>
            <div class="iucn-badge">
                <i class="fa-solid fa-shield-halved"></i>
                Vulnerable - IUCN Red List
            </div>
            <div class="threat-grid">
                <div class="threat-item">
                    <i class="fa-solid fa-fish-fins"></i> Tangkapan tidak sengaja (bycatch)
                </div>
                <div class="threat-item">
                    <i class="fa-solid fa-seedling"></i> Kerusakan padang lamun
                </div>
                <div class="threat-item">
                    <i class="fa-solid fa-industry"></i> Polusi & sedimentasi laut
                </div>
                <div class="threat-item">
                    <i class="fa-solid fa-ship"></i> Tabrakan dengan kapal motor
                </div>
                <div class="threat-item">
                    <i class="fa-solid fa-temperature-high"></i> Perubahan iklim global
                </div>
                <div class="threat-item">
                    <i class="fa-solid fa-ban"></i> Perburuan ilegal
                </div>
            </div>
        </div>
    </div>

    {{-- ══ GALERI DOKUMENTASI ══ --}}
    <div style="text-align:center;margin-bottom:2.5rem;">
        <div class="sec-eyebrow"><i class="fa-solid fa-satellite-dish"></i> Arsip Dokumentasi</div>
        <h2 class="sec-title" style="margin-bottom:.4rem;">Potret Kehidupan Dugong</h2>
        <p class="sec-sub" style="margin:0 auto;">Dokumentasi visual dugong di perairan Kepulauan Riau dari waktu ke waktu.</p>
    </div>
    @if($galeriFoto->isNotEmpty())
    <div class="galeri-grid" id="galeriGrid">
        @foreach($galeriFoto as $i => $file)
        <div class="galeri-card" onclick="bukaLightbox({{ $i }})">
            <img src="{{ asset('images/galeri/'.$file) }}" alt="Dokumentasi dugong {{ $i+1 }}" loading="lazy">
            <div class="galeri-zoom-icon"><i class="fa-solid fa-expand"></i></div>
        </div>
        @endforeach
    </div>

    <div class="lightbox-overlay" id="lightboxOverlay">
        <div class="lightbox-img-wrap">
            <button class="lightbox-close" onclick="tutupLightbox()" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
            <button class="lightbox-nav lightbox-prev" onclick="navLightbox(-1)" aria-label="Sebelumnya"><i class="fa-solid fa-chevron-left"></i></button>
            <img src="" alt="" class="lightbox-img" id="lightboxImg">
            <button class="lightbox-nav lightbox-next" onclick="navLightbox(1)" aria-label="Berikutnya"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
        <div class="lightbox-counter" id="lightboxCounter"></div>
    </div>
    @else
    <div class="galeri-empty">
        <i class="fa-solid fa-images" style="font-size:2rem;opacity:.25;display:block;margin-bottom:.8rem;"></i>
        Galeri dokumentasi akan segera hadir.
    </div>
    @endif

    {{-- ══ PARTISIPASI ══ --}}
    <div id="partisipasi" style="scroll-margin-top:80px;margin-bottom:4rem;">
        <div style="text-align:center;margin-bottom:2.5rem;">
            <div class="sec-eyebrow"><i class="fa-solid fa-hand-holding-heart"></i> Aksi Nyata</div>
            <h2 class="sec-title" style="margin-bottom:.5rem;">Bagaimana Anda Bisa Membantu?</h2>
            <p class="sec-sub" style="margin:0 auto;">Konservasi dugong bukan hanya tugas pemerintah, setiap individu bisa membuat perubahan nyata di lapangan.</p>
        </div>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-num">1</div>
                <h4>Laporkan Penampakan</h4>
                <p>Gunakan fitur Laporan di aplikasi ini untuk mencatat setiap penampakan dugong atau kondisi habitat yang Anda temui.</p>
            </div>
            <div class="step-card">
                <div class="step-num">2</div>
                <h4>Jaga Kebersihan Laut</h4>
                <p>Tidak membuang sampah ke laut adalah tindakan paling sederhana namun berdampak besar bagi habitat dugong.</p>
            </div>
            <div class="step-card">
                <div class="step-num">3</div>
                <h4>Edukasi Komunitas</h4>
                <p>Bagikan informasi tentang dugong kepada keluarga, teman, dan komunitas pesisir di sekitar Anda.</p>
            </div>
            <div class="step-card">
                <div class="step-num">4</div>
                <h4>Ikuti Program Konservasi</h4>
                <p>Bergabung dalam kegiatan bersih pantai, penanaman lamun, dan program konservasi dugong di Kepulauan Riau.</p>
            </div>
        </div>
    </div>

    {{-- ══ FAQ ══ --}}
    <div style="text-align:center;margin-bottom:2.5rem;">
        <div class="sec-eyebrow"><i class="fa-solid fa-circle-question"></i> Dukungan & Informasi</div>
        <h2 class="sec-title" style="margin-bottom:.4rem;">Pertanyaan yang Sering Diajukan</h2>
        <p class="sec-sub" style="margin:0 auto;">Temukan jawaban seputar pelaporan, verifikasi, dan data dugong di Simadang.</p>
    </div>
    <div class="faq-grid" id="faqAccordion">
        <div class="faq-item">
            <div class="faq-question">Bagaimana cara melaporkan penampakan dugong? <i class="fa-solid fa-chevron-down"></i></div>
            <div class="faq-answer"><div class="faq-answer-inner">Klik tombol "Laporkan!" atau "Buat Laporan", pilih jenis laporan, tandai lokasi di peta, isi data singkat, lalu kirim. Anda perlu login terlebih dahulu agar laporan bisa diverifikasi admin.</div></div>
        </div>
        <div class="faq-item">
            <div class="faq-question">Apakah saya harus punya akun untuk melapor? <i class="fa-solid fa-chevron-down"></i></div>
            <div class="faq-answer"><div class="faq-answer-inner">Ya. Login diperlukan agar admin bisa menghubungi Anda kembali untuk verifikasi. Anda bisa mendaftar akun gratis hanya dengan nama, email, dan kata sandi.</div></div>
        </div>
        <div class="faq-item">
            <div class="faq-question">Kenapa dugong yang masih hidup juga perlu dilaporkan? <i class="fa-solid fa-chevron-down"></i></div>
            <div class="faq-answer"><div class="faq-answer-inner">Data dugong hidup justru paling dibutuhkan untuk menganalisis pola ruaya dan sebaran populasi, bukan hanya laporan dugong yang terdampar atau mati.</div></div>
        </div>
        <div class="faq-item">
            <div class="faq-question">Berapa lama laporan saya diverifikasi? <i class="fa-solid fa-chevron-down"></i></div>
            <div class="faq-answer"><div class="faq-answer-inner">Anda bisa memantau status laporan (Menunggu / Terverifikasi / Ditolak) kapan saja melalui halaman "Riwayat Laporan" setelah login.</div></div>
        </div>
        <div class="faq-item">
            <div class="faq-question">Bagaimana cara mendapatkan data untuk penelitian? <i class="fa-solid fa-chevron-down"></i></div>
            <div class="faq-answer"><div class="faq-answer-inner">Gunakan menu "Permintaan Data" untuk mengajukan permintaan resmi. Admin akan meninjau dan mengirim data dalam format CSV/JSON ke email Anda dalam 1–3 hari kerja.</div></div>
        </div>
        <div class="faq-item">
            <div class="faq-question">Apa yang harus dilakukan jika menemukan dugong terdampar atau tersangkut jaring? <i class="fa-solid fa-chevron-down"></i></div>
            <div class="faq-answer"><div class="faq-answer-inner">Segera hubungi BPSPL Padang atau instansi terkait untuk penanganan darurat, lalu laporkan kejadiannya di Simadang dengan foto/video sebagai bukti.</div></div>
        </div>
        <div class="faq-item">
            <div class="faq-question">Siapa yang bisa dihubungi untuk kondisi darurat? <i class="fa-solid fa-chevron-down"></i></div>
            <div class="faq-answer"><div class="faq-answer-inner">Hubungi BPSPL Padang (Wil. Kepri) di bpspl.padang@kkp.go.id atau lihat direktori kontak lembaga konservasi di halaman Beranda.</div></div>
        </div>
        <div class="faq-item">
            <div class="faq-question">Apakah Simadang bisa diakses lewat HP? <i class="fa-solid fa-chevron-down"></i></div>
            <div class="faq-answer"><div class="faq-answer-inner">Bisa. Simadang berbasis web sepenuhnya dan bisa diakses dari browser HP Android maupun laptop/desktop, tanpa perlu instalasi aplikasi tambahan.</div></div>
        </div>
    </div>

    {{-- ══ CTA FINAL ══ --}}
    <div class="info-cta">
        <div class="sec-eyebrow" style="background:rgba(255,255,255,.15);color:#fff;margin-bottom:1rem;">
            <i class="fa-solid fa-star"></i> Bergabung dalam Konservasi
        </div>
        <h2>Temukan Dugong?<br>Laporkan Sekarang!</h2>
        <p>Setiap laporan yang Anda kirim membantu kami memantau populasi dan menjaga habitat dugong di perairan Bintan, Kepulauan Riau.</p>
        <div class="info-cta-btns">
            <a href="{{ route('laporan.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-pen-to-square"></i> Buat Laporan
            </a>
            @guest
            <a href="{{ route('register') }}" class="btn btn-outline">
                <i class="fa-solid fa-user-plus"></i> Daftar Akun
            </a>
            @endguest
            <a href="{{ route('peta.index') }}" class="btn btn-outline">
                <i class="fa-solid fa-map"></i> Lihat Peta
            </a>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.getElementById('faqAccordion')?.addEventListener('click', function(e) {
    const question = e.target.closest('.faq-question');
    if (!question) return;
    question.closest('.faq-item').classList.toggle('open');
});

/* ══ LIGHTBOX GALERI ══ */
const galeriFoto = @json($galeriFoto->map(fn($f) => asset('images/galeri/'.$f)));
let lightboxIndex = 0;
const lightboxOverlay = document.getElementById('lightboxOverlay');
const lightboxImg     = document.getElementById('lightboxImg');
const lightboxCounter = document.getElementById('lightboxCounter');

function renderLightbox() {
    lightboxImg.src = galeriFoto[lightboxIndex];
    lightboxCounter.textContent = `${lightboxIndex + 1} / ${galeriFoto.length}`;
}
function bukaLightbox(i) {
    lightboxIndex = i;
    renderLightbox();
    lightboxOverlay.classList.add('open');
    document.body.style.overflow = 'hidden';
}
function tutupLightbox() {
    lightboxOverlay.classList.remove('open');
    document.body.style.overflow = '';
}
function navLightbox(dir) {
    lightboxIndex = (lightboxIndex + dir + galeriFoto.length) % galeriFoto.length;
    renderLightbox();
}
lightboxOverlay?.addEventListener('click', e => { if (e.target === lightboxOverlay) tutupLightbox(); });
document.addEventListener('keydown', e => {
    if (!lightboxOverlay?.classList.contains('open')) return;
    if (e.key === 'Escape')     tutupLightbox();
    if (e.key === 'ArrowLeft')  navLightbox(-1);
    if (e.key === 'ArrowRight') navLightbox(1);
});
</script>
@endpush
