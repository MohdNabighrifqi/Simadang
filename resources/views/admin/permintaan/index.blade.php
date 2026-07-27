{{-- resources/views/admin/permintaan/index.blade.php --}}
@extends('layouts.admin')
@section('title','Permintaan Data')
@section('content')

@include('partials.breadcrumb', ['items' => [
    ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
    ['label' => 'Permintaan Data'],
]])

    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;margin-bottom:1.2rem;">
        <div class="page-header" style="margin:0;">
            <h2>Permintaan Data</h2>
            <p>Daftar permintaan data dari masyarakat dan peneliti.</p>
        </div>

        <a href="{{ route('admin.export.form') }}" class="btn btn-teal btn-sm">
            <i class="fa-solid fa-file-export"></i> Ekspor Data
        </a>
    </div>

    <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:1.2rem;">
        <select name="status" class="form-control" style="width:auto;" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="menunggu"   {{ request('status')==='menunggu'   ?'selected':'' }}>Menunggu</option>
            <option value="disetujui"  {{ request('status')==='disetujui'  ?'selected':'' }}>Disetujui</option>
            <option value="ditolak"    {{ request('status')==='ditolak'    ?'selected':'' }}>Ditolak</option>
        </select>
        <a href="{{ route('admin.permintaan.index') }}" class="btn btn-gray btn-sm">Reset</a>
    </form>

    {{-- Mobile --}}
    <div class="mobile-list" style="background:#fff;border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;">
        @forelse($permintaan as $p)
        <a href="{{ route('admin.permintaan.show',$p->id) }}" class="mobile-card-link">
            <div class="mobile-card">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
                    <div>
                        <div style="font-weight:600;">{{ $p->nama_pemohon }}</div>
                        <div style="font-size:.75rem;color:var(--text-muted);">{{ $p->email_pemohon }}</div>
                    </div>
                    @include('partials.badge-permintaan', ['status'=>$p->status])
                </div>
                <div style="font-size:.75rem;color:var(--text-muted);margin-top:5px;">
                    {{ $p->jenis_data_label }} · {{ $p->institusi ?? 'Perorangan' }}
                </div>
            </div>
        </a>
        @empty
        <div style="padding:2.5rem;text-align:center;color:var(--text-muted);">Belum ada permintaan data.</div>
        @endforelse
    </div>

    {{-- Desktop --}}
    <div class="card card-flush desktop-table">
        <table class="table">
            <thead>
                <tr><th>Nama</th><th>Email</th><th>Institusi</th><th>Jenis Data</th><th>Tujuan</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($permintaan as $p)
                <tr>
                    <td style="font-weight:500;">{{ $p->nama_pemohon }}</td>
                    <td style="font-size:.85rem;color:var(--text-muted);">{{ $p->email_pemohon }}</td>
                    <td style="font-size:.85rem;">{{ $p->institusi ?? '—' }}</td>
                    <td style="font-size:.85rem;font-weight:500;">{{ $p->jenis_data_label }}</td>
                    <td style="font-size:.85rem;max-width:200px;">{{ \Illuminate\Support\Str::limit($p->tujuan,50) }}</td>
                    <td>
                        @php
                            $sc = match($p->status) {
                                'disetujui' => ['bg'=>'#E1F5EE','color'=>'#054030','label'=>'Disetujui'],
                                'ditolak'   => ['bg'=>'#FAECE7','color'=>'#6B1008','label'=>'Ditolak'],
                                default     => ['bg'=>'#FEF3C7','color'=>'#7A3D00','label'=>'Menunggu'],
                            };
                        @endphp
                        <span style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};padding:3px 10px;border-radius:20px;font-size:.75rem;font-weight:600;">{{ $sc['label'] }}</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.permintaan.show',$p->id) }}" class="btn btn-gray btn-sm">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:2.5rem;color:var(--text-muted);">Belum ada permintaan data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($permintaan->hasPages())
    <div class="pagination">{{ $permintaan->withQueryString()->links() }}</div>
    @endif
@endsection
