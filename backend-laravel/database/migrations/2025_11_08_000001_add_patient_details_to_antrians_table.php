<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('antrians', function (Blueprint $table) {
            $table->string('nik', 16)->nullable()->after('nama_pasien');
            $table->string('no_rekam_medis')->nullable()->after('nik');
            $table->string('jenis_kelamin')->nullable()->after('no_rekam_medis');
            $table->date('tanggal_lahir')->nullable()->after('jenis_kelamin');
            $table->string('poli_tujuan')->nullable()->after('tanggal_lahir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('antrians', function (Blueprint $table) {
            $table->dropColumn(['nik', 'no_rekam_medis', 'jenis_kelamin', 'tanggal_lahir', 'poli_tujuan']);
        });
    }
};
