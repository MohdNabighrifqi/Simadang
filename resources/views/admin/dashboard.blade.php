{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')
@section('title','Dashboard Admin')
@section('content')

<div class="page-header">
    <h2>Dashboard Admin</h2>
    <p>Kelola laporan, pengguna, dan permintaan data sistem Simadang.</p>
</div>

{{-- Statistik --}}
<div class="admin-stat-grid">
    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background:var(--primary-light);color:var(--primary);">
            <i class="fa-solid fa-clipboard-list"></i>
        </div>
        <div>
            <div class="admin-stat-value">{{ $stats['total'] }}</div>
            <div class="admin-stat-label">Total Laporan Terverifikasi</div>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background:var(--amber-light);color:var(--amber);">
            <i class="fa-solid fa-hourglass-half"></i>
        </div>
        <div>
            <div class="admin-stat-value">{{ $stats['menunggu'] }}</div>
            <div class="admin-stat-label">Menunggu Verifikasi</div>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background:var(--blue-light);color:var(--blue);">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
            <div class="admin-stat-value">{{ $stats['terverifikasi'] }}</div>
            <div class="admin-stat-label">Terverifikasi</div>
        </div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background:var(--tertiary-light);color:var(--primary-dark);">
            <i class="fa-solid fa-users"></i>
        </div>
        <div>
            <div class="admin-stat-value">{{ $stats['users'] }}</div>
            <div class="admin-stat-label">Pengguna Terdaftar</div>
        </div>
    </div>
</div>

{{-- Tabs --}}
<div class="tabs">
    <button class="tab-btn active" onclick="switchTab('laporan',this)">
        Laporan Menunggu
        @if($stats['menunggu']>0)
        <span style="background:var(--primary);color:#fff;border-radius:9999px;padding:1px 7px;font-size:.7rem;font-weight:700;">{{ $stats['menunggu'] }}</span>
        @endif
    </button>
    <button class="tab-btn" onclick="switchTab('permintaan',this)">
        Permintaan Data
        @if($jumlahPermintaan > 0)
        <span style="background:#CA6702;color:#fff;border-radius:9999px;padding:1px 7px;font-size:.7rem;font-weight:700;">{{ $jumlahPermintaan }}</span>
        @endif
    </button>
    <button class="tab-btn" onclick="switchTab('users',this)">
        Pengguna
    </button>
</div>

{{-- Tab: Laporan Menunggu --}}
<div id="tab-laporan">
    <div class="card card-flush">
        <div class="card-header">
            <span style="font-size:.875rem;font-weight:600;">Laporan Menunggu Verifikasi</span>
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-gray btn-sm">
                Lihat Semua <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        {{-- Mobile --}}
        <div class="mobile-list">
            @forelse($laporan as $lap)
            <a href="{{ route('admin.laporan.show',$lap->id) }}" class="mobile-card-link">
                <div class="mobile-card">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
                        <div>
                            <div class="table-mono">{{ $lap->kode }}</div>
                            <div style="font-size:.875rem;font-weight:500;margin-top:2px;">{{ $lap->lokasi?->nama ?? '-' }}</div>
                        </div>
                        @include('partials.badge-status',['status'=>$lap->status])
                    </div>
                    <div style="font-size:.75rem;color:var(--text-muted);margin-top:5px;">
                        {{ \Carbon\Carbon::parse($lap->tanggal)->translatedFormat('d M Y') }}
                        · {{ $lap->nama_pelapor ?? $lap->user?->name ?? '-' }}
                    </div>
                    <div style="margin-top:6px;font-size:.75rem;color:var(--primary);font-weight:500;">
                        Tap untuk detail & verifikasi
                    </div>
                </div>
            </a>
            @empty
            <div style="padding:2.5rem;text-align:center;color:var(--text-muted);">
                <i class="fa-solid fa-circle-check" style="font-size:2rem;color:#86EFAC;display:block;margin-bottom:.5rem;"></i>
                Tidak ada laporan yang menunggu.
            </div>
            @endforelse
        </div>

        {{-- Desktop --}}
        <div class="desktop-table">
            <table class="table">
                <thead>
                    <tr><th>Kode</th><th>Tanggal</th><th>Jenis</th><th>Kondisi</th><th>Lokasi</th><th>Pelapor</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($laporan as $lap)
                    <tr>
                        <td class="table-mono">{{ $lap->kode }}</td>
                        <td style="white-space:nowrap;color:var(--text-muted);font-size:.85rem;">{{ \Carbon\Carbon::parse($lap->tanggal)->translatedFormat('d M Y') }}</td>
                        <td>
                            @if($lap->jenis?->nama==='dugong')
                                <span style="color:#0A9396;font-size:.8rem;font-weight:500;">Dugong</span>
                            @else
                                <span style="color:var(--amber);font-size:.8rem;font-weight:500;">Habitat</span>
                            @endif
                        </td>
                        <td>
                            @php $k=$lap->kondisi?->nama; @endphp
                            @if($k==='hidup') <span style="color:#0A9396;font-size:.78rem;font-weight:600;">Hidup</span>
                            @elseif($k==='mati_terdampar') <span style="color:#374151;font-size:.78rem;font-weight:600;">Terdampar</span>
                            @elseif($k==='mati_tertangkap') <span style="color:#AE2012;font-size:.78rem;font-weight:600;">Tertangkap</span>
                            @else <span style="color:var(--text-muted);font-size:.78rem;">-</span>
                            @endif
                        </td>
                        <td>{{ $lap->lokasi?->nama ?? '-' }}</td>
                        <td style="color:var(--text-muted);font-size:.85rem;">{{ $lap->nama_pelapor ?? $lap->user?->name ?? '-' }}</td>
                        <td>
                            <a href="{{ route('admin.laporan.show',$lap->id) }}" class="btn btn-gray btn-sm">
                                <i class="fa-solid fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:2.5rem;color:var(--text-muted);">
                            <i class="fa-solid fa-circle-check" style="font-size:2rem;color:#86EFAC;display:block;margin-bottom:.5rem;"></i>
                            Tidak ada laporan yang menunggu verifikasi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Tab: Permintaan Data --}}
