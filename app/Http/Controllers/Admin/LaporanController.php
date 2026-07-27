<?php
// app/Http/Controllers/Admin/LaporanController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FotoLaporan;
use App\Models\JenisLaporan;
use App\Models\Kondisi;
use App\Models\Laporan;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $q = Laporan::with(['jenis','kondisi','lokasi','user'])
            ->orderByRaw("FIELD(status, 'menunggu', 'terverifikasi', 'ditolak')")
            ->latest('tanggal');

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $q->where(function ($sub) use ($cari) {
                $sub->whereHas('lokasi', fn($l) => $l->where('nama', 'like', "%{$cari}%"))
                    ->orWhere('catatan', 'like', "%{$cari}%")
                    ->orWhere('nama_pelapor', 'like', "%{$cari}%")
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$cari}%"));
            });
        }
        if ($request->filled('status'))         $q->where('status', $request->status);
        if ($request->filled('kondisi'))        $q->whereHas('kondisi', fn($k) => $k->where('nama', $request->kondisi));
        if ($request->filled('tanggal_dari'))   $q->whereDate('tanggal', '>=', $request->tanggal_dari);
        if ($request->filled('tanggal_sampai')) $q->whereDate('tanggal', '<=', $request->tanggal_sampai);

        $laporan = $q->paginate(15);

        return view('admin.laporan.index', compact('laporan'));
    }

    public function show(Laporan $laporan)
    {
        $laporan->load(['jenis','kondisi','lokasi','user','fotos']);
        return view('admin.laporan.show', compact('laporan'));
    }

    public function edit(Laporan $laporan)
    {
        $laporan->load(['jenis','kondisi','lokasi','fotos']);
        return view('admin.laporan.edit', [
            'laporan'         => $laporan,
            'jenisList'       => JenisLaporan::all(),
            'kondisiList'     => Kondisi::all(),
            'lokasiList'      => Lokasi::orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, Laporan $laporan)
    {
        $data = $request->validate([
            'jenis_id'      => ['required','exists:jenis_laporan,id'],
            'kondisi_id'    => ['nullable','exists:kondisi,id'],
            'lokasi_id'     => ['nullable','exists:lokasi,id'],
            'tanggal'       => ['required','date'],
            'waktu'         => ['nullable','date_format:H:i'],
            'jumlah_dugong' => ['nullable','integer','min:1'],
            'deskripsi'     => ['required','string','min:10'],
            'nama_pelapor'  => ['nullable','string','max:100'],
            'no_hp'         => ['nullable','string','max:30'],
            'email'         => ['nullable','email','max:100'],
            'sumber_data'   => ['nullable','string','max:100'],
            'status'        => ['required','in:menunggu,terverifikasi,ditolak'],
            'catatan'       => ['nullable','string','max:500'],
            'latitude'      => ['nullable','numeric','between:-90,90'],
            'longitude'     => ['nullable','numeric','between:-180,180'],
        ]);

        $laporan->update($data);

        return redirect()->route('admin.laporan.index')
            ->with('success', "Laporan {$laporan->kode} berhasil diperbarui.");
    }

    public function verify(Request $request, Laporan $laporan)
    {
        $request->validate([
            'status'  => ['required','in:terverifikasi,ditolak'],
            'catatan' => ['nullable','string','max:500'],
        ]);

        $laporan->update([
            'status'  => $request->status,
            'catatan' => $request->catatan,
        ]);

        return back()->with('success', "Laporan {$laporan->kode} berhasil diperbarui.");
    }

    public function destroy(Laporan $laporan)
    {
        // Hapus foto
        foreach ($laporan->fotos as $foto) {
            Storage::disk('public')->delete($foto->path);
        }
        $kode = $laporan->kode;
        $laporan->delete();

        return redirect()->route('admin.laporan.index')
            ->with('success', "Laporan {$kode} berhasil dihapus.");
    }
}
