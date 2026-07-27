<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permintaan_data', function (Blueprint $table) {
            $table->enum('format_file', ['xlsx', 'pdf'])->default('xlsx')->after('jenis_data');
        });
    }

    public function down(): void
    {
        Schema::table('permintaan_data', function (Blueprint $table) {
            $table->dropColumn('format_file');
        });
    }
};
