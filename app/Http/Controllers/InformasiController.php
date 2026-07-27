<?php
// app/Http/Controllers/InformasiController.php
namespace App\Http\Controllers;

use App\Models\Kondisi;
use App\Models\Laporan;
use App\Models\Lokasi;

class InformasiController extends Controller
{
    public function index()
    {
        $hidup      = Kondisi::where('nama','hidup')->first();
        $terdampar  = Kondisi::where('nama','mati_terdampar')->first();
        $tertangkap = Kondisi::where('nama','mati_tertangkap')->first();

        $stats = [
            'total'           => Laporan::terverifikasi()->count(),
            'hidup'           => $hidup      ? Laporan::terverifikasi()->where('kondisi_id',$hidup->id)->count()      : 0,
            'mati_terdampar'  => $terdampar  ? Laporan::terverifikasi()->where('kondisi_id',$terdampar->id)->count()  : 0,
            'mati_tertangkap' => $tertangkap ? Laporan::terverifikasi()->where('kondisi_id',$tertangkap->id)->count() : 0,
        ];

        $lokasiList  = Lokasi::whereNotNull('latitude')->orderBy('nama')->get();
        $rekapLokasi = $lokasiList->map(function($lok) use ($hidup,$terdampar,$tertangkap) {
            $base = Laporan::terverifikasi()->where('lokasi_id',$lok->id);
            $h = $hidup      ? (clone $base)->where('kondisi_id',$hidup->id)->count()      : 0;
            $t = $terdampar  ? (clone $base)->where('kondisi_id',$terdampar->id)->count()  : 0;
            $k = $tertangkap ? (clone $base)->where('kondisi_id',$tertangkap->id)->count() : 0;
            return [
                'lokasi'     => $lok->nama,
                'wilayah'    => $lok->wilayah,
                'hidup'      => $h,
                'terdampar'  => $t,
                'tertangkap' => $k,
                'total'      => $h + $t + $k,
            ];
        })->filter(fn($r) => $r['total'] > 0)->values();

        $galeriFoto = collect(glob(public_path('images/galeri/*.{webp,jpg,jpeg,png}'), GLOB_BRACE))
                        ->map(fn($path) => basename($path))
                        ->sort()
                        ->values();

        return view('informasi.index', compact('stats','rekapLokasi','galeriFoto'));
    }
}
