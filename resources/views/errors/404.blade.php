{{-- resources/views/errors/404.blade.php --}}
@extends('layouts.app')
@section('title', '404 Tidak Ditemukan')
@section('content')
<div class="container">
    <div class="error-page">
        <div class="error-emoji">🌊</div>
        <h2>Halaman Tidak Ditemukan</h2>
        <p>Sepertinya dugong yang Anda cari sudah berenang ke tempat lain. Halaman ini tidak tersedia.</p>
        <a href="{{ route('beranda') }}" class="btn btn-teal">← Kembali ke Beranda</a>
    </div>
</div>
@endsection
