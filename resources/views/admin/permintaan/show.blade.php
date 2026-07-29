{{-- resources/views/admin/permintaan/show.blade.php --}}
@extends('layouts.admin')
@section('title','Detail Permintaan Data')
@section('content')

@include('partials.breadcrumb', ['items' => [
    ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
    ['label' => 'Permintaan Data', 'route' => route('admin.permintaan.index')],
    ['label' => 'Detail #' . $permintaan->id],
]])

    <div class="detail-grid" style="--sidebar-w:300px;">

        {{-- Kolom Kiri: Info --}}
        <div class="card">
            <div class="card-title" style="margin-bottom:1.2rem;">Detail Permintaan Data</div>

            <div class="detail-grid-cols2">

                <div class="info-row">
                    <div class="info-label">Nama Pemohon</div>
                    <div class="info-val">{{ $permintaan->nama_pemohon }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-val">{{ $permintaan->email_pemohon }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Institusi</div>
                    <div class="info-val">{{ $permintaan->institusi ?? '-' }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Jenis Data</div>
                    <div class="info-val" style="color:var(--primary);font-weight:700;">
                        {{ $permintaan->jenis_data_label }}
                    </div>
                </div>

                <div class="info-row info-row-full">
                    <div class="info-label">Tujuan Penggunaan</div>
                    <div class="info-val">{{ $permintaan->tujuan }}</div>
                </div>

                @if($permintaan->periode_dari || $permintaan->periode_sampai)
                <div class="info-row">
                    <div class="info-label">Periode Data</div>
                    <div class="info-val">
                        {{ $permintaan->periode_dari ?? '-' }} s/d {{ $permintaan->periode_sampai ?? 'sekarang' }}
                    </div>
                </div>
                @endif

                @if($permintaan->keterangan)
                <div class="info-row info-row-full">
                    <div class="info-label">Keterangan Tambahan</div>
                    <div class="info-val">{{ $permintaan->keterangan }}</div>
                </div>
                @endif

                <div class="info-row">
                    <div class="info-label">Status</div>
                    <div class="info-val">
                        @php
                        $sc = match($permintaan->status) {
                            'disetujui' => ['bg'=>'#E1F5EE','color'=>'#054030','label'=>'Disetujui'],
                            'ditolak'   => ['bg'=>'#FAECE7','color'=>'#6B1008','label'=>'Ditolak'],
                            default     => ['bg'=>'#FFFBEB','color'=>'#7A3D00','label'=>'Menunggu'],
                        };
                        @endphp
                        <span style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};padding:3px 12px;border-radius:20px;font-size:.78rem;font-weight:700;">
                            {{ $sc['label'] }}
                        </span>
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Dikirim</div>
                    <div class="info-val" style="font-size:.85rem;">
                        {{ $permintaan->created_at->translatedFormat('d F Y, H:i') }}
                    </div>
                </div>

                @if($permintaan->catatan_admin)
                <div class="info-row info-row-full">
                    <div class="info-label">Catatan Admin</div>
                    <div class="info-val" style="font-size:.85rem;color:var(--text-muted);">
                        {{ $permintaan->catatan_admin }}
                    </div>
                </div>
                @endif

            </div>
        </div>

        {{-- Kolom Kanan: Aksi --}}
        <div style="display:flex;flex-direction:column;gap:1rem;">

            {{-- Tindakan Admin --}}
            <div class="card" style="border-left:3px solid
                @if($permintaan->status==='disetujui') #1D9E75
                @elseif($permintaan->status==='ditolak') var(--coral)
                @else var(--amber) @endif;">

                <div class="card-title" style="font-size:.875rem;margin-bottom:1rem;">Tindakan Admin</div>

                @if($permintaan->status === 'menunggu')
                <form method="POST" action="{{ route('admin.permintaan.update', $permintaan->id) }}">
                    @csrf @method('PATCH')
                    <div class="form-group" style="margin-bottom:.75rem;">
                        <label class="form-label">Catatan untuk Pemohon <span style="font-weight:400;color:var(--text-muted);">(opsional)</span></label>
                        <textarea name="catatan_admin" rows="3" class="form-control"
                                  placeholder="Informasi tambahan atau instruksi..."></textarea>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:6px;">
                        <button type="submit" name="status" value="disetujui" class="btn btn-teal" style="justify-content:center;">
                            Setujui & Kirim Data ke Email
                        </button>
                        <button type="submit" name="status" value="ditolak" class="btn btn-sm"
                                style="justify-content:center;background:var(--coral-light);color:var(--coral);border:none;"
                                onclick="return confirm('Tolak permintaan ini?')">
                            Tolak Permintaan
                        </button>
                    </div>
                </form>

                @elseif($permintaan->status === 'disetujui')
                <div style="text-align:center;padding:.5rem 0 1rem;">
                    <i class="fa-solid fa-circle-check" style="font-size:2rem;color:#1D9E75;display:block;margin-bottom:.5rem;"></i>
                    <div style="font-weight:600;color:#1D9E75;margin-bottom:3px;">Permintaan Disetujui</div>
                    <div style="font-size:.78rem;color:var(--text-muted);">Data {{ $permintaan->format_file === 'pdf' ? 'PDF' : 'Excel' }} telah dikirim ke email pemohon</div>
                </div>
                <form method="POST" action="{{ route('admin.permintaan.update', $permintaan->id) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="menunggu">
                    <button type="submit" class="btn btn-gray btn-sm" style="width:100%;justify-content:center;">
                        Reset ke Menunggu
                    </button>
                </form>

                @else
                <div style="text-align:center;padding:.5rem 0 1rem;">
                    <i class="fa-solid fa-circle-xmark" style="font-size:2rem;color:var(--coral);display:block;margin-bottom:.5rem;"></i>
                    <div style="font-weight:600;color:var(--coral);margin-bottom:3px;">Permintaan Ditolak</div>
                </div>
                <form method="POST" action="{{ route('admin.permintaan.update', $permintaan->id) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="menunggu">
                    <button type="submit" class="btn btn-gray btn-sm" style="width:100%;justify-content:center;">
                        Reset ke Menunggu
                    </button>
                </form>
                @endif
            </div>

            {{-- Ekspor Data --}}
            @if($permintaan->status === 'disetujui')
            @php
                $paramEkspor = [
                    'tipe'           => $permintaan->jenis_data,
                    'status'         => 'terverifikasi',
                    'periode_dari'   => $permintaan->periode_dari,
                    'periode_sampai' => $permintaan->periode_sampai,
                ];
            @endphp
            <div class="card" style="background:var(--primary-light);border-color:rgba(0,95,115,.15);">
                <div class="card-title" style="font-size:.875rem;color:var(--primary);margin-bottom:.75rem;">
                    Ekspor Data untuk Pemohon
                </div>
                <p style="font-size:.78rem;color:var(--text-muted);line-height:1.6;margin-bottom:.75rem;">
                    Unduh ulang berkas dengan isi yang sama seperti yang dikirim ke email pemohon.
                </p>
                <div style="display:flex;flex-direction:column;gap:6px;">
                    <a href="{{ route('admin.export.laporan', array_merge($paramEkspor, ['format'=>'xlsx'])) }}"
                       class="btn btn-teal btn-sm" style="justify-content:center;">
                        <i class="fa-solid fa-file-excel"></i> Unduh Excel
                    </a>
                    <a href="{{ route('admin.export.laporan', array_merge($paramEkspor, ['format'=>'pdf'])) }}"
                       class="btn btn-gray btn-sm" style="justify-content:center;">
                        <i class="fa-solid fa-file-pdf"></i> Unduh PDF
                    </a>
                </div>
            </div>
            @endif

            {{-- Hapus --}}
            <form method="POST" action="{{ route('admin.permintaan.destroy', $permintaan->id) }}"
                  onsubmit="return confirm('Hapus permintaan ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm"
                        style="width:100%;justify-content:center;background:var(--coral-light);color:var(--coral);border:none;">
                    Hapus Permintaan
                </button>
            </form>

        </div>
    </div>
@endsection
