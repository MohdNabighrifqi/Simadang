{{-- resources/views/partials/admin-breadcrumb.blade.php
     Usage:
     @include('partials.admin-breadcrumb', ['items' => [
         ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
         ['label' => 'Kelola Laporan', 'route' => route('admin.laporan.index')],
         ['label' => $laporan->kode],
     ]])
--}}
@php
    $backItem = collect($items)->reverse()->skip(1)->first(fn($i) => !empty($i['route']));
    $backUrl  = $backItem['route'] ?? route('admin.dashboard');
@endphp
<div class="admin-page-head">
    <a href="{{ $backUrl }}" class="btn-back-circle" title="Kembali">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
    <nav class="admin-breadcrumb">
        @foreach($items as $i => $item)
            @if($i > 0)
            <i class="fa-solid fa-chevron-right crumb-sep"></i>
            @endif
            @if(!empty($item['route']) && !$loop->last)
                <a href="{{ $item['route'] }}">{{ $item['label'] }}</a>
            @else
                <span class="crumb-current">{{ $item['label'] }}</span>
            @endif
        @endforeach
    </nav>
</div>
