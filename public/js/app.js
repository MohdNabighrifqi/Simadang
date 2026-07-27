/**
 * SiKoDung — public/js/app.js
 * Interaktivitas frontend: tab, peta, chart, toast
 */

/* ── BAR CHART ── */
function renderBarChart(containerId, labels, data) {
    const el = document.getElementById(containerId);
    if (!el) return;
    const max = Math.max(...data, 1);
    el.innerHTML = data.map((v, i) => `
        <div class="bar-col">
            <span class="bar-val">${v}</span>
            <div class="bar-rect" style="height:${Math.round(v/max*100)}px;opacity:${(0.45 + v/max*0.55).toFixed(2)}"></div>
            <span class="bar-lbl">${labels[i]}</span>
        </div>
    `).join('');
}

/* ── AREA/LINE CHART (admin dashboard) ── */
function renderAreaChart(containerId, labels, data) {
    const el = document.getElementById(containerId);
    if (!el) return;
    const w = el.clientWidth || 600;
    const h = 220;
    const padX = 8, padY = 18;
    const max = Math.max(...data, 1);
    const stepX = data.length > 1 ? (w - padX * 2) / (data.length - 1) : 0;
    const points = data.map((v, i) => [
        padX + i * stepX,
        h - padY - (v / max) * (h - padY * 2),
    ]);
    const linePath = points.map((p, i) => (i === 0 ? 'M' : 'L') + p[0].toFixed(1) + ',' + p[1].toFixed(1)).join(' ');
    const areaPath = linePath
        + ` L${points[points.length - 1][0].toFixed(1)},${h - padY}`
        + ` L${points[0][0].toFixed(1)},${h - padY} Z`;
    const dots = points.map((p, i) =>
        `<circle cx="${p[0].toFixed(1)}" cy="${p[1].toFixed(1)}" r="3.5" fill="#fff" stroke="#005F73" stroke-width="2"><title>${labels[i]}: ${data[i]}</title></circle>`
    ).join('');
    const labelsHtml = labels.map(l => `<span>${l}</span>`).join('');

    el.innerHTML = `
        <svg viewBox="0 0 ${w} ${h}" preserveAspectRatio="none" style="width:100%;height:${h}px;overflow:visible;">
            <defs>
                <linearGradient id="${containerId}-grad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#0A9396" stop-opacity="0.35"/>
                    <stop offset="100%" stop-color="#0A9396" stop-opacity="0"/>
                </linearGradient>
            </defs>
            <path d="${areaPath}" fill="url(#${containerId}-grad)" stroke="none"></path>
            <path d="${linePath}" fill="none" stroke="#005F73" stroke-width="2.5" stroke-linejoin="round" stroke-linecap="round"></path>
            ${dots}
        </svg>
        <div style="display:flex;justify-content:space-between;margin-top:6px;font-size:.68rem;color:var(--text-muted,#6B7280);">${labelsHtml}</div>
    `;
}

/* ── MAP PINS ── */
function initMapPins() {
    const pins = document.querySelectorAll('.map-pin[data-title]');
    const popup = document.getElementById('map-popup');
    if (!popup) return;
    pins.forEach(pin => {
        pin.addEventListener('click', () => {
            document.getElementById('map-popup-title').textContent = pin.dataset.title;
            document.getElementById('map-popup-desc').textContent  = pin.dataset.desc;
            popup.style.display = 'block';
            clearTimeout(popup._t);
            popup._t = setTimeout(() => popup.style.display = 'none', 3500);
        });
    });
    document.addEventListener('click', e => {
        if (!e.target.closest('.map-pin') && !e.target.closest('#map-popup')) {
            popup.style.display = 'none';
        }
    });
}

/* ── TABS ── */
function initTabs() {
    document.querySelectorAll('[data-tab-target]').forEach(btn => {
        btn.addEventListener('click', () => {
            const group = btn.closest('[data-tab-group]')?.dataset.tabGroup
                       ?? btn.dataset.tabGroup;
            document.querySelectorAll(`[data-tab-group="${group}"] [data-tab-target]`)
                    .forEach(b => b.classList.remove('active'));
            document.querySelectorAll(`[data-tab-group="${group}"] .tab-pane`)
                    .forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            const target = document.getElementById(btn.dataset.tabTarget);
            if (target) target.classList.add('active');
        });
    });
}

