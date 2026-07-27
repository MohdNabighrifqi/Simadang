<?php
// app/Http/Controllers/StatistikController.php
namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Lokasi;
use App\Services\LaporanExportService;

class StatistikController extends Controller
{
    public function index(LaporanExportService $exportSvc)
    {
        $semuaLaporan = Laporan::select('status', 'jumlah_dugong')->get();
        $verified = Laporan::with(['jenis', 'kondisi', 'lokasi', 'user'])->terverifikasi()->get();

        $ringkasan = [
            'total'         => $semuaLaporan->count(),
            'terverifikasi' => $semuaLaporan->where('status', 'terverifikasi')->count(),
            'menunggu'      => $semuaLaporan->where('status', 'menunggu')->count(),
            'ditolak'       => $semuaLaporan->where('status', 'ditolak')->count(),
            'totalDugong'   => (int) $verified->sum('jumlah_dugong'),
        ];

        $statistik = $exportSvc->hitungStatistik($verified);

        $tahunanPerKondisi = $verified
            ->filter(fn ($l) => $l->tanggal)
            ->groupBy(fn ($l) => $l->tanggal->format('Y'))
            ->map(function ($grup, $tahun) {
                return [
                    'tahun'           => $tahun,
                    'hidup'           => $grup->filter(fn ($l) => $l->kondisi?->nama === 'hidup')->count(),
                    'mati_terdampar'  => $grup->filter(fn ($l) => $l->kondisi?->nama === 'mati_terdampar')->count(),
                    'mati_tertangkap' => $grup->filter(fn ($l) => $l->kondisi?->nama === 'mati_tertangkap')->count(),
                ];
            })
            ->sortKeys()
            ->values();

        $rentangTahun = [
            'dari'   => $statistik['tahunan']->min('tahun') ?? date('Y'),
            'sampai' => $statistik['tahunan']->max('tahun') ?? date('Y'),
        ];

        $petaWilayah = Lokasi::whereNotNull('latitude')->whereNotNull('longitude')->get()
            ->map(function ($lok) {
                return [
                    'nama'    => $lok->nama,
                    'wilayah' => $lok->wilayah,
                    'lat'     => (float) $lok->latitude,
                    'lng'     => (float) $lok->longitude,
                    'jumlah'  => Laporan::terverifikasi()->where('lokasi_id', $lok->id)->count(),
                ];
            })
            ->filter(fn ($r) => $r['jumlah'] > 0)
            ->sortByDesc('jumlah')
            ->values();

        return view('statistik.index', [
            'ringkasan'        => $ringkasan,
            'rentangTahun'     => $rentangTahun,
            'bulanan'          => $statistik['bulanan'],
            'tahunan'          => $statistik['tahunan'],
            'tahunanPerKondisi' => $tahunanPerKondisi,
            'wilayahPrioritas' => $statistik['wilayahPrioritas'],
            'kondisiDugong'    => $statistik['kondisiDugong'],
            'petaWilayah'      => $petaWilayah,
        ]);
    }
}
