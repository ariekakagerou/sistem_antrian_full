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
            $table->string('nama_pasien')->after('user_id');
            $table->string('nomor_hp', 20)->after('nama_pasien');
            $table->string('alamat')->nullable()->after('nomor_hp');
            $table->text('keluhan')->nullable()->after('alamat');
            $table->dropColumn(['user_id', 'layanan_id']); // Hapus kolom yang tidak diperlukan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('antrians', function (Blueprint $table) {
            $table->dropColumn(['nama_pasien', 'nomor_hp', 'alamat', 'keluhan']);
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('layanan_id')->nullable()->constrained();
        });
    }
};
