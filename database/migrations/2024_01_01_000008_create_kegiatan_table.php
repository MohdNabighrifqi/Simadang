<?php
// database/migrations/2024_01_01_000008_create_kegiatan_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);
            $table->date('tanggal');
            $table->string('lokasi', 150);
            $table->text('deskripsi');
            $table->string('penyelenggara', 100)->nullable();
            $table->string('foto')->nullable();
            $table->enum('status', ['aktif','selesai','dibatalkan'])->default('aktif');
            $table->foreignId('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamps();
            $table->index('tanggal');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};
