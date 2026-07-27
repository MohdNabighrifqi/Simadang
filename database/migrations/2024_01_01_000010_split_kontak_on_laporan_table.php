<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->string('no_hp', 30)->nullable()->after('kontak');
            $table->string('email', 100)->nullable()->after('no_hp');
        });

        DB::table('laporan')->whereNotNull('kontak')->update(['no_hp' => DB::raw('kontak')]);

        Schema::table('laporan', function (Blueprint $table) {
            $table->dropColumn('kontak');
        });
    }

    public function down(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->string('kontak', 100)->nullable();
        });

        DB::table('laporan')->whereNotNull('no_hp')->update(['kontak' => DB::raw('no_hp')]);

        Schema::table('laporan', function (Blueprint $table) {
            $table->dropColumn(['no_hp', 'email']);
        });
    }
};
