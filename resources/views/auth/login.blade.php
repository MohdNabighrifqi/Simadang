{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')
@section('title', 'Masuk')

@section('content')
<div class="container-sm">
    <div class="card auth-card">
        <div class="auth-icon">🐬</div>
        <h2 class="auth-title">Masuk ke Simadang</h2>
        <p class="auth-sub">Masuk untuk melaporkan dan mengelola data konservasi</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group" style="margin-bottom:1rem;">
                <label class="form-label">Email</label>
                <input type="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}"
                       placeholder="email@example.com"
                       required autofocus>
                @error('email')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div class="form-group" style="margin-bottom:1rem;">
                <label class="form-label">Password</label>
                <input type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="••••••••"
                       required>
                @error('password')<span class="form-error">{{ $message }}</span>@enderror
            </div>

            <div style="display:flex;align-items:center;gap:8px;margin-bottom:1.3rem;">
                <input type="checkbox" name="remember" id="remember"
                       style="width:16px;height:16px;accent-color:var(--teal);">
                <label for="remember" style="font-size:0.84rem;color:var(--text-muted);cursor:pointer;">
                    Ingat saya
                </label>
            </div>

            <button type="submit" class="btn btn-teal btn-block">Masuk</button>
        </form>

        <div class="auth-footer">
            Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
        </div>

        <hr class="auth-divider">
        <div class="auth-hint">
            Demo Admin: <strong>admin@dugong.id</strong> / <strong>admin123</strong><br>
            Demo Warga: <strong>user@dugong.id</strong> / <strong>user123</strong>
        </div>
    </div>
</div>
@endsection
