{{-- resources/views/admin/export/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'Ekspor Data')
@section('content')

@include('partials.breadcrumb', ['items' => [
    ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
    ['label' => 'Ekspor Data'],
]])

<div class="page-header">
    <h2>Ekspor Data</h2>
    <p>Pilih jenis data, format, dan filter yang diinginkan. Pratinjau di bawah akan menyesuaikan secara otomatis sebelum Anda mengunduh.</p>
</div>

{{-- Formulir pilihan --}}
<div class="card">
    <form method="GET" action="{{ route('admin.export.form') }}" class="export-form">
        <div class="form-group">
            <label class="form-label">Jenis Data</label>
            <select name="tipe" class="form-control" onchange="this.form.submit()">
                <option value="laporan"   {{ $tipe==='laporan'   ?'selected':'' }}>Data Laporan Dugong</option>
                <option value="statistik" {{ $tipe==='statistik' ?'selected':'' }}>Data Statistik Konservasi</option>
                <option value="lengkap"   {{ $tipe==='lengkap'   ?'selected':'' }}>Data Lengkap</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Format</label>
            <select name="format" class="form-control" onchange="this.form.submit()">
                <option value="xlsx" {{ $format==='xlsx' ?'selected':'' }}>Excel (.xlsx)</option>
                <option value="pdf"  {{ $format==='pdf'  ?'selected':'' }}>PDF (.pdf)</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="menunggu"      {{ request('status')==='menunggu'      ?'selected':'' }}>Menunggu</option>
                <option value="terverifikasi" {{ request('status')==='terverifikasi' ?'selected':'' }}>Terverifikasi</option>
                <option value="ditolak"       {{ request('status')==='ditolak'       ?'selected':'' }}>Ditolak</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Jenis Laporan</label>
            <select name="jenis" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Jenis</option>
                <option value="dugong"  {{ request('jenis')==='dugong'  ?'selected':'' }}>Dugong</option>
                <option value="habitat" {{ request('jenis')==='habitat' ?'selected':'' }}>Habitat</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Lokasi</label>
            <select name="lokasi_id" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Lokasi</option>
                @foreach($lokasiList as $l)
                <option value="{{ $l->id }}" {{ (string) request('lokasi_id') === (string) $l->id ? 'selected' : '' }}>{{ $l->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}" onchange="this.form.submit()">
        </div>
        <div class="form-group">
            <label class="form-label">Periode Tahun</label>
            <div style="display:flex;gap:8px;align-items:center;">
                <input type="number" name="periode_dari" min="2000" max="{{ date('Y') }}" class="form-control"
                       value="{{ request('periode_dari') }}" placeholder="Dari" onchange="this.form.submit()">
                <span style="color:var(--text-muted);">-</span>
                <input type="number" name="periode_sampai" min="2000" max="{{ date('Y') }}" class="form-control"
                       value="{{ request('periode_sampai') }}" placeholder="Sampai" onchange="this.form.submit()">
            </div>
        </div>

        <div class="export-form-actions">
            <a href="{{ route('admin.export.form') }}" class="btn btn-gray btn-sm">
                <i class="fa-solid fa-rotate-left"></i> Reset
            </a>
            <a href="{{ route('admin.export.laporan', array_merge(request()->query(), ['tipe' => $tipe, 'format' => $format])) }}" class="btn btn-teal">
                <i class="fa-solid {{ $format==='pdf' ? 'fa-file-pdf' : 'fa-file-excel' }}"></i>
                Ekspor {{ $format==='pdf' ? 'PDF' : 'Excel' }} Sekarang
            </a>
        </div>
    </form>
</div>

{{-- Ringkasan cocok filter --}}
<div class="card" style="background:var(--primary-light);border-color:rgba(0,95,115,.15);">
    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        <i class="fa-solid fa-circle-info" style="color:var(--primary);font-size:1.3rem;"></i>
        <div>
            <strong style="color:var(--primary);">{{ $laporan->count() }} laporan</strong>
            <span style="color:var(--text-muted);"> cocok dengan filter yang dipilih, pratinjau berikut mencerminkan isi berkas yang akan diunduh.</span>
        </div>
    </div>
</div>

@if(in_array($tipe, ['laporan', 'lengkap']))
{{-- Preview Data Laporan --}}
<div class="card card-flush">
    <div class="card-header">
        <span class="card-title" style="margin-bottom:0;">Pratinjau Data Laporan</span>
        <span style="font-size:.78rem;color:var(--text-muted);">Menampilkan {{ min(10, $laporan->count()) }} dari {{ $laporan->count() }} baris</span>
    </div>
    <div class="table-wrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Lokasi</th>
                    <th>Kondisi</th>
                    <th style="text-align:center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporan->take(10) as $lap)
                <tr>
                    <td class="table-mono">{{ $lap->kode }}</td>
                    <td>{{ $lap->tanggal?->format('d-m-Y') ?? '-' }}</td>
                    <td>{{ $lap->jenis?->nama === 'dugong' ? 'Dugong' : 'Habitat' }}</td>
                    <td>{{ $lap->lokasi?->nama ?? '-' }}</td>
                    <td>{{ $lap->kondisi_label }}</td>
                    <td style="text-align:center;">@include('partials.badge-status', ['status' => $lap->status])</td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;color:var(--text-muted);">Tidak ada data yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endif

@if($tipe === 'statistik')
<div class="card" style="text-align:center;color:var(--text-muted);padding:2rem;">
    <i class="fa-solid fa-file-excel" style="font-size:1.5rem;margin-bottom:.5rem;display:block;color:var(--primary);"></i>
    Berkas Data Statistik berisi ringkasan, tren bulanan &amp; tahunan, wilayah prioritas, dan kondisi dugong lengkap dengan grafik. Unduh berkasnya untuk melihat visualisasinya.
</div>
@endif

@endsection

@push('styles')
<style>
.export-form { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; }
.export-form .form-group:last-of-type { grid-column: span 1; }
.export-form-actions { grid-column: 1 / -1; display:flex; justify-content:flex-end; gap:8px; flex-wrap:wrap; margin-top:.25rem; }
@media (max-width:900px) { .export-form { grid-template-columns:repeat(2,1fr); } }
@media (max-width:600px) { .export-form { grid-template-columns:1fr; } .export-form-actions { justify-content:stretch; } .export-form-actions .btn { flex:1; justify-content:center; } }
</style>
@endpush
