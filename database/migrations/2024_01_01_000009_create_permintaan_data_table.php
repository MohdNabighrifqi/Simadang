<?php
// database/migrations/2024_01_01_000009_create_permintaan_data_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('permintaan_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama_pemohon', 100);
            $table->string('email_pemohon', 150);
            $table->string('institusi', 150)->nullable();
            $table->string('tujuan', 255);
            $table->enum('jenis_data', ['semua','dugong','habitat','koordinat','statistik']);
            $table->string('periode_dari', 10)->nullable();
            $table->string('periode_sampai', 10)->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['menunggu','disetujui','ditolak'])->default('menunggu');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('permintaan_data'); }
};
