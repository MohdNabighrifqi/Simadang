<?php
// database/seeders/LaporanSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaporanSeeder extends Seeder
{
    public function run(): void
    {
        $lok = DB::table('lokasi')->pluck('id','nama');
        $kon = DB::table('kondisi')->pluck('id','nama');

        // ── Laporan Warga — kondisi_id sudah diisi ───────────
        $warga = [
            ['kode'=>'L-202401-0001','user_id'=>4,'jenis_id'=>1,'kondisi_id'=>$kon['hidup'],          'lokasi_id'=>$lok['Pantai Trikora'], 'tanggal'=>'2024-01-08','waktu'=>'07:30:00','jumlah_dugong'=>1,'deskripsi'=>'Seekor dugong terlihat pagi hari di tepi pantai, berenang perlahan di area padang lamun.','nama_pelapor'=>null,'no_hp'=>null,'sumber_data'=>null,'status'=>'terverifikasi','catatan'=>null],
            ['kode'=>'L-202402-0001','user_id'=>5,'jenis_id'=>2,'kondisi_id'=>null,                   'lokasi_id'=>$lok['Perairan Bintan'],'tanggal'=>'2024-02-14','waktu'=>'10:00:00','jumlah_dugong'=>null,'deskripsi'=>'Kondisi padang lamun sangat baik, kerapatan tinggi dan kejernihan air mendukung fotosintesis.','nama_pelapor'=>null,'no_hp'=>null,'sumber_data'=>null,'status'=>'terverifikasi','catatan'=>null],
            ['kode'=>'L-202403-0001','user_id'=>3,'jenis_id'=>1,'kondisi_id'=>$kon['hidup'],          'lokasi_id'=>$lok['Teluk Sebong'],   'tanggal'=>'2024-03-22','waktu'=>'06:15:00','jumlah_dugong'=>2,'deskripsi'=>'Dua ekor dugong beriringan, diduga induk dan anaknya berdasarkan perbedaan ukuran.','nama_pelapor'=>null,'no_hp'=>'081234567890','sumber_data'=>null,'status'=>'terverifikasi','catatan'=>null],
            ['kode'=>'L-202404-0001','user_id'=>2,'jenis_id'=>1,'kondisi_id'=>$kon['hidup'],          'lokasi_id'=>$lok['Pantai Trikora'], 'tanggal'=>'2024-04-05','waktu'=>'08:45:00','jumlah_dugong'=>1,'deskripsi'=>'Dugong terlihat memakan lamun di kedalaman sekitar 2 meter, aktivitas makan normal.','nama_pelapor'=>null,'no_hp'=>'082345678901','sumber_data'=>null,'status'=>'terverifikasi','catatan'=>null],
            ['kode'=>'L-202404-0002','user_id'=>null,'jenis_id'=>2,'kondisi_id'=>null,                'lokasi_id'=>$lok['Pulau Penyengat'],'tanggal'=>'2024-04-18','waktu'=>'14:00:00','jumlah_dugong'=>null,'deskripsi'=>'Ditemukan sampah plastik cukup banyak di area lamun sisi utara pulau. Perlu pembersihan segera.','nama_pelapor'=>'Nelayan Lokal','no_hp'=>null,'sumber_data'=>null,'status'=>'terverifikasi','catatan'=>null],
            ['kode'=>'L-202405-0001','user_id'=>4,'jenis_id'=>1,'kondisi_id'=>$kon['hidup'],          'lokasi_id'=>$lok['Perairan Bintan'],'tanggal'=>'2024-05-10','waktu'=>'07:00:00','jumlah_dugong'=>3,'deskripsi'=>'Kelompok tiga dugong terlihat pagi hari. Satu individu tampak memiliki luka di bagian sirip.','nama_pelapor'=>null,'no_hp'=>null,'sumber_data'=>null,'status'=>'terverifikasi','catatan'=>null],
            ['kode'=>'L-202406-0001','user_id'=>2,'jenis_id'=>2,'kondisi_id'=>null,                   'lokasi_id'=>$lok['Perairan Dompak'],'tanggal'=>'2024-06-03','waktu'=>'09:30:00','jumlah_dugong'=>null,'deskripsi'=>'Kekeruhan air meningkat pasca hujan deras, padang lamun tertutup lumpur tipis.','nama_pelapor'=>null,'no_hp'=>'082345678901','sumber_data'=>null,'status'=>'terverifikasi','catatan'=>null],
            ['kode'=>'L-202407-0001','user_id'=>5,'jenis_id'=>1,'kondisi_id'=>$kon['hidup'],          'lokasi_id'=>$lok['Teluk Sebong'],   'tanggal'=>'2024-07-14','waktu'=>'08:00:00','jumlah_dugong'=>2,'deskripsi'=>'Dua dugong terlihat pagi hari, kondisi prima, berenang aktif di sekitar padang lamun.','nama_pelapor'=>null,'no_hp'=>null,'sumber_data'=>null,'status'=>'terverifikasi','catatan'=>null],
            ['kode'=>'L-202408-0001','user_id'=>3,'jenis_id'=>1,'kondisi_id'=>$kon['hidup'],          'lokasi_id'=>$lok['Pantai Trikora'], 'tanggal'=>'2024-08-09','waktu'=>'07:20:00','jumlah_dugong'=>1,'deskripsi'=>'Seekor dugong dewasa terlihat sendirian di area padang lamun bagian timur pantai.','nama_pelapor'=>null,'no_hp'=>null,'sumber_data'=>null,'status'=>'terverifikasi','catatan'=>null],
            ['kode'=>'L-202409-0001','user_id'=>2,'jenis_id'=>1,'kondisi_id'=>$kon['hidup'],          'lokasi_id'=>$lok['Perairan Bintan'],'tanggal'=>'2024-09-12','waktu'=>'06:30:00','jumlah_dugong'=>4,'deskripsi'=>'Kelompok empat dugong terlihat pagi hari — penampakan terbanyak tahun ini.','nama_pelapor'=>null,'no_hp'=>'082345678901','sumber_data'=>null,'status'=>'terverifikasi','catatan'=>null],
            ['kode'=>'L-202410-0001','user_id'=>4,'jenis_id'=>1,'kondisi_id'=>$kon['hidup'],          'lokasi_id'=>$lok['Teluk Sebong'],   'tanggal'=>'2024-10-07','waktu'=>'07:45:00','jumlah_dugong'=>1,'deskripsi'=>'Dugong muda terlihat sedang memakan lamun, ukuran sekitar 1,5 meter.','nama_pelapor'=>null,'no_hp'=>null,'sumber_data'=>null,'status'=>'terverifikasi','catatan'=>null],
            ['kode'=>'L-202411-0001','user_id'=>5,'jenis_id'=>2,'kondisi_id'=>null,                   'lokasi_id'=>$lok['Perairan Bintan'],'tanggal'=>'2024-11-14','waktu'=>'09:00:00','jumlah_dugong'=>null,'deskripsi'=>'Kondisi lamun sangat baik pasca musim hujan, pertumbuhan merata di seluruh area.','nama_pelapor'=>null,'no_hp'=>null,'sumber_data'=>null,'status'=>'terverifikasi','catatan'=>null],
            ['kode'=>'L-202412-0001','user_id'=>3,'jenis_id'=>1,'kondisi_id'=>$kon['hidup'],          'lokasi_id'=>$lok['Pantai Trikora'], 'tanggal'=>'2024-12-05','waktu'=>'08:30:00','jumlah_dugong'=>1,'deskripsi'=>'Dugong soliter terlihat pagi hari, kondisi sehat, tidak ada tanda cedera.','nama_pelapor'=>null,'no_hp'=>'081234567890','sumber_data'=>null,'status'=>'terverifikasi','catatan'=>null],
            ['kode'=>'L-202501-0001','user_id'=>3,'jenis_id'=>1,'kondisi_id'=>$kon['hidup'],          'lokasi_id'=>$lok['Pantai Trikora'], 'tanggal'=>'2025-01-15','waktu'=>'09:30:00','jumlah_dugong'=>2,'deskripsi'=>'Terlihat 2 ekor dugong berenang pagi hari di sekitar padang lamun yang masih lebat.','nama_pelapor'=>null,'no_hp'=>'081234567890','sumber_data'=>null,'status'=>'terverifikasi','catatan'=>null],
            ['kode'=>'L-202501-0002','user_id'=>2,'jenis_id'=>2,'kondisi_id'=>null,                   'lokasi_id'=>$lok['Perairan Dompak'],'tanggal'=>'2025-01-12','waktu'=>'14:15:00','jumlah_dugong'=>null,'deskripsi'=>'Kondisi padang lamun terlihat rusak akibat jangkar kapal yang bersandar sembarangan.','nama_pelapor'=>null,'no_hp'=>'082345678901','sumber_data'=>null,'status'=>'terverifikasi','catatan'=>null],
            ['kode'=>'L-202501-0003','user_id'=>null,'jenis_id'=>1,'kondisi_id'=>$kon['hidup'],       'lokasi_id'=>$lok['Perairan Bintan'],'tanggal'=>'2025-01-10','waktu'=>'07:00:00','jumlah_dugong'=>3,'deskripsi'=>'Kelompok tiga ekor dugong terlihat beriringan pada pagi hari, kondisi sehat.','nama_pelapor'=>'Budi Santoso','no_hp'=>null,'sumber_data'=>null,'status'=>'terverifikasi','catatan'=>null],
            ['kode'=>'L-202501-0004','user_id'=>null,'jenis_id'=>1,'kondisi_id'=>$kon['hidup'],       'lokasi_id'=>$lok['Teluk Sebong'],   'tanggal'=>'2025-01-08','waktu'=>'16:00:00','jumlah_dugong'=>1,'deskripsi'=>'Seekor dugong terlihat mendekati perahu nelayan, lalu menjauh ke arah padang lamun.','nama_pelapor'=>'Rina Wati','no_hp'=>'085678901234','sumber_data'=>null,'status'=>'menunggu','catatan'=>null],
            ['kode'=>'L-202501-0005','user_id'=>null,'jenis_id'=>2,'kondisi_id'=>null,                'lokasi_id'=>$lok['Pulau Penyengat'],'tanggal'=>'2025-01-05','waktu'=>'11:00:00','jumlah_dugong'=>null,'deskripsi'=>'Padang lamun masih cukup lebat di sisi barat. Kejernihan air sangat baik, visibilitas 4-5 meter.','nama_pelapor'=>'Dedi Kurniawan','no_hp'=>null,'sumber_data'=>null,'status'=>'menunggu','catatan'=>null],
            ['kode'=>'L-202501-0006','user_id'=>null,'jenis_id'=>1,'kondisi_id'=>$kon['hidup'],       'lokasi_id'=>$lok['Pantai Trikora'], 'tanggal'=>'2025-01-03','waktu'=>'08:45:00','jumlah_dugong'=>1,'deskripsi'=>'Dugong soliter terlihat pagi hari, namun foto yang dikirim tidak jelas.','nama_pelapor'=>'Yuni Astuti','no_hp'=>null,'sumber_data'=>null,'status'=>'ditolak','catatan'=>'Foto bukti tidak memadai untuk verifikasi. Silakan kirim ulang dengan foto lebih jelas.'],
            ['kode'=>'L-202501-0007','user_id'=>4,'jenis_id'=>1,'kondisi_id'=>$kon['hidup'],          'lokasi_id'=>$lok['Perairan Bintan'],'tanggal'=>'2025-01-18','waktu'=>'07:15:00','jumlah_dugong'=>2,'deskripsi'=>'Dua dugong terlihat pagi hari, salah satu tampak lebih kecil kemungkinan anak.','nama_pelapor'=>null,'no_hp'=>null,'sumber_data'=>null,'status'=>'menunggu','catatan'=>null],
            ['kode'=>'L-202501-0008','user_id'=>5,'jenis_id'=>2,'kondisi_id'=>null,                   'lokasi_id'=>$lok['Pantai Trikora'], 'tanggal'=>'2025-01-20','waktu'=>'10:00:00','jumlah_dugong'=>null,'deskripsi'=>'Ditemukan jaring nelayan yang ditinggal di area lamun, berpotensi membahayakan dugong.','nama_pelapor'=>null,'no_hp'=>'085678901234','sumber_data'=>null,'status'=>'terverifikasi','catatan'=>null],
        ];

        $ts = ['created_at'=>'2026-04-24 06:06:47','updated_at'=>'2026-04-24 06:06:47'];
        foreach ($warga as $row) {
            DB::table('laporan')->insert(array_merge($row, $ts));
        }

        // ── Data Penelitian dugong_bintan (58 titik, semua kondisi terisi) ──
        $penelitian = [
            ['kode'=>'DB-2024-001','lok'=>'Busung',           'thn'=>'2024-01-01','k'=>'mati_terdampar', 's'=>'Kuesioner'],
            ['kode'=>'DB-2020-002','lok'=>'Busung',           'thn'=>'2020-01-01','k'=>'hidup',          's'=>'Kuesioner'],
            ['kode'=>'DB-2017-003','lok'=>'Busung',           'thn'=>'2017-01-01','k'=>'hidup',          's'=>'Kuesioner'],
            ['kode'=>'DB-2010-004','lok'=>'Busung',           'thn'=>'2010-01-01','k'=>'hidup',          's'=>'BPSPL'],
            ['kode'=>'DB-2018-005','lok'=>'Busung',           'thn'=>'2018-01-01','k'=>'hidup',          's'=>'BPSPL'],
            ['kode'=>'DB-2017-006','lok'=>'Busung',           'thn'=>'2017-01-01','k'=>'hidup',          's'=>'BPSPL'],
            ['kode'=>'DB-2023-007','lok'=>'Busung',           'thn'=>'2023-01-01','k'=>'hidup',          's'=>'Kuesioner'],
            ['kode'=>'DB-2022-008','lok'=>'Busung',           'thn'=>'2022-01-01','k'=>'hidup',          's'=>'Kuesioner'],
            ['kode'=>'DB-2024-009','lok'=>'Busung',           'thn'=>'2024-01-01','k'=>'mati_terdampar', 's'=>'BPSPL'],
            ['kode'=>'DB-2024-010','lok'=>'Pengudang',        'thn'=>'2024-01-01','k'=>'hidup',          's'=>'Kuesioner'],
            ['kode'=>'DB-2012-011','lok'=>'Pengudang',        'thn'=>'2012-01-01','k'=>'mati_terdampar', 's'=>'Kuesioner'],
            ['kode'=>'DB-2019-012','lok'=>'Pengudang',        'thn'=>'2019-01-01','k'=>'mati_terdampar', 's'=>'Kuesioner'],
            ['kode'=>'DB-2015-013','lok'=>'Pengudang',        'thn'=>'2015-01-01','k'=>'hidup',          's'=>'Zaki M et al 2022'],
            ['kode'=>'DB-2021-014','lok'=>'Pengudang',        'thn'=>'2021-01-01','k'=>'hidup',          's'=>'Kuesioner'],
            ['kode'=>'DB-2008-015','lok'=>'Pengudang',        'thn'=>'2008-01-01','k'=>'hidup',          's'=>'Zaki M et al 2022'],
            ['kode'=>'DB-2020-016','lok'=>'Pangkil Sidi',     'thn'=>'2020-01-01','k'=>'hidup',          's'=>'BPSPL'],
            ['kode'=>'DB-2019-017','lok'=>'Pangkil Sidi',     'thn'=>'2019-01-01','k'=>'hidup',          's'=>'Zaki M et al 2022'],
            ['kode'=>'DB-2019-018','lok'=>'Pangkil Sidi',     'thn'=>'2019-01-01','k'=>'mati_terdampar', 's'=>'Zaki M et al 2022'],
            ['kode'=>'DB-2018-019','lok'=>'Pangkil Sidi',     'thn'=>'2018-01-01','k'=>'mati_tertangkap','s'=>'Kuesioner'],
            ['kode'=>'DB-2018-020','lok'=>'Pangkil Sidi',     'thn'=>'2018-01-01','k'=>'mati_terdampar', 's'=>'BPSPL'],
            ['kode'=>'DB-2022-021','lok'=>'Pangkil Sidi',     'thn'=>'2022-01-01','k'=>'hidup',          's'=>'BPSPL'],
            ['kode'=>'DB-2015-022','lok'=>'Pangkil Sidi',     'thn'=>'2015-01-01','k'=>'hidup',          's'=>'Kuesioner'],
            ['kode'=>'DB-2019-023','lok'=>'Pangkil Sidi',     'thn'=>'2019-01-01','k'=>'mati_tertangkap','s'=>'Kuesioner'],
            ['kode'=>'DB-2024-024','lok'=>'Pangkil',          'thn'=>'2024-01-01','k'=>'hidup',          's'=>'Kuesioner'],
            ['kode'=>'DB-2010-025','lok'=>'Pangkil',          'thn'=>'2010-01-01','k'=>'hidup',          's'=>'Kuesioner'],
            ['kode'=>'DB-2013-026','lok'=>'Pangkil',          'thn'=>'2013-01-01','k'=>'hidup',          's'=>'Kuesioner'],
            ['kode'=>'DB-2024-027','lok'=>'Pangkil',          'thn'=>'2024-01-01','k'=>'hidup',          's'=>'Zaki M et al 2022'],
            ['kode'=>'DB-2011-028','lok'=>'Pangkil',          'thn'=>'2011-01-01','k'=>'hidup',          's'=>'BPSPL'],
            ['kode'=>'DB-2019-029','lok'=>'Pangkil',          'thn'=>'2019-01-01','k'=>'mati_tertangkap','s'=>'Kuesioner'],
            ['kode'=>'DB-2017-030','lok'=>'Penaga',           'thn'=>'2017-01-01','k'=>'mati_terdampar', 's'=>'Kuesioner'],
            ['kode'=>'DB-2017-031','lok'=>'Penaga',           'thn'=>'2017-01-01','k'=>'mati_tertangkap','s'=>'BPSPL'],
            ['kode'=>'DB-2011-032','lok'=>'Penaga',           'thn'=>'2011-01-01','k'=>'hidup',          's'=>'Kuesioner'],
            ['kode'=>'DB-2016-033','lok'=>'Penaga',           'thn'=>'2016-01-01','k'=>'hidup',          's'=>'BPSPL'],
            ['kode'=>'DB-2020-034','lok'=>'Penaga',           'thn'=>'2020-01-01','k'=>'hidup',          's'=>'Kuesioner'],
            ['kode'=>'DB-2020-035','lok'=>'Penaga',           'thn'=>'2020-01-01','k'=>'mati_tertangkap','s'=>'Zaki M et al 2022'],
            ['kode'=>'DB-2011-036','lok'=>'Penaga',           'thn'=>'2011-01-01','k'=>'mati_tertangkap','s'=>'BPSPL'],
            ['kode'=>'DB-2010-037','lok'=>'Pantai Dugong',    'thn'=>'2010-01-01','k'=>'hidup',          's'=>'BPSPL'],
            ['kode'=>'DB-2010-038','lok'=>'Pantai Dugong',    'thn'=>'2010-01-01','k'=>'hidup',          's'=>'BPSPL'],
            ['kode'=>'DB-2017-039','lok'=>'Pantai Dugong',    'thn'=>'2017-01-01','k'=>'hidup',          's'=>'Kuesioner'],
            ['kode'=>'DB-2017-040','lok'=>'Pantai Dugong',    'thn'=>'2017-01-01','k'=>'mati_terdampar', 's'=>'Kuesioner'],
            ['kode'=>'DB-2023-041','lok'=>'Berakit',          'thn'=>'2023-01-01','k'=>'hidup',          's'=>'Kuesioner'],
            ['kode'=>'DB-2009-042','lok'=>'Berakit',          'thn'=>'2009-01-01','k'=>'mati_terdampar', 's'=>'Kuesioner'],
            ['kode'=>'DB-2016-043','lok'=>'Berakit',          'thn'=>'2016-01-01','k'=>'mati_tertangkap','s'=>'BPSPL'],
            ['kode'=>'DB-2014-044','lok'=>'Berakit',          'thn'=>'2014-01-01','k'=>'mati_tertangkap','s'=>'Zaki M et al 2022'],
            ['kode'=>'DB-2016-045','lok'=>'Berakit',          'thn'=>'2016-01-01','k'=>'hidup',          's'=>'Kuesioner'],
            ['kode'=>'DB-2019-046','lok'=>'Berakit',          'thn'=>'2019-01-01','k'=>'mati_tertangkap','s'=>'BPSPL'],
            ['kode'=>'DB-2009-047','lok'=>'Pantai Kelam Pagi','thn'=>'2009-01-01','k'=>'hidup',          's'=>'BPSPL'],
            ['kode'=>'DB-2009-048','lok'=>'Pantai Kelam Pagi','thn'=>'2009-01-01','k'=>'mati_tertangkap','s'=>'Kuesioner'],
            ['kode'=>'DB-2016-049','lok'=>'Pantai Kelam Pagi','thn'=>'2016-01-01','k'=>'mati_tertangkap','s'=>'Kuesioner'],
            ['kode'=>'DB-2017-050','lok'=>'Pantai Kelam Pagi','thn'=>'2017-01-01','k'=>'mati_terdampar', 's'=>'Kuesioner'],
            ['kode'=>'DB-2019-051','lok'=>'Pantai Kelam Pagi','thn'=>'2019-01-01','k'=>'mati_tertangkap','s'=>'Zaki M et al 2022'],
            ['kode'=>'DB-2023-052','lok'=>'Pantai Kelam Pagi','thn'=>'2023-01-01','k'=>'hidup',          's'=>'BPSPL'],
            ['kode'=>'DB-2013-053','lok'=>'Mangkik Kecil',   'thn'=>'2013-01-01','k'=>'hidup',          's'=>'Zaki M et al 2022'],
            ['kode'=>'DB-2008-054','lok'=>'Mangkik Kecil',   'thn'=>'2008-01-01','k'=>'hidup',          's'=>'Zaki M et al 2022'],
            ['kode'=>'DB-2015-055','lok'=>'Mangkik Kecil',   'thn'=>'2015-01-01','k'=>'mati_terdampar', 's'=>'BPSPL'],
            ['kode'=>'DB-2012-056','lok'=>'Mangkik Kecil',   'thn'=>'2012-01-01','k'=>'mati_terdampar', 's'=>'Kuesioner'],
            ['kode'=>'DB-2017-057','lok'=>'Mangkik Kecil',   'thn'=>'2017-01-01','k'=>'mati_terdampar', 's'=>'Kuesioner'],
            ['kode'=>'DB-2023-058','lok'=>'Mangkik Kecil',   'thn'=>'2023-01-01','k'=>'hidup',          's'=>'BPSPL'],
            ['kode'=>'DB-2024-059','lok'=>'Mangkik Kecil',   'thn'=>'2024-01-01','k'=>'mati_terdampar', 's'=>'BPSPL'],
        ];

        $ts2 = ['created_at'=>'2026-06-08 14:33:21','updated_at'=>'2026-06-08 14:33:21'];
        foreach ($penelitian as $p) {
            DB::table('laporan')->insert(array_merge([
                'kode'         => $p['kode'],
                'user_id'      => null,
                'jenis_id'     => 1,
                'kondisi_id'   => $kon[$p['k']],
                'lokasi_id'    => $lok[$p['lok']] ?? null,
                'tanggal'      => $p['thn'],
                'waktu'        => null,
                'jumlah_dugong'=> 1,
                'deskripsi'    => 'Data pengamatan dugong dari sumber penelitian dan kuesioner lapangan.',
                'nama_pelapor' => null,
                'no_hp'       => null,
                'sumber_data'  => $p['s'],
                'status'       => 'terverifikasi',
                'catatan'      => 'Sumber: '.$p['s'],
            ], $ts2));
        }
    }
}
