<?php
// app/Mail/PermintaanDataDisetujui.php
namespace App\Mail;

use App\Models\Laporan;
use App\Models\PermintaanData;
use App\Services\LaporanExportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PermintaanDataDisetujui extends Mailable
{
    use Queueable, SerializesModels;

    public string $namaFile;

    public function __construct(
        public PermintaanData $permintaan
    ) {
        $ekstensi = $permintaan->format_file === 'pdf' ? 'pdf' : 'xlsx';
        $this->namaFile = 'data-dugong-bintan-' . $permintaan->id . '.' . $ekstensi;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Simadang] Data Dugong Bintan - Permintaan #' . $this->permintaan->id . ' Disetujui',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.permintaan-disetujui',
            with: ['namaFile' => $this->namaFile],
        );
    }

    public function attachments(): array
    {
        return [
            $this->permintaan->format_file === 'pdf'
                ? Attachment::fromData(fn () => $this->buatPdf(), $this->namaFile)->withMime('application/pdf')
                : Attachment::fromData(fn () => $this->buatExcel(), $this->namaFile)->withMime('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
        ];
    }

    private function ambilLaporan()
    {
        $q = Laporan::with(['jenis', 'kondisi', 'lokasi', 'user', 'fotos'])->terverifikasi();

        if ($this->permintaan->periode_dari) {
            $q->whereYear('tanggal', '>=', $this->permintaan->periode_dari);
        }
        if ($this->permintaan->periode_sampai) {
            $q->whereYear('tanggal', '<=', $this->permintaan->periode_sampai);
        }

        return $q->orderBy('tanggal')->get();
    }

    private function buatExcel(): string
    {
        $svc = app(LaporanExportService::class);
        $spreadsheet = $svc->buatWorkbook($this->ambilLaporan(), $this->permintaan->jenis_data);

        $writer = new Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);
        $temp = tempnam(sys_get_temp_dir(), 'sikodung_xlsx_');
        $writer->save($temp);
        $isi = file_get_contents($temp);
        unlink($temp);

        return $isi;
    }

    private function buatPdf(): string
    {
        $svc = app(LaporanExportService::class);
        $laporan = $this->ambilLaporan();
        $mode = $this->permintaan->jenis_data;

        $judul = match ($mode) {
            'statistik' => 'Data Statistik Konservasi',
            'lengkap'   => 'Data Lengkap (Laporan & Statistik)',
            default     => 'Data Laporan Dugong',
        };

        return Pdf::loadView('exports.laporan-pdf', [
            'mode'         => $mode,
            'judulDokumen' => $judul,
            'laporan'      => $laporan,
            'statistik'    => $svc->hitungStatistik($laporan),
        ])->setPaper('a4', 'portrait')->output();
    }
}
