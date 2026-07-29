{{-- resources/views/permintaan/create.blade.php --}}
@extends('layouts.app')
@section('title','Permintaan Data')
@section('content')
<div class="container">

    @include('partials.breadcrumb', ['items' => [
        ['label' => 'Beranda', 'route' => route('beranda')],
        ['label' => 'Permintaan Data'],
    ]])

    <div class="page-header">
        <h2>Permintaan Data Dugong</h2>
        <p>Isi formulir berikut untuk meminta data pengamatan dugong dari sistem Simadang. Admin akan meninjau permintaan Anda dalam 1–3 hari kerja.</p>
    </div>

    <div class="detail-grid permintaan-layout" style="--sidebar-w:320px;">

        <div class="card permintaan-form-card">
            <form method="POST" action="{{ route('permintaan.store') }}">
                @csrf
                <div class="form-grid">

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" 
                            value="{{ auth()->user()->name }}" disabled
                            style="background:var(--surface-2);color:var(--text-muted);">
                        <input type="hidden" name="nama_pemohon" value="{{ auth()->user()->name }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" 
                            value="{{ auth()->user()->email }}" disabled
                            style="background:var(--surface-2);color:var(--text-muted);">
                        <input type="hidden" name="email_pemohon" value="{{ auth()->user()->email }}">
                    </div>

                    <div class="form-group col-2">
                        <label class="form-label">Institusi / Lembaga <span style="font-weight:400;color:var(--text-muted);">(opsional)</span></label>
                        <input type="text" name="institusi"
                               class="form-control @error('institusi') is-invalid @enderror"
                               value="{{ old('institusi') }}"
                               placeholder="Universitas, LSM, instansi pemerintah, dll.">
                        @error('institusi')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group col-2">
                        <label class="form-label">Tujuan Penggunaan Data <span class="required">*</span></label>
                        <input type="text" name="tujuan"
                               class="form-control @error('tujuan') is-invalid @enderror"
                               value="{{ old('tujuan') }}"
                               placeholder="cth: Penelitian skripsi, laporan konservasi, pemetaan habitat">
                        @error('tujuan')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jenis Data yang Diminta <span class="required">*</span></label>
                        <select name="jenis_data" class="form-control @error('jenis_data') is-invalid @enderror">
                            <option value="">- Pilih Jenis Data -</option>
                            <option value="laporan"   {{ old('jenis_data')==='laporan'   ?'selected':'' }}>Data Laporan Dugong</option>
                            <option value="statistik" {{ old('jenis_data')==='statistik' ?'selected':'' }}>Data Statistik Konservasi</option>
                            <option value="lengkap"   {{ old('jenis_data')==='lengkap'   ?'selected':'' }}>Data Lengkap</option>
                        </select>
                        @error('jenis_data')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Format File <span class="required">*</span></label>
                        <select name="format_file" class="form-control @error('format_file') is-invalid @enderror">
                            <option value="xlsx" {{ old('format_file','xlsx')==='xlsx' ?'selected':'' }}>Excel (.xlsx), untuk analisis data</option>
                            <option value="pdf"  {{ old('format_file')==='pdf'         ?'selected':'' }}>PDF (.pdf), untuk dokumentasi/pelaporan</option>
                        </select>
                        @error('format_file')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Periode Data <span style="font-weight:400;color:var(--text-muted);">(opsional)</span></label>
                        <div style="display:flex;gap:8px;align-items:center;">
                            <input type="number" name="periode_dari" min="2000" max="{{ date('Y') }}"
                                   class="form-control" value="{{ old('periode_dari') }}" placeholder="Dari tahun">
                            <span style="color:var(--text-muted);">-</span>
                            <input type="number" name="periode_sampai" min="2000" max="{{ date('Y') }}"
                                   class="form-control" value="{{ old('periode_sampai') }}" placeholder="Sampai tahun">
                        </div>
                    </div>

                    <div class="form-group col-2">
                        <label class="form-label">Keterangan Tambahan <span style="font-weight:400;color:var(--text-muted);">(opsional)</span></label>
                        <textarea name="keterangan" rows="3"
                                  class="form-control @error('keterangan') is-invalid @enderror"
                                  placeholder="Jelaskan kebutuhan data lebih spesifik jika diperlukan...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-teal">
                        Kirim Permintaan
                    </button>
                    <a href="{{ route('beranda') }}" class="btn btn-gray">Batal</a>
                </div>
            </form>
        </div>

        {{-- Sidebar info --}}
        <div class="permintaan-sidebar" style="display:flex;flex-direction:column;gap:1rem;">
            <div class="card">
                <div class="card-title" style="font-size:.875rem;margin-bottom:.9rem;">Data yang Tersedia</div>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    <div>
                        <div style="font-weight:700;font-size:.82rem;color:var(--primary);margin-bottom:2px;">
                            <i class="fa-solid fa-clipboard-list"></i> Data Laporan Dugong
                        </div>
                        <p style="font-size:.78rem;color:var(--text-muted);line-height:1.65;">
                            Data mentah hasil pelaporan: tanggal, lokasi, koordinat GPS, jumlah &amp; kondisi dugong, serta status verifikasi.
                        </p>
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:.82rem;color:var(--primary);margin-bottom:2px;">
                            <i class="fa-solid fa-chart-column"></i> Data Statistik Konservasi
                        </div>
                        <p style="font-size:.78rem;color:var(--text-muted);line-height:1.65;">
                            Ringkasan total laporan, statistik bulanan &amp; tahunan, wilayah prioritas konservasi, serta kondisi dugong.
                        </p>
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:.82rem;color:var(--primary);margin-bottom:2px;">
                            <i class="fa-solid fa-layer-group"></i> Data Lengkap
                        </div>
                        <p style="font-size:.78rem;color:var(--text-muted);line-height:1.65;">
                            Gabungan Data Laporan Dugong dan Data Statistik Konservasi dalam satu berkas.
                        </p>
                    </div>
                </div>
                <div style="margin-top:1rem;padding-top:.85rem;border-top:1px solid var(--border);font-size:.78rem;color:var(--text-muted);line-height:1.7;">
                    <strong style="color:var(--text);">Format tersedia:</strong><br>
                    Excel (.xlsx), untuk kebutuhan analisis data.<br>
                    PDF (.pdf), untuk kebutuhan dokumentasi atau pelaporan.
                </div>
            </div>
            <div class="card" style="background:var(--primary-light);border-color:rgba(0,95,115,.15);">
                <div class="card-title" style="font-size:.875rem;color:var(--primary);">Proses Permintaan</div>
                <div style="display:flex;flex-direction:column;gap:10px;font-size:.82rem;color:var(--text);">
                    <div style="display:flex;gap:10px;">
                        <span style="width:22px;height:22px;background:var(--primary);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;flex-shrink:0;">1</span>
                        <span>Isi dan kirim formulir permintaan</span>
                    </div>
                    <div style="display:flex;gap:10px;">
                        <span style="width:22px;height:22px;background:var(--primary);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;flex-shrink:0;">2</span>
                        <span>Admin meninjau permintaan (1–3 hari kerja)</span>
                    </div>
                    <div style="display:flex;gap:10px;">
                        <span style="width:22px;height:22px;background:var(--primary);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;flex-shrink:0;">3</span>
                        <span>Data dikirim ke email yang Anda daftarkan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Mobile: seluruh sidebar (Data Tersedia + Proses Permintaan) tampil di atas form */
@media (max-width: 768px) {
    .permintaan-layout .permintaan-sidebar { order: -1; }
}
</style>
@endpush
