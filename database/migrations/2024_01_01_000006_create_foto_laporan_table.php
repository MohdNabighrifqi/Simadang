<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foto_laporan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')
                  ->constrained('laporan')
                  ->cascadeOnDelete()
                  ->comment('Hapus foto jika laporan dihapus');
            $table->string('path', 255)
                  ->comment('Path file relatif dari storage/app/public');
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();

            $table->index('laporan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foto_laporan');
    }
};
