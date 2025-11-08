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
            // Index untuk query yang sering digunakan
            $table->index('loket_id', 'idx_antrians_loket_id');
            $table->index('status', 'idx_antrians_status');
            $table->index('tanggal', 'idx_antrians_tanggal');
            
            // Composite index untuk query filter kombinasi
            $table->index(['loket_id', 'status'], 'idx_antrians_loket_status');
            $table->index(['tanggal', 'status'], 'idx_antrians_tanggal_status');
            $table->index(['loket_id', 'tanggal', 'status'], 'idx_antrians_loket_tanggal_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('antrians', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex('idx_antrians_loket_id');
            $table->dropIndex('idx_antrians_status');
            $table->dropIndex('idx_antrians_tanggal');
            $table->dropIndex('idx_antrians_loket_status');
            $table->dropIndex('idx_antrians_tanggal_status');
            $table->dropIndex('idx_antrians_loket_tanggal_status');
        });
    }
};
