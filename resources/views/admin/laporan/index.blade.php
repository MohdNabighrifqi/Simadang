{{-- resources/views/admin/laporan/index.blade.php --}}
@extends('layouts.admin')
@section('title','Kelola Laporan')

@section('content')

@include('partials.breadcrumb', ['items' => [
    ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
    ['label' => 'Kelola Laporan'],
]])

    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;margin-bottom:1.2rem;">
        <div class="page-header" style="margin:0;">
            <h2>Kelola Laporan</h2>
            <p>Verifikasi, edit, dan hapus semua laporan yang masuk.</p>
        </div>
        <a href="{{ route('admin.export.form', request()->query()) }}" class="btn btn-teal btn-sm">
            <i class="fa-solid fa-file-export"></i> Ekspor Data
        </a>
    </div>

    {{-- Search & Filter --}}
    <form method="GET" action="{{ route('admin.laporan.index') }}" class="filter-bar">
        <div class="filter-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="cari" placeholder="Cari lokasi penampakan / nama pelapor..."
                   class="form-control" value="{{ request('cari') }}" onchange="this.form.submit()">
        </div>
        <select name="status" class="form-control" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="menunggu"      {{ request('status')==='menunggu'      ?'selected':'' }}>Menunggu</option>
            <option value="terverifikasi" {{ request('status')==='terverifikasi' ?'selected':'' }}>Terverifikasi</option>
            <option value="ditolak"       {{ request('status')==='ditolak'       ?'selected':'' }}>Ditolak</option>
        </select>
        <select name="kondisi" class="form-control" onchange="this.form.submit()">
            <option value="">Semua Kondisi</option>
            <option value="hidup"           {{ request('kondisi')==='hidup'           ?'selected':'' }}>Hidup</option>
            <option value="mati_terdampar"  {{ request('kondisi')==='mati_terdampar'  ?'selected':'' }}>Mati Terdampar</option>
            <option value="mati_tertangkap" {{ request('kondisi')==='mati_tertangkap' ?'selected':'' }}>Mati Tertangkap</option>
        </select>
        <div class="filter-sep"></div>
        <div class="filter-daterange">
            <input type="date" name="tanggal_dari" class="form-control" title="Dari tanggal"
                   value="{{ request('tanggal_dari') }}" onchange="this.form.submit()">
            <span>-</span>
            <input type="date" name="tanggal_sampai" class="form-control" title="Sampai tanggal"
                   value="{{ request('tanggal_sampai') }}" onchange="this.form.submit()">
        </div>
        <a href="{{ route('admin.laporan.index') }}" class="filter-reset">
            <i class="fa-solid fa-rotate-left"></i> Reset
        </a>
    </form>

    {{-- Mobile: card list --}}
    <div class="mobile-list">
        @forelse($laporan as $lap)
        <a href="{{ route('admin.laporan.show', $lap->id) }}" class="mobile-card-link">
            <div class="mobile-card">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
                    <div>
                        <div class="table-mono" style="font-size:.75rem;color:var(--text-muted);">{{ $lap->kode }}</div>
                        <div style="font-weight:500;font-size:.875rem;margin-top:2px;">
                            @if($lap->jenis?->nama === 'dugong')
                                <i class="fa-solid fa-fish" style="color:var(--primary,#005F73)"></i> Dugong
                            @else
                                <i class="fa-solid fa-leaf" style="color:var(--amber,#CA6702)"></i> Habitat
                            @endif
                        </div>
                    </div>
                    @include('partials.badge-status', ['status' => $lap->status])
                </div>
                <div style="display:flex;gap:1rem;margin-top:6px;font-size:.75rem;color:var(--text-muted);flex-wrap:wrap;">
                    <span><i class="fa-solid fa-calendar"></i> {{ \Carbon\Carbon::parse($lap->tanggal)->translatedFormat('d M Y') }}</span>
                    <span><i class="fa-solid fa-location-dot"></i> {{ $lap->lokasi?->nama ?? '-' }}</span>
                    <span><i class="fa-solid fa-user"></i> {{ $lap->nama_pelapor ?? $lap->user?->name ?? '-' }}</span>
                </div>
                <div style="margin-top:6px;font-size:.75rem;color:var(--primary,#005F73);font-weight:500;">
                    Tap untuk detail <i class="fa-solid fa-arrow-right" style="font-size:.65rem;"></i>
                </div>
            </div>
        </a>
        @empty
        <div style="padding:2.5rem;text-align:center;color:var(--text-muted);">Belum ada laporan.</div>
        @endforelse
    </div>

    {{-- Desktop: table --}}
    <div class="card card-flush desktop-table">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th><th>Tanggal</th><th>Jenis</th>
                        <th>Kondisi</th><th>Lokasi</th><th>Pelapor</th><th>Status</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $lap)
                    <tr>
                        <td class="table-mono">{{ $lap->kode }}</td>
                        <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($lap->tanggal)->translatedFormat('d M Y') }}</td>
                        <td>
                            @if($lap->jenis?->nama === 'dugong')
                                <i class="fa-solid fa-fish" style="color:var(--primary,#005F73)"></i> Dugong
                            @else
                                <i class="fa-solid fa-leaf" style="color:var(--amber,#CA6702)"></i> Habitat
                            @endif
                        </td>
                        <td>
                            @php $k = $lap->kondisi?->nama; @endphp
                            @if($k==='hidup') <span style="color:#2196F3;font-size:.78rem;font-weight:600;">Hidup</span>
                            @elseif($k==='mati_terdampar') <span style="color:#424242;font-size:.78rem;font-weight:600;">Terdampar</span>
                            @elseif($k==='mati_tertangkap') <span style="color:#E65100;font-size:.78rem;font-weight:600;">Tertangkap</span>
                            @else <span style="color:var(--text-muted);font-size:.78rem;">-</span>
                            @endif
                        </td>
                        <td>{{ $lap->lokasi?->nama ?? '-' }}</td>
                        <td>{{ $lap->nama_pelapor ?? $lap->user?->name ?? '-' }}</td>
                        <td>@include('partials.badge-status', ['status' => $lap->status])</td>
                        <td>
                            <a href="{{ route('admin.laporan.show', $lap->id) }}"
                               class="btn btn-gray btn-sm" title="Detail">
                                <i class="fa-solid fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:2.5rem;color:var(--text-muted);">
                            Belum ada laporan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($laporan->hasPages())
    <div class="pagination">{{ $laporan->withQueryString()->links() }}</div>
    @endif
@endsection
