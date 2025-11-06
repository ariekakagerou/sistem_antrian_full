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
        Schema::create('antrians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('layanan_id')->constrained()->onDelete('cascade');
            $table->foreignId('loket_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('nomor_antrian');
            $table->enum('status', ['menunggu', 'dipanggil', 'dilewati', 'selesai', 'batal'])->default('menunggu');
            $table->date('tanggal');
            $table->timestamp('waktu_panggil')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Index untuk pencarian yang lebih cepat
            $table->index(['tanggal', 'status']);
            $table->index(['layanan_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrians');
    }
};
