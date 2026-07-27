<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Perluas enum dulu supaya nilai lama & baru bisa hidup berdampingan sementara data dipindah.
        DB::statement("ALTER TABLE permintaan_data MODIFY COLUMN jenis_data ENUM('semua','dugong','habitat','koordinat','statistik','laporan','lengkap') NOT NULL");

        DB::table('permintaan_data')->where('jenis_data', 'semua')->update(['jenis_data' => 'lengkap']);
        DB::table('permintaan_data')->whereIn('jenis_data', ['dugong', 'habitat', 'koordinat'])->update(['jenis_data' => 'laporan']);

        // Persempit ke 3 nilai final.
        DB::statement("ALTER TABLE permintaan_data MODIFY COLUMN jenis_data ENUM('laporan','statistik','lengkap') NOT NULL DEFAULT 'lengkap'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE permintaan_data MODIFY COLUMN jenis_data ENUM('semua','dugong','habitat','koordinat','statistik','laporan','lengkap') NOT NULL");
        DB::table('permintaan_data')->where('jenis_data', 'lengkap')->update(['jenis_data' => 'semua']);
        DB::table('permintaan_data')->where('jenis_data', 'laporan')->update(['jenis_data' => 'dugong']);
        DB::statement("ALTER TABLE permintaan_data MODIFY COLUMN jenis_data ENUM('semua','dugong','habitat','koordinat','statistik') NOT NULL");
    }
};
