<?php
// app/Http/Controllers/PetaController.php
namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Lokasi;
use App\Models\Kondisi;

class PetaController extends Controller
{
    public function index()
    {
        // Ambil semua laporan dugong terverifikasi
        // Prioritas koordinat: laporan.latitude → lokasi.latitude
        $laporanPeta = Laporan::terverifikasi()
            ->where('jenis_id', 1)
            ->with(['kondisi','lokasi'])
            ->get()
            ->filter(function($l) {
                // Tampilkan jika punya koordinat langsung ATAU lokasi punya koordinat
                $lat = $l->latitude ?? $l->lokasi?->latitude;
                $lng = $l->longitude ?? $l->lokasi?->longitude;
                return $lat && $lng;
            })
            ->map(fn($l) => [
                'kode'      => $l->kode,
                'kondisi'   => $l->kondisi?->nama ?? 'hidup',
                'tahun'     => $l->tanggal->format('Y'),
                'lokasi'    => $l->lokasi?->nama ?? '—',
                'sumber'    => $l->is_historis
                                ? str_replace('Sumber: ', '', $l->catatan ?? $l->sumber_data ?? 'Penelitian')
                                : 'Laporan Masyarakat',
                // Prioritas: koordinat individual (data penelitian) → koordinat pusat lokasi (laporan warga)
                'latitude'  => (float) ($l->latitude ?? $l->lokasi?->latitude),
                'longitude' => (float) ($l->longitude ?? $l->lokasi?->longitude),
                'is_historis' => $l->is_historis,
            ])
            ->values();

        // Statistik kondisi
        $hidup      = Kondisi::where('nama', 'hidup')->first();
        $terdampar  = Kondisi::where('nama', 'mati_terdampar')->first();
        $tertangkap = Kondisi::where('nama', 'mati_tertangkap')->first();

        $stats = [
            'hidup'      => $hidup      ? Laporan::terverifikasi()->where('kondisi_id', $hidup->id)->count()      : 0,
            'terdampar'  => $terdampar  ? Laporan::terverifikasi()->where('kondisi_id', $terdampar->id)->count()  : 0,
            'tertangkap' => $tertangkap ? Laporan::terverifikasi()->where('kondisi_id', $tertangkap->id)->count() : 0,
            'total'      => Laporan::terverifikasi()->where('jenis_id', 1)->count(),
        ];

        // Rekap per lokasi
        $lokasiList  = Lokasi::whereNotNull('latitude')->orderBy('nama')->get();
        $rekapLokasi = $lokasiList->map(function($lok) use ($hidup, $terdampar, $tertangkap) {
            $base = Laporan::terverifikasi()->where('lokasi_id', $lok->id);
            $h = $hidup      ? (clone $base)->where('kondisi_id', $hidup->id)->count()      : 0;
            $t = $terdampar  ? (clone $base)->where('kondisi_id', $terdampar->id)->count()  : 0;
            $k = $tertangkap ? (clone $base)->where('kondisi_id', $tertangkap->id)->count() : 0;
            return [
                'lokasi'     => $lok->nama,
                'hidup'      => $h,
                'terdampar'  => $t,
                'tertangkap' => $k,
                'total'      => $h + $t + $k,
            ];
        })->filter(fn($r) => $r['total'] > 0)->values();

        return view('peta.index', compact('laporanPeta', 'stats', 'rekapLokasi'));
    }
}
