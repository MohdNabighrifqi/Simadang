{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title','Dashboard Admin') - Simadang</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
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
    .pd-header { padding: 12px 14px; border-bottom: 1px solid rgba(0,0,0,.07); background: #F0F8FA; }
    .pd-name  { font-weight: 700; font-size: .875rem; color: #005F73; }
    .pd-email { font-size: .72rem; color: #6B7280; }
    .pd-link {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 14px; font-size: .85rem; color: #1C1C1E;
        text-decoration: none; transition: background .15s;
    }
    .pd-link:hover { background: #F0F8FA; color: #005F73; }
    .pd-link i { width: 16px; color: #6B7280; }
    .pd-link:hover i { color: #005F73; }
    .pd-sep { height: 1px; background: rgba(0,0,0,.07); }
    .pd-logout {
        display: flex; align-items: center; gap: 10px; width: 100%;
        padding: 10px 14px; font-size: .85rem; color: #AE2012;
        background: none; border: none; cursor: pointer;
        font-family: inherit; text-align: left; transition: background .15s;
    }
    .pd-logout:hover { background: #FDECEA; }
    .pd-logout i { width: 16px; }
    </style>
    @stack('styles')
</head>
<body class="admin-body">

@php
    $user = auth()->user();
    $initials = $user
        ? strtoupper(mb_substr($user->name,0,1)) . strtoupper(mb_substr(strrchr(trim($user->name),' '),1,1) ?? '')
        : '';
@endphp

<div class="admin-layout">

    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-sidebar-brand">
            <img src="{{ asset('images/Logo Sidugong.png') }}" alt="Simadang">
            <span>Simadang</span>
            <button class="admin-sidebar-close" id="adminSidebarClose" aria-label="Tutup menu">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="admin-sidebar-section">Menu</div>
        <nav class="admin-sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge admin-nav-icon"></i> Dashboard
            </a>
            <a href="{{ route('admin.laporan.index') }}" class="admin-nav-link {{ request()->is('admin/laporan*') ? 'active' : '' }}">
                <i class="fa-solid fa-clipboard-list admin-nav-icon"></i> Kelola Laporan
            </a>
            <a href="{{ route('admin.permintaan.index') }}" class="admin-nav-link {{ request()->is('admin/permintaan-data*') ? 'active' : '' }}">
                <i class="fa-solid fa-database admin-nav-icon"></i> Permintaan Data
            </a>
            <a href="{{ route('admin.users.index') }}" class="admin-nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                <i class="fa-solid fa-users admin-nav-icon"></i> Kelola Pengguna
            </a>
        </nav>

        <div class="admin-sidebar-section">Lainnya</div>
        <nav class="admin-sidebar-nav">
            <a href="{{ route('admin.export.form') }}" class="admin-nav-link">
                <i class="fa-solid fa-file-export admin-nav-icon"></i> Ekspor Data
            </a>
            <a href="{{ route('beranda') }}" class="admin-nav-link">
                <i class="fa-solid fa-arrow-up-right-from-square admin-nav-icon"></i> Lihat Situs Publik
            </a>
        </nav>

        <div class="admin-sidebar-footer">
            Simadang Admin &copy; {{ date('Y') }}
        </div>
    </aside>
    <div class="admin-overlay" id="adminOverlay"></div>

    <div class="admin-main-col">
        <header class="admin-topbar">
            <button class="admin-burger" id="adminBurger" aria-label="Buka menu">
                <i class="fa-solid fa-bars"></i>
            </button>

            <div class="admin-search">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="Cari laporan, pengguna...">
            </div>

            <div class="admin-topbar-end">
                <div class="profile-wrap">
                    <button class="admin-icon-btn" id="notifBell" title="Notifikasi" onclick="toggleNotifPanel()">
                        <i class="fa-regular fa-bell"></i>
                        <span class="admin-dot" id="notifDot" style="display:none;"></span>
                    </button>
                    <div class="profile-dropdown" id="notifPanel" style="min-width:280px;">
                        <div class="pd-header">
                            <div class="pd-name">Notifikasi</div>
                        </div>
                        <div id="notifList">
                            <div class="pd-link" style="color:var(--text-muted);cursor:default;">
                                <i class="fa-solid fa-spinner fa-spin"></i> Memuat...
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-wrap">
                    <button class="admin-profile-btn" onclick="toggleDropdown()">
                        <div class="navbar-avatar" style="border-color:var(--border-md);">{{ $initials }}</div>
                        <div class="admin-profile-meta">
                            <span class="admin-profile-name">{{ $user->name }}</span>
                            <span class="admin-profile-role">Administrator</span>
                        </div>
                        <i class="fa-solid fa-chevron-down chevron" id="chevronIcon" style="font-size:.7rem;color:var(--text-muted);transition:transform .18s;"></i>
                    </button>
                    <div class="profile-dropdown" id="profileDropdown">
                        <div class="pd-header">
                            <div class="pd-name">{{ $user->name }}</div>
                            <div class="pd-email">{{ $user->email }}</div>
                        </div>
                        <a href="{{ route('beranda') }}" class="pd-link">
                            <i class="fa-solid fa-house"></i> Situs Publik
                        </a>
                        <div class="pd-sep"></div>
                        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                            @csrf
                            <button type="submit" class="pd-logout">
                                <i class="fa-solid fa-right-from-bracket"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="admin-content">
            @if(session('success'))
            <div class="alert alert-success" id="flash-msg">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-error" id="flash-msg">
                <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
            </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}"></script>
<script>
function toggleDropdown() {
    document.getElementById('notifPanel')?.classList.remove('open');
    const dd = document.getElementById('profileDropdown');
    const ch = document.getElementById('chevronIcon');
    dd?.classList.toggle('open');
    if (ch) ch.style.transform = dd?.classList.contains('open') ? 'rotate(180deg)' : '';
}
function toggleNotifPanel() {
    document.getElementById('profileDropdown')?.classList.remove('open');
    document.getElementById('notifPanel')?.classList.toggle('open');
    if (typeof Notification !== 'undefined' && Notification.permission === 'default') {
        Notification.requestPermission();
    }
}
document.addEventListener('click', function(e) {
    document.querySelectorAll('.profile-wrap').forEach(wrap => {
        if (!wrap.contains(e.target)) {
            wrap.querySelector('.profile-dropdown')?.classList.remove('open');
        }
    });
    if (!e.target.closest('.profile-wrap')) {
        const ch = document.getElementById('chevronIcon');
        if (ch) ch.style.transform = '';
    }
});

/* ── NOTIFIKASI LIVE (poll laporan/permintaan menunggu) ── */
(function () {
    const dot  = document.getElementById('notifDot');
    const bell = document.getElementById('notifBell');
    const list = document.getElementById('notifList');
    if (!dot || !bell) return;

    const STORAGE_KEY = 'sikodung_last_pending_total';
    let lastTotal = parseInt(localStorage.getItem(STORAGE_KEY) ?? '-1', 10);

    function renderList(data) {
        if (!list) return;
        const items = [];
        if (data.menunggu_laporan > 0) {
            items.push(`<a href="{{ route('admin.laporan.index', ['status'=>'menunggu']) }}" class="pd-link">
                <i class="fa-solid fa-clipboard-list"></i> ${data.menunggu_laporan} laporan menunggu verifikasi
            </a>`);
        }
        if (data.menunggu_permintaan > 0) {
            items.push(`<a href="{{ route('admin.permintaan.index', ['status'=>'menunggu']) }}" class="pd-link">
                <i class="fa-solid fa-database"></i> ${data.menunggu_permintaan} permintaan data menunggu
            </a>`);
        }
        list.innerHTML = items.length
            ? items.join('')
            : '<div class="pd-link" style="color:var(--text-muted);cursor:default;"><i class="fa-solid fa-circle-check"></i> Tidak ada notifikasi baru</div>';
    }

    async function cekNotifikasi() {
        try {
            const res = await fetch('{{ route('admin.notifikasi.cek') }}', { headers: { 'Accept': 'application/json' } });
            if (!res.ok) return;
            const data = await res.json();
            const total = (data.menunggu_laporan ?? 0) + (data.menunggu_permintaan ?? 0);

            dot.style.display = total > 0 ? '' : 'none';
            bell.title = total > 0 ? `${total} item menunggu tindakan` : 'Notifikasi';
            renderList(data);

            if (lastTotal >= 0 && total > lastTotal) {
                const selisih = total - lastTotal;
                if (typeof Notification !== 'undefined' && Notification.permission === 'granted') {
                    new Notification('Simadang - Laporan Baru', {
                        body: `${selisih} laporan/permintaan baru menunggu tindakan Anda.`,
                        icon: '{{ asset('images/Logo Sidugong.png') }}',
                    });
                }
            }
            lastTotal = total;
            localStorage.setItem(STORAGE_KEY, String(total));
        } catch (e) { /* diamkan — koneksi mungkin sedang terputus */ }
    }

    cekNotifikasi();
    setInterval(cekNotifikasi, 20000);
})();
</script>
@stack('scripts')
</body>
</html>
