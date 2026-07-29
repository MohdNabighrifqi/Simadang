<?php
// app/Services/LaporanService.php
namespace App\Services;

use App\Mail\LaporanBaruMasuk;
use App\Models\FotoLaporan;
use App\Models\JenisLaporan;
use App\Models\Kondisi;
use App\Models\Laporan;
use App\Models\Lokasi;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LaporanService
{
    public function store(array $data, ?UploadedFile $foto = null): Laporan
    {
        $jenis   = JenisLaporan::where('nama', $data['jenis'])->firstOrFail();
        $lokasi  = Lokasi::where('nama', $data['lokasi'])->first();
        $kondisi = null;
        if ($data['jenis'] === 'dugong' && !empty($data['kondisi_dugong'])) {
            $kondisi = Kondisi::where('nama', $data['kondisi_dugong'])->first();
        }

        // Update koordinat lokasi jika user memilih titik di peta
        if ($lokasi && !empty($data['lat_coord']) && !empty($data['lng_coord'])) {
            // Hanya update jika lokasi belum punya koordinat
            if (!$lokasi->latitude) {
                $lokasi->update([
                    'latitude'  => $data['lat_coord'],
                    'longitude' => $data['lng_coord'],
                ]);
            }
        }

        $laporan = Laporan::create([
            'kode'          => $this->generateKode(),
            'user_id'       => auth()->id(),
            'jenis_id'      => $jenis->id,
            'kondisi_id'    => $kondisi?->id,
            'lokasi_id'     => $lokasi?->id,
            'tanggal'       => $data['tanggal'],
            'waktu'         => $data['waktu'] ?? null,
            'jumlah_dugong' => $data['jumlah_dugong'] ?? null,
            'deskripsi'     => $data['deskripsi'],
            'nama_pelapor'  => $data['nama_pelapor'] ?? auth()->user()?->name,
            'no_hp'         => $data['no_hp'],
            'email'         => $data['email'] ?? null,
            'sumber_data'   => 'Laporan Masyarakat',
            'status'        => 'menunggu',
        ]);

        if ($foto) {
            $path = $foto->store('laporan', 'public');
            FotoLaporan::create(['laporan_id' => $laporan->id, 'path' => $path]);
        }

        $this->notifikasiAdminLaporanBaru($laporan);

        return $laporan;
    }

    private function notifikasiAdminLaporanBaru(Laporan $laporan): void
    {
        // Laporan sudah tersimpan di DB sebelum ini dipanggil — kegagalan kirim
        // email (mis. SMTP diblokir/timeout di hosting) tidak boleh menggagalkan
        // pengiriman laporan itu sendiri, jadi errornya ditangkap & dicatat saja.
        $adminEmails = User::where('role', 'admin')->pluck('email');
        foreach ($adminEmails as $email) {
            try {
                Mail::to($email)->send(new LaporanBaruMasuk($laporan));
            } catch (\Throwable $e) {
                Log::error('Gagal mengirim notifikasi laporan baru ke admin', [
                    'laporan_kode' => $laporan->kode,
                    'email'        => $email,
                    'error'        => $e->getMessage(),
                ]);
            }
        }
    }

    public function verifikasi(Laporan $laporan, string $status, ?string $catatan = null): Laporan
    {
        $laporan->update(['status' => $status, 'catatan' => $catatan]);
        return $laporan->fresh();
    }

    public function destroy(Laporan $laporan): void
    {
        foreach ($laporan->fotos as $foto) {
            Storage::disk('public')->delete($foto->path);
        }
        $laporan->delete();
    }

    public function getChartData(): array
    {
        $labels = []; $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $bulan    = now()->subMonths($i);
            $labels[] = $bulan->translatedFormat('M');
            $data[]   = Laporan::terverifikasi()->dataMasyarakat()
                               ->whereMonth('tanggal', $bulan->month)
                               ->whereYear('tanggal',  $bulan->year)
                               ->count();
        }
        return compact('labels', 'data');
    }

    public function getStats(): array
    {
        $hidup      = Kondisi::where('nama','hidup')->first();
        $terdampar  = Kondisi::where('nama','mati_terdampar')->first();
        $tertangkap = Kondisi::where('nama','mati_tertangkap')->first();

        return [
            'total'           => Laporan::terverifikasi()->count(),
            'historis'        => Laporan::terverifikasi()->dataHistoris()->count(),
            'bulan_ini'       => Laporan::dataMasyarakat()
                                        ->whereMonth('tanggal', now()->month)
                                        ->whereYear('tanggal',  now()->year)
                                        ->count(),
            'menunggu'        => Laporan::menunggu()->count(),
            'terverifikasi'   => Laporan::terverifikasi()->count(),
            'ditolak'         => Laporan::where('status','ditolak')->count(),
            'hidup'           => $hidup      ? Laporan::terverifikasi()->where('kondisi_id',$hidup->id)->count()      : 0,
            'mati_terdampar'  => $terdampar  ? Laporan::terverifikasi()->where('kondisi_id',$terdampar->id)->count()  : 0,
            'mati_tertangkap' => $tertangkap ? Laporan::terverifikasi()->where('kondisi_id',$tertangkap->id)->count() : 0,
            'users'           => \App\Models\User::count(),
        ];
    }

    private function generateKode(): string
    {
        do {
            $kode = 'L-' . now()->format('Ym') . '-' . strtoupper(Str::random(4));
        } while (Laporan::where('kode', $kode)->exists());
        return $kode;
    }
}
