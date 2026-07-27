<?php
// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\PermintaanData;
use App\Models\User;
use App\Services\LaporanService;

class DashboardController extends Controller
{
    public function __construct(protected LaporanService $svc) {}

    public function index()
    {
        $stats = $this->svc->getStats();

        // Laporan menunggu
        $laporan = Laporan::with(['jenis','kondisi','lokasi','user'])
                          ->where('status','menunggu')
                          ->latest('tanggal')
                          ->paginate(10);

        // Permintaan data menunggu
        $permintaan = PermintaanData::with('user')
                                    ->where('status','menunggu')
                                    ->latest()
                                    ->get();

        $jumlahPermintaan = $permintaan->count();
        $users = User::latest()->get();

        return view('admin.dashboard', compact(
            'stats','laporan','users','permintaan','jumlahPermintaan'
        ));
    }

    public function pollCount()
    {
        return response()->json([
            'menunggu_laporan'    => Laporan::where('status', 'menunggu')->count(),
            'menunggu_permintaan' => PermintaanData::where('status', 'menunggu')->count(),
        ]);
    }
}
