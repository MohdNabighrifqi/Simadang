<?php
// app/Http/Controllers/Admin/ExportController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\Lokasi;
use App\Services\LaporanExportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportController extends Controller
{
    public function __construct(protected LaporanExportService $exportSvc) {}

    public function form(Request $request)
    {
        $tipe   = $request->get('tipe', 'lengkap');
        $format = $request->get('format', 'xlsx');

        $laporan = $this->buildQuery($request)->get();

        return view('admin.export.index', [
            'tipe'       => $tipe,
            'format'     => $format,
            'laporan'    => $laporan,
            'lokasiList' => Lokasi::orderBy('nama')->get(),
        ]);
    }

    public function laporan(Request $request)
    {
        $laporan = $this->buildQuery($request)->get();
        $format  = $request->get('format', 'xlsx');
        $tipe    = $request->get('tipe', 'lengkap'); // laporan | statistik | lengkap

        if ($format === 'pdf') {
            return $this->exportPdf($laporan, $tipe);
        }

        return $this->exportExcel($laporan, $tipe);
    }

    private function buildQuery(Request $request): Builder
    {
        $q = Laporan::with(['jenis', 'kondisi', 'lokasi', 'user', 'fotos'])->latest('tanggal');

        if ($request->filled('status'))         $q->where('status', $request->status);
        if ($request->filled('jenis'))          $q->whereHas('jenis', fn($j) => $j->where('nama', $request->jenis));
        if ($request->filled('tanggal'))        $q->whereDate('tanggal', $request->tanggal);
        if ($request->filled('lokasi_id'))      $q->where('lokasi_id', $request->lokasi_id);
        if ($request->filled('periode_dari'))   $q->whereYear('tanggal', '>=', $request->periode_dari);
        if ($request->filled('periode_sampai')) $q->whereYear('tanggal', '<=', $request->periode_sampai);

        return $q;
    }

    private function exportExcel($laporan, string $tipe)
    {
        $filename = 'data-dugong-bintan-' . now()->format('Ymd-His') . '.xlsx';
        $spreadsheet = $this->exportSvc->buatWorkbook($laporan, $tipe);

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->setIncludeCharts(true);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function exportPdf($laporan, string $tipe)
    {
        $judul = match ($tipe) {
            'statistik' => 'Data Statistik Konservasi',
            'lengkap'   => 'Data Lengkap (Laporan & Statistik)',
            default     => 'Data Laporan Dugong',
        };

        $pdf = Pdf::loadView('exports.laporan-pdf', [
            'mode'         => $tipe,
            'judulDokumen' => $judul,
            'laporan'      => $laporan,
            'statistik'    => $this->exportSvc->hitungStatistik($laporan),
        ])->setPaper('a4', 'portrait');

        $filename = 'data-dugong-bintan-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($filename);
    }
}
