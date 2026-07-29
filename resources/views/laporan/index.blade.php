{{-- resources/views/laporan/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Daftar Laporan')
@section('content')
<div class="container">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
    <div class="page-header" style="margin:0;">
        <h2>Laporan Konservasi</h2>
        <p>Semua laporan penampakan dugong dan kondisi habitat.</p>
    </div>
    @auth
<div style="display:flex;gap:8px;flex-wrap:wrap;">
    <a href="{{ route('laporan.create') }}" class="btn btn-teal">
        <i class="fa-solid fa-plus"></i> Buat Laporan
    </a>
    <a href="{{ route('laporan.riwayat') }}" class="btn btn-gray">
        <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Saya
    </a>
    <a href="{{ route('permintaan.create') }}" class="btn btn-gray">
        <i class="fa-solid fa-database"></i> Permintaan Data
    </a>
</div>
    @else
    <a href="{{ route('login') }}" class="btn btn-gray">
        <i class="fa-solid fa-right-to-bracket"></i> Masuk untuk Melapor
    </a>
    @endauth
</div>

    <form method="GET" action="{{ route('laporan.index') }}" class="filter-bar">
        <div class="filter-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="cari_lokasi" placeholder="Cari lokasi penampakan..."
                   class="form-control" value="{{ request('cari_lokasi') }}" onchange="this.form.submit()">
        </div>
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
        <a href="{{ route('laporan.index') }}" class="filter-reset">
            <i class="fa-solid fa-rotate-left"></i> Reset
        </a>
    </form>
    <p style="font-size:.76rem;color:var(--text-muted);margin-top:-.6rem;margin-bottom:1.2rem;">
        <i class="fa-solid fa-circle-info"></i> Hanya laporan yang sudah <strong>terverifikasi</strong> admin yang ditampilkan di sini.
    </p>

    <div class="card card-flush">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Kondisi</th>
                        <th>Lokasi</th>
                        <th>Pelapor</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($laporan as $lap)
                    <tr>
                        <td class="table-mono">{{ $lap->kode }}</td>
                        <td style="white-space:nowrap;">
                            {{ \Carbon\Carbon::parse($lap->tanggal)->translatedFormat('d M Y') }}
                        </td>
                        <td>
                            @if($lap->jenis?->nama === 'dugong')
                                <i class="fa-solid fa-fish" style="color:var(--primary,#005F73)"></i> Dugong
                            @else
                                <i class="fa-solid fa-leaf" style="color:var(--amber,#CA6702)"></i> Habitat
                            @endif
                        </td>
                        <td>
                            @php $k = $lap->kondisi?->nama; @endphp
                            @if($k === 'hidup')
                                <span style="color:#2196F3;font-size:.78rem;font-weight:600;">
                                    <i class="fa-solid fa-fish"></i> Hidup
                                </span>
                            @elseif($k === 'mati_terdampar')
                                <span style="color:#424242;font-size:.78rem;font-weight:600;">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Terdampar
                                </span>
                            @elseif($k === 'mati_tertangkap')
                                <span style="color:#E65100;font-size:.78rem;font-weight:600;">
                                    <i class="fa-solid fa-skull-crossbones"></i> Tertangkap
                                </span>
                            @else
                                <span style="color:var(--text-muted);font-size:.78rem;">-</span>
                            @endif
                        </td>
                        <td>{{ $lap->lokasi?->nama ?? '-' }}</td>
                        <td>{{ $lap->nama_pelapor ?? $lap->user?->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('laporan.show', $lap->id) }}"
                               class="btn btn-gray btn-sm">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:2.5rem;color:var(--text-muted);">
                            <i class="fa-solid fa-inbox" style="font-size:2rem;display:block;margin-bottom:.5rem;opacity:.3"></i>
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
</div>
@endsection
