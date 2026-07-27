{{-- resources/views/laporan/history.blade.php --}}
@extends('layouts.app')
@section('title', 'Riwayat Laporan Saya')

@section('content')
<div class="container">

    @include('partials.breadcrumb', ['items' => [
        ['label' => 'Beranda', 'route' => route('beranda')],
        ['label' => 'Laporan', 'route' => route('laporan.index')],
        ['label' => 'Riwayat Saya'],
    ]])

    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
        <div class="page-header" style="margin:0;">
            <h2>Riwayat Laporan Saya</h2>
            <p>Semua laporan yang pernah Anda kirimkan melalui SiKoDung.</p>
        </div>
        <a href="{{ route('laporan.create') }}" class="btn btn-teal">
            <i class="fa-solid fa-plus"></i> Buat Laporan Baru
        </a>
    </div>

    {{-- Statistik mini --}}
    <div class="stat-grid" style="margin-bottom:1.5rem;">
        <div class="stat-card stat-teal">
            <div class="stat-label">Total Laporan</div>
            <div class="stat-value">{{ $laporan->total() }}</div>
            <div class="stat-sub">semua yang pernah dikirim</div>
        </div>
        <div class="stat-card stat-blue">
            <div class="stat-label">Terverifikasi</div>
            <div class="stat-value">{{ $jumlahStatus['terverifikasi'] }}</div>
            <div class="stat-sub">diterima admin</div>
        </div>
        <div class="stat-card stat-amber">
            <div class="stat-label">Menunggu</div>
            <div class="stat-value">{{ $jumlahStatus['menunggu'] }}</div>
            <div class="stat-sub">perlu verifikasi</div>
        </div>
        <div class="stat-card stat-coral">
            <div class="stat-label">Ditolak</div>
            <div class="stat-value">{{ $jumlahStatus['ditolak'] }}</div>
            <div class="stat-sub">lihat catatan admin</div>
        </div>
    </div>

    @if($laporan->isEmpty())
    <div class="card" style="text-align:center;padding:3.5rem 2rem;">
        <div style="width:64px;height:64px;background:var(--primary-light,#E0F0F4);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
            <i class="fa-solid fa-clipboard-list" style="font-size:1.6rem;color:var(--primary,#005F73);"></i>
        </div>
        <h3 style="font-size:1.1rem;margin-bottom:.4rem;">Belum ada laporan</h3>
        <p style="color:var(--text-muted);font-size:.875rem;margin-bottom:1.5rem;max-width:320px;margin-left:auto;margin-right:auto;">
            Anda belum pernah membuat laporan. Mulai laporkan penampakan dugong sekarang!
        </p>
        <a href="{{ route('laporan.create') }}" class="btn btn-teal">
            <i class="fa-solid fa-pen-to-square"></i> Buat Laporan Pertama
        </a>
    </div>
    @else
    <div class="card card-flush">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Catatan Admin</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporan as $lap)
                    <tr>
                        <td class="table-mono">{{ $lap->kode }}</td>
                        <td>{{ \Carbon\Carbon::parse($lap->tanggal)->translatedFormat('d M Y') }}</td>
                        <td>
                            @if($lap->jenis?->nama === 'dugong')
                                <i class="fa-solid fa-fish" style="color:var(--primary,#005F73)"></i> Dugong
                            @else
                                <i class="fa-solid fa-leaf" style="color:var(--amber,#CA6702)"></i> Habitat
                            @endif
                        </td>
                        <td>{{ $lap->lokasi?->nama ?? '—' }}</td>
                        <td>@include('partials.badge-status', ['status' => $lap->status])</td>
                        <td style="max-width:180px;">
                            @if($lap->catatan)
                                <span style="font-size:.78rem;color:var(--text-muted);">
                                    {{ Str::limit($lap->catatan, 50) }}
                                </span>
                            @else
                                <span style="color:var(--border-md);font-size:.78rem;">—</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('laporan.show', $lap->id) }}"
                               class="btn btn-gray btn-sm">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($laporan->hasPages())
    <div class="pagination">{{ $laporan->links() }}</div>
    @endif
    @endif
</div>
@endsection
