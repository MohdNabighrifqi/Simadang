{{-- resources/views/admin/users/_form.blade.php --}}
<div class="form-grid">
    <div class="form-group col-2">
        <label class="form-label">Nama Lengkap <span class="required">*</span></label>
        <input type="text" name="name"
               class="form-control @error('name') is-invalid @enderror"
               value="{{ old('name', $user->name ?? '') }}"
               placeholder="Nama lengkap pengguna">
        @error('name')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label class="form-label">Email <span class="required">*</span></label>
        <input type="email" name="email"
               class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $user->email ?? '') }}"
               placeholder="email@example.com">
        @error('email')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label class="form-label">Role <span class="required">*</span></label>
        <select name="role" class="form-control @error('role') is-invalid @enderror">
            <option value="warga" {{ old('role', $user->role ?? 'warga') === 'warga' ? 'selected':'' }}>Warga</option>
            <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected':'' }}>Admin</option>
        </select>
        @error('role')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group col-2">
        <label class="form-label">Asal Daerah <span style="font-weight:400;color:var(--text-muted);">(opsional)</span></label>
        <input type="text" name="daerah"
               class="form-control @error('daerah') is-invalid @enderror"
               value="{{ old('daerah', $user->daerah ?? '') }}"
               placeholder="Kecamatan / Kelurahan">
        @error('daerah')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label class="form-label">
            Password
            @isset($user)
            <span style="font-weight:400;color:var(--text-muted);">(kosongkan jika tidak diubah)</span>
            @else
            <span class="required">*</span>
            @endisset
        </label>
        <input type="password" name="password"
               class="form-control @error('password') is-invalid @enderror"
               placeholder="{{ isset($user) ? 'Isi untuk ganti password' : 'Minimal 8 karakter' }}">
        @error('password')<span class="form-error">{{ $message }}</span>@enderror
    </div>

    <div class="form-group">
        <label class="form-label">
            Konfirmasi Password
            @isset($user)
            <span style="font-weight:400;color:var(--text-muted);">(opsional)</span>
            @else
            <span class="required">*</span>
            @endisset
        </label>
        <input type="password" name="password_confirmation"
               class="form-control"
               placeholder="Ulangi password">
    </div>
</div>
