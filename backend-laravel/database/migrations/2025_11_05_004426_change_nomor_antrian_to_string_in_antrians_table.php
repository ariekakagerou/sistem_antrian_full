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
            // Ubah tipe data kolom nomor_antrian menjadi string
            $table->string('nomor_antrian')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('antrians', function (Blueprint $table) {
            // Kembalikan ke tipe data integer jika diperlukan
            $table->integer('nomor_antrian')->change();
        });
    }
};
