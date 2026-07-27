{{-- resources/views/partials/badge-status.blade.php --}}
@php
$cfg = match($status) {
    'terverifikasi' => ['badge-success', '✓ Terverifikasi'],
    'menunggu'      => ['badge-warning', '⏳ Menunggu'],
    'ditolak'       => ['badge-danger',  '✗ Ditolak'],
    default         => ['badge-gray',    ucfirst($status)],
};
@endphp
<span class="badge {{ $cfg[0] }}">{{ $cfg[1] }}</span>
