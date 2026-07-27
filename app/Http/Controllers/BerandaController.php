<?php
// app/Http/Controllers/BerandaController.php
namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Services\LaporanService;

class BerandaController extends Controller
{
    public function __construct(protected LaporanService $svc) {}

    public function index()
    {
        $stats = $this->svc->getStats();
        $chart = $this->svc->getChartData();
        $laporanTerbaru = Laporan::with(['jenis','kondisi','lokasi'])
                                 ->terverifikasi()
                                 ->dataMasyarakat()
                                 ->latest('tanggal')
                                 ->take(5)
                                 ->get();

        return view('beranda.index', [
            'stats'          => $stats,
            'chartLabels'    => $chart['labels'],
            'chartData'      => $chart['data'],
            'laporanTerbaru' => $laporanTerbaru,
        ]);
    }
}
