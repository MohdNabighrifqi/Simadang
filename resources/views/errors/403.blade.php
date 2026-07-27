{{-- resources/views/errors/403.blade.php --}}
@extends('layouts.app')
@section('title', '403 Akses Ditolak')
@section('content')
<div class="container">
    <div class="error-page">
        <div class="error-emoji">🚫</div>
        <h2>Akses Ditolak</h2>
        <p>Anda tidak memiliki izin untuk mengakses halaman ini. Halaman ini hanya dapat diakses oleh Admin.</p>
        <a href="{{ route('beranda') }}" class="btn btn-teal">← Kembali ke Beranda</a>
    </div>
</div>
@endsection
