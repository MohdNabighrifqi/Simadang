{{-- resources/views/permintaan/sukses.blade.php --}}
@extends('layouts.app')
@section('title','Permintaan Terkirim')
@section('content')
<div class="container">
    <div style="max-width:520px;margin:3rem auto;text-align:center;">
        <div style="width:72px;height:72px;background:var(--primary-light);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
            <i class="fa-solid fa-circle-check" style="font-size:2rem;color:var(--primary);"></i>
        </div>
        <h2 style="font-size:1.4rem;margin-bottom:.5rem;">Permintaan Berhasil Dikirim</h2>
        <p style="color:var(--text-muted);margin-bottom:1.5rem;line-height:1.7;">
            Permintaan data Anda telah diterima. Admin akan meninjau dan mengirimkan data ke
            <strong>{{ $permintaan->email_pemohon }}</strong> dalam 1–3 hari kerja.
        </p>
        <div class="card" style="text-align:left;margin-bottom:1.5rem;">
            <div class="card-title" style="font-size:.875rem;">Ringkasan Permintaan</div>
            <div style="display:flex;flex-direction:column;gap:8px;font-size:.85rem;">
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--text-muted);">Nama</span>
                    <span style="font-weight:500;">{{ $permintaan->nama_pemohon }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--text-muted);">Jenis Data</span>
                    <span style="font-weight:500;">{{ $permintaan->jenis_data_label }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--text-muted);">Tujuan</span>
                    <span style="font-weight:500;">{{ $permintaan->tujuan }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;">
                    <span style="color:var(--text-muted);">Status</span>
                    <span style="font-weight:600;color:var(--amber);">Menunggu Tinjauan Admin</span>
                </div>
            </div>
        </div>
        <div style="display:flex;gap:10px;justify-content:center;">
            <a href="{{ route('beranda') }}" class="btn btn-teal">Kembali ke Beranda</a>
            <a href="{{ route('permintaan.create') }}" class="btn btn-gray">Buat Permintaan Lain</a>
        </div>
    </div>
</div>
@endsection
