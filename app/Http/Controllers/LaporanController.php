<?php
// app/Http/Controllers/LaporanController.php
namespace App\Http\Controllers;

use App\Http\Requests\StoreLaporanRequest;
use App\Models\JenisLaporan;
use App\Models\Kondisi;
use App\Models\Laporan;
use App\Models\Lokasi;
use App\Services\LaporanService;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function __construct(protected LaporanService $svc) {}

    public function index(Request $request)
    {
        // Halaman publik hanya menampilkan laporan yang sudah terverifikasi admin —
        // data menunggu/ditolak belum tentu akurat sehingga tidak ditampilkan ke publik sama sekali.
        $q = Laporan::with(['jenis','kondisi','lokasi','user'])
            ->where('status', 'terverifikasi')
            ->latest('tanggal');

        if ($request->filled('cari_lokasi')) {
            $cari = $request->cari_lokasi;
            $q->where(function ($sub) use ($cari) {
                $sub->whereHas('lokasi', fn($l) => $l->where('nama', 'like', "%{$cari}%"))
                    ->orWhere('catatan', 'like', "%{$cari}%");
            });
        }
        if ($request->filled('kondisi')) {
            $q->whereHas('kondisi', fn($k) => $k->where('nama', $request->kondisi));
        }
        if ($request->filled('tanggal_dari')) {
            $q->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $q->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        return view('laporan.index', [
            'laporan' => $q->paginate(config('sikodung.per_page',15)),
        ]);
    }

    public function create()
    {
        $lokasiKoordinat = Lokasi::whereNotNull('latitude')->whereNotNull('longitude')
            ->get(['nama','latitude','longitude'])
            ->mapWithKeys(fn($l) => [$l->nama => [(float) $l->latitude, (float) $l->longitude]]);

        return view('laporan.create', [
            'lokasiOptions'   => Lokasi::orderBy('nama')->pluck('nama'),
            'kondisiOptions'  => Kondisi::all(),
            'lokasiKoordinat' => $lokasiKoordinat,
        ]);
    }

    public function store(StoreLaporanRequest $request)
    {
        $lap = $this->svc->store($request->validated(), $request->file('foto'));
        return redirect()->route('laporan.riwayat')
            ->with('success', "Laporan {$lap->kode} berhasil dikirim! Menunggu verifikasi admin.");
    }

    public function show(Laporan $laporan)
    {
        $laporan->load(['jenis','kondisi','lokasi','user','fotos']);
        return view('laporan.show', compact('laporan'));
    }

    public function riwayat()
    {
        $laporan = Laporan::with(['jenis','kondisi','lokasi'])
                          ->dataMasyarakat()
                          ->where('user_id', auth()->id())
                          ->latest('tanggal')
                          ->paginate(config('sikodung.per_page',15));

        $jumlahStatus = [
            'terverifikasi' => Laporan::dataMasyarakat()->where('user_id',auth()->id())->where('status','terverifikasi')->count(),
            'menunggu'      => Laporan::dataMasyarakat()->where('user_id',auth()->id())->where('status','menunggu')->count(),
            'ditolak'       => Laporan::dataMasyarakat()->where('user_id',auth()->id())->where('status','ditolak')->count(),
        ];

        return view('laporan.history', compact('laporan','jumlahStatus'));
    }
}
