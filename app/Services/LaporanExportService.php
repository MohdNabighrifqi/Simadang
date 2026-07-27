<?php
// app/Services/LaporanExportService.php
namespace App\Services;

use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExportService
{
    private const TEAL       = '005F73';
    private const TEAL_LIGHT = 'E0F0F4';
    private const AMBER      = 'CA6702';
    private const BORDER     = 'D0D7DE';
    private const ZEBRA      = 'F6FAFB';

    private const WARNA_KONDISI = [
        'Hidup'           => '2196F3',
        'Mati Terdampar'  => '424242',
        'Mati Tertangkap' => 'E65100',
    ];

    /**
     * Bangun workbook. $mode menentukan sheet mana yang disertakan:
     * 'laporan' => hanya sheet Data Laporan, 'statistik' => hanya sheet Statistik,
     * 'lengkap' => kedua sheet (dipakai untuk ekspor umum admin).
     */
    public function buatWorkbook(Collection $laporan, string $mode = 'lengkap'): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        if (in_array($mode, ['laporan', 'lengkap'])) {
            $sheet = $spreadsheet->createSheet();
            $this->isiSheetDataLaporan($sheet, $laporan);
        }

        if (in_array($mode, ['statistik', 'lengkap'])) {
            $sheet = $spreadsheet->createSheet();
            $this->isiSheetStatistik($sheet, $laporan);
        }

        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }

    /**
     * Kumpulan statistik yang sama juga dipakai untuk export PDF, supaya konsisten.
     */
    public function hitungStatistik(Collection $laporan): array
    {
        $terverifikasi = $laporan->where('status', 'terverifikasi');

        $ringkasan = [
            'Total Laporan'               => $laporan->count(),
            'Laporan Terverifikasi'       => $laporan->where('status', 'terverifikasi')->count(),
            'Laporan Menunggu Verifikasi' => $laporan->where('status', 'menunggu')->count(),
            'Laporan Ditolak'             => $laporan->where('status', 'ditolak')->count(),
            'Total Dugong Teramati'       => (int) $terverifikasi->sum('jumlah_dugong'),
        ];

        $bulanIndo = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
        $bulanan = collect(range(1, 12))->map(function ($bulan) use ($laporan, $bulanIndo) {
            return [
                'label'  => $bulanIndo[$bulan - 1],
                'jumlah' => $laporan->filter(fn($l) => $l->tanggal && (int) $l->tanggal->format('n') === $bulan)->count(),
            ];
        });

        $tahunan = $laporan
            ->filter(fn($l) => $l->tanggal)
            ->groupBy(fn($l) => $l->tanggal->format('Y'))
            ->map(fn($grup, $tahun) => ['tahun' => $tahun, 'jumlah' => $grup->count()])
            ->sortKeys()
            ->values();

        $wilayahPrioritas = $terverifikasi
            ->filter(fn($l) => $l->lokasi)
            ->groupBy(fn($l) => $l->lokasi->nama)
            ->map(fn($grup, $nama) => ['lokasi' => $nama, 'jumlah' => $grup->count()])
            ->sortByDesc('jumlah')
            ->values();

        $labelKondisi = [
            'hidup'           => 'Hidup',
            'mati_terdampar'  => 'Mati Terdampar',
            'mati_tertangkap' => 'Mati Tertangkap',
        ];
        $kondisiDugong = collect($labelKondisi)->map(function ($label, $nama) use ($laporan) {
            return [
                'kondisi' => $label,
                'jumlah'  => $laporan->filter(fn($l) => $l->kondisi?->nama === $nama)->count(),
            ];
        })->values();

        return compact('ringkasan', 'bulanan', 'tahunan', 'wilayahPrioritas', 'kondisiDugong');
    }

    private function isiSheetDataLaporan(Worksheet $sheet, Collection $laporan): void
    {
        $sheet->setTitle('Data Laporan');

        $kolom = ['Kode', 'Tanggal', 'Jenis', 'Lokasi', 'Wilayah', 'Latitude', 'Longitude', 'Jumlah Dugong', 'Kondisi Dugong', 'Pelapor', 'Status Verifikasi', 'Foto'];
        $kolomAkhir = 'L';

        // ── Judul dokumen ──
        $sheet->setCellValue('A1', 'Data Laporan Dugong Bintan — SiKoDung');
        $sheet->mergeCells("A1:{$kolomAkhir}1");
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB(self::TEAL);
        $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->getRowDimension(1)->setRowHeight(28);

        $sheet->setCellValue('A2', 'Diunduh pada ' . now()->translatedFormat('d F Y, H:i') . ' WIB — total ' . $laporan->count() . ' laporan');
        $sheet->mergeCells("A2:{$kolomAkhir}2");
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('6B7280'));
        $sheet->getRowDimension(2)->setRowHeight(16);

        $barisHeader = 4;
        $this->tulisBarisHeader($sheet, $barisHeader, 'A', $kolom);

        $baris = $barisHeader + 1;
        $barisMulaiData = $baris;
        foreach ($laporan as $l) {
            $sheet->fromArray([
                $l->kode,
                $l->tanggal?->format('d-m-Y') ?? '—',
                $l->jenis?->nama === 'dugong' ? 'Dugong' : 'Habitat',
                $l->lokasi?->nama ?? '—',
                $l->lokasi?->wilayah ?? '—',
                $l->latitude ?? $l->lokasi?->latitude ?? '—',
                $l->longitude ?? $l->lokasi?->longitude ?? '—',
                $l->jumlah_dugong ?? '—',
                $this->labelKondisi($l->kondisi?->nama),
                $l->nama_pelapor ?? $l->user?->name ?? '—',
                ucfirst($l->status),
            ], null, "A{$baris}");

            $sheet->getRowDimension($baris)->setRowHeight(42);

            if (($baris - $barisMulaiData) % 2 === 1) {
                $sheet->getStyle("A{$baris}:{$kolomAkhir}{$baris}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB(self::ZEBRA);
            }

            $this->tempelFotoLaporan($sheet, $l, $baris, $kolomAkhir);

            $baris++;
        }

        if ($baris === $barisMulaiData) {
            $sheet->setCellValue("A{$baris}", '— Tidak ada data —');
            $sheet->mergeCells("A{$baris}:{$kolomAkhir}{$baris}");
            $baris++;
        }

        $this->beriBorder($sheet, "A{$barisHeader}:{$kolomAkhir}" . ($baris - 1));
        foreach (range('A', 'K') as $kolomHuruf) {
            $sheet->getColumnDimension($kolomHuruf)->setAutoSize(true);
        }
        $sheet->getColumnDimension('L')->setWidth(16);
        $sheet->getStyle("A{$barisMulaiData}:{$kolomAkhir}" . ($baris - 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->freezePane('A' . ($barisHeader + 1));
    }

    private function tempelFotoLaporan(Worksheet $sheet, $laporan, int $baris, string $kolomFoto): void
    {
        $foto = $laporan->relationLoaded('fotos')
            ? $laporan->fotos->first(fn ($f) => !$f->is_video)
            : null;

        $path = $foto ? storage_path('app/public/' . $foto->path) : null;

        if (!$path || !is_file($path)) {
            $sheet->setCellValue("{$kolomFoto}{$baris}", '—');
            $sheet->getStyle("{$kolomFoto}{$baris}")->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
            return;
        }

        $drawing = new Drawing();
        $drawing->setPath($path);
        $drawing->setHeight(54);
        $drawing->setOffsetX(6);
        $drawing->setOffsetY(4);
        $drawing->setCoordinates("{$kolomFoto}{$baris}");
        $drawing->setWorksheet($sheet);
    }

    private function isiSheetStatistik(Worksheet $sheet, Collection $laporan): void
    {
        $sheet->setTitle('Statistik');
        $data = $this->hitungStatistik($laporan);

        // ── Judul dokumen ──
        $sheet->setCellValue('A1', 'Statistik Konservasi Dugong Bintan — SiKoDung');
        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB(self::TEAL);
        $sheet->getStyle('A1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(28);

        $sheet->setCellValue('A2', 'Diunduh pada ' . now()->translatedFormat('d F Y, H:i') . ' WIB');
        $sheet->mergeCells('A2:C2');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('6B7280'));
        $sheet->getRowDimension(2)->setRowHeight(16);

        $baris = 4;
        $namaSheet = $sheet->getTitle();
        $tinggiChart = 15; // jumlah baris yang "diperkirakan" tertutup tinggi grafik
        $kolomTersembunyi = ['N', 'O', 'P']; // menyimpan data sumber grafik, disembunyikan agar tidak redundan dgn visualisasi

        // ── RINGKASAN (tabel saja, tanpa grafik — tidak redundan) ──
        $baris = $this->tulisJudulSeksi($sheet, $baris, 'Ringkasan', 2);
        $this->tulisBarisHeader($sheet, $baris, 'A', ['Metrik', 'Nilai']);
        $baris++;
        $mulaiRingkasan = $baris;
        foreach ($data['ringkasan'] as $label => $nilai) {
            $sheet->fromArray([$label, $nilai], null, "A{$baris}");
            $baris++;
        }
        $this->beriBorder($sheet, "A{$mulaiRingkasan}:B" . ($baris - 1));
        $baris += 2;

        // ── STATISTIK BULANAN — hanya Bar Chart, tabel sumber disembunyikan ──
        $judulBulanan = $baris;
        $baris = $this->tulisJudulSeksi($sheet, $baris, 'Statistik Bulanan', 2);
        [$mulaiBulanan, $akhirBulanan] = $this->tulisDataTersembunyi(
            $sheet, $baris, 'N', ['Bulan', 'Jumlah Laporan'],
            $data['bulanan']->map(fn ($b) => [$b['label'], $b['jumlah']])->all()
        );
        $this->tambahkanChart(
            $sheet, 'Jumlah Laporan per Bulan', DataSeries::TYPE_BARCHART, DataSeries::DIRECTION_COL,
            "'{$namaSheet}'!\$N\${$mulaiBulanan}:\$N\${$akhirBulanan}",
            "'{$namaSheet}'!\$O\${$mulaiBulanan}:\$O\${$akhirBulanan}",
            "E{$judulBulanan}", 'L' . ($judulBulanan + $tinggiChart),
            "'{$namaSheet}'!\$O\$" . $baris,
            warna: self::TEAL
        );
        $baris = $judulBulanan + $tinggiChart + 2;

        // ── STATISTIK TAHUNAN — hanya Line Chart, tabel sumber disembunyikan ──
        $judulTahunan = $baris;
        $baris = $this->tulisJudulSeksi($sheet, $baris, 'Statistik Tahunan', 2);
        [$mulaiTahunan, $akhirTahunan] = $this->tulisDataTersembunyi(
            $sheet, $baris, 'N', ['Tahun', 'Jumlah Laporan'],
            $data['tahunan']->map(fn ($t) => [$t['tahun'], $t['jumlah']])->all()
        );
        $this->tambahkanChart(
            $sheet, 'Tren Laporan per Tahun', DataSeries::TYPE_LINECHART, null,
            "'{$namaSheet}'!\$N\${$mulaiTahunan}:\$N\${$akhirTahunan}",
            "'{$namaSheet}'!\$O\${$mulaiTahunan}:\$O\${$akhirTahunan}",
            "E{$judulTahunan}", 'L' . ($judulTahunan + $tinggiChart),
            "'{$namaSheet}'!\$O\$" . $baris,
            warna: self::TEAL
        );
        $baris = $judulTahunan + $tinggiChart + 2;

        // ── WILAYAH PRIORITAS — hanya Horizontal Bar Chart, tabel sumber disembunyikan ──
        $judulWilayah = $baris;
        $baris = $this->tulisJudulSeksi($sheet, $baris, 'Wilayah Prioritas (berdasarkan laporan terverifikasi)', 3);
        [$mulaiWilayah, $akhirWilayah] = $this->tulisDataTersembunyi(
            $sheet, $baris, 'N', ['Peringkat', 'Lokasi', 'Jumlah'],
            $data['wilayahPrioritas']->map(fn ($w, $i) => [$i + 1, $w['lokasi'], $w['jumlah']])->values()->all()
        );
        $this->tambahkanChart(
            $sheet, 'Peringkat Wilayah Prioritas', DataSeries::TYPE_BARCHART, DataSeries::DIRECTION_BAR,
            "'{$namaSheet}'!\$O\${$mulaiWilayah}:\$O\${$akhirWilayah}",
            "'{$namaSheet}'!\$P\${$mulaiWilayah}:\$P\${$akhirWilayah}",
            "E{$judulWilayah}", 'L' . ($judulWilayah + $tinggiChart),
            "'{$namaSheet}'!\$P\$" . $baris,
            warna: self::AMBER
        );
        $baris = $judulWilayah + $tinggiChart + 2;

        // ── KONDISI DUGONG — tetap tampilkan tabel + Doughnut Chart (dikecualikan) ──
        $judulKondisi = $baris;
        $baris = $this->tulisJudulSeksi($sheet, $baris, 'Kondisi Dugong', 2);
        $this->tulisBarisHeader($sheet, $baris, 'A', ['Kondisi', 'Jumlah']);
        $baris++;
        $mulaiKondisi = $baris;
        foreach ($data['kondisiDugong'] as $k) {
            $sheet->fromArray([$k['kondisi'], $k['jumlah']], null, "A{$baris}");
            $warnaBaris = self::WARNA_KONDISI[$k['kondisi']] ?? null;
            if ($warnaBaris) {
                $sheet->getStyle("A{$baris}")->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color($warnaBaris));
            }
            $baris++;
        }
        $akhirKondisi = $baris - 1;
        $this->beriBorder($sheet, "A{$mulaiKondisi}:B{$akhirKondisi}");
        $this->tambahkanChart(
            $sheet, 'Proporsi Kondisi Dugong', DataSeries::TYPE_DOUGHNUTCHART, null,
            "'{$namaSheet}'!\$A\${$mulaiKondisi}:\$A\${$akhirKondisi}",
            "'{$namaSheet}'!\$B\${$mulaiKondisi}:\$B\${$akhirKondisi}",
            "E{$judulKondisi}", 'L' . ($judulKondisi + $tinggiChart),
            tampilkanLegenda: true,
            tampilkanPersen: true,
            warna: array_map(fn ($k) => self::WARNA_KONDISI[$k['kondisi']] ?? '999999', $data['kondisiDugong']->all())
        );

        foreach (range('A', 'C') as $kolomHuruf) {
            $sheet->getColumnDimension($kolomHuruf)->setAutoSize(true);
        }
        $sheet->getColumnDimension('A')->setWidth(max(14, $sheet->getColumnDimension('A')->getWidth()));
        foreach ($kolomTersembunyi as $kolomHuruf) {
            $sheet->getColumnDimension($kolomHuruf)->setVisible(false);
        }
    }

    /**
     * Tulis data sumber grafik ke kolom tersembunyi (tidak ditampilkan ke pengguna),
     * supaya angka yang sama tidak diulang sebagai tabel di samping visualisasinya.
     *
     * @return array{0:int,1:int} [barisMulai, barisAkhir] data (tanpa header)
     */
    private function tulisDataTersembunyi(Worksheet $sheet, int $barisHeader, string $kolomAwal, array $header, array $baris): array
    {
        $sheet->fromArray($header, null, "{$kolomAwal}{$barisHeader}");
        $b = $barisHeader + 1;
        $mulai = $b;

        if (empty($baris)) {
            $placeholder = array_fill(0, count($header), 0);
            $placeholder[0] = 'Tidak ada data';
            $sheet->fromArray($placeholder, null, "{$kolomAwal}{$b}");
            $b++;
        } else {
            foreach ($baris as $row) {
                $sheet->fromArray($row, null, "{$kolomAwal}{$b}");
                $b++;
            }
        }

        return [$mulai, $b - 1];
    }

    private function tambahkanChart(
        Worksheet $sheet,
        string $judul,
        string $tipe,
        ?string $arah,
        string $rangeKategori,
        string $rangeNilai,
        string $anchorAtas,
        string $anchorBawah,
        ?string $rangeLabelSeri = null,
        bool $tampilkanLegenda = false,
        bool $tampilkanPersen = false,
        string|array|null $warna = null,
    ): void {
        $kategori = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $rangeKategori)];
        $nilaiValues = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $rangeNilai);
        if ($warna !== null) {
            $nilaiValues->setFillColor($warna);
        }
        $nilai = [$nilaiValues];
        $label = $rangeLabelSeri ? [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $rangeLabelSeri)] : [];

        $series = new DataSeries(
            $tipe,
            $tipe === DataSeries::TYPE_LINECHART ? null : DataSeries::GROUPING_CLUSTERED,
            array_keys($nilai),
            $label,
            $kategori,
            $nilai,
            $arah
        );

        $labelLayout = new Layout();
        if ($tampilkanPersen) {
            $labelLayout->setShowPercent(true);
            $labelLayout->setShowCatName(true);
        } else {
            $labelLayout->setShowVal(true);
        }

        $plotArea = new PlotArea($labelLayout, [$series]);
        $legend   = $tampilkanLegenda ? new Legend(Legend::POSITION_RIGHT, null, false) : null;
        $title    = new Title($judul);

        $chart = new Chart(uniqid('chart_'), $title, $legend, $plotArea);
        $chart->setTopLeftPosition($anchorAtas);
        $chart->setBottomRightPosition($anchorBawah);

        $sheet->addChart($chart);
    }

    private function labelKondisi(?string $nama): string
    {
        return match ($nama) {
            'hidup'           => 'Hidup',
            'mati_terdampar'  => 'Mati Terdampar',
            'mati_tertangkap' => 'Mati Tertangkap',
            default           => '—',
        };
    }

    private function tulisJudulSeksi(Worksheet $sheet, int $baris, string $judul, int $lebarKolom = 2): int
    {
        $kolomAkhir = chr(ord('A') + $lebarKolom - 1);
        $range = "A{$baris}:{$kolomAkhir}{$baris}";

        $sheet->setCellValue("A{$baris}", $judul);
        $sheet->mergeCells($range);
        $sheet->getStyle($range)->getFont()->setBold(true)->setSize(12)->setColor(
            new \PhpOffice\PhpSpreadsheet\Style\Color(self::TEAL)
        );
        $sheet->getStyle($range)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB(self::TEAL_LIGHT);
        $sheet->getStyle($range)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle($range)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_MEDIUM)->getColor()->setARGB(self::TEAL);
        $sheet->getRowDimension($baris)->setRowHeight(22);

        return $baris + 1;
    }

    private function tulisBarisHeader(Worksheet $sheet, int $baris, string $kolomAwal, array $judulKolom): void
    {
        $sheet->fromArray($judulKolom, null, "{$kolomAwal}{$baris}");
        $kolomAkhir = chr(ord($kolomAwal) + count($judulKolom) - 1);
        $range = "{$kolomAwal}{$baris}:{$kolomAkhir}{$baris}";

        $sheet->getStyle($range)->getFont()->setBold(true)->setColor(
            new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF')
        );
        $sheet->getStyle($range)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB(self::TEAL);
        $sheet->getStyle($range)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension($baris)->setRowHeight(20);
    }

    private function beriBorder(Worksheet $sheet, string $range): void
    {
        $sheet->getStyle($range)->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)
            ->getColor()->setARGB(self::BORDER);
    }
}
