{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Simadang') - Konservasi Dugong</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
    /* Dropdown profil */
    .profile-wrap { position: relative; }
    .profile-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        background: #fff;
        border: 1px solid rgba(0,0,0,.1);
        border-radius: 10px;
        min-width: 200px;
        box-shadow: 0 8px 24px rgba(0,61,74,.15);
        overflow: hidden;
        z-index: 300;
    }
    .profile-dropdown.open { display: block; }
    .pd-header {
        padding: 12px 14px;
        border-bottom: 1px solid rgba(0,0,0,.07);
        background: #F0F8FA;
    }
    .pd-name  { font-weight: 700; font-size: .875rem; color: #005F73; }
    .pd-email { font-size: .72rem; color: #6B7280; }
    .pd-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        font-size: .85rem;
        color: #1C1C1E;
        text-decoration: none;
        transition: background .15s;
    }
    .pd-link:hover { background: #F0F8FA; color: #005F73; }
    .pd-link i { width: 16px; color: #6B7280; }
    .pd-link:hover i { color: #005F73; }
    .pd-sep { height: 1px; background: rgba(0,0,0,.07); }
    .pd-logout {
        display: flex; align-items: center; gap: 10px;
        width: 100%;
        padding: 10px 14px;
        font-size: .85rem;
        color: #AE2012;
        background: none;
        border: none;
        cursor: pointer;
        font-family: inherit;
        text-align: left;
        transition: background .15s;
    }
    .pd-logout:hover { background: #FDECEA; }
    .pd-logout i { width: 16px; }

    /* Avatar button */
    .avatar-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.2);
        border-radius: 8px;
        padding: 5px 10px 5px 6px;
        cursor: pointer;
        transition: background .18s;
        color: #fff;
        font-family: inherit;
        font-size: .85rem;
        font-weight: 500;
    }
    .avatar-btn:hover { background: rgba(255,255,255,.2); }
    .avatar-btn .chevron { font-size: .65rem; opacity: .7; }
    </style>
    @stack('styles')
</head>
<body>

@php
    $isAdmin = auth()->check() && auth()->user()->role === 'admin';
    $isWarga = auth()->check() && auth()->user()->role !== 'admin';
    $isGuest = !auth()->check();
    $user    = auth()->user();
    $initials = $user
        ? strtoupper(mb_substr($user->name,0,1)) . strtoupper(mb_substr(strrchr(trim($user->name),' '),1,1) ?? '')
        : '';
@endphp

<nav class="navbar">
    {{-- Brand --}}
    <a href="{{ $isAdmin ? route('admin.dashboard') : route('beranda') }}" class="navbar-brand">
        <img src="{{ asset('images/Logo Sidugong.png') }}" alt="Simadang"
             style="width:62px;height:62px;object-fit:contain;">
        <span class="navbar-brand-text">Simadang</span>
    </a>

    {{-- Desktop Nav — sama untuk publik, warga, maupun admin yang sedang menjelajah situs publik --}}
    <div class="navbar-nav navbar-nav-desktop">
        <a href="{{ route('beranda') }}"        class="nav-link {{ request()->is('/') ? 'active':'' }}">Beranda</a>
        <a href="{{ route('peta.index') }}"      class="nav-link {{ request()->is('peta*') ? 'active':'' }}">Peta</a>
        @if(!$isGuest)
        <a href="{{ route('laporan.index') }}"   class="nav-link {{ request()->is('laporan') ? 'active':'' }}">Laporan</a>
        @endif
        <a href="{{ route('informasi.index') }}" class="nav-link {{ request()->is('informasi*') ? 'active':'' }}">Informasi</a>
        <a href="{{ route('statistik.index') }}" class="nav-link {{ request()->is('statistik*') ? 'active':'' }}">Tren Laporan</a>
    </div>

    {{-- Desktop Right --}}
    <div class="navbar-end navbar-end-desktop">
        <a href="{{ route('laporan.create') }}" class="btn btn-primary btn-sm">
            <i class="fa-solid fa-triangle-exclamation"></i> Laporkan!
        </a>
        @if($isGuest)
            <a href="{{ route('login') }}" class="btn-nav-masuk">
                <i class="fa-solid fa-right-to-bracket"></i> Login
            </a>
        @else
            <div class="profile-wrap">
                <button type="button" class="avatar-btn" onclick="toggleDropdown()">
                    <div class="navbar-avatar">{{ $initials }}</div>
                    <span class="navbar-username">{{ explode(' ',$user->name)[0] }}</span>
                    <i class="fa-solid fa-chevron-down chevron" id="chevronIcon"></i>
                </button>
                <div class="profile-dropdown" id="profileDropdown">
                    <div class="pd-header">
                        <div class="pd-name">{{ $user->name }}</div>
                        <div class="pd-email">{{ $user->email }}</div>
                    </div>
                    @if($isAdmin)
                    <a href="{{ route('admin.dashboard') }}" class="pd-link">
                        <i class="fa-solid fa-gauge"></i> Dashboard Admin
                    </a>
                    @else
                    <a href="{{ route('laporan.riwayat') }}" class="pd-link">
                        <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Laporan
                    </a>
                    @endif
                    <div class="pd-sep"></div>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="pd-logout">
                            <i class="fa-solid fa-right-from-bracket"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <button class="burger-btn" id="burgerBtn" aria-label="Menu">
        <span class="burger-line"></span>
        <span class="burger-line"></span>
        <span class="burger-line"></span>
    </button>
</nav>

<div class="mobile-overlay" id="mobileOverlay"></div>
<div class="mobile-drawer" id="mobileDrawer">
    <div class="drawer-header">
        <div style="display:flex;align-items:center;gap:8px;">
            <img src="{{ asset('images/Logo Sidugong.png') }}" alt=""
                 style="width:30px;height:30px;object-fit:contain;">
            <span style="font-weight:700;font-size:1rem;color:#fff;">Simadang</span>
        </div>
        <button class="drawer-close" id="drawerClose"><i class="fa-solid fa-xmark"></i></button>
    </div>

    @if(auth()->check())
    <div class="drawer-user">
        <div class="drawer-avatar">{{ $initials }}</div>
        <div>
            <div style="font-weight:600;font-size:.875rem;">{{ $user->name }}</div>
            <div style="font-size:.72rem;color:var(--text-muted);">
                {{ $isAdmin ? 'Administrator' : 'Dugong Ranger' }}
            </div>
        </div>
    </div>
    @else
    <div class="drawer-guest-banner">
        <p style="margin-bottom:10px;font-size:.82rem;color:var(--text-muted);">
            Masuk untuk membuat dan memantau laporan.
        </p>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('login') }}"    class="btn btn-teal btn-sm" style="flex:1;justify-content:center;">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn-gray btn-sm" style="flex:1;justify-content:center;">Daftar</a>
        </div>
    </div>
    @endif

    <div class="drawer-nav">
        @if($isWarga || $isAdmin)
        <a href="{{ route('beranda') }}"          class="drawer-link"><i class="fa-solid fa-house drawer-icon"></i> Beranda</a>
        <a href="{{ route('peta.index') }}"        class="drawer-link"><i class="fa-solid fa-map drawer-icon"></i> Peta</a>
        <a href="{{ route('laporan.index') }}"     class="drawer-link"><i class="fa-solid fa-clipboard-list drawer-icon"></i> Laporan</a>
        <a href="{{ route('informasi.index') }}"   class="drawer-link"><i class="fa-solid fa-book drawer-icon"></i> Informasi</a>
        <a href="{{ route('statistik.index') }}"   class="drawer-link"><i class="fa-solid fa-chart-bar drawer-icon"></i> Tren Laporan</a>
        <div class="drawer-sep"></div>
        @if($isAdmin)
        <a href="{{ route('admin.dashboard') }}" class="drawer-link drawer-link-highlight"><i class="fa-solid fa-gauge drawer-icon"></i> Dashboard Admin</a>
        @else
        <a href="{{ route('laporan.create') }}"    class="drawer-link drawer-link-highlight"><i class="fa-solid fa-plus drawer-icon"></i> Buat Laporan</a>
        <a href="{{ route('laporan.riwayat') }}" class="drawer-link"><i class="fa-solid fa-clock-rotate-left drawer-icon"></i> Riwayat Laporan</a>
        <a href="{{ route('permintaan.create') }}" class="drawer-link"><i class="fa-solid fa-database drawer-icon"></i> Permintaan Data</a>
        @endif

        @else
        <a href="{{ route('beranda') }}"          class="drawer-link"><i class="fa-solid fa-house drawer-icon"></i> Beranda</a>
        <a href="{{ route('peta.index') }}"        class="drawer-link"><i class="fa-solid fa-map drawer-icon"></i> Peta</a>
        <a href="{{ route('informasi.index') }}"   class="drawer-link"><i class="fa-solid fa-book drawer-icon"></i> Informasi</a>
        <a href="{{ route('statistik.index') }}"   class="drawer-link"><i class="fa-solid fa-chart-bar drawer-icon"></i> Tren Laporan</a>
        <div class="drawer-sep"></div>
        <a href="{{ route('login') }}" class="drawer-link drawer-link-locked">
            <i class="fa-solid fa-lock drawer-icon"></i> Buat Laporan
            <span class="drawer-badge-login">Masuk dulu</span>
        </a>
        @endif
    </div>

    @auth
    <div class="drawer-footer">
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="btn btn-gray btn-block">
                <i class="fa-solid fa-right-from-bracket"></i> Keluar
            </button>
        </form>
    </div>
    @endauth
</div>

@if(session('success'))
<div class="container" style="padding-top:1rem;padding-bottom:0;">
    <div class="alert alert-success" id="flash-msg">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
</div>
@endif
@if(session('error'))
<div class="container" style="padding-top:1rem;padding-bottom:0;">
    <div class="alert alert-error" id="flash-msg">
        <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
    </div>
</div>
@endif

<main>@yield('content')</main>

<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-col footer-brand">
            <div class="footer-logo">
                <img src="{{ asset('images/Logo Sidugong.png') }}" alt="Simadang">
                <span>Simadang</span>
            </div>
            <p>Sistem Informasi Konservasi Dugong, memantau dan melindungi dugong beserta habitat padang lamun di perairan Bintan, Kepulauan Riau.</p>
        </div>

        <div class="footer-col">
            <h4>Jelajahi</h4>
            <a href="{{ route('beranda') }}">Beranda</a>
            <a href="{{ route('peta.index') }}">Peta Sebaran</a>
            <a href="{{ route('informasi.index') }}">Informasi Dugong</a>
            <a href="{{ route('statistik.index') }}">Tren Laporan</a>
            @if(!$isGuest)
            <a href="{{ route('laporan.index') }}">Laporan Masyarakat</a>
            @endif
        </div>

        <div class="footer-col">
            <h4>Kontak & Lembaga</h4>
            <a href="mailto:bpspl.padang@kkp.go.id"><i class="fa-solid fa-building-columns"></i> BPSPL Padang (Wil. Kepri)</a>
            <a href="mailto:info@wwf.id"><i class="fa-solid fa-leaf"></i> WWF Indonesia</a>
            <a href="{{ route('permintaan.create') }}"><i class="fa-solid fa-database"></i> Permintaan Data</a>
        </div>

        <div class="footer-col">
            <h4>Akun</h4>
            @if($isAdmin)
                <a href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
            @elseif($isWarga)
                <a href="{{ route('laporan.create') }}">Buat Laporan</a>
                <a href="{{ route('laporan.riwayat') }}">Riwayat Laporan</a>
            @else
                <a href="{{ route('login') }}">Login</a>
                <a href="{{ route('register') }}">Daftar Akun</a>
            @endif
        </div>
    </div>

    <div class="footer-bottom">
        <span>&copy; {{ date('Y') }} Simadang, Konservasi Dugong Bintan.</span>
        <span>Dibangun untuk pelestarian ekosistem laut Kepulauan Riau.</span>
    </div>
</footer>

<script>
// Drawer
const burger  = document.getElementById('burgerBtn');
const drawer  = document.getElementById('mobileDrawer');
const overlay = document.getElementById('mobileOverlay');
const closeBtn= document.getElementById('drawerClose');
function openDrawer()  { drawer.classList.add('active'); overlay.classList.add('active'); burger.classList.add('active'); document.body.style.overflow='hidden'; }
function closeDrawer() { drawer.classList.remove('active'); overlay.classList.remove('active'); burger.classList.remove('active'); document.body.style.overflow=''; }
burger?.addEventListener('click', openDrawer);
closeBtn?.addEventListener('click', closeDrawer);
overlay?.addEventListener('click', closeDrawer);

// Profile dropdown
function toggleDropdown() {
    const dd = document.getElementById('profileDropdown');
    const ch = document.getElementById('chevronIcon');
    dd?.classList.toggle('open');
    ch?.style.setProperty('transform', dd?.classList.contains('open') ? 'rotate(180deg)' : '');
}
document.addEventListener('click', function(e) {
    const wrap = document.querySelector('.profile-wrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('profileDropdown')?.classList.remove('open');
        const ch = document.getElementById('chevronIcon');
        if (ch) ch.style.transform = '';
    }
});

// Flash dismiss
const flash = document.getElementById('flash-msg');
if (flash) {
    setTimeout(() => { flash.style.transition='opacity .5s'; flash.style.opacity='0'; }, 3500);
    setTimeout(() => { flash.remove(); }, 4000);
}

// Active drawer
const path = location.pathname;
document.querySelectorAll('.drawer-link').forEach(a => {
    const href = a.getAttribute('href');
    if (href && href !== '/' && path.startsWith(href)) a.classList.add('active');
    if (href === '/' && path === '/') a.classList.add('active');
});
</script>
@stack('scripts')
</body>
</html>