/* ── TYPE TOGGLE (form laporan) ── */
function initTypeToggle() {
    const btns  = document.querySelectorAll('.type-btn');
    const input = document.getElementById('jenis_laporan');
    btns.forEach(btn => {
        btn.addEventListener('click', () => {
            btns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            if (input) input.value = btn.dataset.value;
            // toggle jumlah_dugong field
            const jmlWrap = document.getElementById('jumlah-wrap');
            if (jmlWrap) jmlWrap.style.display = btn.dataset.value === 'dugong' ? '' : 'none';
        });
    });
}

/* ── FLASH AUTO-DISMISS ── */
function initFlash() {
    document.querySelectorAll('.alert[data-auto-dismiss]').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity .4s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        }, 4000);
    });
}

/* ── CONFIRM DELETE ── */
function confirmDelete(formId, msg) {
    if (confirm(msg || 'Yakin ingin menghapus?')) {
        document.getElementById(formId).submit();
    }
}

/* ── ACTIVE NAV ── */
function setActiveNav() {
    const path = window.location.pathname;
    document.querySelectorAll('.nav-link[data-path]').forEach(link => {
        link.classList.toggle('active',
            path === link.dataset.path ||
            (link.dataset.path !== '/' && path.startsWith(link.dataset.path))
        );
    });
}

/* ── INIT ── */
document.addEventListener('DOMContentLoaded', () => {
    setActiveNav();
    initMapPins();
    initTabs();
    initTypeToggle();
    initFlash();

    // chart — data dikirim dari blade via window.__chart
    if (window.__chart) {
        renderBarChart('bar-chart', window.__chart.labels, window.__chart.data);
    }
    if (window.__trendChart) {
        renderAreaChart('trend-chart', window.__trendChart.labels, window.__trendChart.data);
    }
});

/* ── BURGER / MOBILE DRAWER ─────────────────────────────────────────────── */
function initBurger() {
    const burgerBtn     = document.getElementById('burgerBtn');
    const mobileDrawer  = document.getElementById('mobileDrawer');
    const mobileOverlay = document.getElementById('mobileOverlay');
    const drawerClose   = document.getElementById('drawerClose');

    if (!burgerBtn) return;

    function openDrawer() {
        burgerBtn.classList.add('active');
        mobileDrawer.classList.add('active');
        mobileOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        burgerBtn.classList.remove('active');
        mobileDrawer.classList.remove('active');
        mobileOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    burgerBtn.addEventListener('click', () => {
        burgerBtn.classList.contains('active') ? closeDrawer() : openDrawer();
    });

    // Tutup saat klik overlay
    mobileOverlay.addEventListener('click', closeDrawer);

    // Tutup saat klik tombol X
    if (drawerClose) drawerClose.addEventListener('click', closeDrawer);

    // Tutup saat klik link di drawer
    document.querySelectorAll('.drawer-link').forEach(link => {
        link.addEventListener('click', closeDrawer);
    });

    // Tutup saat tekan Escape
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeDrawer();
    });

    // Set active link di drawer
    const path = window.location.pathname;
    document.querySelectorAll('.drawer-link').forEach(link => {
        const href = link.getAttribute('href');
        if (href && href !== '/' && path.startsWith(href)) {
            link.classList.add('active');
        } else if (href === '/' && path === '/') {
            link.classList.add('active');
        }
    });
}

// Tambah initBurger ke DOMContentLoaded
document.addEventListener('DOMContentLoaded', () => {
    initBurger();
    initAdminSidebar();
});

/* ── ADMIN SIDEBAR (off-canvas di mobile) ───────────────────────────────── */
function initAdminSidebar() {
    const burger  = document.getElementById('adminBurger');
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('adminOverlay');
    const closeBtn= document.getElementById('adminSidebarClose');
    if (!burger || !sidebar || !overlay) return;

    function open()  { sidebar.classList.add('active'); overlay.classList.add('active'); document.body.style.overflow = 'hidden'; }
    function close() { sidebar.classList.remove('active'); overlay.classList.remove('active'); document.body.style.overflow = ''; }

    burger.addEventListener('click', open);
    overlay.addEventListener('click', close);
    closeBtn?.addEventListener('click', close);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') close(); });
    sidebar.querySelectorAll('.admin-nav-link').forEach(link => link.addEventListener('click', close));
}
