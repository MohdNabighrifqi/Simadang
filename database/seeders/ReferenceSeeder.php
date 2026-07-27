<?php
// database/seeders/ReferenceSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferenceSeeder extends Seeder
{
    public function run(): void
    {
        // ── Jenis Laporan ──────────────────────────────────────
        DB::table('jenis_laporan')->insert([
            ['id'=>1,'nama'=>'dugong', 'deskripsi'=>'Laporan terkait penampakan, temuan, atau kondisi dugong secara langsung.', 'created_at'=>now(),'updated_at'=>now()],
            ['id'=>2,'nama'=>'habitat','deskripsi'=>'Laporan terkait kondisi habitat dugong seperti padang lamun, kualitas air, dan ancaman lingkungan.','created_at'=>now(),'updated_at'=>now()],
        ]);

        // ── Kondisi ─────────────────────────────────────────────
        DB::table('kondisi')->insert([
            ['id'=>1,'nama'=>'hidup',           'deskripsi'=>'Dugong ditemukan dalam kondisi hidup dan aktif.',                    'created_at'=>now(),'updated_at'=>now()],
            ['id'=>2,'nama'=>'mati_terdampar',  'deskripsi'=>'Dugong ditemukan mati dan terdampar di pesisir.',                   'created_at'=>now(),'updated_at'=>now()],
            ['id'=>3,'nama'=>'mati_tertangkap', 'deskripsi'=>'Dugong mati akibat tertangkap jaring atau alat tangkap nelayan.', 'created_at'=>now(),'updated_at'=>now()],
        ]);

        // ── Lokasi — sesuai SQL asli + tambahan lokasi Bintan ──
        DB::table('lokasi')->insert([
            // Lokasi lama (dari LaporanSeeder warga)
            ['id'=>1, 'nama'=>'Pantai Trikora',     'latitude'=>null,     'longitude'=>null,      'wilayah'=>'Bintan Timur',     'keterangan'=>'Salah satu lokasi utama penampakan dugong di Bintan.',          'created_at'=>now(),'updated_at'=>now()],
            ['id'=>2, 'nama'=>'Perairan Bintan',    'latitude'=>null,     'longitude'=>null,      'wilayah'=>'Bintan',           'keterangan'=>'Perairan umum di sekitar Pulau Bintan.',                        'created_at'=>now(),'updated_at'=>now()],
            ['id'=>3, 'nama'=>'Teluk Sebong',       'latitude'=>null,     'longitude'=>null,      'wilayah'=>'Bintan Utara',     'keterangan'=>'Kawasan teluk dengan padang lamun yang cukup lebat.',           'created_at'=>now(),'updated_at'=>now()],
            ['id'=>4, 'nama'=>'Pulau Penyengat',    'latitude'=>null,     'longitude'=>null,      'wilayah'=>'Tanjungpinang',    'keterangan'=>'Pulau bersejarah di perairan Tanjungpinang.',                   'created_at'=>now(),'updated_at'=>now()],
            ['id'=>5, 'nama'=>'Perairan Dompak',    'latitude'=>null,     'longitude'=>null,      'wilayah'=>'Tanjungpinang',    'keterangan'=>'Perairan di sekitar kawasan Dompak.',                           'created_at'=>now(),'updated_at'=>now()],
            // Lokasi dari dugong_bintan (sudah ada koordinat GPS)
            ['id'=>6, 'nama'=>'Pangkil Sidi',       'latitude'=>0.760720, 'longitude'=>104.563686,'wilayah'=>'Bintan Timur',     'keterangan'=>'Lokasi dengan data koordinat lengkap dari penelitian lapangan.','created_at'=>now(),'updated_at'=>now()],
            ['id'=>7, 'nama'=>'Pangkil',            'latitude'=>0.856508, 'longitude'=>104.322095,'wilayah'=>'Bintan Timur',     'keterangan'=>null,                                                            'created_at'=>now(),'updated_at'=>now()],
            ['id'=>8, 'nama'=>'Penaga',             'latitude'=>1.014194, 'longitude'=>104.400493,'wilayah'=>'Teluk Bintan',     'keterangan'=>null,                                                            'created_at'=>now(),'updated_at'=>now()],
            ['id'=>9, 'nama'=>'Pengudang',          'latitude'=>1.228335, 'longitude'=>104.526900,'wilayah'=>'Teluk Sebong',     'keterangan'=>null,                                                            'created_at'=>now(),'updated_at'=>now()],
            ['id'=>10,'nama'=>'Busung',             'latitude'=>0.971092, 'longitude'=>104.234869,'wilayah'=>'Seri Kuala Lobam', 'keterangan'=>null,                                                            'created_at'=>now(),'updated_at'=>now()],
            // Lokasi tambahan dari dugong_bintan yang belum ada
            ['id'=>11,'nama'=>'Berakit',            'latitude'=>1.222700, 'longitude'=>104.531800,'wilayah'=>'Teluk Sebong',     'keterangan'=>'Perairan Berakit di utara Bintan.',                             'created_at'=>now(),'updated_at'=>now()],
            ['id'=>12,'nama'=>'Pantai Dugong',      'latitude'=>1.144900, 'longitude'=>104.613100,'wilayah'=>'Bintan Timur',     'keterangan'=>'Pantai Dugong di timur laut Bintan.',                           'created_at'=>now(),'updated_at'=>now()],
            ['id'=>13,'nama'=>'Pantai Kelam Pagi',  'latitude'=>0.826900, 'longitude'=>104.485900,'wilayah'=>'Bintan Selatan',   'keterangan'=>'Pantai Kelam Pagi.',                                            'created_at'=>now(),'updated_at'=>now()],
            ['id'=>14,'nama'=>'Mangkik Kecil',      'latitude'=>0.921900, 'longitude'=>104.711600,'wilayah'=>'Bintan Timur',     'keterangan'=>'Perairan Mangkik Kecil di timur Bintan.',                       'created_at'=>now(),'updated_at'=>now()],
            ['id'=>15,'nama'=>'Lainnya',            'latitude'=>null,     'longitude'=>null,      'wilayah'=>null,               'keterangan'=>'Lokasi lainnya yang tidak termasuk dalam daftar.',               'created_at'=>now(),'updated_at'=>now()],
        ]);
    }
}
