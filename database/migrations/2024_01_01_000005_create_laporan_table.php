<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique()
                  ->comment('Kode unik laporan, contoh: L-202401-0001');

            // Relasi ke users (nullable: laporan bisa dari non-user/anonim)
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Relasi ke tabel referensi
            $table->foreignId('jenis_id')
                  ->constrained('jenis_laporan')
                  ->restrictOnDelete();

            $table->foreignId('kondisi_id')
                  ->nullable()
                  ->constrained('kondisi')
                  ->nullOnDelete()
                  ->comment('NULL jika jenis = habitat');

            $table->foreignId('lokasi_id')
                  ->nullable()
                  ->constrained('lokasi')
                  ->nullOnDelete();

            // Data laporan
            $table->date('tanggal');
            $table->time('waktu')->nullable();
            $table->unsignedSmallInteger('jumlah_dugong')->nullable()
                  ->comment('Jumlah dugong yang terlihat, NULL jika jenis habitat');
            $table->text('deskripsi');

            // Pelapor eksternal (anonim / data penelitian)
            $table->string('nama_pelapor', 100)->nullable()
                  ->comment('Diisi jika bukan user terdaftar');
            $table->string('kontak', 100)->nullable();

            // Sumber data penelitian
            $table->string('sumber_data', 100)->nullable()
                  ->comment('Kuesioner / BPSPL / Referensi penelitian');

            // Verifikasi
            $table->enum('status', ['menunggu', 'terverifikasi', 'ditolak'])
                  ->default('menunggu');
            $table->text('catatan')->nullable()
                  ->comment('Catatan admin saat verifikasi/penolakan');

            $table->timestamps();

            // Index untuk query yang sering dipakai
            $table->index('status');
            $table->index('tanggal');
            $table->index('jenis_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
