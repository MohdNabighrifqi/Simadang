@php
$cfg = match($status) {
    'disetujui' => ['bg'=>'#E1F5EE','color'=>'#054030','label'=>'Disetujui'],
    'ditolak'   => ['bg'=>'#FAECE7','color'=>'#6B1008','label'=>'Ditolak'],
    default     => ['bg'=>'#FFFBEB','color'=>'#7A3D00','label'=>'Menunggu'],
};
@endphp
<span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:9999px;font-size:.75rem;font-weight:600;background:{{ $cfg['bg'] }};color:{{ $cfg['color'] }};">
    {{ $cfg['label'] }}
</span>