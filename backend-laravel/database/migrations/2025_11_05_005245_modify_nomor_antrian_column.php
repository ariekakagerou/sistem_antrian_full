<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Tambahkan kolom baru sementara
        Schema::table('antrians', function (Blueprint $table) {
            $table->string('temp_nomor_antrian')->after('nomor_antrian');
        });

        // Salin data dari kolom lama ke kolom baru
        DB::statement("UPDATE antrians SET temp_nomor_antrian = 'A' || nomor_antrian");

        // Hapus kolom lama
        Schema::table('antrians', function (Blueprint $table) {
            $table->dropColumn('nomor_antrian');
        });

        // Ganti nama kolom baru ke nama asli
        Schema::table('antrians', function (Blueprint $table) {
            $table->renameColumn('temp_nomor_antrian', 'nomor_antrian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Kembalikan ke tipe data integer
        Schema::table('antrians', function (Blueprint $table) {
            // Buat kolom sementara untuk integer
            $table->integer('temp_nomor_antrian_int')->after('nomor_antrian');
        });

        // Konversi kembali ke integer (perlu penyesuaian jika format nomor_antrian tidak bisa langsung di-cast)
        DB::statement("UPDATE antrians SET temp_nomor_antrian_int = CAST(SUBSTRING(nomor_antrian, 2) AS INTEGER)");

        // Hapus kolom string
        Schema::table('antrians', function (Blueprint $table) {
            $table->dropColumn('nomor_antrian');
        });

        // Ganti nama kolom integer ke nama asli
        Schema::table('antrians', function (Blueprint $table) {
            $table->renameColumn('temp_nomor_antrian_int', 'nomor_antrian');
        });
    }
};