<div id="tab-permintaan" style="display:none;">
    <div class="card card-flush">
        <div class="card-header">
            <span style="font-size:.875rem;font-weight:600;">Permintaan Data Menunggu</span>
            <a href="{{ route('admin.permintaan.index') }}" class="btn btn-gray btn-sm">
                Lihat Semua <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        {{-- Mobile --}}
        <div class="mobile-list">
            @forelse($permintaan as $p)
            <a href="{{ route('admin.permintaan.show',$p->id) }}" class="mobile-card-link">
                <div class="mobile-card">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
                        <div>
                            <div style="font-weight:600;">{{ $p->nama_pemohon }}</div>
                            <div style="font-size:.75rem;color:var(--text-muted);">{{ $p->email_pemohon }}</div>
                        </div>
                        <span style="background:#FFFBEB;color:#D97706;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:600;flex-shrink:0;">Menunggu</span>
                    </div>
                    <div style="font-size:.75rem;color:var(--text-muted);margin-top:5px;">
                        {{ $p->jenis_data_label }} · {{ $p->institusi ?? 'Perorangan' }}
                    </div>
                    <div style="margin-top:5px;font-size:.75rem;color:var(--text-muted);">{{ \Illuminate\Support\Str::limit($p->tujuan, 60) }}</div>
                </div>
            </a>
            @empty
            <div style="padding:2.5rem;text-align:center;color:var(--text-muted);">
                <i class="fa-solid fa-inbox" style="font-size:2rem;opacity:.2;display:block;margin-bottom:.5rem;"></i>
                Tidak ada permintaan data yang menunggu.
            </div>
            @endforelse
        </div>

        {{-- Desktop --}}
        <div class="desktop-table">
            <table class="table">
                <thead>
                    <tr><th>Nama</th><th>Email</th><th>Institusi</th><th>Jenis Data</th><th>Tujuan</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($permintaan as $p)
                    <tr>
                        <td style="font-weight:500;">{{ $p->nama_pemohon }}</td>
                        <td style="font-size:.85rem;color:var(--text-muted);">{{ $p->email_pemohon }}</td>
                        <td style="font-size:.85rem;">{{ $p->institusi ?? '-' }}</td>
                        <td style="font-size:.85rem;font-weight:500;">{{ $p->jenis_data_label }}</td>
                        <td style="font-size:.85rem;max-width:180px;">{{ \Illuminate\Support\Str::limit($p->tujuan,45) }}</td>
                        <td>
                            <a href="{{ route('admin.permintaan.show',$p->id) }}" class="btn btn-gray btn-sm">
                                <i class="fa-solid fa-eye"></i> Tinjau
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:2.5rem;color:var(--text-muted);">Tidak ada permintaan yang menunggu.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Tab: Pengguna --}}
<div id="tab-users" style="display:none;">
    <div class="card card-flush">
        <div class="card-header">
            <span style="font-size:.875rem;font-weight:600;">Pengguna Terdaftar</span>
            <a href="{{ route('admin.users.create') }}" class="btn btn-teal btn-sm">
                <i class="fa-solid fa-plus"></i> Tambah
            </a>
        </div>

        {{-- Mobile --}}
        <div class="mobile-list">
            @forelse($users as $user)
            <div class="mobile-card">
                <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;">
                    <div>
                        <div style="font-weight:500;">{{ $user->name }}</div>
                        <div style="font-size:.75rem;color:var(--text-muted);">{{ $user->email }}</div>
                    </div>
                    @if($user->role==='admin')
                        <span class="badge badge-info">Admin</span>
                    @else
                        <span class="badge badge-gray">Warga</span>
                    @endif
                </div>
                <div style="display:flex;gap:6px;margin-top:10px;">
                    <a href="{{ route('admin.users.edit',$user->id) }}" class="btn btn-gray btn-sm" style="flex:1;justify-content:center;">
                        <i class="fa-solid fa-pen"></i> Edit
                    </a>
                    @if($user->id!==auth()->id())
                    <form method="POST" action="{{ route('admin.users.destroy',$user->id) }}"
                          style="flex:1;margin:0;" onsubmit="return confirm('Hapus {{ addslashes($user->name) }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm" style="width:100%;justify-content:center;background:var(--coral-light);color:var(--coral);border:none;">
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

        {{-- Desktop --}}
        <div class="desktop-table">
            <table class="table">
                <thead>
                    <tr><th>#</th><th>Nama</th><th>Email</th><th>Daerah</th><th>Role</th><th>Bergabung</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $user)
                    <tr>
                        <td style="color:var(--text-muted);">{{ $i+1 }}</td>
                        <td style="font-weight:500;">{{ $user->name }}</td>
                        <td style="color:var(--text-muted);font-size:.85rem;">{{ $user->email }}</td>
                        <td>{{ $user->daerah ?? '-' }}</td>
                        <td>
                            @if($user->role==='admin')
                                <span class="badge badge-info">Admin</span>
                            @else
                                <span class="badge badge-gray">Warga</span>
                            @endif
                        </td>
                        <td style="color:var(--text-muted);font-size:.85rem;white-space:nowrap;">
                            {{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d M Y') }}
                        </td>
                        <td>
                            <div style="display:flex;gap:5px;">
                                <a href="{{ route('admin.users.edit',$user->id) }}" class="btn btn-gray btn-sm">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                @if($user->id!==auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy',$user->id) }}" style="margin:0;"
                                      onsubmit="return confirm('Hapus {{ addslashes($user->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background:var(--coral-light);color:var(--coral);border:none;">
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
</div>

@endsection
@push('scripts')
<script>
function switchTab(name,btn){
    ['laporan','permintaan','users'].forEach(n=>{
        document.getElementById('tab-'+n).style.display='none';
    });
    document.querySelectorAll('.tab-btn').forEach(b=>b.classList.remove('active'));
    document.getElementById('tab-'+name).style.display='';
    btn.classList.add('active');
}
</script>
@endpush
