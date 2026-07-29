{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.admin')
@section('title','Kelola Pengguna')

@section('content')

@include('partials.breadcrumb', ['items' => [
    ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
    ['label' => 'Kelola Pengguna'],
]])

    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;margin-bottom:1.2rem;">
        <div class="page-header" style="margin:0;">
            <h2>Kelola Pengguna</h2>
            <p>Daftar semua pengguna terdaftar di sistem Simadang.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-teal">
            <i class="fa-solid fa-plus"></i> Tambah Pengguna
        </a>
    </div>

    {{-- Filter --}}
    <form method="GET" action="{{ route('admin.users.index') }}" class="filter-bar">
        <div class="filter-search">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="cari" placeholder="Cari nama / email..."
                   class="form-control" value="{{ request('cari') }}" onchange="this.form.submit()">
        </div>
        <select name="role" class="form-control" onchange="this.form.submit()">
            <option value="">Semua Role</option>
            <option value="admin" {{ request('role')==='admin' ?'selected':'' }}>Admin</option>
            <option value="warga" {{ request('role')==='warga' ?'selected':'' }}>Warga</option>
        </select>
        <a href="{{ route('admin.users.index') }}" class="filter-reset">
            <i class="fa-solid fa-rotate-left"></i> Reset
        </a>
    </form>

    {{-- Mobile: card list --}}
    <div class="mobile-list">
        @forelse($users as $user)
        <div class="mobile-card">
            <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;">
                <div>
                    <div style="font-weight:500;">{{ $user->name }}</div>
                    <div style="font-size:.78rem;color:var(--text-muted);">{{ $user->email }}</div>
                </div>
                @if($user->role === 'admin')
                    <span class="badge badge-info"><i class="fa-solid fa-shield"></i> Admin</span>
                @else
                    <span class="badge badge-success"><i class="fa-solid fa-user"></i> Warga</span>
                @endif
            </div>
            <div style="font-size:.75rem;color:var(--text-muted);margin-top:5px;">
                {{ $user->daerah ?? '-' }} · {{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d M Y') }}
            </div>
            <div style="display:flex;gap:6px;margin-top:10px;">
                <a href="{{ route('admin.users.edit', $user->id) }}"
                   class="btn btn-sm"
                   style="flex:1;justify-content:center;background:var(--primary-light,#E0F0F4);color:var(--primary,#005F73);border:none;">
                    <i class="fa-solid fa-pen"></i> Edit
                </a>
                @if($user->id !== auth()->id())
                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                      style="flex:1;margin:0;"
                      onsubmit="return confirm('Hapus pengguna {{ addslashes($user->name) }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm"
                            style="width:100%;justify-content:center;background:var(--coral-light);color:var(--coral);border:none;">
                        <i class="fa-solid fa-trash"></i> Hapus
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div style="padding:2rem;text-align:center;color:var(--text-muted);">Belum ada pengguna.</div>
        @endforelse
    </div>

    {{-- Desktop: table --}}
    <div class="card card-flush desktop-table">
        <div class="table-wrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th><th>Nama</th><th>Email</th>
                        <th>Asal Daerah</th><th>Role</th><th>Bergabung</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $loop->iteration + ($users->currentPage()-1) * $users->perPage() }}</td>
                        <td style="font-weight:500;">{{ $user->name }}</td>
                        <td style="color:var(--text-muted);font-size:.85rem;">{{ $user->email }}</td>
                        <td>{{ $user->daerah ?? '-' }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge badge-info"><i class="fa-solid fa-shield"></i> Admin</span>
                            @else
                                <span class="badge badge-success"><i class="fa-solid fa-user"></i> Warga</span>
                            @endif
                        </td>
                        <td style="white-space:nowrap;">{{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d M Y') }}</td>
                        <td>
                            <div style="display:flex;gap:5px;">
                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="btn btn-sm"
                                   style="background:var(--primary-light,#E0F0F4);color:var(--primary,#005F73);border:none;">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                      style="margin:0;"
                                      onsubmit="return confirm('Hapus {{ addslashes($user->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm"
                                            style="background:var(--coral-light);color:var(--coral);border:none;">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--text-muted);">Belum ada pengguna.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($users->hasPages())
    <div class="pagination">{{ $users->withQueryString()->links() }}</div>
    @endif
@endsection
