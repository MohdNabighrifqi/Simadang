<?php
// app/Http/Controllers/Admin/PermintaanDataController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PermintaanDataDisetujui;
use App\Models\PermintaanData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PermintaanDataController extends Controller
{
    public function index(Request $request)
    {
        $q = PermintaanData::with('user')
            ->orderByRaw("FIELD(status, 'menunggu', 'disetujui', 'ditolak')")
            ->latest();
        if ($request->filled('status')) $q->where('status', $request->status);
        $permintaan = $q->paginate(15);
        return view('admin.permintaan.index', compact('permintaan'));
    }

    public function show(PermintaanData $permintaan)
    {
        return view('admin.permintaan.show', compact('permintaan'));
    }

    public function update(Request $request, PermintaanData $permintaan)
    {
        $request->validate([
            'status'        => ['required', 'in:disetujui,ditolak,menunggu'],
            'catatan_admin' => ['nullable', 'string', 'max:500'],
        ]);

        $statusLama = $permintaan->status;

        $permintaan->update([
            'status'        => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        // Kirim email otomatis jika baru disetujui
        if ($request->status === 'disetujui' && $statusLama !== 'disetujui') {
            try {
                Mail::to($permintaan->email_pemohon)
                    ->send(new PermintaanDataDisetujui($permintaan));

                return redirect()->route('admin.permintaan.index')
                    ->with('success', "Permintaan disetujui. Data CSV telah dikirim ke {$permintaan->email_pemohon}.");

            } catch (\Exception $e) {
                // Log error tapi jangan gagalkan proses
                Log::error('Gagal kirim email permintaan data: ' . $e->getMessage());

                return redirect()->route('admin.permintaan.index')
                    ->with('success', "Permintaan disetujui, tapi email gagal dikirim. Cek konfigurasi MAIL di .env.")
                    ->with('warning', $e->getMessage());
            }
        }

        $pesan = match($request->status) {
            'ditolak'  => 'Permintaan data ditolak.',
            'menunggu' => 'Status permintaan dikembalikan ke menunggu.',
            default    => 'Status permintaan diperbarui.',
        };

        return redirect()->route('admin.permintaan.index')
            ->with('success', $pesan);
    }

    public function destroy(PermintaanData $permintaan)
    {
        $permintaan->delete();
        return redirect()->route('admin.permintaan.index')
            ->with('success', 'Permintaan data berhasil dihapus.');
    }
}
