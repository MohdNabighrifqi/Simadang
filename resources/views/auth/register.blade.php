{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')
@section('title', 'Daftar Akun')

@section('content')
<div class="container-sm">
    <div class="card auth-card">
        <div class="auth-icon">📝</div>
        <h2 class="auth-title">Daftar Akun</h2>
        <p class="auth-sub">Bergabung sebagai pelapor konservasi dugong</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group" style="margin-bottom:1rem;">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}"
                       placeholder="Nama lengkap Anda"
                       required autofocus>
                @error('name')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group" style="margin-bottom:1rem;">
                <label class="form-label">Email</label>
                <input type="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}"
                       placeholder="email@example.com"
                       required>
                @error('email')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group" style="margin-bottom:1rem;">
                <label class="form-label">Asal Daerah</label>
                <input type="text" name="daerah"
                       class="form-control @error('daerah') is-invalid @enderror"
                       value="{{ old('daerah') }}"
                       placeholder="Kecamatan / Kelurahan">
                @error('daerah')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group" style="margin-bottom:1rem;">
                <label class="form-label">Password</label>
                <input type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Minimal 8 karakter"
                       required>
                @error('password')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group" style="margin-bottom:1.3rem;">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                       class="form-control"
                       placeholder="Ulangi password"
                       required>
            </div>

            <button type="submit" class="btn btn-teal btn-block">Daftar</button>
        </form>

        <div class="auth-footer">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </div>
</div>
@endsection
